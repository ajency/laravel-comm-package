<?php
namespace Ajency\Comm\API;

use Ajency\Comm\Models\Error;
use Illuminate\Support\Facades\Auth;

class Provider {


    /*
     * Flow :
     *
     * We know which event to trigger from send notifications function
     * We have the list of current active providers globally
     * We can access the event list and do event.provider.template to get which template to use
     *
     * Provider needs to be active globally first
     * If active, for the event provider needs to be either defined, with a template or not set to false
     *
     * Consider this a magic funtion
     *
     */

    public function getProvidersForEvent($event, $recepients = [])
    {
        $provider_jobs = [];
        $channels = config('aj-comm-channels');
        $events = config('aj-comm-events');
        foreach($channels as $channel => $settings) { //for each channel
            if(!$event['channels'] || ($event['channels'] && in_array($channel,$event['channels']))) { //Keep only channels specified as required for the event

                if($settings['provider'] !== false) { //we check if a provider is not diabled
                    if(isset($events[$event['event']][$settings['provider']])) { //we then check if the provider has the event defined
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
                        $error->setMessage($settings['provider']. 'provider does not have template defined for event '.$event['event']);
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