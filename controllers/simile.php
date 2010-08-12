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
 * @license    http://www.gnu.org/copyleft/lesser.html
 *             GNU Lesser General Public License (LGPL)
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
	 * Displays JSON of incidents formatted for Simile Timeline
	 */
	public function timeline_data()
	{
		$this->auto_render = FALSE;
        $this->template = new View('json/timeline');
		$timeline_data = Simile_Model::get_timeline_data();
		//header('Content-type: application/json');
		echo $timeline_data;

	}

	/*
	 * Displays a page with a Simile Timeplot of incidents per day
	 */
	public function timeplot()
	{
		$view = new View("timeplot");
		$view->render(TRUE);
	}

	/*
	 * Displays text data of number of incidents per day formatted for Simile
	 * Timeplot
	 */
	public function timeplot_text_data()
	{
		$this->auto_render = FALSE;
		$this->template = new View('json/timeline');
		$timeplot_data = Simile_Model::get_timeplot_text_data();

		echo $timeplot_data;
	}

	/*
	 * Displays a page with a Mashup of Simile Timeline and Map of incidents
	 */
	public function timemap()
	{
		$view = new View("timemap");
		$view->timemap_data = Simile_Model::get_timemap_data();
		$view->render(TRUE);
	}

}
?>
