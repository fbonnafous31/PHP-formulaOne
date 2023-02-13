<?php

namespace App\Controllers;

use App\Utils\Logger\Logger;
use App\Utils\Curl\CurlController;
use App\Extractor\CircuitExtractor;
use App\Utils\Database\GetDatabase;

class CircuitController
{
    const TABLE_NAME = 'Circuit';

    protected $db;
    protected $logger;
    protected $extractor;

    public function __construct()
    {
        $this->db        = GetDatabase::getDatabase();
        $this->logger    = new Logger;
        $this->extractor = new CircuitExtractor;
    }

    public function import($minSeason = 1950, $maxSeason = 2022)
    {
        $currentSeason = $maxSeason;
        while ($currentSeason >= $minSeason) {

            $url = "http://ergast.com/api/f1/" . $currentSeason . "/circuits";

            $xml = CurlController::extract_xml($url);
            if ($currentSeason == $maxSeason) {
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

            $currentSeason--;
        }
    }
}
