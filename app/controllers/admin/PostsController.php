<?php
namespace Admin;
use BaseController;
use Post, Type, Request, Language, Auth, Entrust, Category, View, Response, Validator, Input, Redirect, App, Album;

class PostsController extends BaseController {
	
	private $type;
	private $language;
	private $user;
	private $editor;

	public function __construct() {
		$this->beforeFilter('csrf', array('only' => array('store', 'update')));
		$this->beforeFilter('edit-posts', array('only' => array('edit', 'update', 'destroy')));
    	
    	//TYPE
		$type = Request::get('type') ? Request::get('type') : null;
		if ($type !== null)
			$type = Type::whereSlug($type)->first();
		if (empty($type))
			$type = Type::first();
		$this->type = $type;
		$this->beforeFilter('create-posts:'.$type->id);

		//LANGUAGE
		$language = Request::get('language') ? Request::get('language') : null;
		if ($language !== null) 
			$language = Language::whereSlug($language)->first();
		if (empty($language) or $language == null)
			$language = Language::first();
		$this->language = $language;
		//USER
		$this->user = Auth::user();
		$this->editor = Entrust::hasRole('Editor In Chief');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$user = $this->user;
		$type = $this->type;
		$editor = $this->editor;
		if($editor)
			$posts = $type->posts()->with('language', 'category')->get();
		else
			$posts = $user->posts()->whereTypeId($type->id)->with('language', 'category')->get();
        return View::make('admin.posts.index', compact('posts', 'type', 'editor'));
	}
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$type = $this->type;
		$language = $this->language;
		$languages = Language::lists('title', 'id');
		$categories = array(0 => 'Без категория') + Category::whereTypeId($type->id)->whereLanguageId($language->id)->lists('title', 'id');
		$albums = Album::whereSystem(false)->get();
        return View::make('admin.posts.create', compact('type', 'languages', 'categories', 'language', 'albums'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make(Input::all(), array('title' => 'required', 'language' => 'required', 'order' => 'required|numeric', 'image'=> 'image', 'content' => 'required'));
		if ($validator->fails())
			return Redirect::to('admin/posts/create')->withErrors($validator)->withInput();

		$language = Language::find(Input::get('language'));
		$type = Type::find(Input::get('type'));
		$category = Category::find(Input::get('category'));
		$user = $this->user;

		$post 				= new Post;

		$post->public = false;
		$post->language()->associate($language);
		$post->type()->associate($type);
		$post->user()->associate($user);

		if (!empty($category))
			$post->category()->associate($category);
		$post->title 		= Input::get('title');
		$post->slug  		= Input::get('title');
		$post->image 		= Input::get('image');
		$post->content 		= Input::get('content');
		$post->order 		= Input::get('order');
		$post->date 		= Input::get('date') ? Input::get('date') : null;
		$post->media_url 	= Input::get('media_url') ? Input::get('media_url') : null;
		$post->save();

		$albums = array();
		if (Input::get('albums'))
			$albums = Input::get('albums');
		$post->albums()->sync($albums);

		return Redirect::to('admin/posts?type='.$type->slug);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$editor = $this->editor;
		$user = $this->user;
		if($editor)
			$post = Post::with('albums')->find($id);
		else
			$post = $user->posts()->with('albums')->find($id);
		if (empty($post))
			App::abort(404);
		$albums = Album::whereSystem(false)->get();
		$languages = Language::lists('title', 'id');
		$categories = array(0 => 'Без категория') + Category::whereTypeId($post->type->id)->whereLanguageId($post->language->id)->lists('title', 'id');
	    return View::make('admin.posts.edit', compact('post', 'categories', 'languages', 'albums', 'editor'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$validator = Validator::make(Input::all(), array('title' => 'required', 'language' => 'required', 'order' => 'required|numeric', 'image'=> 'image', 'content' => 'required'));

		$language = Language::find(Input::get('language'));
		$category = Category::find(Input::get('category'));
		
		$post = Post::find($id);
		$post->language()->associate($language);
		$post->category_id = null;
		if (!empty($category))
			$post->category()->associate($category);

		$post->title 		= Input::get('title');
		$post->public 		= Input::get('public') ? true : false;
		$post->slug  		= Input::get('title');
		$post->image 		= Input::get('image');
		$post->content 		= Input::get('content');
		$post->order 		= Input::get('order');
		$post->date 		= Input::get('date') ? Input::get('date') : null;
		$post->save();


		$albums = array();
		if (Input::get('albums'))
			$albums = Input::get('albums');
		$post->albums()->sync($albums);

		return Redirect::to('admin/posts?type='.$post->type->slug);

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$post = Post::find($id);
		if (empty($post))
			return Response::json(array('success' => false));
		$post->delete();
		return Response::json(array('success' => true));
	}

}
