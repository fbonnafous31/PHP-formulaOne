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

            foreach ($status->Status as $attribute) {
                $attributes .= QueryBuilder::build_attributes_columnlist($attribute->getName());

                foreach ($attribute->attributes() as $attribute => $value) {
                    $attributes .= QueryBuilder::build_attributes_columnlist($attribute);
                }
                break;
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

    public function insert($xml, string $tableName): array
    {
        $queries = [];
        foreach ($xml->StatusTable as $status) {
            $attributes = '';
            $values = '';

            foreach ($status->attributes() as $attribute => $value) {
                $attributes .= QueryBuilder::build_attributes_datalist($attribute);
                $values     .= QueryBuilder::build_values_datalist($value);
            }
            $head_attributes = $attributes;
            $head_values = $values;

            $attributes = '';
            $values = '';

            foreach ($status->Status as $attribute) {
                $attributes .= QueryBuilder::build_attributes_datalist($attribute->getName());
                $values     .= QueryBuilder::build_values_datalist($attribute->__toString());

                foreach ($attribute->attributes() as $attribute => $value) {
                    $attributes .= QueryBuilder::build_attributes_datalist($attribute);
                    $values     .= QueryBuilder::build_values_datalist($value);
                }
                $queries[] = 'INSERT into ' . $tableName . '(' . substr($head_attributes . ' ' . $attributes, 0, -2) . ') VALUES (' . substr($head_values . ' ' . $values, 0, -2) . ');';
                $attributes = '';
                $values = '';
            }
        }
        return $queries;
    }
}
