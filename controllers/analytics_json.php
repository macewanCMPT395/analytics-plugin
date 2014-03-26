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
    
    public function d3_para_coord_json()
    {
        $json_features = $this->create_d3_para_coord_json();
        $this->render_d3_json( $json_features );
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
    
    public function render_d3_json( $json_features )
    {
        $json = json_encode( $json_features);

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

    /**
     * Create a JSON object
     *
     * @param type the type of linechart to create: 0 = daily, 1 = total
     * @return a JSON object with the desired data to be rendered
     */
    protected function create_linechart_json( $type = 0 )
    {
        $db = new Analytics_Model;

        // for each category create data set
        $json = array();
        $categories = $db->get_categories();
        foreach( $categories as $category )
        {
            $incidents = $db->get_incidents_by_category( $category->id );
            $total = 0;

            // create data points
            $json_category_data = array();
            foreach( $incidents as $incident )
            {
                $json_item = array();

                // create javascript compatible string
                $timestamp = strtotime( $incident->incident_date ) * 1000;
                $count = (int)$incident->count;
                $total += $count;

                // select data set
                if( $type == 0 )
                {
                    $json_item = array(
                        $timestamp,
                        $count
                    );
                }
                else
                {
                    $json_item = array(
                        $timestamp,
                        $total
                    );
                }

                array_push($json_category_data, $json_item);
            }

            // Create category section
            $json_category = array();
            $json_category = array(
                'category' => $category->category_title,
                'color' => $category->category_color,
                'data' => $json_category_data
            );

            array_push( $json, $json_category );
        }

        return $json;
    }
    
    /**
     * Create a JSON object
     *
     * @return a JSON object with the desired data to be rendered
     */
    protected function create_d3_para_coord_json()
    {
        $db = new Analytics_Model;
        $search_time = 'Date';

        // query database
        $query = $db->get_incidents_table_D3_pc();

        // create JSON object
        $json_features = array();
        foreach ( $query as $row )
        {
            $json_item = array();
            foreach ( $row as $key => $value )
            {

                if ( $key == $search_time )
                {
                    $json_item[ $key ] = strtotime( +$value ) * 1000;
                }
                else
                {
                    $json_item[ $key ] = +$value;
                }
            }
            array_push( $json_features, $json_item );
        }
        return $json_features;
    }

// End Main
} // End Main
