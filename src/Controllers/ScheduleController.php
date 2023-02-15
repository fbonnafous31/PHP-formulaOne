<?php

namespace App\Controllers;

use App\Utils\Curl\CurlController;
use App\Extractor\QueryExtractor;
use App\Extractor\ScheduleExtractor;

class ScheduleController
{
    const TABLE_NAME = 'Schedule';

    protected $extractor;

    public function __construct()
    {
        $this->extractor = new QueryExtractor(new ScheduleExtractor);
    }

    public function import($minSeason = 1950, $maxSeason = 2021)
    {
        $currentSeason = $maxSeason;
        while ($currentSeason >= $minSeason) {

            $url = "http://ergast.com/api/f1/" . $currentSeason;

            $xml = CurlController::extract_xml($url);

            dump($xml);
            dump(self::class);
            if ($currentSeason == $maxSeason) {
                $this->extractor->buildTable($xml, self::TABLE_NAME);
            }
            $this->extractor->buildQuery($xml, self::TABLE_NAME);

            $currentSeason--;
        }
    }
}
