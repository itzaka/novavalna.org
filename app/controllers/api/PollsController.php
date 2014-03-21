<?php
namespace Api;
use BaseController;
use Request, Type, Language, Response, Poll, Answer, Cookie, Session;

class PollsController extends BaseController {

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
		
		$polls 	= Poll::with('answers', 'language');

		$polls 	= $polls->whereLanguageId($language)->take($take)->skip($skip)->orderBy($order, $sort)->get();
		return $polls->isEmpty() ?  
			Response::json(array('error' => true, 'message' => 'there are no banners')) : 
			Response::json(array('error'=> false, 'total'	=> $polls->count(), 'polls'	=> $polls->toArray()));		
	}

	
	public function show($id)
	{

		$voted = Cookie::has('poll-'.$id) ? true : false;
		$vote = Cookie::has('poll-'.$id) ? Cookie::get('poll-'.$id) : 0;

		$poll = Poll::with('answers', 'language');
		$poll = is_numeric($id) ? $poll->find($id) : $poll->whereSlug($id)->first();

		return $poll ? Response::json(array('error'=> false, 'voted' => $voted, 'vote' => $vote, 'poll'	=> $poll->toArray())) : Response::json(array('error' => true, 'message' => 'poll not found'));
	}

	public function update($id)
	{
		if (Cookie::has('poll-'.$id) or Session::has('poll-'.$id))
		{
			$anser = Cookie::has('poll-'.$id) ? Cookie::has('poll-'.$id) : Session::has('poll-'.$id);
			return Response::json(array('error' => true, 'message' => 'you have already voted for this poll', 'answer' => Cookie::get('poll-'.$id)));
		}
		$poll = Poll::find($id);
		$answer = $poll->answers()->whereId(Request::get('answer'))->first();
		if(!$answer)
			return Response::json(array('error' => true, 'message' => 'invalid answer selected'));
		$answer->votes += 1;
		$answer->save();
		$poll->votes += 1;
		$poll->save();
		$cookie = Cookie::forever('poll-'.$id, $answer->id);
		$session = Session::put('poll-'.$id, $answer->id);
		return Response::json(array('error' => false, 'message' => 'thanks for your vote', 'answer' => $answer->id))->withCookie($cookie);

	}
}
