<?php
namespace Ajency\Comm\Subscription;

use Ajency\Comm\Models\Error;
use Ajency\Comm\Models\Subscriber_Email;
use Ajency\Comm\Models\Subscriber_Mobile_No;
use Ajency\Comm\Models\Subscriber_Webpush_Id;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use League\Flysystem\Exception;

/*
 * A base class that lets us define Subscription methods
 * Subscription methods are any methods utilized to subscribe users to receive notifications
 */
class Subscription
{
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
        return '+91'; //TODO - settings
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
     * Main method to create a subscription
     * Mehod has to be used along with class setter methods
     * Refer Readme.md for input variables
     */
    public function createSubscription()
    {
        $webpushes = [];
        $emails = [];
        $mobiles = [];

        if ($this->webpush_ids !== null) {
            $single = false;
            if (is_array($this->webpush_ids)) {
                foreach ($this->webpush_ids as $k => $v) {
                    if (is_array($v)) {
                        if ($v['provider'] && $v['subscriber_id']) {
                            $email = [];
                            $email['provider'] = $v['provider'];
                            $email['subscriber_id'] = $v['subscriber_id'];
                            $email['user_id'] = isset($v['user_id']) ? $v['user_id'] : $this->getUserId();
                            $webpushes[] = $email;
                        }
                    } else {
                        if ($this->webpush_ids['provider'] && $this->webpush_ids['subscriber_id']) {
                            $single = true;
                        }
                    }
                }
                if ($single) {
                    $this->webpush_ids['user_id'] = isset($this->webpush_ids['user_id']) ? $this->webpush_ids['user_id'] : $this->getUserId();
                    $webpushes[] = $this->webpush_ids;
                }
            } else {

                //Do notthing
            }
        }

        if ($this->mobile_nos !== null) {
            $mobiles = $this->getInsertableArrayMobile($this->mobile_nos);
        }

        if ($this->emails !== null) {
            $emails = $this->getInsertableArrayEmail($this->emails);
        }



        DB::beginTransaction();
        try {
            if ($emails) {
                Subscriber_Email::insert($emails);
            }

            if ($mobiles) {
                Subscriber_Mobile_No::insert($mobiles);
            }

            if ($webpushes) {
                Subscriber_Webpush_Id::insert($webpushes);
            }

            DB::commit();

            return ['success' => true, 'message' => 'Subscription stored for user successfully' , 'data' => ['emails' => $emails , 'mobiles' => $mobiles, 'webpushes' => $webpushes]];
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
            return ['success' => false, 'message' => $e->getMessage(), 'data' => ['emails' => $emails , 'mobiles' => $mobiles, 'webpushes' => $webpushes]];
        }
    }


}