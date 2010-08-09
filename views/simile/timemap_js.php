$(function() {
	TimeMap.init({
        mapId: "map",               // Id of map div element (required)
        timelineId: "timeline",     // Id of timeline div element (required) 
        options: {
			mapType: "normal",
            eventIconPath: "<?php echo url::base()."plugins/simile/views/images/";?>"
        },
        datasets: [
            {
                title: "Test This Out",
                theme: "red",
                type: "progressive",
                options: {
					// Data to be loaded in JSON from a remote URL
                    type: "json",
					url: "<?php echo url::site()."simile/timeline_data/"; ?>?start=[start]&end=[end]&callback=",
					<?php
					// Add 3 days
					echo "start: \"".$start_date."\",\n";
					// lower cutoff date for data
					echo "dataMinDate: \"".$first_date."\",\n";

					if ($last_date)
					{
						// upper cutoff date for data
						echo "dataMaxDate: \"".$last_date."\",\n";
					}
					?>
                    interval: 86400000,   
                    // function to turn date into string appropriate for service
                    formatDate: function(d) {
                        return TimeMap.util.formatDate(d, 1);
                    }
                }
            }
        ],
        bandInfo: [
            {
               width:          "85%", 
               intervalUnit:   Timeline.DateTime.DAY, 
		       intervalPixels: 300
            },
            {
               width:          "15%", 
               intervalUnit:   Timeline.DateTime.WEEK, 
		       intervalPixels: 200,
               showEventText:  false,
               trackHeight:    0.2,
               trackGap:       0.2,
			   overview:       true
            }
        ]
    });
});