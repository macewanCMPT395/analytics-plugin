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

class Analytics_json_Controller extends Controller {
    function __construct()
    {
            parent::__construct();
    }

    public function index()
    {
        $this->pieChartData = $this->piechart_json(); 
    }

    /**
     * Get json piechart formatting
     */
    public function piechart_json()
    {
        $json_features = $this->create_piechart_json();
        $this->render_analytics_json( $json_features );
    }

    public function linechart_json( $type = 0 )
    {
        $json_features = $this->create_linechart_json( $type );
        $this->render_analytics_json( $json_features );
    }

    public function render_analytics_json( $json_features )
    {
        $json = json_encode( array(
            "type" => "ChartCollection",
            "chartData" => $json_features
        ));

        header( 'Content-type: application/json; charset=utf-8');

        echo $json;
    }

    protected function create_piechart_json()
    {
        $db = new Analytics_Model;

        // query database
        $query = $db->get_incidents_grouped_by_id();

        // create JSON object
        $json_features = array();
        foreach( $query as $item )
        {
            $json_item = array();
            $json_item = array(
                'label' => $item->category_title,
                'data' => (int)$item->count
            );

            array_push($json_features, $json_item);
        }

        return $json_features;
    }

    protected function create_linechart_json( $type = 0 )
    {
        $db = new Analytics_Model;

        $categories = $db->get_categories();

        $json = array();
        foreach( $categories as $category )
        {
            //echo "$category->id : $category->category_title\n";
            // query database
            $incidents = $db->get_incidents_by_category( $category->id );

            // create category data
            $sum = 0;
            $json_category_data = array();
            foreach( $incidents as $incident )
            {
                //echo "->$incident->count : $incident->incident_date\n";

                $json_item = array();

                // incidents a day
                if( $type == 0 )
                {
                    $json_item = array(
                        (int)$incident->count,
                        strtotime( $incident->incident_date )
                    );
                }
                // incidents of all time
                else
                {
                    $sum += (int)$incident->count;
                    $json_item = array(
                        $sum,
                        strtotime( $incident->incident_date )
                    );
                }

                array_push($json_category_data, $json_item);
            }

            // Create category section
            $json_category = array();
            $json_category = array(
                'category' => $category->category_title,
                'chart' => $json_category_data
            );

            array_push( $json, $json_category );
        }
        return $json;
    }
} // End Main
