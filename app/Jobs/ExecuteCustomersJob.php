<?php

namespace App\Jobs;

use App;
use App\Models\Webhook;
use App\Repository\WebhookRepositoryInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ExecuteCustomersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $webhookId;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 120;

    /**
     * Create a new job instance.
     *
     * @param string $webhookId
     */
    public function __construct($webhookId)
    {
        set_time_limit(0);
        $this->webhookId = $webhookId;
    }

    /**
     * Execute the job.
     *
     * @return bool
     */
    public function handle()
    {
        /** @var Webhook $webhook */
        $webhook = Webhook::with('shop')->where('is_executed', 0)->find($this->webhookId);
        if (empty($webhook)) {
            return true;
        }

        /** @var WebhookRepositoryInterface $webhookRepo */
        $webhookRepo = App::make(WebhookRepositoryInterface::class);

        $webhookRepo->customerWebhook($webhook);

        $webhook->update(['is_executed' => 1]);

        return true;
    }
}
