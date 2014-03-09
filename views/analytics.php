<script type="text/javascript"><?php echo "$chart"; ?></script>
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
                        </div>
                        <!-- end analytic-tabs block -->
		</div>
		<!-- end analytics block -->
	</div>
</div>
