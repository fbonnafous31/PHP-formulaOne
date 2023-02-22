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

$importer->importBySeason(new DriverExtractor, 'Drivers', 2022);
$importer->importBySeason(new ConstructorExtractor,  'Constructors', 2022);
$importer->importBySeason(new CircuitExtractor, 'Circuits', 2022);
$importer->importBySeason(new ScheduleExtractor, 'Schedules', 2022);
$importer->importByRound(new ResultExtractor, 'Results', 2022);
$importer->importByRound(new QualifyingExtractor, 'Qualifying', 2022);
$importer->importByRound(new DriverStandingExtractor, 'DriverStandings', 2022);
$importer->importByRound(new ConstructorStandingExtractor, 'ConstructorStandings', 2022);
$importer->importByRound(new FinishingStatusExtractor, 'Status', 2022);
$importer->importByStop(new PitStopExtractor, 'PitStops', 2022);
