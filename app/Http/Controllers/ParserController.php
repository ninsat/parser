<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Parser;
use App\Models\Ad;

class ParserController extends Controller
{
    public function init(Request $request)
    {

/*        $fields = DB::table('fields')
            ->select('id', 'name', 'selector')
            ->where('template_id', '=', $request->input('template_id'))
            ->get();*/

        //$url = DB::table('fields')->where('name', 'mainUrl')->value('selector');
        $InitUrl = 'https://www.olx.ua/list/q-%D0%B1%D1%80%D0%B8%D0%BB%D0%BB%D0%B8%D0%B0%D0%BD%D1%82%D1%8B/';
        $paginateSelector = '.pager span a.lheight24';
        $pagesSelector = 'h3.x-large a.detailsLink';

/*        $curlOptions = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_ENCODING => 'UTF-8',
            CURLOPT_SSL_VERIFYPEER => true,
        ];

        $curlInit = curl_init();
        curl_setopt_array($curlInit, $curlOptions);

        $html = curl_exec($curlInit);

        curl_close($curlInit);

        var_dump($html);*/

        $parser = new Parser();

        $categoryPageUrls = $parser->getPaginatedUrl($InitUrl, $paginateSelector);
        $pagesUrl = $parser->getUrlsFromCategory($categoryPageUrls, $pagesSelector);

        var_dump($pagesUrl);
    }

    public function listNewAds() {
        $InitUrl = 'https://www.olx.ua/list/q-%D0%B1%D1%80%D0%B8%D0%BB%D0%BB%D0%B8%D0%B0%D0%BD%D1%82%D1%8B/';
        $paginateSelector = '.pager span a.lheight24';
        $pagesSelector = 'h3.x-large a.detailsLink';

/*        $parser = new Parser();

        $categoryPageUrls = $parser->getPaginatedUrl($InitUrl, $paginateSelector);
        $pagesUrl = $parser->getUrlsFromCategory($categoryPageUrls, $pagesSelector);*/

        $parser = new Parser();

        $pagesUrl = [
            0 => [0 => 'test1', 1 => 'test11'],
            1 => [0 => 'test2', 1 => 'test22'],
            2 => [0 => 'test3', 1 => 'test33'],
            3 => [0 => 'test4', 1 => 'test44'],
            3 => [0 => 'test5', 1 => 'test55'],

        ];

        $adsList = $parser->createAdWithUrl($pagesUrl, 1);

        return view('parser.list-new-ads', ['ads' => $adsList]);
    }
}
