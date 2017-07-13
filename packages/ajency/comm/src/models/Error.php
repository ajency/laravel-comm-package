<?php
namespace Ajency\Comm\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class Error extends Model
{
    protected $table = 'aj_comm_errors'; //can make this a config?

    private $error_level;
    private $error_type;
    private $is_primary;

}