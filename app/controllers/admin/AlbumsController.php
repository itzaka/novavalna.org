<?php
namespace Admin;
use BaseController;
use Album, Image, Entrust, Request,  View, Response, Validator, Input, Redirect, App;

class AlbumsController extends BaseController {
	
	private $editor;

	public function __construct() {
		$this->beforeFilter('create-albums');
		$this->editor = Entrust::hasRole('Editor In Chief');
		$this->beforeFilter('edit-albums', array('only' => array('destroy')));
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$editor = $this->editor;
		if ($editor)
			$albums = Album::all();
		else
			$albums = Album::whereSystem(false)->get();
		if (Request::ajax()) {
			return $albums;
		}
		
        return View::make('admin.albums.index', compact('albums', 'editor'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$albums = Album::lists('title', 'id');
		$editor = $this->editor;
        return View::make('admin.albums.create', compact('editor', 'albums'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		if (Request::ajax()) {
			$album = new Album;
			$album->title = 'Untitled';
			$album->save();
			return Response::json(array('success' => true, 'id' => $album->id, 'title' => $album->title), 200);
		}
		$validator = Validator::make(Input::all(), array('title' => 'required'));
		if ($validator->fails())
			return Redirect::to('admin/albums/create')->withErrors($validator)->withInput();

		$album 	= new Album;

		$album->title = Input::get('title');
		$album->save();
		
		if (Input::get('images')) {
			$images = Input::get('images');
			foreach ($images as $image) {
				$image = Image::find($image);
				$image->album()->associate($album);
				$image->save();
			}
		}

		return Redirect::to('admin/albums');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		if (Request::ajax()) {
			$album = Album::with('images')->find($id);
			return $album;
		}
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$editor = $this->editor;
		$album = Album::find($id);
		$images = $album->images()->with('user')->orderBy('id', 'desc')->get();
		$albums = Album::lists('title', 'id');
		if (empty($album))
			App::abort(404);
        return View::make('admin.albums.edit', compact('images', 'album', 'albums', 'editor'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		if (Request::ajax()) {
			if($id != 1) {
				$album = Album::find($id);
				$album->title = Request::get('title');
				$album->save();
				return Response::json(array('success' => true, 'id' => $album->id, 'title' => $album->title), 200);
			}
			return Response::json(array('success' => false), 200);
		}
		$validator = Validator::make(Input::all(), array('title' => 'required'));
		if ($validator->fails())
			return Redirect::to('admin/albums/'.$id.'/edit')->withErrors($validator)->withInput();

		$album 	= Album::find($id);

		$album->title = Input::get('title');
		$album->save();
		
		if (Input::get('images')) {
			$images = Input::get('images');
			foreach ($images as $image) {
				$image = Image::find($image);
				$image->album()->associate($album);
				$image->save();
			}
		}
		return Redirect::to('admin/albums');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		
		$album = Album::find($id);
		if ($album->system == false) {
			$no_album = Album::find(1);
			$images = $album->images;
			foreach($images as $image){
				$image->album()->associate($no_album);
				$image->save();
			}
			if (empty($album))
				return Response::json(array('success' => false));
			$album->delete();
			return Response::json(array('success' => true));
		}
		return Response::json(array('success' => false));
	}

}
