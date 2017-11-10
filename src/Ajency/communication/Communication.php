<?php
namespace Ajency\Comm\Communication;

use Ajency\Comm\Models\Error;
use Ajency\Comm\Models\Subscriber_Webpush_Id;
use App\Jobs\ProcessEvents;
use Illuminate\Support\Facades\Auth;
use Ajency\Comm\Models\Subscriber_Email;
use Carbon\Carbon;

/*
 * A base class that lets us define Communication methods
 *
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
     * @param Notification $notifications
     * @internal param mixed $notification
     */
    public function setNotifications(Notification $notifications)
    {
        $this->notifications = $notifications;
    }

    /*
     * Function that queues notifications by converting an event into notification job
     *
     * @return 1
     */
    public function beginCommunication()
    {
        try {
            $jobs = $this->getIndividualJobs($this->getNotifications());
            $delay = $this->getNotifications()->getDelay();
            foreach ($jobs as $job) {
                dispatch(new ProcessEvents($job))->delay(Carbon::now()->addMinutes($delay));
            }
            return 1;
        } catch (\Exception $e) {

            //
            throw $e;
        }
    }


    /*
     * Magic method that splits jobs based on channels
     * Return an array of jobs to be processed
     *
     * @param Notification $notifications
     *
     * @return array
     */
    public function getIndividualJobs(Notification $notifications)
    {
        $provider_jobs = [];
        $channels = config('aj-comm-channels');
        $events = config('aj-comm-events');
        // dd($notifications->getChannels());
        foreach ($channels as $channel => $settings) { //for each channel
            if (!$notifications->getChannels() || ($notifications->getChannels() && in_array($channel, $notifications->getChannels()))) { //Keep only channels specified as required for the event
                if ($settings['provider'] !== false) { //we check if a provider is not diabled
                    if (isset($events[$notifications->getEvent()][$settings['provider']])) { //we then check if the provider has the event defined
                        $data['channel'] = $channel;
                        $data['event'] = $notifications->getEvent();
                        $data['provider'] = $settings['provider'];
                        $data['template_id'] = $events[$notifications->getEvent()][$settings['provider']];
                        // $data['provider_params'] = $notifications->getProviderParams();
                        $recipients =  $notifications->getRecipients($channel);
                        // dd($recipients);
                        foreach ($recipients as $recipient) {
                            $data['recipients'] = [$recipient];
                            $data['provider_params'] = $settings;
                            $provider_jobs[] = $data;
                        }
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
