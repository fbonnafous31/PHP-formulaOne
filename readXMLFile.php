<?php
$fichier = 'drivers.xml';
if (file_exists($fichier)) {
    // $xml = simplexml_load_file($fichier);
    // print_r($xml);

    $contenu = simplexml_load_file($fichier);

    // echo '<pre>';
    // print_r($contenu);
    // echo '</pre>';

    echo "<table>";
    echo "<thead>";
    echo "<tr>";
    echo "<td>Numéro</td>";
    echo "<td>Prénom</td>";
    echo "<td>Nom</td>";
    echo "<td>Date de naissance</td>";
    echo "<td>Nationalité</td>";
    echo "</tr>";
    echo "</thead>";
    foreach ($contenu as $driver) {
        echo "<thead>";
        echo "<tr>";
        echo "<td>" . $driver->PermanentNumber . "</td>";
        echo "<td>" . $driver->GivenName . "</td>";
        echo "<td>" . $driver->FamilyName . "</td>";
        echo "<td>" . date('d-m-Y', strtotime($Driver->DateOfBirth)) . "</td>";
        echo "<td>" . $driver->Nationality . "</td>";
        echo "</tr>";
    }
    echo "<table>";
} else {
    exit('Failed to open test.xml.');
}
