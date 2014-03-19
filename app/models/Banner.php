<?php

class Banner extends Eloquent {
	protected $guarded = array();
	protected $softDelete = true;
	protected $hidden = array('language_id', 'position_id');

	public static $rules = array();

	public function position() {
		return $this->belongsTo('Position');
	}
	public function language() {
		return $this->belongsTo('Language');
	}
	public function user() {
		return $this->belongsTo('User');
	}
}
