<?php

namespace App\Extractor;

use App\Utils\Logger\Logger;
use App\Utils\Database\GetDatabase;
use App\Extractor\ExtractorInterface;

class QueryExtractor
{
    protected $db;
    protected $logger;
    protected $extractor;

    public function __construct(ExtractorInterface $extractor)
    {
        $this->db        = GetDatabase::getDatabase();
        $this->logger    = new Logger;
        $this->extractor = $extractor;
    }

    public function buildTable($xml, string $tableName)
    {
        $query = $this->extractor->drop_table($tableName);
        $this->logger->log($query, false);
        $this->db->execute_query($query);

        $query = $this->extractor->create_table($xml, $tableName);
        $this->logger->log($query, false);
        $this->db->execute_query($query);
    }

    public function buildQuery($xml, string $tableName)
    {
        $queries = $this->extractor->insert($xml, $tableName);
        foreach ($queries as $query) {
            // $this->logger->log($query, false);
            $this->db->execute_query($query);
        }
    }
}
