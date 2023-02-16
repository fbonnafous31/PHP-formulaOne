<?php

namespace App\Controllers;

use App\Extractor\QueryExtractor;
use App\Utils\Curl\CurlController;
use App\Extractor\FinishingStatusExtractor;

class FinishingStatusController
{
    const TABLE_NAME = 'FinishingStatus';

    protected $extractor;

    public function __construct()
    {
        $this->extractor  = new QueryExtractor(new FinishingStatusExtractor);
    }

    public function import($minSeason = 1950, $maxSeason = 2022)
    {
        $round = 1;
        $currentSeason = $maxSeason;
        while ($currentSeason >= $minSeason) {

            $url = "http://ergast.com/api/f1/" . $currentSeason . "/status";

            $xml = CurlController::extract_xml($url);

            dump($xml);

            if (($currentSeason == $maxSeason) and ($round == 1)) {
                $this->extractor->buildTable($xml, self::TABLE_NAME);
            }
            $this->extractor->buildQuery($xml, self::TABLE_NAME);

            $currentSeason--;
            $round = 1;
        }
    }
}
