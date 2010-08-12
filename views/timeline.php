<?php
/**
 * Similne Timeline view page.
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author     Ushahidi Team <team@ushahidi.com>
 * @package    Ushahidi - http://source.ushahididev.com
 * @module     Simile Timeline
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license    http://www.gnu.org/copyleft/lesser.html GNU Lesser General
 * Public License (LGPL)
 */
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
	"http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
		<title>Ushahidi Simile Timeline</title>
		<style type="text/css">
			body {
				background: #EDE6D0;
			}
			h3 {color: #77603C;}
		</style>
		<script src="http://static.simile.mit.edu/timeline/api-2.3.0/timeline-api.js?bundle=true"
		type="text/javascript"></script>
		<script type="text/javascript">
			var tl;
			var startDate = new Date();
			var focusDate = new Date();
			function onLoad()
			{
				var eventSource = new Timeline.DefaultEventSource();
				var bandInfos = [
					Timeline.createBandInfo({
						eventSource:    eventSource,
						date:           startDate, //"Jun 28 2007 00:00:00 GMT",
						width:          "70%",
						intervalUnit:   Timeline.DateTime.DAY,
						intervalPixels: 100
					}),
					Timeline.createBandInfo({
						eventSource:    eventSource,
						date:           focusDate, //"Jun 28 2009 00:00:00 GMT",
						width:          "30%",
						intervalUnit:   Timeline.DateTime.MONTH,
						intervalPixels: 200
					})
				];
				bandInfos[1].syncWith = 0;
				bandInfos[1].highlight = true;

				tl = Timeline.create(document.getElementById("timeline"),
				                     bandInfos);

				var url = "timeline_data/";
				tdata = "";
				$.getJSON(url,
				function(data) {
					tdata = data;
					eventSource.loadJSON(tdata, ".");
				})
			}

			var resizeTimerID = null;
			function onResize()
			{
				if (resizeTimerID == null) {
					resizeTimerID = window.setTimeout(function() {
						resizeTimerID = null;
						tl.layout();
					}, 500);
				}
			}
		</script>
	</head>
	<body onload="onLoad();" onresize="onResize();">
		<h3>Ushahidi Simile Timeline</h3>
		<div id="timeline" style="height: 400px; border: 1px solid #77603C;">
		</div>
		<noscript>
			This page uses Javascript to show you a Timeline. Please enable
			Javascript in your browser to see the full page. Thank you.
		</noscript>
	</body>
</html>
