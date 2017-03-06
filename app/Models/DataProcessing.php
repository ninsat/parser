<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\DomCrawler\Crawler;

class DataProcessing extends Model
{
    /* Обработка данных для определенных полей */
    public static function processingData($crawler, $field, $adUrl) {

        if (!($crawler instanceof Crawler)) {
            return false;
        }

        /* ID  Обьявления */
        if ($field->name === 'adId') {

            // Проверка на пустое значение
            $count = $crawler->filter($field->selector)->count();
            if($count < 1) {
                return false;
            }

            /* Получение данных из селектора */
            $parsedData = $crawler->filter($field->selector)->text();

            /* Обработка полученых результатов */
            $data = trim($parsedData);
            $regExp = '/\d+/';
            preg_match($regExp, $data, $matches);
            $result = $matches[0];

            return $result;
        }

        /* Имя пользователя */
        if ($field->name === 'userName') {

            // Проверка на пустое значение
            $count = $crawler->filter($field->selector)->count();
            if($count < 1) {
                return false;
            }

            // Получение результата
            $parsedData = $crawler->filter($field->selector)->first()->text();
            $result = trim($parsedData);

            return $result;
        }

        /* ID Пользователя */
        if ($field->name === 'userId') {

            // Проверка на пустое значение
            $count = $crawler->filter($field->selector)->count();
            if($count < 1) {
                return false;
            }

            $parsedData = $crawler->filter($field->selector)->attr('href');

            $parsedData = explode('/', trim($parsedData, '/'));
            $result = $parsedData[count($parsedData) - 1];

            return $result;
        }

        /* URL для запроса на страницу телефонов пользователя */
        if ($field->name === 'userTel') {

            // Проверка на пустое значение
            $count = $crawler->filter($field->selector)->count();
            if($count < 1) {
                return false;
            }

            $parsedData = $crawler->filter($field->selector)->attr('class');

            /* Выбираем json */
            $startPattern = mb_strpos($parsedData, '{\'');
            $endPattern = mb_strpos($parsedData, '\'}');
            $data = mb_substr($parsedData, $startPattern, $endPattern - $startPattern + 2);
            $json = str_replace("'", '"', $data);

            /*
             * Return $dataArr example:
             * array('path' => 'phone', 'id' => 'oRB52', 'id_raw' => '367405988');
             */

            $dataArr = json_decode($json, true);

            /* Формируем ссылку на страницу контактов */
            $host = parse_url($adUrl);
            $result = $host['scheme'] . '://' . $host['host'] . '/ajax/misc/contact/phone/' . $dataArr['id'] . '/';

            return $result;
        }


        /* Получение данных для всех остальных полей */
        $result = $crawler->filter($field->selector)->text();

        return $result;
    }

    public static function getUserTelephones($jsonString) {
        $userTel = json_decode($jsonString, true);
        $userTel = $userTel['value'];

        if (is_integer(strpos($userTel, '<span class="block">'))) {
            $search = [' ', '-', ':'];
            $userTel = str_replace('<span class="block">', '', $userTel);
            $userTel = explode('</span>', str_replace($search, '', $userTel));

            /* Удаляем последний пустой элемент */
            array_pop($userTel);

            $result = json_encode($userTel);

        } else {
            $tmp = str_replace(' ', '', $userTel);
            $result = json_encode($tmp);
        }

        return $result;
    }

    public static function getUserTelFromJson ($string)
    {

    }
}
