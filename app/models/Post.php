<?php

class Post extends Eloquent {
	protected $guarded = array();

	public static $rules = array();
	protected $softDelete = true;
	protected $hidden = array('language_id', 'type_id', 'category_id');

	public function language() {
		return $this->belongsTo('Language', 'language_id');
	}
	public function type() {
		return $this->belongsTo('Type', 'type_id');
	}
	public function user() {
		return $this->belongsTo('User', 'user_id');
	}
	public function category() {
		return $this->belongsTo('Category', 'category_id');
	}
	public function albums() {
		return $this->belongsToMany('Album');
	}
	public function setSlugAttribute($value) {
		$slug = Functions::slug($value);
		$slugCount = count( $this->whereRaw("slug REGEXP '^{$slug}(-[0-9]*)?$'")->get() );
		$slugFinal = ($slugCount > 0) ? "{$slug}-{$slugCount}" : $slug;
		$this->attributes['slug'] = $slugFinal;
	}
}
