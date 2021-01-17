<?php

header("Content-Type:text");

$options = array(
    'ssl' => array(
        'cafile' => 'cacert.pem',
        'verify_peer' => true,
        'verify_peer_name' => true,
    ),
);
$context = stream_context_create($options);

$fh = fopen('venues.csv.txt', 'r');



while (!feof($fh)) {

    $venueInfoArray = fgetcsv($fh);
    $name = $venueInfoArray[0];
    $address = $venueInfoArray[1];
    $city = $venueInfoArray[2];
    $phone = $venueInfoArray[3];
    $website = $venueInfoArray[4];
    $addrToCheck = urlencode($address . "," . $city);

    // don't forget to add your developer key below. Failure to do so
    // can result in script errors when the file_get_contents fails.

    $xmlFile = file_get_contents('https://maps.googleapis.com/maps/api/geocode/xml?address=' . $addrToCheck . '&sensor=false&key=AIzaSyASuOY4JMHLEHl1_QFk_wCjT87yAuMXSas', false, $context);
    $data = new SimpleXMLElement($xmlFile);

    if ($data->status == "OK") {
        $long = $data->result->geometry->location->lng->__toString();
        $lat = (string) $data->result->geometry->location->lat;

        $venueArray[] = array('name' => $name,
            'address' => $address,
            'city' => $city,
            'phone' => $phone,
            'website' => $website,
            'latitude' => $lat,
            'longtitude' => $long
        );
    }
}

echo "<pre>";
print_r($venueArray);
echo "</pre>";

$myPropertyMapString = 'var listVenues = ' . json_encode($venueArray) . ';';

file_put_contents("convVenueData.js", $myPropertyMapString);
fclose($fh);
