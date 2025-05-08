<?php

namespace App\Console\Commands;

use App\Jobs\SyncProductsJob;
use App\Jobs\SyncThemesJob;
use App\Models\User;
use Illuminate\Console\Command;

class AppTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $shop = User::first();

        $this->info('Themes started sync...');
         SyncThemesJob::dispatchSync($shop);

        dd('Data synced successfully..!!!');
    }
}
