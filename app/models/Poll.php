<?php

class Poll extends Eloquent {
	protected $guarded = array();
	protected $hidden = array('language_id', 'deleted_at');

	public static $rules = array();
	protected $softDelete = true;

	public function answers(){
		return $this->hasMany('Answer');
	}
	public function language(){
		return $this->belongsTo('Language');
	}
	public function user() {
		return $this->belongsTo('User');
	}
}
