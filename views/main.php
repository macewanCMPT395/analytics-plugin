<style>

#analytics {
    overflow: hidden;
}

#chart-filter-box {
    float: right;
    clear: right;
}

.filter-box {
    width: 180px;
    height: 200px;
    overflow: scroll;
}
</style>
<script type="text/javascript"><?php echo $chart; ?></script> 
<div id="content">
    <div id="analytics" >
        <div id="chart" >
            <div id="chart-window" style="width:100%;"></div>
            <div id="chart-overview" style="width:100%;"></div>
            <div id="chart-date-slider"></div>
        </div> <!-- end chart -->

        <form id="filter">
            <div id="chart-filter-box">
                <h2>Chart Type</h2>
                <div id="chart-type-filter-box" class="filter-box">
                    <?php echo analytics_helper::select_chart_type("radio", "chartType", "filter-element"); ?>
                </div>

                <h2>Daily or Cumulative incidents</h2>
                <div id="chart-cumulative-filter-box" class="filter-box">
                    <?php echo analytics_helper::select_cumulative("radio", "cumulative", "filter-element"); ?>
                </div>

                <h2>Category</h2>
                <div id="chart-category-filter-box" class="filter-box">
                    <?php echo analytics_helper::select_category("checkbox", "categoryId", "filter-element"); ?>
                </div>

                <h2>Country</h2>
                <div id="chart-country-filter-box" class="filter-box">
                    <?php echo analytics_helper::select_country("checkbox", "countryId", "filter-element"); ?>
                </div>

                <h2>Keyword</h2>
                <div id="chart-custom-filter-box" class="filter-box">
                    <label> 
                       <input type="text" name="keyword" />
                        Custom Filter
                    </label>
                </div>
            </div> <!-- end chart-filter-box -->
            <input type="submit" value="submit" />
        </form>
    </div> <!-- end analytics -->
</div> <!-- end content -->
