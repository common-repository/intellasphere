<?php

/**
 * IntellaSphere BackEnd Admin Menus
 *
 *
 * @author    Intellasphere
 * @category 	Core
 * @package 	IntellaShphere
 */


if (!class_exists("Itsp_Admin_Setting")) {

    class Itsp_Admin_Setting {

        /**
         * Initializing the Init Hook
         */
        public static function is_init() {
            self::is_init_hooks();
        }

        /**
         * Save the Key Hooks
         */
        public static function is_init_hooks() {
            add_action('admin_menu', array('Itsp_Admin_Setting', 'is_integration'), 10, 2);
            add_action('admin_post_is_save_options', array('Itsp_Admin_Setting', 'is_process_is_save_options'), 10, 1);
        }

        /**
         * Backend Menus Integration
         */
        public static function is_integration() {
            add_menu_page('Intellasphere', __(ITSP_NAME, 'intellasphere'), 'manage_options', 'is-integration', array('Itsp_Admin_Setting', 'is_render_dashboard_page'), ITSP_ICON);
            $valid = Itsp_Admin_Setting::is_connect();
           // if ($valid) {
                add_submenu_page('is-integration', 'Generate Short Code', __("Banners & Popups", 'intellasphere'), 'manage_options', 'is-banner-page', array('Itsp_Admin_Setting', 'is_banner_display'));
                add_submenu_page('is-integration', 'Addons', __('Addons', 'intellasphere'), 'manage_options', 'is-addons-page', array('Itsp_Admin_Setting', 'is_addons_display'));
            //}
               add_submenu_page('is-integration', 'Settings', __('Settings', 'intellasphere'), 'manage_options', 'is-settings-page', array('Itsp_Admin_Setting', 'is_settings_display'));
            // add_submenu_page('is-integration', 'Banner',  __('Banner' ,'intellasphere'), 'manage_options', 'is-Banner-pagesssss', array('Itsp_Admin_Setting', 'is_banner_display'));
        }

        /**
         * Backend Form HTml
         */
        public static function is_shortcode_display() {
            include ITSP_INTEGRATION__PLUGIN_DIR . 'admin/views/itsp_shortcode_generator.php';
        }

        /**
         * Backend Addons Form HTml
         */
        public static function is_addons_display() {
            include ITSP_INTEGRATION__PLUGIN_DIR . 'admin/views/itsp_addons_generator.php';
        }

        /**
         * settings
         */
        public static function is_settings_display() {
            include ITSP_INTEGRATION__PLUGIN_DIR . 'admin/views/itsp_settings_generator.php';
        }


        /**
         * Backend Addons sForm HTml
         */
        public static function is_banner_display() {
            include ITSP_INTEGRATION__PLUGIN_DIR . 'admin/views/itsp_shortcode_generator.php';
        }

        /**
         * Key Configuration Backend HTml
         */
        public static function is_render_dashboard_page() {
		    if (isset($_GET['type']) && $_GET['type'] == 'clear' && isset($_GET['page']) && $_GET['page']== 'is-integration') {
                self::is_clear();
                print('<script>window.location.href="admin.php?page=is-integration"</script>');
                exit;
            }
            //  $connectid = get_option('is_connect_email');
            $results = Itsp_Admin_Setting::is_connect();
            if ($results) {
                include(ITSP_INTEGRATION__PLUGIN_DIR . 'admin/views/itsp_company_form.php');
            } else {
                include(ITSP_INTEGRATION__PLUGIN_DIR . 'admin/views/itsp-landing.php');
            }
        }

        /**
         * is Connected 
         * @return boolean
         */
        public static function is_connect() {
            $connectid = get_option('is_connect_email');
            if (isset($connectid) && $connectid != '') {
                $xauthtoken = !is_null(get_option('x-auth-token')) ? get_option('x-auth-token') : '';
                $args = array(
                    'headers' => array(
                        'Content-Type' => 'application/json',
                        'x-auth-token' => $xauthtoken
                ));
                $response = wp_remote_get(ITSP_API_REST . 'rest/customers?username=' . urlencode($connectid) . '', $args);
                if (!is_wp_error($response) && is_array($response)) {
                    $user_company = json_decode($response['body']); // use the contentvar
                    $comany_list = (array) $user_company;
                    return $comany_list;
                } else {
                    return false;
                }
            }
            return false;
        }

        /**
         * Save the Key 
         */
        public static function is_process_is_save_options() {
            if (!current_user_can('manage_options')) {
                wp_die('You are not allowed to be on this page.');
            }
            // Check that nonce field
            check_admin_referer('is_op_verify');
            $options = !is_null(get_option('is_op_array')) ? get_option('is_op_array') : '';
            if (isset($_POST['customerid'])) {
                $options['customerid'] = sanitize_text_field($_POST['customerid']);
            }
            update_option('is_op_array', $options);

            wp_redirect(admin_url('/admin.php?page=is-integration', 'http'), 301);
            exit;
        }

        /**
         * Get Engage
         * @return array
         */
        public static function is_get_engaments() {
            $engagement_widgets = array(
                'FORM_CONTACTUS' => 'contactform',
                'COUPON' => 'offer',
                'POLL' => 'poll',
                'SURVEY' => 'survey',
                'FORM_FEEDBACK' => 'feedbackform',
                'NEWSLETTER_SUBSCRIPTION' => 'newslettersubscription',
                'FORM_REVIEW' => 'review',
                'PROMOTERLIST_SUBSCRIPTION' => 'promoterlistsubscription',
                'BANNER' => 'banner',
                "EVENT" => 'event'
            );
            return $engagement_widgets;
        }

        /**
         * Clear the Data
         */
        public static function is_clear() {
            delete_option('is_connect_email');
            delete_option('is_op_array');
        }

    }

}
add_action('init', array('Itsp_Admin_Setting', 'is_init'));
