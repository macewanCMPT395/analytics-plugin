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

$( document ).ready( function (){
    $("#parellel-coords").hide();

    // Tabs
    // NOTE: jquery ui tabs is missing
    $('#analytics-tab-0').click( function(){
        $('#parellel-coords').hide();
        $('#filter-view').show();
    });
    
    $('#analytics-tab-1').click( function(){
        $('#parellel-coords').show();
        $('#filter-view').hide();
    });

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

    // The two charts to display
    var plot;
    var plot_overview;

    // Handle submit button
    $('#filter').submit( function( event ){
        event.preventDefault();
        event.stopPropagation();

        // Get query string
        var query_string = $('#filter').serialize();

        // Generate charts
        $.ajax({
            url: '<?php echo url::base(TRUE)."analytics_json/get"; ?>',
            data: query_string
        }).success( function(data){
                // Init data series
                var data_series = [];

                // Get data series
                $(data['chartData']).each( function( i ){

                    // Bar charts require special options on each data series
                    if( getVars( query_string )["chartType"] === "bar" )
                    {
                        // Create data series for each data set
                        data_series.push({
                            data: data['chartData'][i]['data'],             // Select data to display
                            label: data['chartData'][i]['label'] ,          // Select data labels
                            lines: {
                                show: false                                 // Show lines between points
                            },
                            bars: {
                                show: true,                                 // Show bars
                                fill: true,                                 // Fill bars
                                lineWidth: 1,                               // Pixels
                                barWidth: 12*24*60*60,                      // Set bar width
                                fillColor: data['chartData'][i]['color']    // Set bar fill color
                            }
                        });
                    }
                    // Create data series for normal data set
                    else
                    {
                        // Create data series for each data set
                        data_series.push({ 
                            data: data['chartData'][i]['data'],         // Select data to plot
                            label: data['chartData'][i]['label']        // Select data labels
                        });
                    }
                });

                // Get chart type
                var chart_type = getVars( query_string )[ "chartType" ];

                // Plot chart
                switch( chart_type )
                {
                case 'bar':
                    // Show chart overview
                    $('#chart-overview').show();

                    // Set bar chart options
                    var options = {
                        xaxis: {
                            mode: "time",           // show date/time on axis
                            tickLength: 5           // point interval
                        },
                        selection: {
                            mode: "x"               // Select axis
                        },
                        grid: {
                            //markings: 
                        },
                        legend: {
                            position: "nw",         // Legend location
                            backgroundOpacity: 0,   // Legend opacity
                            margin: 5,
                            container: '#chart-legend',
                            noColumns: 8
                        }
                    };

                    // Plot bar chart
                    plot = $.plot("#chart-window", data_series, options );

                    // Set chart overview options
                    var overview_options = {
                        series: {
                            lines: {
                                show: true,         // Display lines
                                lineWidth: 1
                            },
                            shadowSize: 0           // Set shadow size
                        },
                        xaxis: {
                            ticks: [],
                            mode: "time"            // Display date/time on axis
                        },
                        yaxis: {
                            ticks: [],
                            min: 0,                 // Minimum value to show
                            autoscaleMargin: 0.1    // Scale axis
                        },
                        selection: {
                            mode: "x"               // Select on axis
                        },
                        legend: {
                            show: false             // Display legend
                        }
                    };

                    // Plot bar chart overview
                    plot_overview = $.plot( "#chart-overview", data_series, overview_options );

                    // Bind the charts together
                    $("#chart-window").bind("plotselected", function( event, ranges ){
                        // Select ranges
                        plot = $.plot( "#chart-window", data_series, $.extend( true, {}, options, {
                            xaxis: {
                                min: ranges.xaxis.from,
                                max: ranges.xaxis.to
                            }
                        }));

                        // Dont fire event on overview (prevents infinite loop)
                        plot_overview.setSelection( ranges, true );
                    });

                    // Zoom to selection
                    $("#chart-overview").bind("plotselected", function( event, ranges ){
                        plot.setSelection( ranges );
                    });

                    break;

                case "line":
                    // Show chart overview
                    $('#chart-overview').show();

                    // Set line chart options
                    var options = {
                        xaxis: {
                            mode: "time",       // Use dates/times on axis
                            tickLength: 5       // How many points to show
                        },
                        selection: {
                            mode: "x"           // Select axis
                        },
                        grid: {
                            //markings: 
                        },
                        legend: {
                            position: "nw",     // Legend position = ne,se,sw,nw
                            backgroundOpacity: 0,
                            margin: 5,
                            container: '#chart-legend',
                            noColumns: 8
                        }
                    };

                    // Set overview line chart options
                    var overview_options = {
                        series: {
                            lines: {
                                show: true,     // Draw lines between points
                                lineWidth: 1    // Pixels
                            },
                            shadowSize: 0       // Display shadow
                        },
                        xaxis: {
                            ticks: [],
                            mode: "time"        // show date/time on axis
                        },
                        yaxis: {
                            ticks: [],
                            min: 0,
                            autoscaleMargin: 0.1    
                        },
                        selection: {
                            mode: "x"           // Select axis
                        },
                        legend: {
                            show: false         // Show legend
                        }
                    };

                    // Plot pie chart
                    plot = $.plot("#chart-window", data_series, options );

                    // Plot overview chart
                    plot_overview = $.plot( "#chart-overview", data_series, overview_options );

                    // Bind the charts together
                    $("#chart-window").bind("plotselected", function( event, ranges ){
                        // Select range
                        plot = $.plot( "#chart-window", data_series, $.extend( true, {}, options, {
                            xaxis: {
                                min: ranges.xaxis.from,
                                max: ranges.xaxis.to
                            }
                        }));

                        // Dont fire event on overview (prevents infinite loop)
                        plot_overview.setSelection( ranges, true );
                    });

                    // Plot selection
                    $("#chart-overview").bind("plotselected", function( event, ranges ){
                        plot.setSelection( ranges );
                    });

                    break;

                case "pie": // fall through
                default:

                    // Hide chart overview window
                    $('#chart-overview').hide();

                    // Set piechart options
                    var options = {
                        series: {
                            // Set pie chart specific options
                            pie: {
                                show: true,             // show pie chart
                                radius: 'auto',         // set circle radius = [0..1]
                                innerRadius: 0,         // set inner circle radius (doghnut charts) = [0..1]
                                label: {            
                                    show: true,         // Show labels
                                    radius: 1,          // Label size
                                    threshold: 0.02,    // Sets whether or not to display lablels [0..1]
                                    background: {   
                                        opacity: 0.8,   // set label opacity
                                        color: '#000'   // set label color
                                    }
                                }
                            }
                        },
                        // Legend options
                        legend: {
                            show: true,                 // Show a legend
                            position: "ne",
                            margin: 5,
                            container: '#chart-legend',
                            noColumns: 8
                        }
                    };

                    // Plot pie chart
                    plot = $.plot( "#chart-window", data_series, options );
                    
                    break;
                }

        }).fail( function(){
            // Inform user
            alert( "An error occured preparing chart." );    
        });
    });

    // Trigger default filter view on page load
    $('input[name="chartType"]')[0].checked = true;
    $("#filter").submit();
});

