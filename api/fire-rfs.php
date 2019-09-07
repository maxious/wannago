<?php
//http://www.rfs.nsw.gov.au/feeds/majorIncidents.json
require_once("../lib.inc.php");
api_headers();
$data = json_decode(file_get_contents ('majorIncidents.json'));
echo json_encode($data, JSON_PRETTY_PRINT);