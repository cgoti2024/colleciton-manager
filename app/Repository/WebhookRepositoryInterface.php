<?php

namespace App\Repository;

use App\Models\Webhook;
interface WebhookRepositoryInterface
{
    /**
     * @param Webhook $webhook
     * @return mixed
     */
    public function orderWebhook(Webhook $webhook);

    /**
     * @param Webhook $webhook
     * @return mixed
     */
    public function productWebhook($webhook);

        /**
     * @param Webhook $webhook
     * @return mixed
     */
    public function customerWebhook($webhook);
}