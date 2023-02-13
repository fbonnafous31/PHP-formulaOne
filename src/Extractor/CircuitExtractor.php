<?php

namespace App\Extractor;

use App\Utils\Controller\QueryBuilder;

class CircuitExtractor implements ExtractorInterface
{
    public function create_table($xml, string $tableName): string
    {
        $attributes = '';
        $query  = 'CREATE TABLE ' . $tableName . ' (';
        foreach ($xml->CircuitTable->attributes() as $attribute => $value) {
            $query .= QueryBuilder::build_attributes_columnlist($attribute);
        }

        foreach ($xml->CircuitTable->Circuit as $circuit) {
            foreach ($circuit->attributes() as $attribute => $value) {
                $query .= QueryBuilder::build_attributes_columnlist($attribute);
            }

            foreach ($circuit as $attribute => $value) {
                if ($attribute != 'Location') {
                    $query .= QueryBuilder::build_attributes_columnlist($attribute);
                }
            }
            break;
        }

        foreach ($xml->CircuitTable->Circuit->Location as $location) {
            foreach ($location->attributes() as $attribute => $value) {
                $query .= QueryBuilder::build_attributes_columnlist($attribute);
            }

            foreach ($location as $attribute => $value) {
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
        foreach ($xml->CircuitTable as $attr => $circuits) {
            foreach ($circuits as $circuit) {
                $attributes = '';
                $values = '';

                foreach ($circuits->attributes() as $attribute => $value) {
                    $attributes .= QueryBuilder::build_attributes_datalist($attribute);
                    $values     .= QueryBuilder::build_values_datalist($value);
                }

                foreach ($circuit->attributes() as $attribute => $value) {
                    $attributes .= QueryBuilder::build_attributes_datalist($attribute);
                    $values     .= QueryBuilder::build_values_datalist($value);
                }

                foreach ($circuit as $attribute => $value) {
                    if ($attribute != 'Location') {
                        $attributes .= QueryBuilder::build_attributes_datalist($attribute);
                        $values     .= QueryBuilder::build_values_datalist($value);
                    }
                }

                foreach ($circuit->Location as $location) {
                    foreach ($location->attributes() as $attribute => $value) {
                        $attributes .= QueryBuilder::build_attributes_datalist($attribute);
                        $values     .= QueryBuilder::build_values_datalist($value);
                    }

                    foreach ($location as $attribute => $value) {
                        $attributes .= QueryBuilder::build_attributes_datalist($attribute);
                        $values     .= QueryBuilder::build_values_datalist($value);
                    }
                    break;
                }
                $queries[] = 'INSERT into ' . $tableName . '(' . substr($attributes, 0, -2) . ') VALUES (' . substr($values, 0, -2) . ');';
            }
        }
        return $queries;
    }
}
