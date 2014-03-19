<?php
namespace Admin;
use BaseController;
use Banner, Position, Entrust, Auth, Language, Image, Request,  View, Response, Validator, Input, Redirect, App;

class BannersController extends BaseController {
	
	private $editor;
	private $user;

	public function __construct() {
		$this->beforeFilter('create-banners');
		$this->editor = Entrust::hasRole('Editor In Chief');
		$this->user = Auth::user();
		$this->beforeFilter('edit-banners', array('only' => array('edit' ,'update', 'destroy')));
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$user = $this->user;
		$editor = $this->editor;

		$banners = Banner::with('position')->get();
		if($editor)
			$banners = Banner::with('position')->get();
		else
			$banners = $user->banners()->with('position')->get();

		if (Request::ajax()) {
			return Response::json(array('aaData' => $banners->toArray()));
		}
		
        return View::make('admin.banners.index', compact('banners', 'editor'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$languages = Language::lists('title', 'id');
		$positions = Position::lists('title', 'id');
        return View::make('admin.banners.create', compact('positions', 'languages'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make(Input::all(), array('language' => 'required', 'url' => 'url|required', 'position' => 'required|numeric', 'image' => 'required', 'order' => 'required'));
		if ($validator->fails())
			return Redirect::to('admin/banners/create')->withErrors($validator)->withInput();
		
		$language = Language::find(Input::get('language'));
		$position = Position::find(Input::get('position'));

		$banner 	= new banner;

		$banner->image = Input::get('image');
		$banner->url = Input::get('url');
		$banner->caption = Input::get('caption');
		$banner->order = Input::get('order');
		
		$banner->language()->associate($language);
		$banner->position()->associate($position);

		$banner->save();
		
		return Redirect::to('admin/banners');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$banner = Banner::find($id);
		return $banner;
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$banner = Banner::find($id);
		if (empty($banner))
			App::abort(404);
		$languages = Language::lists('title', 'id');
		$positions = Position::lists('title', 'id');
        return View::make('admin.banners.edit', compact('banner', 'languages', 'positions'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$validator = Validator::make(Input::all(), array('language' => 'required', 'url' => 'url|required', 'position' => 'required|numeric', 'image' => 'required', 'order' => 'required'));
		if ($validator->fails())
			return Redirect::to('admin/banners/'.$id.'/edit')->withErrors($validator)->withInput();

		$banner 	= Banner::find($id);

		$language 	= Language::find(Input::get('language'));
		$position 	= Position::find(Input::get('position'));

		$banner->image = Input::get('image');
		$banner->url = Input::get('url');
		$banner->caption = Input::get('caption');
		$banner->order = Input::get('order');
		
		$banner->language()->associate($language);
		$banner->position()->associate($position);

		$banner->save();
		
		return Redirect::to('admin/banners');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$banner = Banner::find($id);
		if (empty($banner))
			return Response::json(array('success' => false));
		$banner->delete();
		return Response::json(array('success' => true));
	}

}
