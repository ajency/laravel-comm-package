<?php
namespace Ajency\Comm\Subscription;

use Ajency\Comm\Models\Error;
use Ajency\Comm\Models\Subscriber_Webpush_Id;
use App\Jobs\processEvents;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Ajency\Comm\Models\Subscriber_Email;


class Helper
{
    public function getInsertableArrayEmail($array, $key = 'email')
    {
        $emails = [];
        $single = false;
        if (is_array($array)) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    if ($v[$key]) { //check for valid emails
                        $email = [];
                        $email['is_primary'] = isset($v['is_primary']) ? $v['is_primary'] : false;
                        $email[$key] = $v[$key];
                        $email['user_id'] = $this->getUserId();
                        $emails[] = $email;
                    }
                } else {
                    if ($array[$key]) {
                        $single = true;
                    }
                }
            }
            if ($single) {
                $array['user_id'] = $this->getUserId();
                $emails[] = $array;
            }
        } else {
            $email = [];
            $email['is_primary'] = false;
            $email[$key] = $array;
            $email['user_id'] = $this->getUserId();
            $emails[] = $email;
        }
        return $emails;
    }


    public function getInsertableArrayMobile($array, $key = 'mobile_no')
    {
        $emails = [];
        $single = false;
        if (is_array($array)) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    if ($v[$key]) { //check for valid emails
                        $email = [];
                        $email['is_primary'] = isset($v['is_primary']) ? $v['is_primary'] : false;
                        $email[$key] = $v[$key];
                        $email['user_id'] = $this->getUserId();
                        $email['country_code'] = $this->getDefaultCountryCode();
                        $emails[] = $email;
                    }
                } else {
                    if ($array[$key]) {
                        $single = true;
                    }
                }
            }
            if ($single) {
                $array['is_primary'] = isset($array['is_primary']) ? $array['is_primary'] : false;
                $array['user_id'] = $this->getUserId();
                $array['country_code'] = $this->getDefaultCountryCode();
                $emails[] = $array;
            }
        } else {
            $email = [];
            $email['is_primary'] = false;
            $email[$key] = $array;
            $email['user_id'] = $this->getUserId();
            $email['country_code'] = $this->getDefaultCountryCode();
            $emails[] = $email;
        }
        return $emails;
    }
}