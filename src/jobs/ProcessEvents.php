<?php

namespace App\Jobs;

use AjComm;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessEvents implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $job_to_process;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($job_to_process)
    {
        $this->job_to_process = $job_to_process;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        AjComm::processNotificationJob($this->job_to_process);
    }
}
