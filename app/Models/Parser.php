<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Facades\DB;
use App\Models\Ad;

class Parser extends Model
{
    private function curlInit($url) {

        $curlOptions = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_ENCODING => 'UTF-8',
            CURLOPT_SSL_VERIFYPEER => true,
        ];

        $curlInit = curl_init();
        curl_setopt_array($curlInit, $curlOptions);

        $html = curl_exec($curlInit);

        curl_close($curlInit);

        return $html;

    }

    public function getPaginatedUrl($mainUrl, $paginationSelector)
    {
        $requestPattern = '?page=';

        /* Get html source of page */
        $html = $this->curlInit($mainUrl);

        /* Get last pagination element */
        $crawler = new Crawler($html);

        $lastPaginationPage = $crawler->filter($paginationSelector)
            ->last()->text();

        /* Clean and settype to integer */
        $lastPaginationPage = (int)trim($lastPaginationPage);

        /* Create array with links to category page */
        $links = [];

        for ($i = 2; $i <= $lastPaginationPage; $i++) {
            $links[$i] = $mainUrl . $requestPattern . $i;
        }


        return $links;
    }

    /*
     * @array categoryPageUrls
     * @var pagesSelector
     *
     * */

    public function getUrlsFromCategory($categoryPageUrls, $pagesSelector) {

        /* Array for all page url on query */
        $allPageUrl = [];

        foreach ($categoryPageUrls as $url) {

            /* Get html source of page */
            $html = $this->curlInit($url);

            /* Get All Url from title */
            $crawler = new Crawler($html);

            $allPageUrl[] = $crawler->filter($pagesSelector)
                ->each(function (Crawler $node) {

                    /* filter every title and get href element */
                    $href = explode('#', $node->attr('href'));
                    return $href[0];
                });
        }

        return $allPageUrl;
    }

    public function createAdWithUrl($pagesUrl, $templateId) {

        /* Array for new inserted ads ID */
        $arrayIDs = [];

        foreach ($pagesUrl as $pages) {
            foreach ($pages as $adUrl) {

                $urlFromDb = DB::table('ads')->where('additional_url', $adUrl)->value('additional_url');

                if (!empty($urlFromDb)) {
                    break;
                }

                try {
                    $ad = new Ad();

                    $ad->additional_url = $adUrl;
                    $ad->template_id = $templateId;

                    $ad->save();

                    $arrayIDs[] = $ad->id;

                } catch (\Exception $e) {
                    return $e->getMessage();
                }
            }
        }

        $newListAds = DB::table('ads')
            ->select('id', 'additional_url', 'template_id')
            ->whereIn('id', $arrayIDs)
            ->get();

        return $newListAds;
    }

}
