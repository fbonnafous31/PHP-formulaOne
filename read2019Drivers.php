<?php
$file = __DIR__ . '/ressources/xml/2019_Drivers.xml';
if (file_exists($file)) {
    $content = simplexml_load_file($file);

    echo "Série "  . $content->attributes()->series . " - " .  $content->attributes()->url . "<br><br>";

    echo "Saison "  . $content->DriverTable->attributes()->season . "<br><br>";

    echo "<table>";
    foreach ($content->DriverTable->Driver as $driver) {
        echo "<tr>";
        echo "<td>" . $driver->attributes()->driverId . "</td>";
        echo "<td>" . $driver->attributes()->code . "</td>";
        echo "<td>" . $driver->attributes()->url . "</td>";
        echo "<td>" . $driver->PermanentNumber . "</td>";
        echo "<td>" . $driver->GivenName . "</td>";
        echo "<td>" . $driver->FamilyName . "</td>";
        echo "<td>" . date('d-m-Y', strtotime($Driver->DateOfBirth)) . "</td>";
        echo "<td>" . $driver->Nationality . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    exit('Failed to open file.');
}
