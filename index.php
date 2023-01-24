<?php

use App\Utils\Database\GetDatabase;

require_once('libraries/autoload.php');

print "Page d'accueil <br><br>";

echo "Accès base de données";
$db = GetDatabase::getDatabase();
$results = $db->query('SELECT * from driver');

echo "<br><br> Liste des pilotes depuis 1950";
foreach ($results as $result) :
    echo '<br>' . $result->GivenName . ' ' . $result->FamilyName;
endforeach;
