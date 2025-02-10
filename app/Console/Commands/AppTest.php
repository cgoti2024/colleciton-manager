<?php

namespace App\Console\Commands;

use App\Jobs\SyncCollectionJob;
use App\Jobs\SyncProductsJob;
use App\Models\User;
use Illuminate\Console\Command;

class AppTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app-test';

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
        $shop = User::find(2);

        $this->info('Products started sync...');
         SyncCollectionJob::dispatchSync($shop);

        dd('Data synced successfully..!!!');
    }
}
