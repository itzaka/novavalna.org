<?php
namespace Api;
use BaseController;
use Request, Language, Response, Banner, Position;

class BannersController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	private $lang;
	public function __construct() {		
		$lang 		= Request::get('language') ? Request::get('language') : "bg";
		$lang 		= Language::whereSlug($lang)->first();
		$this->lang = $lang  ? $lang : Language::whereSlug('bg')->first();
	}
	public function index()
	{
		$order_arr  = array('created_at', 'order', 'title', 'type_id', 'category_id', 'id');
		$sort_arr 	= array('asc', 'desc');
		$order 		= in_array(Request::get('order') , $order_arr)	? Request::get('order') : "created_at";
		$sort 		= in_array(Request::get('sort') , $sort_arr)	? Request::get('sort') 	: "asc";
		$take 		= is_numeric(Request::get('limit'))				? Request::get('limit') : 10;
		$skip		= is_numeric(Request::get('offset'))			? Request::get('offset'): 0;
		$language 	= $this->lang->id;

		
		$banners 	= Banner::with('position', 'language');

		if (Request::get('positions')) {
			$positions 	= explode(',', Request::get('positions'));
			$positions_arr = array();
			foreach($positions as $position)
			{
				$position = is_numeric($position) ? Position::find($position) : Position::whereSlug($position)->first();
				if ($position)
					array_push($positions_arr, $position->id);
			}
			if(empty($positions_arr))
				return Response::json(array('error' => true, 'message' => 'Requested positions are invalid'));
			$banners 	= $banners->whereIn('position_id', $positions_arr);
		}

		$banners 	= $banners->whereLanguageId($language)->take($take)->skip($skip)->orderBy($order, $sort)->get();
		return $banners->isEmpty() ?  
			Response::json(array('error' => true, 'message' => 'there are no banners')) : 
			Response::json(array('error'=> false, 'total'	=> $banners->count(), 'banners'	=> $banners->toArray()));		
	}

	
	public function show($id)
	{
		$banner = Banner::with('position', 'language');
		$banner = is_numeric($id) ? $banner->find($id) : $banner->whereSlug($id)->first();

		return $banner ? Response::json(array('error'=> false, 'banner'	=> $banner->toArray())) : Response::json(array('error' => true, 'message' => 'banner not found'));
	}

}
