<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ad extends Model {

	protected $table = 'ads';
	public $timestamps = true;

	public function user()
	{
		return $this->belongsTo('User');
	}

	public function owner()
	{
		return $this->belongsTo('Owner');
	}

	public function tag()
	{
		return $this->belongsToMany('Query');
	}

}