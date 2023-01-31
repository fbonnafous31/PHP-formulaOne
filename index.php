<?php

use App\Utils\Database\GetDatabase;

include 'vendor/autoload.php';

require_once('libraries/autoload.php');

$loader = new \Twig\Loader\FilesystemLoader('./templates');

$twig = new \Twig\Environment($loader);

$db = GetDatabase::getDatabase();

$drivers_list = $db->query("SELECT * FROM driver WHERE season = 2022");

echo $twig->render('driver/list.html.twig', [
    'drivers' => $drivers_list
]);
