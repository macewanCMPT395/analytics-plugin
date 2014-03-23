<script type="text/javascript"><?php echo "$chart"; ?></script>
<style>
    #analytic-tabs ul {
        padding: 1em;
        clear: both;
    }

    #analytic-tabs li {
        list-style-type: none;
        background-color: #c2c2c2;
        float: left;
        margin: 1em;
        padding: 1em;
    }

    #analytic-tabs li:hover {
        background-color: #008cff;
    }

    #analytic-tabs a {
        color: #fff;
        font-weight: bold;
        text-decoration: none;
    }

    #analytic-tabs div {
        text-align: left;
        float: center;
        clear: both;
        width: 100%;
    }

    // d3 demo styles
    // default css style for node elements.
    .node {
        stroke-width: 1.5px;
    }


</style>

<div id="content">
    <div class="content-bg">
        <!-- start analytics block -->
        <div class="big-block">
            <h1><?php echo Kohana::lang('ui_main.analytics'); ?></h1>    


            <div id="analytic-tabs">
                <ul>
                    <li><a id="analytics-tab-0" class="analytics-tab" href="#all-time">All Time</a></li>
                    <li><a id="analytics-tab-1" class="analytics-tab" href="#daily-incidents">Daily Incidents</a></li>
                    <li><a id="analytics-tab-2" class="analytics-tab" href="#total-incidents">Total Incidents</a></li>
                    <li><a id="analytics-tab-3" class="analytics-tab" href="#d3tab">d3 test</a></li>
                </ul>

                <div id="all-time">
                    <div id="chart-all-time" style="width:100%;height:600px;"></div>
                </div>
                <!-- end total-pie-chart block -->

                <div id="daily-incidents">
                    <div id="chart-daily-incidents" style="width:800px;height:600px;"></div>
                </div>
                <!-- end total-incidents block -->

                <div id="total-incidents">
                    <div id="chart-total-incidents" style="width:800px;height:600px;"></div>
                </div>
                <!-- end daily-incidents block -->

                <div id="d3tab">
                    <h1><b>D3 TEST</b></h1>
                    <div id="d3view"></div>
                </div>
                <!-- end d3-test block -->
            </div>
        </div>
        <!-- end analytic-tabs block -->
    </div>
    <!-- end analytics block -->

</div>
<script>
    // D3 test script from a web example
    // http://bl.ocks.org/mbostock/1021953
    $(document).ready(function() {
        var width = 800,
                height = 600;

        // function sets a scale colors used. cat10 is reserved for
        // 10 colors pre-set.
        var fill = d3.scale.category10();

        // gravety nodes for random nodes to gravate too.
        var nodes = [],
                foci = [{x: 150, y: 150}, {x: 350, y: 250}, {x: 700, y: 400}];

        // setup render environment using SVG vectors.
        // place the render in div id 'd3view'
        var svg = d3.select("#d3view").append("svg")
                .attr("width", width)
                .attr("height", height);

        // setup force vector layout plugin.
        var force = d3.layout.force()
                .nodes(nodes)
                .links([])
                .gravity(0)
                .size([width, height])
                .on("tick", tick);

        // begind element space for circle vectors.
        var node = svg.selectAll("circle");

        // user defined function simulating each tick.
        function tick(e) {
            var k = .1 * e.alpha;

            // Push nodes toward their designated focus.
            nodes.forEach(function(o, i) {
                o.y += (foci[o.id].y - o.y) * k;
                o.x += (foci[o.id].x - o.x) * k;
            });

            node
                    .attr("cx", function(d) {
                        return d.x;
                    })
                    .attr("cy", function(d) {
                        return d.y;
                    });
        }

        // timer of updates
        setInterval(function() {
            // build data of node id: is color code.
            nodes.push({id: ~~(Math.random() * foci.length)});
            
            // start the simulation and append the data to D3 object
            force.start();
            node = node.data(nodes);

            // for every new datum, append circle div for svg vector.
            // Then set attributes and position of the vector.
            node.enter().append("circle")
                    .attr("class", "node")   // this ids the css style.
                    .attr("cx", function(d) {
                        return d.x;
                    })
                    .attr("cy", function(d) {
                        return d.y;
                    })
                    .attr("r", 8)
                    .style("fill", function(d) {
                        return fill(d.id);
                    })
                    .style("stroke", function(d) {
                        return d3.rgb(fill(d.id)).darker(2);
                    })
                    .call(force.drag);
                    // tell force object to simulate next step.
        }, 100);
    });
</script>
