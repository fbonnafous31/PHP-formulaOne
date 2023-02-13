<?php

namespace App\Controllers;

use App\Extractor\QueryExtractor;
use App\Extractor\ResultExtractor;
use App\Utils\Curl\CurlController;

class ResultController
{
    const MAX_ROUND  = 22;
    const TABLE_NAME = 'Result';

    protected $extractor;

    public function __construct()
    {
        $this->extractor  = new QueryExtractor(new ResultExtractor);
    }

    public function import($minSeason = 1950, $maxSeason = 2022)
    {
        $round = 1;
        $currentSeason = $maxSeason;
        while ($currentSeason >= $minSeason) {

            while ($round <= self::MAX_ROUND) {

                $url = "http://ergast.com/api/f1/" . $currentSeason . "/" . $round . "/results";

                $xml = CurlController::extract_xml($url);

                if (($currentSeason == $maxSeason) and ($round == 1)) {
                    $this->extractor->buildTable($xml, self::TABLE_NAME);
                }
                $this->extractor->buildQuery($xml, self::TABLE_NAME);

                $round++;
            }

            $currentSeason--;
            $round = 1;
        }
    }
}
