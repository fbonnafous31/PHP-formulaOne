<?php

namespace App\Extractor;

use App\Extractor\ExtractorInterface;
use App\Utils\Controller\QueryBuilder;

class PitStopExtractor implements ExtractorInterface
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
                if (in_array($attribute, array('Circuit', 'PitStopsList')) == false) {
                    if ($attribute == 'Time') $attribute = 'raceTime';
                    $query .= QueryBuilder::build_attributes_columnlist($attribute);
                }
            }

            foreach ($race->Circuit as $circuit) {
                foreach ($circuit->attributes() as $attribute => $value) {
                    if (in_array($attribute, array('url')) == false) {
                        $query .= QueryBuilder::build_attributes_columnlist($attribute);
                    }
                }
            }

            foreach ($race->PitStopsList->PitStop as $pitStop) {
                foreach ($pitStop->attributes() as $attribute => $value) {
                    $query .= QueryBuilder::build_attributes_columnlist($attribute);
                }
                break;
            }
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
        foreach ($xml->RaceTable->Race as $race) {
            $attributes = '';
            $values = '';
            foreach ($race->attributes() as $attribute => $value) {
                $attributes .= QueryBuilder::build_attributes_datalist($attribute);
                $values     .= QueryBuilder::build_values_datalist($value);
            }
            foreach ($race as $attribute => $value) {
                if (in_array($attribute, array('Circuit', 'PitStopsList')) == false) {
                    if ($attribute == 'Time') $attribute = 'raceTime';
                    $attributes .= QueryBuilder::build_attributes_datalist($attribute);
                    $values     .= QueryBuilder::build_values_datalist($value);
                }
            }
            foreach ($race->Circuit as $circuit) {
                foreach ($circuit->attributes() as $attribute => $value) {
                    if (in_array($attribute, array('url')) == false) {
                        $attributes .= QueryBuilder::build_attributes_datalist($attribute);
                        $values     .= QueryBuilder::build_values_datalist($value);
                    }
                }
            }
            $head_attributes = $attributes;
            $head_values = $values;

            $attributes = '';
            $values = '';

            foreach ($race->PitStopsList->PitStop as $pitStop) {
                foreach ($pitStop->attributes() as $attribute => $value) {
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
