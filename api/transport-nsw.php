<?php
//curl -X GET --header 'Accept: application/x-google-protobuf' --header 'Authorization: apikey l' 'https://api.transport.nsw.gov.au/v1/gtfs/vehiclepos/buses'
//curl -X GET --header 'Accept: application/x-google-protobuf' --header 'Authorization: apikey l' 'https://api.transport.nsw.gov.au/v1/gtfs/vehiclepos/nswtrains'
/*
wget https://opendata.transport.nsw.gov.au/sites/default/files/tfnsw-gtfs-realtime.proto_.txt
npm i protobufjs -g
protoc --decode transit_realtime.FeedMessage tfnsw-gtfs-realtime.proto_.txt < buses.pbuf | egrep "latitude|longitude|air_conditioned" > buses.txt
protoc --decode transit_realtime.FeedMessage tfnsw-gtfs-realtime.proto_.txt < buses.pbuf | egrep "latitude|longitude|trip_id" > trains.txt
*/
/* The following are the possible values that could be found in the <set_type> field of the trip_id
for passenger trains.
Value Train Set Type
A Waratah
C C Set
H Oscar
J Hunter
K K Set
M Millennium
N Endeavour
P Xplorer
S S Set
T Tangara
V Intercity
X XPT
Z Indian Pacific*/
