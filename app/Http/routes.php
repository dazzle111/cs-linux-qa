<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'PostsController@index');
Route::get('/test', 'PostsController@test');
Route::get('discussions/search','PostsController@search');
Route::get('discussions/manage','PostsController@manage');
Route::post('discussions/top', 'PostsController@top');
Route::resource('discussions','PostsController');


Route::get('discussion/{id}/comment/{id1}/edit', 'CommentsController@editComment');

Route::patch('comments/{id}/comment/{id1}', 'CommentsController@changeComment');
Route::resource('comment','CommentsController');
Route::post('/follow', 'FollowsController@follow');
Route::post('comment/api/create', 'CommentsController@check');
Route::post('accept', 'CommentsController@accept');

Route::get('/user/register', 'UsersController@register');
Route::get('/user/profile', 'UsersController@profile');
Route::get('/user/login', 'UsersController@login');
Route::get('/user/avatar', 'UsersController@avatar');
Route::post('/user/register', 'UsersController@store');
Route::post('/user/login', 'UsersController@signin');
Route::get('/user/password', 'UsersController@password');
Route::post('/user/password', 'UsersController@changePassword');
Route::get('/user/lost', 'UsersController@lost');
Route::get('/user/name', 'UsersController@username');
Route::post('/user/profile','UsersController@update');

Route::post('/thumbs', 'LikesController@likes');

Route::post('/avatar', 'UsersController@changeAvatar');
Route::post('/crop/api', 'UsersController@cropAvatar');
Route::post('/post/upload', 'PostsController@upload');

Route::get('notification', 'NotificationController@index');
Route::post('notification/mymessage', 'NotificationController@read');

Route::get('/logout', 'UsersController@logout');

