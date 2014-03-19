<?php

class Type extends Eloquent {
	protected $guarded = array();

	public static $rules = array();
 	
 	public function typeable()
    {
        return $this->morphTo();
    }

	public function posts() {
		return $this->hasMany('Post');
	}
	public function categories() {
		return $this->hasMany('Category');
	}
	public function album() {
		return $this->belongsTo('Album');
	}

}
