<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * Analytics Controller
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license 
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author     Kundi Bora Team
 * @package    Ushahidi - http://source.ushahididev.com
 * @subpackage Analytics
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license    http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL) 
 */

class Analytics_Controller extends Main_Controller {
    function __construct()
    {
            parent::__construct();
    }

    public function index()
    {
        $this->template->header->this_page = 'analytics';
        $this->template->content = new View('analytics');

        $this->template->header->page_title .= Kohana::lang('ui_main.analytics').Kohana::config('settings.title_delimiter');

        $this->template->content->chart = new View( 'analytics_js' );

        $this->pieChartData = $this->piechart_json(); 
    }

    /**
     * Get json piechart formatting
     */
    public function analytics_json( )
    {
        $json_features = $this->piechart_json();
        $this->render_analytics_json( $json_features );
    }

    public function render_analytics_json( $json_features )
    {
        $json = json_encode( array(
            "type" => "FeatureCollection",
            "features" => $json_features
        ));

        //header( 'Content-type: application/json; charset=utf-8');

        echo $json;
    }

    public function piechart_json()
    {
        $db = new Database();

        // query database
        $sql = "SELECT COUNT( category.id ) AS count, category.category_title, category.category_color FROM category JOIN incident_category ON category.id=incident_category.category_id JOIN incident ON incident_category.incident_id=incident.id GROUP BY category.id";
        //$sql = "SELECT category.category_title, incident.incident_title, category.category_color FROM category JOIN incident_category ON category.id=incident_category.category_id JOIN incident ON incident_category.incident_id=incident.id";
        $query = $db->query($sql);

        // create JSON object
        $json_features = array();
        foreach( $query as $item )
        {
            //var_dump( $item );

            $json_item = array();
            $json_item = array(
                'value' => (int)$item->count,
                'color' => '#'.$item->category_color
            );

            array_push($json_features, $json_item);
        }

        $json = json_encode( $json_features );

        return $json;
    }
} // End Main
