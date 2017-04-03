<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GolfingRecordMigration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'golfingrecord-migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'The command to run when migrating to the new Golfing Record architecture';

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
     * @return mixed
     */
    public function handle()
    {
        //
    }
}
