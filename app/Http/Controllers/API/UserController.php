<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use App\User;
use App\Image;
use JWTAuth;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image as InterventionImage;

class UserController extends Controller
{


    public function __contruct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'authUser', 'checkEmailUnique']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();

        return response()->json([
            'users' => $users
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function signup(Request $request)
    {

        $this->validate($request, [
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users',
            'password' => 'required|string|min:7'
        ]);


        if ($request->photo != null)
        {

            $name = time().'.'. explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];

            InterventionImage::make($request->photo)->save(public_path('img/profile/').$name);

            // $request->merge(['photo' => $name]);

            $user = User::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
                'photo' => $name
            ]);

        }else{
            $user = User::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
                'photo' => 'person.png'
            ]);
        }



        $credentials = request(['email', 'password']);


        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Email or Password Is Incorrect'], 401);
        }

        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires' => auth('api')->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);

    }

    public function getAuthUser($id)
    {
        $user = User::findOrFail($id);
        return response()->json([
            'user' => $user
        ]);
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh(){

        return response()->json([
            'token' => auth()->refresh(),
            'token_type' => 'bearer',
            'expires' => auth('api')->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }

    public function signin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string|email|',
            'password' => 'required|string|min:7'
        ]);



        //recieve credentials
        $credentials = request(['email', 'password']);


        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Email or Password Is Incorrect'], 401);
        }

        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires' => auth('api')->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
        // try {
        //     //if it fails means credentials are not valid
        //     if(!$token = JWTAuth::attempt($credentials))
        //     {
        //         //send appropraite error message
        //         return response()->json([
        //             'error' => 'Invalid Credentials'
        //         ], 401);
        //     }

        // }catch (JWTException $e)
        // {
        //     return response()->json([
        //         'error' => 'could not create token!'
        //     ], 500);
        // }

        // return response()->json([
        //     'token' => $token
        // ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        return response()->json([
            'user' => $user
        ]);
    }

    public function checkEmailUnique($email)
    {
        $user = User::where('email', $email)->first();

        return response()->json($user);
    }

    public function profile()
    {
        return auth('api')->user();
    }

    // public function updateProfile(Request $request)
    // {
    //     $user = User::findOrFail($request->user_id);

    //     // $this->validate($request, [
    //     //     'name' => 'required|string|max:191',
    //     //     'email' => 'required|string|email|max:191|unique:users,email,'.$user->id,
    //     //     'password' => 'sometimes|required|string|min:7'
    //     // ]);


    //     $current_photo = $user->photo;


    //     if($request->photo)
    //     {
    //         $name = time().'.'. explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];

    //         Image::make($request->photo)->save(public_path('img/profile/').$name);

    //         $request->merge(['photo' => $name]);

    //         //delete the old photo

    //         $userPhoto = public_path('img/profile/').$current_photo;

    //         if(file_exists($userPhoto))
    //         {
    //             @unlink($userPhoto);
    //         }
    //     }

    //     if(!empty($request->password))
    //     {
    //         $request->merge(['password' => Hash::make($request['password'])]);
    //     }

    //     $user->update($request->all());

    //     return ['message' => "Success"];
    // }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $this->validate($request, [
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users,email,'.$user->id
        ]);

        $user = User::findOrFail($id);


        $current_photo = $user->photo;


        if(strlen($request->photo) > 80)
        {
            $name = time().'.'. explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];

            InterventionImage::make($request->photo)->save(public_path('img/profile/').$name);

            $request->merge(['photo' => $name]);

            //delete the old photo

            $userPhoto = public_path('img/profile/').$current_photo;

            if(file_exists($userPhoto))
            {
                @unlink($userPhoto);
            }

            $user->photo = $request->photo;
        }

        if(!empty($request->password))
        {
            $request->merge(['password' => Hash::make($request['password'])]);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return ['message' => "Success", "user" => $user];
    }

    public function search(Request $request)
    {
        if($search = Request::get('query'))
        {
            $users = User::where(function($query) use ($search) {
                $query->where('name', 'LIKE', "%$search%")
                        ->orWhere('email', 'LIKE', "%$search%")
                        ->orWhere('type', 'LIKE', "%$search%");
            })->paginate(20);

        }else{
            $users = User::latest()->paginate(20);
        }

        return $users;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {


        $user = User::findOrFail($id);

        $this->authorize('isAdmin');

        $user->delete();

        return ['message' => 'user deleted!'];

    }

    public function getUserFriend($id)
    {

        $user = User::findOrFail($id);

        $friends = $user->friends;

        return response()->json([
            'friends' => $friends
        ],200);
    }

    public function addFriend(Request $request)
    {

        $user = User::findOrFail($request->user_id);
        $friend = User::findOrFail($request->friend_id);

        $user->friends()->syncWithoutDetaching($friend);
        $user->theFriends()->syncWithoutDetaching($friend);

        return response()->json([
            'message' => 'friend added successfully'
        ],200);

        // $user->friends()->sync($friend)
    }

}
