<<?php

    $url = "http://ergast.com/api/f1/2022/drivers";
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_URL, $url);

    $data = curl_exec($curl);
    curl_close($curl);

    $xml = simplexml_load_string($data);

    // Initialisation du premier attribut (erreur dans les données restituées)
    foreach ($xml->DriverTable->attributes() as $attribute => $value) {
        echo $attribute, ' : ', $value, "<br>";
    }

    foreach ($xml->DriverTable as $drivers) {
        foreach ($drivers as $driver) {
            // attributs
            foreach ($driver->attributes() as $attribute => $value) {
                echo $attribute, ' : ', $value, "<br>";
            }

            // éléments du tableau
            foreach ($driver as $key => $value) {
                echo $key . " : " . $value . "<br>";
            }
            echo "<br>";
        }
    }
