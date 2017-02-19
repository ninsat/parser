<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Facades\DB;
use App\Models\Content;

class Ad extends Model
{
    protected $table = 'ads';
    public $timestamps = true;

    public function adsFilling() {
        $fieldData = []; // Для тестирования

        /* Выбираем обьявления для которых еще не были получены данные из парсинга */
        $adNotProcessing = DB::table('ads')
            ->select('id', 'ad_url', 'template_id', 'created_at')
            ->where('fetched', '=', 0) //  Обьявление еще не заполненое данными
            ->orderBy('created_at', 'asc')
            ->take(5) // Ограничение наполнения обявлений за один запуск скрипта
            ->get();

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

            /* Если по текущему адресу уже нет страницы с обьявлением переходим к след. итерации */
            if (empty($html)) {
                continue;
            }

            $crawler = new Crawler($html);

            /* В цикле сохраняем в поля спарсенные данные текущего обьявления */
            foreach ($fields as $field) {

                $fieldData[$ad->id][$field->name] = DataProcessing::processingData($crawler, $field, $ad->ad_url);

                /* Поле требующее дополнительный запрос CURL */
                if ($field->name === 'userTel') {
                    $sourceContactPage = Parser::htmlSource($fieldData[$ad->id][$field->name], $ad->ad_url);
                    $fieldData[$ad->id][$field->name] = DataProcessing::getUserTelephones($sourceContactPage);

                }



                /* Создаем обьект Модели */
            //    $content = new Content();

                /* Наполняем */
            //    $content->field_id = $field->id;
            //    $content->ad_id = $ad->id;
            //    $content->body = $dataFieldNotProcessing;

                /* Сохраняем */
             //   $content->save();
            }

            /* Если все успешно обновляем статус обьявления */
            //DB::table('ads')
            //    ->where('id', $ad->id)
            //    ->update(['fetched' => 1]);
        }

        return $fieldData;
    }

    public function adsUrlsStore($templateId) {

        try {
            $fields = DB::table('fields')
                ->select('id', 'name', 'selector')
                ->where('template_id', '=', $templateId)
                ->get();

        } catch (\Exception $e) {

            return $e->getMessage();
        }

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

        /* Get html source of page */
        $html = Parser::htmlSource($startUrl);

        /* Get last pagination element */
        $crawler = new Crawler($html);

        $lastPaginationPage = $crawler->filter($paginationSelector)
            ->last()->text();

        /* Clean and settype to integer */
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

            /* Get html source of page */
            $html = Parser::htmlSource($url);

            /* Get All Url from title */
            $crawler = new Crawler($html);

            $adUrls[] = $crawler->filter($adUrlSelector)
                ->each(function (Crawler $node) {

                    /* filter every title and get href element */
                    $href = explode('#', $node->attr('href'));
                    return $href[0];

                });
        }

        return $adUrls;
    }

    protected function bulkStoreAd($adUrls, $templateId) {

        /* Array for new inserted ads ID */
        $IdArray = [];

        foreach ($adUrls as $pages) {
            foreach ($pages as $adUrl) {

                $urlFromDb = DB::table('ads')->where('ad_url', $adUrl)->value('ad_url');

                /* Save ad to DB and return own ID to array */
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

        $result = DB::table('ads')
            ->select('id', 'ad_url', 'template_id')
            ->whereIn('id', $IdArray)
            ->get();

        return $result;
    }

}
