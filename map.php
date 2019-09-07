<?php
require_once("lib.inc.php");
include_header();
?>
<div id="map" class="map"></div>
<script>
    var styleFunction = function(feature) {
        console.log(feature);
        //now you can use any property of your feature to identify the different colors
        //I am using the ID property of your data just to demonstrate
        var color;
        if (feature.get("ID") == 1) {
            color = "red";
        } else if (feature.get("ID") == 2) {
            color = "green";
        } else {
            color = "black";
        }

        var retStyle = new ol.style.Style({
            stroke: new ol.style.Stroke({
                color: color,
                width: 5
            })
        });
        return retStyle;

    };

    var weather = new ol.layer.Vector({
        title: 'BOM WOW weather',
        source: new ol.source.Vector({
            format: new ol.format.GeoJSON(),
            url: './api/weather-bomwow.php'
        }),
        style: styleFunction
    });
    var sentinel = new ol.layer.Tile({
        title: 'Sentinel Bushfires',
        source: new ol.source.TileWMS({
            url: 'http://sentinel.ga.gov.au/geoserver/public/wms',
            params: {
                'LAYERS': 'public:hotspot_current_4326',
                'TILED': true
            },
            serverType: 'geoserver',
        })
    });

    var map = new ol.Map({
        target: 'map',
        layers: [

            new ol.layer.Tile({
                source: new ol.source.Stamen({
                    layer: 'terrain'
                })
            }),            weather,
            sentinel,
        ],
        view: new ol.View({
            //map.getView().getZoom(); 10 
            projection: 'EPSG:4326',
            //map.getView().calculateExtent();
            center: [151.0942840576172, -33.925824165344224], // 
            // australia extent: [113.338953078, -43.6345972634, 153.569469029, -10.6681857235], 
            //extent: [150.7308769226076, -34.22031998634341, 151.50953292846697, -33.633238077163725], //sydney
            zoom: 12,
            //minZoom: 10,
            maxZoom: 16
        })
    });
    var layerSwitcher = new ol.control.LayerSwitcher({
        tipLabel: 'Légende', // Optional label for button
        groupSelectStyle: 'children' // Can be 'children' [default], 'group' or 'none'
    });
    map.addControl(layerSwitcher);
</script>
</body>

</html>