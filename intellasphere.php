<?php

/**
 * Plugin Name: Intellasphere
 * Plugin URI: https://www.intellasphere.com/
 * Description: Intellasphere toolkit that helps you Integrate Intellasphere Beautifully.
 * Version: 1.1.1
 * Author: Intellasphere
 * Requires at least: 4.9
 * Text Domain: intellasphere
 * Tested up to: 6.3
 * Requires PHP: 5.2.4
 * License: GPLv2 or later
 * @package Intellasphere
 * @category Core
 * @author Intellasphere
 */

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}


/**
 * Holds the version of Intellasphere.
 *
 * @since 1.0.0
 *
 * @var string Intellasphere Version.
 */

if ( ! defined( 'ITSP_INTELLASPHERE_VERSION' ) ) {
      define('ITSP_INTELLASPHERE_VERSION', '1.0.6');
}


/**
 * Holds the filesystem directory path (with trailing slash) for Intellasphere.
 *
 * @since 1.0.0
 *
 * @var string Plugin Root File
 */

if ( ! defined( 'ITSP_INTEGRATION__PLUGIN_DIR' ) ) {
   define('ITSP_INTEGRATION__PLUGIN_DIR', plugin_dir_path(__FILE__));
}


/**
 * Holds the SSL for Intellasphere.
 *
 * @since 1.0.0
 *
 * @var string Plugin SSL
 */
if ( ! defined( 'ITSP_HTTPS' ) ) {
      define('ITSP_HTTPS', is_ssl() ? "https://" : "https://");
}



/**
 * Holds the filesystem directory path (with trailing slash) for Intellasphere.
 *
 * @since 1.0.0
 *
 * @var string Plugin folder URL
 */

if ( ! defined( 'ITSP_INTEGRATION__PLUGIN_URL' ) ) {
    define('ITSP_INTEGRATION__PLUGIN_URL', untrailingslashit(plugins_url('/', __FILE__)));
}


/**
 * Holds the Millisecond for Intellasphere.
 *
 * @since 1.0.0
 *
 * @var string Plugin Millisecond
 */
if ( ! defined( 'ITSP_MILLISECONDS' ) ) {
    define('ITSP_MILLISECONDS', round(microtime(true) * 1000));
}



/**
 * Hold the Environment Variable API URL
 */
if(getenv('APP_NAME')){
     $env_app_name= getenv('APP_NAME');
}


/**
 * Hold the Environment Variable API URL
 */
if(getenv('APP_ICON')){
     $env_app_icon= getenv('APP_ICON');
}


/**
 * Hold the Environment Variable API Logo
 */
if(getenv('APP_LOGO')){
     $env_app_logo= getenv('APP_LOGO');
}


/**
 * Hold the Environment Variable APP URL
 */

if(getenv('APP_URL')){
     $env_app_url= getenv('APP_URL');
}

/**
 * Hold the Environment Variable API URL
 */
if(getenv('API_URL')){
     $env_api_url= getenv('API_URL');
}

/**
 * Hold the Environment Header COlor
 */
if(getenv('APP_HEADER_COLOR')){
     $env_app_header_color= getenv('APP_HEADER_COLOR');
}

/**
 * Hold the Environment PRIMARY COlor
 */

if(getenv('APP_PRIMARY_COLOR')){
     $env_app_primary_color= getenv('APP_PRIMARY_COLOR');
}

/**
 * Hold the Environment Secondary COlor
 */

if(getenv('APP_SECONDARY_COLOR')){
     $env_app_secondary_color= getenv('APP_SECONDARY_COLOR');
}


/**
 * App NAME
 */
if ( ! defined( 'ITSP_NAME' ) ) {
     if(isset($env_app_name)){
         define('ITSP_NAME', $env_app_name);
     }else{
         define('ITSP_NAME', 'Intellasphere');
     }
}

/**
 * App ICON
 */
if ( ! defined( 'ITSP_ICON' ) ) {
     if(isset($env_app_icon)){
         define('ITSP_ICON', $env_app_icon);
     }else{
         define('ITSP_ICON', ITSP_INTEGRATION__PLUGIN_URL . '/assets/images/icon.png');
     }
}

/**
 * App logo
 */
if ( ! defined( 'ITSP_LOGO' ) ) {
     if(isset($env_app_logo)){
         define('ITSP_LOGO', $env_app_logo);
     }else{
         define('ITSP_LOGO', ITSP_INTEGRATION__PLUGIN_URL.'/admin/images/logo.png');
     }
}

/**
 * App Header COlor
 */
if ( ! defined( 'ITSP_HEADER_COLOR' ) ) {
     if(isset($env_app_header_color)){
         define('ITSP_HEADER_COLOR', $env_app_header_color);
     }else{
         define('ITSP_HEADER_COLOR', '#1c171e');
     }
}

