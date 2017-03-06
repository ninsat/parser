<?php

namespace App\Classes;


class Helper
{
    public static function isJsonObject ($string) {
        return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
    }

    public static function urlValidate($url) {
        $urlEncoded = self::urlEncode($url);

        $status = filter_var($urlEncoded, FILTER_VALIDATE_URL);

        if (!$status) {
            return false;
        }

        return true;
    }

    public static function urlEncode($url) {

        $path = parse_url($url, PHP_URL_PATH);

        if (strpos($path,'%') !== false){
            $result = $url;
        } else {
            $encoded_path = array_map('urlencode', explode('/', $path));
            $result = str_replace($path, implode('/', $encoded_path), $url);
        }

        return $result;
    }
}