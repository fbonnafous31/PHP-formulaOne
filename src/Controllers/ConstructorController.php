<?php

namespace App\Controllers;

use App\Extractor\ConstructorExtractor;
use App\Utils\Logger\Logger;
use App\Utils\Database\GetDatabase;
use App\Utils\Controller\QueryBuilder;
use App\Utils\Curl\CurlController;

class ConstructorController
{
    const TABLE_NAME = 'Constructor';

    protected $db;
    protected $logger;
    protected $extractor;

    public function __construct()
    {
        $this->db        = GetDatabase::getDatabase();
        $this->logger    = new Logger;
        $this->extractor = new ConstructorExtractor;
    }

    public function import($minSeason = 1950, $maxSeason = 2022)
    {
        $currentSeason = $maxSeason;
        while ($currentSeason >= $minSeason) {
            $url = "http://ergast.com/api/f1/" . $currentSeason . "/constructors";

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
