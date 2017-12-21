<?php

namespace Ajency\Comm\Models;

use Ajency\Comm\campaigns\AjPepoCampaign;
use Ajency\Comm\Models\Error;
use Illuminate\Database\Eloquent\Model;

class AjCommCampaignLists extends Model
{
    protected $fillable = [
        'type', 'list_name', 'list_id',
    ];

    protected $attributes = [];

    /**
     * Gets the capaign lists by type list names.
     *
     * @param      <type>  $type        The type
     * @param      array   $list_names  The list names
     *
     * @return     <type>  The capaign lists by type list names.
     */
    public function getCapaignListsByTypeListNames($type, $list_names = array())
    {
        $result = AjCommCampaignLists::whereIn('list_name', $list_names)
            ->where('type', $type)
            ->get();

        return $result;
    }

    /**
     * Gets the campaign lists by parameters.
     *
     * @param      array   $params  [
     *                                   ['type','=','pepo'],
     *                                   ['list_name','=','job-seeker']
     *                                ]
     *
     * @return     <type>  The campaign lists by parameters.
     */
    public function getCampaignListsByParams($params = array())
    {

        $result = AjCommCampaignLists::where($params)
            ->where('type', $type)
            ->get();

        return $result;
    }

    public function fetchUpdateListByCampaignProvider($campaign_provider = 'pepo')
    {

        try {
            switch ($campaign_provider) {

                case 'pepo':

                    $pepo_campaign = new AjPepoCampaign();
                    $list_result   = $pepo_campaign->getLists();

                    $campaign_lists = json_decode($list_result[0]);

                    foreach ($campaign_lists->data as $campaign_list) {

                        $list_item = array(
                            'list_id'   => $campaign_list->id,
                            'list_name' => $campaign_list->name,
                            'type'      => $campaign_provider,
                        );

                        $list_item_update_attrs = array(
                            'list_id'   => $campaign_list->id,
                            'list_name' => $campaign_list->name,
                            'type'      => $campaign_provider,
                        );

                        $list_items[] = $list_item;

                        AjCommCampaignLists::updateOrCreate($list_item_update_attrs, $list_item);

                    }

                    break;
            }

        } catch (\Exception $e) {

            DB::rollBack();
            $error = new Error();
            $error->setUserId(Auth::id());
            $error->setMessage($e->getMessage());
            $error->setLevel(3);
            $error->setTag('campaign subscription list');
            $error->save();
            //Also return a message incase we need to expose an API
            return ['success' => false, 'message' => $e->getMessage()];
        }

    }

}
