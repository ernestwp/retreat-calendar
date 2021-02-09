<?php
/**
 * Plugin Name
 *
 * @package           Retreat_Calendar
 * @author            
 * @copyright         2022 
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Retreat Calendar
 * Plugin URI:        
 * Description:       
 * Version:           0.1
 * Requires at least: 5.5
 * Requires PHP:      7.0
 * Author:            
 * Author URI:        
 * Text Domain:       retreat-calendar
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */

/*
 * Main plugin file --
 */

namespace Retreat_Calendar;

// If this file is called directly, abort.
defined( 'ABSPATH' ) || exit;

/**
 * This class initiates the plugin load sequence and sets general plugin variables
 *
 * @package Retreat_Calendar
 */
class Initialize_Plugin {

	/**
	 * The plugin name
	 *
	 * @since    0.1
	 * @access   public
	 * @var      string
	 */
	const PLUGIN_NAME = 'Retreat Calendar';

	/**
	 * The plugin name acronym
	 *
	 * @since    0.1
	 * @access   public
	 * @var      string
	 */
	const PLUGIN_PREFIX = 'Retr';

	/**
	 * Min PHP Version
	 *
	 * @since    0.1
	 * @access   public
	 * @var      string
	 */
	const MIN_PHP_VERSION = '7.0';

	/**
	 * The plugin version number
	 *
	 * @since    0.1
	 * @access   public
	 * @var      string
	 */
	const PLUGIN_VERSION = '0.1';

	/**
	 * The full path and filename
	 *
	 * @since    0.1
	 * @access   public
	 * @var      string
	 */
	const MAIN_FILE = __FILE__;

	/**
	 * The instance of the class
	 *
	 * @since    0.1
	 * @access   private
	 * @var      Object
	 */
	private static $instance = null;

	/**
	 * Creates singleton instance of class
	 *
	 * Singleton is needed here the prevent the multiple class initializations
	 *
	 * @since 0.1
	 * @return Initialize_Plugin $instance The Initialize_Plugin Class
	 *
	 */
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * class constructor
	 */
	private function __construct() {

		// Load Utilities
		$this->initialize_utilities();

		// Load Configuration
		$this->initialize_config();

		// Load the plugin files
		$this->boot_plugin();

	}

	/**
	 * Initialize Static singleton class that has shared function and variables that can be used anywhere in WP
	 *
	 * @since 0.1
	 */
	private function initialize_utilities() {

		include_once( dirname( __FILE__ ) . '/src/utilities/utilities.php' );
		Utilities::set_date_time_format();

	}

	/**
	 * Initialize Static singleton class that configures all constants, utilities variables and handles activation/deactivation
	 *
	 * @since 0.1
	 */
	private function initialize_config() {

		include_once( dirname( __FILE__ ) . '/src/utilities/class-config.php' );

		$config_instance = Config::get_instance();

		$config_instance->configure_plugin_before_boot(
			self::PLUGIN_NAME,
			self::PLUGIN_PREFIX,
			self::PLUGIN_VERSION,
			self::MAIN_FILE
		);

	}

	/**
	 * Initialize Static singleton class auto loads all the files needed for the plugin to work
	 *
	 * @since 0.1
	 */
	private function boot_plugin() {

		// Only include Module_interface, do not initialize is ... interfaces cannot be initialized
		add_filter( 'Skip_class_initialization', array( $this, 'add_skipped_classes' ), 10, 1 );

		include_once( dirname( __FILE__ ) . '/src/utilities/class-autoloader.php' );
		Autoloader::get_instance();

		do_action( Utilities::get_prefix() . '_plugin_loaded' );

	}

	/**
	 * Add Classes that need to be included automatically but not initialized
	 *
	 * @param array $skipped_classes Collection of classes that are being skipped over for initialization (new Class)
	 *
	 * @return array
	 */
	public function add_skipped_classes( $skipped_classes ) {
		return $skipped_classes;
	}
}

// Let's run it
Initialize_Plugin::get_instance();