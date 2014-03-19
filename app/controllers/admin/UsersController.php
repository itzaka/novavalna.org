<?php
namespace Admin;
use BaseController;
use User, Role, Entrust, Post, Image, Category, Type, Banner, Poll, DB, Request, Hash, Auth, View, Response, Validator, Input, Redirect, App;

class usersController extends BaseController {


	public function __construct() {
		$this->beforeFilter('manage-users', array('only' => array('index', 'show', 'edit', 'update', 'destroy')));
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

		$users = User::get();
		if (Request::ajax()) {
			return Response::json(array('aaData' => $users->toArray()));
		}
		
        return View::make('admin.users.index', compact('users'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function profile()
	{
		$user = Auth::user();
        return View::make('admin.users.profile', compact('user'));
	}
	public function dashboard()
	{
		$user = Auth::user();
		$data = array();
		$data['user'] = $user;
		if(Entrust::hasRole('Editor In Chief')) {
			$data['posts_total'] = Post::count();
			$about = Type::whereSlug('about')->first();
			$data['about_unpublished'] = $about->posts()->wherePublic(false)->count();
			$data['about_total'] = $about->posts()->count();
			$news = Type::whereSlug('news')->first();
			$data['news_unpublished'] = $news->posts()->wherePublic(false)->count();
			$data['news_total'] = $news->posts()->count();
			$events = Type::whereSlug('events')->first();
			$data['events_unpublished'] = $events->posts()->wherePublic(false)->count();
			$data['events_total'] = $events->posts()->count();
			$activities = Type::whereSlug('activities')->first();
			$data['activities_unpublished'] = $activities->posts()->wherePublic(false)->count();
			$data['activities_total'] = $activities->posts()->count();
			$summer_camp = Type::whereSlug('summer-camp')->first();
			$data['summer_camp_unpublished'] = $summer_camp->posts()->wherePublic(false)->count();
			$data['summer_camp_total'] = $summer_camp->posts()->count();
			$vlog = Type::whereSlug('vlog')->first();
			$data['vlog_unpublished'] = $vlog->posts()->wherePublic(false)->count();
			$data['vlog_total'] = $vlog->posts()->count();
			$data['categories_unpublished'] = Category::wherePublic(false)->count();
			$data['categories_total'] = Category::count();
			$data['images'] = Image::with('album')->wherePublic(false)->get();
			$data['images_unpublished'] = $data['images']->count();
			$data['images_total'] = Image::count();
			$data['banners_unpublished'] = Banner::wherePublic(false)->count();
			$data['banners_total'] = Banner::count();
			$data['polls_unpublished'] = Poll::wherePublic(false)->count();
			$data['polls_total'] = Poll::count();
			$data['users'] = User::orderBy('created_at', 'desc')->take(3)->get();
			$data['users_total'] = User::count();
			return View::make('admin.users.editordash')->with($data);
		}
		$data['posts_total'] = Post::count();
		$about = Type::whereSlug('about')->first();
		$data['about_total'] = $about->posts()->whereUserId($user->id)->wherePublic(true)->count();
		$news = Type::whereSlug('news')->first();
		$data['news_total'] = $news->posts()->whereUserId($user->id)->wherePublic(true)->count();
		$events = Type::whereSlug('events')->first();
		$data['events_total'] = $events->posts()->whereUserId($user->id)->wherePublic(true)->count();
		$activities = Type::whereSlug('activities')->first();
		$data['activities_total'] = $activities->posts()->whereUserId($user->id)->wherePublic(true)->count();
		$summer_camp = Type::whereSlug('summer-camp')->first();
		$data['summer_camp_total'] = $summer_camp->posts()->whereUserId($user->id)->wherePublic(true)->count();
		$vlog = Type::whereSlug('vlog')->first();
		$data['vlog_total'] = $vlog->posts()->whereUserId($user->id)->wherePublic(true)->count();
		$data['banners_total'] = Banner::whereUserId($user->id)->wherePublic(true)->count();
		$data['polls_total'] = Poll::whereUserId($user->id)->wherePublic(true)->count();
		$data['users'] = User::orderBy('created_at', 'desc')->take(3)->get();
		$data['users_total'] = User::count();
        return View::make('admin.users.dashboard')->with($data);
	}
	public function profile_update()
	{
		$user = Auth::user();
		$validator = Validator::make(Input::all(), array('first_name' => 'required', 'last_name' => 'required', 'username' => 'required|unique:users,username,'.$user->id, 'email' => 'required|email|unique:users,email,'.$user->id));
		if ($validator->fails())
			return Redirect::to('admin/profile')->withErrors($validator)->withInput();
 		
		if (Input::get('newpassword') != null) {
 			if (Auth::validate(array('password' => Input::get('password'))))
 				$user->password = Input::get('newpassword');
 			else
			return Redirect::to('admin/profile')->withErrors(array('Въвели сте грешна парола'))->withInput();
		}
		
 		$user->email = Input::get('email'); 
 		$user->first_name = Input::get('first_name'); 
 		$user->last_name = Input::get('last_name'); 

        $user->updateUniques(); 

        return Redirect::to('admin/profile');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$user = user::find($id);
        return View::make('admin.users.show', compact('user'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$user = User::find($id);
		if (empty($user))
			App::abort(404);
		$roles = Role::all();
        return View::make('admin.users.edit', compact('user', 'roles'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id = null)
	{
		$user 	= User::find($id);
		$roles = Input::get('roles');
		if ($roles == null)
			$roles = array();
		$user->roles()->sync($roles);			

		return Redirect::to('admin/users');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$user = user::find($id);
		if (empty($user))
			return Response::json(array('success' => false));
		$user->delete();
		return Response::json(array('success' => true));
	}

}
