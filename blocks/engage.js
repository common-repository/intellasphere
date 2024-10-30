(function () {
    var el = wp.element.createElement,
            registerBlockType = wp.blocks.registerBlockType,
            ServerSideRender = wp.serverSideRender,
            TextControl = wp.components.TextControl,
            InspectorControls = wp.blockEditor.InspectorControls,
            SelectControl = wp.components.SelectControl,
            ColorPalette = wp.components.ColorPalette,
            PanelBody = wp.components.PanelBody,
            withSelect = wp.data.withSelect,
            CheckboxControl = wp.components.CheckboxControl,
            ColorIndicator = wp.components.ColorIndicator,
            BaseControl = wp.components.BaseControl,
            apiFetch = wp.apiFetch,
            useEffect = wp.element,
            __ = wp.i18n.__,
            registerStore = wp.data.registerStore;

    var ajax_cache = {};
    const actions = {
        setIsData(IsData) {
            return {
                type: 'SET_IS_DATA',
                IsData,
            };
        },
        receiveIsData(path) {
            return {
                type: 'RECEIVE_IS_DATA',
                path,
            };
        },
    };

    const get_block_list = () => wp.data.select('core/block-editor').getBlocks();
    let block_list = get_block_list();
    var i = 1, j = 1, k = 1;
    wp.data.subscribe(() => {
        if (i) {
            if (jQuery('.create_embed_widget').length) {
                jQuery('.create_embed_widget').each(function () {
                    jQuery("#" + this.id + "iframe").remove();
                   // createEmbedWidget(this.id);

                });
                i = 0;
            }
        }
        if (j) {
            if (jQuery('.bootstrap_modal_full_calendar').length) {
               // fullCalendar();
                j = 0;
            }

        }
        if (k) {
            if (jQuery('.itsp_is_engage_carousel').length) {
               // owl_carousel_slider();
                k = 0;
            }
        }
        const new_block_list = get_block_list();
        const block_list_changed = new_block_list !== block_list;
        block_list = new_block_list;
        if (block_list_changed) {
            const clientid = wp.data.select('core/block-editor').getSelectedBlockClientId();
            const Blockid = 'block-' + clientid;
            setTimeout(function () {
                if (clientid) {
                    var iframeid = jQuery('#' + Blockid + " .create_embed_widget").attr('id');
                    if (typeof iframeid !== "undefined") {
                        jQuery("#" + iframeid + "iframe").remove();
                        //createEmbedWidget(iframeid);
                    }
                    if (jQuery('#' + Blockid + ' .bootstrap_modal_full_calendar').length) {
                       // fullCalendar();
                    }
                    if (jQuery('#' + Blockid + ' .itsp_is_engage_carousel').length) {
                        //owl_carousel_slider();
                    }
                }
            }, 3000);
        }
    });

    /**
     * Full Calender Function Call to initialize the Calender
     * @returns 
     */
    const fullCalendar = () => {
        jQuery('.bootstrap_modal_full_calendar').fullCalendar({
            timezone: 'local',
            displayEventTime: false,
            header: {
                left: 'prev,next,today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            eventClick: function (event, jsEvent, view) {
                if (event.url) {
                    window.open(event.url);
                    return false;
                }
                return false;
            },
            events: function (start, end, timezone, callback) {
                var data = {
                    action: 'get_calender_events',
                    start: start.unix() * 1000,
                    end: end.unix() * 1000
                };
                jQuery.post(frontajax.url, data, function (response) {
                    var response = jQuery.parseJSON(response);
                    callback(response.events);
                });
            }
        });
    };

    /**
     * OwlCarousel Slider For Editor initialize
     * @returns 
     */
    const owl_carousel_slider = () => {
        jQuery('.itsp_is_engage_carousel').each(function (i) {
            var id = jQuery('.itsp_is_engage_carousel').attr('id');
            var number_sliders = jQuery(this).attr('number_slider_show');
            if (typeof number_sliders !== 'undefined') {
                if (number_sliders == '1') {
                    jQuery("#" + id).owlCarousel({
                        loop: true,
                        margin: 10,
                        nav: true,
                        items: 1,
                        itemWidth: 320

                    });
                } else if (number_sliders == '2') {
                    jQuery("#" + id).owlCarousel({
                        loop: true,
                        margin: 10,
                        nav: true,
                        responsive: {0: {items: 1}, 600: {items: 2}, 1000: {items: 2}}

                    });
                } else if (number_sliders == '3') {
                    jQuery("#" + id).owlCarousel({
                        loop: true,
                        margin: 10,
                        nav: true,
                        responsive: {0: {items: 1}, 600: {items: 2}, 1000: {items: 3}}

                    });
                }
            } else {
                jQuery("#" + id).owlCarousel({
                    loop: false,
                    margin: 10,
                    nav: true,
                    items: 1

                });
            }
        });

    };
    const store = registerStore('is/secure-contact-block', {
        reducer(state = { IsData: {} }, action) {
            switch (action.type) {
                case 'SET_IS_DATA':
                    return {
                        ...state,
                        IsData: action.IsData,
                    };
            }
            return state;
        },

        actions,

        selectors: {
            receiveIsData(state) {
                const {IsData} = state;
                return IsData;
            },
        },

        controls: {
            RECEIVE_IS_DATA(action) {
                return apiFetch({path: action.path});
            },
        },

        resolvers: {
            * receiveIsData(state) {
                const IsData = yield actions.receiveIsData('/api/v1/engage/' + state);
                ajax_cache[state] = IsData;
                return actions.setIsData(IsData);
            },
        },
    });

    /**
     * Register Intellasphere Block Type
     * 
     */
    registerBlockType('is/engage', {
        title: __('Intellasphere', 'intellasphere'),
        icon: 'admin-settings',
        category: 'common',
        edit: withSelect((select, props) => {
            return {
                IsData: select('is/secure-contact-block').receiveIsData(props.attributes.engage_type),
            };
        })(props => {
                var popup = "",
                    slider = "",
                    multiple_list_columns = " ",
                    multiple_list_grid = " ",
                    list_type = " ",
                    banner = "",
                    events = "",
                    newsletters = "",
                    timer = "",
                    width_height = "",
                    survey = "itsp_display_none",
                    review = "itsp_display_none",
                    banner_hide = "itsp_display_none",
                    multiple_blocks = "itsp_display_none",
                    banner_display = "itsp_display_none",
                    text_field_shape = "itsp_display_none",
                    btnstyle = "itsp_display_none",
                    custom_review = "itsp_display_none",
                    override_brand_kit = "itsp_display_none",
                    multiple_individual = "itsp_display_none",
                    multiple_review_multiple = "itsp_display_none",
                    multiple_blocks_extra = "display-block",
                    multiple_review_sliders = "itsp_display_none",
                    multiple_list_extra = "display-block";
            
            
            var secbtbgcolor = props.attributes.secbtbgcolor;
            var primary_color = props.attributes.primary_color;
            var seccolor = props.attributes.seccolor;
            var pribgcolor = props.attributes.pribgcolor;
            var secbgcolor = props.attributes.secbgcolor;
            var pritextcolor = props.attributes.pritextcolor;
            var sectextcolor = props.attributes.sectextcolor;
            var txtcolor = props.attributes.txtcolor;
            var btbgcolor = props.attributes.btbgcolor;
            var bttxtcolor = props.attributes.bttxtcolor;
            var secbttxtcolor = props.attributes.secbttxtcolor;
            var borcolor = props.attributes.borcolor;
            var warcolor = props.attributes.warcolor;
            if (props.attributes.widget_shortcode_type == "Embed") {
                popup = "itsp_display_none";
                slider = "itsp_display_none";
                timer = "itsp_display_none";
            }
            if (props.attributes.engage_type == "SURVEY") {
                survey = "";
            }
            if (props.attributes.engage_type == "FORM_REVIEW" && props.attributes.individual_multiple == 'individual') {
                review = "display-block";

            }
            if (props.attributes.engage_type == "BANNER" || props.attributes.engage_type == "PROMOTIONS") {
                banner = "itsp_display_none";
                banner_hide = '';
                timer = "itsp_display_none";
                popup = "itsp_display_none";
                slider = "itsp_display_none";

            }
            if (props.attributes.engage_type == "BANNER") {
                timer = "";
                banner_display = "display";

            }
            list_type = [
                {value: 'List', label: 'List'},
                {value: 'Grid', label: 'Grid'},
                {value: 'Slider', label: 'Slider'},
                {value: 'Calender', label: 'Calender'},
            ];

            if (props.attributes.engage_type == "EVENTS" || props.attributes.engage_type == "PROMOTIONS") {
                events = "itsp_display_none";
            }

            if (props.attributes.engage_type == "EVENTS" || props.attributes.engage_type == "FORM_REVIEW") {
                multiple_individual = "display-block";
            }
            if ((props.attributes.individual_multiple == "multiple" && props.attributes.engage_type == "EVENTS") || (props.attributes.individual_multiple == "multiple" && props.attributes.engage_type == "NEWSLETTER_SUBSCRIPTION") || (props.attributes.individual_multiple == "multiple" && props.attributes.engage_type == "FORM_REVIEW") || (props.attributes.individual_multiple == "multiple" && props.attributes.engage_type == "COUPON")) {
                multiple_blocks = "display-block";
                multiple_blocks_extra = "itsp_display_none";
                multiple_list_extra = "itsp_display_none";
            }
            if (props.attributes.engage_type == "FORM_REVIEW" && props.attributes.individual_multiple == "individual") {
                multiple_list_extra = "display-block";
            }

            if (props.attributes.engage_type == "FORM_REVIEW" && props.attributes.individual_multiple == "multiple") {
                multiple_review_multiple = "display-block";
            }
            if (props.attributes.engage_type == "FORM_REVIEW" && props.attributes.individual_multiple == "multiple" && props.attributes.layout_type == "Slider") {
                multiple_review_sliders = "display-block";
            }

            if (props.attributes.engage_type == "NEWSLETTER") {
                list_type = [{value: 'Grid', label: 'Grid'}]
            }

            if (props.attributes.engage_type == "COUPON" || props.attributes.engage_type == "FORM_REVIEW") {
                list_type = [{value: 'List', label: 'List'}, {value: 'Grid', label: 'Grid'}, {value: 'Slider', label: 'Slider'}];
            }
            if (props.attributes.engage_type == "COUPON" || props.attributes.engage_type == "EVENTS" || props.attributes.engage_type == "NEWSLETTER_SUBSCRIPTION" || props.attributes.engage_type == "FORM_REVIEW") {
                multiple_individual = "display-block";
            }

            multiple_list_columns = "display-block";
            multiple_list_grid = "itsp_display_none";

            var multiple_list_calender = "display-block";
            if (props.attributes.engage_type == "EVENTS" && props.attributes.layout_type == "List" || props.attributes.engage_type == "FORM_REVIEW" && props.attributes.layout_type == "List") {
                multiple_list_columns = "itsp_display_none";
            }
            if (props.attributes.engage_type == "EVENTS" && props.attributes.layout_type == "Grid" || props.attributes.engage_type == "COUPON" && props.attributes.layout_type == "Grid") {
                multiple_list_grid = "display-block";

            }
            if ((props.attributes.engage_type == "EVENTS" && props.attributes.layout_type == "Calender")) {
                multiple_list_calender = "itsp_display_none";
            }

            if (props.attributes.engage_type == "NEWSLETTER_SUBSCRIPTION" && props.attributes.layout_type == "Grid") {
                multiple_list_grid = "display-block";
            }

            if (props.attributes.engage_type == "GRID_NEWSLETTERS") {
                newsletters = "itsp_display_none";
                events = "itsp_display_none";
                banner = "itsp_display_none";
            }
            if (props.attributes.engage_type == "FORM_REVIEW_CUSTOM") {
                custom_review = "";
                events = "itsp_display_none";
                banner = "itsp_display_none";
            }
            if (props.attributes.engage_type == "NEWSLETTER_SUBSCRIPTION") {
                multiple_individual = "itsp_display_none";
            }
            if (props.attributes.engage_type == "NEWSLETTER") {
                multiple_list_extra = "itsp_display_none";
                multiple_blocks_extra = "itsp_display_none";
                multiple_blocks = "display-block";
                multiple_list_grid = "display-block !important";

            }
            if (props.attributes.brandkiton) {
                override_brand_kit = '';
            }
            if (props.IsData.length === 0) {
                return "No Data Available";
            }

            if (props.attributes.trxtfdshape == "ROUNDED") {
                text_field_shape = "display-block";
            }
            if (props.attributes.btnstyle == "ROUNDED") {
                btnstyle = "display-block";
            }
            return [
                /*
                 * The ServerSideRender element uses the REST API to automatically call
                 * php_block_render() in your PHP code whenever it needs to get an updated
                 * view of the block.
                 */
                el(ServerSideRender, {
                    block: 'is/engage',
                    attributes: props.attributes,

                }),
                /*
                 * InspectorControls lets you add controls to the Block sidebar. In this case,
                 * we're adding a TextControl, which lets us edit the 'foo' attribute (which
                 * we defined in the PHP). The onChange property is a little bit of magic to tell
                 * the block editor to update the value of our 'foo' property, and to re-render
                 * the block.
                 */
                el(InspectorControls, {},
                        el(SelectControl, {
                            label: __('Engagement Type', 'intellasphere'),
                            value: props.attributes.engage_type,
                            onChange: (value) => {
                                props.setAttributes({engage_type: value});
                                props.setAttributes({individual_multiple: 'individual'});
                                props.setAttributes({selectform: ''});

                            },
                            options: [
                                {value: 'FORM_CONTACTUS', label: 'Contact us'},
                                {value: 'NEWSLETTER_SUBSCRIPTION', label: 'Newsletter Subscription'},
                                {value: 'NEWSLETTER', label: 'Newsletter'},
                                {value: 'EVENTS', label: 'Events'},
                                {value: 'FORM_FEEDBACK', label: 'Feedback'},
                                {value: 'FORM_REVIEW', label: 'Review'},
                                {value: 'POLL', label: 'Poll'},
                                {value: 'COUPON', label: 'Offer'},
                                {value: 'PROMOTERLIST_SUBSCRIPTION', label: 'Promoter Sign-Up'},
                                {value: 'SURVEY', label: 'Survey'},
                            ]
                        }),
                        el(SelectControl, {
                            label: __('Individual / Multiple', 'intellasphere'),
                            value: props.attributes.individual_multiple,
                            className: multiple_individual,
                            onChange: (value) => {
                                props.setAttributes({individual_multiple: value});
                            },
                            options: [
                                {value: 'individual', label: 'individual'},
                                {value: 'multiple', label: 'multiple'}
                            ]
                        }),
                        el(SelectControl, {
                            label: __('Layout Type', 'intellasphere'),
                            value: props.attributes.layout_type,
                            className: multiple_blocks + " layout_change_init",
                            onChange: (value) => {
                                props.setAttributes({layout_type: value});
                            },
                            options: list_type
                        }),
                        el(SelectControl, {
                            label: __('Number Of Column', 'intellasphere'),
                            value: props.attributes.nbrofcolumn,
                            className: multiple_blocks + " " + multiple_list_columns + " " + multiple_list_grid,
                            onChange: (value) => {
                                props.setAttributes({nbrofcolumn: value});
                            },
                            options: [
                                {value: '25', label: '4'},
                                {value: '33', label: '3'},
                                {value: '49', label: '2'},
                                {value: '100', label: '1'},
                            ]
                        }),
                        el(SelectControl, {
                            label: __('Limit', 'intellasphere'),
                            value: props.attributes.lytlimit,
                            className: multiple_blocks + " " + multiple_list_calender,
                            onChange: (value) => {
                                props.setAttributes({lytlimit: value});
                            },
                            options: [
                                {value: '5', label: '5'},
                                {value: '4', label: '4'},
                                {value: '3', label: '3'},
                                {value: '2', label: '2'},
                                {value: '1', label: '1'},
                            ]
                        }),
                        el(SelectControl, {
                            label: __('Select Engagement', 'intellasphere'),
                            value: props.attributes.selectform,
                            className: newsletters + " " + multiple_list_extra,
                            options: ajax_cache[props.attributes.engage_type],
                            onChange: (value) => {
                                props.setAttributes({selectform: value});
                            },

                        }),
                        el(TextControl, {
                            label: __('Max Width', 'intellasphere'),
                            value: props.attributes.maxwidth,
                            className: banner + ' ' + width_height + " " + multiple_blocks_extra,
                            onChange: (value) => {
                                props.setAttributes({maxwidth: value});
                            },
                        }),
                        el(TextControl, {
                            label: __('Max Height', 'intellasphere'),
                            value: props.attributes.maxheight,
                            className: banner + ' ' + width_height + " " + multiple_blocks_extra,
                            onChange: (value) => {
                                props.setAttributes({maxheight: value});
                            },
                        }),
                        el(TextControl, {
                            label: __('width', 'intellasphere'),
                            value: props.attributes.width,
                            className: banner + ' ' + width_height + " " + multiple_blocks_extra,
                            onChange: (value) => {
                                props.setAttributes({width: value});
                            },
                        }),
                        el(TextControl, {
                            label: __('Height', 'intellasphere'),
                            value: props.attributes.height,
                            className: banner + ' ' + width_height + " " + multiple_blocks_extra,
                            onChange: (value) => {
                                props.setAttributes({height: value});
                            },
                        }),
                        el(SelectControl, {
                            label: __('Layout Type', 'intellasphere'),
                            value: props.attributes.formreview_type,
                            className: custom_review,
                            onChange: (value) => {
                                props.setAttributes({formreview_type: value});
                            },
                            options: [
                                {value: 'Slider', label: 'Slider'},
                                {value: 'List', label: 'List'},
                            ]
                        }),
                        el(CheckboxControl, {
                            label: __('Transparent Background', 'intellasphere'),
                            className: banner + " " + multiple_blocks_extra,
                            checked: props.attributes.transparent_background,
                            onChange: function onChange(value) {
                                props.setAttributes({transparent_background: value});
                            }
                        }),
                        el(
                                CheckboxControl,
                                {
                                    label: __('Hide Title', 'intellasphere'),
                                    className: banner + " " + multiple_blocks_extra,
                                    checked: props.attributes.hide_title,
                                    onChange: function (value) {
                                        props.setAttributes({hide_title: value});
                                    },
                                },
                                ),
                        el(CheckboxControl, {
                            label: __('Border Enable Or Disable', 'intellasphere'),
                            className: banner + " " + multiple_blocks_extra,
                            checked: props.attributes.border_enable_disable,
                            onChange: (value) => {
                                props.setAttributes({border_enable_disable: value});
                            },

                        }),
                        el(CheckboxControl, {
                            label: __('Hide logo', 'intellasphere'),
                            className: banner + " " + multiple_blocks_extra,
                            checked: props.attributes.hide_logo,
                            onChange: (value) => {
                                props.setAttributes({hide_logo: value});
                            },

                        }),
                        el(CheckboxControl, {
                            label: __('Hide Company', 'intellasphere'),
                            className: banner + " " + multiple_blocks_extra,
                            checked: props.attributes.hide_company,
                            onChange: (value) => {
                                props.setAttributes({hide_company: value});
                            },

                        }),
                        el(SelectControl, {
                            label: __('Review Type', 'intellasphere'),
                            value: props.attributes.reviewstype,
                            className: review,
                            onChange: (value) => {
                                props.setAttributes({moderate: value});
                            },
                            options: [
                                {value: 'Reviews', label: 'Reviews'},
                                {value: 'Review%20Form', label: 'Review Form'}
                            ]
                        }),
                        el(SelectControl, {
                            label: __('Survey Type', 'intellasphere'),
                            value: props.attributes.survey_type,
                            className: survey,
                            onChange: (value) => {
                                props.setAttributes({survey_type: value});
                            },
                            options: [
                                {value: 'Short%20Form Survey', label: 'Short Survey '},
                                {value: 'Long%20Form Survey', label: 'Long Survey'}
                            ]
                        }),
                        el(SelectControl, {
                            label: __('Rating:', 'intellasphere'),
                            value: props.attributes.rating,
                            className: multiple_review_multiple,
                            onChange: (value) => {
                                props.setAttributes({rating: value});
                            },
                            options: [
                                {value: '0', label: 'All Ratings'},
                                {value: '7', label: 'Positive (4 & 5 star)'},
                                {value: '1', label: '1 star'},
                                {value: '2', label: '2 star'},
                                {value: '3', label: '3 star'},
                                {value: '4', label: '4 star'},
                                {value: '5', label: '5 star'}
                            ]
                        }),
                        el(SelectControl, {
                            label: __('Number of sliders:', 'intellasphere'),
                            value: props.attributes.nbrslider,
                            className: multiple_review_sliders,
                            onChange: (value) => {
                                props.setAttributes({nbrslider: value});

                            },
                            options: [
                                {value: '1', label: '1'},
                                {value: '2', label: '2'},
                                {value: '3', label: '3'},
                            ]
                        }),
                        el(CheckboxControl, {
                            label: __('Rating:', 'intellasphere'),
                            value: props.attributes.reviewstype,
                            className: multiple_review_multiple,
                            checked: props.attributes.override_rating,
                            onChange: function onChange(value) {
                                props.setAttributes({override_rating: value});
                            }
                        }),
                        el(SelectControl, {
                            label: __('Placement', 'intellasphere'),
                            value: props.attributes.banner_data_align,
                            className: banner_display,
                            onChange: (value) => {
                                props.setAttributes({banner_data_align: value});
                            },
                            options: [
                                {value: 'Top', label: 'Top'},
                                {value: 'Bottom', label: 'Bottom'}
                            ]
                        }),
                        ),
                el(InspectorControls, {},
                        el(PanelBody,
                                {title: __('Brand kit', 'intellasphere'), initialOpen: false, className: events},
                                el(CheckboxControl, {
                                    label: __('Override Brand kit', 'intellasphere'),
                                    checked: props.attributes.brandkiton,
                                    onChange: function onChange(value) {
                                        props.setAttributes({brandkiton: value});
                                    }
                                }),
                                el(PanelBody,
                                        {title: __('Color', 'intellasphere'), initialOpen: false, className: override_brand_kit},
                                        el(PanelBody,
                                                {initialOpen: true, className: override_brand_kit},
                                                title = __('Primary Color', 'intellasphere'),
                                                el(ColorIndicator, {
                                                    colorValue: primary_color,
                                                }),
                                                el(ColorPalette, {
                                                    value: props.attributes.pricolor,
                                                    onChange: (value) => {
                                                        props.setAttributes({pricolor: value});
                                                    },
                                                    colors: [{color: brandkit_colorpalette.primaryColor, name: 'primaryColor'}],
                                                }),
                                                title = __('Secondary Color', 'intellasphere'),
                                                el(ColorIndicator, {
                                                    colorValue: seccolor,
                                                }),
                                                el(ColorPalette, {
                                                    label: __('Secondary Color', 'intellasphere'),
                                                    value: props.attributes.seccolor,
                                                    onChange: (value) => {
                                                        props.setAttributes({seccolor: value});
                                                    },
                                                    colors: [{color: brandkit_colorpalette.secondaryColor, name: 'secondaryColor'}],
                                                }),
                                                title = __('Primary Background Color', 'intellasphere'),
                                                el(ColorIndicator, {
                                                    colorValue: pribgcolor,
                                                }),
                                                el(ColorPalette, {
                                                    label: __('Primary Background Color', 'intellasphere'),
                                                    value: props.attributes.pribgcolor,
                                                    onChange: (value) => {
                                                        props.setAttributes({pribgcolor: value});
                                                    },
                                                    colors: [{color: brandkit_colorpalette.primaryBackgroundColor, name: 'primaryColor'}],
                                                }),
                                                title = __('Secondary Background Color', 'intellasphere'),
                                                el(ColorIndicator, {
                                                    colorValue: secbgcolor,
                                                }),
                                                el(ColorPalette, {
                                                    label: __('Secondary Background Color', 'intellasphere'),
                                                    value: props.attributes.secbgcolor,
                                                    onChange: (value) => {
                                                        props.setAttributes({secbgcolor: value});
                                                    },
                                                    colors: [{color: brandkit_colorpalette.secondaryBackgroundColor, name: 'primaryColor'}],
                                                }),
                                                title = __('Primary Text Color', 'intellasphere'),
                                                el(ColorIndicator, {
                                                    colorValue: pritextcolor,
                                                }),
                                                el(ColorPalette, {
                                                    value: props.attributes.pritextcolor,
                                                    onChange: (value) => {
                                                        props.setAttributes({pritextcolor: value});
                                                    },
                                                    colors: [{color: brandkit_colorpalette.primaryTextColor, name: 'PrimaryTextColor'}],
                                                }),
                                                title = __('Secondary Text Color', 'intellasphere'),
                                                el(ColorIndicator, {
                                                    colorValue: sectextcolor,
                                                }),
                                                el(ColorPalette, {
                                                    value: props.attributes.sectextcolor,
                                                    onChange: (value) => {
                                                        props.setAttributes({sectextcolor: value});
                                                    },
                                                    colors: [{color: brandkit_colorpalette.secondaryTextColor, name: 'SecondaryTextColor'}],
                                                }),
                                                title = __('Text Color', 'intellasphere'),
                                                el(ColorIndicator, {
                                                    colorValue: txtcolor,
                                                }),
                                                el(ColorPalette, {
                                                    value: props.attributes.txtcolor,
                                                    onChange: (value) => {
                                                        props.setAttributes({txtcolor: value});
                                                    },
                                                    colors: [{color: brandkit_colorpalette.textColor, name: 'TextColor'}],
                                                }),
                                                title = __('Button Background Color', 'intellasphere'),
                                                el(ColorIndicator, {
                                                    colorValue: btbgcolor,
                                                }),
                                                el(ColorPalette, {
                                                    value: props.attributes.btbgcolor,
                                                    onChange: (value) => {
                                                        props.setAttributes({btbgcolor: value});
                                                    },
                                                    colors: [{color: brandkit_colorpalette.buttonBackgroundColor, name: 'buttonBackgroundColor'}],
                                                }),
                                                title = __('Button Text', 'intellasphere'),
                                                el(ColorIndicator, {
                                                    colorValue: bttxtcolor,
                                                }),
                                                el(ColorPalette, {
                                                    value: props.attributes.bttxtcolor,
                                                    onChange: (value) => {
                                                        props.setAttributes({bttxtcolor: value});
                                                    },
                                                    colors: [{color: brandkit_colorpalette.buttonTextColor, name: 'buttonTextColor'}],
                                                }),
                                                title = __('Secondary Button Bg Color', 'intellasphere'),
                                                el(ColorIndicator, {
                                                    colorValue: secbtbgcolor,
                                                }),
                                                el(ColorPalette, {
                                                    value: props.attributes.secbtbgcolor,
                                                    onChange: (value) => {
                                                        props.setAttributes({secbtbgcolor: value});
                                                    },
                                                    colors: [{color: brandkit_colorpalette.secondaryButtonBackgroundColor, name: 'secondaryButtonBackgroundColor'}],
                                                }),
                                                title = __('Secondary Button Text:', 'intellasphere'),
                                                el(ColorIndicator, {
                                                    colorValue: secbttxtcolor,
                                                }),
                                                el(ColorPalette, {
                                                    value: props.attributes.secbttxtcolor,
                                                    onChange: (value) => {
                                                        props.setAttributes({secbttxtcolor: value});
                                                    },
                                                    colors: [{color: brandkit_colorpalette.secondaryButtonTextColor, name: 'secondaryButtonTextColor'}],
                                                }),
                                                el(SelectControl, {
                                                    label: __('Border Width', 'intellasphere'),
                                                    value: props.attributes.borderwidth,
                                                    className: banner,
                                                    onChange: (value) => {
                                                        props.setAttributes({borderwidth: value});
                                                    },
                                                    options: [
                                                        {value: 'thin', label: 'thin'},
                                                        {value: 'medium', label: 'medium'},
                                                        {value: 'thick', label: 'thick'},
                                                    ]
                                                }),
                                                title = __('Border Color', 'intellasphere'),
                                                el(ColorIndicator, {
                                                    colorValue: borcolor,
                                                }),
                                                el(ColorPalette, {
                                                    value: props.attributes.borcolor,
                                                    onChange: (value) => {
                                                        props.setAttributes({borcolor: value});
                                                    },
                                                    colors: [{color: brandkit_colorpalette.primaryColor, name: 'primaryColor'}],
                                                }),
                                                title = __('Warning Color', 'intellasphere'),
                                                el(ColorIndicator, {
                                                    colorValue: warcolor,
                                                }),
                                                el(ColorPalette, {
                                                    value: props.attributes.warcolor,
                                                    onChange: (value) => {
                                                        props.setAttributes({warcolor: value});
                                                    },
                                                    colors: [{color: brandkit_colorpalette.primaryColor, name: 'primaryColor'}],
                                                })
                                                )),
                                el(PanelBody,
                                        {title: 'Fonts', initialOpen: false, className: override_brand_kit},
                                        el(SelectControl, {
                                            label: __('Design Type', 'intellasphere'),
                                            value: props.attributes.design_type,
                                            className: 'itsp_display_none',
                                            onChange: (value) => {
                                                props.setAttributes({design_type: value});
                                            },
                                            options: [
                                                {value: 'Design_1', label: 'Design_1'},
                                                {value: 'Design_2', label: 'Design_2'},
                                                {value: 'Design_3', label: 'Design_3'}
                                            ]
                                        }),
                                        el(SelectControl, {
                                            label: __('Font Family', 'intellasphere'),
                                            value: props.attributes.fntfamily,
                                            onChange: (value) => {
                                                props.setAttributes({fntfamily: value});
                                            },
                                            options: [
                                                {value: 'Arial', label: 'Arial'},
                                                {value: 'Helvetica', label: 'Helvetica'},
                                                {value: 'Georgia', label: 'Georgia'},
                                                {value: 'sans-serif', label: 'sans-serif'},
                                            ]
                                        }),
                                        el(SelectControl, {
                                            label: __('Font Weight', 'intellasphere'),
                                            value: props.attributes.fntweight,
                                            onChange: (value) => {
                                                props.setAttributes({fntweight: value});
                                            },
                                            options: [
                                                {value: '100', label: '100'},
                                                {value: '200', label: '200'},
                                                {value: '300', label: '300'},
                                                {value: '400', label: '400'},
                                                {value: '500', label: '500'},
                                                {value: '600', label: '600'},
                                                {value: '900', label: '900'},
                                            ]
                                        }),
                                        el(SelectControl, {
                                            label: __('Line height ', 'intellasphere'),
                                            value: props.attributes.linheight,
                                            onChange: (value) => {
                                                props.setAttributes({linheight: value});
                                            },
                                            options: [
                                                {value: '1.2', label: '1.2'},
                                                {value: '1.4', label: '1.4'},
                                                {value: '1.6', label: '1.6'},
                                                {value: '1.8', label: '1.8'},
                                            ]
                                        }),
                                        el(SelectControl, {
                                            label: __('Body Font Size ', 'intellasphere'),
                                            value: props.attributes.ptag,
                                            onChange: (value) => {
                                                props.setAttributes({ptag: value});
                                            },
                                            options: [
                                                {value: '1.2', label: '1.2'},
                                                {value: '1.4', label: '1.4'},
                                                {value: '1.6', label: '1.6'},
                                                {value: '1.8', label: '1.8'},
                                            ]
                                        }),
                                        el(TextControl, {
                                            label: __('Heading (H1) Font Size', 'intellasphere'),
                                            value: props.attributes.h1,
                                            onChange: (value) => {
                                                props.setAttributes({h1: value});
                                            },
                                        }),
                                        el(TextControl, {
                                            label: __('Engagement Title (H2) Font Size', 'intellasphere'),
                                            value: props.attributes.h2,

                                            onChange: (value) => {
                                                props.setAttributes({h2: value});
                                            },
                                        }),
                                        el(TextControl, {
                                            label: __('Section Title (H3) Font Size', 'intellasphere'),
                                            value: props.attributes.h3,

                                            onChange: (value) => {
                                                props.setAttributes({h3: value});
                                            },
                                        }),
                                        el(TextControl, {
                                            label: __('Footer (H4) Font Size', 'intellasphere'),
                                            value: props.attributes.h4,
                                            onChange: (value) => {
                                                props.setAttributes({h4: value});
                                            },
                                        }),
                                        el(TextControl, {
                                            label: __('Custom Text1 (H5) Font Size', 'intellasphere'),
                                            value: props.attributes.h5,
                                            className: banner,
                                            onChange: (value) => {
                                                props.setAttributes({h5: value});
                                            },
                                        }),
                                        el(TextControl, {
                                            label: __('Custom Text2 (H6) Font Size', 'intellasphere'),
                                            value: props.attributes.h6,
                                            className: banner,
                                            onChange: (value) => {
                                                props.setAttributes({h6: value});
                                            },
                                        }),
                                        ),
                                el(PanelBody,
                                        {title: 'Buttons', initialOpen: false, className: override_brand_kit},
                                        el(SelectControl, {
                                            label: __('Button Shape', 'intellasphere'),
                                            value: props.attributes.btnstyle,

                                            onChange: (value) => {
                                                props.setAttributes({btnstyle: value});
                                            },
                                            options: [
                                                {value: 'RECTANGLE', label: 'Rectangle'},
                                                {value: 'ROUNDED', label: 'Rounded'}
                                            ]
                                        }),
                                        el(SelectControl, {
                                            label: __('Button Radius', 'intellasphere'),
                                            value: props.attributes.btncorradius,
                                            className: btnstyle,
                                            onChange: (value) => {
                                                props.setAttributes({btncorradius: value});
                                            },
                                            options: [
                                                {value: '4px', label: '4px'},
                                                {value: '8px', label: '8px'},
                                                {value: '12px', label: '12px'},
                                                {value: '16px', label: '16px'},
                                                {value: '20px', label: '20px'},
                                                {value: '24px', label: '24px'},
                                            ]
                                        }),
                                        el(SelectControl, {
                                            label: __('Button Size', 'intellasphere'),
                                            value: props.attributes.button_size,
                                            onChange: (value) => {
                                                props.setAttributes({button_size: value});
                                            },
                                            options: [
                                                {value: 'small', label: 'small'},
                                                {value: 'medium', label: 'medium'},
                                                {value: 'large', label: 'large'}
                                            ]
                                        })
                                        ),
                                el(PanelBody,
                                        {title: 'Forms', initialOpen: false, className: override_brand_kit},
                                        el(SelectControl, {
                                            label: __('Text Field Style', 'intellasphere'),
                                            value: props.attributes.txtfdstyle,
                                            onChange: (value) => {
                                                props.setAttributes({txtfdstyle: value});
                                            },
                                            options: [
                                                {value: 'BORDER_ALL', label: 'Box (Outline)'},
                                                {value: 'BORDER_NONE', label: 'Box (Filled)'},
                                                {value: 'BORDER_BOTTOM', label: 'Underline'},
                                            ]
                                        }),
                                        el(SelectControl, {
                                            label: __('Text Field Shape', 'intellasphere'),
                                            value: props.attributes.trxtfdshape,

                                            onChange: (value) => {
                                                props.setAttributes({trxtfdshape: value});
                                            },
                                            options: [
                                                {value: 'RECTANGLE', label: 'RECTANGLE'},
                                                {value: 'ROUNDED', label: 'ROUNDED'}
                                            ]
                                        }),
                                        el(SelectControl, {
                                            label: __('Text Field Radius', 'intellasphere'),
                                            value: props.attributes.trxtfdradius,
                                            className: text_field_shape,
                                            onChange: (value) => {
                                                props.setAttributes({trxtfdradius: value});
                                            },
                                            options: [
                                                {value: '4px', label: '4px'},
                                                {value: '8px', label: '8px'},
                                                {value: '12px', label: '12px'},
                                                {value: '16px', label: '16px'},
                                                {value: '20px', label: '20px'},
                                                {value: '24px', label: '24px'},
                                            ]
                                        }),
                                        ))),
            ];
        }),
        save: function (props) {

            return null;
        },
    });
})();
