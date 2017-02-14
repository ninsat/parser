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
}
