<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Ad;

class AdController extends Controller
{
    public function adsByTemplate() {

        return view('ad.ads-by-template');
    }

    public function parse()
    {
        $ad = new Ad();
        $ads = $ad->adsFilling();

        echo "<pre>";
        print_r($ads);
        echo "</pre>";
        //var_dump($ads);
    }

    public function fetch(Request $request)
    {
        $validator = Validator::make($request->input(), [
            'template_id' => 'required|numeric|min:1|max:5',
        ]);


        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $ad = new Ad();

        $adList = $ad->adsUrlsStore($request->input('template_id'));

        return view('ad.fetch', ['ads' => $adList]);
    }
}
