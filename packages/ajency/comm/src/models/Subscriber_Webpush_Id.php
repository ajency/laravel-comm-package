<?php
namespace Ajency\Comm\Models;

use Illuminate\Database\Eloquent\Model;

class Subscriber_Webpush_Id extends Model
{
    protected $table = 'aj_comm_subscriber_webpush_ids'; //can make this a config?
    public $incrementing = false;
}