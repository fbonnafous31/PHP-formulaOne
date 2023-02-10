<?php

namespace App\Controllers;

use App\Utils\Logger\Logger;
use App\Utils\Database\GetDatabase;
use App\Utils\Controller\QueryBuilder;
use App\Utils\Curl\CurlController;

class ResultController
{
    const MAX_ROUND = 22;

    protected $db;
    protected $logger;

    public function __construct()
    {
        $this->db     = GetDatabase::getDatabase();
        $this->logger = new Logger;
    }

    public function import($minSeason = 1950, $maxSeason = 2022)
    {
        $round = 1;
        $currentSeason = $maxSeason;
        while ($currentSeason >= $minSeason) {

            while ($round <= self::MAX_ROUND) {

                $url = "http://ergast.com/api/f1/" . $currentSeason . "/" . $round . "/results";

                $xml = CurlController::extract_xml($url);

                // dd($xml);

                if (($currentSeason == $maxSeason) and ($round == 1)) $this->create_table($xml, 'result');

                $this->insert_data($xml);

                $round++;
            }

            $currentSeason--;
            $round = 1;
        }
    }

    private function create_table($xml, $tableName)
    {
        $query = 'CREATE TABLE ' . $tableName . ' (';
        foreach ($xml->RaceTable->Race as $race) {
            foreach ($race->attributes() as $attribute => $value) {
                $query .= QueryBuilder::build_attributes_columnlist($attribute);
            }

            foreach ($race as $attribute => $value) {
                if (in_array($attribute, array('Circuit', 'ResultsList')) == false) {
                    if ($attribute == 'Time') $attribute = 'StartTime';
                    $query .= QueryBuilder::build_attributes_columnlist($attribute);
                }
            }
            break 1;
        }

        foreach ($race->Circuit as $circuit) {
            foreach ($circuit->attributes() as $attribute => $value) {
                if (in_array($attribute, array('url')) == false) {
                    $query .= QueryBuilder::build_attributes_columnlist($attribute);
                }
            }
        }

        foreach ($race->ResultsList->Result as $result) {
            foreach ($result->attributes() as $attribute => $value) {
                if (in_array($attribute, array('ResultsList')) == false) {
                    $query .= QueryBuilder::build_attributes_columnlist($attribute);
                }
            }

            foreach ($result as $attribute => $value) {
                if (in_array($attribute, array('ResultsList', 'Driver', 'Circuit', 'Constructor', 'FastestLap')) == false) {
                    if ($attribute == 'Time') $attribute = 'RaceTime';
                    $query .= QueryBuilder::build_attributes_columnlist($attribute);
                }
            }
            foreach ($result->Driver as $driver) {
                foreach ($driver->attributes() as $attribute => $value) {
                    if (in_array($attribute, array('url')) == false) {
                        $query .= QueryBuilder::build_attributes_columnlist($attribute);
                    }
                }
                break 1;
            }
            foreach ($result->Constructor as $constructor) {
                foreach ($constructor->attributes() as $attribute => $value) {
                    if (in_array($attribute, array('url')) == false) {
                        $query .= QueryBuilder::build_attributes_columnlist($attribute);
                    }
                }
                break 1;
            }
            foreach ($result->FastestLap as $fastestLap) {
                foreach ($fastestLap->attributes() as $attribute => $value) {
                    $query .= QueryBuilder::build_attributes_columnlist($attribute);
                }
                foreach ($fastestLap as $attribute => $value) {
                    if ($attribute == 'Time') $attribute = 'FastestTime';
                    $query .= QueryBuilder::build_attributes_columnlist($attribute);
                }
                break 1;
            }
            break 1;
        }

        $query = substr($query, 0, -2) .  ');';

        $this->db->execute_query("DROP TABLE IF EXISTS " . $tableName);
        $this->db->execute_query($query);

        $this->logger->log($query, false);
    }

    private function insert_data($xml)
    {
        foreach ($xml->RaceTable->Race as $attr => $race) {
            $attributes = '';
            $values = '';
            foreach ($race->attributes() as $attribute => $value) {
                $attributes .= QueryBuilder::build_attributes_datalist($attribute);
                $values     .= QueryBuilder::build_values_datalist($value);
            }

            foreach ($race as $attribute => $value) {
                if (in_array($attribute, array('Circuit', 'ResultsList')) == false) {
                    if ($attribute == 'Time') $attribute = 'StartTime';
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

            foreach ($race->ResultsList->Result as $result) {
                foreach ($result->attributes() as $attribute => $value) {
                    if (in_array($attribute, array('ResultsList')) == false) {
                        $attributes .= QueryBuilder::build_attributes_datalist($attribute);
                        $values     .= QueryBuilder::build_values_datalist($value);
                    }
                }

                foreach ($result as $attribute => $value) {
                    if (in_array($attribute, array('ResultsList', 'Driver', 'Circuit', 'Constructor', 'FastestLap')) == false) {
                        if ($attribute == 'Time') $attribute = 'RaceTime';
                        $attributes .= QueryBuilder::build_attributes_datalist($attribute);
                        $values     .= QueryBuilder::build_values_datalist($value);
                    }
                }

                foreach ($result->Driver as $driver) {
                    foreach ($driver->attributes() as $attribute => $value) {
                        if (in_array($attribute, array('url')) == false) {
                            $attributes .= QueryBuilder::build_attributes_datalist($attribute);
                            $values     .= QueryBuilder::build_values_datalist($value);
                        }
                    }
                }

                foreach ($result->Constructor as $constructor) {
                    foreach ($constructor->attributes() as $attribute => $value) {
                        if (in_array($attribute, array('url')) == false) {
                            $attributes .= QueryBuilder::build_attributes_datalist($attribute);
                            $values     .= QueryBuilder::build_values_datalist($value);
                        }
                    }
                }

                foreach ($result->FastestLap as $fastestLap) {
                    foreach ($fastestLap->attributes() as $attribute => $value) {
                        $attributes .= QueryBuilder::build_attributes_datalist($attribute);
                        $values     .= QueryBuilder::build_values_datalist($value);
                    }
                    foreach ($fastestLap as $attribute => $value) {
                        if ($attribute == 'Time') $attribute = 'FastestTime';
                        $attributes .= QueryBuilder::build_attributes_datalist($attribute);
                        $values     .= QueryBuilder::build_values_datalist($value);
                    }
                }
                $query = 'INSERT into result (' . substr($head_attributes . ' ' . $attributes, 0, -2) . ') VALUES (' . substr($head_values . ' ' . $values, 0, -2) . ');';
                $attributes = '';
                $values = '';

                dump($query);

                $this->logger->log($query, false);
                $this->db->execute_query($query);
            }
        }
    }
}
