<?php

namespace App\Providers;

use App\Repository\CollectionRepository;
use App\Repository\CollectionRepositoryInterface;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Repository\WebhookRepository;
use App\Repository\CustomerRepository;
use Illuminate\Support\ServiceProvider;
use App\Repository\OrderRepositoryInterface;
use App\Repository\ProductRepositoryInterface;
use App\Repository\WebhookRepositoryInterface;
use App\Repository\CustomerRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(CollectionRepositoryInterface::class, CollectionRepository::class);
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
