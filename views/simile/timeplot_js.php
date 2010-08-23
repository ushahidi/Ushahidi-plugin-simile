var timeplot;

function onLoad() {
	var eventSource = new Timeplot.DefaultEventSource();
	<?php
	foreach ($categories as $category)
	{
		?>
		var eventSource_<?php echo $category->id; ?> = new Timeplot.DefaultEventSource();
		<?php
	}
	?>
	var timeGeometry = new Timeplot.DefaultTimeGeometry({
		gridColor: "#000000",
		axisLabelsPlacement: "top"
	});

	var valueGeometry = new Timeplot.DefaultValueGeometry({
		gridColor: "#000000",
		axisLabelsPlacement: "left",
		min: 0
	});
  
	var plotInfo = [
		Timeplot.createPlotInfo({
			id: "plot1",
			dataSource: new Timeplot.ColumnSource(eventSource,1),
			valueGeometry: valueGeometry,
			timeGeometry: timeGeometry,
			lineColor: "#ff0000",
			fillColor: "#cc8080",
			showValues: true
		})
		<?php
		foreach ($categories as $category)
		{
			?>,
			Timeplot.createPlotInfo({
                id: "<?php echo $category->category_title; ?>",
                dataSource: new Timeplot.ColumnSource(eventSource_<?php echo $category->id; ?>,1),
				valueGeometry: valueGeometry,
				timeGeometry: timeGeometry,
                lineColor: "#<?php echo $category->category_color; ?>",
                dotColor: "#000000",
                showValues: true
            })	
			<?php
		}
		?>
	];

	timeplot = Timeplot.create(document.getElementById("timeplot"),
													 plotInfo);
	timeplot.loadText("timeplot_text_data", ",", eventSource);
	<?php
	foreach ($categories as $category)
	{
		?>
		timeplot.loadText("timeplot_text_data/<?php echo $category->id; ?>", ",", eventSource_<?php echo $category->id; ?>);
		<?php
	}
	?>
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

$(function() {
	onLoad();
	$(window).resize(function() {
	  onResize();
	});
});