<?php

namespace App\Models;

use App\Classes\ArrayToExport;
use App\Classes\Helper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Facades\DB;
use App\Models\Content;

class Ad extends Model
{
    protected $table = 'ads';
    public $timestamps = true;

    /* Парсинг обьявлений */
    public function adsFilling() {
        $fieldDataTest = []; // Для тестирования
        $idArray = [];

        /* Выбираем обьявления для которых еще не были получены данные из парсинга */
        $adNotProcessing = DB::table('ads')
            ->select('id', 'ad_url', 'template_id', 'created_at')
            ->where('fetched', '=', 0) //  Обьявление еще не заполненое данными
            ->where('ignored', '=', 0)
            ->inRandomOrder()
            //->orderBy('created_at', 'asc')
            ->take(50) // Ограничение наполнения обявлений за один запуск скрипта
            ->get();

        if (count($adNotProcessing) < 1) {
            return false;
        }

        /* Перебираем массив обьявлений и наполняем данными из парсинга */
        foreach ($adNotProcessing as $ad) {

            /* Получаем ID полей для текущего обьявления, а также паттерны для парсинга */
            $fields = DB::table('fields')
                ->select('id', 'selector', 'template_id', 'name')
                ->where('template_id', '=', $ad->template_id)
                ->whereNotIn('name', ['mainUrl', 'adUrl', 'paginate']) // Исключаем не нужные нам поля
                ->get();

            /* Имея обьявление, принадлежащие к нему поля и паттерны начинаем парсинг */
            $html = Parser::htmlSource($ad->ad_url);

            /* Если по текущему адресу уже нет страницы с обьявлением, устанавливает флаг "Проигнорированное"
            *  и переходим к след. итерации
            */
            if (empty($html)) {

                DB::table('ads')
                    ->where('id', $ad->id)
                    ->update(['ignored' => 1]);

                $idArray['ignored'][] = $ad->id;

                continue;
            }

            $crawler = new Crawler($html);

            /* В цикле сохраняем в поля спарсенные данные текущего обьявления */
                foreach ($fields as $field) {

                    $processingData = DataProcessing::processingData($crawler, $field, $ad->ad_url);

                    if (!$processingData) {
                        $processingData = '';
                    }

                    $fieldData = $processingData;

                    /* Поле требующее дополнительный запрос CURL */
                    if ($field->name === 'userTel' && !empty($fieldData)) {
                        $sourceContactPage = Parser::htmlSource($fieldData, $ad->ad_url);
                        $fieldData = DataProcessing::getUserTelephones($sourceContactPage);

                    }

                    /* Создаем обьект Модели */
                    $content = new Content();

                    /* Наполняем */
                    $content->field_id = $field->id;
                    $content->ad_id = $ad->id;
                    $content->body = $fieldData;

                    /* Сохраняем */
                    $content->save();
                }



            /* Если все успешно обновляем статус обьявления */
            DB::table('ads')
                ->where('id', $ad->id)
                ->update(['fetched' => true, 'updated_at' => Carbon::now()]);

            $idArray['fetched'][] = $ad->id;
        }

        return $idArray;
    }

    public function checkTemplateUpdateDate() {
        // Выбираем все шаблоны
        $templates = DB::table('templates')
            ->select('id')
            ->where('work', 1)
            ->get();

        $todayUpdatedAds = []; // Первое обьявление из шаблона которое было сегодня получено

        foreach ($templates as $template) {

            $todayUpdatedAds[$template->id] = DB::table('ads')
                ->select('id', 'ad_url', 'template_id', 'created_at')
                ->where('template_id', '=', $template->id)
                ->where('created_at', '>=', Carbon::today())
                ->first();
        }

        $notUpdatedTemplates = [];

        foreach ($templates as $template) {
            if (!isset($todayUpdatedAds[$template->id])) {
                $notUpdatedTemplates[$template->id] = $template->id;
            }
        }

        if (empty($notUpdatedTemplates)) {
            return false;
        }

        $notUpdatedTemplate = array_shift($notUpdatedTemplates);

        return $notUpdatedTemplate;
    }

