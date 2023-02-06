<?php

namespace App\Utils\Controller;

class QueryBuilder
{
    public static function build_attributes_columnlist($attribute)
    {
        return '`' . $attribute . '`' . ' VARCHAR(255), ';
    }

    public static function build_attributes_datalist($attribute)
    {
        return '`' . $attribute . '`' . ', ';
    }

    public static function build_values_datalist($value)
    {
        return '\'' . addslashes($value) . '\'' . ', ';
    }
}
