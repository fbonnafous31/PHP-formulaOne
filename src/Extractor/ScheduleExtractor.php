<?php

namespace App\Extractor;

use App\Utils\Controller\QueryBuilder;

class ScheduleExtractor implements ExtractorInterface
{
    public function create_table($xml, string $tableName): string
    {
        $attributes = '';
        $query  = 'CREATE TABLE ' . $tableName . ' (';
        foreach ($xml->RaceTable->Race as $race) {
            foreach ($race->attributes() as $attribute => $value) {
                $query .= QueryBuilder::build_attributes_columnlist($attribute);
            }

            foreach ($race as $attribute => $value) {
                $query .= QueryBuilder::build_attributes_columnlist($attribute);
            }

            $query .= QueryBuilder::build_attributes_columnlist('Sprint');

            foreach ($race->Circuit as $circuit) {
                foreach ($circuit->attributes() as $attribute => $value) {
                    if (in_array($attribute, array('url')) == false) {
                        $query .= QueryBuilder::build_attributes_columnlist($attribute);
                    }
                }
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
        foreach ($xml->RaceTable->Race as $attr => $race) {
            $attributes = '';
            $values = '';
            foreach ($race->attributes() as $attribute => $value) {
                $attributes .= QueryBuilder::build_attributes_datalist($attribute);
                $values     .= QueryBuilder::build_values_datalist($value);
            }

            foreach ($race as $attribute => $value) {
                if (in_array($attribute, array('Circuit', 'FirstPractice', 'SecondPractice', 'ThirdPractice', 'Qualifying', 'Sprint')) == false) {
                    $attributes .= QueryBuilder::build_attributes_datalist($attribute);
                    $values     .= QueryBuilder::build_values_datalist($value);
                }
            }
            $queries[] = 'INSERT into ' . $tableName . '(' . substr($attributes, 0, -2) . ') VALUES (' . substr($values, 0, -2) . ');';
            $attributes = '';
            $values = '';
        }
        dump($queries);
        return $queries;
    }
}
