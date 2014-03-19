<?php
namespace Api;
use BaseController;
use Request, Language, Response, Album, Position;

class AlbumsController extends BaseController {

	public function index()
	{
		$order_arr  = array('created_at',  'title', 'id');
		$sort_arr 	= array('asc', 'desc');
		$order 		= in_array(Request::get('order') , $order_arr)	? Request::get('order') : "created_at";
		$sort 		= in_array(Request::get('sort') , $sort_arr)	? Request::get('sort') 	: "asc";
		$take 		= is_numeric(Request::get('limit'))				? Request::get('limit') : 10;
		$skip		= is_numeric(Request::get('offset'))			? Request::get('offset'): 0;

		
		$albums 	= Album::take($take)->skip($skip)->orderBy($order, $sort)->get();
		return $albums->isEmpty() ?  
			Response::json(array('error' => true, 'message' => 'there are no albums')) : 
			Response::json(array('error'=> false, 'total'	=> $albums->count(), 'albums'	=> $albums->toArray()));		
	}

	
	public function show($id)
	{
		$album = Album::with('images', 'posts')->find($id);

		return $album ? Response::json(array('error'=> false, 'album'	=> $album->toArray())) : Response::json(array('error' => true, 'message' => 'album not found'));
	}

}
