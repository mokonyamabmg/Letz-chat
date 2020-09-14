<?php

namespace App\Http\Controllers\API;

use JWTAuth;
use App\User;
use App\Image;
use Response;
use App\Blogpost;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image as InterventionImage;
use Illuminate\Support\Facades\Storage;

class BlogpostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $blogposts = Blogpost::orderBy('created_at', 'desc')->get();

        $mostCommented = Blogpost::mostCommented()->take(5)->get();

        $postData = $blogposts->map(function($post) {


            $postComments = $post->comments;
            $postLikes = $post->likes;
            $postUser = $post->user;

            $postimage = Image::where('blogpost_id', $post->id)->first();

            return [
                "post_id" => $post->id,
                "title" => $post->title,
                "content" => $post->content,
                "photo" => $postimage ? $postimage->path : null,
                "created" => $post->created_at,
                "commentsCount" => $postComments->count(),
                "likesCount" => $postLikes->count(),
                "comments" => $postComments,
                "likes" => $postLikes,
                "user" => $postUser
            ];

        });


        return response()->json([
            'postData' => $postData,
            'mostCommented' => $mostCommented
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if(! $user = JWTAuth::parseToken()->authenticate())
        {
            return response()->json(['message' => 'User Not Found', 404]);
        }

        $this->validate($request, [
            'title' => 'required|string|max:191',
            'content' => 'required'
        ]);

        $user = JWTAuth::parseToken()->toUser();

        if ($request->photo)
        {

            $name = time().'.'. explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];

            InterventionImage::make($request->photo)->save(public_path('img/post/').$name);

            $request->merge(['photo' => $name]);


            $blogpost = new Blogpost();

            $blogpost->title = $request->title;
            $blogpost->content = $request->content;
            $blogpost->user_id = $request->user_id;
            $blogpost->save();

            $image = new Image();
            $image->path = $request->photo;
            $image->blogpost_id = $blogpost->id;

            $blogpost->image()->save($image);
        }else{
            $blogpost = new Blogpost();

            $blogpost->title = $request->title;
            $blogpost->content = $request->content;
            $blogpost->user_id = $request->user_id;
            $blogpost->save();
        }


        // $blogPost->image()->save(Image::create(['path', $path]));

        return response()->json(['blogpost' => $blogpost], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $blogpost = Blogpost::findOrfail($id);

        $postComments = $blogpost->comments;
        $postLikes = $blogpost->likes;
        $postUser = $blogpost->user;

        $photo = Image::where('blogpost_id', $blogpost->id)->first();


        return response()->json([
            "post" => $blogpost,
            "photo" => $photo,
            "comments" => $postComments->count(),
            "likes" => $postLikes->count(),
            "user" => $postUser
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $this->validate($request, [
            'title' => 'required|string|max:191',
            'content' => 'required'
        ]);


        $blogpost = Blogpost::findOrFail($id);

        $this->authorize('update-post', $blogpost);

        $blogpost->title = $request['title'];
        $blogpost->content = $request['content'];
        $blogpost->photo = $request['photo'];
        $blogpost->save();

        return ['message' => "Success"];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $blogpost = Blogpost::findOrFail($id);

        // $this->authorize('delete-post', $blogpost);

        // $this->authorize('isAdmin');

        $blogpost->delete();

        return ['message' => 'blogpost deleted!'];
    }


    // public function search(Request $request)
    // {

    //     $usersPosts;

    //     if($search = Request::get('query'))
    //     {
    //         $users = User::where(function($query) use ($search) {
    //             $query->where('name', 'LIKE', "%$search%");
    //             })->paginate(20);

    //         if($users)
    //         {
    //             $users->map(function($user){
    //                 $posts = Blogpost::where('user_id', $user->id)->get();

    //                 $usersPosts->push($posts);

    //             });
    //         }

    //     }else{
    //         // $usersPosts = User::latest()->paginate(40);
    //         // $usersPosts->push($posts);
    //     }

    //     return $usersPosts;
    // }
}
