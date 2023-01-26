<?php

function read_api_drivers($xml)
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
