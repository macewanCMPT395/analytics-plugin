<script type="text/javascript"><?php echo $chart; ?></script> 
<div id="content">
    <div id="analytics" >
        <div id="chart" >
            <div id="chart-window" style="width:100%;height:600px;"></div>
            <div id="chart-overview" style="width:100%;"></div>
            <div id="chart-date-slider"></div>
        </div> <!-- end chart -->

        <div id="chart-filter-box">
            <form name="filters">
                <?php echo analytics_helper::select_chart_type( "filters", "radio", "chartType", true ); ?>
                <?php echo analytics_helper::select_cumulative( "filters", "radio", "cumulative", true ); ?>
                <?php echo analytics_helper::select_category( "filters", "checkbox", "categoryId", true ); ?>
                <?php echo analytics_helper::select_country( "filters", "checkbox", "countryId", true ); ?>
                <input type="text" name="keyword" />
                <input type="submit" value="submit" />
            </form>
        </div> <!-- end chart-filter-box -->
    </div> <!-- end analytics -->
</div> <!-- end content -->
