<?php
/**
 * Analytics D3 demo script

 * PHP version 5
 * LICENSE: This source file is subject to LGPL license 
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author     Kundi Bora Team
 * @package    Ushahidi - http://source.ushahididev.com
 * @subpackage Analytics
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license    http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL) 
 *
 * Uses D3 js and a parallel Coordinints tool kit:
 * http://d3js.org/
 * http://syntagmatic.github.io/parallel-coordinates/
 * 
 *  Code adapted to read and display Ushahidi data.
 */
?>
//<script type='text/javascript'>
    var insert_tag = "#d3PCoords";
    var json_url = "<?php echo url::base( TRUE ) . 'analytics_json/d3_para_coord_json'; ?>";
    var pc;

    var w = 800,
	    h = 700;

    var dimensions = [ 'Country', 'Incident #', 'Category Trusted', 'Verified', 'Category', 'Date (Unix)' ];

    $(document).ready(function() {
	var color = d3.scale.category20();

	// load json file and create the chart
	d3.json(json_url, function( data ) {
	    var pc = d3.parcoords()(insert_tag)
		    .data(data)
		    .width(w)
		    .height(h)
		    .color(function( d ) {
			return color(d['Category']);
		    })
		    .alpha(0.7)
		    .margin({top: 30, left: 50, bottom: 15, right: 10})
		    .mode("queue")
		    .rate(32)
		    .render()
		    .composite("xor")
		    .ticks(10)
		    .tickPadding(5)
		    .dimensions(dimensions)
		    .updateAxes()
		    .brushable()
		    .reorderable()
		    ;

	    $(".label").show();

	    pc.svg.selectAll("text")
		    .style({"font-weight": "bold",
			"font": "11px sans-serif",
			"text-anchor": "end"})
	});
    });
    //</script>