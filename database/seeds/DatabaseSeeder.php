<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (App::environment() === 'production') {
            exit('Please don\'t do this!');
        }
        
        Eloquent::unguard();
        
        $tables = [
            'users',
        ];
        
        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }
           
        $this->call(UsersTableSeeder::class);
    }
}
