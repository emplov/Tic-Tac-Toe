<?php

namespace App\Console\Commands;

use App\Events\RoomCreated;
use Illuminate\Console\Command;

class TestEvent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:event';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        RoomCreated::dispatch('Test');

        return 0;
    }
}
