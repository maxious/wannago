<?php
require_once("lib.inc.php");
include_header();
?>
<style>
.au-grid .container-fluid {
    max-width: 100% !important;
}
main>.au-body {
     padding: 0 !important;
}
</style>
<div id="map" class="map"></div>
<script>
    var weatherStyleFunction = function(feature) {
        var colors=[
            [247, 249, 252],[255, 250, 234],[254, 248, 219],[255, 244, 204],[254, 239, 187],[253, 235, 172],[253, 230, 155],
[253, 226, 142],[253, 223, 125],[254, 218, 109],[254, 215, 102],[254, 210, 99],[254, 204, 97],[254, 199, 95],[254, 194, 92],[253, 188, 88],[252, 183, 86],
[251, 178, 83],[250, 172, 80],[250, 168, 78],[249, 163, 75],[248, 157, 72],[248, 152, 70],[247, 147, 67],[246, 143, 64],[246, 138, 63],
[245, 131, 59],[244, 125, 55],[243, 115, 54],[243, 110, 55],[242, 98, 52],
[240, 88, 51],[236, 79, 51],[233, 70, 52],[229, 59, 51],[224, 46, 51],[221, 36, 52],[216, 31, 53],[212, 31, 54],[207, 32, 54],[197, 32, 53],
[183, 31, 50],[164, 29, 45],[146, 24, 39],[129, 20, 36],[110, 12, 31],[89, 11, 28],[73, 19, 27],[52, 17, 23],[42, 16, 20],[33, 11, 16]
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
        type: "weather",
        visible: false,
        source: new ol.source.Vector({
            format: new ol.format.GeoJSON(),
            url: './api/weather-bomwow.php'
    //         attributions: [new ol.Attribution({
    //   html: "Where it came from"
    // })]
        }),
        style: weatherStyleFunction
    });

    var dustStyleFunction = function(feature) {

if (feature.get("pm2_5") < 35 && feature.get("pm10") < 150) {
        return new ol.style.Style({
            image: new ol.style.Icon( ({
          anchor: [0.5, 46],
          anchorXUnits: 'fraction',
          anchorYUnits: 'pixels',
          src: './img/wind.png'
        }))})
} else if(feature.get("pm2_5") > -1000) {
    return new ol.style.Style({
            image: new ol.style.Icon( ({
          anchor: [0.5, 46],
          anchorXUnits: 'fraction',
          anchorYUnits: 'pixels',
          src: './img/sick.png'
        }))})
}
    };

    var dust_liverpool = new ol.layer.Vector({
        title: 'Liverpool dust',
        type:'dust',
        visible: <?=($_REQUEST['region'] == 'sydney'? 'true':'false')?>,
        source: new ol.source.Vector({
            format: new ol.format.GeoJSON(),
            url: './api/dust-liverpool.php'
    //         attributions: [new ol.Attribution({
    //   html: "Where it came from"
    // })]
        }),
        style: dustStyleFunction
    });

    
    var dust_luftdaten = new ol.layer.Vector({
        title: 'Luftdaten dust',
        type:'dust',
        source: new ol.source.Vector({
            format: new ol.format.GeoJSON(),
            url: './api/dust-luftdaten.php'
    //         attributions: [new ol.Attribution({
    //   html: "Where it came from"
    // })]
        }),
        style: dustStyleFunction
    });
    var dust_qld = new ol.layer.Vector({
        title: 'Queensland Government dust',
        type:'dust',
        visible: <?=($_REQUEST['region'] == 'qld'? 'true':'false')?>,
        source: new ol.source.Vector({
            format: new ol.format.GeoJSON(),
            url: './api/dust-qld.php'
    //         attributions: [new ol.Attribution({
    //   html: "Where it came from"
    // })]
        }),
        style: dustStyleFunction
    });
    var dust_darwin = new ol.layer.Vector({
        title: 'Darwin dust',
        type:'dust',
        visible: <?=($_REQUEST['region'] == 'darwin'? 'true':'false')?>,
        source: new ol.source.Vector({
            format: new ol.format.GeoJSON(),
            url: './api/dust-darwin.php'
    //         attributions: [new ol.Attribution({
    //   html: "Where it came from"
    // })]
        }),
        style: dustStyleFunction
    });
    var rfsfire = new ol.layer.Vector({
        title: 'RFS Current Incidents',
        type: 'fire',
        visible: <?=($_REQUEST['region'] == 'sydney'? 'true':'false')?>,
        source: new ol.source.Vector({
            format: new ol.format.GeoJSON(),
            url: './api/fire-rfs.php'
    //         attributions: [new ol.Attribution({
    //   html: "Where it came from"
    // })]
        }),
        style: new ol.style.Style({
            image: new ol.style.Icon( ({
          anchor: [0.5, 46],
          anchorXUnits: 'fraction',
          anchorYUnits: 'pixels',
          src: './img/wildfire.png'
        }))})
    });
    
    
    var darwinpark = new ol.layer.Vector({
        title: 'Darwin Parks',
        type: 'parks',
        visible: <?=($_REQUEST['region'] == 'darwin'? 'true':'false')?>,
        source: new ol.source.Vector({
            format: new ol.format.GeoJSON(),
            url: './api/parks-darwin.php'
    //         attributions: [new ol.Attribution({
    //   html: "Where it came from"
    // })]
        }),
        style: new ol.style.Style({

          stroke: new ol.style.Stroke({
            color: 'green',
            width: 1
          }),
          fill: new ol.style.Fill({
            color: 'rgba(34,139,34, 0.7)'
          })
        })
    });

