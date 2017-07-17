<?php

namespace App\Jobs;

use Ajency\Comm\API\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;


class processEvents implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $processEventsArray;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($processEventsArray)
    {
        $this->processEventsArray = $processEventsArray;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $notification = $this->processEventsArray;
        Notification::provider_split($notification);
    }
}
