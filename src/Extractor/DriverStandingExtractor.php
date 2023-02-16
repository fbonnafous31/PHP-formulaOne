<?php

namespace App\Extractor;

use App\Utils\Controller\QueryBuilder;

class DriverStandingExtractor implements ExtractorInterface
{
    public function create_table($xml, string $tableName): string
    {
        $attributes = '';
        $query  = 'CREATE TABLE ' . $tableName . ' (';

        foreach ($xml->StandingsTable->StandingsList as $standingList) {
            foreach ($standingList->attributes() as $attribute => $value) {
                $attributes .= QueryBuilder::build_attributes_columnlist($attribute);
            }

            foreach ($standingList->DriverStanding as $driverStanding) {
                foreach ($driverStanding->attributes() as $attribute => $value) {
                    $attributes .= QueryBuilder::build_attributes_columnlist($attribute);
                }

                foreach ($driverStanding->Driver->attributes() as $attribute => $value) {
                    if (in_array($attribute, array('url', 'code')) == false) {
                        $attributes .= QueryBuilder::build_attributes_columnlist($attribute);
                    }
                }

                foreach ($driverStanding->Constructor->attributes() as $attribute => $value) {
                    if (in_array($attribute, array('url')) == false) {
                        $attributes .= QueryBuilder::build_attributes_columnlist($attribute);
                    }
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
        foreach ($xml->StandingsTable->StandingsList as $standingList) {
            $attributes = '';
            $values = '';

            foreach ($standingList->DriverStanding as $driverStanding) {
                foreach ($standingList->attributes() as $attribute => $value) {
                    $attributes .= QueryBuilder::build_attributes_datalist($attribute);
                    $values     .= QueryBuilder::build_values_datalist($value);
                }

                foreach ($driverStanding->attributes() as $attribute => $value) {
                    $attributes .= QueryBuilder::build_attributes_datalist($attribute);
                    $values     .= QueryBuilder::build_values_datalist($value);
                }

                foreach ($driverStanding->Driver->attributes() as $attribute => $value) {
                    if (in_array($attribute, array('url', 'code')) == false) {
                        $attributes .= QueryBuilder::build_attributes_datalist($attribute);
                        $values     .= QueryBuilder::build_values_datalist($value);
                    }
                }

                foreach ($driverStanding->Constructor->attributes() as $attribute => $value) {
                    if (in_array($attribute, array('url')) == false) {
                        $attributes .= QueryBuilder::build_attributes_datalist($attribute);
                        $values     .= QueryBuilder::build_values_datalist($value);
                    }
                }

                $queries[] = 'INSERT into ' . $tableName . '(' . substr($attributes, 0, -2) . ') VALUES (' . substr($values, 0, -2) . ');';
                $attributes = '';
                $values = '';
            }
        }
        return $queries;
    }
}
