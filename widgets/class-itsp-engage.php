<?php
if (!defined('ABSPATH')) {
    exit;
}
if (!class_exists("Itsp_Engage")) {

    /**
     * IntellaSphere  Widget.
     * @author   IntellaSphere
     * @category Widgets
     * @version  1.0.0
     * @extends  WC_Widget
     */
    class Itsp_Engage extends WP_Widget {

        function __construct() {
            parent::__construct(
                    'Itsp_Engage', __('Intellasphere', 'intellasphere'),
                    array(
                        'description' => __('Display IntellaSphere Engagement', 'intellasphere')
                    )
            );
            add_action('admin_footer-widgets.php', array($this, 'upload_scripts'), 9999);
        }

        /**
         * Load Scripts for Widgets Backend
         */
        public function upload_scripts() {
            ?>
            <script>
                (function (jQuery) {
                    function initColorPicker(widget) {
                        widget.find('.irrish_color_picker').wpColorPicker({
                            change: _.throttle(function () { // For Customizer
                                jQuery(this).trigger('change');
                            }, 3000)
                        });
                    }

                    function onFormUpdate(event, widget) {
                        initColorPicker(widget);
                    }

                    jQuery(document).on('widget-added widget-updated', onFormUpdate);
                    jQuery(document).ready(function () {
                        jQuery('#widgets-right .widget:has(.irrish_color_picker)').each(function () {
                            initColorPicker(jQuery(this));
                        });
                        jQuery(document).on("click", ".brandkit_id", function () {
                            var widgetid = jQuery(this).attr('widget_id');
                            if (jQuery(this).prop("checked") == true) {
                                jQuery('.is_color_block .brandkit_colors_block').removeClass('brandkit_checked_false');
                                jQuery('.is_color_block .brandkit_colors_block').addClass('brandkit_checked_true');
                                jQuery('.brandkit_id').prop('checked', true);
                                jQuery('.' + widgetid + 'enabledisable').attr('disabled', false);
                            } else if (jQuery(this).prop("checked") == false) {
                                jQuery('.is_color_block .brandkit_colors_block').addClass('brandkit_checked_false');
                                jQuery('.is_color_block .brandkit_colors_block').removeClass('brandkit_checked_true');
                                jQuery('.brandkit_id').prop('checked', false);
                                jQuery('.' + widgetid + 'enabledisable').attr('disabled', true);
                            }
                        });

                        /**
                         * select-data Engagement
                         */
                        jQuery(document).on("click", ".engage_select_data", function () {
                            var select_id = this.id;
                            var dataid = jQuery(this).attr('data-id');
                            var get_data_select = jQuery(this).attr('data-select');
                            var selected_id = jQuery(this).attr('data-change');
                            var selected_eng_type = jQuery('#' + selected_id).val();
                            var selected_value = jQuery('#' + dataid).val();
                            var selected_value = jQuery('#engage_widget-is_engage--engagement_type').val();
                            if (get_data_select == "1") {
                                jQuery(this).hide(0);
                                jQuery('select.' + select_id).show(0);
                                jQuery('#loader_engage').show();
                                jQuery(this).attr("data-select", "0");
                                jQuery(this).empty();
                                var options = '';
                                if (select_id != "shortcode_gen_ajax-select") {
                                    options = '<option value="">Select</option>';
                                }
                                if (selected_eng_type == "FORM_REVIEW_CUSTOM") {
                                    selected_eng_type = "FORM_REVIEW";
                                    options += "<option value='no_options:NoLink'>NOLink</option>";
                                }
                                var data = {
                                    action: 'select_engage',
                                    type: selected_eng_type
                                };
                                var i = 0;
                                var firstselect = 0;
                                if (selected_value == '') {
                                    firstselect = 1;
                                }

                                if (selected_eng_type != "PROMOTIONS" && selected_eng_type != "REVIEWS") {
                                    jQuery.post(globalajax.ajaxurl, data, function (response) {
                                        var response = jQuery.parseJSON(response);
                                        if (response.results != "ServerError" && typeof (response.results.errors) == "undefined" && response.results.errors == null) {
                                            var loop = 1;
                                            jQuery.each(response.results, function (key, value) {
                                                var current = value.postId + ":" + value.title;
                                                if (selected_value == current)
                                                    options += '<option value="' + value.postId + ":" + value.title + '" selected>' + value.title + '</option>';
                                                else
                                                    options += '<option value="' + value.postId + ":" + value.title + '" >' + value.title + '</option>';
                                                loop = loop + 1;
                                            });
                                            jQuery('#' + select_id).append(options);
                                            jQuery('select.' + select_id).hide(1);
                                            jQuery('#loader_engage').hide();
                                            jQuery('#' + select_id).show(1);     
                                            
                                        } else {
                                            if (typeof (response.results.errors) != "undefined" && response.results.errors != null)
                                                options = '<option >Server Taking Too long Please Reload and Try it again</option>';
                                            else
                                                options = '<option >Please Set Engagement</option>';
                                            jQuery('#' + select_id).html(options);
                                            jQuery('select.' + select_id).hide(1);
                                            jQuery('#loader_engage').hide();
                                            jQuery('#' + select_id).show(1);
                                        }
                                    });

                                    jQuery('#widget-is_engage--engagement_type').trigger("change");

                                } else if (selected_eng_type == "PROMOTIONS") {
                                    options += '<option value="Grid:Grid">Grid</option><option value="Slider:Slider" >Slider</option>';
                                    jQuery('#' + select_id).html(options);
                                    jQuery('select.' + select_id).hide(1);
                                    jQuery('#' + select_id).show(1);
                                    jQuery('#loader_engage').hide();

                                } else if (selected_eng_type == "REVIEWS") {
                                    options += '<option value="Grid:Grid">Grid</option><option value="Slider:Slider" >Slider</option>';
                                    jQuery('#' + select_id).html(options);
                                    jQuery('select.' + select_id).hide(1);
                                    jQuery('#' + select_id).show(1);
                                    jQuery('#loader_engage').hide();

                                }
                            }
                        });

                    });

                    jQuery(document).on("change", ".widget_engagement_type", function () {
                        var data_id = jQuery(this).attr('data-id');
                        var realclick = jQuery(this).attr('engamemnethover');
                        jQuery('.' + data_id + 'radio_type').val("individual").trigger("change");
                        if (realclick == "1") {
                            jQuery('.radio_type').hide();
                            jQuery('.' + data_id + 'radio_type').val("individual");
                            jQuery('#' + data_id + "ajax-select").attr("data-select", "1");
                            jQuery("." + data_id + 'nbrofcolumn').hide();
                            jQuery("." + data_id + 'nbrslider').hide();
                            if (jQuery(this).val() == "EVENT" || jQuery(this).val() == "COUPON" || jQuery(this).val() == "FORM_REVIEW") {
                                jQuery('.radio_type').show();
                            } else {
                                jQuery('.' + data_id + 'mtlayout').val("List");

                            }

                            /* Survery and Review*/
                            if (jQuery(this).val() == "FORM_REVIEW") {
                                jQuery('#' + data_id + "survey").hide();
                                jQuery('#' + data_id + "review").show();
                            } else if (jQuery(this).val() == "SURVEY") {
                                jQuery('#' + data_id + "survey").show();
                                jQuery('#' + data_id + "review").hide();
                            } else {
                                jQuery('#' + data_id + "survey").hide();
                                jQuery('#' + data_id + "review").hide();
                            }

                            /** Banner */
                            if (jQuery(this).val() == "BANNER") {
                                jQuery("#" + data_id + "banneralignemnt").show();
                                jQuery('#' + data_id + "datawidth").hide();
                                jQuery('#' + data_id + "dataheight").hide();
                                jQuery('#' + data_id + "displaytime").show();
                                jQuery('.' + data_id + 'widget_tlh').hide();
                            } else {
                                jQuery("#" + data_id + "banneralignemnt").hide();
                                jQuery('#' + data_id + "datawidth").show();
                                jQuery('#' + data_id + "dataheight").show();
                                jQuery('#' + data_id + "displaytime").hide();
                                jQuery('.' + data_id + 'widget_tlh').show();
                            }

                            /** Default Individual */

                            /** Layout type  **/
                            var layout_type = jQuery('.' + data_id + 'radio_type').val();
                            if (layout_type == "individual") {
                                jQuery("." + data_id + 'mtlayout').hide();
                                jQuery("#" + data_id + 'lytlimit').hide();
                                jQuery("." + data_id + 'select_engage').show();
                            } else {
                                jQuery("." + data_id + 'mtlayout').show();
                                jQuery("#" + data_id + 'lytlimit').show();
                                jQuery("#" + data_id + 'datawidth').hide();
                                jQuery("#" + data_id + 'dataheight').hide();
                                jQuery("." + data_id + 'widget_tlh').hide();
                                jQuery("#" + data_id + 'review').hide();
                                jQuery("." + data_id + 'select_engage').hide();
                            }


                            /* disable select layout */

                            jQuery('#' + data_id + ' option[value="Grid:Grid"]').attr("disabled", true);
                            jQuery("." + data_id + 'mtlayout option[value="List"]').attr("disabled", false).show();
                            jQuery("." + data_id + 'mtlayout option[value="Calender:Calender"]').attr("disabled", false).show();
                            jQuery("." + data_id + 'mtlayout option[value="Grid:Grid"]').attr("disabled", false).show();
                            jQuery("." + data_id + 'mtlayout option[value="Slider:Slider"]').attr("disabled", false).show();
                            /* Disable Dropdown */
                            if (jQuery(this).val() == "COUPON") {
                                jQuery("." + data_id + 'mtlayout option[value="Calender:Calender"]').attr("disabled", true).hide();
                            } else if (jQuery(this).val() == "FORM_REVIEW") {
                                jQuery("." + data_id + 'mtlayout option[value="Calender:Calender"]').attr("disabled", true).hide();
                            } else if (jQuery(this).val() == "NEWSLETTER_SUBSCRIPTION") {
                                jQuery("." + data_id + 'mtlayout option[value="Grid:Grid"]').attr("Selected", true);
                                jQuery("." + data_id + 'mtlayout option[value="Calender:Calender"]').attr("disabled", true).hide();
                                jQuery("." + data_id + 'mtlayout option[value="List"]').attr("disabled", true).hide();
                                jQuery("." + data_id + 'mtlayout option[value="Slider:Slider"]').attr("disabled", true).hide();
                            } else if (jQuery(this).val() == "NEWSLETTER") {
                                jQuery("." + data_id + 'select_engage').hide();
                                jQuery("#" + data_id + 'lytlimit').show();
                                jQuery("." + data_id + 'nbrofcolumn').show();
                                jQuery("#" + data_id + 'datawidth').hide();
                                jQuery("#" + data_id + 'dataheight').hide();
                                jQuery("." + data_id + 'widget_tlh').hide();
                                jQuery("#" + data_id + 'review').hide();
                            }
                        }
                        jQuery(this).attr("engamemnethover", "0");
                        jQuery(".engage_select_data").trigger("click");
                        jQuery('#engage_widget-is_engage--engagement_type').val('');
                    });

                    jQuery(document).on("change", "select.engage_select_data", function () {
                        var selected = jQuery(this).children("option:selected").val();
                        var id = jQuery(this).attr('data-change');
                        jQuery("#engage_" + id).val(selected);
                    });

                    jQuery(document).on("mouseover", ".widget_engagement_type", function () {
                        jQuery(this).attr("engamemnethover", "1");

                    });

                    jQuery(document).on("change", ".typofeng", function () {
                        var realclick = jQuery(this).attr('type_of_engag_hover');
                        if (realclick == "1") {
                            var dataid = jQuery(this).attr('data-id');
                            var engament_value = jQuery('#' + dataid + 'engagement_type').val();
                            var layout_type = jQuery('#' + dataid + 'mtlayout').val();
                            jQuery("#" + dataid + 'rating').hide();
                            jQuery("#" + dataid + 'moderate').hide();
                            if (jQuery(this).val() == 'multiple') {
                                jQuery("." + dataid + 'mtlayout').show();
                                jQuery("#" + dataid + 'lytlimit').show();
                                jQuery("#" + dataid + 'datawidth').hide();
                                jQuery("#" + dataid + 'dataheight').hide();
                                jQuery("." + dataid + 'widget_tlh').hide();
                                jQuery("#" + dataid + 'review').hide();
                                jQuery("." + dataid + 'select_engage').hide();
                                if (jQuery("#" + dataid + "engagement_type").val() == "FORM_REVIEW") {
                                    jQuery("." + dataid + 'select_engage').show();
                                    jQuery("#" + dataid + 'rating').show();
                                    jQuery("#" + dataid + 'moderate').show();
                                }

                            } else {
                                jQuery("." + dataid + 'mtlayout').hide();
                                jQuery("#" + dataid + 'lytlimit').hide();
                                jQuery("#" + dataid + 'datawidth').show();
                                jQuery("#" + dataid + 'dataheight').show();
                                jQuery("." + dataid + 'widget_tlh').show();
                                jQuery("." + dataid + 'select_engage').show();
                                jQuery("." + dataid + 'nbrofcolumn').hide();
                                if (jQuery("#" + dataid + "engagement_type").val() == "FORM_REVIEW")
                                    jQuery("#" + dataid + 'review').show();
                            }
                            if (engament_value == "NEWSLETTER_SUBSCRIPTION") {
                                if (jQuery(this).val() == 'multiple') {
                                    jQuery("." + dataid + 'nbrofcolumn').show();
                                } else {
                                    jQuery("." + dataid + 'nbrofcolumn').hide();
                                }
                            } else if (jQuery(this).val() == 'multiple' && layout_type == "Grid:Grid") {
                                jQuery("." + dataid + 'nbrofcolumn').show();
                            } else {
                                jQuery("." + dataid + 'nbrofcolumn').hide();
                            }
                            if (engament_value == "FORM_REVIEW" || engament_value == "COUPON") {
                                jQuery('#' + dataid + 'mtlayout').val('List');

                            }

                        }
                        jQuery(this).attr("type_of_engag_hover", "0");

                    });
                    jQuery(document).on("change", ".is-text-shape .trxtfdshape", function () {
                        var dataid = jQuery(this).attr('data-id');
                        if (this.value == "ROUNDED") {
                            jQuery('.' + dataid).show();
                        } else {
                            jQuery('.' + dataid).hide();
                        }
                    });

                    jQuery(document).on("change", ".mtlayout", function () {
                        var realclick = jQuery(this).attr('multi_layout_hover');
                        if (realclick == "1") {
                            var dataid = jQuery(this).attr('data-id');
                            var engament_value = jQuery('#' + dataid + 'engagement_type').val();
                            if (engament_value == "FORM_CONTACTUS") {
                                jQuery("#" + dataid + 'lytlimit').hide();
                            } else {
                                jQuery("#" + dataid + 'lytlimit').show();
                            }
                            if (jQuery(this).val() == 'Grid:Grid') {
                                jQuery("." + dataid + 'nbrofcolumn').show();
                            } else {
                                jQuery("." + dataid + 'nbrofcolumn').hide();
                            }
                            if (jQuery(this).val() == 'Calender:Calender') {
                                jQuery("#" + dataid + 'lytlimit').hide();
                            } else if (jQuery(this).val() == 'individual') {
                                jQuery("#" + dataid + 'lytlimit').show();
                            }
                            if (engament_value == "FORM_REVIEW" && jQuery(this).val() == 'Slider:Slider') {
                                jQuery("." + dataid + 'nbrslider').show();
                            } else {
                                jQuery("." + dataid + 'nbrslider').hide();
                            }
                        }
                        jQuery(this).attr("multi_layout_hover", "0");
                    });


                    jQuery(document).on("mouseover", ".typofeng", function () {
                        jQuery(this).attr("type_of_engag_hover", "1");

                    });
                    jQuery(document).on("mouseover", ".mtlayout", function () {
                        jQuery(this).attr("multi_layout_hover", "1");

                    });
                    jQuery(document).on("change", ".mtlayout", function () {
                        var realclick = jQuery(this).attr('multi_layout_hover');
                        if (realclick == "1") {
                            var dataid = jQuery(this).attr('data-id');
                            var engament_value = jQuery('#' + dataid + 'engagement_type').val();

                            if (engament_value == "FORM_CONTACTUS") {
                                jQuery("#" + dataid + 'lytlimit').hide();
                            } else {
                                jQuery("#" + dataid + 'lytlimit').show();
                            }
                            if (jQuery(this).val() == 'Grid:Grid') {
                                jQuery("." + dataid + 'nbrofcolumn').show();
                            } else {
                                jQuery("." + dataid + 'nbrofcolumn').hide();
                            }

                            if (jQuery(this).val() == 'Calender:Calender') {
                                console.log('hide');
                                jQuery("#" + dataid + 'lytlimit').hide();
                            } else if (jQuery(this).val() == 'individual') {
                                jQuery("#" + dataid + 'lytlimit').show();
                                console.log('show');
                            }
                        }
                        jQuery(this).attr("multi_layout_hover", "0");
                    });
                }(jQuery));
            </script>
            <?php
        }

        /**
         *  IntellaSphere Front End Widget Display
         */
        public function widget($args, $instance) {
            $engagement_widgets = Itsp_Admin_Setting::is_get_engaments();
            $type = '';
            if (isset($instance['engagement_type']))
                $type = isset($engagement_widgets[$instance['engagement_type']]) ? $engagement_widgets[$instance['engagement_type']] : '';
            if (isset($instance['engagement_type']) && $instance['engagement_type'] == "EVENT" && $instance['typofeng'] == 'multiple') {
                $type = explode(':', $instance['mtlayout']);
                $instance['type'] = isset($type[0]) ? $type[0] : "";
                Itsp_Widgets:: is_frontend_event_display($args, $instance);
            } elseif (isset($instance['engagement_type']) && $instance['engagement_type'] == "FORM_REVIEW" && $instance['typofeng'] == 'individual') {
                $instance['widgettype'] = $instance['widgettypereview'];
                Itsp_Widgets::is_frontend_display($args, $instance, $type, '', $instance['engagement_type']);
            } elseif (isset($instance['engagement_type']) && $instance['engagement_type'] == "SURVEY") {
                $instance['widgettype'] = $instance['widgettypesurvey'];
                Itsp_Widgets::is_frontend_display($args, $instance, $type, '', $instance['engagement_type']);
            } elseif (isset($instance['engagement_type']) && $instance['engagement_type'] == "NEWSLETTER") {
                $instance['type'] = 'GRID';
                Itsp_Widgets::is_frontend_display_grid_newsletter($args, $instance);
            } elseif (isset($instance['engagement_type']) && $instance['engagement_type'] == "COUPON" && $instance['typofeng'] == 'multiple') {
                $type = explode(':', $instance['mtlayout']);
                $instance['type'] = isset($type[0]) ? $type[0] : "";
                Itsp_Widgets::is_frontend_display_promotion($args, $instance);
            } elseif (isset($instance['engagement_type']) && $instance['engagement_type'] == "FORM_REVIEW" && $instance['typofeng'] == 'multiple') {
                $type = explode(':', $instance['mtlayout']);
                $instance['reviewlayout'] = isset($type[0]) ? $type[0] : "";
                Itsp_Widgets:: is_frontend_display_reviews($args, $instance);
            } else {
                $engagement_type = isset($instance['engagement_type']) ? $instance['engagement_type'] : "";
                Itsp_Widgets::is_frontend_display($args, $instance, $type, '', $engagement_type);
            }
        }

        /**
         * 
         * @param type $instance IntellaSphere Backend Wideget Display
         */
        public function form($instance) {
            $ebdid = (isset($instance['embedcode']) && (!empty($instance['embedcode']))) ? $instance['embedcode'] : '';
            $selectid = isset($instance['selectid']) ? $instance['selectid'] : 'Select Options';
            $disable = (isset($instance['brandkiton']) && $instance['brandkiton'] == "on") ? "" : "disabled=disabled";
            $color_disable = (isset($instance['brandkiton']) && $instance['brandkiton'] == "on") ? "brandkit_checked_true" : "";
            $options = '';
            $options .= "<option value=''>$selectid</option>";
            if (!empty($selectid) && $selectid != 'Select Options') {
                $get_name = explode(':', $selectid);
                $get_name_value = isset($get_name[0]) ? $get_name[0] : '';
                $get_name_label = isset($get_name[1]) ? $get_name[1] : '';
                $options .= "<option value='$get_name_value' selected>$get_name_label</option>";
            }
            if (!empty($options)) {
                ?>
                <p>
                    <label class="engagement_type" for="<?php echo esc_attr($this->get_field_id('text')); ?>"><?php _e('Engagement Type:', 'intellashpere'); ?>  
                        <select class='widget_engagement_type get_engagement_id' id="<?php echo esc_attr($this->get_field_id('engagement_type')); ?>" name="<?php echo esc_attr($this->get_field_name('engagement_type')); ?>" type="text" data-id="<?php echo esc_attr($this->get_field_id('')); ?>">
                            <option value= 'FORM_CONTACTUS' <?php echo esc_attr((isset($instance['engagement_type']) && ($instance['engagement_type'] === 'FORM_CONTACTUS')) ? 'selected="selected"' : ""); ?> ><?php _e('Contact Us', 'intellashpere'); ?></option>
                            <option value= 'NEWSLETTER_SUBSCRIPTION' <?php echo esc_attr((isset($instance['engagement_type']) && ($instance['engagement_type'] === 'NEWSLETTER_SUBSCRIPTION')) ? 'selected="selected"' : ""); ?>><?php _e('Newsletter Subscription', 'intellashpere'); ?> </option>
                            <option value= 'NEWSLETTER' <?php echo esc_attr((isset($instance['engagement_type']) && ($instance['engagement_type'] === 'NEWSLETTER')) ? 'selected="selected"' : ""); ?>><?php _e('Newsletter', 'intellashpere'); ?></option>
                            <option value= 'EVENT' <?php echo esc_attr((isset($instance['engagement_type']) && ($instance['engagement_type'] === 'EVENT')) ? 'selected="selected"' : ""); ?>><?php _e('Events', 'intellashpere'); ?></option>
                            <option value= 'FORM_FEEDBACK' <?php echo esc_attr((isset($instance['engagement_type']) && ($instance['engagement_type'] === 'FORM_FEEDBACK')) ? 'selected="selected"' : ""); ?>><?php _e('Feedback', 'intellashpere'); ?></option>
                            <option value= 'FORM_REVIEW' <?php echo esc_attr((isset($instance['engagement_type']) && ($instance['engagement_type'] === 'FORM_REVIEW')) ? 'selected="selected"' : ""); ?>><?php _e('Review', 'intellashpere'); ?></option>
                            <option value= 'POLL' <?php echo esc_attr((isset($instance['engagement_type']) && ($instance['engagement_type'] === 'POLL')) ? 'selected="selected"' : ""); ?>><?php _e('Poll', 'intellashpere'); ?></option>
                            <option value= 'COUPON' <?php echo esc_attr((isset($instance['engagement_type']) && ($instance['engagement_type'] === 'COUPON')) ? 'selected="selected"' : ""); ?>><?php _e('Offer', 'intellashpere'); ?></option>
                            <option value= 'PROMOTERLIST_SUBSCRIPTION' <?php echo esc_attr((isset($instance['engagement_type']) && ($instance['engagement_type'] === 'PROMOTERLIST_SUBSCRIPTION')) ? 'selected="selected"' : ""); ?>><?php _e('Promoter Sign-Up', 'intellashpere'); ?></option>
                            <option value= 'SURVEY' <?php echo esc_attr((isset($instance['engagement_type']) && ($instance['engagement_type'] === 'SURVEY')) ? 'selected="selected"' : ""); ?>><?php _e('Survey', 'intellashpere'); ?></option>
                        </select>                
                    </label>

                </p>

                <?php
                $hide_show = ( isset($instance['engagement_type']) && ($instance['engagement_type'] == "EVENTS" || $instance['engagement_type'] == "BANNER" || ( isset($instance['typofeng']) && $instance['typofeng'] == "multiple") || ( isset($instance['engagement_type']) && $instance['engagement_type'] == "NEWSLETTER") ) ) ? "none" : '';
                $width_show = ( isset($instance['engagement_type']) && ($instance['engagement_type'] == "EVENTS" || $instance['engagement_type'] == "BANNER" || ( isset($instance['typofeng']) && $instance['typofeng'] == "multiple" )) || ( isset($instance['engagement_type']) && $instance['engagement_type'] == "NEWSLETTER") ) ? "none" : '';
                $max_width_show = ( isset($instance['engagement_type']) && ($instance['engagement_type'] == "EVENTS" || ( isset($instance['typofeng']) && $instance['typofeng'] == "multiple" )) ) ? "none" : '';
                $type_of_engag = ( (isset($instance['typofeng']) && $instance['typofeng'] == "individual") || (!isset($instance['typofeng']))) ? "display:none" : "display:block";
                $radio_type = ( (isset($instance['engagement_type']) && $instance['engagement_type'] == "SURVEY" ) || ( (isset($instance['engagement_type'])) && ((isset($instance['engagement_type'])) && ($instance['engagement_type'] == "PROMOTERLIST_SUBSCRIPTION" ))) || (isset($instance['engagement_type']) && ($instance['engagement_type'] == "POLL")) || ( isset($instance['engagement_type']) && ($instance['engagement_type'] == "FORM_CONTACTUS")) || ( (isset($instance['engagement_type']) ) && $instance['engagement_type'] == "BANNER") || (isset($instance['engagement_type']) && ($instance['engagement_type'] == "FORM_FEEDBACK")) || !isset($instance['engagement_type']) || ( (isset($instance['engagement_type']) ) && $instance['engagement_type'] == "NEWSLETTER_SUBSCRIPTION") || ( (isset($instance['engagement_type']) ) && $instance['engagement_type'] == "NEWSLETTER") ) ? "display:none" : "display:block";
                $slidertype = ( (isset($instance['engagement_type']) && $instance['engagement_type'] == "FORM_REVIEW" && isset($instance['mtlayout']) && $instance['mtlayout'] == "Slider:Slider") ) ? "display:block" : "display:none";
                ?>

                <div class="radio_type" style="<?php echo esc_attr($radio_type); ?>">
                    <label for="<?php echo esc_attr($this->get_field_id('text')); ?>" class="<?php echo esc_attr($this->get_field_id('layout_type')); ?>" > <?php _e(' Individual / Multiple:', 'intellashpere'); ?>   </label> <br>
                    <select name="<?php echo esc_attr($this->get_field_name('typofeng')); ?>" class="typofeng <?php echo esc_attr($this->get_field_id('radio_type')); ?>" data-id="<?php echo esc_attr($this->get_field_id('')); ?>">
                        <option value="individual" <?php echo esc_attr((isset($instance['typofeng']) && ($instance['typofeng'] === 'individual')) ? 'selected="selected"' : ""); ?> >Individual</option>
                        <option value="multiple" <?php echo esc_attr((isset($instance['typofeng']) && ($instance['typofeng'] === 'multiple')) ? 'selected="selected"' : ""); ?> >Multiple</option>
                    </select>  
                </div>
                <?php ?>
                <p class="<?php echo esc_attr($this->get_field_id('mtlayout')); ?>" style="<?php echo esc_attr($type_of_engag); ?>">
                    <label for="<?php echo esc_attr($this->get_field_id('text')); ?>" class="<?php echo esc_attr($this->get_field_id('layout_type')); ?>" > <?php _e('Layout Type:', 'intellashpere'); ?>  </label> <br>
                    <select name="<?php echo esc_attr($this->get_field_name('mtlayout')); ?>" class="layout_type mtlayout <?php echo esc_attr($this->get_field_id('mtlayout')); ?>" id="<?php echo esc_attr($this->get_field_id('mtlayout')); ?>" data-id="<?php echo esc_attr($this->get_field_id('')); ?>">
                        <option value="List" <?php echo esc_attr((isset($instance['mtlayout']) && ($instance['mtlayout'] === 'List')) ? 'selected="selected"' : ""); ?> <?php echo esc_attr((isset($instance['engagement_type']) && $instance['engagement_type'] == "NEWSLETTER_SUBSCRIPTION" ) ? 'disabled="disabled" style="display:none;" ' : ""); ?>>List</option> 
                        <option value="Grid:Grid" <?php echo esc_attr((isset($instance['mtlayout']) && ($instance['mtlayout'] === 'Grid:Grid')) ? 'selected="selected"' : ""); ?> >Grid</option>
                        <option value="Slider:Slider" <?php echo esc_attr((isset($instance['mtlayout']) && ($instance['mtlayout'] === 'Slider:Slider')) ? 'selected="selected"' : ""); ?> <?php echo esc_attr((isset($instance['engagement_type']) && $instance['engagement_type'] == "NESLETTER_SUBSCRIPTION" ) ? 'disabled="disabled" style="display:none;" ' : ""); ?> >Slider</option>
                        <option value="Calender:Calender" <?php echo esc_attr((isset($instance['mtlayout']) && ($instance['mtlayout'] === 'Calender:Calender')) ? 'selected="selected"' : ""); ?>  <?php echo esc_attr((isset($instance['engagement_type']) && ($instance['engagement_type'] != 'EVENT')) ? 'disabled="disabled" style="display:none;" ' : ""); ?> >Calender</option> 

                    </select>  
                </p>

                <p class="nbrslider <?php echo esc_attr($this->get_field_id('nbrslider')); ?>" style="<?php echo esc_attr($slidertype); ?>">
                    <label for="<?php echo esc_attr($this->get_field_id('text')); ?>" class="<?php echo esc_attr($this->get_field_id('nbrslider')); ?>" > <?php _e('Number of sliders:', 'intellashpere'); ?>   </label> <br>
                    <select name="<?php echo esc_attr($this->get_field_name('nbrslider')); ?>" class="mtlayout <?php echo esc_attr($this->get_field_id('nbrslider')); ?>" id="<?php echo esc_attr($this->get_field_id('nbrslider')); ?>" data-id="<?php echo esc_attr($this->get_field_id('')); ?>">
                        <option value="1" <?php echo esc_attr((isset($instance['nbrslider']) && ($instance['nbrslider'] === '1')) ? 'selected="selected"' : ""); ?>>1</option> 
                        <option value="2" <?php echo esc_attr((isset($instance['nbrslider']) && ($instance['nbrslider'] === '2')) ? 'selected="selected"' : ""); ?>>2</option>
                        <option value="3" <?php echo esc_attr((isset($instance['nbrslider']) && ($instance['nbrslider'] === '3')) ? 'selected="selected"' : ""); ?>>3</option>
                    </select>  
                </p>
                <?php
                $number_of_column = (isset($instance['mtlayout']) && ($instance['mtlayout'] == "Grid:Grid") && (($instance['engagement_type'] == 'EVENT') || ($instance['engagement_type'] == 'COUPON')) && ($instance['typofeng'] == "multiple" ) || (isset($instance['engagement_type']) && $instance['engagement_type'] == 'NEWSLETTER')) ? "display:block" : "display:none";
                ?>
                <p class="<?php echo esc_attr($this->get_field_id('nbrofcolumn')); ?> number_column_type" style="<?php echo esc_attr($number_of_column); ?>">
                    <label for="<?php echo esc_attr($this->get_field_id('text')); ?>" class="<?php echo esc_attr($this->get_field_id('nbrofcolumn')); ?>" > <?php _e('Number Of Column:', 'intellashpere'); ?>   </label> <br>
                    <select name="<?php echo esc_attr($this->get_field_name('nbrofcolumn')); ?>" id="<?php echo esc_attr($this->get_field_id('nbrofcolumn')); ?>">
                        <option value="25" <?php echo esc_attr((isset($instance['nbrofcolumn']) && ($instance['nbrofcolumn'] === '25')) ? 'selected="selected"' : ""); ?>>4</option>
                        <option value="33" <?php echo esc_attr((isset($instance['nbrofcolumn']) && ($instance['nbrofcolumn'] === '33')) ? 'selected="selected"' : ""); ?> >3</option>
                        <option value="49" <?php echo esc_attr((isset($instance['nbrofcolumn']) && ($instance['nbrofcolumn'] === '49')) ? 'selected="selected"' : ""); ?> >2</option>
                        <option value="100" <?php echo esc_attr((isset($instance['nbrofcolumn']) && ($instance['nbrofcolumn'] === '100')) ? 'selected="selected"' : ""); ?> >1</option>

                    </select>  
                </p>  

                <?php
                $type_of_engag = ( (isset($instance['typofeng']) && $instance['typofeng'] == "individual") || (!isset($instance['typofeng'])) || (isset($instance['mtlayout']) && $instance['mtlayout'] == "Calender:Calender") ) ? "display:none" : "display:block";
                if (isset($instance['engagement_type']) && $instance['engagement_type'] == 'NEWSLETTER')
                    $type_of_engag = "display:block";
                ?>
                <p id="<?php echo esc_attr($this->get_field_id('lytlimit')); ?>" style="<?php echo esc_attr($type_of_engag); ?>">
                    <?php _e('Limit:', 'intellashpere'); ?>  
                    <input type="number" name="<?php echo esc_attr($this->get_field_name('lytlimit')); ?>" value="<?php echo esc_attr(isset($instance['lytlimit']) ? esc_attr($instance['lytlimit']) : '5'); ?>">
                </p>
                <?php
                $eng_type = ( (isset($instance['typofeng']) && ($instance['typofeng'] == "individual")) || !isset($instance['typofeng']) || $instance['engagement_type'] == "FORM_REVIEW" ) ? "display:block" : "display:none";
                if (isset($instance['engagement_type']) && $instance['engagement_type'] == "NEWSLETTER") {
                    $eng_type = "display:none";
                }
                ?>
                <p class="<?php echo esc_attr($this->get_field_id('select_engage')); ?>" style="<?php echo esc_attr(esc_attr($eng_type)); ?>">
                    <label for="<?php echo esc_attr($this->get_field_id('text')); ?>" class="<?php echo esc_attr($this->get_field_id('select_engage_type')); ?>"> <?php _e('Select Engagement:', 'intellashpere'); ?>   </label> <br>
                    <select class='auto_timer engage_select_data' style="width:100%;" id="<?php echo esc_attr($this->get_field_id('ajax-select')); ?>" 
                            name="<?php echo esc_attr($this->get_field_name('ajax-select')); ?>" type="text" data-select="1"  data-change="<?php echo esc_attr($this->get_field_id('engagement_type')); ?>" data-id= "<?php echo esc_attr($this->get_field_id('')); ?>" data-selectid="<?php echo esc_attr('engage_' . $this->get_field_id('engagement_type')); ?>">
                                <?php print $options ?>
                    </select>  
                    <select class="<?php echo esc_attr($this->get_field_id('ajax-select')); ?>" id="loader_engage" style="display:none;width:100%;">
                        <option value="-1"></option>
                    </select> 

                </p>
                <p style="display:none;">
                    <label for="<?php echo esc_attr($this->get_field_id('selectid')); ?>"><?php _e('selectid', 'intellashpere'); ?>  </label> 
                    <input class="auto_timer selectid" id="<?php echo esc_attr('engage_' . $this->get_field_id('engagement_type')); ?>" name="<?php echo esc_attr($this->get_field_name('selectid')); ?>"   type="text" value="<?php echo esc_attr(isset($instance['selectid']) ? $instance['selectid'] : ''); ?>" />
                </p>
            <?php } else { ?>
                <label for="<?php echo esc_attr($this->get_field_id('text')); ?>">
                    <?php _e('No Lead', 'intellashpere'); ?> 
                </label>

                <?php
            }
            ?>
            <p class="embeded_dimension" style="display:<?php echo esc_attr($width_show); ?>;width: 100%;" id="<?php echo esc_attr($this->get_field_id('datamaxwidth')); ?>">
                <label for="<?php echo esc_attr($this->get_field_id('datamaxwidth')); ?>"><?php _e('Max-Width:', 'intellashpere'); ?></label> 
                <input class="auto_timer dimension_width" name="<?php echo esc_attr($this->get_field_name('datamaxwidth')); ?>"   type="text" value="<?php echo esc_attr(isset($instance['datamaxwidth']) ? $instance['datamaxwidth'] : '400px'); ?>" />
            </p>
            <p class="embeded_dimension" style="display:<?php echo esc_attr($max_width_show); ?>;width: 100%;" id="<?php echo esc_attr($this->get_field_id('datamaxheight')); ?>" >
                <label for="<?php echo esc_attr($this->get_field_id('datamaxheight')); ?>"><?php _e('Max-Height:', 'intellashpere'); ?></label> 
                <input class="auto_timer dimension_height" name="<?php echo esc_attr($this->get_field_name('datamaxheight')); ?>"   type="text" value="<?php echo esc_attr(isset($instance['datamaxheight']) ? $instance['datamaxheight'] : '0px'); ?>" />
            </p>
            <p class="embeded_dimension" style="display:<?php echo esc_attr($width_show); ?>" id="<?php echo esc_attr($this->get_field_id('datawidth')); ?>">
                <label for="<?php echo esc_attr($this->get_field_id('datawidth')); ?>"><?php _e('Width:', 'intellashpere'); ?></label> 
                <input class="auto_timer dimension_width" name="<?php echo esc_attr($this->get_field_name('datawidth')); ?>"   type="text" value="<?php echo esc_attr(isset($instance['datawidth']) ? $instance['datawidth'] : '450px'); ?>" />
            </p>
            <p class="embeded_dimension" style="display:<?php echo esc_attr($width_show); ?>" id="<?php echo esc_attr($this->get_field_id('dataheight')); ?>" >
                <label for="<?php echo esc_attr($this->get_field_id('dataheight')); ?>"><?php _e('Height:', 'intellashpere'); ?></label> 
                <input class="auto_timer dimension_height" name="<?php echo esc_attr($this->get_field_name('dataheight')); ?>"   type="text" value="<?php echo esc_attr(isset($instance['dataheight']) ? $instance['dataheight'] : '0px'); ?>" />
            </p>
            <?php
            $banner_placement = ( isset($instance['engagement_type']) && $instance['engagement_type'] == "BANNER" ) ? "block" : 'none';
            ?>
            <p  class= "displaytime" id="<?php echo esc_attr($this->get_field_id('displaytime')); ?>" style="display:<?php echo esc_attr($banner_placement); ?>"> 
                <label for="<?php echo esc_attr($this->get_field_id('text')); ?> "><?php _e('When does it dipslay?:', 'intellashpere'); ?> 
                    <select class='auto_timer' id="<?php echo esc_attr($this->get_field_id('datatimer')); ?>"
                            name="<?php echo esc_attr($this->get_field_name('datatimer')); ?>" type="text">
                        <option value="0" <?php
                        if (isset($instance['datatimer']) && ($instance['datatimer'] === '0')) {
                            echo esc_attr('selected="selected"');
                        }
                        ?>>Immediately</option>
                        <option value="5000" <?php
                        if (isset($instance['datatimer']) && ($instance['datatimer'] === '5000')) {
                            echo esc_attr('selected="selected"');
                        }
                        ?>>5 seconds delay</option>
                        <option value="10000" <?php
                        if (isset($instance['datatimer']) && ($instance['datatimer'] === '10000')) {
                            echo esc_attr('selected="selected"');
                        }
                        ?>>10 seconds delay</option>
                        <option value="40000" <?php
                        if (isset($instance['datatimer']) && ($instance['datatimer'] === '40000')) {
                            echo esc_attr('selected="selected"');
                        }
                        ?>>40 seconds delay</option>
                        <option value="60000" <?php
                        if (isset($instance['datatimer']) && ($instance['datatimer'] === '60000')) {
                            echo esc_attr('selected="selected"');
                        }
                        ?>>60 seconds delay</option>
                    </select>                
                </label>
            </p>
            <?php
            $eng_type = ( isset($instance['typofeng']) && ($instance['typofeng'] == "individual") ) ? "display:block" : "display:none";

            if (!isset($instance['typofeng'])) {
                $eng_type = "display:block";
            }

            if (isset($instance['engagement_type']) && ($instance['engagement_type'] == "NEWSLETTER")) {
                $eng_type = "display:none";
            }
            ?>
            <p class="<?php echo esc_attr($this->get_field_id('widget_tlh')); ?>" style="<?php echo esc_attr($eng_type); ?>">
                <input class="checkbox" type="checkbox" <?php isset($instance['tranbg']) ? checked($instance['tranbg'], 'on') : checked('on', 'on'); ?> id="<?php echo esc_attr($this->get_field_id('tranbg')); ?>" name="<?php echo esc_attr($this->get_field_name('tranbg')); ?>" /> 
                <label for="<?php echo esc_attr($this->get_field_id('tranbg')); ?>"><?php _e('Transparent Background', 'intellashpere'); ?> </label>
            </p>
            <p class="<?php echo esc_attr($this->get_field_id('widget_tlh')); ?>" style="display:<?php echo esc_attr($hide_show); ?>">
                <input class="checkbox" type="checkbox" <?php isset($instance['shhidtitle']) ? checked($instance['shhidtitle'], 'on') : checked('on', 'on'); ?> id="<?php echo esc_attr($this->get_field_id('shhidtitle')); ?>" name="<?php echo esc_attr($this->get_field_name('shhidtitle')); ?>" /> 
                <label for="<?php echo esc_attr($this->get_field_id('shhidtitle')); ?>"><?php _e('Hide Title', 'intellashpere'); ?> </label>
            </p>
            <p class="<?php echo esc_attr($this->get_field_id('widget_tlh')); ?>" style="display:<?php echo esc_attr($hide_show); ?>">
                <input class="checkbox" type="checkbox" <?php isset($instance['showhideb']) ? checked($instance['showhideb'], 'on') : checked('on', 'on'); ?> id="<?php echo esc_attr($this->get_field_id('showhideb')); ?>" name="<?php echo esc_attr($this->get_field_name('showhideb')); ?>" /> 
                <label for="<?php echo esc_attr($this->get_field_id('showhideb')); ?>"><?php _e('Border Enable Or Disable', 'intellashpere'); ?> </label>
            </p>
            <p class="<?php echo esc_attr($this->get_field_id('widget_tlh')); ?>" style="display:<?php echo esc_attr($hide_show); ?>">
                <input class="checkbox" type="checkbox" <?php isset($instance['showhidec']) ? checked($instance['showhidec'], 'on') : checked('on', 'on'); ?> id="<?php echo esc_attr($this->get_field_id('showhidec')); ?>" name="<?php echo esc_attr($this->get_field_name('showhidec')); ?>" /> 
                <label for="<?php echo esc_attr($this->get_field_id('showhidec')); ?>"><?php _e('Hide Logo', 'intellashpere'); ?> </label>
            </p>
            <p class="<?php echo esc_attr($this->get_field_id('widget_tlh')); ?>" style="display:<?php echo esc_attr($hide_show); ?>">
                <input class="checkbox" type="checkbox" <?php isset($instance['showhidecompany']) ? checked($instance['showhidecompany'], 'on') : checked('on', 'on'); ?> id="<?php echo esc_attr($this->get_field_id('showhidecompany')); ?>" name="<?php echo esc_attr($this->get_field_name('showhidecompany')); ?>" /> 
                <label for="<?php echo esc_attr($this->get_field_id('showhidecompany')); ?>"> <?php _e('Hide Company', 'intellashpere'); ?> </label>
            </p>
            <?php
            $review_type = ( isset($instance['engagement_type']) && $instance['engagement_type'] != 'FORM_REVIEW' || !isset($instance['engagement_type']) || $instance['typofeng'] == "multiple") ? "none" : 'block';
            $review_multiple = "none";
            $review_type = "block";
            if ((isset($instance['typofeng']) && $instance['typofeng'] == "multiple" ) && $instance['engagement_type'] == 'FORM_REVIEW') {
                $review_multiple = "block";
                $review_type = "none";
            }

            if (!isset($instance['engagement_type']) || (isset($instance['engagement_type']) && $instance['engagement_type'] != 'FORM_REVIEW')) {
                $review_type = "none";
            }
            ?>
            <p id="<?php echo esc_attr($this->get_field_id('review')); ?> " style="display:<?php print $review_type; ?>">
                <label class="review_type" for="<?php echo esc_attr($this->get_field_id('text')); ?>"> <?php _e('Review Type:', 'intellashpere'); ?>  
                    <select class='auto_timer <?php echo esc_attr($this->get_field_id('enabledisable')); ?>' 
                            name="<?php echo esc_attr($this->get_field_name('widgettypereview')); ?>" type="text">
                        <option value= 'Review%20Form' <?php
                        if (isset($instance['widgettypereview']) && ($instance['widgettypereview'] === 'Review%20Form')) {
                            echo esc_attr('selected="selected"');
                        }
                        ?>>Review Form</option>
                        <option value= 'Reviews'<?php
                        if (isset($instance['widgettypereview']) && ($instance['widgettypereview'] === 'Reviews')) {
                            echo esc_attr('selected="selected"');
                        }
                        ?>>Reviews</option>

                    </select>

                </label>
            </p>
            <?php ?>
            <p id="<?php echo esc_attr($this->get_field_id('rating')); ?>" style="display:<?php print $review_multiple; ?>">
                <label class="review_rating" for="<?php echo esc_attr($this->get_field_id('text')); ?>"> <?php _e('Rating:', 'intellashpere'); ?>   
                    <select class='auto_timer <?php echo esc_attr($this->get_field_id('enabledisable')); ?>' 
                            name="<?php echo esc_attr($this->get_field_name('rating')); ?>" type="text">
                        <option value="0" <?php
                        if (isset($instance['rating']) && ($instance['rating'] === '0')) {
                            echo esc_attr('selected="selected"');
                        }
                        ?>
                                >All Ratings</option>
                        <option value="7"
                        <?php
                        if (isset($instance['rating']) && ($instance['rating'] === '7')) {
                            echo esc_attr('selected="selected"');
                        }
                        ?>      
                                >Positive (4 & 5 star)</option>
                        <option value="6"
                        <?php
                        if (isset($instance['rating']) && ($instance['rating'] === '6')) {
                            echo esc_attr('selected="selected"');
                        }
                        ?>        

                                >Negative (1 & 2 star)</option>
                        <option value="1"

                                <?php
                                if (isset($instance['rating']) && ($instance['rating'] === '1')) {
                                    echo esc_attr('selected="selected"');
                                }
                                ?>          

                                >1 star</option>
                        <option value="2"
                        <?php
                        if (isset($instance['rating']) && ($instance['rating'] === '2')) {
                            echo esc_attr('selected="selected"');
                        }
                        ?>   

                                >2 star</option>
                        <option value="3"
                        <?php
                        if (isset($instance['rating']) && ($instance['rating'] === '3')) {
                            echo esc_attr('selected="selected"');
                        }
                        ?>     

                                >3 star</option>
                        <option value="4"
                        <?php
                        if (isset($instance['rating']) && ($instance['rating'] === '4')) {
                            echo esc_attr('selected="selected"');
                        }
                        ?>>4 star</option>
                        <option value="5"
                        <?php
                        if (isset($instance['rating']) && ($instance['rating'] === '5')) {
                            echo esc_attr('selected="selected"');
                        }
                        ?>>5 star</option>
                    </select>

                </label>
            </p>
            <p id="<?php echo esc_attr($this->get_field_id('moderate')); ?>" style="display:<?php echo esc_attr($review_multiple); ?>">
                <input class="checkbox" type="checkbox" <?php isset($instance['moderate']) ? checked($instance['moderate'], 'on') : ''; ?> data-id="<?php echo esc_attr($this->get_field_id('moderate')); ?>" name="<?php echo esc_attr($this->get_field_name('moderate')); ?>"   id="<?php echo esc_attr($this->get_field_id('moderate-check-button')); ?>" widget_id="<?php echo esc_attr($this->get_field_id('')); ?>"/> <label for="<?php echo esc_attr($this->get_field_id('text')); ?>"><?php _e('Rating', 'intellashpere'); ?></label>
            </p>
            <?php
            $survey_type = ( isset($instance['engagement_type']) && $instance['engagement_type'] != 'SURVEY' || !isset($instance['engagement_type']) ) ? "none" : 'block';
            ?>
            <p id="<?php echo esc_attr($this->get_field_id('survey')); ?>" style="display:<?php print $survey_type; ?>">
                <label for="<?php echo esc_attr($this->get_field_id('text')); ?>"><?php _e('Survey Type:', 'intellashpere'); ?> 
                    <select class='auto_timer' 
                            name="<?php echo esc_attr($this->get_field_name('widgettypesurvey')); ?>" type="text">
                        <option value= 'Short%20Form Survey'<?php
                        if (isset($instance['widgettypesurvey']) && ($instance['widgettypesurvey'] === 'Short%20Form Survey')) {
                            echo esc_attr('selected="selected"');
                        }
                        ?>>Short Form Survey</option>
                        <option value= 'Long%20Form Survey' <?php
                        if (isset($instance['widgettypesurvey']) && ($instance['widgettypesurvey'] === 'Long%20Form Survey')) {
                            echo esc_attr('selected="selected"');
                        }
                        ?>>Long Form Survey</option>
                    </select>

                </label>
            </p>
            <p id="<?php echo esc_attr($this->get_field_id('banneralignemnt')); ?>" style="display:<?php echo esc_attr($banner_placement); ?>">
                <label for="<?php echo esc_attr($this->get_field_id('banneralignemnt')); ?>"><?php _e('Placement', 'intellashpere'); ?>  
                    <select class='auto_timer' 
                            name="<?php echo esc_attr($this->get_field_name('banneralignemnt')); ?>" type="text">
                        <option value= 'Top'<?php
                        if (isset($instance['banneralignemnt']) && ($instance['banneralignemnt'] === 'Top')) {
                            echo esc_attr('selected="selected"');
                        }
                        ?>>Top</option>
                        <option value= 'Bottom' <?php
                        if (isset($instance['banneralignemnt']) && ($instance['banneralignemnt'] === 'Bottom')) {
                            echo esc_attr('selected="selected"');
                        }
                        ?>>Bottom</option>
                    </select>

                </label>
            </p>
            <?php
            $review_type = ( isset($instance['engagement_type']) && $instance['engagement_type'] == "FORM_REVIEW_CUSTOM" ) ? "block" : 'none';
            $obj = new Itsp_Utility();
            $brandkit = $obj->brandkit();
            $brandkit_colorpalette = (array) $brandkit['colorPalette'];
            $brandkit_fontinfo = (array) $brandkit['fontInfo'];
            $brandkit_buttoninfo = (array) $brandkit['buttonInfo'];
            ?>
            <p id="<?php echo esc_attr($this->get_field_id('review_type')); ?>" style="display:<?php echo esc_attr($review_type); ?>">
                <label for="<?php echo esc_attr($this->get_field_id('review_type')); ?>"><?php _e('Layout', 'intellashpere'); ?>  
                    <select class='auto_timer' 
                            name="<?php echo esc_attr($this->get_field_name('reviewlayout')); ?>" type="text">
                        <option value= 'Slider'<?php
                        if (isset($instance['reviewlayout']) && ($instance['reviewlayout'] === 'Slider')) {
                            echo esc_attr('selected="selected"');
                        }
                        ?>>Slider</option>
                        <option value= 'List' <?php
                        if (isset($instance['reviewlayout']) && ($instance['reviewlayout'] === 'List')) {
                            echo esc_attr('selected="selected"');
                        }
                        ?>>List</option>
                    </select>

                </label>
            </p>

            <p style="display:none">
                <label for="<?php echo esc_attr($this->get_field_id('text')); ?>"><?php _e('Type:', 'intellashpere'); ?>   
                    <select class='color_setting_changed' id="<?php echo esc_attr($this->get_field_id('datachanged')); ?>"
                            name="<?php echo esc_attr($this->get_field_name('datachanged')); ?>" type="text">
                        <option value= 'changed'>changed</option>
                    </select>                
                </label>
            </p>
            <p id="<?php echo esc_attr($this->get_field_id('customize-check')); ?>" class="customize-id"  data-id="<?php echo esc_attr($this->get_field_id('customize')); ?>" name="<?php echo esc_attr($this->get_field_name('customize')); ?>" main-id="<?php echo esc_attr($this->get_field_id('')); ?>">
                <label for="<?php echo esc_attr($this->get_field_id('customize')); ?>"><a href="javascript: var obj = jQuery('#<?php echo esc_attr($this->get_field_id('advanced')); ?>'); if(!obj.is(':visible')) {var a = obj.show('slow');} else {var a = obj.hide('slow');}  "><?php _e('Advanced Options', 'intellashpere'); ?></a></label>
            </p>
            <div class="<?php echo esc_attr($this->get_field_id('customize')); ?>" id="<?php echo esc_attr($this->get_field_id('advanced')); ?>" style="display:none;">
                <div class="tabs">
                    <a id="color_irrish_picker" href="javascript: var obj = jQuery('#<?php echo esc_attr($this->get_field_id('color')); ?>'); if(!obj.is(':visible')) {var a = obj.show('slow');}  var obj2=jQuery('#<?php echo esc_attr($this->get_field_id('style')); ?>'); var a = obj2.hide('slow');   var obj2=jQuery('#<?php echo esc_attr($this->get_field_id('buttons')); ?>'); var a = obj2.hide('slow'); var obj2=jQuery('#<?php echo esc_attr($this->get_field_id('forms')); ?>'); var a = obj2.hide('forms'); var element = document.getElementById('<?php echo esc_attr($this->get_field_id('tab2')); ?>'); element.classList.add('inactive'); var element = document.getElementById('<?php echo esc_attr($this->get_field_id('tab1')); ?>'); element.classList.remove('inactive'); var element = document.getElementById('<?php echo esc_attr($this->get_field_id('tab3')); ?>'); element.classList.add('inactive');  var element = document.getElementById('<?php echo esc_attr($this->get_field_id('tab4')); ?>'); element.classList.add('inactive');">
                        <div class="tab-links"><div class="tab-links-text" id="<?php echo esc_attr($this->get_field_id('tab1')); ?>"><span class="<?php echo esc_attr($this->get_field_id('customize-color')); ?>" id="<?php echo esc_attr($this->get_field_id('customize-color')); ?>" class="customize-color"  data-id="<?php echo esc_attr($this->get_field_id('color')); ?>" name="<?php echo esc_attr($this->get_field_name('color')); ?>"   id="<?php echo esc_attr($this->get_field_id('color')); ?>" main-id="<?php echo esc_attr($this->get_field_id('')); ?>" >
                                    <label for="<?php echo esc_attr($this->get_field_id('color')); ?>"><?php _e('Color', 'intellashpere'); ?> </label>
                                </span></div></div></a>
                    <a href="javascript: var obj2=jQuery('#<?php echo esc_attr($this->get_field_id('color')); ?>'); var a = obj2.hide('slow'); 
                       var obj2=jQuery('#<?php echo esc_attr($this->get_field_id('buttons')); ?>'); var a = obj2.hide('slow'); 
                       var obj2=jQuery('#<?php echo esc_attr($this->get_field_id('forms')); ?>'); var a = obj2.hide('forms');  
                       var obj2=jQuery('#<?php echo esc_attr($this->get_field_id('style')); ?>'); var a = obj2.show('style'); 
                       var element = document.getElementById('<?php echo esc_attr($this->get_field_id('tab1')); ?>'); 
                       element.classList.add('inactive'); 
                       var element = document.getElementById('<?php echo esc_attr($this->get_field_id('tab3')); ?>'); 
                       element.classList.add('inactive'); 
                       var element = document.getElementById('<?php echo esc_attr($this->get_field_id('tab4')); ?>'); 
                       element.classList.add('inactive'); 
                       var element = document.getElementById('<?php echo esc_attr($this->get_field_id('tab2')); ?>');
                       element.classList.remove('inactive');" class="widget-style" main-id="<?php echo esc_attr($this->get_field_id('')); ?>">
                        <div class="tab-links"><div  class="tab-links-text"  id="<?php echo esc_attr($this->get_field_id('tab2')); ?>"><span  class="<?php echo esc_attr($this->get_field_id('style')); ?>" data-id="<?php echo esc_attr($this->get_field_id('style')); ?>"   >
                                    <label for="<?php echo esc_attr($this->get_field_id('style')); ?>"><?php _e('Fonts', 'intellashpere'); ?>  </label>
                                </span>
                            </div>
                        </div>
                    </a>
                    <a href="javascript: var obj = jQuery('#<?php echo esc_attr($this->get_field_id('buttons')); ?>'); if(!obj.is(':visible')) {var a = obj.show('slow');}  var obj2=jQuery('#<?php echo esc_attr($this->get_field_id('color')); ?>'); var a = obj2.hide('hide');  var obj2=jQuery('#<?php echo esc_attr($this->get_field_id('forms')); ?>'); var a = obj2.hide('hide');  var obj2=jQuery('#<?php echo esc_attr($this->get_field_id('style')); ?>'); var a = obj2.hide('slow');  var element = document.getElementById('<?php echo esc_attr($this->get_field_id('tab1')); ?>'); element.classList.add('inactive'); var element = document.getElementById('<?php echo esc_attr($this->get_field_id('tab2')); ?>'); element.classList.add('inactive'); var element = document.getElementById('<?php echo esc_attr($this->get_field_id('tab3')); ?>'); element.classList.remove('inactive');  var element = document.getElementById('<?php echo esc_attr($this->get_field_id('tab4')); ?>'); element.classList.add('inactive');">
                        <div class="tab-links"><div class="tab-links-text" id="<?php echo esc_attr($this->get_field_id('tab3')); ?>">
                                <span>
                                    <label><?php _e('Buttons', 'intellashpere'); ?> </label>
                                </span></div></div></a>
                    <a href="javascript: var obj = jQuery('#<?php echo esc_attr($this->get_field_id('forms')); ?>'); if(!obj.is(':visible')) {var a = obj.show('slow');}  var obj2=jQuery('#<?php echo esc_attr($this->get_field_id('buttons')); ?>'); var a = obj2.hide('hide');  var obj2=jQuery('#<?php echo esc_attr($this->get_field_id('style')); ?>'); var a = obj2.hide('slow'); var obj2=jQuery('#<?php echo esc_attr($this->get_field_id('color')); ?>'); var a = obj2.hide('slow'); var element = document.getElementById('<?php echo esc_attr($this->get_field_id('tab1')); ?>'); element.classList.add('inactive'); var element = document.getElementById('<?php echo esc_attr($this->get_field_id('tab2')); ?>'); element.classList.add('inactive'); var element = document.getElementById('<?php echo esc_attr($this->get_field_id('tab3')); ?>'); element.classList.add('inactive');  var element = document.getElementById('<?php echo esc_attr($this->get_field_id('tab4')); ?>'); element.classList.remove('inactive');">
                        <div class="tab-links"><div class="tab-links-text" id="<?php echo esc_attr($this->get_field_id('tab4')); ?>"><span>
                                    <label><?php _e('Forms', 'intellashpere'); ?> </label>
                                </span></div></div></a>

                </div>
                <div class="is-container-color" id="<?php echo esc_attr($this->get_field_id('color')); ?>" style="display:none;">
                    <div class="is_color_block">
                        <p>
                            <input class="checkbox brandkit_id" type="checkbox" <?php isset($instance['brandkiton']) ? checked($instance['brandkiton'], 'on') : ''; ?> data-id="<?php echo esc_attr($this->get_field_id('brandkit')); ?>" name="<?php echo esc_attr($this->get_field_name('brandkiton')); ?>"   id="<?php echo esc_attr($this->get_field_id('brandkiton-button')); ?>" widget_id="<?php echo esc_attr($this->get_field_id('')); ?>"/> 
                            <label for="<?php echo esc_attr($this->get_field_id('brandkiton')); ?>"><?php _e('Override Brand kit', 'intellashpere'); ?>  </label>
                        </p>

                        <div class="<?php echo esc_attr($this->get_field_id('brandkit')); ?> brandkit_colors_block <?php print $color_disable; ?>">
                            <p>
                                <label for="<?php echo esc_attr($this->get_field_id('pricolor')); ?>"><?php _e('Primary Color:', 'intellashpere'); ?></label> 
                                <input class="auto_timer irrish_color_picker  fl-color-picker-value pricolor" id="<?php echo esc_attr($this->get_field_id('pricolor')); ?>" name="<?php echo esc_attr($this->get_field_name('pricolor')); ?>"   type="text" value="<?php echo esc_attr(isset($instance['pricolor']) ? $instance['pricolor'] : $brandkit_colorpalette['primaryColor']); ?>" />
                            </p>
                            <p>
                                <label for="<?php echo esc_attr($this->get_field_id('seccolor')); ?>"><?php _e('Secondary Color:', 'intellashpere'); ?> </label> 
                                <input class="auto_timer irrish_color_picker  fl-color-picker-value seccolor" id="<?php echo esc_attr($this->get_field_id('seccolor')); ?>" name="<?php echo esc_attr($this->get_field_name('seccolor')); ?>"   type="text" value="<?php echo esc_attr(isset($instance['seccolor']) ? $instance['seccolor'] : $brandkit_colorpalette['secondaryColor']); ?>" />
                            </p>
                            <p>
                                <label for="<?php echo esc_attr($this->get_field_id('pribgcolor')); ?>"><?php _e('Primary Background Color:', 'intellashpere'); ?></label> 
                                <input class="auto_timer irrish_color_picker  fl-color-picker-value pribgcolor" id="<?php echo esc_attr($this->get_field_id('pribgcolor')); ?>" name="<?php echo esc_attr($this->get_field_name('pribgcolor')); ?>"   type="text" value="<?php echo esc_attr(isset($instance['pribgcolor']) ? $instance['pribgcolor'] : $brandkit_colorpalette['primaryBackgroundColor']); ?>" />
                            </p>
                            <p>
                                <label for="<?php echo esc_attr($this->get_field_id('secbgcolor')); ?>"><?php _e('Secondary Background Color:', 'intellashpere'); ?></label> 
                                <input class="auto_timer irrish_color_picker  fl-color-picker-value secbgcolor" id="<?php echo esc_attr($this->get_field_id('secbgcolor')); ?>" name="<?php echo esc_attr($this->get_field_name('secbgcolor')); ?>"   type="text" value="<?php echo esc_attr(isset($instance['secbgcolor']) ? $instance['secbgcolor'] : $brandkit_colorpalette['secondaryBackgroundColor']); ?>" />
                            </p>
                            <p>
                                <label for="<?php echo esc_attr($this->get_field_id('pritextcolor')); ?>"><?php _e('Primary Text Color:', 'intellashpere'); ?></label> 
                                <input class="auto_timer irrish_color_picker  fl-color-picker-value pritextcolor" id="<?php echo esc_attr($this->get_field_id('pritextcolor')); ?>" name="<?php echo esc_attr($this->get_field_name('pritextcolor')); ?>"   type="text" value="<?php echo esc_attr(isset($instance['pritextcolor']) ? $instance['pritextcolor'] : $brandkit_colorpalette['primaryTextColor']); ?>" />
                            </p>
                            <p>
                                <label for="<?php echo esc_attr($this->get_field_id('sectextcolor')); ?>"><?php _e('Secondary Text Color:', 'intellashpere'); ?></label> 
                                <input class="auto_timer irrish_color_picker  fl-color-picker-value sectextcolor" id="<?php echo esc_attr($this->get_field_id('sectextcolor')); ?>" name="<?php echo esc_attr($this->get_field_name('sectextcolor')); ?>"   type="text" value="<?php echo esc_attr(isset($instance['sectextcolor']) ? $instance['sectextcolor'] : $brandkit_colorpalette['secondaryTextColor'] ); ?>" />
                            </p>
                            <p>
                                <label for="<?php echo esc_attr($this->get_field_id('txtcolor')); ?>"><?php _e('Text Color:', 'intellashpere'); ?></label> 
                                <input class="auto_timer irrish_color_picker txtcolor" id="<?php echo esc_attr($this->get_field_id('txtcolor')); ?>" name="<?php echo esc_attr($this->get_field_name('txtcolor')); ?>"   type="text" value="<?php echo esc_attr(isset($instance['txtcolor']) ? $instance['txtcolor'] : $brandkit_colorpalette['textColor'] ); ?>" />
                            </p>
                            <p>
                                <label for="<?php echo esc_attr($this->get_field_id('btbgcolor')); ?>"><?php _e('Button Background Color:', 'intellashpere'); ?></label> 
                                <input class="auto_timer irrish_color_picker btbgcolor" id="<?php echo esc_attr($this->get_field_id('btbgcolor')); ?>" name="<?php echo esc_attr($this->get_field_name('btbgcolor')); ?>"   type="text" value="<?php echo esc_attr(isset($instance['btbgcolor']) ? $instance['btbgcolor'] : $brandkit_colorpalette['buttonBackgroundColor'] ); ?>" />
                            </p>
                            <p>
                                <label for="<?php echo esc_attr($this->get_field_id('bttxtcolor')); ?>"><?php _e('Button Text:', 'intellashpere'); ?></label> 
                                <input class="auto_timer irrish_color_picker bttxtcolor" id="<?php echo esc_attr($this->get_field_id('bttxtcolor')); ?>" name="<?php echo esc_attr($this->get_field_name('bttxtcolor')); ?>"   type="text" value="<?php echo esc_attr(isset($instance['bttxtcolor']) ? $instance['bttxtcolor'] : $brandkit_colorpalette['buttonTextColor']); ?>" />
                            </p>

                            <p>
                                <label for="<?php echo esc_attr($this->get_field_id('secbtbgcolor')); ?>"><?php _e('Secondary Button Background Color:', 'intellashpere'); ?></label> 
                                <input class="auto_timer irrish_color_picker secbtbgcolor" id="<?php echo esc_attr($this->get_field_id('secbtbgcolor')); ?>" name="<?php echo esc_attr($this->get_field_name('secbtbgcolor')); ?>"   type="text" value="<?php echo esc_attr(isset($instance['secbtbgcolor']) ? $instance['secbtbgcolor'] : $brandkit_colorpalette['secondaryButtonBackgroundColor'] ); ?>" />
                            </p>

                            <p>
                                <label for="<?php echo esc_attr($this->get_field_id('secbttxtcolor')); ?>"><?php _e('Secondary Button Text:', 'intellashpere'); ?></label> 
                                <input class="auto_timer irrish_color_picker secbttxtcolor" id="<?php echo esc_attr($this->get_field_id('secbttxtcolor')); ?>" name="<?php echo esc_attr($this->get_field_name('secbttxtcolor')); ?>"   type="text" value="<?php echo esc_attr(isset($instance['secbttxtcolor']) ? $instance['secbttxtcolor'] : $brandkit_colorpalette['secondaryButtonTextColor']); ?>" />
                            </p>
                            <p>
                                <label for="Border Width"><?php _e('Border Width', 'intellashpere'); ?>  </label> <br>
                                <select name="<?php echo esc_attr($this->get_field_name('borwidth')); ?>" class='<?php echo esc_attr($this->get_field_id('enabledisable')); ?>'  type="select" style="width:100%;" <?php print $disable; ?>>
                                    <option value="thin" <?php
                                    if (isset($instance['borwidth']) && ($instance['borwidth'] === 'thin')) {
                                        echo esc_attr('selected="selected"');
                                    } else if (isset($brandkit_colorpalette['borderWidth']) && ($brandkit_colorpalette['borderWidth'] === 'thin')) {
                                        echo esc_attr('selected="selected"');
                                    }
                                    ?>>Thin</option>
                                    <option value="medium" <?php
                                    if (isset($instance['borwidth']) && ($instance['borwidth'] === 'medium')) {
                                        echo esc_attr('selected="selected"');
                                    } else if (isset($brandkit_colorpalette['borderWidth']) && ($brandkit_colorpalette['borderWidth'] === 'medium')) {
                                        echo esc_attr('selected="selected"');
                                    }
                                    ?>>Medium</option>
                                    <option value="thick" <?php
                                    if (isset($instance['borwidth']) && ($instance['borwidth'] === 'thick')) {
                                        echo esc_attr('selected="selected"');
                                    } else if (isset($brandkit_colorpalette['borderWidth']) && ($brandkit_colorpalette['borderWidth'] === 'thick')) {
                                        echo esc_attr('selected="selected"');
                                    }
                                    ?>>Thick</option>
                                </select>
                            </p>
                            <p>
                                <label for="<?php echo esc_attr($this->get_field_id('borcolor')); ?>"><?php _e('Border Color:', 'intellashpere'); ?></label> 
                                <input class="auto_timer irrish_color_picker borcolor" id="<?php echo esc_attr($this->get_field_id('borcolor')); ?>" name="<?php echo esc_attr($this->get_field_name('borcolor')); ?>"   type="text" value="<?php echo esc_attr(isset($instance['borcolor']) ? $instance['borcolor'] : $brandkit_colorpalette['borderColor']); ?>" />
                            </p>
                            <p>
                                <label for="<?php echo esc_attr($this->get_field_id('warningColor')); ?>"><?php _e('Warning Color:', 'intellashpere'); ?></label> 
                                <input class="auto_timer irrish_color_picker warningColor  fl-color-picker-value" id="<?php echo esc_attr($this->get_field_id('warningColor')); ?>" name="<?php echo esc_attr($this->get_field_name('warningColor')); ?>"   type="text" value="<?php echo esc_attr(isset($instance['warningColor']) ? $instance['warningColor'] : $brandkit_colorpalette['warningColor'] ); ?>" />
                            </p>

                        </div>
                    </div>
                </div> 
                <div class="is-container-style"  id="<?php echo esc_attr($this->get_field_id('style')); ?>" style="display:none;">
                    <div class="is-style-block">
                        <p>
                            <input class="checkbox brandkit_id" type="checkbox" <?php isset($instance['brandkiton']) ? checked($instance['brandkiton'], 'on') : ''; ?> data-id="<?php echo esc_attr($this->get_field_id('brandkit')); ?>" name="<?php echo esc_attr($this->get_field_name('brandkiton')); ?>"   id="<?php echo esc_attr($this->get_field_id('brandkiton-button')); ?>" widget_id="<?php echo esc_attr($this->get_field_id('')); ?>"/> 
                            <label for="<?php echo esc_attr($this->get_field_id('brandkiton')); ?>"><?php _e('Override Brand kit', 'intellashpere'); ?> </label>
                        </p>
                        <p>
                            <label for="<?php echo esc_attr($this->get_field_id('text')); ?>"><?php _e('Font Family', 'intellashpere'); ?>   </label>
                            <br>
                            <select class='auto_timer <?php echo esc_attr($this->get_field_id('enabledisable')); ?>' style="width:100%;" id="<?php echo esc_attr($this->get_field_id('fntfamily')); ?>"
                                    name="<?php echo esc_attr($this->get_field_name('fntfamily')); ?>" type="text" <?php print $disable; ?>>
                                <option value= 'proxima-nova' <?php
                                if (isset($instance['fntfamily']) && ($instance['fntfamily'] === 'proxima-nova')) {
                                    echo esc_attr('selected="selected"');
                                } else if (isset($brandkit_fontinfo['fontFamily']) && ($brandkit_fontinfo['fontFamily'] === 'proxima-nova')) {
                                    echo esc_attr('selected="selected"');
                                }
                                ?>>proxima</option>
                                <option value= 'Arial,Helvetica,sans-serif'  <?php
                                if (isset($instance['fntfamily']) && ($instance['fntfamily'] === 'Arial,Helvetica,sans-serif')) {
                                    echo esc_attr('selected="selected"');
                                } else if (isset($brandkit_fontinfo['fontFamily']) && ($brandkit_fontinfo['fontFamily'] === 'Arial,Helvetica,sans-serif')) {
                                    echo esc_attr('selected="selected"');
                                }
                                ?>>Arial, Helvetica, sans-serif</option>
                                <option value= 'Verdana,Geneva,sans-serif'  <?php
                                if (isset($instance['fntfamily']) && ($instance['fntfamily'] === 'Verdana,Geneva,sans-serif')) {
                                    echo esc_attr('selected="selected"');
                                } else if (isset($brandkit_fontinfo['fontFamily']) && ($brandkit_fontinfo['fontFamily'] === 'Verdana,Geneva,sans-serif')) {
                                    echo esc_attr('selected="selected"');
                                }
                                ?>>Verdana, Geneva, sans-serif</option>
                                <option value= '‘Trebuchet MS’,Helvetica,sans-serif'  <?php
                                if (isset($instance['fntfamily']) && ($instance['fntfamily'] === '‘Trebuchet MS’,Helvetica,sans-serif')) {
                                    echo esc_attr('selected="selected"');
                                } else if (isset($brandkit_fontinfo['fontFamily']) && ($brandkit_fontinfo['fontFamily'] === '‘Trebuchet MS’,Helvetica,sans-serif')) {
                                    echo esc_attr('selected="selected"');
                                }
                                ?>>‘Trebuchet MS’, Helvetica, sans-serif</option>
                                <option value= '‘Arial Black’,Gadget,sans-serif'  <?php
                                if (isset($instance['fntfamily']) && ($instance['fntfamily'] === '‘Arial Black’,Gadget,sans-serif')) {
                                    echo esc_attr('selected="selected"');
                                } else if (isset($brandkit_fontinfo['fontFamily']) && ($brandkit_fontinfo['fontFamily'] === '‘Arial Black’,Gadget,sans-serif')) {
                                    echo esc_attr('selected="selected"');
                                }
                                ?>>‘Arial Black’, Gadget, sans-serif</option>
                                <option value= 'BookmanOld Style,serif'  <?php
                                if (isset($instance['fntfamily']) && ($instance['fntfamily'] === 'BookmanOld Style,serif')) {
                                    echo esc_attr('selected="selected"');
                                } else if (isset($brandkit_fontinfo['fontFamily']) && ($brandkit_fontinfo['fontFamily'] === 'BookmanOld Style,serif')) {
                                    echo esc_attr('selected="selected"');
                                }
                                ?>>Bookman Old Style, serif</option>
                                <option value= '‘Comic Sans MS’,cursive'  <?php
                                if (isset($instance['fntfamily']) && ($instance['fntfamily'] === '‘Comic Sans MS’,cursive')) {
                                    echo esc_attr('selected="selected"');
                                } else if (isset($brandkit_fontinfo['fontFamily']) && ($brandkit_fontinfo['fontFamily'] === '‘Comic Sans MS’,cursive')) {
                                    echo esc_attr('selected="selected"');
                                }
                                ?>>‘Comic Sans MS’, cursive</option>
                                <option value= 'Courier,monospace'  <?php
                                if (isset($instance['fntfamily']) && ($instance['fntfamily'] === 'Courier,monospace')) {
                                    echo esc_attr('selected="selected"');
                                } else if (isset($brandkit_fontinfo['fontFamily']) && ($brandkit_fontinfo['fontFamily'] === 'Courier,monospace')) {
                                    echo esc_attr('selected="selected"');
                                }
                                ?>>Courier, monospace</option>
                                <option value= '‘Courier New’,Courier,monospace'  <?php
                                if (isset($instance['fntfamily']) && ($instance['fntfamily'] === '‘Courier New’,Courier,monospace')) {
                                    echo esc_attr('selected="selected"');
                                } else if (isset($brandkit_fontinfo['fontFamily']) && ($brandkit_fontinfo['fontFamily'] === '‘Courier New’,Courier,monospace')) {
                                    echo esc_attr('selected="selected"');
                                }
                                ?>>‘Courier New’, Courier, monospace</option>
                                <option value= 'Garamond,serif'  <?php
                                if (isset($instance['fntfamily']) && ($instance['fntfamily'] === 'Garamond,serif')) {
                                    echo esc_attr('selected="selected"');
                                } else if (isset($brandkit_fontinfo['fontFamily']) && ($brandkit_fontinfo['fontFamily'] === 'Garamond,serif')) {
                                    echo esc_attr('selected="selected"');
                                }
                                ?>>Garamond, serif</option>
                                <option value= 'Georgia,serif'  <?php
                                if (isset($instance['fntfamily']) && ($instance['fntfamily'] === 'Georgia,serif')) {
                                    echo esc_attr('selected="selected"');
                                } else if (isset($brandkit_fontinfo['fontFamily']) && ($brandkit_fontinfo['fontFamily'] === 'Georgia,serif')) {
                                    echo esc_attr('selected="selected"');
                                }
                                ?>>Georgia, serif</option>
                                <option value= 'Impact,Charcoal, sans-serif'  <?php
                                if (isset($instance['fntfamily']) && ($instance['fntfamily'] === 'Impact,Charcoal, sans-serif')) {
                                    echo esc_attr('selected="selected"');
                                } else if (isset($brandkit_fontinfo['fontFamily']) && ($brandkit_fontinfo['fontFamily'] === 'Impact,Charcoal, sans-serif')) {
                                    echo esc_attr('selected="selected"');
                                }
                                ?>>Impact, Charcoal, sans-serif</option>
                                <option value= 'Lucida Console,Monaco,monospace'  <?php
                                if (isset($instance['fntfamily']) && ($instance['fntfamily'] === 'Lucida Console,Monaco,monospace')) {
                                    echo esc_attr('selected="selected"');
                                } else if (isset($brandkit_fontinfo['fontFamily']) && ($brandkit_fontinfo['fontFamily'] === 'Lucida Console,Monaco,monospace')) {
                                    echo esc_attr('selected="selected"');
                                }
                                ?>>Lucida Console, Monaco, monospace</option>
                                <option value= 'Lucida Sans Unicode,‘Lucida Grande’'  <?php
                                if (isset($instance['fntfamily']) && ($instance['fntfamily'] === 'Lucida Sans Unicode,‘Lucida Grande’')) {
                                    echo esc_attr('selected="selected"');
                                } else if (isset($brandkit_fontinfo['fontFamily']) && ($brandkit_fontinfo['fontFamily'] === 'Lucida Sans Unicode,‘Lucida Grande’')) {
                                    echo esc_attr('selected="selected"');
                                }
                                ?>>Lucida Sans Unicode, ‘Lucida Grande’, sans-serif</option>
                                <option value= '‘MSSans Serif’,Geneva,sans-serif'  <?php
                                if (isset($instance['fntfamily']) && ($instance['fntfamily'] === '‘MSSans Serif’,Geneva,sans-serif')) {
                                    echo esc_attr('selected="selected"');
                                } else if (isset($brandkit_fontinfo['fontFamily']) && ($brandkit_fontinfo['fontFamily'] === '‘MSSans Serif’,Geneva,sans-serif')) {
                                    echo esc_attr('selected="selected"');
                                }
                                ?>>‘MS Sans Serif’, Geneva, sans-serif</option>
                                <option value= '‘MSSerif’,‘New York’,sans-serif'  <?php
                                if (isset($instance['fntfamily']) && ($instance['fntfamily'] === '‘MSSerif’,‘New York’,sans-serif')) {
                                    echo esc_attr('selected="selected"');
                                } else if (isset($brandkit_fontinfo['fontFamily']) && ($brandkit_fontinfo['fontFamily'] === '‘MSSerif’,‘New York’,sans-serif')) {
                                    echo esc_attr('selected="selected"');
                                }
                                ?>>‘MS Sans Serif’, Geneva, sans-serif</option>
                                <option value= '‘PalatinoLinotype’,Book Antiqua,Palatino, serif'  <?php
                                if (isset($instance['fntfamily']) && ($instance['fntfamily'] === '‘PalatinoLinotype’,Book Antiqua,Palatino, serif')) {
                                    echo esc_attr('selected="selected"');
                                } else if (isset($brandkit_fontinfo['fontFamily']) && ($brandkit_fontinfo['fontFamily'] === '‘PalatinoLinotype’,Book Antiqua,Palatino, serif')) {
                                    echo esc_attr('selected="selected"');
                                }
                                ?>>‘Palatino Linotype’, Book Antiqua, Palatino, serif</option>
                                <option value= 'Symbol,sans-serif'  <?php
                                if (isset($instance['fntfamily']) && ($instance['fntfamily'] === 'Symbol,sans-serif')) {
                                    echo esc_attr('selected="selected"');
                                } else if (isset($brandkit_fontinfo['fontFamily']) && ($brandkit_fontinfo['fontFamily'] === 'Symbol,sans-serif')) {
                                    echo esc_attr('selected="selected"');
                                }
                                ?>>Symbol, sans-serif</option>
                                <option value= 'Tahoma,Geneva,sans-serif'  <?php
                                if (isset($instance['fntfamily']) && ($instance['fntfamily'] === 'Tahoma,Geneva,sans-serif')) {
                                    echo esc_attr('selected="selected"');
                                } else if (isset($brandkit_fontinfo['fontFamily']) && ($brandkit_fontinfo['fontFamily'] === 'Tahoma,Geneva,sans-serif')) {
                                    echo esc_attr('selected="selected"');
                                }
                                ?>>Tahoma, Geneva, sans-serif</option>
                                <option value= '‘TimesNew Roman’,Times,serif'  <?php
                                if (isset($instance['fntfamily']) && ($instance['fntfamily'] === '‘TimesNew Roman’,Times,serif')) {
                                    echo esc_attr('selected="selected"');
                                } else if (isset($brandkit_fontinfo['fontFamily']) && ($brandkit_fontinfo['fontFamily'] === '‘TimesNew Roman’,Times,serif')) {
                                    echo esc_attr('selected="selected"');
                                }
                                ?>>‘Times New Roman’, Times, serif</option>
                                <option value= 'Webdings,sans-serif'  <?php
                                if (isset($instance['fntfamily']) && ($instance['fntfamily'] === 'Webdings,sans-serif')) {
                                    echo esc_attr('selected="selected"');
                                } else if (isset($brandkit_fontinfo['fontFamily']) && ($brandkit_fontinfo['fontFamily'] === 'Webdings,sans-serif')) {
                                    echo esc_attr('selected="selected"');
                                }
                                ?>>Webdings, sans-serif</option>
                                <option value= 'Wingdings,‘Zapf Dingbats’,sans-serif'  <?php
                                if (isset($instance['fntfamily']) && ($instance['fntfamily'] === 'Wingdings,‘Zapf Dingbats’,sans-serif')) {
                                    echo esc_attr('selected="selected"');
                                } else if (isset($brandkit_fontinfo['fontFamily']) && ($brandkit_fontinfo['fontFamily'] === 'Wingdings,‘Zapf Dingbats’,sans-serif')) {
                                    echo esc_attr('selected="selected"');
                                }
                                ?>>Wingdings, ‘Zapf Dingbats’, sans-serif</option>
                                <option value= 'Arial'  <?php
                                if (isset($instance['fntfamily']) && ($instance['fntfamily'] === 'Arial')) {
                                    echo esc_attr('selected="selected"');
                                } else if (isset($brandkit_fontinfo['fontFamily']) && ($brandkit_fontinfo['fontFamily'] === 'Arial')) {
                                    echo esc_attr('selected="selected"');
                                }
                                ?>>Arial</option>

                            </select> 
                        </p>

                        <p class="is-font-weight">
                            <label for="Font-Weight"><?php _e('Font Weight', 'intellashpere'); ?> </label> <br>
                            <select name="<?php echo esc_attr($this->get_field_name('fntweight')); ?>"  class='<?php echo esc_attr($this->get_field_id('enabledisable')); ?>'  type="select" style="width:100%;" <?php print $disable; ?>>
                                <option value="100" <?php
                                if (isset($instance['fntweight']) && ($instance['fntweight'] === '100')) {
                                    echo esc_attr('selected="selected"');
                                } else if (isset($brandkit_fontinfo['fontWeight']) && ($brandkit_fontinfo['fontWeight'] === '100')) {
                                    echo esc_attr('selected="selected"');
                                }
                                ?>>100</option>
                                <option value="200" <?php
                                if (isset($instance['fntweight']) && ($instance['fntweight'] === '200')) {
                                    echo esc_attr('selected="selected"');
                                } else if (isset($brandkit_fontinfo['fontWeight']) && ($brandkit_fontinfo['fontWeight'] === '200')) {
                                    echo esc_attr('selected="selected"');
                                }
                                ?>>200</option>
                                <option value="300"  <?php
                                if (isset($instance['fntweight']) && ($instance['fntweight'] === '300')) {
                                    echo esc_attr('selected="selected"');
                                } else if (isset($brandkit_fontinfo['fontWeight']) && ($brandkit_fontinfo['fontWeight'] === '300')) {
                                    echo esc_attr('selected="selected"');
                                }
                                ?>>300</option>
                                <option value="400"  <?php
                                if (isset($instance['fntweight']) && ($instance['fntweight'] === '400')) {
                                    echo esc_attr('selected="selected"');
                                } else if (isset($brandkit_fontinfo['fontWeight']) && ($brandkit_fontinfo['fontWeight'] === '400')) {
                                    echo esc_attr('selected="selected"');
                                }
                                ?>>400</option>
                                <option value="500"  <?php
                                if (isset($instance['fntweight']) && ($instance['fntweight'] === '500')) {
                                    echo esc_attr('selected="selected"');
                                } else if (isset($brandkit_fontinfo['fontWeight']) && ($brandkit_fontinfo['fontWeight'] === '500')) {
                                    echo esc_attr('selected="selected"');
                                }
                                ?>>500</option>
                                <option value="600" <?php
                                if (isset($instance['fntweight']) && ($instance['fntweight'] === '600')) {
                                    echo esc_attr('selected="selected"');
                                } else if (isset($brandkit_fontinfo['fontWeight']) && ($brandkit_fontinfo['fontWeight'] === '600')) {
                                    echo esc_attr('selected="selected"');
                                }
                                ?>>600</option>
                                <option value="900"  <?php
                                if (isset($instance['fntweight']) && ($instance['fntweight'] === '900')) {
                                    echo esc_attr('selected="selected"');
                                } else if (isset($brandkit_fontinfo['fontWeight']) && ($brandkit_fontinfo['fontWeight'] === '900')) {
                                    echo esc_attr('selected="selected"');
                                }
                                ?>>900</option>
                            </select>
                        </p>
                        <p class="is-line-height">
                            <label><?php _e('Line Height', 'intellashpere'); ?>  </label> <br>
                            <select name="<?php echo esc_attr($this->get_field_name('linheight')); ?>"   type="select" style="width:100%;" class="shortcode_change <?php echo esc_attr($this->get_field_id('enabledisable')); ?>" <?php print esc_attr($disable); ?>>
                                <option value="1.2" <?php
                                if (isset($instance['linheight']) && ($instance['linheight'] === '1.2')) {
                                    echo esc_attr('selected="selected"');
                                } else if (isset($brandkit_fontinfo['lineHeight']) && ($brandkit_fontinfo['lineHeight'] === '1.2')) {
                                    echo esc_attr('selected="selected"');
                                }
                                ?>>1.2</option>
                                <option value="1.4"  <?php
                                if (isset($instance['linheight']) && ($instance['linheight'] === '1.4')) {
                                    echo esc_attr('selected="selected"');
                                } else if (isset($brandkit_fontinfo['lineHeight']) && ($brandkit_fontinfo['lineHeight'] === '1.4')) {
                                    echo esc_attr('selected="selected"');
                                }
                                ?>>1.4</option>
                                <option value="1.6"  <?php
                                if (isset($instance['linheight']) && ($instance['linheight'] === '1.6')) {
                                    echo esc_attr('selected="selected"');
                                } else if (isset($brandkit_fontinfo['lineHeight']) && ($brandkit_fontinfo['lineHeight'] === '1.6')) {
                                    echo esc_attr('selected="selected"');
                                }
                                ?>>1.6</option>
                                <option value="1.8"  <?php
                                if (isset($instance['linheight']) && ($instance['linheight'] === '1.8')) {
                                    echo esc_attr('selected="selected"');
                                } else if (isset($brandkit_fontinfo['lineHeight']) && ($brandkit_fontinfo['lineHeight'] === '1.8')) {
                                    echo esc_attr('selected="selected"');
                                }
                                ?>>1.8</option>
                            </select>
                        </p>
                        <p class="is-bodysize">
                            <label><?php _e('Body Font Size', 'intellashpere'); ?> </label> <br>
                            <input  name="<?php echo esc_attr($this->get_field_name('ptag')); ?>" type="text" value="<?php echo esc_attr((isset($instance['ptag']) && $instance['ptag'] != '') ? $instance['ptag'] : "14px"); ?>" class =<?php echo esc_attr($this->get_field_id('enabledisable')); ?> style="width:100%;margin: 0px !important;" <?php print $disable; ?>>
                        </p>
                        <p class="is-h1">
                            <label><?php _e('Heading (H1) Font Size', 'intellashpere'); ?>  </label> <br>
                            <input name="<?php echo esc_attr($this->get_field_name('h1')); ?>" type="text" value="<?php echo esc_attr((isset($instance['h1']) && $instance['h1'] != '' ) ? $instance['h1'] : $brandkit_fontinfo['h1']); ?>" class =<?php echo esc_attr($this->get_field_id('enabledisable')); ?> style="width:100%;margin: 0px !important;" <?php print $disable; ?>>
                        </p>
                        <p class="is-heading2">
                            <label><?php _e('Engagement Title (H2) Font Size', 'intellashpere'); ?>  </label> <br>
                            <input name="<?php echo esc_attr($this->get_field_name('h2')); ?>" type="text" value="<?php echo esc_attr((isset($instance['h2']) && $instance['h2'] != '') ? $instance['h2'] : $brandkit_fontinfo['h2']); ?>" class =<?php echo esc_attr($this->get_field_id('enabledisable')); ?> style="width:100%;margin: 0px !important;" <?php print $disable; ?>>
                        </p>
                        <p class="is-heading3">
                            <label><?php _e('Section Title (H3) Font Size', 'intellashpere'); ?> </label> <br>
                            <input name="<?php echo esc_attr($this->get_field_name('h3')); ?>" type="text" value="<?php echo esc_attr((isset($instance['h3']) && $instance['h3'] != '') ? $instance['h3'] : $brandkit_fontinfo['h3']); ?>" class =<?php echo esc_attr($this->get_field_id('enabledisable')); ?> style="width:100%;margin: 0px !important;" <?php print $disable; ?>>
                        </p>
                        <p class="is-heading4">
                            <label><?php _e('Footer (H4) Font Size', 'intellashpere'); ?> </label> <br>
                            <input name="<?php echo esc_attr($this->get_field_name('h4')); ?>" type="text" value="<?php echo esc_attr((isset($instance['h4']) && $instance['h4'] != '') ? $instance['h4'] : $brandkit_fontinfo['h4']); ?>" class =<?php echo esc_attr($this->get_field_id('enabledisable')); ?> style="width:100%;margin: 0px !important;" <?php print $disable; ?>>
                        </p>
                        <p class="is-heading5">
                            <label><?php _e('Custom Text1 (H5) Font Size', 'intellashpere'); ?> </label> <br>
                            <input name="<?php echo esc_attr($this->get_field_name('h5')); ?>" type="text" value="<?php echo esc_attr((isset($instance['h5']) && $instance['h5'] != '' ) ? $instance['h5'] : $brandkit_fontinfo['h5']); ?>"  class =<?php echo esc_attr($this->get_field_id('enabledisable')); ?> style="width:100%;margin: 0px !important;" <?php print $disable; ?>>
                        </p>
                        <p class="is-heading6">
                            <label><?php _e('Custom Text2 (H6) Font Size', 'intellashpere'); ?> </label> <br>
                            <input name="<?php echo esc_attr($this->get_field_name('h6')); ?>" type="text" value="<?php echo esc_attr((isset($instance['h6']) && $instance['h6'] != '') ? $instance['h6'] : $brandkit_fontinfo['h6']); ?>"  class =<?php echo esc_attr($this->get_field_id('enabledisable')); ?> style="width:100%;margin: 0px !important;" <?php print $disable; ?>>
                        </p>




                    </div>
                </div>
                <div class="is-container-buttons"  id="<?php echo esc_attr($this->get_field_id('buttons')); ?>" style="display:none;">
                    <div class="is-buttons-block">
                        <div class="is-buttondesign" >
                            <p>
                                <input class="checkbox brandkit_id" type="checkbox" <?php isset($instance['brandkiton']) ? checked($instance['brandkiton'], 'on') : ''; ?> data-id="<?php echo $this->get_field_id('brandkit'); ?>" name="<?php echo esc_attr($this->get_field_name('brandkiton')); ?>"   id="<?php echo esc_attr($this->get_field_id('brandkiton-button')); ?>" widget_id="<?php echo esc_attr($this->get_field_id('')); ?>"/> 
                                <label for="<?php echo esc_attr($this->get_field_id('brandkiton')); ?>"><?php _e('Override Brand kit', 'intellashpere'); ?> </label>
                            </p>
                            <p class="is-text-shape" >
                                <label><?php _e('Button Shape ', 'intellashpere'); ?> </label><br>

                                <select class ="trxtfdshape shortcode_change <?php echo esc_attr($this->get_field_id('enabledisable')); ?>"  name="<?php echo esc_attr($this->get_field_name('btnstyle')); ?>" style="width:100%;" data-id="<?php echo esc_attr($this->get_field_id('btinputradius')); ?> "  type="text" <?php echo esc_attr($disable); ?> 
                                        class =<?php echo esc_attr($this->get_field_id('enabledisable')); ?>>

                                    <option value="RECTANGLE" <?php
                                    if (isset($instance['btnstyle']) && ($instance['btnstyle'] == 'RECTANGLE')) {
                                        echo esc_attr('selected="selected"');
                                    } else if (isset($brandkit_buttoninfo['fontSize']) && ($brandkit_buttoninfo['fontSize'] === 'RECTANGLE')) {
                                        echo esc_attr('selected="selected"');
                                    }
                                    ?>>RECTANGLE</option>
                                    <option value="ROUNDED" <?php
                                    if (isset($instance['btnstyle']) && ($instance['btnstyle'] == 'ROUNDED')) {
                                        echo esc_attr('selected="selected"');
                                    } else if (isset($brandkit_buttoninfo['fontSize']) && ($brandkit_buttoninfo['fontSize'] === 'ROUNDED')) {
                                        echo esc_attr('selected="selected"');
                                    }
                                    ?>>Rounded</option>
                                </select>
                            </p>
                            <?php $display_file_style = (isset($instance['btnstyle']) && $instance['btnstyle'] == "ROUNDED") ? "display:block" : "display:none"; ?>

                            <p class="<?php echo esc_attr($this->get_field_id('btinputradius')); ?>" style="<?php echo esc_attr($display_file_style); ?>">
                                <label><?php _e('Text Field Radius', 'intellashpere'); ?> </label> <br>
                                <select name="<?php echo esc_attr($this->get_field_name('btncorradius')); ?>"   type="select" style="width:100%;" class="shortcode_change <?php echo esc_attr($this->get_field_id('enabledisable')); ?>" <?php print esc_attr($disable); ?>>
                                    <option value="4px" <?php
                                    if (isset($instance['btncorradius']) && ($instance['btncorradius'] === '4px')) {
                                        echo esc_attr('selected="selected"');
                                    } else if (isset($brandkit_buttoninfo['style']) && ($brandkit_buttoninfo['style'] === '4px')) {
                                        echo esc_attr('selected="selected"');
                                    }
                                    ?>>4px</option>
                                    <option value="8px" <?php
                                    if (isset($instance['btncorradius']) && ($instance['btncorradius'] === '8px')) {
                                        echo esc_attr('selected="selected"');
                                    } else if (isset($brandkit_buttoninfo['style']) && ($brandkit_buttoninfo['style'] === '8px')) {
                                        echo esc_attr('selected="selected"');
                                    }
                                    ?>>8px</option>
                                    <option value="12px" <?php
                                    if (isset($instance['btncorradius']) && ($instance['btncorradius'] === '12px')) {
                                        echo esc_attr('selected="selected"');
                                    } else if (isset($brandkit_buttoninfo['style']) && ($brandkit_buttoninfo['style'] === '12px')) {
                                        echo esc_attr('selected="selected"');
                                    }
                                    ?>>12px</option>
                                    <option value="16px" <?php
                                    if (isset($instance['btncorradius']) && ($instance['btncorradius'] === '16px')) {
                                        echo esc_attr('selected="selected"');
                                    } else if (isset($brandkit_buttoninfo['style']) && ($brandkit_buttoninfo['style'] === '16px')) {
                                        echo esc_attr('selected="selected"');
                                    }
                                    ?>>16px</option>
                                    <option value="20px" <?php
                                    if (isset($instance['btncorradius']) && ($instance['btncorradius'] === '20px')) {
                                        echo esc_attr('selected="selected"');
                                    } else if (isset($brandkit_buttoninfo['style']) && ($brandkit_buttoninfo['style'] === '20px')) {
                                        echo esc_attr('selected="selected"');
                                    }
                                    ?>>20px</option>
                                    <option value="24px" <?php
                                    if (isset($instance['btncorradius']) && ($instance['btncorradius'] === '24px')) {
                                        echo esc_attr('selected="selected"');
                                    } else if (isset($brandkit_buttoninfo['style']) && ($brandkit_buttoninfo['style'] === '24px')) {
                                        echo esc_attr('selected="selected"');
                                    }
                                    ?>>24px</option>
                                </select>
                            </p>
                            <p>
                                <label> <?php _e('Button Size', 'intellashpere'); ?>  </label><br>

                                <select name="<?php echo esc_attr($this->get_field_name('btnsize')); ?>" style="width:100%;" type="text" <?php echo esc_attr($disable); ?> 
                                        class =<?php echo esc_attr($this->get_field_id('enabledisable')); ?>>

                                    <option value='SMALL' <?php
                                    if (isset($instance['btnsize']) && ($instance['btnsize'] == 'small')) {
                                        echo esc_attr('selected="selected"');
                                    } else if (isset($brandkit_buttoninfo['size']) && ($brandkit_buttoninfo['size'] === 'SMALL')) {
                                        echo esc_attr('selected="selected"');
                                    }
                                    ?>>Small</option>
                                    <option value='MEDIUM'  <?php
                                    if (isset($instance['btnsize']) && ($instance['btnsize'] == 'MEDIUM')) {
                                        echo esc_attr('selected="selected"');
                                    } else if (isset($brandkit_buttoninfo['size']) && ($brandkit_buttoninfo['size'] === 'medium')) {
                                        echo esc_attr('selected="selected"');
                                    }
                                    ?>>Medium</option>
                                    <option value='LARGE'  <?php
                                    if (isset($instance['btnsize']) && ($instance['btnsize'] == 'LARGE')) {
                                        echo esc_attr('selected="selected"');
                                    } else if (isset($brandkit_buttoninfo['size']) && ($brandkit_buttoninfo['size'] === 'LARGE')) {
                                        echo esc_attr('selected="selected"');
                                    }
                                    ?>>LARGE</option>
                                </select>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="is-container-forms" id="<?php echo esc_attr($this->get_field_id('forms')); ?>" style="display:none;">
                    <div class="is-forms-block">
                        <div class="is-formdesign" >
                            <p>
                                <input class="checkbox brandkit_id" type="checkbox" <?php isset($instance['brandkiton']) ? checked($instance['brandkiton'], 'on') : ''; ?> data-id="<?php echo esc_attr($this->get_field_id('brandkit')); ?>" name="<?php echo esc_attr($this->get_field_name('brandkiton')); ?>"   id="<?php echo esc_attr($this->get_field_id('brandkiton-button')); ?>" widget_id="<?php echo esc_attr($this->get_field_id('')); ?>"/> 
                                <label for="<?php echo esc_attr($this->get_field_id('brandkiton')); ?>"> <?php _e('Override Brand kit', 'intellashpere'); ?> </label>
                            </p><p>
                                <label><?php _e('Text Field Style', 'intellashpere'); ?> </label><br>
                                <select  name="<?php echo esc_attr($this->get_field_name('txtfdstyle')); ?>"  style="width:100%;"  
                                         <?php print $disable; ?> class =<?php echo esc_attr($this->get_field_id('enabledisable')); ?>>
                                    <option value= 'BORDER_ALL'  <?php
                                    if (isset($instance['txtfdstyle']) && ($instance['txtfdstyle'] == 'BORDER_ALL')) {
                                        echo esc_attr('selected="selected"');
                                    } else if (isset($brandkit_formInfo['borderStyle']) && ($brandkit_formInfo['borderStyle'] === 'BORDER_ALL')) {
                                        echo esc_attr('selected="selected"');
                                    }
                                    ?>>Box (Outline)</option>
                                    <option value= 'BORDER_NONE'  <?php
                                    if (isset($instance['txtfdstyle']) && ($instance['txtfdstyle'] == 'BORDER_NONE')) {
                                        echo esc_attr('selected="selected"');
                                    } else if (isset($brandkit_formInfo['borderStyle']) && ($brandkit_formInfo['borderStyle'] === 'BORDER_NONE')) {
                                        echo esc_attr('selected="selected"');
                                    }
                                    ?>>Box (Filled)</option>
                                    <option value= 'BORDER_BOTTOM'  <?php
                                    if (isset($instance['txtfdstyle']) && ($instance['txtfdstyle'] == 'BORDER_BOTTOM')) {
                                        echo esc_attr('selected="selected"');
                                    } else if (isset($brandkit_formInfo['borderStyle']) && ($brandkit_formInfo['borderStyle'] === 'BORDER_BOTTOM')) {
                                        echo esc_attr('selected="selected"');
                                    }
                                    ?>>Underline</option>
                                </select> </p>
                            <p class="is-text-shape" >
                                <label><?php _e('Text Field Shape', 'intellashpere'); ?>   </label><br>
                                <select class ="trxtfdshape <?php echo esc_attr($this->get_field_id('enabledisable')); ?>" name="<?php echo esc_attr($this->get_field_name('trxtfdshape')); ?>" data-id="<?php echo esc_attr($this->get_field_id('trxtfdshape')); ?>" style="width:100%;"
                                        <?php print $disable; ?>  >
                                    <option value= 'RECTANGLE'  <?php
                                    if (isset($instance['rectangle']) && ($instance['trxtfdshape'] == 'RECTANGLE')) {
                                        echo esc_attr('selected="selected"');
                                    } else if (isset($brandkit_buttoninfo['style']) && ($brandkit_buttoninfo['style'] === 'RECTANGLE')) {
                                        echo esc_attr('selected="selected"');
                                    }
                                    ?>>Rectangle</option>
                                    <option value= 'ROUNDED'  <?php
                                    if (isset($instance['trxtfdshape']) && ($instance['trxtfdshape'] == 'ROUNDED')) {
                                        echo esc_attr('selected="selected"');
                                    } else if (isset($brandkit_buttoninfo['style']) && ($brandkit_buttoninfo['style'] === 'ROUNDED')) {
                                        echo esc_attr('selected="selected"');
                                    }
                                    ?>>Rounded</option>

                                </select>
                            </p >
                            <?php $display_file_style = (isset($instance['trxtfdshape']) && $instance['trxtfdshape'] == "ROUNDED") ? "display:block" : "display:none";
                            ?>
                            <p class="<?php echo esc_attr($this->get_field_id('trxtfdshape')); ?>" style="<?php echo esc_attr($display_file_style); ?>">
                                <label><?php _e('Text Field Radius', 'intellashpere'); ?>   </label> <br>
                                <select name="<?php echo esc_attr($this->get_field_name('trxtfdradius')); ?>" type="select" style="width:100%;" class="shortcode_change <?php echo esc_attr($this->get_field_id('enabledisable')); ?>" <?php print esc_attr($disable); ?>>
                                    <option value="4px" <?php
                                    if (isset($instance['trxtfdradius']) && ($instance['trxtfdradius'] === '4px')) {
                                        echo esc_attr('selected="selected"');
                                    } else if (isset($brandkit_buttoninfo['buttonCornerRadius']) && ($brandkit_buttoninfo['buttonCornerRadius'] === '4px')) {
                                        echo esc_attr('selected="selected"');
                                    }
                                    ?>>4px</option>
                                    <option value="8px" <?php
                                    if (isset($instance['trxtfdradius']) && ($instance['trxtfdradius'] === '8px')) {
                                        echo esc_attr('selected="selected"');
                                    } else if (isset($brandkit_buttoninfo['buttonCornerRadius']) && ($brandkit_buttoninfo['buttonCornerRadius'] === '8px')) {
                                        echo esc_attr('selected="selected"');
                                    }
                                    ?>>8px</option>
                                    <option value="12px" <?php
                                    if (isset($instance['trxtfdradius']) && ($instance['trxtfdradius'] === '12px')) {
                                        echo esc_attr('selected="selected"');
                                    } else if (isset($brandkit_buttoninfo['buttonCornerRadius']) && ($brandkit_buttoninfo['buttonCornerRadius'] === '12px')) {
                                        echo esc_attr('selected="selected"');
                                    }
                                    ?>>12px</option>
                                    <option value="16px" <?php
                                    if (isset($instance['trxtfdradius']) && ($instance['trxtfdradius'] === '16px')) {
                                        echo esc_attr('selected="selected"');
                                    } else if (isset($brandkit_buttoninfo['buttonCornerRadius']) && ($brandkit_buttoninfo['buttonCornerRadius'] === '16px')) {
                                        echo esc_attr('selected="selected"');
                                    }
                                    ?>>16px</option>
                                    <option value="20px" <?php
                                    if (isset($instance['trxtfdradius']) && ($instance['trxtfdradius'] === '20px')) {
                                        echo esc_attr('selected="selected"');
                                    } else if (isset($brandkit_buttoninfo['buttonCornerRadius']) && ($brandkit_buttoninfo['buttonCornerRadius'] === '20px')) {
                                        echo esc_attr('selected="selected"');
                                    }
                                    ?>>20px</option>
                                    <option value="24px" <?php
                                    if (isset($instance['trxtfdradius']) && ($instance['trxtfdradius'] === '24px')) {
                                        echo esc_attr('selected="selected"');
                                    } else if (isset($brandkit_buttoninfo['buttonCornerRadius']) && ($brandkit_buttoninfo['buttonCornerRadius'] === '24px')) {
                                        echo esc_attr('selected="selected"');
                                    }
                                    ?>>24px</option>
                                </select>
                            </p>
                        </div>
                    </div>
                </div>

            </div>    

            <?php
        }

        // Updating widget replacing old instances with new
        public function update($new_instance, $old_instance) {
            include ITSP_INTEGRATION__PLUGIN_DIR . 'admin/views/itsp-widget-update-form-fields.php';
            return $instance;
        }

    }

}
 