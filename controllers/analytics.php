<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );
/**
 * This is the controller for the analytics
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

        $this->template->content->hello = 'Hello World!';

        $this->template->header->page_title .= Kohana::lang('ui_main.analytics').Kohana::config('settings.title_delimiter');
    }
} // End Main
