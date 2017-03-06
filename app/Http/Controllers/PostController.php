<?php

namespace App\Http\Controllers;

use App\Classes\Helper;
use App\Models\Ad;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function documentation()
    {
        return view('documentation.index');
    }

    public function test() {

        $ad = new Ad();

        $result = $ad->lastUpdateParsed(14);

        echo '<pre>';
        print_r($result);
        echo '<pre>';
        exit();
    }
}
