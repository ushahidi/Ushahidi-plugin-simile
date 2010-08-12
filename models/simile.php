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
