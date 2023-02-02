<?php

use App\Controllers\DriverController;
use App\Controllers\ConstructorController;

include 'vendor/autoload.php';

require_once('libraries/autoload.php');

$driver = new DriverController;

// echo $driver->show(2020, 2021);

$results = new ConstructorController;

$results->import();
