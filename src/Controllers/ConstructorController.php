<?php

namespace App\Controllers;

use App\Utils\Logger\Logger;
use App\Utils\Database\GetDatabase;
use App\Repository\DriverRepository;

class ConstructorController
{

    private $attributes;
    private $values;
    private $sql_insert;

    protected $db;
    protected $logger;
    protected $query;

    public function __construct()
    {
        $this->db     = GetDatabase::getDatabase();
        $this->logger = new Logger;
        $this->query  = new DriverRepository;
    }

    public function import($minSeason = 1950, $maxSeason = 2022)
    {
        $currentSeason = $maxSeason;
        while ($currentSeason >= $minSeason) {
            $url = "http://ergast.com/api/f1/" . $currentSeason . "/constructors";
            $xml = $this->extract_xml($url);

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

    private function insert_data($xml, $season = 2022)
    {
        $this->init_query();

        foreach ($xml->ConstructorTable as $attr => $constructors) {
            foreach ($constructors as $constructor) {

                foreach ($constructors->attributes() as $attribute => $value) {
                    $this->build_query($attribute, $value);
                }

                foreach ($constructor->attributes() as $attribute => $value) {
                    $this->build_query($attribute, $value);
                }

                foreach ($constructor as $attribute => $value) {
                    $this->build_query($attribute, $value);
                }
                $this->sql_insert = 'INSERT into constructor (' . substr($this->attributes, 0, -2) . ') VALUES (' . substr($this->values, 0, -2) . ');';

                $this->db->execute_query($this->sql_insert);

                $this->logger->log($this->sql_insert, false);
                $this->init_query();
            }
        }
    }

    private function extract_xml($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_URL, $url);
        $data = curl_exec($curl);
        curl_close($curl);
        $xml = simplexml_load_string($data);

        return $xml;
    }

    private function init_query()
    {
        $this->attributes = '';
        $this->values = '';
        $this->sql_insert = '';
    }

    private function build_query($attribute, $value)
    {
        $this->attributes  .= $attribute . ', ';
        $this->values .= '\'' . addslashes($value) . '\'' . ', ';
    }
}
