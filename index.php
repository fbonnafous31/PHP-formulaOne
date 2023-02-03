<?php

use App\Controllers\DriverController;
use App\Controllers\ConstructorController;

include 'vendor/autoload.php';

require_once('libraries/autoload.php');

$driver = new DriverController;

// $driver->import();

// echo $driver->show(2020, 2022);

$results = new ConstructorController;

$results->import();
