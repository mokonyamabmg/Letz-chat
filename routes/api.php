<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {

    return $request->user();
});


Route::apiResources([
    'user' => 'API\UserController',
    'posts' => 'API\BlogpostController',
    'comments' => 'API\CommentController'
]);

Route::get('profile', 'API\UserController@profile')->middleware('api');
Route::put('profile', 'API\UserController@updateProfile')->middleware('api');
Route::get('findUser', 'API\UserController@search')->middleware('api');
Route::get('findPost', 'API\BlogpostController@search')->middleware('api');
Route::post('/signup', 'API\UserController@signup')->middleware('api');
Route::post('/signin', 'API\UserController@signin')->middleware('api');
Route::get('friend/{id}', 'API\UserController@getUserFriend')->middleware('api');
Route::post('addFriend', 'API\UserController@addFriend')->middleware('api');
Route::get('authUser/{id}', 'API\UserController@getAuthUser')->name('authUser')->middleware('api');
Route::post('likePost', 'API\LikeController@likePost')->middleware('api');
Route::get('checkEmail/{user}', 'API\UserController@checkEmailUnique')->name('checkEmailUnique');

// Client ID: 1
// Client secret: OEyrRJ3Ldm3GFw4w3XZFN8hMMZ2Ez9Q5fJhi9tcG
// Password grant client created successfully.
// Client ID: 2
// Client secret: DCTPwJzzkoif0mHADiee8G7rahmvQjJT6DC1cVCQ
