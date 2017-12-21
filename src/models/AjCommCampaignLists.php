<?php

namespace Ajency\Comm\Models;

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
    public function getCapaignListsByTypeListNames($type,$list_names = array())
    {
        $result = AjCommCampaignLists::whereIn('list_name',$list_names)
        								->where('type',$type)
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
    public function getCampaignListsByParams($params=array()){

    	$result = AjCommCampaignLists::where($params)
        								->where('type',$type)
        								->get();
        
        return $result;
    }




    public function saveCampaign(){
    	
    }

}
