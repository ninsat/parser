<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parser extends Model
{
    public static function htmlSource($url, $referer = null) {

        $header = [
            "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
            "Accept-Language: en-us,en;q=0.5",
            "Accept-Encoding: gzip, deflate",
            "Connection: keep-alive",
            "Upgrade-Insecure-Requests: 1",
            "Cache-Control: max-age=0"
        ];

        $curlOptions = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_ENCODING => 'UTF-8',
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.3) Gecko/2008092417 Firefox/3.0.3',
            CURLOPT_HTTPHEADER => $header
        ];

        if (!empty($referer)) {
            $curlOptions[CURLOPT_REFERER] = $referer;
        }

        $curlInit = curl_init();
        curl_setopt_array($curlInit, $curlOptions);
        $html = curl_exec($curlInit);
        curl_close($curlInit);

        return $html;

    }
}
