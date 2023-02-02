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

            if ($currentSeason == $maxSeason) $this->create_table($xml);

            $this->insert_data($xml);

            $currentSeason--;
        }
    }

    private function create_table($xml)
    {
        $sql_create = 'CREATE TABLE constructor (';
        foreach ($xml->ConstructorTable->attributes() as $attribute => $value) {
            $sql_create .= $attribute . ' VARCHAR(255), ';
        }

        foreach ($xml->ConstructorTable->Constructor as $constructor) {
            foreach ($constructor->attributes() as $attribute => $value) {
                $sql_create .= $attribute . ' VARCHAR(255), ';
            }

            foreach ($constructor as $attribute => $value) {
                $sql_create .= $attribute . ' VARCHAR(255), ';
            }
            break 1;
        }
        $sql_create = substr($sql_create, 0, -2) .  ');';

        $this->db->execute_query("DROP TABLE IF EXISTS constructor ");
        $this->db->execute_query($sql_create);

        $this->logger->log($sql_create, false);
    }

    private function insert_data($xml)
    {
        $builder = new QueryBuilder;

        foreach ($xml->ConstructorTable as $attr => $constructors) {
            foreach ($constructors as $constructor) {
                $attributes = '';
                $values = '';

                foreach ($constructors->attributes() as $attribute => $value) {
                    $attributes .= $builder->build_attributes($attribute);
                    $values .= $builder->build_values($value);
                }

                foreach ($constructor->attributes() as $attribute => $value) {
                    $attributes .= $builder->build_attributes($attribute);
                    $values .= $builder->build_values($value);
                }

                foreach ($constructor as $attribute => $value) {
                    $attributes .= $builder->build_attributes($attribute);
                    $values .= $builder->build_values($value);
                }
                $query = 'INSERT into constructor (' . substr($attributes, 0, -2) . ') VALUES (' . substr($values, 0, -2) . ');';
                $this->logger->log($query, false);

                $this->db->execute_query($query);
            }
        }
    }
}
