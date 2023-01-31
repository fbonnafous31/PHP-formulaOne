<?php

namespace App\Repository;

use App\Utils\Database\GetDatabase;

class DriverRepository
{
    protected $database;

    public function __construct()
    {
        $this->database = GetDatabase::getDatabase();
    }

    public function list_drivers($minSeason = 1950, $maxSeason = 2022)
    {
        $query = "SELECT * FROM driver WHERE season BETWEEN $minSeason AND $maxSeason";

        $result = $this->database->query($query);

        return $result;
    }
}
