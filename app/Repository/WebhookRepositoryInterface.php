<?php

namespace App\Repository;

interface WebhookRepositoryInterface
{
    public function productWebhook($webhook);

    public function collectionWebhook($webhook);
}
