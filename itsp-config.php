<?php
/**
 * IntellaSphere Contain Admin Connect Required files 
 *
 * Widget related functions and widget registration.
 *
 * @author    Intellasphere
 * @category 	Core
 * @package 	IntellaShphere/Functions
 */

$results = Itsp_Admin_Setting::is_connect();
//if ($results) {
    require_once( ITSP_INTEGRATION__PLUGIN_DIR . 'class.itsp-widgets-gutenberg.php');
    require_once( ITSP_INTEGRATION__PLUGIN_DIR . 'addons-integration.php');
    require_once( ITSP_INTEGRATION__PLUGIN_DIR . 'class.itsp-gutenberg.php');
//}
