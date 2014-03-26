//<script type='text/javascript'>
    /*
     * 
     * Modifyed example code from:  http://bl.ocks.org/jasondavies/1341281
     * Adapted to read and display Ushahidi data.
     * 
     * Addition comments added for understanding.
     */

    $(document).ready(function() {

        var insert_tag = "#d3PCoords";
        var json_url = "<?php echo url::base( TRUE ) . 'analytics_json/d3_para_coord_json'; ?>";

        var m = [30, 10, 10, 10],
                w = 800 - m[1] - m[3],
                h = 600 - m[0] - m[2];

        var x = d3.scale.ordinal().rangePoints([0, w], 1), // x scale placement of y-bars
                y = {}, // Array of domain y-scale objects. Order by keys from json d3data attributes.
                dragging = {};  // array to hold temp x locations of y[] element domains.

        var line = d3.svg.line(), // tool to write SVG line scripts.
                axis = d3.svg.axis().orient("left"), // axis object, binds to what called it.
                background,
                foreground;

        var svg;

        var d3data;

        d3.json(json_url, function(json) {     // read the json at this relative URL.
            d3data = json;
            // Will extract the data into d3data.
            // Extract the list of dimensions and create a scale for each.
            x.domain(dimensions = d3.keys(d3data[0]).filter(function(d) {
                return true && (y[d] = d3.scale.linear()
                        .domain(d3.extent(d3data, function(p) {
                            return p[d];
                        }))
                        .range([h, 0]));
            }));

            svg = d3.select(insert_tag).append("svg:svg")   // Append the SVG draw pane to the
                    .attr("width", w + m[1] + m[3])             // selected div tag.
                    .attr("height", h + m[0] + m[2])
                    .append("svg:g")
                    .attr("transform", "translate(" + m[3] + "," + m[0] + ")");

            //console.debug(d3data);
            //console.log(y['Longitude'].domain());

            // Add grey background lines for context.
            background = svg.append("svg:g")
                    .attr("class", "background")
                    .selectAll("path")
                    .data(d3data)
                    .enter().append("svg:path")
                    .attr("d", path);

            // Add blue foreground lines for focus.
            foreground = svg.append("svg:g")
                    .attr("class", "foreground")
                    .selectAll("path")
                    .data(d3data)
                    .enter().append("svg:path")
                    .attr("d", path);

            // Add a group element for each dimension.
            var g = svg.selectAll(".dimension")
                    .data(dimensions)
                    .enter().append("svg:g")
                    .attr("class", "dimension")
                    .attr("transform", function(d) {
                        return "translate(" + x(d) + ")";
                    })
                    .call(d3.behavior.drag()
                            .on("dragstart", function(d) {
                                dragging[d] = this.__origin__ = x(d);
                                background.attr("visibility", "hidden");
                            })
                            .on("drag", function(d) {
                                dragging[d] = Math.min(w, Math.max(0, this.__origin__ += d3.event.dx));
                                foreground.attr("d", path);
                                dimensions.sort(function(a, b) {
                                    return position(a) - position(b);
                                });
                                x.domain(dimensions);
                                g.attr("transform", function(d) {
                                    return "translate(" + position(d) + ")";
                                })
                            })
                            .on("dragend", function(d) {
                                delete this.__origin__;
                                delete dragging[d];
                                transition(d3.select(this)).attr("transform", "translate(" + x(d) + ")");
                                transition(foreground)
                                        .attr("d", path);
                                background
                                        .attr("d", path)
                                        .transition()
                                        .delay(500)
                                        .duration(0)
                                        .attr("visibility", null);
                            }));

            // Add an axis and title.
            g.append("svg:g")
                    .attr("class", "axis")
                    .each(function(d) {
                        d3.select(this).call(axis.scale(y[d]));
                    })
                    .append("svg:text")
                    .attr("text-anchor", "middle")
                    .attr("y", -9)
                    .text(String);

            // Add and store a brush for each axis.
            g.append("svg:g")
                    .attr("class", "brush")
                    .each(function(d) {
                        d3.select(this).call(y[d].brush = d3.svg.brush().y(y[d]).on("brush", brush));
                    })
                    .selectAll("rect")
                    .attr("x", -8)
                    .attr("width", 16);
        });

        // ensures y domains set onto a safe x position.
        function position(d) {
            var v = dragging[d];
            return v == null ? x(d) : v;
        }

        // inline function for these calls.
        function transition(g) {
            return g.transition().duration(500);
        }

        // Returns the path for a given data point.
        function path(d) {
            return line(dimensions.map(function(p) {
                return [position(p), y[p](d[p])];
            }));
        }

        // Handles a brush event, toggling the display of foreground lines.
        // observed it can only work in numbers bc of the compares.
        function brush() {
            var actives = dimensions.filter(function(p) {
                return !y[p].brush.empty();
            }),
                    extents = actives.map(function(p) {
                        return y[p].brush.extent();
                    });
            foreground.style("display", function(d) {
                return actives.every(function(p, i) {
                    return extents[i][0] <= d[p] && d[p] <= extents[i][1];
                }) ? null : "none";
            });
        }
    });
    //</script>