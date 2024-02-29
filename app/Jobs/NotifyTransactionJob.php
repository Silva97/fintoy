<?php

namespace App\Jobs;

use App\Clients\NotificationClient;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyTransactionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of seconds to wait before retrying the job.
     */
    public int $backoff = 60;

    /**
     * The number of retries to run the job.
     */
    public int $tries = 10;

    protected User $payee;
    protected int $value;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $payee, int $value)
    {
        $this->payee = $payee;
        $this->value = $value;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = app()->make(NotificationClient::class);

        if (!$client->notifyTransaction($this->payee, $this->value)) {
            throw new \Exception("External service error: message not sent!");
        }
    }
}
