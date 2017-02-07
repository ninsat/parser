<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class AdUser extends Model {

	protected $table = 'ad_user';
	public $timestamps = true;

	public function ad()
	{
		return $this->hasMany('AdInfo');
	}

}