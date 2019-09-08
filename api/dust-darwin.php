<?php
require_once("../lib.inc.php");
api_headers();

// // wget https://opendata.arcgis.com/datasets/238004152d184aa89df8c8f6f4a4ff1c_0.geojson -O darwin-devices.json
//  wget "https://services6.arcgis.com/tVfesLETUHNU9Vna/ArcGIS/rest/services/City_of_Darwin_PM10_IoT/FeatureServer/0/query?where=time+%3E%3D+TIMESTAMP+%272019-09-05+12%3A00%3A00%27&objectIds=&time=&resultType=none&outFields=*&returnIdsOnly=false&returnUniqueIdsOnly=false&returnCountOnly=false&returnDistinctValues=false&cacheHint=false&orderByFields=&groupByFieldsForStatistics=&outStatistics=&having=&resultOffset=&resultRecordCount=&sqlFormat=none&f=pjson&token=" -O darwin-pm10.json
//  wget "https://services6.arcgis.com/tVfesLETUHNU9Vna/ArcGIS/rest/services/City_of_Darwin_PM2_5_IoT/FeatureServer/0/query?where=time+%3E%3D+TIMESTAMP+%272019-09-05+12%3A00%3A00%27&objectIds=&time=&resultType=none&outFields=*&returnIdsOnly=false&returnUniqueIdsOnly=false&returnCountOnly=false&returnDistinctValues=false&cacheHint=false&orderByFields=&groupByFieldsForStatistics=&outStatistics=&having=&resultOffset=&resultRecordCount=&sqlFormat=none&f=pjson&token=" -O darwin-pm25.json
// https://services6.arcgis.com/tVfesLETUHNU9Vna/ArcGIS/rest/services
$data = json_decode(file_get_contents ('darwin-devices.json'));
$j_data_pm10 = json_decode(file_get_contents ('darwin-pm10.json'));
//$j_data_pm10 = json_decode(file_get_contents ('https://services6.arcgis.com/tVfesLETUHNU9Vna/ArcGIS/rest/services/City_of_Darwin_PM10_IoT/FeatureServer/0/query?where=time+%3E%3D+TIMESTAMP+%27'.date("Y-m-d").'+12%3A00%3A00%27&objectIds=&time=&resultType=none&outFields=*&returnIdsOnly=false&returnUniqueIdsOnly=false&returnCountOnly=false&returnDistinctValues=false&cacheHint=false&orderByFields=&groupByFieldsForStatistics=&outStatistics=&having=&resultOffset=&resultRecordCount=&sqlFormat=none&f=pjson&token='));
$data_pm10 = Array();
$j_data_pm2_5 = json_decode(file_get_contents ('darwin-pm25.json'));
//$j_data_pm2_5 = json_decode(file_get_contents ('https://services6.arcgis.com/tVfesLETUHNU9Vna/ArcGIS/rest/services/City_of_Darwin_PM2_5_IoT/FeatureServer/0/query?where=time+%3E%3D+TIMESTAMP+%27'.date("Y-m-d").'+12%3A00%3A00%27&objectIds=&time=&resultType=none&outFields=*&returnIdsOnly=false&returnUniqueIdsOnly=false&returnCountOnly=false&returnDistinctValues=false&cacheHint=false&orderByFields=&groupByFieldsForStatistics=&outStatistics=&having=&resultOffset=&resultRecordCount=&sqlFormat=none&f=pjson&token='));
$data_pm2_5 = Array();


foreach($j_data_pm10->features as $feature) {
    $data_pm10[trim(str_replace("MQTT Device ","",$feature->attributes->device_name))][$feature->attributes->time] = $feature->attributes->value;
}

foreach($j_data_pm2_5->features as $feature) {
    $data_pm2_5[trim(str_replace("MQTT Device ","",$feature->attributes->device_name))][$feature->attributes->time] = $feature->attributes->value;
}

foreach($data->features as &$feature) {
    $feature->properties->DeviceId = trim($feature->properties->DeviceId);
 //echo $feature->properties->DeviceId." ";
 if (array_key_exists($feature->properties->DeviceId,$data_pm10)) {
 ksort($data_pm10[$feature->properties->DeviceId]);
 //echo array_values($data_pm10[$feature->properties->DeviceId])[0];
 $feature->properties->pm10 = array_values($data_pm10[$feature->properties->DeviceId])[0];
 $feature->properties->pm2_5 = array_values($data_pm2_5[$feature->properties->DeviceId])[0];
 $feature->properties->name =  $feature->properties->MXLOCATION;
 } else {
     unset($feature);
 }
}

//print_r(array_keys($data_pm10));
//print_r($data_pm10);

echo json_encode($data, JSON_PRETTY_PRINT);