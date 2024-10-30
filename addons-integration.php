<?php

/**
 * @since 1.0.0
 * This files contains the addons of the Intellasphere Integration
 */

/**
 * Check Ninja form plugin is active or not
 */
$active_plugins = apply_filters('active_plugins', get_option('active_plugins'));
$ninja = get_option('is_ninja_addon');

if (in_array('ninja-forms/ninja-forms.php', $active_plugins) && isset($ninja) && $ninja == 'active') {
    add_filter('ninja_forms_register_actions', 'itsp_ninj_form_register_actions');
    if (!function_exists('itsp_ninj_form_register_actions')) {

        /**
         * @since 1.0.0
         * function to integrate
         * @param array $actions
         * @return \ITSP_Leadaction
         */
        function itsp_ninj_form_register_actions($actions) {
            require_once( ITSP_INTEGRATION__PLUGIN_DIR . 'addons/ninjaform.php');
            $actions['is_lead'] = new Itsp_Leadaction();
            return $actions;
        }

    }
}
/**
 * Check Gravity form is active or not
 */
$gravity = get_option('is_gravity_addon');
if (isset($gravity) && $gravity == 'active') {
    /**
     *  Hook to Initilize the gravity form 
     */
    add_action('gform_loaded', array('Itsp_Gravity_Addon', 'load'), 5);
    if (!class_exists("Itsp_Gravity_Addon")) {
        /**
         * @since 1.0.0
         * Include the Gravity Addon 
         */
        class Itsp_Gravity_Addon {
            public static function load() {
                if (method_exists('GFForms', 'include_addon_framework')) {
                    require_once( ITSP_INTEGRATION__PLUGIN_DIR . 'addons/gravityform.php');
                    GFAddOn::register('Itsp_Gfisaddon');
                }
            }
        }
    }
}
