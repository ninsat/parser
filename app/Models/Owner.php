<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Owner extends Model {

	protected $table = 'owners';
	public $timestamps = true;

	public function ad()
	{
		return $this->hasMany('Ad');
	}

}