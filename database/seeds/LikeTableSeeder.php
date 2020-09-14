<?php

use Illuminate\Database\Seeder;

class LikeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $posts = App\Blogpost::all();

        $comments = App\Comment::all();

        $users = App\User::all();

        $likes = factory(App\Like::class, 1500)->make()->each(function($like) use ($posts, $comments, $users) {
            $like->blogpost_id = $posts->random()->id;
            $like->comment_id = $comments->random()->id;
            $like->user_id= $users->random()->id;

            $like->save();
        });
    }
}
