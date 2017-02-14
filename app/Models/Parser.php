<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parser extends Model
{
    public static function htmlSource($url) {

        $curlOptions = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_ENCODING => 'UTF-8',
            CURLOPT_SSL_VERIFYPEER => true,
        ];

        $curlInit = curl_init();
        curl_setopt_array($curlInit, $curlOptions);
        $html = curl_exec($curlInit);
        curl_close($curlInit);

        return $html;

    }
}
