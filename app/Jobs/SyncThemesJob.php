<?php

// app/Jobs/SyncThemesJob.php
namespace App\Jobs;

use App;
use App\Models\User;
use App\Repository\ThemeRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncThemesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $shop;

    public function __construct($shop)
    {
        $this->shop = $shop;
    }

    public function handle(): void
    {
        /** @var ThemeRepositoryInterface $themeRepo */
        $themeRepo = App::make(ThemeRepositoryInterface::class);
        try {
            $response = $this->getShopifyThemes($this->shop);

            if (isset($response['body']['themes'])) {
                $themes = $response['body']['themes'];

                foreach ($themes as $theme) {
                    $themeRepo->store($theme->toArray(), $this->shop->id);
                }
            }
        } catch (\Exception $exception) {
            info('Error while syncing themes: ' . $exception->getMessage());
        }
    }

    protected function getShopifyThemes($user)
    {
        $response = $user->api()->rest('GET', '/admin/themes.json');

        if ($response['status'] === 429) {
            info('Too many requests; retrying...');
            sleep(1);
            return $this->getShopifyThemes($user);
        }

        return $response;
    }
}
