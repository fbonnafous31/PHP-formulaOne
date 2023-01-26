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
        $sql_create = 'CREATE TABLE driver (';

        foreach ($xml->DriverTable->attributes() as $attribute => $value) {
            $sql_create .= $attribute . ' VARCHAR(255), ';
        }

        foreach ($xml->DriverTable->Driver as $driver) {
            foreach ($driver->attributes() as $attribute => $value) {
                $sql_create .= $attribute . ' VARCHAR(255), ';
            }

            foreach ($driver as $attribute => $value) {
                $sql_create .= $attribute . ' VARCHAR(255), ';
            }
            break 1;
        }
        $sql_create = substr($sql_create, 0, -2) .  ');';

        $this->db->execute_query("DROP TABLE IF EXISTS driver ");
        $this->db->execute_query($sql_create);

        Logger::log($sql_create, false);
    }

    public function insert_table($xml, $season)
    {
        $this->init_query();

        foreach ($xml->DriverTable as $attr => $drivers) {
            foreach ($drivers as $driver) {

                foreach ($drivers->attributes() as $attribute => $value) {
                    $this->build_query($attribute, $value);
                }

                foreach ($driver->attributes() as $attribute => $value) {
                    $this->build_query($attribute, $value);
                }

                foreach ($driver as $attribute => $value) {
                    $this->build_query($attribute, $value);
                }
                $this->sql_insert = 'INSERT into driver (' . substr($this->attributes, 0, -2) . ') VALUES (' . substr($this->values, 0, -2) . ');';

                $this->db->execute_query($this->sql_insert);

                Logger::log($this->sql_insert, false);
                $this->init_query();
            }
        }
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
