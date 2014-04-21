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
                            lines: {
                                show: false
                            },
                            bars: {
                                show: true,
                                fill: true,
                                lineWidth: 1,
                                barWidth: 12*24*60*60,
                                fillColor: data['chartData'][i]['color']
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
                    var options = {
                        series: {
                            pie: {
                                show: true,
                                radius: 'auto',
                                innerRadius: 0,
                                label: {
                                    show: true,
                                    radius: 1,
                                    threshold: 0.02,
                                    background: {
                                        opacity: 0.8,
                                        color: '#000'
                                    }
                                }
                            }
                        },
                        legend: {
                            show: true
                        }
                    };

                    plot = $.plot( "#chart-window", d, options );
                    $('#chart-overview').hide();
                }
                else if( getVars( query_string )["chartType"] == "line" )
                {
                    $('#chart-overview').show();

                    // Plot main line chart
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

