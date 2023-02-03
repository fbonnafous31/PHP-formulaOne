<?php

namespace App\Utils\Controller;

class QueryBuilder
{
    public static function build_attributes_list($attribute)
    {
        return $attribute . ', ';
    }

    public static function build_values_list($value)
    {
        return '\'' . addslashes($value) . '\'' . ', ';
    }
}
