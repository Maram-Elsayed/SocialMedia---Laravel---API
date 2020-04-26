<?php

use Illuminate\Http\Request;


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Authentication Routes
Route::group([
    'middleware' => 'api',
], function ($router) {

    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');

});

//User Routes
Route::resource('users', 'UsersController');
Route::post('register', 'UsersController@register');

//Post Routes
Route::post('posts/{id}', 'PostsController@update');
Route::get('posts/user/{id}','PostsController@viewUserPosts');
Route::resource('posts','PostsController');

//Comment To Post Routes
Route::resource('comments', 'CommentsController');
Route::post('comments/{id}', 'CommentsController@update');

//Reactions Routes
Route::resource('reactions', 'ReactionsController');

//Search Routes
Route::get('search/users', 'SearchController@search_users');
Route::get('search/posts', 'SearchController@search_posts');


//React To Post Routes
Route::resource('react', 'PostReactionsController');

//Friend Request Routes
Route::resource('friendrequests', 'FriendRequestsController');
Route::post('friendrequests/{id}','FriendRequestsController@accept_decline_request');

//Friends Routes
Route::resource('friends', 'FriendsController');
Route::get('friends/user/{id}','FriendsController@viewUserFriends');

//Chat Routes
Route::resource('chats', 'ChatsController');
Route::post('chats/{id}', 'ChatsController@remove_name');


//Messages Routes
Route::resource('chat', 'MessagesController');
Route::post('chat/{id}','MessagesController@send_message');

//Chat Participants Routes
Route::resource('chat/participants', 'ChatParticipantsController');
Route::post('chat/add_participants/{id}', 'ChatParticipantsController@add_participants');

