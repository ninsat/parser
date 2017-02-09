<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Query extends Model {

	protected $table = 'querys';
	public $timestamps = true;

	public function ad()
	{
		return $this->belongsToMany('Ad');
	}

}