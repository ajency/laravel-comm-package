<?php
namespace Ajency\Comm\API;

use Ajency\Comm\Models\Subscriber_Email;
use Ajency\Comm\Models\Subscriber_Mobile_No;
use Ajency\Comm\Models\Subscriber_Webpush_Id;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use League\Flysystem\Exception;

class Subscription {

    private $user_id;
    private $emails;
    private $mobile_nos;
    private $webpush_ids;
    private $default_country_code;

    /**
     * @return mixed
     */
    public function getDefaultCountryCode()
    {
        return '+91';
    }

    /**
     * @param mixed $default_country_code
     */
    public function setDefaultCountryCode($default_country_code)
    {
        $this->default_country_code = $default_country_code;
    }



    /**
     * @return mixed
     */
    public function getUserId()
    {
        return isset($this->user_id) ? $this->user_id : Auth::id();
    }

    /**
     * @param mixed $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * @return mixed
     */
    public function getEmails()
    {
        return $this->emails;
    }

    /**
     * @param mixed $emails
     */
    public function setEmails($emails)
    {
        $this->emails = $emails;
    }

    /**
     * @return mixed
     */
    public function getMobileNos()
    {
        return $this->mobile_nos;
    }

    /**
     * @param mixed $mobile_nos
     */
    public function setMobileNos($mobile_nos)
    {
        $this->mobile_nos = $mobile_nos;
    }

    /**
     * @return mixed
     */
    public function getWebpushIds()
    {
        return $this->webpush_ids;
    }

    /**
     * @param mixed $webpush_ids
     */
    public function setWebpushIds($webpush_ids)
    {
        $this->webpush_ids = $webpush_ids;
    }


    /*
     * TODO :
     * Add subscription, make an API
     * UI with socialite login
     * Push crew integration on login
     * Push crew sending provider add
     * On succssful subscription in application send a push notification
     */
    public function create_or_update_subscription() {

        if($this->webpush_ids !== null) {

            $webpushes = [];
            $emails = [];
            $mobiles = [];
            $single = false;
            if(is_array($this->webpush_ids)) {
                foreach($this->webpush_ids as $k => $v) {
                    if(is_array($v)) {
                        if ($v['provider'] && $v['subscriber_id'])
                        {
                            $email = [];
                            $email['provider'] = $v['provider'];
                            $email['subscriber_id'] = $v['subscriber_id'];
                            $email['user_id'] = isset($v['user_id']) ? $v['user_id'] : $this->getUserId();
                            $webpushes[] = $email;
                        }
                    } else {
                        if ($this->webpush_ids['provider'] && $this->webpush_ids['subscriber_id'])
                        {
                            $single = true;
                        }
                    }
                }
                if($single) {
                    $this->webpush_ids['user_id'] = isset($this->webpush_ids['user_id']) ? $this->webpush_ids['user_id'] : $this->getUserId();
                    $webpushes[] = $this->webpush_ids;
                }
            } else {

                //Do notthing
            }
        }

        if($this->mobile_nos !== null) {
            $mobiles = $this->get_insertable_array_mobile($this->mobile_nos);
        }

        if($this->emails !== null) {
            $emails = $this->get_insertable_array_email($this->emails);
        }



        DB::beginTransaction();
        try{

            if($emails) {
                Subscriber_Email::insert($emails);
            }

            if($mobiles) {
                Subscriber_Mobile_No::insert($mobiles);
            }

            if($webpushes) {
                Subscriber_Webpush_Id::insert($webpushes);
            }

            DB::commit();

            //TODO - add success string to lang file
            return ['success' => true, 'message' => 'Subscription stored for user successfully'];
        }catch(\Exception $e){
            //TODO - ?
            DB::rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function get_insertable_array_email($array, $key = 'email') {

        $emails = [];
        $single = false;
        if(is_array($array)) {
            foreach($array as $k => $v){
                if(is_array($v)){
                    if ($v[$key]) { //check for valid emails
                        $email = [];
                        $email['is_primary'] = isset($v['is_primary']) ? $v['is_primary'] : false;
                        $email[$key] = $v[$key];
                        $email['user_id'] = isset($v['user_id']) ? $v['user_id'] : $this->getUserId();
                        $emails[] = $email;
                    }
                } else {
                    if ($array[$key]) {
                        $single = true;
                    }
                }
            }
            if($single) {
                $array['user_id'] = isset($array['user_id']) ? $array['user_id'] : $this->getUserId();
                $emails[] = $array;
            }
        } else {
            $email = [];
            $email['is_primary'] = false;
            $email[$key] = $array;
            $email['user_id'] = isset($email['user_id']) ? $email['user_id'] : $this->getUserId();
            $emails[] = $email;
        }
        return $emails;
    }


    public function get_insertable_array_mobile($array, $key = 'mobile_no') {

        $emails = [];
        $single = false;
        if(is_array($array)) {
            foreach($array as $k => $v){
                if(is_array($v)){
                    if ($v[$key]) { //check for valid emails
                        $email = [];
                        $email['is_primary'] = isset($v['is_primary']) ? $v['is_primary'] : false;
                        $email[$key] = $v[$key];
                        $email['user_id'] = isset($v['user_id']) ? $v['user_id'] : $this->getUserId();
                        $email['country_code'] = $this->getDefaultCountryCode();
                        $emails[] = $email;
                    }
                } else {
                    if ($array[$key]) {
                        $single = true;
                    }
                }
            }
            if($single) {
                $array['is_primary'] = isset($array['is_primary']) ? $array['is_primary'] : false;
                $array['user_id'] = isset($array['user_id']) ? $array['user_id'] : $this->getUserId();
                $array['country_code'] = $this->getDefaultCountryCode();
                $emails[] = $array;
            }
        } else {
            $email = [];
            $email['is_primary'] = false;
            $email[$key] = $array;
            $email['user_id'] = isset($email['user_id']) ? $email['user_id'] : $this->getUserId();
            $email['country_code'] = $this->getDefaultCountryCode();
            $emails[] = $email;
        }
        return $emails;
    }
}