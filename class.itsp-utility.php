<?php
/**
 * IntellaSphere Utility Functions
 *
 * Utility related functions 
 *
 * @author    Intellasphere
 * @category 	Core
 * @package 	IntellaShphere
 */

if (!class_exists('Itsp_Utility')) {

    class Itsp_Utility {
        
         /**
          * Initialize the actions
          */
         function __construct() {
            add_action('wp_enqueue_scripts', [$this, 'init_plugin']);
            add_action('enqueue_block_editor_assets', [$this, 'init_plugin']);
            add_action('admin_enqueue_scripts', [$this, 'admin_script']);
            add_action('wp_ajax_select_engage', [$this, 'select_engage']);
            add_action('wp_ajax_get_calender_events', [$this, 'get_calender_events']);
            add_action('wp_ajax_nopriv_get_calender_events', [$this, 'get_calender_events']);
            add_action('wp_ajax_process_to_connect', [$this, 'process_to_connect']);
            add_action('wp_ajax_banner_table', [$this, 'banner_table']);
        }

         /**
          * Loader js ,css & Third Party libraries
          */
        
        public function init_plugin() {
            wp_enqueue_script(
                    'intellasphere', ITSP_INTEGRATION__PLUGIN_URL . '/assets/js/intellasphere.js', array('jquery'), ITSP_INTELLASPHERE_VERSION
            );
            wp_enqueue_script(
                    'fontfamily', ITSP_INTEGRATION__PLUGIN_URL . '/assets/js/proxima.js', array('jquery'), ITSP_INTELLASPHERE_VERSION
            );
            wp_localize_script(
                'intellasphere', 'frontajax', array(
                'url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce("process_nonce"),
                    )
            );
            wp_enqueue_style(
                    'bootstrap', ITSP_INTEGRATION__PLUGIN_URL . '/assets/css/bootstrap.min.css', false, 'v5.1.3', 'all'
            );
            
            wp_enqueue_script(
                    'bootstrap', ITSP_INTEGRATION__PLUGIN_URL . '/assets/js/bootstrap.bundle.min.js', array('jquery'), 'v5.1.3', true
            );
            
            wp_enqueue_script('moment');
            
            wp_enqueue_script(
                    'embedded-js', ITSP_INTJAVA, array('jquery'), NULL, true
            );
            wp_enqueue_script(
                    'fullcalender', ITSP_INTEGRATION__PLUGIN_URL . '/assets/js/fullcalendar.min.js', array('jquery'), NULL, true
            );
            wp_enqueue_style(
                    'fullcalendar', ITSP_INTEGRATION__PLUGIN_URL . '/assets/css/fullcalendar.min.css', false, NULL, 'all'
            );

            wp_enqueue_style(
                    'font_awesome', ITSP_INTEGRATION__PLUGIN_URL . '/assets/css/font-awesome.min.css', false, NULL, 'all'
            );
            wp_enqueue_script(
                    'owl-carowsel', ITSP_INTEGRATION__PLUGIN_URL . '/assets/js/owl.carousel.min.js', array('jquery'), NULL, true
            );
            wp_enqueue_style(
                    'intcss', ITSP_INTEGRATION__PLUGIN_URL . '/assets/css/intellasphere.css', array(), ITSP_INTELLASPHERE_VERSION
            );
            wp_enqueue_style(
                    'owl-carowsel', ITSP_INTEGRATION__PLUGIN_URL . '/assets/css/owl.carousel.min.css', array(), true
            );
            wp_localize_script('intellasphere','brandkit_colorpalette ', $this->global_brankit_json());
            
            if (is_user_logged_in() && isset($_GET['fl_builder'])) {
                $this->load_default_scripts(1);
            }
        }
   
        /**
         * get the branding form Intellasphere
         * @return Brankit json
         */
        public function get_activities_by_since($start, $type) {
            $option_name = 'activities_by_since_events_' . $type . '';
            $results = get_option($option_name);

            if ($results == "" || !is_array($results) || !$results) {
                $new_value = $this->activities_by_since($start, $type);
                if (get_option($option_name) !== false) {
                    // The option already exists, so update it.
                    update_option($option_name, $new_value);
                } else {
                    // The option hasn't been created yet, so add it with $autoload set to 'no'.
                    $deprecated = null;
                    $autoload = 'no';
                    add_option($option_name, $new_value, $deprecated, $autoload);
                }
                return $new_value;
            }
            return $results;
        }

        /**
         * API to get  Engagement
         * @param type $start 
         * @param type $type
         * @return type
         */
        public  function activities_by_since($start, $type) {
            $results = array();
            $xauthtoken = !is_null(get_option('x-auth-token')) ? get_option('x-auth-token') : '';
            $args = array(
                'headers' => array(
                    'Content-Type' => 'application/json',
                    'x-auth-token' => $xauthtoken
            ));
            $key = Itsp_Utility::key();
            if (Itsp_Utility::key()) {
                $response = wp_remote_get(ITSP_API_REST . '/rest/app/activities/' . $key . '/?type=' . $type . '&channel=intellapages&status=All&streamType=PUBLISHED&since=' . $start . '', $args);
                if (is_array($response)) {
                    $header = $response['headers']; // array of http header lines
                    $results = json_decode($response['body']); // use the content
                }
            }
            return $results;
        }

        /**
         * Static Events Based on Last-updated date
         * @param type $start
         * @return type
         */
        public static function static_events($start) {
            return (new self())->get_activities_by_since($start, 'EVENT');
        }

        /**
         * Static Newsletter Based on Last-updated date
         * @param type $start
         * @return type
         */
        public static function static_newsletter($start) {
            return (new self())->get_activities_by_since($start, 'PAGE_NEWSLETTER');
        }

        /**
         * Static Offers Based on Lastupdated date
         * @param type $start
         * @return type
         */
        public static function static_offers($start) {
            return (new self())->get_activities_by_since($start, 'COUPON');
        }

        /*
         * Setting the Global Brankit at the top 
         */
        public function global_brankit_json() {
            global $pagenow;
            if ($pagenow == 'widgets.php' || (isset($_GET['page']) && $_GET['page'] == 'is-banner-page') || (isset($_GET['fl_builder'])) || $pagenow == "post.php" || $pagenow == "post-new.php" || $pagenow == "site-editor.php") {
                $uns_color_palettes = $this->brandkit();
                if (isset($uns_color_palettes['colorPalette'])) {
                    return (array) $uns_color_palettes['colorPalette'];
                }
            }
        }

        /**
         * get the branding form Intellasphere
         * @return Brankit json
         */
        public static function brandkit() {
            $option_name = 'get_brandkit_data';
            $results = get_option($option_name);
            if (!is_array($results) || !$results) {
                $results = Itsp_Utility::get_brandkit();
                if (get_option($option_name) !== false) {
                    // The option already exists, so update it.
                    update_option($option_name, $results);
                } else {
                    // The option hasn't been created yet, so add it with $autoload set to 'no'.
                    $deprecated = null;
                    $autoload = 'no';
                    add_option($option_name, $results, $deprecated, $autoload);
                }
            }
            return $results;
        }

        /**
         * get the branding form Intellasphere
         * @return Brankit json
         */
        public static function get_brandkit() {
            $results = array();
            if (Itsp_Utility::key()) {
                $xauthtoken = !is_null(get_option('x-auth-token')) ? get_option('x-auth-token') : '';
                $args = array(
                    'headers' => array(
                        'Content-Type' => 'application/json',
                        'x-auth-token' => $xauthtoken
                ));
                $get_response = wp_remote_get(ITSP_INTELLASPHERE . '/rest/customerinfo/' . Itsp_Utility::key(), $args);
                if (!is_wp_error($get_response)) {
                    if ($get_response['response']['code'] == 200) {
                        $json_response = json_decode($get_response['body']);
                        $results = (array) @$json_response->customer->brandkitInfo;
                       
                    }
                } else {
                    $results = "ServerError";
                }
            }
            return $results;
        }

        /**
         * get the branding form Intellasphere
         * @return Brankit json
         */
        public static function staticbrandkit($instance) {
            $results = array();
            $results['brandkit_colorpalette'] = array(
                'primaryColor' => (isset($instance['pricolor']) && !empty($instance['pricolor'])) ? ltrim($instance['pricolor'], "#") : 'fff',
                'secondaryColor' => (isset($instance['seccolor']) && !empty($instance['seccolor'])) ? ltrim($instance['seccolor'], "#") : 'fff',
                'primaryBackgroundColor' => (isset($instance['pribgcolor']) && !empty($instance['pribgcolor'])) ? ltrim($instance['pribgcolor'], "#") : 'fff',
                'secondaryBackgroundColor' => (isset($instance['secbgcolor']) && !empty($instance['secbgcolor'])) ? ltrim($instance['secbgcolor'], "#") : 'fff',
                'primaryTextColor' => (isset($instance['pritextcolor']) && !empty($instance['pritextcolor'])) ? ltrim($instance['pritextcolor'], "#") : 'fff',
                'secondaryTextColor' => (isset($instance['sectextcolor']) && !empty($instance['sectextcolor'])) ? ltrim($instance['sectextcolor'], "#") : 'fff',
                'textColor' =>  (isset($instance['txtcolor']) && !empty($instance['txtcolor'])) ? ltrim($instance['txtcolor'], "#") : '4a4c54',
                'buttonBackgroundColor' => (isset($instance['btbgcolor']) && !empty($instance['btbgcolor'])) ? ltrim($instance['btbgcolor'], "#") : '005e77',
                'buttonTextColor' => (isset($instance['bttxtcolor']) && !empty($instance['bttxtcolor'])) ? ltrim($instance['bttxtcolor'], "#") : 'fff' ,
                'secondaryButtonBackgroundColor' => (isset($instance['secbtbgcolor']) && !empty($instance['secbtbgcolor'])) ? ltrim($instance['secbtbgcolor'], "#") : 'fff' ,
                'warningColor' => (isset($instance['warningColor']) && !empty($instance['warningColor'])) ? ltrim($instance['warningColor'], "#") : 'fff',
                'borderColor' => (isset($instance['borcolor']) && !empty($instance['borcolor'])) ? ltrim($instance['borcolor'], "#") : 'fff',
                'secondaryButtonTextColor' => (isset($instance['secbttxtcolor']) && !empty($instance['secbttxtcolor'])) ? ltrim($instance['secbttxtcolor'], "#") : 'fff' ,
                'borderWidth' => (isset($instance['borwidth']) && !empty($instance['borwidth'])) ? $instance['borwidth'] : 'Thin' ,
            );
            $results['brandkit_fontinfo'] = array(
                'h1' => $instance['h1'],
                'h2' => $instance['h2'],
                'h3' => $instance['h3'],
                'h4' => $instance['h4'],
                'h5' => $instance['h5'],
                'h6' => $instance['h6'],
                'fontFamily' => $instance['fntfamily'],
                'fontWeight' => $instance['fntweight'],
                'lineHeight' => $instance['linheight'],
                'p' => $instance['ptag']
            );
            $results['brandkit_buttoninfo'] = array(
                'style' => $instance['btnstyle'],
                'size'  => $instance['btnsize'],
                'buttonCornerRadius' => $instance['btncorradius'],
            );///txtfdstyle
            $results['brandkit_forminfo'] = array(
                'borderStyle' => isset($instance['txtfdstyle'])?$instance['trxtfdshape']:"",
                'inputStyle' =>  isset($instance['trxtfdshape'])?$instance['trxtfdshape']:"",
                'inputCornerRadius' => isset($instance['trxtfdradius'])?$instance['trxtfdradius']:"4px",
            );

            return $results;
        }

        /**
         * Get User Reviews
         * @return array
         */
        public static function get_user_reviews($rating, $moderation) {
            $option_name = 'activities_reviews' . $rating . $moderation;
            $results = get_option($option_name);
            if (!is_array($results) || !$results || $results == '') {
                $new_value = Itsp_Utility::set_user_reviews($rating, $moderation);
                if (get_option($option_name) !== false) {
                    // The option already exists, so update it.
                    update_option($option_name, $new_value);
                } else {

                    // The option hasn't been created yet, so add it with $autoload set to 'no'.
                    $deprecated = null;
                    $autoload = 'no';
                    add_option($option_name, $new_value, $deprecated, $autoload);
                }
                return $new_value;
            }
            return $results;
        }

        /**
         * Set the Review Dynamic
         * @return boolean
         */
        public static function set_user_reviews($rating, $moderation) {
            $reviews = array();
            if (Itsp_Utility::key()) {
                $xauthtoken = !is_null(get_option('x-auth-token')) ? get_option('x-auth-token') : '';
                $args = array(
                    'headers' => array(
                        'Content-Type' => 'application/json',
                        'x-auth-token' => $xauthtoken
                ));
                $moderation_html = '';
                if ($moderation == 'on') {
                    $moderation_html = '&moderationStatus=Active';
                }
                $IS_GET_API = ITSP_API_REST . '/rest/reputation/search?customer=' . Itsp_Utility::key() . '&reputationStatus=AllMentions&rating=' . $rating . '&topic=RSPONSE_FOM_REVIEW&brand=all&location=all&key=&channel=all&isFlag=false&flag=start&since=&until=&skip=0' . $moderation_html . '';
                $response = wp_remote_get($IS_GET_API, $args);
                $data = json_decode($response['body']);
                if (!is_wp_error($response)) {
                    if ($response['response']['code'] == 200) {
                        $data = json_decode($response['body']);
                        foreach ($data as $values) {
                            $reviews[] = array(
                                "rating" => $values->rating[0]->value,
                                "message" => $values->content,
                                "displayName" => $values->fromProfile->displayName,
                                'createdTime' => $values->created->millisec
                            );
                        }

                        return $reviews;
                    }
                }
            }
            return false;
        }

        /**
         * Select Event Trigger based on engagement type
         */
        public function select_engage() {
            extract($_POST);
            if (isset($type)) {
                $response = (new self())->get_engage($type);
                echo json_encode(array("results" => $response));
                exit();
            }
        }

        /**
         * get Engagement
         * @param type $type
         */
        public static function get_engage($type) {
            $response = (new self())->full_engament($type);
            $inside_array = array();
            if (!is_wp_error($response)) {
                if ($response['response']['code'] == 200) {
                    $data = json_decode($response['body']);
                    if (is_array($data)) {
                        $i = 0;
                        foreach ($data as $datavalues) {
                            $inside_array[$i] ['postUrl'] = $datavalues->postUrl;
                            $inside_array[$i] ['postId'] = $datavalues->postId;
                            $inside_array[$i] ['title'] = $datavalues->title;
                            $inside_array[$i] ['isDeletable'] = $datavalues->isDeletable;
                            $inside_array[$i] ['bannerEmbedStyle'] = isset($datavalues->bannerEmbedStyle) ? $datavalues->bannerEmbedStyle : "";

                            $i++;
                        }
                        $response = $inside_array;
                    }
                } else {
                    $response = 'ServerError';
                }
            }
            return $response;
        }

        /**
         * Remote Get Call to Connect to Engament with X-auth-token
         * @param type $type
         * @return type
         */
        public static function full_engament($type) {
            $xauthtoken = !is_null(get_option('x-auth-token')) ? get_option('x-auth-token') : '';
            $args = array(
                'headers' => array(
                    'Content-Type' => 'application/json',
                    'x-auth-token' => $xauthtoken
            ));
            $kalim = Itsp_Utility::key();
            
            $IS_GET_API = ITSP_API_DISPLAY . Itsp_Utility::key() . '?type=' . $type . '&channel=intellapages&status=All&streamType=PUBLISHED&since=' . round(microtime(true) * 1000) . '';
            $response = wp_remote_get($IS_GET_API, $args);
            return $response;
        }

        /**
         * Ajax Call To get Calender Events based on Start Date and End Date
         */
        public function get_calender_events() {
            if (Itsp_Utility::key()) {
                $start = intval($_POST['start']);
                $end = intval($_POST['end']);
                $timezone_offset_minutes = 330;
                $timezone_name = timezone_name_from_abbr("", $timezone_offset_minutes * 60, false);
                $events = array();
                $xauthtoken = !is_null(get_option('x-auth-token')) ? get_option('x-auth-token') : '';
                $args = array(
                    'headers' => array(
                        'Content-Type' => 'application/json',
                        'x-auth-token' => $xauthtoken
                ));
                $response = wp_remote_get(ITSP_API_DISPLAY . Itsp_Utility::key() . '/EVENT?requestFrom=&since=' . $start . '&until=' . $end . '', $args);
                if (is_array($response)) {
                    $header = $response['headers']; // array of http header lines
                    $body = json_decode($response['body']); // use the content
                    $data_events = array();
                    foreach ($body as $r) {
                        date_default_timezone_set($timezone_name);
                        $data_events[] = array(
                            "id" => $r->id,
                            "title" => $r->title,
                            "description" => $r->description,
                            "end" => date('m/d/Y H:i:s', $r->eventEndDate / 1000),
                            "start" => date('m/d/Y H:i:s', $r->eventStartDate / 1000),
                            "url" => $r->postUrl,
                        );
                    }
                    echo json_encode(array("events" => $data_events));
                    exit();
                }
            }
            echo json_encode(array("events" => false));
            exit();
        }

     

        /**
         * get the branding form Intellasphere
         * @return Brankit json
         */
        public static function get_post_details($posturl) {
            Itsp_Utility::set_post_details_id($posturl);
            $option_name = $posturl;
            $results = get_option($option_name);
            if (!$results || !is_object($results)) {
                $new_value = Itsp_Utility::set_post_details($posturl);
                if (get_option($option_name) !== false) {

                    // The option already exists, so update it.
                    update_option($option_name, $new_value);
                } else {

                    // The option hasn't been created yet, so add it with $autoload set to 'no'.
                    $deprecated = null;
                    $autoload = 'no';
                    add_option($option_name, $new_value, $deprecated, $autoload);
                }
                return $new_value;
            }
            return $results;
        }

        /**
         * Storing the Id as Identifier
         * @param type $posturl
         */
        public Static function set_post_details_id($posturl) {
            $option_name = 'get_post_details';
            $results = get_option($option_name);
            $new_value = array($posturl);
            if (!$results || !in_array($posturl, $results)) {

                array_push($new_value, $posturl);
                if (get_option($option_name) !== false) {
                    // The option already exists, so update it.
                    update_option($option_name, $new_value);
                } else {
                    // The option hasn't been created yet, so add it with $autoload set to 'no'.
                    $deprecated = null;
                    $autoload = 'no';
                    add_option($option_name, $new_value, $deprecated, $autoload);
                }
            }
        }

        /**
         * Function to Get Post Details
         * @param type $posturl
         * @return boolean
         */
        public static function set_post_details($posturl) {
            if (Itsp_Utility::key()) {
                $xauthtoken = !is_null(get_option('x-auth-token')) ? get_option('x-auth-token') : '';
                $args = array(
                    'headers' => array(
                        'Content-Type' => 'application/json',
                        'x-auth-token' => $xauthtoken
                ));
                $IS_GET_API = ITSP_API_REST . 'rest/app/' . Itsp_Utility::key() . '/' . $posturl . '';

                $response = wp_remote_get($IS_GET_API, $args);
                if (!is_wp_error($response)) {
                    if ($response['response']['code'] == 200) {
                        $data = json_decode($response['body']);
                        return $data;
                    }
                }
            }
            return false;
        }

     

        /**
         * Beaver builder to load  scripts
         */
        public function load_default_scripts($include) {
            wp_enqueue_style('is_admin_spectrum_style', ITSP_INTEGRATION__PLUGIN_URL . '/admin/cs/admin.css', array(), ITSP_INTELLASPHERE_VERSION);
            wp_enqueue_style('is_admin_spectrum_style', ITSP_INTEGRATION__PLUGIN_URL . '/admin/cs/spectrum.css', array(), ITSP_INTELLASPHERE_VERSION);
            if ($include)
                wp_enqueue_script("globalajax_script", ITSP_INTEGRATION__PLUGIN_URL . '/admin/js/int_costom_js.js', array('jquery'), ITSP_INTELLASPHERE_VERSION);
           
            //wp_enqueue_script( 'globalajax_script', $js_url . 'jquery.validate.min.js', array(), 10 );
           
//            wp_enqueue_script("globalajax_script", ITSP_INTEGRATION__PLUGIN_URL . '/admin/js/int_costom_js.js', array('jquery'), ITSP_INTELLASPHERE_VERSION);

            wp_enqueue_script('globalajax_script"',ITSP_INTEGRATION__PLUGIN_URL . '/admin/js/int_costom_js.js','','',true);
            wp_enqueue_style('wp-color-picker');
            wp_enqueue_script(
                    'iris', admin_url('js/iris.min.js'), array('jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch'), false, ITSP_INTELLASPHERE_VERSION
            );
            wp_enqueue_script(
                    'wp-color-picker', admin_url('js/color-picker.min.js'), array('iris'), false, ITSP_INTELLASPHERE_VERSION
            );
            $colorpicker_l10n = array(
                'clear' => __('Clear', 'intellasphere'),
                'defaultString' => __('Default', 'intellasphere'),
                'pick' => __('Select Color', 'intellasphere'),
                'current' => __('Current Color', 'intellasphere'),
            );
            wp_localize_script('wp-color-picker', 'globalajax', array('ajaxurl' => admin_url('admin-ajax.php')));
        }

        
        /**
         * Load the Scripts in widgets & Banner page
         * @param type $hook
         * @return type
         */
        public function admin_script($hook) {
            if ($hook == 'widgets.php') {
                $this->load_default_scripts(0);
            } else if ((isset($_GET['page']) && $_GET['page'] == 'is-banner-page')) {
                $this->load_default_scripts(1);
            }
            return;
        }

        
        /**
         * Store the login token in options x-auth-token
         */
        public function process_to_connect() {
            $url = ITSP_API_REST . 'j_spring_security_check';
            $email = sanitize_email($_POST['email']);
            $psw = trim($_POST['psw']);
            $data = 'j_username=' . urlencode($email) . '&j_password=' . urlencode($psw) . '';
            $res = wp_remote_post($url, array(
                'method' => 'POST',
                'headers' => array('Content-Type' => 'application/x-www-form-urlencoded'),
                'body' => $data
            ));
            if (!is_wp_error($res) && $res['response']['code'] == 200) {
                $data = wp_remote_retrieve_headers($res, true);
                $status = '';
                if (get_option('x-auth-token') !== false) {
                    $status = update_option('is_connect_email', $email);
                    update_option('x-auth-token', $data['x-auth-token']);
                } else {
                    $deprecated = null;
                    $autoload = 'no';
                    $status = add_option('is_connect_email', $email, $deprecated, $autoload);
                    add_option('x-auth-token', $data['x-auth-token']);
                }
                $this->first_company_active($email);
                echo wp_send_json_success(esc_attr($status));
            }else if(isset($res->errors['http_request_failed'][0])) {
                echo wp_send_json_error(array('error' => "invalid End point"));
            }else {
                echo wp_send_json_error(array('error' => esc_attr($res)));
            }
        }

        /**
         * Ajax Request to Insert into the Table
         * @global type $wpdb
         */
        public function banner_table() {
            global $wpdb;
            $banner_code = sanitize_text_field($_POST['banner_code']);
            $banner_page = sanitize_text_field($_POST['banner_page']);
            $tablename = $wpdb->prefix . 'banner';
            $id = $wpdb->query(
                    $wpdb->prepare(
                            "INSERT INTO $tablename ( `pages`, `banner`) VALUES
		( %s, %s)",
                            $banner_page,
                            $banner_code
                    )
            );
            $url = admin_url('admin.php?page=is-banner-page');
            echo wp_send_json_success($url);
            exit();
        }

        /**
         * First Company Active
         * @param type $connectid
         */
        public function first_company_active($connectid) {
            // $options = array();
            $xauthtoken = !is_null(get_option('x-auth-token')) ? get_option('x-auth-token') : '';
            $args = array(
                'headers' => array(
                    'Content-Type' => 'application/json',
                    'x-auth-token' => $xauthtoken
            ));
            $response = wp_remote_get(ITSP_API_REST . '/rest/customers?username=' . urlencode($connectid) . '', $args);
            if (!is_wp_error($response) && is_array($response)) {
                $user_company = json_decode($response['body']); // use the contentvar
                $comany_list = (array) $user_company;
                
                if (count($comany_list) == 1) {
                    reset($comany_list);
                    $firstKey = key($comany_list);
                    
                    $options = array();
                    if (isset($firstKey)) {
                        $options['customerid'] = sanitize_text_field($firstKey);
                        
                        if (get_option('is_op_array') !== false) {
                            update_option('is_op_array', $options);
                        } else {
                            add_option('is_op_array', $options);
                        }
                        
                    }
                }
            }
        }

        /**
         * Get Customer id 
         * @return type
         */
        public static function key() {
            if (is_multisite() && defined('Customerid')) {
                return Customerid;
            } else {
                $results = !is_null(get_option('is_op_array')) ? get_option('is_op_array') : '';
                return isset($results['customerid']) ? $results['customerid'] : false;
            }
        }

        /**
         * Get the Address from the Object
         * @param type $obj
         * @return type
         */
        public static function is_address($obj) {
            $address = '';
            if (!empty($obj->street)) {
                $address .= $obj->street . ', ';
            }
            if (!empty($obj->substreet)) {
                $address .= $obj->substreet . ', ';
            }

            if (!empty($obj->state)) {
                $address .= $obj->state . ', ';
            }
            if (!empty($obj->country)) {
                $address .= $obj->country . ', ';
            }

            if (!empty($obj->zipcode)) {
                $address .= $obj->zipcode . ', ';
            }
            return rtrim($address, ', ');
        }

        /**
         * Time Zone Set
         */
        public static function time_zone_set() {
            $timezone_offset_minutes = 330;
            $timezone_name = timezone_name_from_abbr("", $timezone_offset_minutes * 60, false);
            date_default_timezone_set($timezone_name);
        }

        /*
         * Gravity & Ninja Form Lead Generation Connections Post Request
         */
        public static function is_store_lead($lead_lists) {
            $lead_array = $lead_lists;
            reset($lead_lists);
            $first_key = key($lead_lists);
            $url = ITSP_INTELLASPHERE . '/rest/app/contactformresponse/' . Itsp_Utility::key() . '/' . $first_key . '/null';
            $data = $lead_array[$first_key];
            $result = wp_remote_post($url, array(
                'sslverify' => false,
                'timeout' => 15,
                'headers' => array('Content-Type' => 'application/json; charset=utf-8'),
                'body' => json_encode($data))
            );
            return $result;
        }

        /*
         * Ninja Form Mapping
         */
        public static function ninja_form_mapping() {
            $response = Itsp_Utility::full_engament('FORM_CONTACTUS');
            $field_mapping_settings = array();
            $options = array();
            if (!is_wp_error($response)) {
                if ($response['response']['code'] == 200) {
                    $data = json_decode($response['body']);
                    if (is_array($data)) {
                        $field_mapping_settings['option'][] = array('label' => __('Select The Option', 'intellasphere'), 'value' => "");
                        foreach ($data as $datavalues) {
                            $field_mapping_settings['option'][] = array('label' => __($datavalues->title, 'intellasphere'), 'value' => $datavalues->postId);
                            foreach ($datavalues->fields as $values) {
                                if (!$values->hidden) {
                                    $field_mapping_settings['fields'][] = array(
                                        'name' => 'is_lead_list_' . $values->hashKey,
                                        'type' => 'textbox',
                                        'label' => $values->displayName,
                                        'width' => 'one-half',
                                        'group' => 'primary',
                                        'value' => $values->hashKey,
                                        'use_merge_tags' => TRUE,
                                        'deps' => array(
                                            'is_select_form' => $datavalues->postId
                                        ),
                                        'help' => __($datavalues->description, 'intellasphere')
                                    );
                                }
                            }
                        }
                    }
                }
            }

            return $field_mapping_settings;
        }

        /**
         * Gravity Form Integration
         * @return type
         */
        public static function gravity_form_mapping() {
            $response = Itsp_Utility::full_engament('FORM_CONTACTUS');
            $field_mapping_settings = array();
            $options = array();
            if (!is_wp_error($response)) {
                if ($response['response']['code'] == 200) {
                    $data = json_decode($response['body']);
                    if (is_array($data)) {
                        $field_mapping_settings['option'][] = array('label' => __('Select The Option', 'intellasphere'), 'value' => "");
                        foreach ($data as $datavalues) {
                            $field_mapping_settings['option'][] = array('label' => __($datavalues->title, 'intellasphere'), 'value' => $datavalues->postId);
                            $field_mapping_settings['option_array'][] = $datavalues->postId;
                            foreach ($datavalues->fields as $values) {
                                if (!$values->hidden) {
                                    $field_mapping_settings['fields'][$datavalues->postId][] = array(
                                        'name' => $values->hashKey,
                                        'label' => esc_html__($values->displayName, 'intellasphere'),
                                        'required' => true,
                                        'dependency' => array('field' => 'createTask', 'values' => $datavalues->postId),
                                    );
                                }
                            }
                        }
                    }
                }
            }
            return $field_mapping_settings;
        }

    }

}

