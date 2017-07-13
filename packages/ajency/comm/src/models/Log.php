<?php
namespace Ajency\Comm\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class Log extends Model
{
    protected $table = 'aj_comm_notification_log'; //can make this a config?

}