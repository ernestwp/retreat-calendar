<?php

namespace Retreat_Calendar;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Calendar_App
 *
 * @package     Retreat_Calendar
 * @since       0.1
 *
 */
class Calendar_App extends Module {


	/**
	 * @var array Dates of the month
	 */
	public $dates_grid = [];

	/**
	 * @var array Days of the week
	 */
	public $days = [];

	/**
	 * @var array Months on the year
	 */
	public $months = [];

	/**
	 * Class constructor
	 *
	 * The construct is setup so that you can auto run code when the switch is active or run background code
	 * when the switch is in-active
	 *
	 * @since 0.1
	 */
	public function __construct() {
		parent::__construct();
	}

	/*
	 * Initialize actions, filters, and/or custom functions
	 *
	 * This function run after settings have been loading and dependencies have been checked
	 *
	 * Most, if not all, functions should be run after plugins have been loaded. This will give access to modify and/or
	 * override functions for any external plugin or theme. We can also check if a plugin or theme exists before
	 * executing any action, filters, and/or extending classes from it.
	 *
	 * @since 0.1
	 */
	function run() {
		add_shortcode( 'retreat_calendar', [ $this, 'retreat_calendar' ] );
		add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );
	}

	function retreat_calendar() {

		// Collect months of the year
		for ( $i = 1; $i <= 12; $i ++ ) {
			$timestamp                               = mktime( 0, 0, 0, date( $i ), 1 );
			$this->months[ date( 'n', $timestamp ) ] = date( 'M', $timestamp );
		}

		// Collect th days of the week
		for ( $i = 6; $i <= 12; $i ++ ) {
			$timestamp        = mktime( 0, 0, 0, 0, $i );
			$this->days[ $i ] = date( 'D', $timestamp );
		}

		// The first day of August 2025
		$month = mktime( 0, 0, 0, 8, 1, 2025 );

		// numeric representation of the day of the week of the first day of the month
		$start_number_day = date( 'N', $month );

		// The amount of days in the month
		$days_in_month = date( 't', $month );

		// create empty placeholder dates
		for ( $i = 1; $i <= $start_number_day; $i ++ ) {
			$this->dates_grid[] = (object)[
				'num' => null,
				'date' => null
			];
		}

		// create actual dates
		for ( $i = 1; $i <= $days_in_month; $i ++ ) {
			$this->dates_grid[] = (object)[
				'num' => $i,
				'date' => date("Y-m-d", mktime(0, 0, 0, 8, $i, 2025) )
			];
		}

		ob_start();
		include "templates/calendar.php";

		return ob_get_clean();
	}

	/**
	 * @param $hook
	 */
	function scripts( $hook ) {

		// Setup group management JS with localized WP Rest API variables @see rest-api-end-points.php
		wp_register_script( Utilities::get_prefix() . '-frontend-js', Utilities::get_frontend_js(), array(), Utilities::get_version(), true );

		// API data
//		$api_setup = array(
//			'root'          => esc_url_raw( rest_url() . $this->root_path ),
//			'nonce'         => \wp_create_nonce( 'wp_rest' ),
//			'plugin_prefix' => Utilities::get_prefix()
//		);

		//wp_localize_script( Utilities::get_prefix() . '-admin-settings', 'SettingsApiSetup', $api_setup );

		wp_enqueue_script( Utilities::get_prefix() . '-frontend-js' );

		wp_enqueue_style( Utilities::get_prefix() . '-frontend-css', Utilities::get_frontend_css(), array(), Utilities::get_version() );

	}

	/*
	 * Checks if the class is dependant on another variable, function, plugin and/or theme
	 *
	 * If the dependency does not exists then the on/off switch on the module is replace with a message.
	 *
	 * @since 0.1
	 *
	 * @return boolean || string
	 */
	function dependants_exist() {
		// Return true dependency is available
		$this->dependants_exist = true;

	}

	/**
	 * Detailed description of module
	 *
	 * This information is only loaded in the admin settings page to create a module which includes an on/off switch
	 * and settings modal pop up that populates module options in the WP DB. The details are retrieve by creating a
	 * reflection class(http://php.net/manual/en/class.reflectionclass.php). The class does not need to be initialized
	 * to get the details.
	 * @return array $class_details
	 * @since 0.1
	 *
	 * @see   Retreat_Calendar/AdminMenu::get_class_details()
	 *
	 */
	function set_module_details() {
		/*
		 * Settings define the inputs that are added to the settings modal pop.
		 *
		 * @type object
		 */
		$this->settings = (object) [];
	}

	public function set_active_or_not() {
		$this->is_active = true;
	}
}