    public function adsUrlsStore($templateId) {

        $fields = DB::table('fields')
            ->select('id', 'name', 'selector')
            ->where('template_id', '=', $templateId)
            ->get();


        /* Create main selectors for parse start data */
        $startUrl = '';
        $adUrlSelector = '';
        $paginationSelector = '';

        foreach ($fields as $field) {
            if ($field->name === 'mainUrl') {
                $startUrl = $field->selector;
            }

            if ($field->name === 'paginate') {
                $paginationSelector = $field->selector;
            }

            if ($field->name === 'adUrl') {
                $adUrlSelector = $field->selector;
            }
        }

        $requestPattern = '?page=';

        /* Получаем HTML исходник текущей страницы */
        $html = Parser::htmlSource($startUrl);

        /* Получаем последний элемент пагинации */
        $crawler = new Crawler($html);

        $lastPaginationPage = $crawler->filter($paginationSelector)
            ->last()->text();

        /* Чистим и приводим к числовому типу */
        $lastPaginationPage = (int)trim($lastPaginationPage);

        /* Create array with links to category page, starts from first page to last page */
        $paginationUrls[1] = $startUrl;

        for ($i = 2; $i <= $lastPaginationPage; $i++) {
            $paginationUrls[$i] = $startUrl . $requestPattern . $i;
        }

        /* Get for each ad */
        $adUrls = $this->getAdsUrls($paginationUrls, $adUrlSelector);

        /* Save each ad to db with own url */
        $result = $this->bulkStoreAd($adUrls, $templateId);

        return $result;
    }

    public function getAdsUrls($paginationUrls, $adUrlSelector) {
        /* Multi array with subpages and ad links */
        $adUrls = [];

        foreach ($paginationUrls as $url) {

            /* Получаем HTML исходник текущей страницы */
            $html = Parser::htmlSource($url);

            /* Получаем URL адреса из Названий обьявлений */
            $crawler = new Crawler($html);

            $adUrls[] = $crawler->filter($adUrlSelector)
                ->each(function (Crawler $node) {

                    /* Фильтруем каждое название обьявления на olx и возвращаем ссылку на него */
                    $href = explode('#', $node->attr('href'));
                    return $href[0];

                });
        }

        return $adUrls;
    }

    protected function bulkStoreAd($adUrls, $templateId) {

        /* Массив содержащий IDшники сохраненных обьявлений */
        $IdArray = [];

        foreach ($adUrls as $pages) {
            foreach ($pages as $adUrl) {

                $urlFromDb = DB::table('ads')->where('ad_url', $adUrl)->value('ad_url');

                /* Сохраняем обьявление в БД и возвращаем его ID */
                if (empty($urlFromDb)) {

                        $ad = new Ad();

                        $ad->ad_url = $adUrl;
                        $ad->template_id = $templateId;
                        $ad->fetched = 0;

                        $ad->save();

                        $IdArray[] = $ad->id;
                }

            }
        }

        return count($IdArray);
    }

    /*
     * Получение обработаных обьявлений по Id шаблона
     * */
    public static function listAdsByTemplate($templateId) {
        $result = Ad::where('fetched', '=', 1)
            ->where('template_id', '=', $templateId)
            ->where('ignored', '=', 0)
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return $result;
    }

    /*
     * Получение одного обьявления со всеми полями и данными по его ID и ID шаблона
     * */
    public static function adByTemplateByAdId($templateId, $adId) {

        $ad = Ad::where('id', '=', $adId)->where('template_id', '=', $templateId)->firstOrFail();

        $fieldTemplates = DB::table('fields')
            ->select('id', 'name')
            ->where('template_id', $templateId)
            ->get();

        /* Получаем из коллекции все ID полей */
        $fieldTemplatesId = $fieldTemplates->pluck('id');

        /* Выбираем все данные для этого обьявления */
        $contentsData = DB::table('contents')
            ->where('ad_id', $ad->id)
            ->whereIn('field_id', $fieldTemplatesId)
            ->get();

        $data = [];

        foreach ($fieldTemplates as $fieldTemplate) {
            foreach ($contentsData as $fieldValue) {
                if ($fieldTemplate->id === $fieldValue->field_id) {
                    $data[$fieldTemplate->name]['field'] = $fieldTemplate;
                    $data[$fieldTemplate->name]['data'] = $fieldValue;
                }
            }
        }

        /* Добавляем в атрибуты обьекта не "оригинальные" данные */
        $ad->fields = $data;

        /* Добавляем русскоязычные названия */

        $rusFieldsName = config('parser');

        foreach ($ad->fields as $fieldName => $fieldValue) {
            if (isset($rusFieldsName[$fieldName])) {
                $fieldValue['field']->rusName = $rusFieldsName[$fieldName];
            }
        }

        return $ad;
    }

