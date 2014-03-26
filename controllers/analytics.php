<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * Analytics Controller
 *
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

class Analytics_Controller extends Main_Controller {
    function __construct()
    {
            parent::__construct();
    }

    public function index()
    {
        $this->template->header->this_page = 'analytics';
        $this->template->content = new View('analytics');

        $this->template->header->page_title .= Kohana::lang('ui_main.analytics').Kohana::config('settings.title_delimiter');

        $this->template->content->chart = new View( 'analytics_js' );
        $this->template->content->d3chart = new View( 'd3_js' );
    }
} // End Main
