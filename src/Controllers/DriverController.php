<?php

namespace App\Controllers;

use App\Extractor\DriverExtractor;
use App\Extractor\QueryExtractor;
use App\Utils\Curl\CurlController;
use App\Repository\DriverRepository;

class DriverController
{
    const TABLE_NAME = 'Driver';

    protected $query;
    protected $extractor;

    public function __construct()
    {
        $this->query      = new DriverRepository;
        $this->extractor  = new QueryExtractor(new DriverExtractor);
    }

    public function import($minSeason = 1950, $maxSeason = 2022)
    {
        // Boucle sur les saisons décroissantes pour avoir la structure de DB la plus complète pour la table des pilotes
        $currentSeason = $maxSeason;
        while ($currentSeason >= $minSeason) {
            $url = "http://ergast.com/api/f1/" . $currentSeason . "/drivers";

            $xml = CurlController::extract_xml($url);

            if ($currentSeason == $maxSeason) {
                $this->extractor->buildTable($xml, self::TABLE_NAME);
            }
            $this->extractor->buildQuery($xml, self::TABLE_NAME);

            $currentSeason--;
        }
    }

    public function show($minSeason = 1950, $maxSeason = 2023)
    {
        $loader = new \Twig\Loader\FilesystemLoader('./templates');

        $twig = new \Twig\Environment($loader);

        $drivers_list = $this->query->list_drivers($minSeason, $maxSeason);

        return $twig->render('driver/list.html.twig', [
            'drivers' => $drivers_list
        ]);
    }
}
