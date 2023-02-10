<?php

namespace App\Extractor;

use App\Extractor\ExtractorInterface;
use App\Utils\Controller\QueryBuilder;

class DriverExtractor implements ExtractorInterface
{
    public function create_table($xml, string $tableName): string
    {
        $attributes = '';
        $query  = 'CREATE TABLE ' . $tableName . ' (';
        foreach ($xml->DriverTable->attributes() as $attribute => $value) {
            $attributes .= QueryBuilder::build_attributes_columnlist($attribute);
        }

        foreach ($xml->DriverTable->Driver as $driver) {
            foreach ($driver->attributes() as $attribute => $value) {
                $attributes .= QueryBuilder::build_attributes_columnlist($attribute);
            }

            foreach ($driver as $attribute => $value) {
                $attributes .= QueryBuilder::build_attributes_columnlist($attribute);
            }
            break;
        }
        $query .= $attributes;
        $query = substr($query, 0, -2) .  ');';

        return $query;
    }

    public function drop_table(string $tableName): string
    {
        return "DROP TABLE IF EXISTS " . $tableName;
    }

    public function insert($xml, $tableName): array
    {
        $queries = [];
        foreach ($xml->DriverTable as $attr => $drivers) {
            foreach ($drivers as $driver) {
                $attributes = '';
                $values = '';

                foreach ($drivers->attributes() as $attribute => $value) {
                    $attributes .= QueryBuilder::build_attributes_datalist($attribute);
                    $values     .= QueryBuilder::build_values_datalist($value);
                }

                foreach ($driver->attributes() as $attribute => $value) {
                    $attributes .= QueryBuilder::build_attributes_datalist($attribute);
                    $values     .= QueryBuilder::build_values_datalist($value);
                }

                foreach ($driver as $attribute => $value) {
                    $attributes .= QueryBuilder::build_attributes_datalist($attribute);
                    $values     .= QueryBuilder::build_values_datalist($value);
                }
                $queries[] = 'INSERT into ' . $tableName . '(' . substr($attributes, 0, -2) . ') VALUES (' . substr($values, 0, -2) . ');';
            }
        }
        return $queries;
    }
}
