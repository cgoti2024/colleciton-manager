<?php

namespace App\Console\Commands;

use App\Jobs\SyncCollectionJob;
use App\Models\User;
use Illuminate\Console\Command;
use function Symfony\Component\String\title;

class test extends Command
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
        $user = User::find(2);
//        $this->createCollection($user);
    }

    protected function createCollection($user) {
        $names = ['test15', 'test2', 'test3', 'test4', 'test11', 'test21', 'test31', 'test41', 'test12', 'test22', 'test32', 'test42'];
        foreach ($names as $n) {
            $input = [
                'custom_collection' => [
                    'title' => $n,
                    'collects' => [
                        ['product_id' => 8873196650740],
                        ['product_id' => 8873196585204],
                        ['product_id' => 8873196749044],
                    ],
                ],
            ];

            $response = $user->api()->rest('POST', '/admin/custom_collections.json', $input);
        }
    }
}
