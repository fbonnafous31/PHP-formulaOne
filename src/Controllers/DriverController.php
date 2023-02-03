<?php

namespace App\Controllers;

use App\Utils\Logger\Logger;
use App\Utils\Curl\CurlController;
use App\Utils\Database\GetDatabase;
use App\Repository\DriverRepository;
use App\Utils\Controller\QueryBuilder;

class DriverController
{
    protected $db;
    protected $logger;
    protected $query;

    public function __construct()
    {
        $this->db     = GetDatabase::getDatabase();
        $this->logger = new Logger;
        $this->query  = new DriverRepository;
    }

    public function import($minSeason = 1950, $maxSeason = 2022)
    {
        // Boucle sur les saisons décroissantes pour avoir la structure de DB la plus complète pour la table des pilotes
        $currentSeason = $maxSeason;
        while ($currentSeason >= $minSeason) {
            $url = "http://ergast.com/api/f1/" . $currentSeason . "/drivers";

            $xml = CurlController::extract_xml($url);

            if ($currentSeason == $maxSeason) $this->create_table($xml);

            $this->insert_data($xml);

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

    private function create_table($xml)
    {
        $sql_create = 'CREATE TABLE driver (';
        foreach ($xml->DriverTable->attributes() as $attribute => $value) {
            $sql_create .= $attribute . ' VARCHAR(255), ';
        }

        foreach ($xml->DriverTable->Driver as $driver) {
            foreach ($driver->attributes() as $attribute => $value) {
                $sql_create .= $attribute . ' VARCHAR(255), ';
            }

            foreach ($driver as $attribute => $value) {
                $sql_create .= $attribute . ' VARCHAR(255), ';
            }
            break 1;
        }
        $sql_create = substr($sql_create, 0, -2) .  ');';

        $this->db->execute_query("DROP TABLE IF EXISTS driver ");
        $this->db->execute_query($sql_create);

        $this->logger->log($sql_create, false);
    }

    private function insert_data($xml, $season = 2022)
    {
        foreach ($xml->DriverTable as $attr => $drivers) {
            foreach ($drivers as $driver) {
                $attributes = '';
                $values = '';

                foreach ($drivers->attributes() as $attribute => $value) {
                    $attributes .= QueryBuilder::build_attributes_list($attribute);
                    $values     .= QueryBuilder::build_values_list($value);
                }

                foreach ($driver->attributes() as $attribute => $value) {
                    $attributes .= QueryBuilder::build_attributes_list($attribute);
                    $values     .= QueryBuilder::build_values_list($value);
                }

                foreach ($driver as $attribute => $value) {
                    $attributes .= QueryBuilder::build_attributes_list($attribute);
                    $values     .= QueryBuilder::build_values_list($value);
                }
                $query = 'INSERT into driver (' . substr($attributes, 0, -2) . ') VALUES (' . substr($values, 0, -2) . ');';

                $this->db->execute_query($query);

                $this->logger->log($query, false);
            }
        }
    }
}
