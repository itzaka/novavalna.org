<?php
namespace Api;
use BaseController;
use Request, Type, Language, Response, Category;

class CategoriesController extends BaseController {

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

		
		$categories 	= Category::with('type', 'language');

		if (Request::get('types')) {
			$types 	= explode(',', Request::get('types'));
			$types_arr = array();
			foreach($types as $type)
			{
				$type = is_numeric($type) ? Type::find($type) : Type::whereSlug($type)->first();
				if ($type)
					array_push($types_arr, $type->id);
			}
			if(empty($types_arr))
				return Response::json(array('error' => true, 'message' => 'Requested types are invalid'));
			$categories 	= $categories->whereIn('type_id', $types_arr);
		}

		$categories 	= $categories->whereLanguageId($language)->take($take)->skip($skip)->orderBy($order, $sort)->get();
		return $categories->isEmpty() ?  
			Response::json(array('error' => true, 'message' => 'there are no categories')) : 
			Response::json(array('error'=> false, 'total'	=> $categories->count(), 'categories'	=> $categories->toArray()));		
	}

	
	public function show($id)
	{
		$category = Category::with('type', 'language');
		$category = is_numeric($id) ? $category->find($id) : $category->whereSlug($id)->first();

		return $category ? Response::json(array('error'=> false, 'category'	=> $category->toArray())) : Response::json(array('error' => true, 'message' => 'category not found'));
	}

}
