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
                <div id="chart-type-filter-box" class="filter-box">
                    <h2>Chart Type</h2>
                    <?php echo analytics_helper::select_chart_type("radio", "chartType", "filter-element"); ?>
                </div>
                <div id="chart-cumulative-filter-box" class="filter-box">
                    <h2>Daily or Cumulative incidents</h2>
                    <?php echo analytics_helper::select_cumulative("radio", "cumulative", "filter-element"); ?>
                </div>
                <div id="chart-category-filter-box" class="filter-box">
                    <h2>Category</h2>
                    <?php echo analytics_helper::select_category("checkbox", "categoryId", "filter-element"); ?>
                </div>
                <div id="chart-country-filter-box" class="filter-box">
                    <h2>Country</h2>
                    <?php echo analytics_helper::select_country("checkbox", "countryId", "filter-element"); ?>
                </div>
                <div id="chart-custom-filter-box" class="filter-box">
                    <h2>Keyword</h2>
                    <label>Custom Filter
                        <input type="text" name="keyword" />
                    </label>
                </div>
                <input type="submit" value="submit" />
            </form>
        </div> <!-- end chart-filter-box -->
    </div> <!-- end analytics -->
</div> <!-- end content -->
