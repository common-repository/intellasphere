<?php
/**
 * Review Sliders Templates
 */
if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
 $imgurl = ITSP_INTEGRATION__PLUGIN_URL . '/assets/placeholders/offer.png';  
echo '<img src='.$imgurl.' width="100%">';
} else {
$output = '';
$j = 0;
$ci = 0;
if (is_array($revresults)) {
    foreach ($revresults as $revdata) {
        $ci++;
        if ($ci > $lytlimit)
            break;
        $pstrating = ($revdata['rating'] == -2) ? 1 : ($revdata['rating'] == -1 ? 2 : ($revdata['rating'] == 0 ? 3 : ($revdata['rating'] == 1 ? 4 : ($revdata['rating'] == 2 ? 5 : ''))));
        $ngtrating = ($pstrating == 5) ? 0 : (($pstrating == 4) ? 1 : (($pstrating == 3) ? 2 : (($pstrating == 2) ? 3 : (($pstrating == 1) ? 4 : ($pstrating == 0 ? 5 : '')))));
        $crttime = isset($revdata['createdTime']) ? date('M d y, h:i:s A', intVal($revdata['createdTime'] / 1000)) : "";
        $revstructure[] = array(
            "datePublished" => date("Y-m-d", intVal($revdata['createdTime'] / 1000)),
            "description" => $revdata['message'],
            "author" => $revdata['displayName'],
            "name" => $revdata['displayName'],
            "reviewRating" => array(
                "@type" => "Rating",
                "bestRating" => "5",
                "ratingValue" => $revdata['rating'],
                "worstRating" => "1"
            )
        );
        $ratings = '<div class="itsp_reviews_block">';
        $ratings .= '<span class="itsp_rating_input">';
        for ($i = 0; $i < $pstrating; $i++) {
            $ratings .= '<span data-value="' . $i . '" class="fa fa-star " style="color:' . esc_attr($brandkit_colorpalette['secondaryButtonTextColor']) . '; font-size:' . esc_attr($brandkit_fontinfo['h5']) . '!important"></span>';
        }
        for ($i = 0; $i < $ngtrating; $i++) {
            $ratings .= '<span data-value="5" class="fa fa-star-o" style="color:' . esc_attr($brandkit_colorpalette['secondaryButtonTextColor']) . '; font-size:' . esc_attr($brandkit_fontinfo['h5']) . '!important"></span>';
        }

        $ratings .= '</span>';
        $ratings .= '</div>';
        $active_class = ($j == 0) ? "active" : '';
        $output .= "<div class='itsp_item $active_class'>";
        $output .= "<div class='itsp_is_engage_row' style='border:1px solid " . esc_attr($brandkit_colorpalette['borderColor']) . "; background-color: " . esc_attr($brandkit_colorpalette['primaryBackgroundColor']) . ";'>";
        $output .= "<div class='itsp_block-text'>";
        $output .= $ratings;
        $output .= "<p class='itsp_review_description' style='color:" . esc_attr($brandkit_colorpalette['primaryTextColor']) . "; font-size:" . esc_attr($brandkit_fontinfo['p']) . ";line-height:" . esc_attr($brandkit_fontinfo['lineHeight']) . ";font-family:" . esc_attr($brandkit_fontinfo['fontFamily']) . ";'>{$revdata['message']}</p>";
        $output .= "<div class='itsp_company_name' style='color:" . esc_attr($brandkit_colorpalette['secondaryTextColor']) . "; font-size:" . esc_attr($brandkit_fontinfo['h2']) . ";line-height:" . esc_attr($brandkit_fontinfo['lineHeight']) . ";font-family:" . esc_attr($brandkit_fontinfo['fontFamily']) . ";font-weight:" . esc_attr($brandkit_fontinfo['fontWeight']) . ";'>{$revdata['displayName']}</div>";
        $output .= '</div>';
        $output .= '</div>';
        $output .= '</div>';
        $j++;
    }
}
?>
<div class="itsp_tcb_carousel_reviews_block ">
    <div class="itsp_tcb_carousel_reviews" id="reviews_carousel" style="background:<?php echo isset($instance['reviewbgcolor']) ? esc_attr($instance['reviewbgcolor']) : ''; ?>" nbrslider="<?php echo isset($instance['nbrslider']) ? $instance['nbrslider'] : ""; ?>">
        <div id="native-review-<?php echo isset($instance['reviewlayout']) ? esc_attr($instance['reviewlayout']) : ''; ?>">
<!--            <h3 style="color:<?php //echo esc_attr($brandkit_colorpalette['primaryTextColor']); ?>;font-size:<?php //echo esc_attr($brandkit_fontinfo['h1']); ?>;font-family:<?php //echo esc_attr($brandkit_fontinfo['fontFamily']); ?>;line-height:<?php //echo esc_attr($brandkit_fontinfo['lineHeight']); ?>;text-align:center;width:100%;"> <?php //echo isset($instance['reviewtitle']) ? esc_attr($instance['reviewtitle']) : 'Our Client\'s Testmonials'; ?></h3>
            <p style="color:<?php //echo esc_attr($brandkit_colorpalette['primaryTextColor']); ?>;font-family:<?php //echo esc_attr($brandkit_fontinfo['fontFamily']); ?>;line-height:<?php //echo esc_attr($brandkit_fontinfo['lineHeight']); ?>;text-align:center;width:100%;"> We Do The Work.... You Get More Customers & More Sales!</p>-->
            <!-- The slideshow -->
            <div class="owl-carousel owl-theme itsp_is_engage_carousel" id="<?php print $uid; ?>owl_slider" number_slider_show =<?php echo esc_attr($instance['nbrslider']); ?>>
                <?php print $output; ?>
            </div>
        </div>
    </div>
    <script>
        jQuery(document).ready(function () {
            var number_slider_show = "<?php echo esc_attr($instance['nbrslider']); ?>";
            if (number_slider_show == "1") {
                jQuery("#<?php print $uid; ?>owl_slider").owlCarousel({
                    loop: false,
                    margin: 10,
                    nav: true,
                    items: 1,
                    itemWidth: 320

                });
            } else if (number_slider_show == "2") {
                jQuery("#<?php print $uid; ?>owl_slider").owlCarousel({
                    loop: true,
                    margin: 10,
                    nav: true,
                    responsive: {0: {items: 1}, 600: {items: 2}, 1000: {items: 2}}

                });
            } else if (number_slider_show == "3") {
                jQuery("#<?php print $uid; ?>owl_slider").owlCarousel({
                    loop: true,
                    margin: 10,
                    nav: true,
                    responsive: {0: {items: 1}, 600: {items: 2}, 1000: {items: 3}}


                });
            }
        }

        );
    </script>

    <?php
    if (isset($instance['selectid']) && $instance['selectid'] != 'no_options:NoLink') {
        $reviewid = explode(':', $instance['selectid']);
        if (isset($reviewid[0]) && $reviewid[0] != "") {
            ?>
            <div class="itsp_add_review" style="border-radius: <?php echo esc_attr(($brandkit_buttoninfo['style'] == "RECTANGLE") ? "0px" : $brandkit_buttoninfo['buttonCornerRadius']); ?>; background:<?php echo esc_attr($brandkit_colorpalette['buttonBackgroundColor']); ?>;font-size:<?php echo esc_attr($brandkit_fontinfo['p']); ?>;line-height:<?php echo esc_attr($brandkit_fontinfo['lineHeight']); ?>;font-family:<?php echo esc_attr($brandkit_fontinfo['fontFamily']); ?>;"><a   style="color:<?php echo esc_attr($brandkit_colorpalette['buttonTextColor']); ?> !important;background:<?php echo esc_attr($brandkit_colorpalette['buttonBackgroundColor']); ?>;font-size:<?php echo esc_attr($brandkit_fontinfo['p']); ?>;line-height:<?php echo esc_attr($brandkit_fontinfo['lineHeight']); ?>;font-family:<?php echo esc_attr($brandkit_fontinfo['fontFamily']); ?>;" href="<?php echo ITSP_INTELLASPHERE . '/review/' . Itsp_Utility::key() . '/' . $reviewid[0] . '/null#!/' ?>" target='_blank'>Add Review</a></div>
            <?php
        }
    }
    ?>
</div>
<script type="application/ld+json">
    {
    "@context": "http://schema.org",
    "@type": "Service",
    "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "4.90",
    "reviewCount": "<?php echo esc_attr(count($revresults)); ?>"
    },"review": 
    <?php echo json_encode($revstructure); ?>
    }
</script>
<?php } ?>