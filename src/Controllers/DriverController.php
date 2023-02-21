<?php

namespace App\Controllers;

use App\Extractor\DriverExtractor;
use App\Repository\DriverRepository;

class DriverController
{
    protected $query;

    public function __construct(DriverExtractor $extractor)
    {
        $this->query    = new DriverRepository;
    }

    public function show($minSeason = 1950, $maxSeason = 2023)
    {
        $loader = new \Twig\Loader\FilesystemLoader('./templates');

        $twig = new \Twig\Environment($loader);

        $drivers_list = $this->query->list_drivers($minSeason, $maxSeason);

        return $twig->render('/driver/list.html.twig', [
            'drivers' => $drivers_list
        ]);
    }
}
