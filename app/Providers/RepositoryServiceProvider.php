<?php

namespace App\Providers;

use App\Repository\ProductRepository;
use App\Repository\WebhookRepository;
use Illuminate\Support\ServiceProvider;
use App\Repository\ProductRepositoryInterface;
use App\Repository\WebhookRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(WebhookRepositoryInterface::class, WebhookRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