//     Urban Heat Island (0)
// Heat Vulnerability Index (1)
// Urban Veg Cover - Percent All Veg (2)
// Urban Veg Cover - Percent Tree and Shrub (3)
// Urban Veg Cover - Percent Tree Canopy (4)
    var heatisland = new ol.layer.Tile({
        title: 'Urban Heat Island',
        visible: false,
        source: new ol.source.TileWMS({
            url: 'https://mapprod2.environment.nsw.gov.au/arcgis/services/UHGC/UHGC/MapServer/WMSServer',
            params: {
                'LAYERS': '0',
                'TILED': true
            }
    //         attributions: [new ol.Attribution({
    //   html: "Where it came from"
    // })]
        })
    });
    var vegcover = new ol.layer.Tile({
        title: 'Urban Veg Cover - Percent Tree Canopy',
        visible: false,
        source: new ol.source.TileWMS({
            url: 'https://mapprod2.environment.nsw.gov.au/arcgis/services/UHGC/UHGC/MapServer/WMSServer',
            params: {
                'LAYERS': '4',
                'TILED': true
            }
    //         attributions: [new ol.Attribution({
    //   html: "Where it came from"
    // })]
        })
    });
   
    var sentinel = new ol.layer.Tile({
        title: 'Sentinel Bushfires',
        visible: false,
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
                type: 'base',
                source: new ol.source.Stamen({
                    layer: 'terrain'
                })
            }), darwinpark,
                       weather, 
                       dust_liverpool, dust_luftdaten, dust_qld, dust_darwin,
            sentinel,
            heatisland,
            vegcover,
            rfsfire, 
        ],
        view: new ol.View({
            
            projection: 'EPSG:4326',
            //map.getView().getZoom(); 10 
            //map.getView().calculateExtent();
            <?php
            if ($_REQUEST['region'] == 'sydney') {
                echo '
            center: [151.0942840576172, -33.925824165344224], 
            extent: [150.7308769226076, -34.22031998634341, 151.50953292846697, -33.633238077163725], //sydney
            zoom: 12,
            minZoom: 10,
            maxZoom: 16
            ';
            } else if ($_REQUEST['region'] == 'qld') {
                echo '
            center: [151.0942840576172, -33.925824165344224], 
            extent: [128.84636772311333, -30.857151635534336, 161.34392631686333, -14.597386010534336], //qld
            zoom: 6,
            minZoom: 6,
            maxZoom: 16
            ';
            } else if ($_REQUEST['region'] == 'darwin') {
                echo '
            center: [130.90373182880757, -12.440307702414483], 
            extent: [130.6498446522939, -12.567337121359795, 131.15761900532124, -12.31327828346917], //darwin
            zoom: 12,
            minZoom: 10,
            maxZoom: 16
            ';
            }else {
                echo '
                center: [134.37218286877658, -22.636157167491735], 
                extent: [113.338953078, -43.6345972634, 153.569469029, -10.6681857235], // australia
                zoom: 5,
                minZoom: 5,
                maxZoom: 16
                ';
            }
            ?>
        })
    });
    var layerSwitcher = new ol.control.LayerSwitcher();
    map.addControl(layerSwitcher);
    var popup = new Popup();
map.addOverlay(popup);

    map.on('click', function(evt) {
    var feature = map.forEachFeatureAtPixel(evt.pixel,
      function(feature, layer) {
        var message;
        if (layer.get("type") == 'weather') {
            message = 'Temperature: ' + feature.get("primary").dt.toFixed(2) + '&deg;';
        } else if (layer.get("type") == 'fire') {
            message = '<a href="'+feature.get("link")+
            '">' + feature.get("title")+"</a><br/>" + feature.get("description");
        } else if (layer.get("type") == 'dust') {
            message =  feature.get("name")+"<br>PM2.5: "+feature.get("pm2_5")+"<br/> PM10: "+feature.get("pm10")
            +"<br/><small><a href='https://www.qld.gov.au/environment/pollution/monitoring/air/air-monitoring/air-quality-index' target='_blank'>Learn more about PM2.5/PM10 air quality indicators...</a></small>" ;
        } else {
            function replacer(key, value) {
  // Filtering out properties
  if (key == 'geometry') {
    return undefined;
  }
  return value;
}
            message = layer.get("title")+"-"+layer.get("type") +":" + JSON.stringify(feature.getProperties(), replacer);
        }
        console.log(evt.coordinate)
    popup.show(evt.coordinate, message);
      });
    

    
    });
  
</script>
</body>

</html>