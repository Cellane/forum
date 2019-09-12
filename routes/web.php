<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/threads', 'ThreadController@index')->name('threads');
Route::get('/threads/create', 'ThreadController@create');
Route::get('/threads/{channel}/{thread}', 'ThreadController@show');
Route::post('/threads', 'ThreadController@store');
Route::get('/threads/{channel}', 'ThreadController@index');
Route::delete('/threads/{channel}/{thread}', 'ThreadController@destroy');

Route::post('/locked-threads/{thread}', 'LockedThreadController@store')->name('locked-threads.store');
Route::delete('/locked-threads/{thread}', 'LockedThreadController@destroy')->name('locked-threads.destroy');

Route::post('/replies/{reply}/favorites', 'FavoriteController@store');
Route::delete('/replies/{reply}/favorites', 'FavoriteController@destroy');

Route::get('/threads/{channel}/{thread}/replies', 'ReplyController@index');
Route::post('/threads/{channel}/{thread}/replies', 'ReplyController@store');
Route::patch('/replies/{reply}', 'ReplyController@update');
Route::delete('/replies/{reply}', 'ReplyController@destroy')->name('replies.destroy');

Route::post('/replies/{reply}/best', 'BestReplyController@store')->name('best-replies.store');

Route::post('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionController@store');
Route::delete('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionController@destroy');

Route::get('/profiles/{user}', 'ProfileController@show')->name('profile');

Route::get('/profiles/{user}/notifications', 'UserNotificationController@index');
Route::delete('/profiles/{user}/notifications/{notification}', 'UserNotificationController@destroy');

Route::get('/register/confirm', 'Auth\RegisterConfirmationController@index')->name('register.confirm');

Route::get('/api/users', 'Api\UserController@index');
Route::post('/api/users/{user}/avatar', 'Api\UserAvatarController@store')->name('avatar');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
