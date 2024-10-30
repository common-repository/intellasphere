<?php
/**
 * Offers Grids
 */

if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
 $imgurl = ITSP_INTEGRATION__PLUGIN_URL . '/assets/placeholders/offer.png';  
echo '<img src='.$imgurl.'  width="100%">';
} else {
if (!isset($results->code) && count($results)) {
    ?>
    <div class="itsp_promotions_grid <?php print $uid; ?>" >
        <div class="itsp_is_engage_row  itsp_offer-engage_row <?php print $uid; ?>-row">
            <?php
            $lstupmillisec = '';
            $ofrdata = '';
            $ci = 0;

            foreach ($results as $key => $value) {
                $ci++;
                if ($ci > $lytlimit)
                    break;
                $side_by_side = '';
                $display_in_side = self::is_side_by_side($lytlimit, $ci, $nbrofcolumn, $total_offers_count);
                if ($display_in_side) {
                    $side_by_side = "itsp_side_by_side";
                }
                $lstupmillisec = $value->lastUpdated->millisec;
                $title = isset($value->title) ? $value->title : " ";
                $eddate = ($value->endDate != NULL) ? date('M d, Y', $value->endDate / 1000) : '';
                $bnrtext = (isset($value->bannerText) && $value->bannerText != '') ? $value->bannerText : "unknown title";
                $imgurl = isset($value->promos[0]) ? preg_replace("(^https?://)", "", $value->promos[0]) : "";
                $url = isset($value->postUrl) ? preg_replace("(^https?://)", "", $value->postUrl) : "";
                ?>
                <div class="itsp_engage_grid_block itsp_offer_engage_grid <?php echo esc_attr($side_by_side); ?>" style="width:<?php print $nbrofcolumn; ?>%">
                    <div class="itsp_native_coupon itsp_native_coupon_style" style="border-width: <?php echo esc_attr($brandkit_colorpalette['borderWidth']); ?>;border-color:<?php echo esc_attr($brandkit_colorpalette['borderColor']); ?>;"> 
                        <div> <img src="<?php echo esc_url($imgurl); ?>"></div>
                        <div class="itsp_caption">
                            <h1 style="color:<?php echo esc_attr($brandkit_colorpalette['secondaryTextColor']); ?>; font-size: <?php echo esc_attr($brandkit_fontinfo['h2']); ?>; font-weight: <?php echo esc_attr($brandkit_fontinfo['fontWeight']); ?>;font-family: <?php echo esc_attr($brandkit_fontinfo['fontFamily']); ?>;line-height: <?php echo esc_attr($brandkit_fontinfo['lineHeight']); ?>;"><p><span><?php echo $value->title; ?></span></p></h1>
                            <div class="itsp_offer_description itsp_desc" style="color:<?php echo esc_attr($brandkit_colorpalette['primaryColor']); ?>; font-size: <?php echo esc_attr($brandkit_fontinfo['h2']); ?>; font-family: <?php echo esc_attr($brandkit_fontinfo['fontFamily']); ?>;line-height: <?php echo esc_attr($brandkit_fontinfo['lineHeight']); ?>;font-weight: <?php echo esc_attr($brandkit_fontinfo['fontWeight']); ?>;"><p><span><?php echo $bnrtext; ?></span></p></div>
                            <h4 class="itsp_cta" style="color:<?php echo esc_attr($brandkit_colorpalette['primaryTextColor']); ?>;background:<?php echo esc_attr($brandkit_colorpalette['buttonBackgroundColor']); ?>; font-size: <?php echo esc_attr($brandkit_fontinfo['h2']); ?>; font-weight: <?php echo esc_attr($brandkit_fontinfo['fontWeight']); ?>;font-family: <?php echo esc_attr($brandkit_fontinfo['fontFamily']); ?>;line-height: <?php echo esc_attr($brandkit_fontinfo['lineHeight']); ?>;"><a class="itsp_coupon_pop <?php print $uniq_pop; ?>" href=<?php echo esc_url($url); ?>  style="color:<?php echo esc_attr($brandkit_colorpalette['buttonTextColor']); ?>; font-family: <?php echo esc_attr($brandkit_fontinfo['fontFamily']); ?>;"><?php echo $value->cta_Label; ?></a></h4>
                            <?php if ($eddate != '') { ?>
                                <div class="itsp_engage_expiry itsp_date" style="color: <?php echo esc_attr($brandkit_colorpalette['secondaryTextColor']); ?>; font-size: <?php print $brandkit_fontinfo['h4']; ?>; font-family: <?php print $brandkit_fontinfo['fontFamily']; ?>;line-height: <?php print $brandkit_fontinfo['lineHeight']; ?>;"> <span>Event Date:</span><?php print $eddate; ?></div>
                            <?php } ?>
                        </div>

                    </div>
                </div>
                <?php
                $ofrdata .= '{
                                "@context":"http://schema.org",
                                "@type":"Offer",
                                "name":"' . esc_attr($bnrtext) . '",
                                "image":"' . esc_url($imgurl) . '",
                                "url":"' . esc_url($url) . '",
                                "description" : "' . esc_attr(trim(strip_tags($title))) . '"
                              },';
            }
            print '<script type="application/ld+json">
                 [ ' . rtrim($ofrdata, ',') . ']</script>';
            ?>    
        </div>
        <div class="<?php echo esc_attr($uid); ?>-id" data-last-update="<?php echo esc_attr($lstupmillisec); ?>" data-lytlimit="<?php echo esc_attr($lytlimit); ?>" data-load="<?php echo esc_attr($ci); ?>">  </div>
        <!-- The Modal -->
    </div>


    <?php
} else {
    echo "<div> Promotions are Not found </div>";
}

}