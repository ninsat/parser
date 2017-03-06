<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Classes\Helper;

class Field extends Model
{
    protected $table = 'fields';
    public $timestamps = false;

    public function template()
    {
        return $this->belongsTo('Template');
    }

    public function createNewField(array $fields, $referenceId)
    {

        $preparedFields = $this->prepare($fields);


        foreach ($preparedFields as $fieldName => $value) {

            $field = new Field();
            $field->name = $fieldName;
            $field->selector = $value;
            $field->template_id = $referenceId;
            $field->save();
        }

        return true;
    }

    public function prepare(array $data)
    {

        foreach ($data as $name => &$selector) {
            if ($name === 'mainUrl') {
                $selector = Helper::urlEncode($selector);
            }
        }

        return $data;
    }

}
