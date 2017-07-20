<?php
namespace Ajency\Comm\Models;

use Illuminate\Database\Eloquent\Model;


class Error extends Model
{
    protected $table = 'aj_comm_errors'; //can make this a config?

   /**
     * @return mixed
     */
    public function getLevel()
    {
        return $this->attributes['level'];
    }

    /**
     * @param mixed $level
     */
    public function setLevel($level)
    {
        $this->attributes['level'] = $level;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->attributes['user_id'];
    }

    /**
     * @param mixed $user_id
     */
    public function setUserId($user_id)
    {
        $this->attributes['user_id'] = $user_id;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->attributes['message'];
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->attributes['message'] = $message;
    }

    /**
     * @return mixed
     */
    public function getTag()
    {
        return $this->attributes['tag'];
    }

    /**
     * @param $tag
     * @internal param mixed $message
     */
    public function setTag($tag)
    {
        $this->attributes['tag'] = $tag;
    }
}
