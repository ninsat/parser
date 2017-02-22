<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Ad;
use App\Models\Template;

class AdController extends Controller
{

    /*
     * Контроллер страницы с детальным описанием обьявления
     * Ex: /template/{templateId}/ads/{adId}
     *
     * */
    public function index(Request $request) {
        $ad = Ad::adByTemplateByAdId($request->templateId, $request->adId);
        $template = Template::templateInfoFormId($request->templateId);

        return view('ad.index', ['ad' => $ad, 'template' => $template]);
    }

    /*
     * Контроллер страницы со списком всех спарсеных обьявлений для этого шаблона
     * Ex: /template/{templateId}/ads/
     *
     * */
    public function adsListDone(Request $request)
    {

        $ads = Ad::listAdsByTemplate($request->templateId);
        $template = Template::templateInfoFormId($request->templateId);

        /* Статистика */
        $stat = Ad::templateStatistics($request->templateId);

        return view('ad.ads-by-template', ['ads' => $ads,
            'template' => $template,
            'stat' => $stat['adsDone']
        ]);

    }

    /*
     * Контроллер страницы со списком всех обьявлений шаблона поставленных в очередь на обработку
     * Ex: /template/{templateId}/ads-queue/
     *
     * */
    public function adsListQueue (Request $request)
    {
        $ads = Ad::listQueueAdsByTemplate($request->templateId);
        $template = Template::templateInfoFormId($request->templateId);

        /* Статистика */
        $stat = Ad::templateStatistics($request->templateId);

        return view('ad.ads-queue', ['ads' => $ads, 'template' => $template, 'stat' => $stat['adsQueue']]);
    }

    /*
     * Контроллер отвечающий за парсинг всех обьявлений и всех полей по очереди
     * В дальнейшем должен дергаться по крону
     *
     * */
    public function parse()
    {
        $ad = new Ad();
        $ads = $ad->adsFilling();

        echo "<pre>";
        print_r($ads);
        echo "</pre>";
    }


    /*
     * Контроллер создает первую запись обьявления в БД и ставит обьявление в очередь на парсинг
     *
     * */
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

        return redirect()->back()->with('succes', 'Поставлено в очередь ' . $adList . ' обьявлений');

    }
}
