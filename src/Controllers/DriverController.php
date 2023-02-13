<?php

namespace App\Controllers;

use App\Extractor\DriverExtractor;
use App\Utils\Logger\Logger;
use App\Utils\Curl\CurlController;
use App\Utils\Database\GetDatabase;
use App\Repository\DriverRepository;

class DriverController
{
    const TABLE_NAME = 'Driver';

    protected $db;
    protected $logger;
    protected $query;
    protected $extractor;

    public function __construct()
    {
        $this->db        = GetDatabase::getDatabase();
        $this->logger    = new Logger;
        $this->query     = new DriverRepository;
        $this->extractor = new DriverExtractor;
    }

    public function import($minSeason = 1950, $maxSeason = 2022)
    {
        // Boucle sur les saisons décroissantes pour avoir la structure de DB la plus complète pour la table des pilotes
        $currentSeason = $maxSeason;
        while ($currentSeason >= $minSeason) {
            $url = "http://ergast.com/api/f1/" . $currentSeason . "/drivers";

            $xml = CurlController::extract_xml($url);

            if ($currentSeason == $maxSeason) {
                $query = $this->extractor->drop_table(self::TABLE_NAME);
                $this->logger->log($query, false);
                $this->db->execute_query($query);

                $query = $this->extractor->create_table($xml, self::TABLE_NAME);
                $this->logger->log($query, false);
                $this->db->execute_query($query);
            }

            $queries = $this->extractor->insert($xml, self::TABLE_NAME);
            foreach ($queries as $query) {
                $this->db->execute_query($query);
                $this->logger->log($query, false);
            }

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
