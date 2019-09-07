<?php
require_once("../lib.inc.php");
api_headers();
//curl 'http://bom-wow.metoffice.gov.uk/api/observations/geojson?timePointSlider=0&mapTime=07/09/2019%2003:59&showWowData=on&showOfficialData=on&showDcnnData=on&showRegisteredSites=off&showSchoolSitesData=off&groups=off' -H 'Cookie: ASP.NET_SessionId=bvaibnmsldfjvuawfy15aepf; 0_lat_=-25.274398000000005; 0_lng_=133.77513599999997; 0_zoom_=4; 0_mapLayer_=dt; 0_showWowData_=true; 0_showOfficialData_=true; 0_showDcnnData_=true; 0_showRegisteredSites_=false; 0_showSchoolSites_=false; 0_showGroupSites_=false; 0_showPublicGroupSites_=false; 0_showPrivateGroupSites_=false; 0_timePointPicker_=-1; 0_mapImpactsFilterTags_=; 0_mapPhotosFilterTags_=' -H 'Accept-Encoding: gzip, deflate' -H 'Accept-Language: en-US,en;q=0.9' -H 'X-Test-Header: test-value' -H 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_6),
$data = json_decode(file_get_contents ('bomwow.json'));

 function filterFeature($feature) {
 // australia extent: [113.338953078, -43.6345972634, 153.569469029, -10.6681857235],
  $minLat = -43.6345972634;
 $minLon = 113.338953078;
 $maxLon = 153.569469029;
 $maxLat = -10.6681857235;
 // exclude null sites
 if (empty($feature->properties->primary->dt)) {
     return false;
 }
 if ($feature->geometry->coordinates[0] > $maxLon || $feature->geometry->coordinates[0] < $minLon) {
     return false;
 }
 if ($feature->geometry->coordinates[1] > $maxLat || $feature->geometry->coordinates[1] < $minLat) {
    return false;
}
return true;
 }
$data->features = array_values(array_filter($data->features, "filterFeature"));

echo json_encode($data, JSON_PRETTY_PRINT);