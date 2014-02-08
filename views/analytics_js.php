<?php 
/**
 * Analytics scripting

 * PHP version 5
 * LICENSE: This source file is subject to LGPL license 
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author     Kundi Bora Team
 * @package    Ushahidi - http://source.ushahididev.com
 * @subpackage Analytics
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license    http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL) 
 */
 ?>

function get_piechart_data(){
    var url = "<?php echo url::site().'json/'; ?>";

    // get the chart data
    $.ajax({
        url: url,
        data: options,
        success: function(response){
        },
        dataType: "json"
    });
}

$( document ).ready( function (){
    // Get context
    var ctx = $('#myChart').get(0).getContext('2d');

    // get first returned node in collection
    var myNewChart = new Chart(ctx);

    var url = <?php echo '"'.url::site().'json/index'.'";'; ?>;
    alert( url );
    
    var data = [
        {
            value: 30,
            color: "#f38630"
        },
        {
            value: 50,
            color: "#e0e4cc"
        },
        {
            value: 100,
            color: "#69d2e7"
        }
    ]

    new Chart(ctx).Pie(data);
});


$( document ).ready( function() {

});
