<?php

namespace App\Utils\Database;

use App\Utils\Database\Database;

class GetDatabase
{

    const DB_NAME = 'formulaone';
    const DB_USER = 'fbonnafous';
    const DB_PASS = '';
    const DB_HOST = 'localhost';

    private static $database;

    public static function getDatabase()
    {
        if (self::$database === null) {
            self::$database = new DatabaseController(self::DB_NAME, self::DB_USER, self::DB_PASS, self::DB_HOST);
        }
        return self::$database;
    }
}
