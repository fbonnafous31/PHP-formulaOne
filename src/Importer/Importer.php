<?php

namespace App\Importer;

use App\Extractor\ExtractorInterface;
use App\Extractor\QueryExtractor;
use App\Utils\Curl\CurlController;

class Importer
{
    protected $extractor;
    protected $tableName;

    public function __construct(ExtractorInterface $extractor, string $tableName)
    {
        $this->extractor = new QueryExtractor($extractor);
        $this->tableName = $tableName;
    }

    public function importBySeason($minSeason = 1950, $maxSeason = 2022)
    {
        $currentSeason = $maxSeason;
        while ($currentSeason >= $minSeason) {
            $url = "http://ergast.com/api/f1/" . $currentSeason . "/" . $this->tableName;

            $xml = CurlController::extract_xml($url);

            if ($currentSeason == $maxSeason) {
                $this->extractor->buildTable($xml, $this->tableName);
            }
            $this->extractor->buildQuery($xml, $this->tableName);

            $currentSeason--;
        }
    }
}
