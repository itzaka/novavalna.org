<?php
namespace Admin;
use BaseController;
use Poll, Answer, Request,  View, Response, Input;

class PollsAnswersController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	
	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{	
		$poll 	= Poll::find(Input::get('poll_id'));
		$answer = new Answer;
		$answer->poll()->associate($poll);
		$answer->save();
		return Response::json(array('status'=>'success', 'id' => $answer->id), 200);
	}

	public function update($id)
	{
		$answer = Answer::find($id);
		$answer->title = Input::get('title');
		$answer->save();
		return Response::json(array('status'=>'success'), 200);
	}

	public function destroy($id)
	{
		$answer = Answer::find($id);
		if (empty($answer))
			return Response::json(array('success' => false));
		$answer->delete();
		return Response::json(array('success' => true));
	}

}
