<?php
/**
 * IntellaSphere Files Contain All Related Functions ,hooks ,Filters of the intellasphere Plugin
 *
 * 
 * @author    Intellasphere
 * @category 	Core
 * @package 	IntellaShphere
 */
if (!function_exists('itsp_recursive_sanitize_text_field')) {

    /**
     * Sanitize the array value
     * @since 1.0.0
     * @param type $array
     * @return type array
     */
    function itsp_recursive_sanitize_text_field($array) {
        foreach ($array as $key => &$value) {
            if (is_array($value)) {
                $value = itsp_recursive_sanitize_text_field($value);
            } else {
                $value = sanitize_text_field($value);
            }
        }
        return $array;
    }

}

/**
 * Create the Database Function
 */
if (!function_exists('itsp_installer')) {


    /**
     * Create the Table
     * @since 1.0.0
     */
    function itsp_installer() {
        include('installer.php');
    }

}

/**
 * Short Code to Display Banners In Table
 */
add_shortcode('itsp_bannerlist', 'itsp_banner_list_page');

/**
 * Create the Database Function
 */
if (!function_exists('itsp_banner_list_page')) {

    /**
     * Function to display table of banner in ShortCode
     */
    function itsp_banner_list_page() {
        $banner_list_table = new Itsp_Banners_List();
        echo "</pre><div class='wrap'><h2>Banners & Popups</h2>";
        ?>
        <div class="banner_gen_select_engage_type label-title" style=>Select Banner:
            <select class='notification_banner' id="banner_gen_ajax_select" 
                    name="shortcodeform" type="text" data-select="1"  data-change="banner_gen_engagement_type" style ="width:100%;">
                <option>Select</option>
            </select>  
            <select class="" style="display:none;" id="loader_engage">
                <option value="-1">Loading</option>
            </select> 
            Select Pages:
            <select name="page-dropdown" id="get_banner_pages"> 
                <option value="">
                    <?php echo esc_attr(__('Select Page', 'intellasphere')); ?></option> 
                <?php $pages = get_pages(); ?>
                <option value="ALL">ALL</option>
                <?php
                foreach ($pages as $page) {
                    if (!empty($page->post_title)) {
                        ?>
                        <option value="<?php echo esc_attr($page->ID); ?>"> <?php echo esc_attr($page->post_title); ?> </option>
                        <?php
                    }
                }
                ?>
            </select>

            <input type="submit" value="Add" id="submitbanner">
        </div>
        <?php
        $banner_list_table->prepare_items();
        ?>
        <form method="post">
            <?php
            $banner_list_table->display();
            echo '</form></div>';
        }

    }



    /**
     * Language translate
     */
    add_action('init', 'itsp_text_domain_translate');
    if (!function_exists('itsp_text_domain_translate')) {

        function itsp_text_domain_translate() {
            $plugin_rel_path = basename(dirname(__FILE__)) . '/languages'; /* Relative to WP_PLUGIN_DIR */
            load_plugin_textdomain('intellasphere', false, $plugin_rel_path);
            new Itsp_Utility();
        }

    }




    add_action('http_api_curl', 'itsp_custom_curl_timeout', 9999, 1);

    if (!function_exists('itsp_custom_curl_timeout')) {

        /**
         * Increase the Timeout of curl
         * @param type $handle
         */
        function itsp_custom_curl_timeout($handle) {
            curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 30); // 30 seconds. Too much for production, only for testing.
            curl_setopt($handle, CURLOPT_TIMEOUT, 30); // 30 seconds. Too much for production, only for testing.
        }

    }



    if (!function_exists('itsp_header_banner_notification')) {
    /**
     * Add Banner to Header Function Call
     * @global type $wpdb
     */
    function itsp_header_banner_notification() {
           social_banners('header');
    }

}
     
if (!function_exists('itsp_footer_banner_notification')) {
    /**
     * Add Banner to Footer Function Call
     * @global type $wpdb
     */
    function itsp_footer_banner_notification() {
        social_banners('footer');
    }
}


/**
 * Social Banner For Footer & Heaader
 * @global type $wpdb
 * @param type $type
 */
