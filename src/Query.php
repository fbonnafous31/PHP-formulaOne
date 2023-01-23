<?php

namespace App;

class Query
{
    private $columns;

    function create_table($xml)
    {
        $this->columns = '';
        $attributes = '';

        $sql_create = 'CREATE TABLE driver (';
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
        $this->log($sql_create);
    }

    function insert_table($xml)
    {
        foreach ($xml->DriverTable as $drivers) {
            foreach ($drivers as $driver) {

                $sql_insert = 'INSERT into driver (' . $this->columns . ') VALUES (';
                foreach ($driver->attributes() as $attribute => $value) {
                    $sql_insert .= '\'' . $value . '\'' . ', ';
                }

                foreach ($driver as $key => $value) {
                    $sql_insert .= '\'' . $value . '\'' . ', ';
                }
                $sql_insert = substr($sql_insert, 0, -2) .  ');';
                $this->log($sql_insert);
                $sql_insert = '';
            }
        }
    }

    function log($data)
    {
        $data = $data . "\n";
        $file = __DIR__ . '/../logs/log.txt';
        fopen($file, 'a');
        file_put_contents($file, $data, FILE_APPEND);
    }
}
