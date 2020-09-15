<?php

use Illuminate\Support\Facades\Route;

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


Route::get('/threads', 'ThreadController@index');
Route::get('/threads/create', 'ThreadController@create');
Route::get('/threads/{channel}/{thread}', 'ThreadController@show');
Route::delete('/threads/{channel}/{thread}', 'ThreadController@destroy');
Route::post('/threads', 'ThreadController@store');
Route::get('/threads/{channel}', 'ThreadController@index');

Route::post('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionController@store')
    ->middleware('auth');
Route::delete('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionController@destroy')
    ->middleware('auth');

Route::post('/replies/{reply}/favorites', 'FavoriteController@store');
Route::delete('/replies/{reply}/favorites', 'FavoriteController@destroy');

Route::get('/threads/{channel}/{thread}/replies', 'ReplyController@index');
Route::post('/threads/{channel}/{thread}/replies', 'ReplyController@store');
Route::put('/replies/{reply}', 'ReplyController@update');
Route::delete('/replies/{reply}', 'ReplyController@destroy');

Route::get('/profile/{user}', 'ProfileController@show');

Route::get('/notifications', 'UserNotificationController@index');
Route::get('/notifications/{notifications}/read', 'UserNotificationController@markAsRead');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::post('api/users/{user}/avatar', 'Api\UserAvatarController@store')
    ->middleware('auth');
