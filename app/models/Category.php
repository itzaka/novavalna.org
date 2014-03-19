<?php

class Category extends Eloquent {
	protected $guarded = array();
	protected $hidden = array('language_id', 'type_id', 'deleted_at');

	public static $rules = array();
	protected $softDelete = true;

	public function posts() {
		return $this->hasMany('Post');
	}
	public function type() {
		return $this->belongsTo('Type');
	}
	public function user() {
		return $this->belongsTo('User');
	}
	public function language() {
		return $this->belongsTo('Language', 'language_id');
	}
	public function setSlugAttribute($value) {
		$slug = Functions::slug($value);
		$slugCount = count( $this->whereRaw("slug REGEXP '^{$slug}(-[0-9]*)?$'")->get() );
		$slugFinal = ($slugCount > 0) ? "{$slug}-{$slugCount}" : $slug;
		$this->attributes['slug'] = $slugFinal;
	
		/*
		$type = $this->attributes['type'];
		$slug = Functions::slug($value);
		$slugCount = count( $this->whereTypeId($user->id)->whereRaw("slug REGEXP '^{$slug}(-[0-9]*)?$'")->get() );
		$slugFinal = ($slugCount > 0) ? "{$slug}-{$slugCount}" : $slug;
		$this->attributes['slug'] = $slugFinal;
		*/
	}

}
