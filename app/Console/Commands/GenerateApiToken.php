<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;

class GenerateApiToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate-api-token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an API token for trusted first-party applications to use';

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
        $user = User::find(1);
        
        $token = $user->createToken('Token Name', ['admin'])->accessToken;
        
        echo PHP_EOL;
        echo $token . PHP_EOL;
    }
}
