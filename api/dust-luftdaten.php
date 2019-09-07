<?php
require_once("../lib.inc.php");
api_headers();
// wget https://maps.luftdaten.info/data/v2/data.dust.min.json
// [{"id":4802280106,"sampling_rate":null,"timestamp":"2019-09-07 04:40:19","location":{"id":13208,"latitude":"51.044","longitude":"13.746","altitude":"",
    //"country":"DE","exact_location":0,"indoor":1},"sensor":{"id":36,"pin":"1","sensor_type":{"id":14,"name":"SDS011","manufacturer":"Nova Fitness"}},
    //"sensordatavalues":[{"id":10195215297,"value":"40.80","value_type":"humidity"}]}]
$j_data = json_decode(file_get_contents ('data.dust.min.json'));

 function filterFeature($feature) {
 // australia extent: [113.338953078, -43.6345972634, 153.569469029, -10.6681857235],
  $minLat = -43.6345972634;
 $minLon = 113.338953078;
 $maxLon = 153.569469029;
 $maxLat = -10.6681857235;
 if ($feature->location->longitude > $maxLon || $feature->location->longitude < $minLon) {
     return false;
 }
 if ($feature->location->latitude > $maxLat || $feature->location->latitude < $minLat) {
    return false;
}
return true;
 }
$j_data = array_filter($j_data, "filterFeature");

// {
//     "name": "AQ1",
//     "pm2_5": 18,
//     "pm10": 26,
//     "ts": "2019-09-06T14:00:41.238Z",
//     "lat": "-33.919471",
//     "long": "150.923788"
//   },
# Build GeoJSON feature collection array
$geojson = array(
    'type'      => 'FeatureCollection',
    'features'  => array()
 );
 # Loop through rows to build feature arrays
 foreach($j_data as $row) {
     
     
     $pm2_5= 0;
     $pm10 = 0;
     foreach($row->sensordatavalues as $value) {
         if ($value->value_type == 'P2') {
$pm2_5=$value->value;
         } elseif ($value->value_type == 'P1') {
                         $pm10=$value->value;
         }
     }
     $feature = array(
         'id' => $row->id,
         'type' => 'Feature', 
         'geometry' => array(
             'type' => 'Point',
             # Pass Longitude and Latitude Columns here
             'coordinates' => array($row->location->longitude, $row->location->latitude)
         ),
         # Pass other attribute columns here
         'properties' => array(
             'name' => $row->id,
             "pm2_5" => $pm2_5,
             "pm10"=> $pm10,
             "ts" => $row->timestamp
             )
         );
     # Add feature arrays to feature collection array
     array_push($geojson['features'], $feature);
 };

echo json_encode($geojson, JSON_PRETTY_PRINT);