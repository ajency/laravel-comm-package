<?php
namespace Ajency\Comm\Subscription;

use Ajency\Comm\Models\Error;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


/*
 * A base class that lets us define Subscription methods
 *
 * Subscription methods are any methods utilized to subscribe users to receive notifications
 */
class Subscription
{
    /*
     * Main method to create a subscription
     * Handles transactions
     *
     * @params array $communication_details
     *
     * @return array
     */
    public function createSubscription($communication_details)
    {
        DB::beginTransaction();
        try {
            foreach ($communication_details as $communication_detail) {
                $communication_detail->save();
            }
            DB::commit();
            return ['success' => true, 'message' => 'Subscription stored for user successfully'];
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
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
