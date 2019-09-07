<?php
require_once("lib.inc.php");
include_header();
?>
<iframe src="https://giphy.com/embed/xMFawXGPvt05y" width="480" height="313" frameBorder="0" class="giphy-embed" allowFullScreen></iframe>
  <br/>
  <a class="au-cta-link" href="map.php?region=syd">go to sydney including liverpool!</a>
  <a class="au-cta-link" href="map.php?region=qld">go to queensland!</a>
  <a class="au-cta-link" href="map.php?region=darwin">go to darwin!</a>

  <h1>I want to go to there</h1>

  <h2> datasets used</h2>
  <ul>
    <li>https://sentinel.ga.gov.au/</li>
    <li>https://data.nsw.gov.au/data/dataset/nsw-rural-fire-service-current-incidents-feed</li>
    <li>https://australia.maps.luftdaten.info</li>
    <li>http://bom-wow.metoffice.gov.uk</li>
    <li>Liverpool Smart Pedestrian project http://pavo.its.uow.edu.au:6969/dashboard</li>
</ul>
Air particulate levels good/bad based on https://www.qld.gov.au/environment/pollution/monitoring/air/air-monitoring/air-quality-index but some datasets are used with current levels rather than 24 hour averages.
    All emojis designed by OpenMoji – the open-source emoji and icon project. License: CC BY-SA 4.0 https://github.com/hfg-gmuend/openmoji
  <?php
  include_footer();