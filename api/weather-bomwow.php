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
 if ($feature->geometry->coordinates[0] > $maxLon || $feature->geometry->coordinates[0] < $minLon) {
     return false;
 }
 if ($feature->geometry->coordinates[1] > $maxLat || $feature->geometry->coordinates[1] < $minLat) {
    return false;
}
return true;
 }
$data->features = array_values(array_filter($data->features, "filterFeature"));
$colors = array(
"rgb(247, 249, 252)",
"rgb(255, 250, 234)",
"rgb(254, 248, 219)",
"rgb(255, 244, 204)",
"rgb(254, 239, 187)",
"rgb(253, 235, 172)",
"rgb(253, 230, 155)",
"rgb(253, 226, 142)",
"rgb(253, 223, 125)",
"rgb(254, 218, 109)",
"rgb(254, 215, 102)",
"rgb(254, 210, 99)",
"rgb(254, 204, 97)",
"rgb(254, 199, 95)",
"rgb(254, 194, 92)",
"rgb(253, 188, 88)",
"rgb(252, 183, 86)",
"rgb(251, 178, 83)",
"rgb(250, 172, 80)",
"rgb(250, 168, 78)",
"rgb(249, 163, 75)",
"rgb(248, 157, 72)",
"rgb(248, 152, 70)",
"rgb(247, 147, 67)",
"rgb(246, 143, 64)",
"rgb(246, 138, 63)",
"rgb(245, 131, 59)",
"rgb(244, 125, 55)",
"rgb(243, 115, 54)",
"rgb(243, 110, 55)",
"rgb(242, 98, 52)",
"rgb(240, 88, 51)",
"rgb(236, 79, 51)",
"rgb(233, 70, 52)",
"rgb(229, 59, 51)",
"rgb(224, 46, 51)",
"rgb(221, 36, 52)",
"rgb(216, 31, 53)",
"rgb(212, 31, 54)",
"rgb(207, 32, 54)",
"rgb(197, 32, 53)",
"rgb(183, 31, 50)",
"rgb(164, 29, 45)",
"rgb(146, 24, 39)",
"rgb(129, 20, 36)",
"rgb(110, 12, 31)",
"rgb(89, 11, 28)",
"rgb(73, 19, 27)",
"rgb(52, 17, 23)",
"rgb(42, 16, 20)",
"rgb(33, 11, 16)");
echo json_encode($data, JSON_PRETTY_PRINT);