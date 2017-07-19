<?php
namespace Ajency\Comm\Communication;

use Ajency\Comm\Models\Error;
use Ajency\Comm\Models\Subscriber_Webpush_Id;
use App\Jobs\ProcessEvents;
use Illuminate\Support\Facades\Auth;
use Ajency\Comm\Models\Subscriber_Email;


/*
 * A base class that lets us define Communication methods
 * Communication methods are any methods utilized to send notification via queue process
 */
class Communication
{
    private $notifications;

    /**
     * @return mixed
     */
    public function getNotifications()
    {
        return $this->notifications;
    }

    /**
     * @param mixed $notification
     */
    public function setNotifications(Notification $notifications)
    {
        $this->notifications = $notifications;
    }

    /*
     * Returns 1 if all queued successfully
     */
    public function beginCommunication()
    {


        try {
            $jobs = $this->getProvidersForEvent($this->getNotifications());

            foreach ($jobs as $job) {
                dispatch(new ProcessEvents($job));
            }
            return 1;
        } catch (\Exception $e) {

            //
            throw $e;
        }

    }


    /*
     * Return an array of jobs to be processed
     */
    public function getProvidersForEvent(Notification $notifications)
    {
        $provider_jobs = [];
        $channels = config('aj-comm-channels');
        $events = config('aj-comm-events');
        foreach ($channels as $channel => $settings) { //for each channel
            if (!$notifications->getChannels() || ($notifications->getChannels() && in_array($channel, $notifications->getChannels()))) { //Keep only channels specified as required for the event

                if ($settings['provider'] !== false) { //we check if a provider is not diabled
                    if (isset($events[$notifications->getEvent()][$settings['provider']])) { //we then check if the provider has the event defined
                        $data['channel'] = $channel;
                        $data['event'] = $notifications->getEvent();
                        $data['provider'] = $settings['provider'];
                        $data['template_id'] = $events[$notifications->getEvent()][$settings['provider']];
                        $data['provider_params'] = isset($provider_params) ? $provider_params : null;
                        $data['recepients'] = $notifications->getRecepientIds();
                        $provider_jobs[] = $data;
                    } else { //incase it does not we should log this as a warning to aid the developer
                        $error = new Error();
                        $error->setUserId(Auth::id());
                        $error->setMessage($settings['provider'] . 'provider does not have template defined for event ' . $notifications->getEvent());
                        $error->setLevel(2);
                        $error->setTag('template');
                        $error->save();
                    }
                }
            }
        }
        return $provider_jobs;
    }
}