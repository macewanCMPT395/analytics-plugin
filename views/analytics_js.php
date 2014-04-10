$( document ).ready( function (){

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

    // Get query string value
    // NOTE: stackoverflow.com/questions/4656843/jquery-get-querystring-from-url
    function getVars( queryString )
    {
        var vars = [], hash;
        var hashes = queryString.slice( queryString.indexOf('?') + 1 ).split( '&' );
        
        for( var i = 0; i < hashes.length; i++ )
        {
            hash = hashes[i].split('=');
            vars.push(hash[0]);
            vars[hash[0]] = hash[1];
        }

        return vars;
    }

    // Create filter accoridion
    $('#chart-filter-box').accordion({ collapsible: true, heightStyle: "content" });

    // Add date picker to form
    $("#date-from").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "yy-mm-dd",
        altFormat: "yy-mm-dd",
        yearRange: "c-20:c+0",
        minDate: null
    });

    $("#date-to").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "yy-mm-dd",
        altFormat: "yy-mm-dd",
        yearRange: "c-20:c+0",
        maxDate: 0
    });

    var plot;
    var plot_overview;

    // Handle submit button
    $('#filter').submit( function( event ){
        event.preventDefault();
        event.stopPropagation();

        // Get query string
        var query_string = $('#filter').serialize();
        var url ='<?php echo url::base(TRUE)."analytics_json/get"; ?>';

        // Generate charts
        $.ajax({
            url: url,
            data: query_string
        }).success( function(data){
                // Gather all data series to plot 
                var d = [];
                $(data['chartData']).each( function( i ){
                    if( getVars( query_string )["chartType"] === "bar" )
                    {
                        d.push({
                            data: data['chartData'][i]['data'], 
                            label: data['chartData'][i]['label'] ,
                            bars: {
                                show: true,
                                fill: true,
                                lineWidth: 1,
                                barWidth: 12*24*60*60,
                                fillColor: d['chartData'][i]['color']
                            }
                        });
                    }
                    else
                    {
                        d.push({ 
                            data: data['chartData'][i]['data'], 
                            label: data['chartData'][i]['label'] 
                        });
                    }
                });

                // Plot chart
                if( getVars( query_string )["chartType"] === "pie" )
                {
                    var options = generatePieChartOptions();
                    var plot = $.plot( "#chart-window", d, options );
                }
                else if( getVars( query_string )["chartType"] == "line" )
                {
                    // Unbind plots
                    $("#chart-window").unbind();
                    $("#chart-overview").unbind();

                    // Plot main line chart
                    //var options = generateChartOptions();
                    var options = {
                        xaxis: {
                            mode: "time",
                            tickLength: 5
                        },
                        selection: {
                            mode: "x"
                        },
                        grid: {
                            //markings: 
                        },
                        legend: {
                            position: "nw",
                            backgroundOpacity: 0
                        }
                    };

                    plot = $.plot("#chart-window", d, options );

                    // Plot chart overview
                    var overview_options = {
                        series: {
                            lines: {
                                show: true,
                                lineWidth: 1
                            },
                            shadowSize: 0
                        },
                        xaxis: {
                            ticks: [],
                            mode: "time"
                        },
                        yaxis: {
                            ticks: [],
                            min: 0,
                            autoscaleMargin: 0.1
                        },
                        selection: {
                            mode: "x"
                        },
                        legend: {
                            show: false
                        }
                    };

                    plot_overview = $.plot( "#chart-overview", d, overview_options );

                    // Bind the charts together

                    $("#chart-window").bind("plotselected", function( event, ranges ){
                        // Zoom
                        plot = $.plot( "#chart-window", d, $.extend( true, {}, options, {
                            xaxis: {
                                min: ranges.xaxis.from,
                                max: ranges.xaxis.to
                            }
                        }));

                        // Dont fire event on overview
                        plot_overview.setSelection( ranges, true );
                    });

                    $("#chart-overview").bind("plotselected", function( event, ranges ){
                        plot.setSelection( ranges );
                    });
                }
                else
                {
                    // Plot main bar chart
                    var options = {
                        xaxis: {
                            mode: "time",
                            tickLength: 5
                        },
                        selection: {
                            mode: "x"
                        },
                        grid: {
                            //markings: 
                        },
                        legend: {
                            position: "nw",
                            backgroundOpacity: 0
                        }
                    };

                    plot = $.plot("#chart-window", d, options );

                    // Plot chart overview
                    var overview_options = {
                        series: {
                            lines: {
                                show: true,
                                lineWidth: 1
                            },
                            shadowSize: 0
                        },
                        xaxis: {
                            ticks: [],
                            mode: "time"
                        },
                        yaxis: {
                            ticks: [],
                            min: 0,
                            autoscaleMargin: 0.1
                        },
                        selection: {
                            mode: "x"
                        },
                        legend: {
                            show: false
                        }
                    };

                    plot_overview = $.plot( "#chart-overview", d, overview_options );

                    // Bind the charts together

                    $("#chart-window").bind("plotselected", function( event, ranges ){
                        // Zoom
                        plot = $.plot( "#chart-window", d, $.extend( true, {}, options, {
                            xaxis: {
                                min: ranges.xaxis.from,
                                max: ranges.xaxis.to
                            }
                        }));

                        // Dont fire event on overview
                        plot_overview.setSelection( ranges, true );
                    });

                    $("#chart-overview").bind("plotselected", function( event, ranges ){
                        plot.setSelection( ranges );
                    });
                }
                
        }).fail( function(){
            alert( "An error occured preparing chart." );    
        });

    });

    // Trigger default filter view on page load
    $("#filter").submit();
});

