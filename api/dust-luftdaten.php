<?php
require_once("../lib.inc.php");
api_headers();
// wget https://maps.luftdaten.info/data/v2/data.dust.min.json
// [{"id":4802280106,"sampling_rate":null,"timestamp":"2019-09-07 04:40:19","location":{"id":13208,"latitude":"51.044","longitude":"13.746","altitude":"","country":"DE","exact_location":0,"indoor":1},"sensor":{"id":36,"pin":"1","sensor_type":{"id":14,"name":"SDS011","manufacturer":"Nova Fitness"}},"sensordatavalues":[{"id":10195215297,"value":"40.80","value_type":"humidity"}]}]
$data = json_decode(file_get_contents ('data.dust.min.json'));

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
$data = array_values(array_filter($data, "filterFeature"));

echo json_encode($data, JSON_PRETTY_PRINT);