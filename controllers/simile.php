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

class Simile_Controller extends Template_Controller {
	
	// Cache instance
	protected $cache;
	
	public $auto_render = TRUE;
	
	// Main template
    public $template = 'simile/layout';

	// Table Prefix
	protected $table_prefix;

    public function __construct()
    {
		parent::__construct();
		
		$this->cache = Cache::instance();
		
		// Set Table Prefix
		$this->table_prefix = Kohana::config('database.default.table_prefix');

		$this->template->site_name = Kohana::config('settings.site_name');
		$this->template->site_tagline = Kohana::config('settings.site_tagline');

		plugin::add_javascript('simile/views/js/jquery');
		plugin::add_stylesheet('simile/views/css/styles');
		plugin::add_stylesheet('simile/views/css/nav');
		
		$this->template->simile_js = "";
		$this->template->show_map = FALSE;
		$this->template->js = "";
		
		// Google Analytics
		$google_analytics = Kohana::config('settings.google_analytics');
		$this->template->google_analytics = $this->_google_analytics($google_analytics);
	}
	
	/*
	 * Displays a page with a Simile Timeline & Map of incidents per day
	 */
	public function index()
	{
		plugin::add_javascript('simile/views/js/timemap');
		plugin::add_javascript('simile/views/js/json');
		plugin::add_javascript('simile/views/js/progressive');
		plugin::add_stylesheet('simile/views/css/timemap');
		
		$this->template->content = new View("simile/timemap");
		$this->template->this_page = "timemap";
		$this->template->js = new View("simile/timemap_js");
		$this->template->simile_js = "<script src=\"http://maps.google.com/maps?file=api&v=2&key=".Kohana::config('settings.api_google')."\"
	      type=\"text/javascript\"></script><script src=\"http://static.simile.mit.edu/timeline/api-2.3.0/timeline-api.js?bundle=true\" type=\"text/javascript\"></script>";
	
		// Get the timelines startdate, datemin and datemax
		$first_date = date('Y-m-d');
		$start_date = date('Y-m-d');
		$last_date = "";
		
		$incidents = ORM::factory('incident')
			->where('incident.incident_active = 1 ')
			->orderby("incident_date", "asc")
			->find_all(1);
		foreach ($incidents as $incident)
		{
			$first_date = date('Y-m-d',strtotime($incident->incident_date));
			
			// Add 3 days to the first date for the timeline
			$start_date = date('Y-m-d',(strtotime($incident->incident_date) + 259200));
		}
		
		$incidents = ORM::factory('incident')
			->where('incident.incident_active = 1 ')
			->orderby("incident_date", "desc")
			->find_all(1);
		foreach ($incidents as $incident)
		{
			$last_date = date('Y-m-d',strtotime($incident->incident_date));
		}
		
		$this->template->js->first_date = $first_date;
		$this->template->js->start_date = $start_date;
		$this->template->js->last_date = $last_date;
	}
	
	/*
	 * returns JSON of incidents formatted for Simile Timeline
	 */
	public function timeline_data()
	{
		$this->auto_render = FALSE;
        $this->template = new View('json/timeline');

		// Get Progressive Loader Start and End Dates
		$start = (isset($_GET['start'])) ? $_GET['start'] : "";
		$end = (isset($_GET['end'])) ? $_GET['end'] : "";
		$callback = (isset($_GET['callback'])) ? $_GET['callback'] : "";
		if ($start AND $end)
		{
			$this->cache= Cache::instance();
			$timeline_cache = $this->cache->get('timeline-'.$start.'-'.$end);
			
			if ( ! $timeline_cache)
			{
				$filter = " incident_date>='".$start."' AND incident_date<='".$end."' ";
			
				// Retrieve all markers
				$markers = ORM::factory('incident')
					->join('location', 'location.id', 'incident.location_id')
					->where('incident.incident_active = 1 ')
					->where($filter)
					->find_all();
				$timeline_data = $callback."([";
				$json_array = array();
				foreach ($markers as $marker)
		        {
					$start_date = date('Y-m-d',strtotime($marker->incident_date));
					//$end_date = date('Y-m-d',(strtotime($marker->incident_date)+86400));
		            $json_item = "{";
		            $json_item .= "\"start\":\"" . $start_date . "\",";
					//$json_item .= "\"end\":\"" . $end_date . "\",";
					$json_item .= "\"point\":{\"lat\":".$marker->location->latitude.",\"lon\":".$marker->location->longitude." },";
					$json_item .= "\"title\":\"" . str_replace(chr(10), ' ', str_replace(chr(13), ' ', htmlentities($marker->incident_title))) ."\"";
					//$json_item .= ",\"options\": { ";
					//$json_item .= "\"infoHtml\" : \"".str_replace(chr(10), ' ', str_replace(chr(13), ' ', "<div class='custominfostyle'>" . htmlentities($marker->incident_description) . "</div>"))."\"";
					//$json_item .= "}";
					$json_item .= "}";
					array_push($json_array, $json_item);
				}
				$timeline_data .= implode(",", $json_array);
				$timeline_data .= "]);";

				// Cache this timeline
				$this->cache->set('timeline-'.$start.'-'.$end, $timeline_data, array('timelines'), 3600);
				
				echo $timeline_data;
			}
			else
			{
				echo $timeline_cache;
			}
		}
	}

	/*
	 * Displays a page with a Simile Timeplot of incidents per day
	 */
	public function timeplot()
	{
		$this->template->content = new View("simile/timeplot");
		$this->template->this_page = "timeplot";
		$this->template->js = new View("simile/timeplot_js");
		$this->template->js->categories = ORM::factory('category')->orderby('category_title', 'asc')->find_all();
		$this->template->simile_js = "<script src=\"http://api.simile-widgets.org/timeplot/1.1/timeplot-api.js?bundle=true\" type=\"text/javascript\"></script>";
	}

	/*
	 * returns text data of number of incidents per day formatted for Simile
	 * Timeplot
	 */
	public function timeplot_text_data($category_id = NULL)
	{
		$this->auto_render = FALSE;
		$this->template = new View('json/timeline');
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
	
	/*
	* Google Analytics
	* @param text mixed  Input google analytics web property ID.
    * @return mixed  Return google analytics HTML code.
	*/
	private function _google_analytics($google_analytics = false)
	{
		$html = "";
		if (!empty($google_analytics)) {
			$html = "<script type=\"text/javascript\">
				var gaJsHost = ((\"https:\" == document.location.protocol) ? \"https://ssl.\" : \"http://www.\");
				document.write(unescape(\"%3Cscript src='\" + gaJsHost + \"google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E\"));
				</script>
				<script type=\"text/javascript\">
				var pageTracker = _gat._getTracker(\"" . $google_analytics . "\");
				pageTracker._trackPageview();
				</script>";
		}
		return $html;
	}
}
?>
