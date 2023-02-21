<?php

use App\Utils\Router\Router;
use App\Controllers\DriverController;
use App\Controllers\ResultController;
use App\Extractor\ExtractorInterface;
use App\Controllers\CircuitController;
use App\Controllers\ScheduleController;
use App\Controllers\QualifyingController;
use App\Controllers\ConstructorController;
use App\Controllers\DriverStandingController;
use App\Controllers\FinishingStatusController;
use App\Controllers\ConstructorStandingController;
use App\Controllers\PitStopController;
use App\Extractor\DriverExtractor;
use App\Importer\Importer;

include 'vendor/autoload.php';

require_once('libraries/autoload.php');

// Router::buildRoutes();

// $pitStop = new PitStopController;

// $pitStop->import();

// $constructorStanding = new ConstructorStandingController;

// $constructorStanding->import();

// $finishingStatus = new FinishingStatusController;

// $finishingStatus->import();

// $driverStanding = new DriverStandingController;

// $driverStanding->import();

// $schedule = new ScheduleController;

// $schedule->import();

// $qualifying = new QualifyingController;

// $qualifying->import();

// $result = new ResultController;

// $result->import(2022);

// $circuit = new CircuitController;

// $circuit->import(2022);

$driver = new Importer(new DriverExtractor, 'Drivers');

$driver->importBySeason(2021, 2021);

// echo $driver->show(2020, 2022);

// $constructor = new ConstructorController;

// $constructor->import(2022);
