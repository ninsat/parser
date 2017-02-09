<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Querytag extends Model {

	protected $table = 'querytags';
	public $timestamps = true;

	public function ad()
	{
		return $this->belongsToMany('Ad');
	}

}