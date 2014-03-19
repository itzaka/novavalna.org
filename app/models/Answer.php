<?php

class Answer extends Eloquent {
	protected $guarded = array();
	protected $hidden = array('poll_id');

	public static $rules = array();

	protected $table = 'polls_answers';
	public $timestamps = false;

	public function poll(){
		return $this->belongsTo('Poll');
	}

}
