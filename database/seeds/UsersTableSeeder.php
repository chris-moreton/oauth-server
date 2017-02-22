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
        factory(App\User::class, config('seeding.users'))->create()->each(function ($u) {
            // create a relationship for this new user
            // $u->posts()->save(factory(App\Post::class)->make());
        });

        App\User::create([
            'name' => 'Woooba',
            'email' => 'chris@woooba.com',
            'password' => bcrypt('secret'),
        ]);
    }
}


