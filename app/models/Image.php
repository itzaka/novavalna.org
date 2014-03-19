<?php

class Image extends Eloquent {
	protected $guarded = array();

	public static $rules = array();
	protected $softDelete = true;

	public function album() {
		return $this->belongsTo('Album');
	}
	public function user() {
		return $this->belongsTo('User');
	}

}
