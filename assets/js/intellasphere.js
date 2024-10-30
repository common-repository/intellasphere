jQuery(document).ready(function () {
//    console.log("test");
//    setTimeout(
//       console.log("testsss"),
//    
//    64000);
//    setTimeout(
//      createEmbedWidget("dc2f78d11c1ab9405fed9a97e4b47c9364c8b4efe8ac74146aadd32d"), 
//    
//    75000);

//jQuery(document).ready(function () {
    var h3height = 0;
    jQuery('.itsp_is_engage_carousel .itsp_is_engage_row .itsp_block-text .itsp_review_description').each(function () {
        if (h3height < jQuery(this).outerHeight()) {
            h3height = jQuery(this).outerHeight();
        }
        ;
    });
    jQuery('.itsp_is_engage_carousel .itsp_is_engage_row .itsp_block-text .itsp_review_description').height(h3height);
    ///});

    jQuery('.itsp_is_events_grid_row, .itsp_is_engage_row').each(function () {
        // Cache the highest
        var highestBox = 0;
        jQuery('.itsp_engage_grid_block .itsp_native_coupon img, .itsp_is_engage_row .itsp_native_coupon img', this).each(function () {
            if (jQuery(this).height() > highestBox) {
                highestBox = jQuery(this).height();
            }

        });
        // Set the height of all those children to whichever was highest 
        jQuery('.itsp_engage_grid_block .itsp_native_coupon img, .itsp_is_engage_row .itsp_native_coupon img, .itsp_is_engage_slider_block .owl-item img', this).height(highestBox);

    });

    jQuery(document).on('click', '.itsp_events_pop ,.itsp_coupon_pop', function (e) {
        e.preventDefault();
        var pop_div = jQuery(this).attr('class').split(' ')[1];
        var src = '//' + jQuery(this).attr("href").replace(/^https?:\/\//, '');
        if (jQuery('#itsp_main_iframe_div').length) {
            jQuery("#itsp_main_iframe_div").html('<iframe class="itsp_iframe_placeholder"  scrolling="no" src="' + src + '" style="width:100%;">');
            dynamaic_iframe_height();
        } else {
            jQuery('<div class="modal fade" id="itsp_modal_popup_show" tabindex="-1" role="dialog" aria-labelledby="itsp_modal_popup_show" aria-hidden="true"><div class="modal-dialog modal-lg" role="document"><div class="modal-content"><div id="itsp_main_iframe_div"><iframe class="itsp_iframe_placeholder" src="' + src + '" style="width:100%; height:935px;" ></iframe></div><div class="modal-footer border-0"><button type="button" class="btn btn-secondary itsp_model_popup_close" data-dismiss="modal">Close</button></div></div></div></div>').prependTo('body');
            dynamaic_iframe_height();
        }
        if (jQuery('#itsp_modal_popup_show').length) {
            jQuery('#itsp_modal_popup_show').modal('show');
        }
    });

    jQuery(document).on('click', '.itsp_model_popup_close', function () {
        jQuery('#itsp_modal_popup_show').modal('hide');
    });

    jQuery(".owl_slider").owlCarousel({
        loop: false,
        margin: 10,
        nav: true,
        items: 1,

    });
    var numberslider = jQuery('#reviews_carousel').attr('nbrslider');
    var responsive = "responsive: {0: {items: 1},600: {items: 1},1000: {items: 1}}";
    if (numberslider == '2') {
        responsive = "responsive: {0: {items: 1},600: {items: 2},1000: {items: 2}}";
    } else if (numberslider == '3') {
        responsive = "responsive: {0: {items: 1},600: {items: 2},1000: {items: 3}}";
    }
    jQuery(".owl-carousel-review").owlCarousel({
        loop: true,
        margin: 10,
        nav: true,
        responsive

    });

  

    var idArray = [];
    jQuery('.create_embed_widget').each(function () {
        idArray.push(this.id);
        createEmbedWidget(this.id);
    });


    function dynamaic_iframe_height() {
        window.onmessage = (e) => {
            var height = e.data.split('documentHeight:')[1];
            if (typeof height !== 'undefined') {
                jQuery('.itsp_iframe_placeholder').css("height", height + 'px');

                
            }
        };
    }
});
