<?php

namespace App\Classes;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use SoapBox\Formatter\Formatter;

class ArrayToExport
{
    public static function arrayToCsv ($arrayToExport, $additional) {

        $attachment = 'attachment; filename=export.csv';
        $type = 'text/csv; charset=utf-8';

        if ($additional === 'output') {
            $attachment = 'filename=export.csv';
            $type = 'text/plain';
        }

        $headers = [
            'Cache-Control'             => 'must-revalidate, post-check=0, pre-check=0',
            'Content-type'              => $type,
            'Content-Disposition'       => $attachment,
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

        return Response::stream($callback, 200, $headers);
    }

    public static function arrayToJson($arrayToExport, $additional) {

        $attachment = 'attachment; filename=export.json';

        if ($additional === 'output') {
            $attachment = 'filename=export.json';
        }


        $headers = [
            'Cache-Control'             => 'must-revalidate, post-check=0, pre-check=0',
            'Content-type'              => 'text/json; charset=utf-8',
            'Content-Disposition'       => $attachment,
            'Expires'                   => '0',
            'Pragma'                    => 'public'
        ];

        $jsonData = json_encode($arrayToExport, JSON_PRETTY_PRINT);

        $callback = function() use ($jsonData) {

            $fp = fopen('php://output', 'w');

            if ($fp) {
                fputs($fp, $jsonData);
            }

            fclose($fp);
        };

        return Response::stream($callback, 200, $headers);
    }

    public static function arrayToXML($arrayToExport, $additional) {

        $attachment = 'attachment; filename=export.xml';

        if ($additional === 'output') {
            $attachment = 'filename=export.xml';
        }

        $headers = [
            'Cache-Control'             => 'must-revalidate, post-check=0, pre-check=0',
            'Content-type'              => 'text/xml; charset=utf-8',
            'Content-Disposition'       => $attachment,
            'Expires'                   => '0',
            'Pragma'                    => 'public'
        ];

        $formatter = Formatter::make($arrayToExport, Formatter::ARR);
        $xml = $formatter->toXml();

        $callback = function() use ($xml) {

            $fp = fopen('php://output', 'w');

            if ($fp) {
                fputs($fp, $xml);
            }

            fclose($fp);
        };

        return Response::stream($callback, 200, $headers);
    }

    public static function arrayToYAML($arrayToExport, $additional) {

        $attachment = 'attachment; filename=export.yaml';

        if ($additional === 'output') {
            $attachment = 'filename=export.yaml';
        }

        $headers = [
            'Cache-Control'             => 'must-revalidate, post-check=0, pre-check=0',
            'Content-type'              => 'application/json; charset=utf-8',
            'Content-Disposition'       => $attachment,
            'Expires'                   => '0',
            'Pragma'                    => 'public'
        ];

        $formatter = Formatter::make($arrayToExport, Formatter::ARR);
        $yaml = $formatter->toYaml();

        $callback = function() use ($yaml) {

            $fp = fopen('php://output', 'w');

            if ($fp) {
                fputs($fp, $yaml);
            }

            fclose($fp);
        };

        return Response::stream($callback, 200, $headers);
    }
}