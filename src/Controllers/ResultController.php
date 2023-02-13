<?php

namespace App\Controllers;

use App\Extractor\ResultExtractor;
use App\Utils\Logger\Logger;
use App\Utils\Database\GetDatabase;
use App\Utils\Curl\CurlController;

class ResultController
{
    const MAX_ROUND  = 22;
    const TABLE_NAME = 'Result';

    protected $db;
    protected $logger;
    protected $extractor;

    public function __construct()
    {
        $this->db        = GetDatabase::getDatabase();
        $this->logger    = new Logger;
        $this->extractor = new ResultExtractor;
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
                    $query = $this->extractor->drop_table(self::TABLE_NAME);
                    $this->logger->log($query, false);
                    $this->db->execute_query($query);

                    $query = $this->extractor->create_table($xml, self::TABLE_NAME);
                    $this->logger->log($query, false);
                    $this->db->execute_query($query);
                }

                $queries = $this->extractor->insert($xml, self::TABLE_NAME);
                foreach ($queries as $query) {
                    $this->db->execute_query($query);
                    $this->logger->log($query, false);
                }

                $round++;
            }

            $currentSeason--;
            $round = 1;
        }
    }
}