    /* Экспорт данных по шаблону */
    public function export($templateId, $object, $type, $paginate = 'all')
    {

        // Выбираем все объявления для экспорта
        $ads = Ad::where('fetched', '=', 1)
            ->where('template_id', '=', $templateId)
            ->where('ignored', '=', 0)
            ->orderBy('created_at', 'asc')
            ->get();

        // Выбираем доступные поля
        $fieldTemplates = DB::table('fields')
            ->select('id', 'name')
            ->where('template_id', $templateId)
            ->get();


        $fieldTemplatesId = [];

        // Получаем ID всех полей обьявлений
        if ($object === 'ads') {
            /* Получаем из коллекции все ID полей */
            $fieldTemplatesId = $fieldTemplates->pluck('id');
        }

        // Получаем ID полей для Контактов
        if ($object === 'contacts') {
            // Находим поля контактов
            foreach ($fieldTemplates as $field) {
                if ($field->name === 'userId') {
                    $fieldTemplatesId[] = $field->id;
                }
                if ($field->name === 'userName') {
                    $fieldTemplatesId[] = $field->id;
                }
                if ($field->name === 'userTel') {
                    $fieldTemplatesId[] = $field->id;
                }
            }
        }

        // Получение основных данных
        $contentsData = [];
        $adsData = [];

        foreach ($ads as $ad) {

            $contentsData[$ad->id] = DB::table('contents')
                ->where('ad_id', $ad->id)
                ->whereIn('field_id', $fieldTemplatesId)
                ->get();

            // Добавление в массив некоторых данных из обьявления
            $adsData[$ad->id]['url'] = $ad->ad_url;
        }

        $data = [];
        foreach ($contentsData as $objectData) {
            foreach ($objectData as $content) {

                foreach ($fieldTemplates as $template) {
                    if ($template->id === $content->field_id) {

                        if ($template->name === 'userTel') {
                            $jsonString = $content->body;

                            $userTel = json_decode($jsonString);


                            if (is_array($userTel)) {
                                $telephones = implode(', ', $userTel);
                            } else {
                                $telephones = $userTel;
                            }

                            if (empty($telephones)) {
                                $telephones = 'Телефон не указан';
                            }

                            $data[$content->ad_id][$template->name] = '"' .  $telephones . '"';

                        } else {

                            $data[$content->ad_id][$template->name] = $content->body;

                            if ($object === 'ads') {
                                $data[$content->ad_id]['parsed_at'] = $content->created_at;
                                $data[$content->ad_id]['adId'] = $content->ad_id;

                                if (isset($adsData[$content->ad_id]['url'])) {
                                    $data[$content->ad_id]['adUrl'] = $adsData[$content->ad_id]['url'];
                                }
                            }

                        }
                    }
                }
            }

        }

        // Убираем дубли
        if ($object === 'contacts') {

            $tmpArr = [];
            foreach ($data as $key => $contactData) {
                if (!in_array($contactData['userId'], $tmpArr)) {
                    $tmpArr[] = $contactData['userId'];
                } else {
                    unset($data[$key]);
                }
            }
        }

        if (count($data) < 1) {
            return false;
        }

        $result = false;

        if ($type === 'csv') {
            $result = ArrayToExport::arrayToCsv($data);
        }

        return $result;
    }

    /*
     * Получение обьявлений поставленных в очередь по ID шаблона
     * */
    public static function listQueueAdsByTemplate($templateId) {
        $result = $result = Ad::where('fetched', '=', 0)->where('template_id', '=', $templateId)->paginate(50);

        return $result;
    }

    /*
     * Получение проигнорированных обьявлений ID шаблона
     * */
    public static function listIgnoredAdsByTemplate($templateId) {
        $result = $result = Ad::where('ignored', '=', 1)->where('template_id', '=', $templateId)->paginate(50);

        return $result;
    }


    /*
     * Статистика по конкретному шаблону
     * */
    public static function templateStatistics($templateId)
    {
        $statistics = [];

        /* Количество успешных */
        $statistics['adsDone'] = Ad::where('fetched', 1)->where('template_id', $templateId)->count();

        /* Количество поставленных в очередь */
        $statistics['adsQueue'] = Ad::where('fetched', 0)->where('template_id', $templateId)->count();

        /* Количество проигнорированных */
        $statistics['adsIgnored'] = Ad::where('ignored', 1)->where('template_id', $templateId)->count();

        return $statistics;
    }

    public static function lastUpdateFetched($templateId)
    {
        $query = Ad::select('created_at')
            ->where('fetched', false)
            ->where('template_id', $templateId)
            ->orderBy('updated_at', 'desc')
            ->first();

        if (empty($query)) {
            $query = 'Дата не доступна';

            return $query;
        }

        return $query->created_at->format('Y-m-d H:i');
    }

    public static function lastUpdateParsed($templateId)
    {
        $query = Ad::select('updated_at')
            ->where('fetched', true)
            ->where('template_id', $templateId)
            ->orderBy('updated_at', 'desc')
            ->first();

        if (empty($query)) {
            $query = 'Дата не доступна';

            return $query;
        }

        return $query->updated_at->format('Y-m-d H:i');
    }

    public static function countAdsParsed($templateID) {
        $result = Ad::where('fetched', 1)->where('template_id', $templateID)->count();

        return $result;
    }

    public static function countAdsFetched($templateID) {
        $result = Ad::where('fetched', 0)->where('template_id', $templateID)->count();

        return $result;
    }

    public static function countAdsIgnored($templateID) {
        $result = Ad::where('ignored', 1)->where('template_id', $templateID)->count();

        return $result;
    }

}
