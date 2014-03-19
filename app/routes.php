<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::group(array('prefix' => 'admin', 'namespace' => 'Admin', 'before' => 'admin'), function()
{
	Route::resource('posts', 'PostsController', array('except' => array('show')));
	Route::get('/', 'UsersController@dashboard');
	Route::resource('categories', 'CategoriesController', array('except' => array('show')));
	Route::resource('images', 'ImagesController', array('except' => array('show', 'create', 'edit')));
	Route::resource('albums', 'AlbumsController');
	Route::resource('banners', 'BannersController', array('except' => array('show')));
	Route::resource('polls', 'PollsController');
	Route::resource('answers', 'PollsAnswersController', array('except' => array('show')));
	Route::resource('users', 'UsersController', array('except' => array('show')));
	Route::resource('roles', 'RolesController', array('except' => array('show')));
	Route::get('profile', 'UsersController@profile');
	Route::put('profile', 'UsersController@profile_update');
});
Route::group(array('prefix' => 'api', 'namespace' => 'Api'), function()
{
	Route::get('/', function(){
		return Response::json(array('message' => 'welcome to novavalna.org api'));
	});
	Route::resource('posts', 'PostsController', array('only' => array('index', 'show')));
	Route::resource('categories', 'CategoriesController', array('only' => array('index', 'show')));
	Route::resource('banners', 'BannersController', array('only' => array('index', 'show')));
	Route::resource('polls', 'PollsController', array('only' => array('index', 'show', 'update')));
	Route::resource('albums', 'AlbumsController', array('only' => array('index', 'show', 'update')));
	Route::get( 'user', 'UserController@index');
	Route::post( 'user', 'UserController@store');
	Route::put( 'user', 'UserController@update');
	Route::post( 'user/login', 'UserController@login');
});

Route::group(array(), function()
{
	Route::get('/', 'HomeController@index');
	Route::resource('posts', 'PostsController');
});



// Confide routes
Route::get( 'user/create',                 'UserController@create');
Route::get( 'user/signup-fb',              'UserController@create_fb');
Route::post( 'user/signup-fb',             'UserController@store_fb');
Route::get( 'user/login/fb/callback',      'UserController@login_fb_callback');
Route::post('user',                        'UserController@store');
Route::get( 'user/login',                  'UserController@login');
Route::get( 'user/login-fb',               'UserController@login_fb');
Route::post('user/login',                  'UserController@do_login');
Route::get( 'user/confirm/{code}',         'UserController@confirm');
Route::get( 'user/forgot_password',        'UserController@forgot_password');
Route::post('user/forgot_password',        'UserController@do_forgot_password');
Route::get( 'user/reset_password/{token}', 'UserController@reset_password');
Route::post('user/reset_password',         'UserController@do_reset_password');
Route::get( 'user/logout',                 'UserController@logout');


Route::resource('banners', 'BannersController');

Route::resource('polls', 'PollsController');