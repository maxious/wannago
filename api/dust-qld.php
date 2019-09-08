<?php
require_once("../lib.inc.php");
api_headers();
//https://environment.des.qld.gov.au/cgi-bin/air/xml.php?category=1&region=ALL


$data = array();
//$xml = DOMDocument::loadXML (file_get_contents ('qld.xml'));
$xml = DOMDocument::loadXML (file_get_contents ('https://environment.des.qld.gov.au/cgi-bin/air/xml.php?category=1&region=ALL'));

# Build GeoJSON feature collection array
$geojson = array(
    'type'      => 'FeatureCollection',
    'features'  => array()
 );
 # Loop through rows to build feature arrays
 $xpath = new DOMXpath($xml);
 $result = $xpath->query('//station');
 if (!is_null($result)) {
     foreach ($result as $node) {
$pm10 = 0;
$pm2_5 = 0;

        foreach ($node->getElementsByTagName("measurement") as $measurement) {
            if ($measurement->getAttribute('name') == 'Particle PM10') {
$pm10 = (float)$measurement->textContent;
            } elseif($measurement->getAttribute('name') == 'Particle PM2.5') {
                $pm2_5 = (float)$measurement->textContent;
            }
        }
    
if ($pm10+$pm2_5 > 0) {    
     $feature = array(
         'id' =>  $node->getAttribute('name'),
         'type' => 'Feature', 
         'geometry' => array(
             'type' => 'Point',
             # Pass Longitude and Latitude Columns here
             'coordinates' => array($node->getAttribute('longitude'), $node->getAttribute('latitude'))
         ),
         # Pass other attribute columns here
         'properties' => array(
             'name' => $node->getAttribute('name'),
             "pm2_5" => $pm2_5,
             "pm10"=> $pm10
             )
         );
     # Add feature arrays to feature collection array
     array_push($geojson['features'], $feature);
        }
    }
 };

echo json_encode($geojson, JSON_PRETTY_PRINT);