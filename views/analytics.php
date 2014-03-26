<script type="text/javascript"><?php echo "$chart"; ?></script>
<script type="text/javascript"><?php echo "$d3chart"; ?></script>
<!-- script style css here-->
<style type="text/css">
svg {
 font: 10px sans-serif;
}

.background path {
 fill: none;
 stroke: #ccc;
 stroke-opacity: .4;
 shape-rendering: crispEdges;
}

.foreground path {
 fill: none;
 stroke: steelblue;
 stroke-opacity: .7;
}

.brush .extent {
 fill-opacity: .3;
 stroke: #fff;
 shape-rendering: crispEdges;
}

.axis line, .axis path {
 fill: none;
 stroke: #000;
 shape-rendering: crispEdges;
}

.axis text {
 text-shadow: 0 1px 0 #fff;
 cursor: move;
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
                                <li><a id="analytics-tab-3" class="analytics-tab" href="#parellel-coords">Parallel Coordinates</a></li>
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
                            
                            <div id="parellel-coords">
                                <div id="d3PCoords" style="width:800px;height:600px;"></div>
                            </div>
                            <!-- end D3-incidents block -->
                        </div>
                        <!-- end analytic-tabs block -->
		</div>
		<!-- end analytics block -->
	</div>
                </div>
