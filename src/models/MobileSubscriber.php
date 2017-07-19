<?php
namespace Ajency\Comm\Models;

use Illuminate\Database\Eloquent\Model;

class MobileSubscriber extends Model
{
    protected $table = 'aj_comm_mobile_nos'; //can make this a config?

    public function save(array $options = array())
    {
        if(!$this->ref_id)
        {
            $this->ref_id = Auth::id();
        }
        parent::save($options);
    }

}
