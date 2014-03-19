<?php

class Album extends Eloquent {
	protected $guarded = array();

	public static $rules = array();
	
	public function images() {
		return $this->hasMany('Image');
	}
	public function posts() {
		return $this->belongsToMany('Post');
	}
}
