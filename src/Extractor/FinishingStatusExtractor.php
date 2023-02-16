<?php

namespace App\Extractor;

use App\Utils\Controller\QueryBuilder;

class FinishingStatusExtractor implements ExtractorInterface
{
    public function create_table($xml, string $tableName): string
    {
        $attributes = '';
        $query  = 'CREATE TABLE ' . $tableName . ' (';

        foreach ($xml->StatusTable as $status) {
            foreach ($status->attributes() as $attribute => $value) {
                $attributes .= QueryBuilder::build_attributes_columnlist($attribute);
            }
        }
        $attributes .= QueryBuilder::build_attributes_columnlist('status');


        $query .= $attributes;
        $query = substr($query, 0, -2) .  ');';
        return $query;
    }

    public function drop_table(string $tableName): string
    {
        return "DROP TABLE IF EXISTS " . $tableName;
    }

    public function insert($xml, string $tableName): array
    {
        $queries = [];

        foreach ($xml->StatusTable as $status) {
            $attributes = '';
            $values = '';

            foreach ($status as $attribute => $value) {
                foreach ($status->attributes() as $attr => $val) {
                    $attributes .= QueryBuilder::build_attributes_datalist($attr);
                    $values     .= QueryBuilder::build_values_datalist($val);
                }

                $attributes .= QueryBuilder::build_attributes_datalist($attribute);
                $values     .= QueryBuilder::build_values_datalist($value);

                $queries[] = 'INSERT into ' . $tableName . '(' . substr($attributes, 0, -2) . ') VALUES (' . substr($values, 0, -2) . ');';

                $attributes = '';
                $values = '';
            }
        }
        return $queries;
    }
}
