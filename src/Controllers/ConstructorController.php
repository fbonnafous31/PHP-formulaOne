<?php

namespace App\Controllers;

use App\Extractor\QueryExtractor;
use App\Utils\Curl\CurlController;
use App\Extractor\ConstructorExtractor;

class ConstructorController
{
    const TABLE_NAME = 'Constructor';

    protected $extractor;

    public function __construct()
    {
        $this->extractor = new QueryExtractor(new ConstructorExtractor);
    }

    public function import($minSeason = 1950, $maxSeason = 2022)
    {
        $currentSeason = $maxSeason;
        while ($currentSeason >= $minSeason) {
            $url = "http://ergast.com/api/f1/" . $currentSeason . "/constructors";

            $xml = CurlController::extract_xml($url);

            if ($currentSeason == $maxSeason) {
                $this->extractor->buildTable($xml, self::TABLE_NAME);
            }
            $this->extractor->buildQuery($xml, self::TABLE_NAME);

            $currentSeason--;
        }
    }
}
