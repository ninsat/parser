<?php

namespace App\Models;

use App\Models\Parser;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Facades\DB;

class Ad extends Model
{
    protected $table = 'ads';
    public $timestamps = true;

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

        /* Create array with links to category page */
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

                if (empty($urlFromDb)) {
                    try {
                        $ad = new Ad();

                        $ad->ad_url = $adUrl;
                        $ad->template_id = $templateId;
                        $ad->fetched = 0;

                        $ad->save();

                        $IdArray[] = $ad->id;

                    } catch (\Exception $e) {

                        return $e->getMessage();
                    }

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
