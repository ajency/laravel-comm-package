<?php
namespace Ajency\Comm\Subscription;

use Ajency\Comm\campaigns\AjPepoCampaign;
use Ajency\Comm\Models\Error;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/*
 * A base class that lets us define Subscription methods
 *
 * Subscription methods are any methods utilized to subscribe users to receive notifications
 */
class Subscription
{
    /*
     * Main method to create a subscription
     * Handles transactions
     *
     * @params array $communication_details
     *
     * @return array
     */

    private $attrs = array();

    public function setAttributes($attrs)
    {
        $this->attrs = $attrs;
    }

    public function getAttributes()
    {
        return $this->attrs;
    }

    public function createSubscriptions()
    {

        DB::beginTransaction();
        try {
            // foreach ($communication_details as $communication_detail) {

            //$communication_detail->save();

            //update if subscriber exists or create new
            $comm_attributes = $this->getattributes();
            $email_values    = isset($comm_attributes['values']['email']) ? $comm_attributes['values']['email'] : [];
            $mob_values      = isset($comm_attributes['values']['sms']) ? $comm_attributes['values']['sms'] : [];

            if (is_array($email_values) & count($email_values) > 0) {

                $subscriber_type = 'email';
                $result[]        = $this->createSubscribers($comm_attributes, $subscriber_type, $email_values);

            }

            if (is_array($mob_values) & count($mob_values) > 0) {

                $subscriber_type = 'sms';
                $result[]        = $this->createSubscribers($comm_attributes, $subscriber_type, $mob_values);

            }

            //}
            DB::commit();
            //return ['success' => true, 'message' => 'Subscription stored for user successfully'];
            return $result;

        } catch (\Exception $e) {

            /*
             * TODO - Roadmap
             * Since our db schema takes care of unique key constraints
             * Do we need to make this a setting since numbers get changed all the time?
             * For Mobile we could be more flexible in the rules?
             */
            DB::rollBack();
            $error = new Error();
            $error->setUserId(Auth::id());
            $error->setMessage($e->getMessage());
            $error->setLevel(3);
            $error->setTag('subscription');
            $error->save();
            //Also return a message incase we need to expose an API
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function createSubscribers($comm_attributes, $subscriber_type, $values)
    {

        $result            = [];
        $result_subscriber = [];

        if (is_array($values) & count($values) > 0) {

            $cnt_subscribers = count($values);

            for ($i = 0; $i < $cnt_subscribers; $i++) {

                $subscriber_params['object_id'] = $comm_attributes['object_id'];
                $subscriber_params['value']     = $values[$i];

                switch ($subscriber_type) {
                    case 'sms':
                        $subscriber_params['country_code'] = $values[$i]['country_code'];
                        $subscriber_params['value']        = $values[$i]['value'];
                        break;

                    case 'sms':

                        $subscriber_params['value'] = $values[$i];
                        break;

                }
                $subscriber_params['type']        = $subscriber_type;
                $subscriber_params['object_type'] = $comm_attributes['object_type'];

                $campaign_params = [];
                if (isset($comm_attributes['campaigns'])) {
                    $campaign_params = $comm_attributes['campaigns'];
                }
                $result_subscriber[] = $this->createSubscriber($subscriber_params, $campaign_params);
                // $result              = array_merge($result, $result_subscriber);

            }
        }
        return $result_subscriber;
    }

    public function createSubscriber($subscriber_params, $campaign_params)
    {
        $com_subscriber_email = new \Ajency\Comm\Models\AjCommSubscriberCommunication();
        $com_subscriber_email->setAttributes($subscriber_params);

        $update_attr = array('object_id' => $subscriber_params['object_id'],
            'object_type'                    => $subscriber_params['object_type'],
            'type'                           => $subscriber_params['type'],
            'value'                          => $subscriber_params['value'],
        );

        if ($subscriber_params['type'] == 'sms') {

            $update_attr['country_code'] = $subscriber_params['country_code'];
        }

        /*DB::enableQueryLog();

        DB::listen(
        function ($sql) {
        //  $sql - select * from `ncv_users` where `ncv_users`.`id` = ? limit 1
        //  $bindings - [5]
        //  $time(in milliseconds) - 0.38
        //
        var_dump($sql);
        echo"==============";
        }
        );  */
        $result_campaign = [];

        if (count($campaign_params) > 0 && $subscriber_params['type'] == "email") {

            foreach ($campaign_params as $campaign) {
                $campaign_listings        = $campaign['listing'];
                $campaign_config_listings = $this->getCampaignProviderListingsByListname($campaign['provider'], $campaign_listings);

                if (count($campaign_config_listings) <= 0) {

                    $result_campaign[] = ['success' => false, 'message' => 'There are no lists avaialble with campaign provider type \'' . $campaign['provider'] . '\''];
                } else {

                    $result_campaign[] = $this->subscribeToCampaign($campaign, $subscriber_params['value'], $campaign_config_listings);
                }

            }
        }

        $subscriber_params['campaigns'] = '';

        $result_create = $com_subscriber_email->updateOrCreate($update_attr, $subscriber_params);

        $result = [

             
            'value'       => $subscriber_params['value'],
            'object_id'   => $subscriber_params['object_id'],
            'object_type' => $subscriber_params['object_type'],
            'type'        => $subscriber_params['type'],
            'campaigns' => $result_campaign
        ];

        if ($result_create == true) {
            $result['success'] = true;
            $result['message'] ='Subscription stored for user successfully';
                
            
        } else {

            $result['success']  = false;
            $result['message'] = $result_create; 

        }

        return $result;
    }

    public function subscribeToCampaign($campaign, $email = '', $campaign_config_listings)
    {

        $result_campaign = [];
        switch ($campaign['provider']) {

            case "pepo":

                $result = array();

                $campaign_obj = new AjPepoCampaign();
                //$campaign_listings = $campaign['listing'];

                foreach ($campaign_config_listings as $listing) {

                    $result_cur_campaign = [];

                    $data['email']         = $email;
                    $data['first_name']    = '';
                    $data['last_name']     = '';
                    $config_listing_name[] = $listing->list_name;

                    $subscribe_to_campaign = isset($campaign['subscribe']) ? $campaign['subscribe'] : true;

                    if ($subscribe_to_campaign == true) {
                        $result_campaign_response = $campaign_obj->addToList($listing->list_id, $data);
                        if ($result_campaign_response->subscription_status == "subscribed") {
                            $result_cur_campaign['success'] = true;
                        } else {
                            $result_cur_campaign['success'] = false;
                        }

                    } else {
                        $result_campaign_response = $campaign_obj->removeContactFromList($listing->list_id, $data);
                        if ($result_campaign_response->error == '' || is_null($result_campaign_response->error)) {
                            $result_cur_campaign['success'] = true;
                        } else {
                            $result_cur_campaign['success'] = false;
                        }
                    }
                    $result_cur_campaign['email']   = $email;
                    $result_cur_campaign['list']    = $listing->list_name;
                    $result_cur_campaign['message'] = $result_campaign_response->message;

                }

                $result_campaign[] = $result_cur_campaign;

                $campaign_lists_unavailable = array_diff($campaign['listing'], $config_listing_name);
                foreach ($campaign_lists_unavailable as $unavailable_list) {
                    $result_campaign[] = ['success' => false, 'list' => $unavailable_list, 'email' => $email, 'message' => 'The campaign list id not available'];
                }

                // $pepolists = $campaign_obj->getLists();
                //dd($result);
                return $result_campaign;

                break;

            default:break;
        }
    }

    public function getCampaignProviderListingsByListname($campaign_provider, $campaign_listings)
    {

        $ajcom_campaignlist = new \Ajency\Comm\Models\AjCommCampaignLists();
        $list_result        = $ajcom_campaignlist->getCapaignListsByTypeListNames($campaign_provider, $campaign_listings);

        return $list_result;

        /*
    $campaign_config = config('ajency.comm.aj-comm-campaign');

    if (isset($campaign_config[$campaign_provider])) {

    $campaign_config = $campaign_config[$campaign_provider];

    if (isset($campaign_config['listings'])) {

    return $campaign_config['listings'];

    } else {

    return false;
    }

    } else {
    return false;
    }
     */

    }

}
