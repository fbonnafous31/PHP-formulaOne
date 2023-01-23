<?php

print "Page d'accueil <br><br>";

$saison = 1950;
while ($saison < 2023) {
    $url = "http://ergast.com/api/f1/" . $saison . "/drivers";
    $saison++;
    var_dump($url);
    echo "<br>";
}
