<?php

namespace App\Controllers;

use App\Utils\Logger\Logger;
use App\Utils\Database\GetDatabase;
use App\Utils\Controller\QueryBuilder;
use App\Utils\Curl\CurlController;

class ConstructorController
{
    protected $db;
    protected $logger;

    public function __construct()
    {
        $this->db     = GetDatabase::getDatabase();
        $this->logger = new Logger;
    }

    public function import($minSeason = 1950, $maxSeason = 2022)
    {
        $currentSeason = $maxSeason;
        while ($currentSeason >= $minSeason) {
            $url = "http://ergast.com/api/f1/" . $currentSeason . "/constructors";

            $xml = CurlController::extract_xml($url);

            if ($currentSeason == $maxSeason) $this->create_table($xml, 'constructor');

            $this->insert_data($xml);

            $currentSeason--;
        }
    }

    private function create_table($xml, $tableName)
    {
        $query = 'CREATE TABLE ' . $tableName . ' (';
        foreach ($xml->ConstructorTable->attributes() as $attribute => $value) {
            $query .= QueryBuilder::build_attributes_columnlist($attribute);
        }

        foreach ($xml->ConstructorTable->Constructor as $constructor) {
            foreach ($constructor->attributes() as $attribute => $value) {
                $query .= QueryBuilder::build_attributes_columnlist($attribute);
            }

            foreach ($constructor as $attribute => $value) {
                $query .= QueryBuilder::build_attributes_columnlist($attribute);
            }
            break 1;
        }
        $query = substr($query, 0, -2) .  ');';

        $this->db->execute_query("DROP TABLE IF EXISTS " . $tableName);
        $this->db->execute_query($query);

        $this->logger->log($query, false);
    }

    private function insert_data($xml)
    {
        foreach ($xml->ConstructorTable as $attr => $constructors) {
            foreach ($constructors as $constructor) {
                $attributes = '';
                $values = '';

                foreach ($constructors->attributes() as $attribute => $value) {
                    $attributes .= QueryBuilder::build_attributes_datalist($attribute);
                    $values     .= QueryBuilder::build_values_datalist($value);
                }

                foreach ($constructor->attributes() as $attribute => $value) {
                    $attributes .= QueryBuilder::build_attributes_datalist($attribute);
                    $values     .= QueryBuilder::build_values_datalist($value);
                }

                foreach ($constructor as $attribute => $value) {
                    $attributes .= QueryBuilder::build_attributes_datalist($attribute);
                    $values     .= QueryBuilder::build_values_datalist($value);
                }
                $query = 'INSERT into constructor (' . substr($attributes, 0, -2) . ') VALUES (' . substr($values, 0, -2) . ');';
                $this->logger->log($query, false);

                $this->db->execute_query($query);
            }
        }
    }
}
