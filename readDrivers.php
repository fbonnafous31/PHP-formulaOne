<?php
$file = __DIR__ . '/ressources/xml/2022_Drivers.xml';
if (file_exists($file)) {
    $content = simplexml_load_file($file);

    echo "Saison "  . $content->attributes()->season . "<br><br>";

    echo "<table>";
    foreach ($content->Driver as $driver) {
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