<?php
namespace Admin;
use BaseController;
use Post, Type, Entrust, Auth, Request, Image, Img, View, Response, Input, Functions, File, Config;

class ImagesController extends BaseController {
	
	private $editor;

	public function __construct() {
		$this->editor = Entrust::hasRole('Editor In Chief');
		$this->beforeFilter('edit-images', array('only' => array('update', 'destroy')));
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$editor = $this->editor;
		if (Request::ajax()) {
			$album = Request::get('album');
			if ($album)
				return View::make('admin.albums.ajax.index-album', compact('album', 'editor'));
			else 
				$album = 1;
				return View::make('admin.albums.ajax.index', compact('album', 'editor'));
		}
			
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$destinationPath = Config::get('image.path');
		$user = Auth::user();

		if(Request::get('type') == 'download')
		{
			$image = Request::get('image');

			$extension = ".".pathinfo($image, PATHINFO_EXTENSION);
			if($extension == '.jpg' or $extension == '.jpeg' or $extension == '.JPG' or $extension == '.JPEG')
				$file = imageCreateFromJpeg($image);
			else if($extension == '.png' or $extension == '.PNG')
            	$file = imageCreateFromPng($image); 
			$fileName = str_random(10).$extension;
			
			$thumb = Img::make($file)->grab(Config::get('image.thumb.width'), Config::get('image.thumb.height'))->save($destinationPath.'/thumbs/'.$fileName);
			$cover = Img::make($file)->grab(Config::get('image.cover.width'), Config::get('image.cover.height'))->save($destinationPath.'/covers/'.$fileName);
			$banner = Img::make($file)->grab(Config::get('image.banner.width'), Config::get('image.banner.height'))->save($destinationPath.'/banners/'.$fileName);
			$img = Img::make($file)->resize(1000, 1000, true, false)->save($destinationPath.$fileName);

		}
		else {
			$file = Input::file('upload');
			$fileName = $file->getClientOriginalName();
			$extension = ".".pathinfo($fileName, PATHINFO_EXTENSION);
			$name = pathinfo($fileName, PATHINFO_FILENAME);
			$i = 0;
			while(file_exists($destinationPath.$fileName)){
				$i++;
				$fileName = $name.'-'.$i.$extension;
			}

			$thumb = Img::make($file->getRealPath())->grab(Config::get('image.thumb.width'), Config::get('image.thumb.height'))->save($destinationPath.'/thumbs/'.$fileName);
			$cover = Img::make($file->getRealPath())->grab(Config::get('image.cover.width'), Config::get('image.cover.height'))->save($destinationPath.'/covers/'.$fileName);
			$banner = Img::make($file->getRealPath())->grab(Config::get('image.banner.width'), Config::get('image.banner.height'))->save($destinationPath.'/banners/'.$fileName);
			$img = Img::make($file->getRealPath())->resize(1000, 1000, true, false)->save($destinationPath.$fileName);

		}
		
		$image = new Image;
		$image->user()->associate($user);

		$image->url = $fileName;
		if(Request::get('album'))
			$image->album_id = Request::get('album');
		else 
			$image->album_id = 1;
		$image->save();

		@unlink($_FILES['upload']);
		
		return Response::json(array('success'=> true, 'id' => $image->id, 'user' => $user->first_name.' '.$user->last_name , 'image' => $fileName, 'url' => $fileName, 'path' => $destinationPath), 200);

	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$image = Image::find($id);
		$image->caption = Request::get('caption', $image->caption);
		$image->order = Request::get('order', $image->order);
		$image->public = Request::get('public', $image->public);
		$image->album_id = Request::get('album', $image->album_id);
		$image->save();
		return Response::json(array('success'=> true, 'name' => $image->caption, 'public' => $image->public, 'order' => $image->order, 'id' => $image->id), 200);

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$image = Image::find($id);
		if (empty($image))
			return Response::json(array('success' => false, 400));
		/*File::delete('uploads/images/thumbs/'.$image->url);
		File::delete('uploads/images/covers/'.$image->url);
		File::delete('uploads/images/banners/'.$image->url);
		File::delete('uploads/images/'.$image->url);
		*/
		$image->delete();
		return Response::json(array('success' => true), 200);
	}

}
