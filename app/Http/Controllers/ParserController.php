<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Parser;

class ParserController extends Controller
{
    public function init()
    {
        try {
        $adNotProcessing = DB::table('ads')
            ->select('id', 'ad_url', 'created_at')
            ->where('fetched', '=', 0)
            ->orderBy('created_at', 'asc')
            ->take(100)
            ->get();

        } catch (\Exception $e) {

            return $e->getMessage();
        }

        var_dump($adNotProcessing);

    }

    public function listNewAds(Request $request) {
        $fields = DB::table('fields')
            ->select('id', 'name', 'selector')
            ->where('template_id', '=', $request->input('template_id'))
            ->get();

        /* Create main selectors for parse start data */
        $startUrlSelector = '';
        $adUrlSelector = '';
        $paginationSelector = '';

        foreach ($fields as $field) {
            if ($field->name === 'mainUrl') {
                $startUrlSelector = $field->selector;
            }

            if ($field->name === 'paginate') {
                $paginationSelector = $field->selector;
            }

            if ($field->name === 'adUrl') {
                $adUrlSelector = $field->selector;
            }
        }

        $parser = new Parser();

        $categoryPageUrls = $parser->getPaginatedUrl($startUrlSelector, $paginationSelector);
        $pagesUrl = $parser->getUrlsFromCategory($categoryPageUrls, $adUrlSelector);
        $adsList = $parser->createAdWithUrl($pagesUrl, $request->input('template_id'));

        return view('parser.list-new-ads', ['ads' => $adsList]);
    }
}
