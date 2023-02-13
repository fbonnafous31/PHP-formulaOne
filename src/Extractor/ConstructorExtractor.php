<?php

namespace App\Extractor;

use App\Utils\Controller\QueryBuilder;

class ConstructorExtractor implements ExtractorInterface
{
    public function create_table($xml, string $tableName): string
    {
        $attributes = '';
        $query  = 'CREATE TABLE ' . $tableName . ' (';
        foreach ($xml->ConstructorTable->attributes() as $attribute => $value) {
            $query .= QueryBuilder::build_attributes_columnlist($attribute);
        }

        foreach ($xml->ConstructorTable->Constructor as $constructor) {
            foreach ($constructor->attributes() as $attribute => $value) {
                $query .= QueryBuilder::build_attributes_columnlist($attribute);
            }

            foreach ($constructor as $attribute => $value) {
                $query .= QueryBuilder::build_attributes_columnlist($attribute);
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
        foreach ($xml->ConstructorTable as $attr => $constructors) {
            foreach ($constructors as $constructor) {
                $attributes = '';
                $values = '';

                foreach ($constructors->attributes() as $attribute => $value) {
                    $attributes .= QueryBuilder::build_attributes_datalist($attribute);
                    $values     .= QueryBuilder::build_values_datalist($value);
                }

                foreach ($constructor->attributes() as $attribute => $value) {
                    $attributes .= QueryBuilder::build_attributes_datalist($attribute);
                    $values     .= QueryBuilder::build_values_datalist($value);
                }

                foreach ($constructor as $attribute => $value) {
                    $attributes .= QueryBuilder::build_attributes_datalist($attribute);
                    $values     .= QueryBuilder::build_values_datalist($value);
                }
                $queries[] = 'INSERT into ' . $tableName . '(' . substr($attributes, 0, -2) . ') VALUES (' . substr($values, 0, -2) . ');';
            }
        }
        return $queries;
    }
}
