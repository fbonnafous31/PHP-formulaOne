<?php

namespace App\Controllers;

use App\Extractor\QueryExtractor;
use App\Utils\Curl\CurlController;
use App\Extractor\ConstructorStandingExtractor;

class ConstructorStandingController
{
    const MAX_ROUND  = 22;
    const TABLE_NAME = 'ConstructorStanding';

    protected $extractor;

    public function __construct()
    {
        $this->extractor  = new QueryExtractor(new ConstructorStandingExtractor);
    }

    public function import($minSeason = 1950, $maxSeason = 2022)
    {
        $round = 1;
        $currentSeason = $maxSeason;
        while ($currentSeason >= $minSeason) {

            while ($round <= self::MAX_ROUND) {
                $url = "http://ergast.com/api/f1/" . $currentSeason . "/" . $round . "/constructorStandings";

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