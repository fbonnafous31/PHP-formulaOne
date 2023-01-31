<?php

use App\Repository\DriverRepository;

include 'vendor/autoload.php';

require_once('libraries/autoload.php');

$loader = new \Twig\Loader\FilesystemLoader('./templates');

$twig = new \Twig\Environment($loader);

$repository = new DriverRepository;

$drivers_list = $repository->list_drivers(2020, 2021);

echo $twig->render('driver/list.html.twig', [
    'drivers' => $drivers_list
]);
