<?php
namespace Admin;
use BaseController;
use Post, Type, App, Entrust, Request, Auth, Language, Category, View, Response, Validator, Input, Redirect;

class CategoriesController extends BaseController {
	
	private $type;
	private $language;
	private $editor;

	public function __construct() {
		$this->beforeFilter('create-categories');
		$this->beforeFilter('edit-categories', array('only' => array('edit', 'update', 'destroy')));

		$type = Request::get('type') ? Request::get('type') : null;
		if ($type !== null) {
			$type = Type::whereSlug($type)->first();
			if (empty($type))
				$type = Type::first();
			$this->type = $type;
			if($this->type->permission && !Entrust::can($type->permission))
				return App::abort(404);
		}
		$language = Request::get('language') ? Request::get('language') : null;
		if ($language !== null) {
			$language = Language::whereSlug($language)->first();
			if (empty($language))
				$language = Language::first();
			$this->language = $language;
		}
		
		$this->editor = Entrust::hasRole('Editor In Chief');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		if (Request::ajax())
		{
			$type = Type::find(Input::get('type'));
			$language = Language::find(Input::get('language'));
			$categories = array(0 => 'Без категория') + $type->categories()->whereLanguageId($language->id)->lists('title', 'id');
			return $categories;
		}
		if ($this->type)
			$categories = $this->type->categories()->with('language', 'type')->get();
		else
		{
			$types = Type::all();
			$permtypes= array(0);
			foreach ($types as $type)
			{
				if(!$type->permission or Entrust::can($type->permission) or Entrust::hasRole('Editor In Chief'))
					array_push($permtypes, $type->id);
			}
			$categories = Category::whereIn('type_id', $permtypes)->with('language', 'type')->get();
		}
		$type = $this->type;
		
		$types = Type::lists('title', 'id');
		$editor = $this->editor;
        return View::make('admin.categories.index', compact('categories', 'type', 'types', 'editor'));
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

		$types = Type::all();
		$permtypes= array('0');
		foreach ($types as $type)
		{
			if(!$type->permission or Entrust::can($type->permission) or Entrust::hasRole('Editor In Chief'))
				array_push($permtypes, $type->id);
		}

		$types = Type::whereIn('id', $permtypes)->lists('title', 'id');
        return View::make('admin.categories.create', compact('type', 'languages', 'types', 'language'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make(Input::all(), array('title' => 'required', 'language' => 'required', 'order' => 'required|numeric', 'type' => 'required'));
		if ($validator->fails())
			return Redirect::to('admin/categories/create')->withErrors($validator)->withInput();

		$language = Language::find(Input::get('language'));
		$type = Type::find(Input::get('type'));
		$user = Auth::user();

		$category 			= new Category;

		$category->language()->associate($language);
		$category->type()->associate($type);
		$category->user()->associate($user);

		$category->title 	= Input::get('title');
		$category->slug  	= Input::get('title');
		$category->order 	= Input::get('order');
		$category->save();
		return Redirect::to('admin/categories?type='.$type->slug);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        return View::make('admin.categories.show');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$category = Category::find($id);
		if (empty($category))
			App::abort(404);
		$languages = Language::lists('title', 'id');
		$types = Type::all();
		$permtypes= array('0');
		foreach ($types as $type)
		{
			if(!$type->permission or Entrust::can($type->permission) or Entrust::hasRole('Editor In Chief'))
				array_push($permtypes, $type->id);
		}

		$types = Type::whereIn('id', $permtypes)->lists('title', 'id');
        return View::make('admin.categories.edit', compact('category', 'languages', 'types'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$validator = Validator::make(Input::all(), array('title' => 'required', 'language' => 'required', 'order' => 'required|numeric'));

		$category = Category::find($id);
		
		$category->title 		= Input::get('title');
		$category->order 		= Input::get('order');
		$category->save();
		return Redirect::to('admin/categories?type='.$category->type->slug);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$category = Category::find($id);
		if (empty($category))
			return Response::json(array('success' => false));
		$category->delete();
		return Response::json(array('success' => true));
	}

}
