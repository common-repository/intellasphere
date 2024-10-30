<?php
/**
 * IntellaSphere Widget Functions
 *
 * Widget related functions and widget registration.
 *
 * @author    Intellasphere
 * @category 	Core
 * @package 	IntellaShphere/Functions
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Including the Widget Files
 */
include_once( 'widgets/class-itsp-engage.php');
if (!class_exists("Itsp_Widgets")) {

    class Itsp_Widgets {

        public function __construct() {
            add_action('widgets_init', array($this, 'is_load_intellasphere_widget'));
        }

        /**
         * Initialize IS Widgets
         */
        public function is_load_intellasphere_widget($hook) {
            if (class_exists('Itsp_Engage')) {
                register_widget('Itsp_Engage');
            }
        }

        /**
         * Widget Front End Display
         * @param type $args
         * @param type $instance
         * @param type $datatype
         * @param type $datadynamictype
         * @param type $widgetcode
         */
        public static function is_frontend_display($args, $instance, $ifmtype, $datadynamictype, $widgetcode) {
            $selectid = isset($instance['selectid']) ? explode(':', $instance['selectid']) : '';
            $list = '';
           $ebdid =' ';
            if (isset($widgetcode) && $widgetcode != '') {
                $dataid = isset($selectid[0]) ? $selectid[0] : "";
                
                $ebdid = $dataid;
            }
            $width = (isset($instance['datawidth']) && !empty($instance['datawidth']) && $instance['datawidth'] != '0px') ? ltrim($instance['datawidth'], "#") : '100%';
            $height = (isset($instance['dataheight']) && !empty($instance['dataheight']) && $instance['datawidth'] != '0px') ? ltrim($instance['dataheight'], "#") : '100%';
            $dtastyle = (isset($instance['datastyle']) && !empty($instance['datastyle'])) ? ltrim($instance['datastyle']) : 'Embed';
            $data_design = (isset($instance['datadesign']) && !empty($instance['datadesign'])) ? ltrim($instance['datadesign']) : 'Embed';
            $data_layout = (isset($instance['datalayout']) && !empty($instance['datalayout'])) ? ltrim($instance['datalayout']) : 'Embed';
            $mxwidth = (isset($instance['datamaxwidth']) && !empty($instance['datamaxwidth']) && $instance['datamaxwidth'] != '0px') ? ltrim($instance['datamaxwidth'], "#") : '100%';
            $mxheight = (isset($instance['datamaxheight']) && !empty($instance['datamaxheight']) && $instance['datamaxheight'] != '0px') ? ltrim($instance['datamaxheight'], "#") : '100%';
            $tsptbkground = (isset($instance['tranbg']) && $instance['tranbg'] == 'on') ? 'true' : 'false';
            $hidetitle = (isset($instance['shhidtitle']) && $instance['shhidtitle'] == 'on') ? 'true' : 'false';
            $showcpylogo = (isset($instance['showhidec']) && $instance['showhidec'] == 'on') ? 'true' : 'false';
            $showcpyname = (isset($instance['showhidecompany']) && $instance['showhidecompany'] == 'on') ? 'true' : 'false';
            $dyntype = (isset($instance['widgettype']) && !empty($instance['widgettype'])) ? $instance['widgettype'] : 'null';
            $borderendn = (isset($instance['widgettype']) && $instance['showhideb'] == 'on') ? 'true' : 'false';
            $unqid = md5(uniqid(rand(), true)) . $ebdid;
            $ebdid = trim(str_replace("_default_$ebdid", " ", $ebdid));
            $height = (isset($height) && !empty($height) && $height != '0px') ? ltrim($height, "#") : '100%';
            $mxheight = (isset($mxheight) && !empty($mxheight) && $mxheight != '0px') ? ltrim($mxheight, "#") : '100%';
            $dtaalign = '';
            if ($dtastyle == 'Embed') {
                $dtaalign = 'Embed';
            } elseif ($dtastyle == 'Modal') {
                $dtaalign = $instance['mdalignemnt'];
            } elseif ($dtastyle == 'Slider') {
                $dtaalign = $instance['slidanmt'];
            } 
            $obj = new Itsp_Utility();
            if (!empty($ebdid)) {
                if (isset($instance['brandkiton']) && $instance['brandkiton'] != 'on') {
                    $brandkit = $obj->brandkit();
                    $brandkit_colorpalette = self::remove_hash_code((array) $brandkit['colorPalette']);
                    $brandkit_fontinfo = (array) $brandkit['fontInfo'];
                    $brandkit_buttoninfo = (array) $brandkit['buttonInfo'];
                    $brandkit_forminfo = (array) $brandkit['formInfo'];
                } else {
                    $brandkit = $obj->staticbrandkit($instance);
                    $brandkit_colorpalette = $brandkit['brandkit_colorpalette'];
                    $brandkit_fontinfo = $brandkit['brandkit_fontinfo'];
                    $brandkit_buttoninfo = $brandkit['brandkit_buttoninfo'];
                    $brandkit_forminfo = $brandkit['brandkit_forminfo'];
                    
                }
                include( ITSP_INTEGRATION__PLUGIN_DIR . 'templates/embeddeds/forms.php');
            } else {
                _e( 'Please Select Engagament', 'textdomain' );
            }
        }

        /**
         * This method is to Remove # from Color Code
         * @param type $brandkit_colorpalette
         * @return type
         */
        
        public static function remove_hash_code($brandkit_colorpalette) {
            $colors = array();
            foreach ($brandkit_colorpalette as $key => $value) {
                $colors[$key] = ltrim($value, "#");
            }
            return $colors;
        }

        /**
         * Widget Front Events End Display
         * @param type $args
         * @param type $instance
         */
        public static function is_frontend_event_display($args, $instance) {
            Itsp_Utility::time_zone_set();
            $uniq_pop = uniqid();
            $lytlimit = isset($instance['lytlimit']) ? (int) $instance['lytlimit'] : 20;
            $obj = new Itsp_Utility();
            $brandkit = $obj->brandkit();
            if (isset($instance['brandkiton']) && $instance['brandkiton'] != 'on') {
                $brandkit = $obj->brandkit();
                $brandkit_colorpalette = (array) $brandkit['colorPalette'];
                $brandkit_fontinfo = (array) $brandkit['fontInfo'];
            } else {
                $brandkit = $obj->staticbrandkit($instance);
                $brandkit_colorpalette = $brandkit['brandkit_colorpalette'];
                $brandkit_fontinfo = $brandkit['brandkit_fontinfo'];
            }
            if (isset($instance['type']) && $instance['type'] == "Calender") {
                require_once( ITSP_INTEGRATION__PLUGIN_DIR . 'templates/events/event-calender.php');
            } else if (isset($instance['type']) && $instance['type'] == "Grid") {
                require_once( ITSP_INTEGRATION__PLUGIN_DIR . 'templates/events/event-grids.php');
            } else if (isset($instance['type']) && $instance['type'] == "Slider") {
                require_once( ITSP_INTEGRATION__PLUGIN_DIR . 'templates/events/event-sliders.php');
            } else if (isset($instance['type']) && $instance['type'] == "List") {
                require_once( ITSP_INTEGRATION__PLUGIN_DIR . 'templates/events/event-lists.php');
            }else {
                require_once( ITSP_INTEGRATION__PLUGIN_DIR . 'templates/events/event-grids.php');
            }
        }

        /**
         * Widget Front Events End Display
         * @param type $args
         * @param type $instance
         */
        public static function is_frontend_event_display_guten($args, $instance) {
            Itsp_Utility::time_zone_set();
            $uniq_pop = uniqid();
            $lytlimit = isset($instance['lytlimit']) ? (int) $instance['lytlimit'] : 20;
            $obj = new Itsp_Utility();
            if (isset($instance['brandkiton']) && $instance['brandkiton'] != 'on') {
                $brandkit = $obj->brandkit();
                $brandkit_colorpalette = (array) $brandkit['colorPalette'];
                $brandkit_fontinfo = (array) $brandkit['fontInfo'];
            } else {
                $brandkit = $obj->staticbrandkit($instance);
                $brandkit_colorpalette = $brandkit['brandkit_colorpalette'];
                $brandkit_fontinfo = $brandkit['brandkit_fontinfo'];
            }
            if (isset($instance['type']) && $instance['type'] == "Calender") {
                $html = '';
                ob_start();
                require_once( ITSP_INTEGRATION__PLUGIN_DIR . 'templates/events/event-calender.php');
                $html = ob_get_clean();
                return $html;
            } else if (isset($instance['type']) && $instance['type'] == "Grid") {
                $htmls = '';
                ob_start();
                require_once( ITSP_INTEGRATION__PLUGIN_DIR . 'templates/events/event-grids.php');
                $html = ob_get_clean();
                return $html;
            } else if (isset($instance['type']) && $instance['type'] == "Slider") {
                $html = '';
                ob_start();
                require_once( ITSP_INTEGRATION__PLUGIN_DIR . 'templates/events/event-sliders.php');
                $html = ob_get_clean();
                return $html;
            } else if (isset($instance['type']) && $instance['type'] == "List") {
                $html = '';
                ob_start();
                require_once( ITSP_INTEGRATION__PLUGIN_DIR . 'templates/events/event-lists.php');
                $html = ob_get_clean();
                return $html;
            } else {
                $html = '';
                ob_start();
                require_once( ITSP_INTEGRATION__PLUGIN_DIR . 'templates/events/event-grids.php');
                $html = ob_get_clean();
                return $html;
            }
        }

        /**
         * Grid Newsletter
         * @param type $args
         * @param type $instance
         */
        public static function is_frontend_display_grid_newsletter($args, $instance) {
            $nbrofcolumn = isset($instance['nbrofcolumn']) ? $instance['nbrofcolumn'] : '';
            $uid = isset($args['widget_id']) ? $args['widget_id'] : "";
            $obj = new Itsp_Utility();
            if (isset($instance['brandkiton']) && $instance['brandkiton'] != 'on') {
                $brandkit = $obj->brandkit();
                $brandkit_colorpalette = (array) $brandkit['colorPalette'];
                $brandkit_fontinfo = (array) $brandkit['fontInfo'];
            } else {
                $brandkit = $obj->staticbrandkit($instance);
                $brandkit_colorpalette = $brandkit['brandkit_colorpalette'];
                $brandkit_fontinfo = $brandkit['brandkit_fontinfo'];
            }
            $start = round(microtime(true) * 1000);
            $results = Itsp_Utility::static_newsletter($start);
            $lytlimit = isset($instance['lytlimit']) ? (int) $instance['lytlimit'] : 20;
            $uniq_pop = uniqid();
            if (count($results)) {
                require_once( ITSP_INTEGRATION__PLUGIN_DIR . 'templates/newsletters/newsletter-grids.php');
            } else {
                require_once( ITSP_INTEGRATION__PLUGIN_DIR . 'templates/newsletters/newsletter-no-found.php');
            }
        }

        /**
         * Grid Newsletter
         * @param type $args
         * @param type $instance
         */
        public static function is_frontend_display_grid_newsletter_guten($args, $instance) {
            $nbrofcolumn = isset($instance['nbrofcolumn']) ? $instance['nbrofcolumn'] : '';
            $uid = isset($args['widget_id']) ? $args['widget_id'] : "";
            $obj = new Itsp_Utility();
            if (isset($instance['brandkiton']) && $instance['brandkiton'] != 'on') {
                $brandkit = $obj->brandkit();
                $brandkit_colorpalette = (array) $brandkit['colorPalette'];
                $brandkit_fontinfo = (array) $brandkit['fontInfo'];
            } else {
                $brandkit = $obj->staticbrandkit($instance);
                $brandkit_colorpalette = $brandkit['brandkit_colorpalette'];
                $brandkit_fontinfo = $brandkit['brandkit_fontinfo'];
            }
            $start = round(microtime(true) * 1000);
            $results = Itsp_Utility::static_newsletter($start);
            $lytlimit = isset($instance['lytlimit']) ? (int) $instance['lytlimit'] : 20;
            $uniq_pop = uniqid();
            if (count($results)) {
                $html = '';
                ob_start();
                require_once( ITSP_INTEGRATION__PLUGIN_DIR . 'templates/newsletters/newsletter-grids.php');
                $html = ob_get_clean();
                return $html;
            } else {
                $html = '';
                ob_start();
                require_once( ITSP_INTEGRATION__PLUGIN_DIR . 'templates/newsletters/newsletter-grids.php');
                $html = ob_get_clean();
                return $html;
            }

            return $htmml;
        }

        /**
         * Promotion
         * @param type $args
         * @param type $instance
         * 
         */
        public static function is_frontend_display_promotion($args, $instance) {
                        $brandkit_colorpalette = '';
                        $brandkit_fontinfo = '';
                        $nbrofcolumn = isset($instance['nbrofcolumn']) ? $instance['nbrofcolumn'] : '';
                        $uid = isset($args['widget_id']) ? $args['widget_id'] : "";
                        $obj = new Itsp_Utility();
                        if (isset($instance['brandkiton']) && $instance['brandkiton'] != 'on') {
                            $brandkit = $obj->brandkit();
                            $brandkit_colorpalette = (array) $brandkit['colorPalette'];
                            $brandkit_fontinfo = (array) $brandkit['fontInfo'];
                        } else {
                            $brandkit = $obj->staticbrandkit($instance);
                            $brandkit_colorpalette = $brandkit['brandkit_colorpalette'];
                            $brandkit_fontinfo = $brandkit['brandkit_fontinfo'];
                        }
                        $lytlimit = isset($instance['lytlimit']) ? (int) $instance['lytlimit'] : 20;
                        $start = round(microtime(true) * 1000);
                        Itsp_Utility::time_zone_set();
                        $results = Itsp_Utility::static_offers($start);
                        $total_offers_count = count($results);
                        $uniq_pop = uniqid();
                        if (isset($instance['type']) && $instance['type'] == "Grid") {
                            require_once( ITSP_INTEGRATION__PLUGIN_DIR . 'templates/offers/offer-grids.php');
                        } elseif (isset($instance['type']) && $instance['type'] == "Slider") {
                            require_once( ITSP_INTEGRATION__PLUGIN_DIR . 'templates/offers/offer-sliders.php');
                        } else if (isset($instance['type']) && $instance['type'] == "List") {
                            require_once( ITSP_INTEGRATION__PLUGIN_DIR . 'templates/offers/offer-lists.php');
                        }else{
                            require_once( ITSP_INTEGRATION__PLUGIN_DIR . 'templates/offers/offer-grids.php');
                        } 
        }

       /**
         * This Method is determine weather  image and Content need is display side by side or not
         * @param type $lytlimit
         * @param type $ci
         * @param type $nbrofcolumn
         * @param type $count
         * @return boolean
         */
        public static function is_side_by_side($lytlimit, $ci, $nbrofcolumn, $total_offers_count) {
               if ( ($lytlimit == 4 && $ci == 4 && $nbrofcolumn == 33) 
                    || ($lytlimit == 1) 
                    || ($lytlimit == 5 && $ci == 5 && $nbrofcolumn == 49) 
                    || ($nbrofcolumn == 100) 
                    || ($lytlimit == 5 && $ci == 5 && $nbrofcolumn == 25) 
                    || ($total_offers_count == 1) 
                    || ($nbrofcolumn == 49 && $total_offers_count == 3 && $lytlimit == 5 && $ci == 3)
                    || ($nbrofcolumn == 49 && $total_offers_count == 3 && $lytlimit == 3 && $ci == 3)  
                    || ($nbrofcolumn == 49 && $total_offers_count == 3 && $lytlimit == 4 && $ci == 3)  
                    || ($nbrofcolumn == 49 && $lytlimit == 3 && $ci == 3)          
                  ) {
                return true;
            }
            return false;
        }

        /**
         * Promotion
         * @param type $args
         * @param type $instance
         * 
         */
        public static function is_frontend_promotion_display_guten($args, $instance) {
            $brandkit_colorpalette = '';
            $brandkit_fontinfo = '';
            $nbrofcolumn = isset($instance['nbrofcolumn']) ? $instance['nbrofcolumn'] : '';
            $uid = isset($args['widget_id']) ? $args['widget_id'] : "";
            $obj = new Itsp_Utility();
            if (isset($instance['brandkiton']) && $instance['brandkiton'] != 'on') {
                $brandkit = $obj->brandkit();
                $brandkit_colorpalette = (array) $brandkit['colorPalette'];
                $brandkit_fontinfo = (array) $brandkit['fontInfo'];
            } else {
                $brandkit = $obj->staticbrandkit($instance);
                $brandkit_colorpalette = $brandkit['brandkit_colorpalette'];
                $brandkit_fontinfo = $brandkit['brandkit_fontinfo'];
            }
            $lytlimit = isset($instance['lytlimit']) ? (int) $instance['lytlimit'] : 20;
            $start = round(microtime(true) * 1000);
            Itsp_Utility::time_zone_set();
            $results = Itsp_Utility::static_offers($start);
            $total_offers_count =  count($results);
            $uniq_pop = uniqid();
            if (isset($instance['type']) && $instance['type'] == "Grid") {
                $html = '';
                ob_start(); 
                require_once( ITSP_INTEGRATION__PLUGIN_DIR . 'templates/offers/offer-grids.php');
                $html = ob_get_clean();
                return $html;
            } elseif (isset($instance['type']) && $instance['type'] == "Slider") {
                 $html = '';
                 ob_start();
                 require_once( ITSP_INTEGRATION__PLUGIN_DIR . 'templates/offers/offer-sliders.php'); 
                 $html = ob_get_clean();
                 return $html;
            } else if (isset($instance['type']) && $instance['type'] == "List") {
                $html = '';
                ob_start();
                require_once( ITSP_INTEGRATION__PLUGIN_DIR . 'templates/offers/offer-lists.php'); 
                $html = ob_get_clean();
                return $html;
            }else {
                $html = '';
                ob_start(); 
                require_once( ITSP_INTEGRATION__PLUGIN_DIR . 'templates/offers/offer-grids.php');
                $html = ob_get_clean();
                return $html;
            }
        }

        /**
         * widget form of promotions
         * @param type $instance
         */
        public function form($instance) {
            ?>
            <p>
                <label for="<?php echo $this->get_field_id('text'); ?>">Type: 
                    <select class='itsp_wideget_form' id="<?php echo $this->get_field_id('type'); ?>"
                            name="<?php echo $this->get_field_name('type'); ?>" type="text">
                        <option value="Grid" <?php echo (isset($instance['type']) && $instance['type'] == "Grid") ? 'Selected' : ''; ?> >Grid</option>
                        <option value="Slider"  <?php echo (isset($instance['type']) && $instance['type'] == "Slider") ? 'Selected' : ''; ?> >Slider</option>
                    </select>                
                </label>
            </p>
            <?php
        }

        /**
         * Reviews Display
         * @param type $args
         * @param type $instance
         */
        public static function is_frontend_display_reviews($args, $instance) {
                        $revresults = Itsp_Utility::get_user_reviews($instance['rating'], $instance['moderate']);
                        if ($revresults && count($revresults) != 0) {
                            $obj = new Itsp_Utility();
                            if (isset($instance['brandkiton']) && $instance['brandkiton'] != 'on') {
                                $brandkit = $obj->brandkit();
                                $brandkit_colorpalette = isset($brandkit['colorPalette']) ? (array) $brandkit['colorPalette'] : '';
                                $brandkit_fontinfo = isset($brandkit['colorPalette']) ? (array) $brandkit['fontInfo'] : "";
                                $brandkit_buttoninfo = isset($brandkit['colorPalette']) ? (array) $brandkit['buttonInfo'] : "";
                            } else {
                                $brandkit = $obj->staticbrandkit($instance);
                                $brandkit_colorpalette = $brandkit['brandkit_colorpalette'];
                                $brandkit_fontinfo = $brandkit['brandkit_fontinfo'];
                                $brandkit_buttoninfo = $brandkit['brandkit_buttoninfo'];
                            }
                            $revstructure = array();
                            $uid = isset($args['widget_id']) ? $args['widget_id'] : "";
                            $lytlimit = isset($instance['lytlimit']) ? (int) $instance['lytlimit'] : 20;
                            if (isset($instance['reviewlayout']) && $instance['reviewlayout'] == 'Slider') {
                                require_once( ITSP_INTEGRATION__PLUGIN_DIR . 'templates/reviews/review-sliders.php');
                            } else if (isset($instance['reviewlayout']) && $instance['reviewlayout'] == 'List') {
                                require_once( ITSP_INTEGRATION__PLUGIN_DIR . 'templates/reviews/review-lists.php');
                            }else if (isset($instance['reviewlayout']) && $instance['reviewlayout'] == 'Grid') {
                                require_once( ITSP_INTEGRATION__PLUGIN_DIR . 'templates/reviews/review-grids.php');
                            }else {
                                require_once( ITSP_INTEGRATION__PLUGIN_DIR . 'templates/reviews/review-grids.php');
                            }
                        } else {
                            require_once( ITSP_INTEGRATION__PLUGIN_DIR . 'templates/reviews/review-no-found.php');
                        }
             }

         /**
         * Reviews Display
         * @param type $args
         * @param type $instance
         */
        public static function is_frontend_review_display_guten($args, $instance) {
                       $revresults = Itsp_Utility::get_user_reviews($instance['rating'], $instance['moderate']);
                        if ($revresults && count($revresults) != 0) {
                            $obj = new Itsp_Utility();
                            if (isset($instance['brandkiton']) && $instance['brandkiton'] != 'on') {
                                $brandkit = $obj->brandkit();
                                $brandkit_colorpalette = isset($brandkit['colorPalette']) ? (array) $brandkit['colorPalette'] : '';
                                $brandkit_fontinfo = isset($brandkit['colorPalette']) ? (array) $brandkit['fontInfo'] : "";
                                $brandkit_buttoninfo = isset($brandkit['colorPalette']) ? (array) $brandkit['buttonInfo'] : "";
                            } else {
                                $brandkit = $obj->staticbrandkit($instance);
                                $brandkit_colorpalette = $brandkit['brandkit_colorpalette'];
                                $brandkit_fontinfo = $brandkit['brandkit_fontinfo'];
                                $brandkit_buttoninfo = $brandkit['brandkit_buttoninfo'];
                            }
                            $revstructure = array();
                            $uid = isset($args['widget_id']) ? $args['widget_id'] : "";
                            $lytlimit = isset($instance['lytlimit']) ? (int) $instance['lytlimit'] : 20;
                            if (isset($instance['reviewlayout']) && $instance['reviewlayout'] == 'Slider') {
                                $html = '';
                                ob_start();
                                require_once( ITSP_INTEGRATION__PLUGIN_DIR . 'templates/reviews/review-sliders.php');
                                $html = ob_get_clean();
                                return $html;
                            } else if (isset($instance['reviewlayout']) && $instance['reviewlayout'] == 'List') {
                                $html = '';
                                ob_start();
                                require_once( ITSP_INTEGRATION__PLUGIN_DIR . 'templates/reviews/review-lists.php');
                                $html = ob_get_clean();
                                return $html;
                            } else {
                                $html = '';
                                ob_start();
                                require_once( ITSP_INTEGRATION__PLUGIN_DIR . 'templates/reviews/review-grids.php');
                                $html = ob_get_clean();
                                return $html;
                            }
                        } else {
                            $html = '';
                            ob_start();
                            require_once( ITSP_INTEGRATION__PLUGIN_DIR . 'templates/reviews/review-no-found.php');
                            $html = ob_get_clean();
                            return $html;
                        }
                    }

                }

            }
new Itsp_Widgets();
