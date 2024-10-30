jQuery(document).ready(function () {
    var colorSettings = {
        defaultColor: false,
        change: function (event, ui) {
            jQuery('select.color_setting_changed ').val('changed').trigger('change');
        },
        clear: function () {
        },
        hide: true,
        palettes: true
    };

    jQuery(document).on('widget-updated widget-added', function (event, widget) {
        var drag_id = widget[0]['id'];
        jQuery("#" + drag_id + " .select-data").trigger('click');
        jQuery(".widget-content .sp-replacer").remove();
    });

  

    jQuery(document).on("click", "#color_irrish_picker", function () {
        jQuery('.irrish_color_picker').wpColorPicker();
    });
    jQuery(document).on("change", ".is-text-shape .trxtfdshape", function () {
        var dataid = jQuery(this).attr('data-id');
        if (this.value == "ROUNDED") {
            jQuery('.' + dataid).show();
        } else {
            jQuery('.' + dataid).hide();
        }

    });

    jQuery(document).on("click", "input[name = 'savewidget']", function () {
        var get_id = jQuery(this).attr('id');
        var get_class = get_id.replace('-savewidget', '');
        jQuery('#' + get_class + '-ajax-select').attr("data-select", "1");
    });

    /**
     * select-data Engagement
     */
    jQuery(document).on("click", ".engage_select_data", function () {
        var select_id = this.id;
        var dataselectid = jQuery(this).attr('data-selectid');
        var get_data_select = jQuery(this).attr('data-select');
        var selected_id = jQuery(this).attr('data-change');
        var selected_eng_type = jQuery('#' + selected_id).val();
        var selected_value = jQuery('#' + dataselectid).val();

        if (get_data_select == "1") {
            jQuery(this).hide(0);
            jQuery('select.' + select_id).show(0);
            jQuery('#loader_engage').show();
            jQuery(this).attr("data-select", "0");
            jQuery(this).empty();
            var options = '';
          ///  if (select_id != "shortcode_gen_ajax-select") {
                options = '<option value="">Select</option>';
          //  }
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

    jQuery(document).on("change", "select.engage_select_data", function () {
        var selected = jQuery(this).children("option:selected").val();
        var id = jQuery(this).attr('data-change');
        if (typeof selected !== "undefined") {
            jQuery("#engage_" + id).val(selected);
        }
    });


    jQuery(document).on("mouseover", ".widget_engagement_type", function () {

        jQuery(this).attr("engamemnethover", "1");

    });
    var engagement_type = jQuery('.widget_engagement_type').val();
    if (engagement_type != "FORM_REVIEW") {
        jQuery('.nbrslider').hide();
    }

    jQuery(document).on("change", ".layout_type", function () {
        var engagement_type = jQuery('.widget_engagement_type').val();

        if (this.value == 'Slider:Slider' && engagement_type == "FORM_REVIEW") {
            jQuery('.nbrslider').show();
        } else {
            jQuery('.nbrslider').hide();
        }
    });




    jQuery(document).on("change", ".widget_engagement_type", function () {
        var data_id = jQuery(this).attr('data-id');
        var realclick = jQuery(this).attr('engamemnethover');
        if (realclick == "1") {

            jQuery('.radio_type').hide();

            jQuery('.' + data_id + 'radio_type').val("individual");
            jQuery('#' + data_id + "ajax-select").attr("data-select", "1");
            jQuery("." + data_id + 'nbrofcolumn').hide();
            if (jQuery(this).val() == "EVENT" || jQuery(this).val() == "COUPON" || jQuery(this).val() == "FORM_REVIEW") {
                jQuery('.radio_type').show();

            } else {
                jQuery('.' + data_id + 'mtlayout').val("List");

            }

            /* Survery and Review*/
            if (jQuery(this).val() == "FORM_REVIEW") {
                jQuery('#' + data_id + "survey").hide();
                jQuery('#' + data_id + "review").show();
                jQuery('#' + data_id + "rating").show();
                jQuery('#' + data_id + "moderate").show();
            } else if (jQuery(this).val() == "SURVEY") {
                jQuery('#' + data_id + "survey").show();
                jQuery('#' + data_id + "review").hide();
            } else {
                jQuery('#' + data_id + "survey").hide();
                jQuery('#' + data_id + "review").hide();
                jQuery('#' + data_id + "rating").hide();
                jQuery('#' + data_id + "moderate").hide();
            }

            /** Banner */
            if (jQuery(this).val() == "BANNER") {
                jQuery("#" + data_id + "banneralignemnt").show();
                jQuery('#' + data_id + "datawidth").hide();
                jQuery('#' + data_id + "dataheight").hide();
                jQuery('#' + data_id + "datamaxwidth").hide();
                jQuery('#' + data_id + "datamaxheight").show();
                jQuery('#' + data_id + "display_time").show();
                jQuery('.' + data_id + 'widget_tlh').hide();
            } else {
                jQuery("#" + data_id + "banneralignemnt").hide();
                jQuery('#' + data_id + "datawidth").show();
                jQuery('#' + data_id + "datamaxwidth").show();
                jQuery('#' + data_id + "dataheight").show();
                jQuery('#' + data_id + "display_time").hide();
                jQuery('.' + data_id + 'widget_tlh').show();
            }

            /** Default Individual */

            /** Layout type  **/
            var layout_type = jQuery('.' + data_id + 'radio_type').val();
            if (layout_type == "individual") {
                jQuery("." + data_id + 'mtlayout').hide();
                jQuery("#" + data_id + 'lytlimit').hide();
                jQuery("." + data_id + 'select_engage').show();
                jQuery("#" + data_id + 'rating').hide();
                jQuery("#" + data_id + 'moderate').hide();
            } else {
                jQuery("." + data_id + 'mtlayout').show();
                jQuery("#" + data_id + 'lytlimit').show();
                jQuery("#" + data_id + 'datawidth').hide();
                jQuery("#" + data_id + 'dataheight').hide();
                jQuery("." + data_id + 'widget_tlh').hide();
                jQuery("." + data_id + 'select_engage').hide();
                jQuery("#" + data_id + 'rating').show();
                jQuery("#" + data_id + 'moderate').show();
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
                jQuery("." + data_id + 'mtlayout option[value="Grid:Grid"]').attr("disabled", false).show();
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
            } else if (jQuery(this).val() != "FORM_REVIEW") {
                jQuery('.nbrslider').hide();
            }

        }
        jQuery(this).attr("engamemnethover", "0");
        jQuery(".engage_select_data").trigger("click");
    });




    jQuery(document).on("click", ".widget-style", function () {
        var data_id = jQuery(this).attr('main-id');
        var select_value = jQuery("#" + data_id + "engagement_type").val();
        if (select_value == "BANNER") {
        } else if (select_value == "FORM_REVIEW") {
            jQuery("#" + data_id + "style").toggle();
            jQuery("#" + data_id + "review").show();
        } else if (select_value == "SURVEY") {
            jQuery("#" + data_id + "style").toggle();
            jQuery("#" + data_id + "review").hide();
            jQuery("#" + data_id + "survey").show();
        } else {
            jQuery("#" + data_id + "style").toggle();
            jQuery("#" + data_id + "review").hide();
        }


    });




//Radio Button
    jQuery(document).on("mouseover", ".typofeng", function () {

        jQuery(this).attr("type_of_engag_hover", "1");

    });

    jQuery(document).on("change", ".typofeng", function () {
        var realclick = jQuery(this).attr('type_of_engag_hover');
        if (realclick == "1") {
            var dataid = jQuery(this).attr('data-id');
            var engament_value = jQuery('#' + dataid + 'engagement_type').val();
            var layout_type = jQuery('#' + dataid + 'mtlayout').val();
            if (jQuery(this).val() == 'multiple') {
                jQuery("." + dataid + 'mtlayout').show();
                jQuery("#" + dataid + 'lytlimit').show();
                jQuery("#" + dataid + 'datawidth').hide();
                jQuery("#" + dataid + 'dataheight').hide();
                jQuery("#" + dataid + 'datamaxwidth').hide();
                jQuery("#" + dataid + 'datamaxheight').hide();
                jQuery("." + dataid + 'widget_tlh').hide();

                jQuery("." + dataid + 'select_engage').hide();
                if (jQuery("#" + dataid + "engagement_type").val() == "FORM_REVIEW") {
                    jQuery("." + dataid + 'select_engage').show();
                    jQuery("#" + dataid + 'review').show();
                    jQuery("#" + dataid + 'rating').show();
                    jQuery("#" + dataid + 'moderate').show();
                }

            } else {
                jQuery("." + dataid + 'mtlayout').hide();
                jQuery("#" + dataid + 'lytlimit').hide();
                jQuery("#" + dataid + 'datawidth').show();
                jQuery("#" + dataid + 'dataheight').show();
                jQuery("." + dataid + 'widget_tlh').show();
                jQuery("#" + dataid + 'datamaxwidth').show();
                jQuery("#" + dataid + 'datamaxheight').show();
                jQuery("." + dataid + 'select_engage').show();
                jQuery("." + dataid + 'nbrofcolumn').hide();
                jQuery("#" + dataid + 'review').hide();
                jQuery("#" + dataid + 'rating').hide();
                jQuery("#" + dataid + 'moderate').hide();
                jQuery("#" + dataid + 'review_type').hide();
                if (jQuery("#" + dataid + "engagement_type").val() == "FORM_REVIEW") {
                    jQuery("#" + dataid + 'review').show();
                }

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

            if (engament_value == "FORM_REVIEW") {
                jQuery('#' + dataid + 'mtlayout').val('List');
                if (jQuery(this).val() == 'multiple') {
                    jQuery("#" + dataid + 'review').hide();
                } else {
                    jQuery("#" + dataid + 'review').show();
                }


            }

            if (engament_value == "COUPON") {
                jQuery('#' + dataid + 'mtlayout').val('List');

            }

        }
        jQuery(this).attr("type_of_engag_hover", "0");

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

    jQuery(document).on("change", ".is-text-shape .trxtfdshape", function () {
        var dataid = jQuery(this).attr('data-id');
        if (this.value == "ROUNDED") {
            jQuery('.' + dataid).show();
        } else {
            jQuery('.' + dataid).hide();
        }

    });


    jQuery(document).on("click", "input[name = 'savewidget']", function () {
        var get_id = jQuery(this).attr('id');
        var get_class = get_id.replace('-savewidget', '');
        jQuery('#' + get_class + '-ajax-select').attr("data-select", "1");
    });

    var realclick = "1";

    /**
     * select-data Engagement
     */
    jQuery(document).on("click", "#banner_gen_ajax_select", function () {
        if (realclick == "1") {
            jQuery('#loader_engage').show();
            jQuery('#banner_gen_ajax_select').hide();
            realclick = 0;
            var data = {
                action: 'select_engage',
                type: "BANNER"
            };

            jQuery.post(globalajax.ajaxurl, data, function (response) {
                var response = jQuery.parseJSON(response);
                var options = '';
                jQuery.each(response.results, function (key, value) {
                    var current = value.postId + ":" + value.title;
                    if (value.bannerEmbedStyle != "embed")
                        options += '<option value="' + value.postId + '">' + value.title + '</option>';
                });
                jQuery('#banner_gen_ajax_select').html(options);
                jQuery('#loader_engage').hide();
                jQuery('#banner_gen_ajax_select').show();

            });
        }


    });



    jQuery(document).on("click", "#submitbanner", function () {
        var banner_code = jQuery('#banner_gen_ajax_select').val();
        var banner_page = jQuery('#get_banner_pages').val();
        var type = jQuery('#get_banner_type').val();
        var data = {
            action: 'banner_table',
            banner_code: banner_code,
            banner_page: banner_page,
            banner_type: type
        };
        jQuery.post(globalajax.ajaxurl, data, function (response) {
            if (response.success == true) {
                window.location.href = response.data;
            }
        });
    });


});


