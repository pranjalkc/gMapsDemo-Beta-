<?php

header("Content-Type:text");

$fh = fopen('parking.csv.txt', 'r');

while (!feof($fh)) {
    $parkingInfoArray = fgetcsv($fh);
    $address = $parkingInfoArray[3];
    $city = "Hamilton";
    $longitude = $parkingInfoArray[5];
    $latitude = $parkingInfoArray[6];
    //echo $name;
    
    $parkingArray[] = array('address' => trim($address),
                'city' => trim($city),
                'latitude' => $latitude,
                'longtitude' => $longitude
    );
}

echo "<pre>";
print_r($parkingArray);
echo "</pre>";

$myPropertyMapString = 'var listParking = ' . json_encode($parkingArray) . ';';

file_put_contents("convParkingData.js", $myPropertyMapString);
fclose($fh);
