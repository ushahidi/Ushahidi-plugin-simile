<?php defined('SYSPATH') or die('No direct script access.'); 
/**
 * Simile Controller
 * Demonstrates usage of Simile Timeline, Timemap and Timeplot
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author	   Ushahidi Team <team@ushahidi.com>
 * @package    Ushahidi - http://source.ushahididev.com
 * @module	   Simile Timeline Controller
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license    http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
*
*/

class Simile_Controller extends Controller
{

	/*
	 * Displays a page with a Simile Timeline of incidents per day
	 */
	public function timeline()
	{
		//$incident = ORM::factory('incident')->find();
		//$view->focusDate = $incident->incident_date;
		$view = new View("timeline");
		$view->render(TRUE);
	}
	
	/*
	 * returns JSON of incidents formatted for Simile Timeline
	 */
	public function timeline_data()
	{
		$this->auto_render = FALSE;
        $this->template = new View('json/timeline');
		// Retrieve all markers
		$markers = ORM::factory('incident')
			->select('DISTINCT incident.*')
			->with('location')
			->join('media', 'incident.id', 'media.incident_id','LEFT')
			->where('incident.incident_active = 1 ')
			->find_all();
		$timeline_data = "{\"dateTimeFormat\": \"iso8601\",
						\"wikiURL\": \"http://simile.mit.edu/shelf/\",
						\"wikiSection\": \"Simile Cubism Timeline\",
                        events: [ ";
		$json_array = array();
		foreach ($markers as $marker)
        {
            $json_item = "{";
            $json_item .= "\"start\": \"" . date('Y-m-d',strtotime($marker->incident_date)) . "\",";
			$json_item .= "\"title\": \"" . htmlentities($marker->incident_title)  ."\",";
			$json_item .= "\"description\": \"" . $marker->incident_description ."\",";
			$json_item .= "\"image\": \"" . url::base() . "media/img/media-image.jpg\",";
			$json_item .= "\"link\": \"" . url::base() . "reports/view/" . $marker->id ."\"";
			$json_item .= "}";
			array_push($json_array, $json_item);
		}
		$timeline_data .= implode(",", $json_array);
		$timeline_data .= "]}";

		//header('Content-type: application/json');
		echo $timeline_data;

	}

	/*
	 * Displays a page with a Simile Timeplot of incidents per day
	 */
	public function timeplot()
	{
		//$incident = ORM::factory('incident')->find();
		//$view->focusDate = $incident->incident_date;
		$view = new View("timeplot");
		$view->render(TRUE);
	}

	/*
	 * returns text data of number of incidents per day formatted for Simile
	 * Timeplot
	 */
	public function timeplot_text_data()
	{
		$this->auto_render = FALSE;
		$this->template = new View('json/timeline');
		// Retrieve all markers
		$markers = ORM::factory('incident')
			->select('incident.incident_date, COUNT(*) AS incident_count')
			->where('incident.incident_active = 1 GROUP BY DATE(incident_date)')
			->find_all();
		$timeplot_data = "# Ushahidi Text Data for Timeplot\n";
		$json_array = array();
		foreach ($markers as $marker)
		{
			$json_item = date('Y-m-d',strtotime($marker->incident_date)) . ",";
			$json_item .= $marker->incident_count;
			array_push($json_array, $json_item);
		}
		$timeplot_data .= implode("\n", $json_array);

		echo $timeplot_data;

	}
}
?>
