<?php

use App\Importer\Importer;
use App\Utils\Router\Router;
use App\Extractor\DriverExtractor;
use App\Extractor\ResultExtractor;
use App\Extractor\CircuitExtractor;
use App\Extractor\PitStopExtractor;
use App\Extractor\ScheduleExtractor;
use App\Extractor\QualifyingExtractor;
use App\Extractor\ConstructorExtractor;
use App\Extractor\DriverStandingExtractor;
use App\Extractor\FinishingStatusExtractor;
use App\Extractor\ConstructorStandingExtractor;

include 'vendor/autoload.php';

require_once('libraries/autoload.php');

Router::buildRoutes();

$importer = new Importer();

$importer->importBySeason(new DriverExtractor, 'Drivers');
$importer->importBySeason(new ConstructorExtractor,  'Constructors');
$importer->importBySeason(new CircuitExtractor, 'Circuits');
$importer->importBySeason(new ScheduleExtractor, 'Schedules');
$importer->importByRound(new ResultExtractor, 'Results');
$importer->importByRound(new QualifyingExtractor, 'Qualifying');
$importer->importByRound(new DriverStandingExtractor, 'DriverStandings');
$importer->importByRound(new ConstructorStandingExtractor, 'ConstructorStandings');
$importer->importByRound(new FinishingStatusExtractor, 'Status');
$importer->importByStop(new PitStopExtractor, 'PitStops');
