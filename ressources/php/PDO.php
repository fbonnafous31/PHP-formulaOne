<?php

use App\Utils\Database\GetDatabase;

echo "Accès base de données";
$db = GetDatabase::getDatabase();
$results = $db->query('SELECT * from driver');

echo "<br><br> Liste des pilotes depuis 1950";
foreach ($results as $result) :
    echo '<br>' . $result->GivenName . ' ' . $result->FamilyName;
endforeach;


echo "<br><br> Liste des pilotes depuis français évoluant en 2022";
$results = $db->query_attr('SELECT * FROM driver WHERE season = ? AND nationality = ?', [2022, 'French'], DriverController::class);
foreach ($results as $result) :
    echo '<br>' . $result->GivenName . ' ' . $result->FamilyName;
endforeach;

$query = $db->execute("CREATE TABLE test (Name VARCHAR(255))");
