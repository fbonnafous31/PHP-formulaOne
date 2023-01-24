<?php

require_once('libraries/autoload.php');

use App\Controllers\DriverController;

$url = "http://ergast.com/api/f1/2022/drivers";
$curl = curl_init();
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_URL, $url);
$data = curl_exec($curl);
curl_close($curl);
$xml = simplexml_load_string($data);

$driver = new DriverController();
$driver->create_table($xml);

$season = 1950;
while ($season < 2023) {
    $url = "http://ergast.com/api/f1/" . $season . "/drivers";

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_URL, $url);
    $data = curl_exec($curl);
    curl_close($curl);
    $xml = simplexml_load_string($data);

    $driver->insert_table($xml, $season);

    $season++;
    $create_table = true;
}
