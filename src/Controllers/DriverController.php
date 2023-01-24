<?php

namespace App\Controllers;

use App\Utils\Database\GetDatabase;
use App\Utils\Logger\Logger;

class DriverController
{
    private $attributes;
    private $values;
    private $sql_insert;

    protected $db;

    public function __construct()
    {
        $this->db = GetDatabase::getDatabase();
    }

    public function create_table($xml)
    {
        $sql_create = 'CREATE TABLE driver (season INT, ';
        foreach ($xml->DriverTable as $drivers) {
            foreach ($drivers as $driver) {

                foreach ($driver->attributes() as $attribute => $value) {
                    $sql_create .= $attribute . ' VARCHAR(255), ';
                }

                foreach ($driver as $attribute => $value) {
                    $sql_create .= $attribute . ' VARCHAR(255), ';
                }
                break 1;
            }
        }
        $sql_create = substr($sql_create, 0, -2) .  ');';

        $this->db->execute("DROP TABLE IF EXISTS driver ");
        $this->db->execute($sql_create);

        Logger::log($sql_create, false);
    }

    public function insert_table($xml, $season)
    {
        $this->init_parameters();

        foreach ($xml->DriverTable as $drivers) {
            foreach ($drivers as $driver) {

                foreach ($driver->attributes() as $attribute => $value) {
                    $this->attributes  .= $attribute . ', ';
                    $this->values .= '\'' . addslashes($value) . '\'' . ', ';
                }

                foreach ($driver as $attribute => $value) {
                    $this->attributes  .= $attribute . ', ';
                    $this->values .= '\'' . addslashes($value) . '\'' . ', ';
                }
                $this->sql_insert = 'INSERT into driver (season, ' . substr($this->attributes, 0, -2) . ') VALUES (' . $season . ', ' . substr($this->values, 0, -2) . ');';

                $this->db->execute($this->sql_insert);

                Logger::log($this->sql_insert, false);
                $this->init_parameters();
            }
        }
    }

    public function read_api_drivers($xml)
    {
        foreach ($xml->DriverTable as $drivers) {
            foreach ($drivers as $driver) {
                foreach ($driver->attributes() as $attribute => $value) {
                    echo $attribute, ' : ', $value, "<br>";
                }

                foreach ($driver as $key => $value) {
                    echo $key . " : " . $value . "<br>";
                }
                echo "<br><br>";
            }
        }
    }

    private function init_parameters()
    {
        $this->attributes = '';
        $this->values = '';
        $this->sql_insert = '';
    }
}
