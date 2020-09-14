<?php

use Illuminate\Database\Seeder;

class BlogTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = App\User::all();

        $posts = factory (App\Blogpost::class, 50)->make()->each(function($post) use ($users) {
            $post->user_id = $users->random()->id;
            $post->save();

        });

    }
}
