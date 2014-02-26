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
$( document ).ready( function (){

    // get data
    var data = <?php echo "$this->pieChartData"; ?>;

    // Plot Pie Chart
    $.plot('#myChart', data, {
        series: {
            pie: {
                show: true
            }
        },
        legend: {
            show: true
        }
    });
});
