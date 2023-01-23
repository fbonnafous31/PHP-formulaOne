<?php

require_once('libraries/autoload.php');

use App\Controllers\DriverController;

print "Page d'accueil <br><br>";

$url = "http://ergast.com/api/f1/2022/drivers";
$curl = curl_init();
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_URL, $url);
$data = curl_exec($curl);
curl_close($curl);
$xml = simplexml_load_string($data);

$driver = new DriverController();
$driver->create_table($xml);
$driver->insert_table($xml, 2022);

// $saison = 1950;
// while ($saison < 2023) {
//     $url = "http://ergast.com/api/f1/" . $saison . "/drivers";
//     $saison++;
//     var_dump($url);
//     echo "<br>";
// }
