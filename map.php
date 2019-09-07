<?php
require_once("lib.inc.php");
include_header();
?>
<div id="map" class="map"></div>
<script>
    var styleFunction = function(feature) {
        //now you can use any property of your feature to identify the different colors
        //I am using the ID property of your data just to demonstrate
        var colors=[
            [247, 249, 252],
[255, 250, 234],
[254, 248, 219],
[255, 244, 204],
[254, 239, 187],
[253, 235, 172],
[253, 230, 155],
[253, 226, 142],
[253, 223, 125],
[254, 218, 109],
[254, 215, 102],
[254, 210, 99],
[254, 204, 97],
[254, 199, 95],
[254, 194, 92],
[253, 188, 88],
[252, 183, 86],
[251, 178, 83],
[250, 172, 80],
[250, 168, 78],
[249, 163, 75],
[248, 157, 72],
[248, 152, 70],
[247, 147, 67],
[246, 143, 64],
[246, 138, 63],
[245, 131, 59],
[244, 125, 55],
[243, 115, 54],
[243, 110, 55],
[242, 98, 52],
[240, 88, 51],
[236, 79, 51],
[233, 70, 52],
[229, 59, 51],
[224, 46, 51],
[221, 36, 52],
[216, 31, 53],
[212, 31, 54],
[207, 32, 54],
[197, 32, 53],
[183, 31, 50],
[164, 29, 45],
[146, 24, 39],
[129, 20, 36],
[110, 12, 31],
[89, 11, 28],
[73, 19, 27],
[52, 17, 23],
[42, 16, 20],
[33, 11, 16]
        ];
        var retStyle = new ol.style.Style({
        image: new ol.style.Circle({
            fill: new ol.style.Fill({ color: colors[Math.round(feature.get("primary").dt)] }),
            stroke: new ol.style.Stroke({ color: [0,0,0,1] }),
            radius: 5
        })
    });
        return retStyle;

    };

    var weather = new ol.layer.Vector({
        title: 'BOM WOW weather',
        source: new ol.source.Vector({
            format: new ol.format.GeoJSON(),
            url: './api/weather-bomwow.php'
    //         attributions: [new ol.Attribution({
    //   html: "Where it came from"
    // })]
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
            serverType: 'geoserver'
    //         attributions: [new ol.Attribution({
    //   html: "Where it came from"
    // })]
        })
    });

    var map = new ol.Map({
        target: 'map',
        layers: [

            new ol.layer.Tile({
                title: 'Stamen Maps Terrain',
                //type: 'base',
                source: new ol.source.Stamen({
                    layer: 'terrain'
                })
            }),            weather,
            //sentinel,
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
    var popup = new Popup();
map.addOverlay(popup);

    map.on('click', function(evt) {
    var feature = map.forEachFeatureAtPixel(evt.pixel,
      function(feature) {
        return feature;
      },
      { layerFilter: function(candidate) { 
        return true//(candidate === forest)
      }
    });

    if (feature) {
        console.log(evt.coordinate)
    popup.show(evt.coordinate, '<div><h2>Coordinates</h2><p>' + JSON.stringify(feature.get("primary")) + '</p></div>');
    }
  });
</script>
</body>

</html>