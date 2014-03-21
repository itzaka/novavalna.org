<?php
namespace Api;
use BaseController;
use Request, Type, Language, Post, Response, Category;

class PostsController extends BaseController {

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

		$posts 	= Post::with('type', 'category', 'language');

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
			$posts 	= $posts->whereIn('type_id', $types_arr);
		}

		if (Request::get('categories')) {
			$categories 	= explode(',', Request::get('categories'));
			$categories_arr = array();
			foreach($categories as $category)
			{
				$category = is_numeric($category) ? Category::find($category) : Category::whereSlug($category)->first();
				if ($category)
					array_push($categories_arr, $category->id);
			}
			if(empty($categories_arr))
				return Response::json(array('error' => true, 'message' => 'Requested categories are invalid'));
			$posts 	= $posts->whereIn('category_id', $categories_arr);
		}
		if ($search = Request::get('search')){
			$posts 	= $posts->where(function($query) use($search){
				$query->where('title', 'like', '%'.$search.'%')->orWhere('content', 'like', '%'.$search.'%');
			});
		}

		$posts 	= $posts->whereLanguageId($language)->take($take)->skip($skip)->orderBy($order, $sort)->get();
		return $posts->isEmpty() ?  
			Response::json(array('error' => true, 'message' => 'there are no posts')) : 
			Response::json(array('error'=> false, 'total'	=> $posts->count(), 'posts'	=> $posts->toArray()));		
	}

	
	public function show($id)
	{
		$post = Post::with('type', 'category', 'language', 'albums');
		$post = is_numeric($id) ? $post->find($id) : $post->whereSlug($id)->first();
		return $post ? Response::json(array('error'=> false, 'post'	=> $post->toArray())) : Response::json(array('error' => true, 'message' => 'post not found'));
	}

}
