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

    /**
     * Tab functionality
     *
     * @note jquery ui widget does not work
     */
    function hideCharts(){
        $('#all-time').hide();
        $('#daily-incidents').hide();
        $('#total-incidents').hide();
        $('#parellel-coords').hide();
    }

    hideCharts();
    $('#all-time').show();

    $('#analytics-tab-0').click( function(){
        hideCharts();

        $('#all-time').show();
    });

    $('#analytics-tab-1').click( function(){
        hideCharts();

        $('#daily-incidents').show();
    });

    $('#analytics-tab-2').click( function(){
        hideCharts();

        $('#total-incidents').show();
    });
    
    $('#analytics-tab-3').click( function(){
        hideCharts();

        $('#parellel-coords').show();
    });

    /**
     * Generate pie chart options
     *
     * @param circleRadius the radius of the outer circle between 0 and 1 inclusive
     * @param circleInnerRadius the radius of the inner circle (donut) between 0 and 1 inclusive
     * @param showLegend show the legend
     * @param showLabel show labels on the chart (true or false)
     * @param labelRadius the radius at which labels are placed
     * @param labelThreshold the threshold where labels are not shown
     * @param labelColor the label background color
     * @param labelOpacity the opacity of the label background color
     * @return pie chart options
     */
    function generatePieChartOptions( circleRadius, circleInnerRadius, showLegend, showLabel, labelRadius, labelThreshold, labelColor, labelOpacity )
    {
        // Check parameters
        if( typeof( circleRadius ) === 'undefined' )
            circleRadius = 'auto';

        if( typeof( circleInnerRadius ) === 'undefined' )
            circleInnerRadius = 0;
        
        if( typeof( showLegend ) === 'undefined' )
            showLegend = true;

        if( typeof( showLabel ) === 'undefined' )
            showLabel = true;

        if( typeof( labelRadius ) === 'undefined' )
            labelRadius = 1;

        if( typeof( labelThreshold ) === 'undefined' )
            labelThreshold = 0.02;

        if( typeof( labelColor ) === 'undefined' )
            labelColor = '#000';

        if( typeof( labelOpacity ) === 'undefined' )
            opacity = 0.08;

        // Create options
        var options = {
            series: {
                pie: {
                    show: true,
                    radius: circleRadius,
                    innerRadius: circleInnerRadius,
                    label: {
                        show: showLabel,
                        radius: labelRadius,
                        threshold: labelThreshold,
                        background: {
                            opacity: labelOpacity,
                            color: labelColor
                        }
                    }
                }
            },
            legend: {
                show: showLegend
            }
        };

        return options;
    }

    /**
     * Generate line/bar chart options
     * 
     * @param showLegend show a legend (true/false)
     * @param showLines show lines (true/false)
     * @param showPoints show points (true/false)
     * @param xAxisLabel set x axis label
     * @param yAxisLabel set y axis label
     * @return line/bar chart options
     */
    function generateChartOptions( showLegend, showLines, showPoints, xAxisLabel, yAxisLabel )
    {
        // Check parameters
        if( typeof( showLegend ) === 'undefined' )
            showLegend = true;

        if( typeof( showLines ) === 'undefined' )
            showLines = true;

        if( typeof( showPoints ) === 'undefined' )
            showPoints = true;

        if( typeof( xAxisLabel ) === 'undefined' )
            xAxisLabel = "x";

        if( typeof( yAxisLabel ) === 'undefined' )
            yAxisLabel = "y";

        // Create options
        var options = {
            series: {
                lines: {
                    show: showLines
                },
                points: {
                    show: showPoints
                }
            },
            xaxis: {
                mode: "time",
                axisLabel: xAxisLabel,
                axisLabelUseCanvas: true,
                axisLabelPadding: 10 
            },
            yaxis: {
                min: 0,
                axisLabel: yAxisLabel,
                axisLabelUseCanvas: true,
                axisLabelPadding: 10 
            },
            legend: {
                show: showLegend,
                position: "nw"
            },
            selection : {
                mode: "x"
            }
        };

        return options;
    }

    /**
     * Display a pie chart
     *
     * @param placeholder a string containing the name of the placeholder to bind to
     * @param url the URL where the data is fetched from
     * @param options pie chart options
     */
    function displayPieChart( placeholder, url, options )
    {
        // Check parameters
        if( typeof( placeholder ) === 'undefined' )
            placeholder ='#chart-window';

        if( typeof( url ) === 'undefined' )
            url = '<?php echo url::base(TRUE).'analytics_json/piechart_json/'; ?>';

        if( typeof( options ) === 'undefined' )
            options = generatePieChartOptions();

        // Plot pie chart
        $.getJSON( url, function( data ){
            $.plot( placeholder, data[ 'chartData' ], options );
        });
    }

    /**
     * Display a line chart
     *
     * @param placeholder a string containing the name of the placeholder to bind to
     * @param url the URL where the data is fetched from
     * @param options the line chart options
     */
    function displayLineChart( placeholder, url, options )
    {
        // Check parameters and set defaults if necessary
        if( typeof( placeholder ) === 'undefined' )
            placeholder ='#chart-window';

        if( typeof( url ) === 'undefined' )
            url = '<?php echo url::base(TRUE).'analytics_json/linechart_json/1/'; ?>';

        if( typeof( options ) === 'undefined' )
            options = generateChartOptions();

        // Plot line chart
        $.getJSON( url, function( d ){
            var data = [];

            // Gather all data series to plot 
            $(d['chartData']).each( function( i ){
                data.push({ 
                    data: d['chartData'][i]['data'], 
                    label: d['chartData'][i]['category'] 
                });
            });

            // Allow zoom
            $( placeholder ).bind( "plotselected" , function( event, ranges ){
                plot = $.plot( placeholder, data, $.extend(true, {}, options, {
                    xaxis: {
                        min: ranges.xaxis.from,
                        max: ranges.xaxis.to
                    }
                }));
            });

            // plot graph
            $.plot( placeholder, data, options );
        });
    }

    /**
     * Display a bar chart
     *
     * @param placeholder a string containing the name of the placeholder to bind chart to
     * @param url the URL where the data is fetched from
     * @param options the bar chart options
     */
    function displayBarChart( placeholder, url, options )
    {
        var plot;

        // Check parameters and set defaults if necessary
        if( typeof( placeholder ) === 'undefined' )
            placeholder ='#chart-window';

        if( typeof( url ) === 'undefined' )
            url = '<?php echo url::base( TRUE ).'analytics_json/linechart_json/'; ?>';

        if( typeof( options ) === 'undefined' )
            options = generateChartOptions( true, false, false );

        // Plot line chart
        $.getJSON( url, function( d ){
            var data = [];

            // Gather data from all data series
            $(d['chartData']).each( function( i ){
                data.push({ 
                    data: d['chartData'][i]['data'], 
                    label: d['chartData'][i]['category'],
                    // bar chart specific options
                    bars: { 
                        show: true,
                        barWidth: 12*24*60*60,
                        fill: true,
                        lineWidth: 1,
                        fillColor: d['chartData'][i]['color']
                    }
                });
            });

            // Plot line chart
            plot = $.plot( placeholder, data, options );

            $( placeholder ).bind( "plotselected" , function( event, ranges ){
                plot = $.plot( placeholder, data, $.extend(true, {}, options, {
                    xaxis: {
                        min: ranges.xaxis.from,
                        max: ranges.xaxis.to
                    }
                }));
            });
            $.plot( placeholder, data, options );
        });
    }

    displayPieChart( '#chart-all-time' );
    displayLineChart( '#chart-total-incidents' );
    displayBarChart( '#chart-daily-incidents' );
});