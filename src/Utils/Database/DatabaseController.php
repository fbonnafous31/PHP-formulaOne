<?php

namespace App\Utils\Database;

use PDO;

class DatabaseController
{

    private $db_name;
    private $db_user;
    private $db_pass;
    private $db_host;
    private $pdo;

    public function __construct($db_name, $db_user, $db_pass, $db_host = 'localhost')
    {
        $this->db_name = $db_name;
        $this->db_user = $db_user;
        $this->db_pass = $db_pass;
        $this->db_host = $db_host;
    }

    private function getPDO()
    {
        $pdo = new PDO('mysql:dbname=' . $this->db_name . ';host=' . $this->db_host . '', $this->db_user, $this->db_pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo = $pdo;
        return $pdo;
    }

    public function query($statement)
    {
        $query = $this->getPDO()->query($statement);
        $result = $query->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }

    public function query_attr($statement, $attributes, $classname)
    {
        $query = $this->getPDO()->prepare($statement);
        $query->execute($attributes);
        $result = $query->fetchAll(PDO::FETCH_CLASS, $classname);
        return $result;
    }

    public function execute_query($statement)
    {
        return $this->getPDO()->exec($statement);
    }
}
