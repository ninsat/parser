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

    public function adsListIgnored (Request $request)
    {
        $ads = Ad::listIgnoredAdsByTemplate($request->templateId);
        $template = Template::templateInfoFormId($request->templateId);

        /* Статистика */
        $stat = Ad::templateStatistics($request->templateId);

        return view('ad.ads-ignored', ['ads' => $ads, 'template' => $template, 'stat' => $stat['adsIgnored']]);
    }

    /*
     * Контроллер отвечающий за парсинг всех обьявлений и всех полей по очереди
     * В дальнейшем должен дергаться по крону
     *
     * */
    public function parse()
    {

        $start = microtime(true); // Замер времени выполнения

        $ad = new Ad();
        $ads = $ad->adsFilling();

        if (!$ads) {
            $result = false;
        }

        $result = 'Объявления получены! Время на выполнение составило: ' . (microtime(true) - $start);

        return $result;
    }


    /*
     * Контроллер создает первую запись обьявления в БД и ставит обьявление в очередь на парсинг
     *
     * */

     public function fetch ()
     {
         $start = microtime(true); // Замер времени выполнения

         $ad = new Ad();

         $templateID = $ad->checkTemplateUpdateDate();

         if (!$templateID) {
             return 'Нет необработаных запросов';
         }

         $adList = $ad->adsUrlsStore($templateID);

         $result = 'Количество новых объявлений: ' . $adList . ' Время на выполнение составило: ' . (microtime(true) - $start);

         return $result;
     }

    public function export(Request $request)
    {
        $type = $request->type;
        $paginate = $request->paginate;
        $object = $request->object;
        $templateId = (int) $request->export_template;
        $count = $request->count;
        $additional = $request->additional;

        $error = []; // Массив для ошибок

        if ($count === 'manual' && isset($request->quantity)) {
            $count = (int) $request->quantity;
        } else {
            $count = 'all';
        }

        if (empty($additional) && empty($count) && empty($type) && empty($paginate) && empty($object) && empty($templateId)) {
            $error[] = 'Не переданы необходимые значения для Экспорта';
        }

        if (!is_integer($templateId)) {
            $error[] = 'Номер шаблона не является числовым значением';
        }

        $ad = new Ad();

        $exportedAds = $ad->export($templateId, $object, $count, $type, $additional);

        if (!$exportedAds) {
            return redirect()->back()->with('errors', 'Нечего экспортировать');
        }

        if (count($error) > 0) {
            return false;
        }
/*        echo '<pre>';
        print_r($exportedAds);
        echo '<pre>';
        exit();*/
        return $exportedAds;
    }
}
