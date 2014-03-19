<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
	//
});


App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{
	if (Auth::guest()) return Redirect::guest('user/login');
});
Route::filter('admin', function()
{
	if (Auth::guest()) return Redirect::guest('user/login');
	if (Auth::user()->roles()->count() < 0) return Redirect::guest('/');

});
// Posts
Route::filter('create-posts', function($request, $response, $type='')
{
	$type = Type::find($type);
	if (!Entrust::hasRole('Editor In Chief') && !Entrust::can($type->permission))
    	return Redirect::to('admin')->with('message', 'Нямате необходимите права, за да добавяте Публикации в '.$type->title.'!');
    
});
Route::filter('edit-posts', function()
{
	$id = Request::segment(3);
	$post = Post::find($id);
	$url = 'admin/posts?type='.$post->type->slug;
	if (! Entrust::hasRole('Editor In Chief') && $post->public == true) // Checks the current user
    {
    	if (Request::ajax()) {
			return Response::json(array('success' => false, 'message' => 'Нямате необходимите права!'));
		}
        return Redirect::to($url)->with('message', 'Моля свържете се с Главния редактор, да направи промените!');
    }
});

// Polls
Route::filter('create-polls', function()
{
	if (!Entrust::hasRole('Editor In Chief') && !Entrust::can('create_polls')) // Checks the current user
    {
        return Redirect::to('admin')->with('message', 'Нямате необходимите права, за да добавяте Анкети!');
    }
});
Route::filter('edit-polls', function()
{
	$id = Request::segment(3);
	$poll = Poll::find($id);
	$url = 'admin/polls';
	if (! Entrust::hasRole('Editor In Chief') && $poll->public == true) // Checks the current user
    {
    	if (Request::ajax()) {
			return Response::json(array('success' => false, 'message' => 'Нямате необходимите права!'));
		}
        return Redirect::to($url)->with('message', 'Моля свържете се с Главния редактор, да направи промените!');
    }
});

// Photos
Route::filter('edit-images', function()
{
	$id = Request::segment(3);
	$image = Image::find($id);
	if (! Entrust::hasRole('Editor In Chief') && $image->public == true) // Checks the current user
    {
		return Response::json(array('success'=> false, 'message' => 'Нямате необходимите права!'), 200);
    }
});
Route::filter('create-albums', function()
{
	if (!Entrust::hasRole('Editor In Chief') && !Entrust::can('create_photos')) // Checks the current user
    {
        return Redirect::to('admin')->with('message', 'Нямате необходимите права, за да добавяте Албуми!');
    }
});
Route::filter('edit-albums', function()
{
	if (! Entrust::hasRole('Editor In Chief')) // Checks the current user
    {
		return Response::json(array('success'=> false, 'message' => 'Нямате необходимите права!'), 200);
    }
});
// Categories 
Route::filter('create-categories', function()
{
	if (!Entrust::hasRole('Editor In Chief') && !Entrust::can('create_categories')) // Checks the current user
    {
        return Redirect::to('admin')->with('message', 'Нямате необходимите права, за да добавяте Категории!');
    }
});

Route::filter('edit-categories', function()
{
	$id = Request::segment(3);
	$category = Category::find($id);
	$url = 'admin/categories';
	if (! Entrust::hasRole('Editor In Chief') && $category->public == true) // Checks the current user
    {
    	if (Request::ajax()) {
			return Response::json(array('success' => false, 'message' => 'Нямате необходимите права!'));
		}
        return Redirect::to($url)->with('message', 'Моля свържете се с Главния редактор, да направи промените!');
    }
});

// Banners 
Route::filter('create-banners', function()
{
	if (!Entrust::hasRole('Editor In Chief') && !Entrust::can('create_categories')) // Checks the current user
    {
        return Redirect::to('admin')->with('message', 'Нямате необходимите права, за да добавяте Банери!');
    }
});
Route::filter('edit-banners', function()
{
	$id = Request::segment(3);
	$banner = Banner::find($id);
	$url = 'admin/banners';
	if (! Entrust::hasRole('Editor In Chief') && $banner->public == true) // Checks the current user
    {
    	if (Request::ajax()) {
			return Response::json(array('success' => false, 'message' => 'Нямате необходимите права!'));
		}
        return Redirect::to($url)->with('message', 'Моля свържете се с Главния редактор, да направи промените!');
    }
});
Route::filter('manage-users', function()
{
	if (!Entrust::can('manage_users')) // Checks the current user
    {
        return Redirect::to('admin')->with('message', 'Нямате необходимите права, за да управлявате Потребители!');
    }
});

/*
Entrust::routeNeedsPermission( 'admin/posts*', 'manage_posts', Redirect::to('user/login'));
Entrust::routeNeedsPermission( 'admin/categories*', 'manage_categories', Redirect::to('/') );
Entrust::routeNeedsPermission( 'admin/albums*', 'manage_photos', Redirect::to('/') );
Entrust::routeNeedsPermission( 'admin/banners*', 'manage_banners', Redirect::to('/') );
Entrust::routeNeedsPermission( 'admin/polls*', 'manage_polls', Redirect::to('/') );
Entrust::routeNeedsPermission( 'admin/users*', 'manage_users', Redirect::to('/') );
Entrust::routeNeedsPermission( 'admin/roles*', 'manage_users', Redirect::to('/') );
*/
Route::filter('auth.basic', function()
{
	return Auth::basic();
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() != Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});