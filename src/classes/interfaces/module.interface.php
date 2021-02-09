<?php

namespace Retreat_Calendar;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Abstract Module Class
 *
 * Extended by each module using the same pattern.
 *
 * @version  0.1
 * @package  Retreat_Calendar
 */
interface Module_i {
	function set_module_details();

	function dependants_exist();

	function run();
}