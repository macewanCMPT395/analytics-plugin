<?php defined('SYSPATH') or die('No direct script access.');

class analytics {
	
	/**
	 * Registers the main event add method
	 */
	public function __construct()
	{	
		// Hook into routing
		Event::add('system.pre_controller', array($this, 'add'));
	}
	
	/**
	 * Adds all the events to the main Ushahidi application
	 */
	public function add()
        {
		Event::add('ushahidi_action.nav_main_top', array($this, 'navigation_tab'));
                Event::add('ushahidi_action.header_scripts', array($this, 'add_flotjs'));
	}

        /**
         * Create analytics navigation tab
         *
         * @param this_page show tabs on this page
         * @param dont show tabs
         */
        public function navigation_tab($this_page = FALSE, $dontshow = FALSE)
        {
            $menu_items = array();

            if( ! is_array($dontshow))
            {
                    // Set $dontshow as an array to prevent errors
                    $dontshow = array();
            }

            if( ! in_array('analytics',$dontshow))
            {
                    $menu_items[] = array( 
                            'page' => 'analytics',
                            'url' => url::site('analytics'),
                            'name' => Kohana::lang('ui_main.analytics')
                    );	
            }

            // create a new view
            $analytics_tab = View::factory( 'analytics_tab' );

            // Populate tabs
            $analytics_tab->menu_items = $menu_items;

            // render view
            $analytics_tab->render( TRUE );
        }

        /**
         * Add flot charts to ushahidi
         */
        public function add_flotjs()
        {
            $js = View::factory('flot_js');
            $js->render(TRUE);
        }
}

new analytics;
