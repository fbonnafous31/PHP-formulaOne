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
        echo "Pilotes . <br><br>";
    }

    // Création de la table
    $sql_create = 'CREATE TABLE driver (';
    $i = 0;
    foreach ($xml->DriverTable as $drivers) {
        foreach ($drivers as $driver) {
            $i++;
            // attributs
            foreach ($driver->attributes() as $attribute => $value) {
                $sql_create .= $attribute . ' VARCHAR(255), ';
                $columns .= $attribute . ', ';
            }

            // éléments du tableau
            $attributes = substr($attributes, 0, -2);
            foreach ($driver as $key => $value) {
                $sql_create .= $key . ' VARCHAR(255), ';
                $columns .= $key . ', ';
            }
            break 1;
        }
    }
    $sql_create = substr($sql_create, 0, -2) .  ')';
    $columns = substr($columns, 0, -2);

    echo $sql_create;

    echo "<br><br>";

    // Création des enregistrements
    foreach ($xml->DriverTable as $drivers) {
        foreach ($drivers as $driver) {
            $sql_insert = 'INSERT into driver (' . $columns . ') VALUES (';

            // attributs
            foreach ($driver->attributes() as $attribute => $value) {
                $sql_insert .= '\'' . $value . '\'' . ', ';
            }

            // éléments du tableau
            foreach ($driver as $key => $value) {
                $sql_insert .= '\'' . $value . '\'' . ', ';
            }
            $sql_insert = substr($sql_insert, 0, -2) .  ')';
            echo $sql_insert;
            $sql_insert = '';
            echo "<br><br>";
        }
    }


    // Affichage des données
    foreach ($xml->DriverTable as $drivers) {
        $sql_insert = 'INSERT into driver (' . $columns . ') VALUES (';
        foreach ($drivers as $driver) {
            // attributs
            foreach ($driver->attributes() as $attribute => $value) {
                $sql_insert .= '\'' . $value . '\'' . ', ';
                echo $attribute, ' : ', $value, "<br>";
            }

            // éléments du tableau
            foreach ($driver as $key => $value) {
                $sql_insert .= '\'' . $value . '\'' . ', ';
                echo $key . " : " . $value . "<br>";
            }
            $sql_insert = substr($sql_insert, 0, -2) .  ')';
            echo $sql_insert;
            echo "<br><br>";
        }
    }
