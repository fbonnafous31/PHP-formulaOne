<?php

use App\Controllers\DriverController;

include 'vendor/autoload.php';

require_once('libraries/autoload.php');

$driver = new DriverController;

echo $driver->show(2020, 2021);