/**
 * PRIMARY COLOR
 */

if ( ! defined( 'ITSP_PRIMARY_COLOR' ) ) {
     if(isset($env_app_primary_color)){
         define('ITSP_PRIMARY_COLOR', $env_app_primary_color);
     }else{
         define('ITSP_PRIMARY_COLOR', '#FFF');
     }
}

/**
 * Secondary COLOR
 */

if ( ! defined( 'ITSP_SECONDARY_COLOR' ) ) {
     if(isset($env_app_secondary_color)){
         define('ITSP_SECONDARY_COLOR', $env_app_secondary_color);
     }else{
         define('ITSP_SECONDARY_COLOR', '#0090ef');
     }
}


//APP_HEADER_COLOR
//APP_HEADER_COLOR
/**
 * Holds the Rest api of Intellashpere.
 *
 * @since 1.0.0
 *
 * @var Rest url Intellasphere
 */
if ( ! defined( 'ITSP_INTELLASPHERE' ) ) {
     if(isset($env_app_url)){
         define('ITSP_INTELLASPHERE', ITSP_HTTPS . $env_app_url);
     }elseif(isset($is_app_url)){
        define('ITSP_INTELLASPHERE', $is_app_url);
     }else{
         define('ITSP_INTELLASPHERE', ITSP_HTTPS . 'app-dev-int.equitybrix.net');
     }
}


/**
 * Holds the Rest api of Intellashpere.
 *
 * @since 1.0.8
 *
 * @var Rest url Intellasphere
 */
if(isset(get_option('is_app_settings_url')[0])){
    $is_app_url = get_option('is_app_settings_url');
}


/**
 * Holds the Rest api of Intellashpere.
 *
 * @since 1.0.8
 *
 * @var Rest url Intellasphere
 */
if(isset(get_option('is_api_settings_url')[0])){
    $is_api_url = get_option('is_api_settings_url');
}


/**
 * Holds the Embeded Script.
 *
 * @since 1.0.0
 *
 * @var URL
 */
if ( ! defined( 'ITSP_INTJAVA' ) ) {
    
     if(isset($env_app_url)){
         define('ITSP_INTJAVA', ITSP_HTTPS . $env_app_url.'/ui/scripts/embed.js');
     }elseif(isset($is_app_url)){
        define('ITSP_INTJAVA', $is_app_url . '/ui/scripts/embed.js');
     }else{
        define('ITSP_INTJAVA', ITSP_HTTPS . 'app-dev-int.equitybrix.net/ui/scripts/embed.js');
     }
}



/**
 * Holds the Rest API.
 *
 * @since 1.0.0
 *
 * @var URL
 */

if ( ! defined( 'ITSP_API_REST' ) ) {
    
      if(isset($env_api_url)){
         define('ITSP_API_REST', ITSP_HTTPS . $env_api_url.'/');
      }elseif(isset($is_api_url)){
        define('ITSP_API_REST', $is_api_url . '/');
      }else{
         define('ITSP_API_REST', ITSP_HTTPS . 'api-dev-int.equitybrix.net/');
      }
}



/**
 * Holds the REST API Activiites
 *
 * @since 1.0.0
 *
 * @var URL
 */
if ( ! defined( 'ITSP_API_DISPLAY' ) ) {
     if(isset($env_api_url)){
         define('ITSP_API_DISPLAY', ITSP_HTTPS . $env_api_url.'/rest/app/activities/');
      }elseif(isset($is_api_url)){
         define('ITSP_API_DISPLAY', $is_api_url . '/rest/app/activities/');
      }else{
         define('ITSP_API_DISPLAY', ITSP_HTTPS . 'api-dev-int.equitybrix.net/rest/app/activities/');
      }
}



/*
 *----------------------------------------------------------------------------
 * Intellasphere modules & includes
 *----------------------------------------------------------------------------
 */
require_once( ITSP_INTEGRATION__PLUGIN_DIR . 'functions.php');
require_once( ITSP_INTEGRATION__PLUGIN_DIR . 'admin/class.itsp.settings.php');
require_once( ITSP_INTEGRATION__PLUGIN_DIR . 'class.itsp-utility.php');
require_once( ITSP_INTEGRATION__PLUGIN_DIR . 'class.itsp-banner.php');
require_once( ITSP_INTEGRATION__PLUGIN_DIR . 'itsp-config.php');

/*
 *----------------------------------------------------------------------------
 * Create Table Banners
 *----------------------------------------------------------------------------
 */

register_activation_hook(__file__, 'itsp_installer');



function move_script_header(){
    	$whitelist = array(
			'globalajax_script',
                        'wp-color-picker',
                        'iris'
		);
		foreach ( $whitelist as $key => $handle ) {
			wp_enqueue_script( $handle );
		}
}

add_action( 'fl_builder_ui_enqueue_scripts', 'move_script_header' );