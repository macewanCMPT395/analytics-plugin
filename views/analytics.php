<script type="text/javascript"><?php echo "$d3chart"; ?></script>
<script type="text/javascript"><?php echo $chart; ?></script> 
<?php echo html::stylesheet( 'plugins/analytics/media/css/d3.parcoords.css', 'screen', FALSE ); ?>
<?php echo html::stylesheet( 'plugins/analytics/media/css/analytics', 'screen', FALSE ); ?>
<div id="content">
    <div class="content-bg">
	<div class="big-block">
	    <h1><?php echo Kohana::lang( 'ui_main.analytics' ); ?></h1>

	    <div id="analytic-tabs">
		<ul>
		    <li class="tab"><a id="analytics-tab-0" class="analytics-tab" href="#filter-view">Charts</a></li>
		    <li class="tab"><a id="analytics-tab-1" class="analytics-tab" href="#parellel-coords">Parallel Coordinates</a></li>
		</ul>

		<div id="filter-view">
                    <?php echo $filter_chart; ?>
		</div>
                <!-- end filter view -->

		<div id="parellel-coords">
		    <div id="d3space" ><b> Drag and shift axes. Select region by dragging down axis. Clear region by clicking nonselected.</b><br />
                        <b>Please be patient while the view loads.</b>
			<div id="d3PCoords" class="parcoords"></div>
		    </div>
		</div>
		<!-- end D3-incidents block -->
	    </div>
	    <!-- end analytic-tabs block -->
	</div>
    </div>
</div>
