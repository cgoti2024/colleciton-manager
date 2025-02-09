<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class AfterAuthenticateJob implements ShouldQueue
{
    use Queueable;

    public $shopDomain;

    /**
     * Create a new job instance.
     */
    public function __construct($shopDomain)
    {
        $this->shopDomain = $shopDomain['name'];
        info('AfterAuthenticateJob --- : ' . $this->shopDomain);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        /** @var User $shop */
        $shop = User::whereName($this->shopDomain)->firstOrFail();

        if(empty($shop)) {
            info('Shop not found --- : ' . $this->shopDomain);
            return;
        }

        SyncProductsJob::dispatch($shop);
    }
}
