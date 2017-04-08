<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\User::create([
            'name' => 'Chris',
            'email' => 'chris@example.com',
            'password' => bcrypt('secret'),
        ]);
        
        App\User::create([
            'name' => 'Mary',
            'email' => 'mary@example.com',
            'password' => bcrypt('secret'),
        ]);
        
        App\User::create([
            'id' => 2000000,
            'name' => 'phpunit',
            'email' => 'phpunit@example.com',
            'password' => bcrypt('secret'),
        ]);
        
    }
}


