<?php
namespace Ajency\Comm\Subscription;

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
        $this->attrs =  $attrs;
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
                $mob_values      = isset($comm_attributes['values']['sms']) ? $comm_attributes['values']['sms']:[];

                if (is_array($email_values) & count($email_values) > 0) {

                    $subscriber_type = 'email';
                    $result          = $this->createSubscribers($comm_attributes, $subscriber_type, $email_values);

                }

                if (is_array($mob_values) & count($mob_values) > 0) {

                    $subscriber_type = 'sms';
                    $result          = $this->createSubscribers($comm_attributes, $subscriber_type, $mob_values);

                }

            //}
            DB::commit();
            return ['success' => true, 'message' => 'Subscription stored for user successfully'];
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

        $result = [];

        if (is_array($values) & count($values) > 0) {

            $cnt_subscribers = count($values);

            for ($i = 0; $i < $cnt_subscribers; $i++) {

                $subscriber_params['object_id'] = $comm_attributes['object_id'];
                $subscriber_params['value']     = $values[$i];

                switch ($subscriber_type) {
                    case 'sms' :
                        $subscriber_params['country_code'] = $values[$i]['country_code'];
                        $subscriber_params['value']        = $values[$i]['value'];
                        break;

                    case 'sms':

                        $subscriber_params['value'] = $values[$i];
                        break;

                }
                $subscriber_params['type']        = $subscriber_type;
                $subscriber_params['object_type'] = $comm_attributes['object_type'];
                $result[]                         = $this->createSubscriber($subscriber_params);

            }
        }
        return $result;
    }

    public function createSubscriber($subscriber_params)
    {
        $com_subscriber_email = new \Ajency\Comm\Models\AjCommUserCommunication();
        $com_subscriber_email->setAttributes($subscriber_params);

        $update_attr = array('object_id' => $subscriber_params['object_id'],
            'object_type'                    => $subscriber_params['object_type'],
            'type'                           => $subscriber_params['type'],
            'value'                          => $subscriber_params['value'],
        );

        if ($subscriber_params['type'] == 'sms') {

            $update_attr['country_code'] = $subscriber_params['country_code'];
        }

        $result = $com_subscriber_email->updateOrCreate($update_attr, $subscriber_params);

        return $result;
    }
}
