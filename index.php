<?php

use App\Controllers\DriverController;
use App\Controllers\ResultController;
use App\Controllers\CircuitController;
use App\Controllers\ConstructorController;

include 'vendor/autoload.php';

require_once('libraries/autoload.php');

$result = new ResultController;

$result->import();

// $circuit = new CircuitController;

// $circuit->import();

// $driver = new DriverController;

// $driver->import();

// echo $driver->show(2020, 2022);

// $constructor = new ConstructorController;

// $constructor->import();
