<?php

namespace App\Http\Controllers\API;

use App\Like;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LikeController extends Controller
{
    public function likeComment(Request $request)
    {

        $like = new Like();

        $like->blogpost_id = null;
        $like->comment_id = $request->comment_id;
        $like->user_id = $request->user_id;
        $like->save();

        return response()->json([
            'message' => 'comment liked'
        ], 200);

    }

    public function likePost(Request $request)
    {

        $likesCount = Like::where('blogpost_id', $request->blogpost_id)->where('user_id', $request->user_id)->count();

        if($likesCount < 1)
        {
            $like = new Like();

        $like->blogpost_id = $request->blogpost_id;
        $like->comment_id = null;
        $like->user_id = $request->user_id;
        $like->save();

        return response()->json([
            'message' => 'post liked'
        ], 200);

        }else
        {
            return response()->json([
                'message' => 'Cant like post more than once'
            ], 200);
        }

    }

//     public function disLikePost(Request $request)
//     {
//         $like = Like::where('blogpost_id', $request->blogpost_id)->where('user_id', $request->user_id)->first();

//         if($like)
//         {
//             $like->delete();

//             return response()->json(['message' => 'post disliked']);
//         }else{
//             return response()->json(['message' => 'no like found']);
//     }
// }

}
