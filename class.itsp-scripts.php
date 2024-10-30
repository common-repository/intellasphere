<?php

/**
 * IntellaSphere Style and Scripts
 *
 * Style & Scripts related functions and widget registration.
 *
 * @author    Intellasphere
 * @category 	Core
 * @package 	IntellaShphere
 */
if (!class_exists('Itsp_Style_Scripts')) {

    class Itsp_Style_Scripts {
        public function __construct() {
            $obj = new Itsp_Utility();
            add_action('wp_enqueue_scripts', array($obj, 'init_plugin'));
            add_action('enqueue_block_editor_assets', array($obj, 'init_plugin'));
            add_action('wp_ajax_process_reservation', array($obj, 'process_the_events'));
            add_action('wp_ajax_nopriv_process_reservation', array($obj, 'process_the_events'));
            add_action('wp_ajax_process_the_ajax', array($obj, 'process_the_ajax'));
            add_action('wp_ajax_nopriv_process_the_ajax', array($obj, 'process_the_ajax'));
            add_action('wp_print_scripts', array($obj, 'global_brankit_json'));
            add_action('wp_ajax_select_engage', array($obj, 'select_engage'));
            add_action('wp_ajax_get_calender_events', array($obj, 'get_calender_events'));
            add_action('wp_ajax_nopriv_get_calender_events', array($obj, 'get_calender_events'));
            add_action('admin_enqueue_scripts', array($obj, 'admin_script'));
            add_action('wp_ajax_process_to_connect', array($obj, 'process_to_connect'));
            add_action('wp_ajax_banner_table', array($obj, 'banner_table'));
        }

    }

}