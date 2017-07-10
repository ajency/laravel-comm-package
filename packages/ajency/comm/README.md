# Demo Laravel Package
Installation

in composer.json
add "Ajency\\Comm\\": "packages/ajency/comm/src" under autoload -> psr-4
add "packages/ajency/comm/src/migrations" under autoload -> classmap

in config/app.php
add 'Ajency\Comm\CommServiceProvider' under providers

Run cmds
composer dump-autoload
php artisan vendor:publish --tag=migrations
php artisan migrate

Set config
your users table
your users foreign key ref



php artisan make:migration create_aj_comm_apps --create=aj_comm_apps
php artisan make:migration create_aj_comm_app_events --create=aj_comm_app_events
php artisan make:migration create_aj_comm_app_event_templates --create=aj_comm_app_event_templates
php artisan make:migration create_aj_comm_app_settings --create=aj_comm_app_settings
php artisan make:migration create_aj_comm_channels --create=aj_comm_channels
php artisan make:migration create_aj_comm_permissions --create=aj_comm_permissions
php artisan make:migration create_aj_comm_providers --create=aj_comm_providers
php artisan make:migration create_aj_comm_subscribers --create=aj_comm_subscribers
php artisan make:migration create_aj_comm_subscriber_emails --create=aj_comm_subscriber_emails
php artisan make:migration create_aj_comm_subscriber_mobile_nos --create=aj_comm_subscriber_mobile_nos
php artisan make:migration create_aj_comm_subscriber_webpush_ids --create=aj_comm_subscriber_webpush_ids
php artisan make:migration create_aj_comm_devloper_app_permissions --create=aj_comm_devloper_app_permissions