<?php
namespace Ajency\Comm\API;

use Ajency\Comm\Models\Error;
use Ajency\Comm\Models\Subscriber_Webpush_Id;
use App\Jobs\processEvents;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Ajency\Comm\Models\Subscriber_Email;


/*
 * A base class that lets us define Communication methods
 * Communication methods are any methods utilized to send notification via queue process
 */
class Communication
{
    private $event;

    private $recepient_ids;

    /**
     * @return mixed
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param mixed $event
     */
    public function setEvent($event)
    {
        $this->event = $event;
    }

    /**
     * @return mixed
     */
    public function getRecepientIds()
    {
        return $this->recepient_ids;
    }

    /**
     * @param mixed $recepient_ids
     */
    public function setRecepientIds($recepient_ids)
    {
        $this->recepient_ids = $recepient_ids;
    }


    public function beginCommunication()
    {

        $jobs = $this->getProvidersForEvent($this->event, $this->recepient_ids);
        foreach ($jobs as $job) {
            dispatch(new processEvents($job));
        }
    }


    public function getProvidersForEvent($event, $recepients = [])
    {
        $provider_jobs = [];
        $channels = config('aj-comm-channels');
        $events = config('aj-comm-events');
        foreach ($channels as $channel => $settings) { //for each channel
            if (!$event['channels'] || ($event['channels'] && in_array($channel, $event['channels']))) { //Keep only channels specified as required for the event

                if ($settings['provider'] !== false) { //we check if a provider is not diabled
                    if (isset($events[$event['event']][$settings['provider']])) { //we then check if the provider has the event defined
                        $data['channel'] = $channel;
                        $data['event'] = $event['event'];
                        $data['provider'] = $settings['provider'];
                        $data['template_id'] = $events[$event['event']][$settings['provider']];
                        $data['provider_params'] = isset($event['provider_params']) ? $event['provider_params'] : null;
                        $data['recepients'] = $recepients;
                        $provider_jobs[] = $data;
                    } else { //incase it does not we should log this as a warning to aid the developer
                        $error = new Error();
                        $error->setUserId(Auth::id());
                        $error->setMessage($settings['provider'] . 'provider does not have template defined for event ' . $event['event']);
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