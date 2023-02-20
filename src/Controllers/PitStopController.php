<?php

namespace App\Controllers;

use App\Extractor\QueryExtractor;
use App\Utils\Curl\CurlController;
use App\Extractor\PitStopExtractor;

class PitStopController
{
    const MAX_ROUND  = 22;
    const MAX_STOP   = 10;
    const TABLE_NAME = 'PitStop';

    protected $extractor;

    public function __construct()
    {
        $this->extractor  = new QueryExtractor(new PitStopExtractor);
    }

    public function import($minSeason = 1950, $maxSeason = 2022)
    {
        $round = 1;
        $stop = 1;
        $currentSeason = $maxSeason;
        while ($currentSeason >= $minSeason) {

            while ($round <= self::MAX_ROUND) {

                while ($stop  <= self::MAX_STOP) {

                    $url = "http://ergast.com/api/f1/" . $currentSeason . "/" . $round . "/pitstops/" . $stop;

                    $xml = CurlController::extract_xml($url);

                    if (($currentSeason == $maxSeason) and ($round == 1) and ($stop == 1)) {
                        $this->extractor->buildTable($xml, self::TABLE_NAME);
                    }
                    $this->extractor->buildQuery($xml, self::TABLE_NAME);

                    $stop++;
                }
                $round++;
                $stop = 1;
            }

            $currentSeason--;
            $round = 1;
        }
    }
}
