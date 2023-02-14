<?php

use App\Utils\Router\Router;
use App\Controllers\DriverController;
use App\Controllers\ResultController;
use App\Extractor\ExtractorInterface;
use App\Controllers\CircuitController;
use App\Controllers\ConstructorController;
use App\Controllers\QualifyingController;

include 'vendor/autoload.php';

require_once('libraries/autoload.php');

Router::buildRoutes();

// $qualifying = new QualifyingController;

// $qualifying->import();

// $result = new ResultController;

// $result->import(2022);

// $circuit = new CircuitController;

// $circuit->import(2022);

// $driver = new DriverController;

// $driver->import(2022);

// echo $driver->show(2020, 2022);

// $constructor = new ConstructorController;

// $constructor->import(2022);
