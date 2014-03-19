<?php
namespace Admin;
use BaseController;
use Poll, Position, Entrust, Auth, Language, Answer, Request,  View, Response, Validator, Input, Redirect, App;

class PollsController extends BaseController {

	private $editor;
	private $user;

	public function __construct() {
		$this->beforeFilter('create-categories');
		$this->editor = Entrust::hasRole('Editor In Chief');
		$this->user = Auth::user();
		$this->beforeFilter('edit-polls', array('only' => array('edit' ,'update', 'destroy')));
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
		if($editor)
			$polls = Poll::with('language')->get();
		else
			$polls = $user->polls()->with('language')->get();

		if (Request::ajax()) {
			return Response::json(array('aaData' => $polls->toArray()));
		}
        return View::make('admin.polls.index', compact('polls', 'editor'));
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
        return View::make('admin.polls.create', compact('positions', 'languages'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make(Input::all(), array('language' => 'required', 'title' => 'required', 'order' => 'required'));
		if ($validator->fails())
			return Redirect::to('admin/polls/create')->withErrors($validator)->withInput();
		
		$language = Language::find(Input::get('language'));
		$user = Auth::user();
		
		$poll 	= new Poll;

		$poll->title = Input::get('title');
		$poll->order = Input::get('order');
		$post->public = false;
		$poll->votes = 0;
		
		$poll->language()->associate($language);
		$poll->user()->associate($user);

		$poll->save();
		
		if (Input::get('answers')) {
			foreach(Input::get('answers') as $title) {
				$answer = new Answer;
				$answer->title = $title;
				$answer->poll()->associate($poll);
				$answer->save();
			}
		}
			
		return Redirect::to('admin/polls');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$poll = Poll::find($id);
        return View::make('admin.polls.show', compact('poll'));
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
		$poll = Poll::find($id);
		if (empty($poll))
			App::abort(404);
		$languages = Language::lists('title', 'id');
		$positions = Position::lists('title', 'id');
        return View::make('admin.polls.edit', compact('poll', 'languages', 'positions', 'editor'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$validator = Validator::make(Input::all(), array('language' => 'required', 'title' => 'required', 'order' => 'required'));
		if ($validator->fails())
			return Redirect::to('admin/polls/create')->withErrors($validator)->withInput();
		
		$language = Language::find(Input::get('language'));

		$poll 	= Poll::find($id);

		$poll->title = Input::get('title');
		$poll->order = Input::get('order');
		$poll->public = Input::get('public') ? true : false;
		
		$poll->language()->associate($language);

		$poll->save();
		
			
		return Redirect::to('admin/polls');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$poll = Poll::find($id);
		if (empty($poll))
			return Response::json(array('success' => false));
		$poll->delete();
		return Response::json(array('success' => true));
	}

}
