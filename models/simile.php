<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Model for Simile Timeline, Timeplot and Timemap
 *
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author     Ushahidi Team <team@ushahidi.com>
 * @package    Ushahidi - http://source.ushahididev.com
 * @module     Simile Model
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license    http://www.gnu.org/copyleft/lesser.html
 *             GNU Lesser General Public License (LGPL)
 */

class Simile_Model extends ORM {

	/*
	 * returns JSON of incidents formatted for Simile Timeline
	 */
	public function get_timeline_data($start=NULL, $end=NULL)
	{
		$filter = "";
		if ($start AND $end)
		{
			$filter = " incident_date>='".$start."' AND incident_date<='".$end."' ";
		}

		$markers = ORM::factory('incident')
			->select('DISTINCT incident.*')
			->join('location', 'location.id', 'incident.location_id')
			->where('incident.incident_active = 1 ')
			->where($filter)
			->find_all();

		$timeline_data = "{\"dateTimeFormat\": \"iso8601\",
						\"wikiURL\": \"http://simile.mit.edu/shelf/\",
						\"wikiSection\": \"Simile Cubism Timeline\",
                        events: [ ";
		$json_array = array();
		foreach ($markers as $marker)
        {
            $json_item = "{";
            $json_item .= "\"start\": \"" . 
			              date('Y-m-d',strtotime($marker->incident_date)) .
						  "\",";
			$json_item .= "\"title\": \"" .
			              htmlentities($marker->incident_title)  ."\",";
			$json_item .= "\"description\": \"" .
			              $marker->incident_description ."\",";
			$json_item .= "\"link\": \"" . url::base() .
			              "reports/view/" . $marker->id ."\"";
			$json_item .= "}";
			array_push($json_array, $json_item);
		}
		$timeline_data .= implode(",", $json_array);
		$timeline_data .= "]}";
		
		return $timeline_data;
	}

	/*
	 * Returns text data of number of incidents per day formatted for Simile
	 * Timeplot
	 */
	public function get_timeplot_text_data($category_id = NULL)
	{
		// Retrieve all markers
		if ( ! $category_id )
		{
			$markers = ORM::factory('incident')
				->select('incident.incident_date, COUNT(*) AS incident_count')
				->where('incident.incident_active = 1 GROUP BY DATE(incident_date)')
				->find_all();
		}
		else
		{
			$markers = ORM::factory('incident')
				->join('incident_category', 'incident.id', 'incident_category.incident_id')
				->join('category', 'category.id', 'incident_category.category_id')
				->select('incident.incident_date, COUNT(*) AS incident_count')
				->where('category.id', $category_id)
				->where('incident.incident_active = 1 GROUP BY DATE(incident_date)')
				->find_all();
		}
		$data = "# Ushahidi Text Data for Timeplot\n";
		$json_array = array();
		foreach ($markers as $marker)
		{
			$json_item = date('Y-m-d',strtotime($marker->incident_date)) . ",";
			$json_item .= $marker->incident_count;
			array_push($json_array, $json_item);
		}
		$data .= implode("\n", $json_array);;

		return $data;
	}

	/*
	 * returns JSON of incidents formatted for Simile Timemap
	 */
	public function get_timemap_data()
	{
		// Retrieve all active incidents
		$markers = ORM::factory('incident')
			->select('DISTINCT incident.*')
			->with('location')
			->join('media', 'incident.id', 'media.incident_id','LEFT')
			->where('incident.incident_active = 1 ')
			->find_all();

		$timemap_data = "{\"type\": \"basic\",\nvalue: [ ";
		$json_array = array();
		foreach ($markers as $marker)
        {
            $json_item = "{\n";
            $json_item .= "\"start\": \"" . date('Y-m-d',
					      strtotime($marker->incident_date)) . "\",\n";
			$json_item .= "\"title\": \"" . 
			              htmlentities($marker->incident_title) . "\",\n";
			$json_item .= "\"point\": {\n";
			$json_item .= "  \"lat\": " . $marker->location->latitude .",\n";
			$json_item .= "  \"lon\": " . $marker->location->longitude ."\n";
			$json_item .= "},\n";
			$json_item .= "\"options\": {\n";
			$json_item .= "  \"description\": \"" .
			              $marker->incident_description ."\"\n";
			$json_item .= "}\n}";
			array_push($json_array, $json_item);
		}
		$timemap_data .= implode(",", $json_array);
		$timemap_data .= "]}";

		return $timemap_data;
	}
}
