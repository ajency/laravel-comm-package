<?php
namespace Ajency\Comm\API;

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
     */

    public function getProvidersForEvent($event, $recepients = [])
    {
        $provider_jobs = [];
        $events = $this->getConfig('events');
        $channels = $this->getConfig('channels');

        foreach($channels as $channel => $settings) { //for each channel
            if(!$event['channels'] || ($event['channels'] && in_array($channel,$event['channels']))) { //Keep only channels required for the event
                if($settings['provider'] !== false) { //we check if a provider is set
                    if(isset($events[$event['event']][$settings['provider']])) { //we then check if the provider has the event defined
                        $data['channel'] = $channel;
                        $data['event'] = $event['event'];
                        $data['provider'] = $settings['provider'];
                        $data['template_id'] = $events[$event['event']][$settings['provider']];
                        $data['provider_params'] = isset($event['provider_params']) ? $event['provider_params'] : null;
                        $data['recepients'] = $recepients;
                        $provider_jobs[] = $data;
                    } else {
                        //TODO log as a warning that provider template is not defined

                    }
                }
            }
        }
        return $provider_jobs;
    }

    public function getConfig($config) {

        //TODO set config
        return json_decode(file_get_contents(base_path().'/config/aj-comm/'.$config.'.json'), true);
    }
}