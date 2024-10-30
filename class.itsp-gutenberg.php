<?php
/**
 * the Gutenberg Blocks Class
 */
if (!class_exists('Itsp_Gutenberg_Blocks')) {
    class Itsp_Gutenberg_Blocks {
        
      /**
       * hoot to Initialize the Gutenberg Blocks
       */
        public function __construct() {
            add_action('init', array($this, 'init_plugin'));
            add_action('rest_api_init', array($this, 'is_engage_endpoints'));
            add_filter('block_categories_all', array($this, 'is_block_category'), 10, 2);
        }
        
        /**
         * Init Plugin
         * @global type $pagenow
         */
        public function init_plugin() {
            $block_list = $this->common_engage();
            global $pagenow;
            if ($pagenow != 'widgets.php' && $pagenow != 'customize.php') {
                foreach ($block_list as $key => $values) {
                    $block_js = substr($key, 3) . '.js';
                    $arrDeps = array('wp-blocks', 'wp-element', 'wp-components', 'wp-editor', 'wp-data', 'wp-i18n');
                    wp_register_script(
                            $values, plugins_url('blocks/' . $block_js, __FILE__), $arrDeps
                    );
                    register_block_type($key, $this->is_commmon_block($key, $values));
                }
            }
        }
       /**
        * Engage Endpoints
        */
        public function is_engage_endpoints() {
            register_rest_route('api', '/v1/engage/(?P<id>[a-z0-9\-_]+)', array(
                'methods' => 'GET',
                'callback' => array($this, 'is_engage_rest'),
                'permission_callback' => '__return_true'
            ));
        }
         /**
          * Engage Rest Request
          * @param type $request_data
          */
        public function is_engage_rest($request_data) {
            $filter_results = '';
            if (isset($request_data['id'])) {
                if ($request_data['id'] == "FORM_REVIEW_CUSTOM") {
                    $results = Itsp_Utility::get_engage('FORM_REVIEW');
                    $results[] = array('postId' => "no_options", 'title' => "NoLink");
                    $filter_results = apply_filters('engage_filter', $results);
                } else if ($request_data['id'] == "EVENTS") {
                    $results = Itsp_Utility::get_engage('EVENT');
                    $filter_results = apply_filters('engage_filter', $results);
                } else if ($request_data['id'] == "PROMOTIONS" || $request_data['id'] == "GRID_NEWSLETTERS") {
                    $filter_results = array(
                        array('value' => "", 'label' => "Select Options"),
                        array('value' => "grid", 'label' => "Grid"),
                        array('value' => "slider", 'label' => "Slider"),
                    );
                } else {
                    $results = Itsp_Utility::get_engage($request_data['id']);
                    $filter_results = apply_filters('engage_filter', $results);
                }
            }

            echo json_encode($filter_results);
            exit();
        }

        /**
         * Block Editor if to Check the Current Screen is Gutenberg or not
         * @global type $current_screen
         * @return boolean
         */
        public function is_itsp_block_editor() {
            if (is_admin()) {
                global $current_screen;
                if (!isset($current_screen)) {
                    $current_screen = get_current_screen();
                }
                if (( method_exists($current_screen, 'is_block_editor') && $current_screen->is_block_editor() ) || ( function_exists('is_gutenberg_page')) && is_gutenberg_page()) {
                    return true;
                }
            }
            return false;
        }
        
        /**
         * Front End Render Embedded Code 
         * @param type $attributes
         * @return string
         */
        public function is_common_front_end($attributes) {
            $html = '';
         ///   if (1) {
                $hidetitle = (isset($attributes['hide_title']) && $attributes['hide_title'] == '1') ? 'true' : 'false';
                $tsptbkground = (isset($attributes['transparent_background']) && $attributes['transparent_background'] == '1') ? 'true' : 'false';
                $widget_type = isset($attributes['engage_type']) ? $attributes['engage_type'] : '';
                $review_type = isset($attributes['moderate']) ? $attributes['moderate'] : '';
                $surverytyp = isset($attributes['survey_type']) ? $attributes['survey_type'] : '';
                $widgetdtyp = isset($attributes['widget_dynamic_type']) ? $attributes['widget_dynamic_type'] : 'null';
                $borderendn = (isset($attributes['border_enable_disable']) && $attributes['border_enable_disable'] == '1') ? 'true' : 'false';
                $showcpylogo = (isset($attributes['hide_logo']) && $attributes['hide_logo'] == '1') ? 'true' : 'false';
                $showcpyname = (isset($attributes['hide_company']) && $attributes['hide_company'] == '1') ? 'true' : 'false';
                $indmul = (isset($attributes['individual_multiple'])) ? $attributes['individual_multiple'] : '';
                $lytlimit = (isset($attributes['lytlimit'])) ? $attributes['lytlimit'] : '';
                $nbrofcolumn = (isset($attributes['nbrofcolumn'])) ? $attributes['nbrofcolumn'] : '';
                $rating = (isset($attributes['rating'])) ? $attributes['rating'] : '';
                $nbrslider = (isset($attributes['nbrslider'])) ? $attributes['nbrslider'] : '';
                $moderate = (isset($attributes['moderate'])) ? $attributes['moderate'] : '';
                $select_form = isset($attributes['selectform']) ? 'app_id = ' . $attributes['selectform'] : ' ';
                $ebdid = isset($attributes['selectform']) ? $attributes['selectform'] : ' ';
                $lyttyp = isset($attributes['layout_type']) ? $attributes['layout_type'] : '';
                $mxwidth = isset($attributes['maxwidth']) ? $attributes['maxwidth'] : '';
                $mxheight = isset($attributes['maxheight']) ? ( ( $attributes['maxheight'] == '0' || $attributes['maxheight'] == '0px' ) ? "100%" : $attributes['maxheight'] ) : $attributes['maxheight'];
                $width = isset($attributes['width']) ? $attributes['width'] : '';
                $height = isset($attributes['height']) ? ( ( $attributes['height'] == '0' || $attributes['height'] == '0px' ) ? "100%" : $attributes['height'] ) : $attributes['height'];
           // }
            if (($widget_type == 'SURVEY' || $widget_type == 'FORM_REVIEW') && $widgetdtyp == 'null')
                $widgetdtyp = ($widget_type == 'FORM_REVIEW') ? $review_type : ( ($widget_type == 'SURVEY') ? $surverytyp : 'null');
            $dtaalign = "embed";
            $type = "Embed";
            if (($widget_type != "BANNER" ) && ($type == "Embed")) {
                $when_doesit_display = 0;
            }
            $types_of = 'undefined';
            $ifmtype = 'contactform'; //newslettersubscription //banner //event //feedbackform //review //poll //offer //promoterlistsubscription //survey
            if ($widget_type == "NEWSLETTER_SUBSCRIPTION") {
                $ifmtype = "newslettersubscription";
            } else if ($widget_type == "BANNER") {
                $ifmtype = "banner";
            } else if ($widget_type == "EVENTS") {
                $ifmtype = "event";
            } else if ($widget_type == "FORM_FEEDBACK") {
                $ifmtype = "feedbackform";
            } else if ($widget_type == "FORM_REVIEW") {
                $ifmtype = "review";
                $types_of = $review_type;
            } else if ($widget_type == "POLL") {
                $ifmtype = "poll";
            } else if ($widget_type == "COUPON") {
                $ifmtype = "offer";
            } else if ($widget_type == "PROMOTERLIST_SUBSCRIPTION") {
                $ifmtype = "promoterlistsubscription";
            } else if ($widget_type == "SURVEY") {
                $ifmtype = "survey";
                $types_of = $surverytyp;
            } else if ($widget_type == "NEWSLETTER") {
                $ifmtype = "news_letters_subscription_grid";
            }
            if ($ifmtype == 'news_letters_subscription_grid') {
                $attributes['type'] = 'GRID';
                $args['widget_id'] = uniqid();
                $html = Itsp_Widgets::is_frontend_display_grid_newsletter_guten($args, $attributes);
            } else if ($ifmtype == 'event' && $indmul == "multiple") {
                $args['widget_id'] = rand();
                $instance['brandkiton'] = ($attributes['brandkiton'] == true) ? 'on' : false;
                if ($lyttyp == 'Grid') {
                    $attributes['type'] = 'Grid';
                    $html = Itsp_Widgets:: is_frontend_event_display_guten($args, $attributes);
                } else if ($lyttyp == 'List') {
                    $attributes['type'] = 'List';
                    $html = Itsp_Widgets:: is_frontend_event_display_guten($args, $attributes);
                } else if ($lyttyp == 'Slider') {
                    $attributes['type'] = 'Slider';
                    $html = Itsp_Widgets:: is_frontend_event_display_guten($args, $attributes);
                } else if ($lyttyp == 'Calender') {
                    $attributes['type'] = 'Calender';
                    $html = Itsp_Widgets:: is_frontend_event_display_guten($args, $attributes);
                }
            } else if ($ifmtype == 'offer' && $indmul == "multiple") {
                $args['widget_id'] = rand();
                if ($lyttyp == 'Grid') {
                    $attributes['type'] = 'Grid';
                    $html = Itsp_Widgets:: is_frontend_promotion_display_guten($args, $attributes);
                } else if ($lyttyp == 'List') {
                    $attributes['type'] = 'List';
                    $html = Itsp_Widgets:: is_frontend_promotion_display_guten($args, $attributes);
                } else if ($lyttyp == 'Slider') {
                    $attributes['type'] = 'Slider';
                    $html = Itsp_Widgets:: is_frontend_promotion_display_guten($args, $attributes);
                }
            } else if ($ifmtype == 'review' && $indmul == "multiple") {
                $args['widget_id'] = rand();
                $instance['brandkiton'] = ($attributes['brandkiton'] == true) ? 'on' : false;
                 if ($lyttyp == 'Grid') {
                    $attributes['reviewlayout'] = 'Grid';
                    $html = Itsp_Widgets:: is_frontend_review_display_guten($args, $attributes);
                } else if ($lyttyp == 'List') {
                    $attributes['reviewlayout'] = 'List';
                    $html = Itsp_Widgets:: is_frontend_review_display_guten($args, $attributes);
                } else if ($lyttyp == 'Slider') {
                    $attributes['reviewlayout'] = 'Slider';
                    $html = Itsp_Widgets:: is_frontend_review_display_guten($args, $attributes);
                }   
            } else if (isset($attributes['selectform']) && $attributes['selectform'] != '') {
                 $obj = new Itsp_Utility();
                if (isset($attributes['brandkiton']) && $attributes['brandkiton'] != 'on') {
                    $brandkit = $obj->brandkit();
                    $brandkit_colorpalette = Itsp_Widgets::remove_hash_code((array) $brandkit['colorPalette']);
                    $brandkit_fontinfo = (array) $brandkit['fontInfo'];
                    $brandkit_buttoninfo = (array) $brandkit['buttonInfo'];
                    $brandkit_forminfo = (array) $brandkit['formInfo'];
                } else {
                    $brandkit = $obj->staticbrandkit($attributes);
                    $brandkit_colorpalette = $brandkit['brandkit_colorpalette'];
                    $brandkit_fontinfo = $brandkit['brandkit_fontinfo'];
                    $brandkit_buttoninfo = $brandkit['brandkit_buttoninfo'];
                    $brandkit_forminfo = $brandkit['brandkit_forminfo'];
                }
                $dyntype  = ($ifmtype == 'review')?$attributes['moderate']:(($ifmtype == 'survey')?$attributes['survey_type']:'undefined');
                    $unqid = md5(uniqid(rand(), true)) . $ebdid;
                    $ebdid = trim(str_replace("_default_$ebdid", " ", $ebdid));
                    ob_start();
                    include( ITSP_INTEGRATION__PLUGIN_DIR . 'templates/embeddeds/forms.php');
                    $html = ob_get_clean();
                   // $html = '<div style="text-align:center;" id="e078f705-2c69-45d8-bead-a4ed761960f9" data-url=https://app.intellasphere.com data-type =offer data-id=64c0a541e8ac74146aad0613 data-appId=64d1f1eae8ac74146aade803 data-dynamicType=null data-bgColor=fff data-txtColor=4a4c54 data-textColor=fff data-font=Arial data-btn=2780cb data-btText=fff data-primaryColor=2780cb data-secondaryColor=3c3c3d data-secondaryBgColor=f3f3f3 data-secondaryTxtColor=9a9a9c data-secondaryBtColor=53a318 data-secondaryBtnTxtColor=fff data-isBorderEnable=true data-borderWidth=Thin data-borderColor=2780cb data-warningColor=fa4700 data-fontWeight=600 data-lineHeight=1.4 data-h1=21px data-h2=18px data-h3=16px data-h4=12px data-h5=10px data-h6=8px data-p=14px data-buttonStyle=RECTANGLE data-buttonRadius=4px data-buttonSize=MEDIUM data-formBorderStyle=BORDER_ALL data-formInputStyle=RECTANGLE data-formInputRadius=4px data-style=Embed data-layout=Single_Column data-design=Design_1 data-align=embed data-timer=0 data-transparentBg=false data-width=100% data-height=100% data-maxwidth=400px data-maxheight=100% data-hideTitle=false data-hideCompanyName=false data-hideCompanyLogo=true data-overrideBrandKit=false data-readOnly=false><script>setTimeout(function() {createEmbedWidget("e078f705-2c69-45d8-bead-a4ed761960f9")},500)</script></div> <script src="https://app.intellasphere.com/ui/scripts/embed.js" > </script>';

            } else {
                $html = "Please Select Engagement";
            }
            return $html;
        }
        
        /**
         * Load Brand kit
         * @param type $widget
         * @param type $widget_type
         * @return array
         */
        public function is_commmon_block($widget, $widget_type) {
            $brandkit = Itsp_Utility::brandkit();
            $common_ui = array(
                'editor_script' => $widget_type,
                'render_callback' => array($this, 'is_common_front_end'),
                'attributes' => array(
                    'brandkiton' => array(
                        'type' => 'boolean',
                        'default' => false,
                    ),
                    'pricolor' => array(
                        'type' => 'string',
                        'default' => isset($brandkit['colorPalette']->primaryColor) ? $brandkit['colorPalette']->primaryColor : '',
                    ),
                    'seccolor' => array(
                        'type' => 'string',
                        'default' => isset($brandkit['colorPalette']->secondaryColor) ? $brandkit['colorPalette']->secondaryColor : '',
                    ),
                    'pribgcolor' => array(
                        'type' => 'string',
                        'default' => isset($brandkit['colorPalette']->primaryBackgroundColor) ? $brandkit['colorPalette']->primaryBackgroundColor : '',
                    ),
                    'secbgcolor' => array(
                        'type' => 'string',
                        'default' => isset($brandkit['colorPalette']->secondaryBackgroundColor) ? $brandkit['colorPalette']->secondaryBackgroundColor : '',
                    ),
                    'pritextcolor' => array(
                        'type' => 'string',
                        'default' => isset($brandkit['colorPalette']->primaryTextColor) ? $brandkit['colorPalette']->primaryTextColor : '',
                    ),
                    'pritextcolor' => array(
                        'type' => 'string',
                        'default' => isset($brandkit['colorPalette']->secondaryTextColor) ? $brandkit['colorPalette']->secondaryTextColor : '',
                    ),
                    'txtcolor' => array(
                        'type' => 'string',
                        'default' => isset($brandkit['colorPalette']->textColor) ? $brandkit['colorPalette']->textColor : '',
                    ),
                    'btbgcolor' => array(
                        'type' => 'string',
                        'default' => isset($brandkit['colorPalette']->buttonBackgroundColor) ? $brandkit['colorPalette']->buttonBackgroundColor : '',
                    ),
                    'bttxtcolor' => array(
                        'type' => 'string',
                        'default' => isset($brandkit['colorPalette']->buttonTextColor) ? $brandkit['colorPalette']->buttonTextColor : '',
                    ),
                    'secbtbgcolor' => array(
                        'type' => 'string',
                        'default' => isset($brandkit['colorPalette']->secondaryButtonBackgroundColor) ? $brandkit['colorPalette']->secondaryButtonBackgroundColor : '',
                    ),
                    'secbttxtcolor' => array(
                        'type' => 'string',
                        'default' => isset($brandkit['colorPalette']->secondaryButtonTextColor) ? $brandkit['colorPalette']->secondaryButtonTextColor : '',
                    ),
                    'borcolor' => array(
                        'type' => 'string',
                        'default' => isset($brandkit['colorPalette']->borderColor) ? $brandkit['colorPalette']->borderColor : '',
                    ),
                    'warningColor' => array(
                        'type' => 'string',
                        'default' => isset($brandkit['colorPalette']->warningColor) ? $brandkit['colorPalette']->warningColor : '',
                    ),
                    'width' => array(
                        'type' => 'string',
                        'default' => '400px'
                    ),
                    'height' => array(
                        'type' => 'string',
                        'default' => '0px'
                    ),
                    'maxwidth' => array(
                        'type' => 'string',
                        'default' => '400px'
                    ),
                    'maxheight' => array(
                        'type' => 'string',
                        'default' => '0px'
                    ),
                    'design_type' => array(
                        'type' => 'string',
                        'default' => 'Design_1',
                    ),
                    'layout_type' => array(
                        'type' => 'string',
                        'default' => 'Grid',
                    ),
                    'nbrofcolumn' => array(
                        'type' => 'string',
                        'default' => '25',
                    ),
                    'lytlimit' => array(
                        'type' => 'string',
                        'default' => '5',
                    ),
                    'txtfdstyle' => array(
                        'type' => 'string',
                        'default' => isset($brandkit['formInfo']->borderStyle) ? $brandkit['formInfo']->borderStyle : 'BORDER_ALL',
                    ),
                    'fntfamily' => array(
                        'type' => 'string',
                        'default' => isset($brandkit['fontInfo']->fontFamily) ? $brandkit['fontInfo']->fontFamily : 'proxima-nova',
                    ),
                    'fntweight' => array(
                        'type' => 'string',
                        'default' => isset($brandkit['fontInfo']->fontWeight) ? $brandkit['fontInfo']->fontWeight : '600',
                    ),
                    'linheight' => array(
                        'type' => 'string',
                        'default' => isset($brandkit['fontInfo']->lineHeight) ? $brandkit['fontInfo']->lineHeight : '1.4',
                    ),
                    'h1' => array(
                        'type' => 'string',
                        'default' => isset($brandkit['fontInfo']->h1) ? $brandkit['fontInfo']->h1 : '21px',
                    ),
                    'h2' => array(
                        'type' => 'string',
                        'default' => isset($brandkit['fontInfo']->h2) ? $brandkit['fontInfo']->h2 : '18px',
                    ),
                    'h3' => array(
                        'type' => 'string',
                        'default' => isset($brandkit['fontInfo']->h3) ? $brandkit['fontInfo']->h3 : '16px',
                    ),
                    'h4' => array(
                        'type' => 'string',
                        'default' => isset($brandkit['fontInfo']->h4) ? $brandkit['fontInfo']->h4 : '12px',
                    ), 
                    'h5' => array(
                        'type' => 'string',
                        'default' => isset($brandkit['fontInfo']->h5) ? $brandkit['fontInfo']->h5 : '10px',
                    ), 
                    'h6' => array(
                        'type' => 'string',
                        'default' => isset($brandkit['fontInfo']->h6) ? $brandkit['fontInfo']->h6 : '8px',
                    ),
                    'ptag' => array(
                        'type' => 'string',
                        'default' => isset($brandkit['fontInfo']->p) ? $brandkit['fontInfo']->p : '8px',
                    ),
                    'trxtfdradius'=> array(
                        'type' => 'string',
                        //'default' => isset($brandkit['fontInfo']->p) ? $brandkit['fontInfo']->p : '8px',
                    ),
                    'engage_type' => array(
                        'type' => 'string',
                        'default' => 'FORM_CONTACTUS',
                    ),
                    'selectform' => array(
                        'type' => 'string',
                    ),
                    'hide_title' => array(
                        'type' => 'boolean',
                        'default' => true,
                    ),
                    'transparent_background' => array(
                        'type' => 'boolean',
                        'default' => true,
                    ),
                    'border_enable_disable' => array(
                        'type' => 'boolean',
                        'default' => true,
                    ),
                    'hide_title' => array(
                        'type' => 'boolean',
                        'default' => true,
                    ),
                    'hide_company' => array(
                        'type' => 'boolean',
                        'default' => true,
                    ),
                    'hide_logo' => array(
                        'type' => 'boolean',
                        'default' => true,
                    ),
                    'borwidth' => array(
                        'type' => 'string',
                        'default' => isset($brandkit['colorPalette']->borderWidth) ? $brandkit['colorPalette']->borderWidth : '',
                    ),
                    'btnstyle' => array(
                        'type' => 'string',
                        'default' => isset($brandkit['buttonInfo']->style) ? $brandkit['buttonInfo']->style : 'RECTANGLE',
                    ),
                    'btnsize' => array(
                        'type' => 'string',
                        'default' => isset($brandkit['buttonInfo']->size) ? $brandkit['buttonInfo']->size : 'MEDIUM'
                    ),
                    'btncorradius' => array(
                        'type' => 'string',
                        'default' => isset($brandkit['buttonInfo']->buttonCornerRadius) ? $brandkit['buttonInfo']->buttonCornerRadius : '',
                    ),
                    'trxtfdshape' => array(
                        'type' => 'string',
                        'default' => isset($brandkit['formInfo']->inputCornerRadius) ? trim($brandkit['formInfo']->inputCornerRadius, "#") : '4px',
                    ),
                    'hide_company_logo_name' => array(
                        'type' => 'boolean',
                        'default' => true,
                    ),
                    
                    'embedded' => array(
                        'type' => 'string',
                        'default' => '0',
                    ),
                    'widget' => array(
                        'type' => 'string',
                        'default' => substr($widget, 3),
                    ),
                    'widgettype' => array(
                        'type' => 'string',
                        'default' => $widget_type,
                    ),
                    'moderate' => array(
                        'type' => 'string',
                        'default' => 'Reviews',
                    ),
                    'survey_type' => array(
                        'type' => 'string',
                        'default' => 'Short%20Form Survey',
                    ),
                    
                    'rating' => array(
                        'type' => 'string',
                        'default' => '0',
                    ),
                    'nbrslider' => array(
                        'type' => 'string',
                        'default' => '1',
                    ),
                    'override_rating' => array(
                        'type' => 'boolean',
                        'default' => false,
                    ),
                    'widget_dynamic_type' => array(
                        'type' => 'string',
                        'default' => 'null',
                    ),
                    'banner_data_align' => array(
                        'type' => 'string',
                        'default' => 'Top',
                    ),
                    'formreview_type' => array(
                        'type' => 'string',
                        'default' => 'Slider',
                    ),
                    'design_type' => array(
                        'type' => 'string',
                        'default' => 'filled',
                    ),
                    'trxtfdshape' => array(
                        'type' => 'string',
                        'default' => isset($brandkit['formInfo']->inputStyle) ? $brandkit['formInfo']->inputStyle : '',
                    ),
                    'individual_multiple' => array(
                        'type' => 'string',
                        'default' => 'individual',
                    ),
                     'block_id' => array(
                        'type' => 'string',
                    ),
                    
                ),
                
            );
            return $common_ui;
        }

        /**
         * Common Blocks
         * @return type
         */
        public function common_engage() {
            return array(
                'is/engage' => "FORM_CONTACTUS"
            );
        }
        /*
         * Intellasphere Block Category
         */
        public function is_block_category($categories, $post) {
            return array_merge(
                    $categories, array(
                array(
                    'slug' => 'is-blocks',
                    'title' => __('Intellasphere', 'intellasphere'),
                ),
                    )
            );
        }

    }

}
new Itsp_Gutenberg_Blocks();
