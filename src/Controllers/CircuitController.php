<?php

namespace App\Controllers;

use App\Utils\Logger\Logger;
use App\Utils\Database\GetDatabase;
use App\Utils\Controller\QueryBuilder;
use App\Utils\Curl\CurlController;

class CircuitController
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

            $url = "http://ergast.com/api/f1/" . $currentSeason . "/circuits";

            $xml = CurlController::extract_xml($url);

            if ($currentSeason == $maxSeason) $this->create_table($xml, 'circuit');

            $this->insert_data($xml);

            $currentSeason--;
        }
    }

    private function create_table($xml, $tableName)
    {
        $query = 'CREATE TABLE ' . $tableName . ' (';
        foreach ($xml->CircuitTable->attributes() as $attribute => $value) {
            $query .= QueryBuilder::build_attributes_columnlist($attribute);
        }

        foreach ($xml->CircuitTable->Circuit as $circuit) {
            foreach ($circuit->attributes() as $attribute => $value) {
                $query .= QueryBuilder::build_attributes_columnlist($attribute);
            }

            foreach ($circuit as $attribute => $value) {
                if ($attribute != 'Location') {
                    $query .= QueryBuilder::build_attributes_columnlist($attribute);
                }
            }
            break 1;
        }

        foreach ($xml->CircuitTable->Circuit->Location as $location) {
            foreach ($location->attributes() as $attribute => $value) {
                $query .= QueryBuilder::build_attributes_columnlist($attribute);
            }

            foreach ($location as $attribute => $value) {
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
        foreach ($xml->CircuitTable as $attr => $circuits) {
            foreach ($circuits as $circuit) {
                $attributes = '';
                $values = '';

                foreach ($circuits->attributes() as $attribute => $value) {
                    $attributes .= QueryBuilder::build_attributes_datalist($attribute);
                    $values     .= QueryBuilder::build_values_datalist($value);
                }

                foreach ($circuit->attributes() as $attribute => $value) {
                    $attributes .= QueryBuilder::build_attributes_datalist($attribute);
                    $values     .= QueryBuilder::build_values_datalist($value);
                }

                foreach ($circuit as $attribute => $value) {
                    if ($attribute != 'Location') {
                        $attributes .= QueryBuilder::build_attributes_datalist($attribute);
                        $values     .= QueryBuilder::build_values_datalist($value);
                    }
                }

                foreach ($circuit->Location as $location) {
                    foreach ($location->attributes() as $attribute => $value) {
                        $attributes .= QueryBuilder::build_attributes_datalist($attribute);
                        $values     .= QueryBuilder::build_values_datalist($value);
                    }

                    foreach ($location as $attribute => $value) {
                        $attributes .= QueryBuilder::build_attributes_datalist($attribute);
                        $values     .= QueryBuilder::build_values_datalist($value);
                    }
                    break 1;
                }
                $query = 'INSERT into circuit (' . substr($attributes, 0, -2) . ') VALUES (' . substr($values, 0, -2) . ');';
                $this->logger->log($query, false);

                $this->db->execute_query($query);
            }
        }
    }
}
