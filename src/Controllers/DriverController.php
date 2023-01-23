<?php

namespace App\Controllers;

use App\Utils\Logger;

class DriverController
{
    private $columns;

    function create_table($xml)
    {
        $this->columns = '';
        $attributes = '';

        $sql_create = 'CREATE TABLE driver (season INT, ';
        foreach ($xml->DriverTable as $drivers) {
            foreach ($drivers as $driver) {

                foreach ($driver->attributes() as $attribute => $value) {
                    $sql_create .= $attribute . ' VARCHAR(255), ';
                    $this->columns .= $attribute . ', ';
                }

                $attributes = substr($attributes, 0, -2);
                foreach ($driver as $key => $value) {
                    $sql_create .= $key . ' VARCHAR(255), ';
                    $this->columns .= $key . ', ';
                }
                break 1;
            }
        }
        $sql_create = substr($sql_create, 0, -2) .  ');';
        $this->columns = substr($this->columns, 0, -2);
        Logger::log($sql_create, false);
    }

    function insert_table($xml, $season)
    {
        foreach ($xml->DriverTable as $drivers) {
            foreach ($drivers as $driver) {

                $sql_insert = 'INSERT into driver (season, ' . $this->columns . ') VALUES (' . $season . ',';
                foreach ($driver->attributes() as $attribute => $value) {
                    $sql_insert .= '\'' . $value . '\'' . ', ';
                }

                foreach ($driver as $key => $value) {
                    $sql_insert .= '\'' . $value . '\'' . ', ';
                }
                $sql_insert = substr($sql_insert, 0, -2) .  ');';
                Logger::log($sql_insert, false);
                $sql_insert = '';
            }
        }
    }

    function read_drivers($xml)
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
}
