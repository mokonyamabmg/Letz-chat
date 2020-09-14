<?php

use Illuminate\Database\Seeder;

class FriendshipTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $users = App\User::all();

        App\User::all()->each(function($user) use ($users){

            $user_id = $users->random()->id;

            $user->friends()->sync($user_id);
            $user->theFriends()->sync($user_id);
        });
    }
}
