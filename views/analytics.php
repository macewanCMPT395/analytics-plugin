<script type="text/javascript"><?php echo "$chart"; ?></script>
<script type="text/javascript"><?php echo "$d3chart"; ?></script>
<!-- script style css here-->
<?php echo html::stylesheet( 'plugins/analytics/media/css/d3.parcoords.css', 'screen', FALSE ); ?>
<style type='test/css'>
</style>
<div id="content">
    <div class="content-bg">
	<!-- start analytics block -->
	<div class="big-block">
	    <h1><?php echo Kohana::lang( 'ui_main.analytics' ); ?></h1>

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
		    <div id="d3space" ><b> Drag and shift axes. Select region by dragging down axis. Clear region by clicking nonselected.</b>
			<div id="d3PCoords" class="parcoords"></div>
		    </div>
		</div>
		<!-- end D3-incidents block -->
	    </div>
	    <!-- end analytic-tabs block -->
	</div>
	<!-- end analytics block -->
    </div>
</div>
