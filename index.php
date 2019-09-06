<html>
<head>
<script src="https://cdn.rawgit.com/openlayers/openlayers.github.io/master/en/v5.3.0/build/ol.js"></script>
<link rel="stylesheet" href="https://cdn.rawgit.com/openlayers/openlayers.github.io/master/en/v5.3.0/css/ol.css">

    <!-- The line below is only needed for old environments like Internet Explorer and Android 4.x -->
    <script src="https://cdn.polyfill.io/v2/polyfill.min.js?features=requestAnimationFrame,Element.prototype.classList,URL"></script>
</head>
<body>
<h1>I want to go to there!</h1>
    <div id="map" class="map"></div>
    <script>

      var map = new ol.Map({
        target: 'map',
        layers: [
          new ol.layer.Tile({
            source: new ol.source.XYZ({
              url: 'http://tile.stamen.com/terrain/{z}/{x}/{y}.png'
            })
          })
        ],
        view: new ol.View({
          center: [-472202, 7530279],
          zoom: 12
        })
      });
    </script>
</body>
</html>
