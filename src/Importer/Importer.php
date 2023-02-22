<?php

namespace App\Importer;

use App\Extractor\ExtractorInterface;
use App\Extractor\QueryExtractor;
use App\Utils\Curl\CurlController;

class Importer
{
    const MAX_ROUND  = 22;
    const MAX_STOP   = 10;

    public function importBySeason(ExtractorInterface $extractor, string $tableName, $minSeason = 1950, $maxSeason = 2022)
    {
        $extractor = new QueryExtractor($extractor);

        $currentSeason = $maxSeason;
        while ($currentSeason >= $minSeason) {
            $url = "http://ergast.com/api/f1/" . $currentSeason . "/" . $tableName;

            if ($tableName == 'Schedules') $url = "http://ergast.com/api/f1/" . $currentSeason;

            $xml = CurlController::extract_xml($url);

            if ($currentSeason == $maxSeason) {
                $extractor->buildTable($xml, $tableName);
            }
            $extractor->buildQuery($xml, $tableName);

            $currentSeason--;
        }
    }

    public function importByRound(ExtractorInterface $extractor, string $tableName, $minSeason = 1950, $maxSeason = 2022)
    {
        $extractor = new QueryExtractor($extractor);

        $round = 1;
        $currentSeason = $maxSeason;
        while ($currentSeason >= $minSeason) {

            while ($round <= self::MAX_ROUND) {

                $url = "http://ergast.com/api/f1/" . $currentSeason . "/" . $round . "/"  . $tableName;

                $xml = CurlController::extract_xml($url);

                if (($currentSeason == $maxSeason) and ($round == 1)) {
                    $extractor->buildTable($xml, $tableName);
                }
                $extractor->buildQuery($xml, $tableName);

                $round++;
            }

            $currentSeason--;
            $round = 1;
        }
    }

    public function importByStop(ExtractorInterface $extractor, string $tableName, $minSeason = 1950, $maxSeason = 2022)
    {
        $extractor = new QueryExtractor($extractor);

        $round = 1;
        $stop = 1;
        $currentSeason = $maxSeason;
        while ($currentSeason >= $minSeason) {

            while ($round <= self::MAX_ROUND) {

                while ($stop  <= self::MAX_STOP) {

                    $url = "http://ergast.com/api/f1/" . $currentSeason . "/" . $round . "/" . $tableName . "/" . $stop;

                    $xml = CurlController::extract_xml($url);

                    if (($currentSeason == $maxSeason) and ($round == 1) and ($stop == 1)) {
                        $extractor->buildTable($xml, $tableName);
                    }
                    $extractor->buildQuery($xml, $tableName);

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
