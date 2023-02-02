<?php

namespace App\Utils\Controller;

class QueryBuilder
{
    public function build_attributes($attribute)
    {
        return $attribute . ', ';
    }

    public function build_values($value)
    {
        return '\'' . addslashes($value) . '\'' . ', ';
    }
}
