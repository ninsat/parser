<?php

namespace App\Classes;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class ArrayToExport
{
    public static function arrayToCsv ($arrayToExport) {

        $headers = [
            'Cache-Control'             => 'must-revalidate, post-check=0, pre-check=0',
            'Content-type'              => 'text/csv',
            'Content-Disposition'       => 'attachment; filename=export.csv',
            'Expires'                   => '0',
            'Pragma'                    => 'public'
        ];


        $callback = function() use ($arrayToExport)
        {
            $firstItem = reset($arrayToExport); // Для заголовков
            $rusFieldsName = config('parser'); // Русские названия полей

            $csvTitle = [];
            foreach ($firstItem as $field => $value) {
                if (isset($rusFieldsName[$field])) {
                    $csvTitle[] = $rusFieldsName[$field];
                } else {
                    $csvTitle[] = $field;
                }
            }

            array_unshift($arrayToExport, $csvTitle);

            // Генерируем файл
            $fp = fopen('php://output', 'w');

            // UTF-8 фикс в EXEL
            fputs($fp, $bom = ( chr(0xEF) . chr(0xBB) . chr(0xBF) ));
            if ($fp) {

                // Основное содержимое
                foreach ($arrayToExport as $row) {
                    fputcsv($fp, $row, ';');
                }
            }

            fclose($fp);
        };


        //return $csvTitle;
        return Response::stream($callback, 200, $headers);
    }
}