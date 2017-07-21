<?php
namespace Ajency\Comm\Models;

use Illuminate\Database\Eloquent\Model;

/*
 * Class exposed to allow developer to create an Mobile Subscriber and pass to add subscription method
 */
class MobileSubscriber extends Model
{
    protected $table = 'aj_comm_mobile_nos';

    public function save(array $options = array())
    {
        if (!$this->ref_id) {
            $this->ref_id = Auth::id();
        }
        parent::save($options);
    }
}
