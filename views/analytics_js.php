<?php 
/**
 * Analytics scripting

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
 ?>
$( document ).ready( function (){
    // create tabs
    //$( '#analytic-tabs' ).tabs();

    // Plot Pie Chart
    var pie_chart_url = '<?php echo url::base( FALSE ).'analytics_json/piechart_json/'; ?>';
    $.getJSON( pie_chart_url, function( data ){
        $.plot('#chart-all-time', data['chartData'], {
            series: {
                pie: {
                    show: true,
                    radius: 1
                }
            },
            legend: {
                show: true
            }
        }); // end plot
    }); // end getJSON

    // Plot daily line Chart
    var d = [];
    var daily_incidents = '<?php echo url::base( FALSE ).'analytics_json/linechart_json/'; ?>';
    $.getJSON( daily_incidents, function( mydata ){
        var plot = $.plot('#chart-daily-incidents', [
                    { data: mydata['chartData'][0]['data'], label: mydata['chartData'][0]['category'] },
                    { data: mydata['chartData'][1]['data'], label: mydata['chartData'][1]['category'] },
                    { data: mydata['chartData'][2]['data'], label: mydata['chartData'][2]['category'] },
                    { data: mydata['chartData'][3]['data'], label: mydata['chartData'][3]['category'] },
                    { data: mydata['chartData'][4]['data'], label: mydata['chartData'][4]['category'] },
                    { data: mydata['chartData'][5]['data'], label: mydata['chartData'][5]['category'] },
                    { data: mydata['chartData'][6]['data'], label: mydata['chartData'][6]['category'] },
                    { data: mydata['chartData'][7]['data'], label: mydata['chartData'][7]['category'] },
                    { data: mydata['chartData'][8]['data'], label: mydata['chartData'][8]['category'] },
                    { data: mydata['chartData'][9]['data'], label: mydata['chartData'][9]['category'] },
                    { data: mydata['chartData'][10]['data'], label: mydata['chartData'][10]['category'] },
                    { data: mydata['chartData'][11]['data'], label: mydata['chartData'][11]['category'] },
                    { data: mydata['chartData'][12]['data'], label: mydata['chartData'][12]['category'] },
                    { data: mydata['chartData'][13]['data'], label: mydata['chartData'][13]['category'] },
                    { data: mydata['chartData'][14]['data'], label: mydata['chartData'][14]['category'] },
                    { data: mydata['chartData'][15]['data'], label: mydata['chartData'][15]['category'] },
                    { data: mydata['chartData'][16]['data'], label: mydata['chartData'][16]['category'] },
                    { data: mydata['chartData'][17]['data'], label: mydata['chartData'][17]['category'] },
                    { data: mydata['chartData'][18]['data'], label: mydata['chartData'][18]['category'] }
            ], {
            xaxis: { mode: "time" },
            yaxis: { min: 0 },
            legend: { position: "nw" }
        }); // end plot
    }); // end getJSON

    var total_incidents = '<?php echo url::base( FALSE ).'analytics_json/linechart_json/1/'; ?>';
    $.getJSON( total_incidents, function( mydata ){
        $.plot('#chart-total-incidents', [ 
            { data: mydata['chartData'][0]['data'], label: mydata['chartData'][0]['category'] },
            { data: mydata['chartData'][1]['data'], label: mydata['chartData'][1]['category'] },
            { data: mydata['chartData'][2]['data'], label: mydata['chartData'][2]['category'] },
            { data: mydata['chartData'][3]['data'], label: mydata['chartData'][3]['category'] },
            { data: mydata['chartData'][4]['data'], label: mydata['chartData'][4]['category'] },
            { data: mydata['chartData'][5]['data'], label: mydata['chartData'][5]['category'] },
            { data: mydata['chartData'][6]['data'], label: mydata['chartData'][6]['category'] },
            { data: mydata['chartData'][7]['data'], label: mydata['chartData'][7]['category'] },
            { data: mydata['chartData'][8]['data'], label: mydata['chartData'][8]['category'] },
            { data: mydata['chartData'][9]['data'], label: mydata['chartData'][9]['category'] },
            { data: mydata['chartData'][10]['data'], label: mydata['chartData'][10]['category'] },
            { data: mydata['chartData'][11]['data'], label: mydata['chartData'][11]['category'] },
            { data: mydata['chartData'][12]['data'], label: mydata['chartData'][12]['category'] },
            { data: mydata['chartData'][13]['data'], label: mydata['chartData'][13]['category'] },
            { data: mydata['chartData'][14]['data'], label: mydata['chartData'][14]['category'] },
            { data: mydata['chartData'][15]['data'], label: mydata['chartData'][15]['category'] },
            { data: mydata['chartData'][16]['data'], label: mydata['chartData'][16]['category'] },
            { data: mydata['chartData'][17]['data'], label: mydata['chartData'][17]['category'] },
            { data: mydata['chartData'][18]['data'], label: mydata['chartData'][18]['category'] }
        ], {
            xaxis: { mode: "time" },
            yaxis: { min: 0 },
            legend: { position: "nw" },
        }); // end plot
    }); // end getJSON

/*
    // Plot total line Chart
    var total_incidents = '<?php echo url::base( FALSE ).'analytics_json/linechart_json/1/'; ?>';
    $.getJSON( total_incidents, function( mydata ){
        $.each( mydata['chartData'], function( k, v ){
            //console.log( v['category'] );
            //console.log( v['chart'] );
        });

        $.plot('#chart-total-incidents', [ 
            { data: mydata['chartData'][4]['data'], label: mydata['chartData'][4]['category'] }
        ], {
            color: mydata['chartData'][4]['color'],
            xaxis: [ { mode: "time" } ],
            yaxis: [ { min: 0 } ],
            legend: { position: "sw" }
        }); // end plot
    }); // end getJSON
    */
});