function social_banners($type) {
    global $wpdb;
    $table = $wpdb->prefix . 'banner';
    $results = $wpdb->get_results("SELECT * FROM $table");
    foreach ($results as $key => $value) {
        $page_id = $value->pages;
        if ($value->pages != "ALL") {
            $page_id = (int) $value->pages;
        }
        if ($page_id == get_the_ID() || $page_id == "ALL") {
            $response = Itsp_Utility::get_post_details($value->banner);
            $unqid = uniqid();
            $name = str_replace(' ', '%20', $response->template);
            $bgcolor = str_replace('#', '', $response->theme->backGroundColor);
            $secbgcolor = str_replace('#', '', $response->theme->secondaryBackgroundColor);
            $bttxtcolor = str_replace('#', '', $response->theme->buttonTextColor);
            $button_color = str_replace('#', '', $response->theme->buttonColor);
            $secbtbgcolor = str_replace('#', '', $response->theme->secondaryButtonBackgroundColor);
            $secbtntxtcolor = str_replace('#', '', $response->theme->secondaryButtonTextColor);
            $txtcolor = str_replace('#', '', $response->theme->textColor);
            $tlecolor = str_replace('#', '', $response->theme->titleColor);
            $sectextcolor = str_replace('#', '', $response->theme->secondaryTextColor);
            $borcolor = str_replace('#', '', $response->theme->borderColor);
            $hdrbgcolor = str_replace('#', '', $response->theme->headerBackgroundColor);
            $ftrbgcolor = str_replace('#', '', $response->theme->footerBackgroundColor);
            $pricolor = str_replace('#', '', $response->theme->primaryColor);
            $pribgcolor = str_replace('#', '', $response->theme->primaryBackgroundColor);
            $pritextcolor = isset($response->theme->primaryTextColor) ? str_replace('#', '', $response->theme->primaryTextColor) : "4a4c54";
            $btbgcolor = str_replace('#', '', $response->theme->buttonBackgroundColor);
            $seccolor = str_replace('#', '', $response->theme->secondaryColor);
            $warcolor = str_replace('#', '', $response->theme->warningColor);
            $borderwidth = str_replace('#', '', $response->theme->borderWidth);
            $showborder = str_replace('#', '', $response->theme->showBorder);
            $fntfamily = str_replace('#', '', $response->theme->fontFamily);
            $fntweight = str_replace('#', '', $response->theme->fontWeight);
            $linheight = str_replace('#', '', $response->theme->lineHeight);
            $h1 = str_replace('#', '', $response->theme->h1);
            $h2 = str_replace('#', '', $response->theme->h2);
            $h3 = str_replace('#', '', $response->theme->h3);
            $h4 = str_replace('#', '', $response->theme->h4);
            $h5 = str_replace('#', '', $response->theme->h5);
            $h6 = str_replace('#', '', $response->theme->h6);
            $ptag = str_replace('#', '', $response->theme->p);
            $btnstyle = str_replace('#', '', $response->theme->buttonStyle);
            $btncrnradius = str_replace('#', '', $response->theme->buttonCornerRadius);
            $btnsize = str_replace('#', '', $response->theme->buttonSize);
            $iptborder = str_replace('#', '', $response->theme->inputBorder);
            $iptstyle = str_replace('#', '', $response->theme->inputStyle);
            $iptcrnradius = str_replace('#', '', $response->theme->inputCornerRadius);
            $style = str_replace('#', '', $response->template);
            $align = $response->alignment;
            $height = (isset($response->cardHeight) && $response->cardHeight != 0 ) ? str_replace('#', '', $response->cardHeight) . 'px' : "100%";
            $width = (isset($response->cardWidth) && $response->cardWidth != 0 ) ? str_replace('#', '', $response->cardWidth) . 'px' : "100%";
            $tsptbkground = (isset($response->theme->transparentBackGround)&&$response->theme->transparentBackGround == 1) ? "true" : "false";
            $hidetitle = (isset($response->theme->hideTitle)&& $response->theme->hideTitle == 1) ? "true" : "false";
            $showcpylogo = ($response->showCompanyLogo == 1) ? "true" : "false";
            $showcpyname = ($response->showCompanyName == 1) ? "true" : "false";
            $dtastyle = "Notificationbar";
            if ($response->bannerEmbedStyle == 'modal') {
                $dtastyle = "Modal";
            } else if ($response->bannerEmbedStyle == 'slider') {
                $dtastyle = "Slider";
            } else if ($response->bannerEmbedStyle == 'pageoverlay') {
                $dtastyle = "pageTakeOver";
            }
            if ($type == 'header') {
                if ($align == 'top' || $align == "top_left" || $align == "top_right") {
                    if ($align == "top_left") {
                        $align = "Top%20Left";
                    } elseif ($align == "top_right") {
                        $align = "Top%20Right";
                    }
                    $name = !empty($name) ? $name : ' ';
                    require_once( ITSP_INTEGRATION__PLUGIN_DIR . 'templates/embeddeds/banner.php');
                }
            } else if ($type == 'footer') {
                if ($align == 'bottom' || $align == "middle" || $align == "bottom_right" || $align == "bottom_left") {
                    if ($align == "bottom_right") {
                        $align = "Bottom%20Right";
                    } elseif ($align == "bottom_left") {
                        $align = "Bottom%20Left";
                    }
                    require_once( ITSP_INTEGRATION__PLUGIN_DIR . 'templates/embeddeds/banner.php');
                }
            }
        }
    }
 }

    /**
     * Add Banner Embeded Code to the Header
     */
    add_action('wp_head', 'itsp_header_banner_notification');

    /**
     * Add Banner Embeded Code to the Footer
     */
    add_action('wp_footer', 'itsp_footer_banner_notification');

    /**
     * Filter for Gutenberg to Map the results to title and Post-id
     */
    add_filter('engage_filter', 'is_arrage_options', 10, 1);

    function is_arrage_options($results) {
        $i = 1;
        $options = array();
        $options[] = array('value' => "", 'label' => "Select Options");
        if (is_array($results)) {
            foreach ($results as $key => $values) {
                $options[$i]['value'] = isset($values['postId']) ? $values['postId'] : "";
                $options[$i]['label'] = isset($values['title']) ? $values['title'] : "";
                $i = $i + 1;
            }
        }
        return $options;
    }
    
    
   