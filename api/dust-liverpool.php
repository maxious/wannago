<?php
require_once("../lib.inc.php");
api_headers();
//http://pavo.its.uow.edu.au:6969/api/sensor-data/air/by-period/2019-09-07,2019-09-07

// {
//     "name": "AQ1",
//     "pm2_5": 18,
//     "pm10": 26,
//     "ts": "2019-09-06T14:00:41.238Z",
//     "lat": "-33.919471",
//     "long": "150.923788"
//   },

$j_data = json_decode(file_get_contents ('liverpool.json'));
$data = array();
foreach($j_data as $j) {
    $data[$j->name][$j->ts] = $j;
}
# Build GeoJSON feature collection array
$geojson = array(
    'type'      => 'FeatureCollection',
    'features'  => array()
 );
 # Loop through rows to build feature arrays
 foreach($data as $sitename => $rows) {
     ksort($rows);
     $row = array_reverse(array_values($rows))[0];
     $feature = array(
         'id' => $sitename,
         'type' => 'Feature', 
         'geometry' => array(
             'type' => 'Point',
             # Pass Longitude and Latitude Columns here
             'coordinates' => array($row->long, $row->lat)
         ),
         # Pass other attribute columns here
         'properties' => array(
             'name' => $row->name,
             "pm2_5" => $row->pm2_5,
             "pm10"=> $row->pm10,
             "ts" => $row->ts
             )
         );
     # Add feature arrays to feature collection array
     array_push($geojson['features'], $feature);
 };

echo json_encode($geojson, JSON_PRETTY_PRINT);