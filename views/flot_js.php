<?php 
/**
 * Include flotchart and D3 library
 */
echo html::script( array(
    'plugins/analytics-plugin/media/js/jquery.flot.min',
    'plugins/analytics-plugin/media/js/jquery.flot.pie.min',
    'plugins/analytics-plugin/media/js/jquery.flot.time.min',
    'plugins/analytics-plugin/media/js/jquery.flot.selection',
    'plugins/analytics-plugin/media/js/jquery.flot.axislabels',
    'plugins/analytics-plugin/media/js/d3.min',
    'plugins/analytics-plugin/media/js/d3.parcoords'
    ), FALSE); 
?>
