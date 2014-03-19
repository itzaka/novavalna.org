<?php

class Position extends Eloquent {
	protected $guarded = array();
	protected $table = 'banners_positions';

	public static $rules = array();

	public function banners() {
		return $this->hasMany('Banner');
	}
}
