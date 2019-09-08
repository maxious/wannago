<?php
require_once("../lib.inc.php");
api_headers();
$lat = round($_REQUEST['lat'],2);
$lon = round($_REQUEST['lon'],2);
$url = "https://www.longpaddock.qld.gov.au/cgi-bin/silo/DataDrillDataset.php?lat=".$lat."&lon=".$lon."&start=20190905&finish=20190905&format=json&username=maxious@lambdacomplex.org&password=silo&comment=XN";
echo file_get_contents($url);
/*echo '{
    "location": { "latitude":  -12.4600, "longitude":  130.8400, "elevation":  16.0, "reference": "XN" },
    "extracted":20190908,
    "data": [
    {"date": "2019-09-05", "variables": [
    {"source": 25, "value":  29.5, "variable_code": "max_temp"},
    {"source": 25, "value":  19.5, "variable_code": "min_temp"}] }
    ]
    }';*/