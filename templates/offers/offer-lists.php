<?php
/**
 * Offer list Templates
 */
if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
  $imgurl = ITSP_INTEGRATION__PLUGIN_URL . '/assets/placeholders/offer.png';   
echo '<img src='.$imgurl.' width="100%">';
} else {
if (!isset($results->code)) {
    ?>
    <div class="itsp_promotions_list <?php echo esc_attr($uid); ?>">
        <div class="itsp_list_main_block <?php echo esc_attr($uid); ?>-row">
            <?php
            $ci = 0;
            foreach ($results as $key => $value) {
                $ci++;
                if ($ci > $lytlimit)
                    break;
                $eddate = ($value->endDate != NULL) ? date('M d, Y', $value->endDate / 1000) : '';
                $dsc = isset($value->bannerText) ? $value->bannerText : "unknown Desc";
                $title = (isset($value->owner->displayName) && $value->owner->displayName != '') ? $value->owner->displayName : "unknown title";
                $imgurl = isset($value->promos[0]) ? preg_replace("(^https?://)", "", $value->promos[0]) : "";
                $lstupmillisec = $value->lastUpdated->millisec;
                $url = isset($value->postUrl) ? preg_replace("(^https?://)", "", $value->postUrl) : "";
                $ctalabel = isset($value->cta_Label) ? $value->cta_Label : "unknown Label";
                ?>
                <div class="itsp_is_engage_list itsp_offer_engage_list" style="border-width: <?php print $brandkit_colorpalette['borderWidth']; ?>;border-color: <?php echo esc_attr($brandkit_colorpalette['borderColor']); ?>;">
                    <div class="itsp_is_list_img">
                        <img src="<?php print esc_url($imgurl); ?>" alt="">
                    </div>
                    <div class="itsp_is_list_description itsp_caption">
                        <h1 style="color:<?php echo esc_attr($brandkit_colorpalette['secondaryTextColor']); ?>; font-size: <?php echo esc_attr($brandkit_fontinfo['h2']); ?>; font-weight: <?php echo esc_attr($brandkit_fontinfo['fontWeight']); ?>;font-family: <?php echo esc_attr($brandkit_fontinfo['fontFamily']); ?>;line-height: <?php echo esc_attr($brandkit_fontinfo['lineHeight']); ?>;"><p><span><?php print $title; ?></span></p></h1>
                        <h2 class="itsp_offer_description itsp_desc" style="color:<?php echo esc_attr($brandkit_colorpalette['primaryColor']); ?>; font-size: <?php echo esc_attr($brandkit_fontinfo['h2']); ?>;font-family: <?php echo esc_attr($brandkit_fontinfo['fontFamily']); ?>;line-height: <?php echo esc_attr($brandkit_fontinfo['lineHeight']); ?>;"><p><span><?php print $dsc; ?></span></p></h2>
                        <h4 class="itsp_cta" style="color:<?php echo esc_attr($brandkit_colorpalette['buttonTextColor']); ?>;background:<?php echo esc_attr($brandkit_colorpalette['buttonBackgroundColor']); ?>; font-size: <?php echo esc_attr($brandkit_fontinfo['h2']); ?>; font-weight: <?php echo esc_attr($brandkit_fontinfo['fontWeight']); ?>;font-family: <?php echo esc_attr($brandkit_fontinfo['fontFamily']); ?>;line-height: <?php echo esc_attr($brandkit_fontinfo['lineHeight']); ?>;"><a class="itsp_events_pop <?php print $uniq_pop; ?>" href=<?php echo esc_url($url); ?> style="font-family:<?php echo esc_attr($brandkit_fontinfo['fontFamily']); ?>;color:<?php echo esc_attr($brandkit_colorpalette['buttonTextColor']); ?>;"> <?php echo sanitize_text_field($ctalabel); ?></a></h4>
                        <?php
                        if ($eddate != '') {
                            ?>
                            <div class="itsp_engage_expiry itsp_date" style="color: <?php echo esc_attr($brandkit_colorpalette['secondaryTextColor']); ?>; font-size: <?php echo esc_attr($brandkit_fontinfo['h4']); ?>;font-family: <?php echo esc_attr($brandkit_fontinfo['fontFamily']); ?>;line-height: <?php echo esc_attr($brandkit_fontinfo['lineHeight']); ?>;"><span>Event Date:</span><?php echo esc_attr($eddate); ?></div>
                        <?php } ?>
                    </div>
                </div>

                <?php
            }
            ?>
        </div>
        <div class="<?php echo esc_attr($uid); ?>-id" data-last-update="<?php echo esc_attr($lstupmillisec); ?>" data-lytlimit="<?php echo esc_attr($lytlimit); ?>" data-load="<?php echo esc_attr($ci); ?>">  </div>
    </div>

    <?php
}
}