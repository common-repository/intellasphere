<?php
/**
 * Offers Sliders
 */

if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
 $imgurl = ITSP_INTEGRATION__PLUGIN_URL . '/assets/placeholders/offer.png';  
echo '<img src='.$imgurl.' width="100%">';
} else {
$ofrdata = '';
if (count($results)) {
    ?>
    <div class="itsp_is_engage_slider_block itsp_offer_slider" id="<?php print $uid; ?>">
        <div class="owl-carousel owl-theme itsp_is_engage_carousel" id="<?php print $uid; ?>owl_slider">
            <?php
            if (count($results)) {
                $i = 0;
                $ci = 0;
                foreach ($results as $key => $value) {
                    $eddate = ($value->endDate != NULL) ? date('M d, Y', $value->endDate / 1000) : '';
                    $active = $i == 0 ? 'active' : '';
                    $ci++;
                    if ($ci > $lytlimit)
                        break;
                    $dsc = isset($value->bannerText) ? $value->bannerText : "unknown Desc";
                    $title = (isset($value->owner->displayName) && $value->owner->displayName != '') ? $value->owner->displayName : "unknown title";
                    $imgurl = isset($value->promos[0]) ? preg_replace("(^https?://)", "", $value->promos[0]) : "";
                    $url = isset($value->postUrl) ? preg_replace("(^https?://)", "", $value->postUrl) : "";
                    $prcinginfo = '';
                    foreach ($value->pricingInfo as $values) {
                        $lblname = $values->labelName;
                        $actprice = $values->actualPrice;
                        $discount_price = $values->discountPrice;
                        $prcinginfo .= "<div class='itsp_offer_slider_pricing'><span style='color:" . esc_attr($brandkit_colorpalette['primaryTextColor']) . ";font-size:" . esc_attr($brandkit_fontinfo['p']) . ";font-weight:" . esc_attr($brandkit_fontinfo['fontWeight']) . ";font-family:" . esc_attr($brandkit_fontinfo['fontFamily']) . ";' class='itsp_pricing_label'>$lblname</span> <span style='color:" . esc_attr($brandkit_colorpalette['secondaryTextColor']) . ";font-size:" . esc_attr($brandkit_fontinfo['p']) . ";font-family:" . esc_attr($brandkit_fontinfo['fontFamily']) . ";' class='itsp_actual_price'>  $actprice </span>  <span style='color:" . esc_attr($brandkit_colorpalette['primaryColor']) . ";font-size:" . esc_attr($brandkit_fontinfo['p']) . ";font-family:" . esc_attr($brandkit_fontinfo['fontFamily']) . ";' class='itsp_discount_price'> $discount_price </span> </div>";
                    }
                    ?>
                    <div class="itsp_item <?php print $active; ?> itsp_native_coupon" style="border-width: <?php echo esc_attr($brandkit_colorpalette['borderWidth']); ?>;border-color:<?php echo esc_attr($brandkit_colorpalette['borderColor']); ?>;">
                        <div class="itsp_slider_img"> <img src="//<?php print $imgurl; ?>" ></div>
                        <div class="itsp_caption">
                            <h1 style="color:<?php echo esc_attr($brandkit_colorpalette['secondaryTextColor']); ?>; font-size: <?php echo esc_attr($brandkit_fontinfo['h2']); ?>; font-weight: <?php echo esc_attr($brandkit_fontinfo['fontWeight']); ?>;font-family: <?php echo esc_attr($brandkit_fontinfo['fontFamily']); ?>;line-height: <?php echo esc_attr($brandkit_fontinfo['lineHeight']); ?>;"><p><span><?php echo sanitize_text_field($value->title); ?></span></p></h1>
                            <h2 class="itsp_offer_description itsp_desc" style="color:<?php echo esc_attr($brandkit_colorpalette['primaryColor']); ?>; font-size: <?php echo esc_attr($brandkit_fontinfo['h2']); ?>;font-family: <?php echo esc_attr($brandkit_fontinfo['fontFamily']); ?>;line-height: <?php echo esc_attr($brandkit_fontinfo['lineHeight']); ?>;"><p><span><?php echo sanitize_text_field($dsc); ?></span></p></h2>
                            <div style="color:<?php echo esc_attr($brandkit_colorpalette['secondaryTextColor']); ?>; font-size: <?php echo esc_attr($brandkit_fontinfo['p']); ?>; font-weight: <?php echo esc_attr($brandkit_fontinfo['fontWeight']); ?>;font-family: <?php echo esc_attr($brandkit_fontinfo['fontFamily']); ?>;line-height: <?php echo esc_attr($brandkit_fontinfo['lineHeight']); ?>;"> <?php printf($prcinginfo); ?> </div>
                            <h4 class="itsp_cta" style="color:<?php echo esc_attr($brandkit_colorpalette['primaryTextColor']); ?>;background:<?php echo esc_attr($brandkit_colorpalette['buttonBackgroundColor']); ?>; font-size: <?php echo esc_attr($brandkit_fontinfo['h2']); ?>; font-weight: <?php echo esc_attr($brandkit_fontinfo['fontWeight']); ?>;font-family: <?php echo esc_attr($brandkit_fontinfo['fontFamily']); ?>;line-height: <?php echo esc_attr($brandkit_fontinfo['lineHeight']); ?>;"><a class="itsp_coupon_pop <?php print $uniq_pop; ?>" href=<?php echo esc_url($url); ?> style="color:<?php echo esc_attr($brandkit_colorpalette['buttonTextColor']); ?>; font-family: <?php echo esc_attr($brandkit_fontinfo['fontFamily']); ?>;"><?php echo ($title); ?></a></h4>

                            <?php if ($eddate != '') { ?>
                                <div class="itsp_engage_expiry itsp_date" style="color: <?php echo esc_attr($brandkit_colorpalette['secondaryTextColor']); ?>; font-size: <?php echo esc_attr($brandkit_fontinfo['h4']); ?>; font-family: <?php echo esc_attr($brandkit_fontinfo['fontFamily']); ?>;line-height: <?php echo esc_attr($brandkit_fontinfo['lineHeight']); ?>;"><span>Event Date:</span><?php echo esc_html($eddate); ?></div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php
                    $ofrdata .= '{
                                "@context":"http://schema.org",
                                "@type":"Offer",
                                "name":"' . esc_attr($title) . '",
                                "image":"' . esc_url($imgurl) . '",
                                "url":"' . esc_url($url) . '",
                                "description" : "' . esc_attr(trim(strip_tags($dsc))) . '"
                              },';
                    $i++;
                }
            }
            ?>
        </div>
        <script>
            jQuery(document).ready(function () {
                jQuery("#<?php echo esc_attr($uid); ?>owl_slider").owlCarousel({
                    loop: false,
                    margin: 10,
                    nav: true,
                    items: 1,

                });
            });
        </script>
    </div>
    <?php print '<script type="application/ld+json">
                 [ ' . esc_attr(rtrim($ofrdata, ',')) . ']</script>'; ?>
    <?php
} else {
    echo "<div> Promotions are Not found </div>";
}
}