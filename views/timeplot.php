<?php
/**
 * Simile Timeplot Example view page.
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
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
	      "http://www.w3.org/TR/html4/strict.dtd">
<html>
  <head>
	  <title>Ushahidi Simile Timeplot</title>
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
	</style>
    <script src="http://api.simile-widgets.org/timeplot/1.1/timeplot-api.js"
       type="text/javascript"></script>
    <script type="text/javascript">
		var timeplot;

		function onLoad() {
			var eventSource = new Timeplot.DefaultEventSource();
			var plotInfo = [
				Timeplot.createPlotInfo({
					id: "plot1",
					dataSource: new Timeplot.ColumnSource(eventSource,1),
					valueGeometry: new Timeplot.DefaultValueGeometry({
						gridColor: "#000000",
						axisLabelsPlacement: "left",
						min: 0
					}),
					timeGeometry: new Timeplot.DefaultTimeGeometry({
						gridColor: "#000000",
						axisLabelsPlacement: "top"
					}),
					lineColor: "#ff0000",
					fillColor: "#cc8080",
					showValues: true
				})
			];

			timeplot = Timeplot.create(document.getElementById("timeplot"),
															 plotInfo);
			timeplot.loadText("timeplot_text_data", ",", eventSource);
		}

		var resizeTimerID = null;
		function onResize() {
			if (resizeTimerID == null) {
				resizeTimerID = window.setTimeout(function() {
					resizeTimerID = null;
					timeplot.repaint();
				}, 100);
			}
		}
	</script>
	</head>
	<body onload="onLoad();" onresize="onResize();">
		<h3>Ushahidi Simile Timeplot</h3>
		<div id="timeplot" style="height: 150px;"></div>
		<div class="caption">no. of incidents perd day</div>
	</body>
</html>