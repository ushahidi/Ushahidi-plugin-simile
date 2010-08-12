<?php
/**
 * Simile Timemap Example view page.
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author     Ushahidi Team <team@ushahidi.com>
 * @package    Ushahidi - http://source.ushahididev.com
 * @module     Simile
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license    http://www.gnu.org/copyleft/lesser.html GNU Lesser General
 * Public License (LGPL)
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>Ushahidi Simile Timemap</title>
	<style type="text/css">
		body {
			background: #EDE6D0;
		}
		h3 {
			color: #77603C;
		}
		.caption {
			font-style: italic;
			text-align: center;
		}
		
		#timeline {
			height: 200px;
		}
		#map {
			height: 250px;
		}
	</style>
    <script src="http://maps.google.com/maps?file=api&v=2&key=ABQIAAAAjsEM5UsvCPCIHp80spK1kBQKW7L4j6gYznY0oMkScAbKwifzxxRhJ3SP_ijydkmJpN3jX8kn5r5fEQ" type="text/javascript"></script>
    <script src="http://static.simile.mit.edu/timeline/api/timeline-api.js"
	        type="text/javascript"></script>
    <script src="../media/js/timemap_full.pack.js" type="text/javascript">
	</script>
  </head>
  <body onLoad="onLoad()" onunload="GUnload();">
	<h3>Ushahidi Simile Timemap</h3>
    <div id="timelinecontainer">
      <div id="timeline"></div>
    </div>
    <div id="mapcontainer">
      <div id="map"></div>
    </div>
	<script type="text/javascript">
	
	function onLoad() {
	  tm = TimeMap.init({
		mapId: "map",               // Id of map div element (required)
		timelineId: "timeline",     // Id of timeline div element (required)
		datasets: [
		  {
			data: <?php echo $timemap_data; ?>
		  }
		]
	  });
	}
	</script>
  </body>
</html>