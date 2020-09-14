<?php

use Illuminate\Database\Seeder;

class CommentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $posts = App\Blogpost::all();

        $comments = factory(App\Comment::class, 100)->make()->each(function($comment) use ($posts) {
            $comment->blogpost_id = $posts->random()->id;
            $comment->save();
        });
    }
}
