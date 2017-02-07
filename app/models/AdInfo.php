<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class AdInfo extends Model {

	protected $table = 'ad_node';
	public $timestamps = true;

	public function adUser()
	{
		return $this->belongsTo('AdUser');
	}

}