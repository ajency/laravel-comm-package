<?php
namespace Ajency\Comm\API;

class Notification {

    public static function send_notification($event, $user_ids){

        //if config file error throw an error
        //forach active channel we get the provider
        //for each provider we check if they have the event
        //if yes we queue the event
        $providers = get_providers_json_config();
        foreach($providers as $provider) {
            $provider = get_provider_json_config($provider);

        }
    }

    function get_providers_json_config() {
        DB::table('users')->delete();
        $json = File::get("/config/providers.json");
        return json_decode($json);
    }

    function get_provider_json_config($provider) {
        DB::table('users')->delete();
        $json = File::get("/config/provider/".$provider.".json");
        return json_decode($json);
    }

}