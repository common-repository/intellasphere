<?php
/**
 * Event Lists Template
 */

if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
 $imgurl = ITSP_INTEGRATION__PLUGIN_URL . '/assets/placeholders/event.png';  
echo '<img src='.$imgurl.'  width="100%">';
} else {
$evtdata = '';
$lstupmillisec = '';
$start = round(microtime(true) * 1000);
$results = Itsp_Utility::static_events($start);
$uid = isset($args['widget_id']) ? $args['widget_id'] : '';
?>
<div class="itsp_is_events_list <?php echo esc_attr($uid); ?>">
    <div class="itsp_list_main_block <?php echo esc_attr($uid); ?>-row"  >
        <?php
        if (!isset($results->code) && count($results)) {
            $ij = 0;
            foreach ($results as $key => $value) {
                $ij++;
                if ($ij > $lytlimit)
                    break;
                $lstupmillisec = isset($value->lastUpdated->millisec) ? $value->lastUpdated->millisec : "";
                $dsc = isset($value->bannerText) ? $value->bannerText : "unknown Desc";
                $title = (isset($value->title) && $value->title != '') ? $value->title : "unknown title";
                $strdata = isset($value->eventStartDate) ? date('M d, Y', intval($value->eventStartDate / 1000)) : "";
                $eddate = isset($value->eventEndDate) ? date('M d, Y', intval($value->eventEndDate / 1000)) : "";
                $imgurl = isset($value->promos[0]) ? preg_replace("(^https?://)", "", $value->promos[0]) : "";
                $url = isset($value->postUrl) ? preg_replace("(^https?://)", "", $value->postUrl) : "";
                $address = '';
                $mtiaddress = '';
                $ctalabel = isset($value->cta_Label) ? $value->cta_Label : "unknown Label";
                if (isset($value->venues)) {
                    foreach ($value->venues as $key => $value) {
                        $mtiaddress .= (isset($value->address) && $value->address != '') ? Itsp_Utility::is_address($value->address) : "";
                    }
                }
                if ($mtiaddress) {
                    $address = $mtiaddress;
                } else {
                    if (isset($value->addressType) && $value->addressType == 'physical') {
                        $address = Itsp_Utility::is_address($value->contactUs->address);
                    } else if (isset($value->addressType) && $value->addressType == 'broadcast') {
                        $address = '<a href="//' . $value->addressUrl . '">' . $value->stationName . '</a>';
                    } else if (isset($value->addressType) && $value->addressType == 'online') {
                        $address = '<a href="//' . $value->addressUrl . '" style="color: ' . esc_attr($brandkit_colorpalette['buttonBackgroundColor']) . '; font-family: ' . esc_attr($brandkit_fontinfo['fontFamily']) . '; ">Link Here</a>';
                    } else {
                        if (isset($value->addressType))
                            $address = '<a href="' . $value->addressUrl . '" style="color: ' . esc_attr($brandkit_colorpalette['buttonBackgroundColor']) . '; font-family: ' . esc_attr($brandkit_fontinfo['fontFamily']) . '; ">' . $value->addressUrl . '</a>';
                    }
                }
                $event_date = (isset($value->dayOfEvent) && $value->dayOfEvent == "single") ? date('D, M d, y, h:i A', intval($value->eventStartDate / 1000)) : $strdata . '-' . $eddate;
                ?>
                <div class="itsp_is_engage_list itsp_event_list" style="border-width: <?php echo esc_attr($brandkit_colorpalette['borderWidth']); ?>;border-color: <?php echo esc_attr($brandkit_colorpalette['borderColor']); ?>;">
                    <div class="itsp_is_list_img">
                        <a class="itsp_events_pop <?php print $uniq_pop; ?>" href=<?php echo esc_url($url); ?> style="font-family:<?php echo esc_attr($brandkit_fontinfo['fontFamily']); ?>;">
                            <img src="//<?php print $imgurl; ?>" alt="">
                        </a>
                    </div>
                    <div class="itsp_is_list_description itsp_caption">
                        
                        
                        <h1 style="color:<?php echo esc_attr($brandkit_colorpalette['secondaryTextColor']); ?>; font-size: <?php echo esc_attr($brandkit_fontinfo['h2']); ?>; font-weight: <?php echo esc_attr($brandkit_fontinfo['fontWeight']); ?>;font-family: <?php echo esc_attr($brandkit_fontinfo['fontFamily']); ?>;line-height: <?php echo esc_attr($brandkit_fontinfo['lineHeight']); ?>;"><p><span><?php print $title; ?></span></p></h1>
                        <h2 class="itsp_offer_description itsp_desc" style="color:<?php echo esc_attr($brandkit_colorpalette['primaryColor']); ?>; font-size: <?php echo esc_attr($brandkit_fontinfo['h2']); ?>;font-family: <?php echo esc_attr($brandkit_fontinfo['fontFamily']); ?>;font-weight: <?php echo esc_attr($brandkit_fontinfo['fontWeight']); ?>;line-height: <?php echo esc_attr($brandkit_fontinfo['lineHeight']); ?>;"><p><span><?php echo $dsc; ?></span></p></h2>
                        <div class="itsp_engage_address itsp_cta" style="color: <?php echo esc_attr($brandkit_colorpalette['buttonTextColor']); ?>;background:<?php echo esc_attr($brandkit_colorpalette['buttonBackgroundColor']); ?>; font-size: <?php echo esc_attr($brandkit_fontinfo['p']); ?>;font-family: <?php echo esc_attr($brandkit_fontinfo['fontFamily']); ?>;line-height: <?php echo esc_attr($brandkit_fontinfo['lineHeight']); ?>;"><a class="itsp_events_pop <?php print $uniq_pop; ?>" href="<?php echo esc_url($url); ?>" style="font-family:<?php echo esc_attr($brandkit_fontinfo['fontFamily']); ?>;color:<?php echo esc_attr($brandkit_colorpalette['buttonTextColor']); ?>;">
                                <?php echo esc_attr($ctalabel); ?></a></div>
                        <div class="itsp_engage_date" style="color: <?php echo esc_attr($brandkit_colorpalette['secondaryTextColor']); ?>; font-size: <?php echo esc_attr($brandkit_fontinfo['h4']); ?>;font-family: <?php echo esc_attr($brandkit_fontinfo['fontFamily']); ?>;line-height: <?php echo esc_attr($brandkit_fontinfo['lineHeight']); ?>;"><span>Event Date:</span><?php print $event_date; ?></div>
                    </div>
                </div>
                <?php
            }
        }
        if (!isset($results->code) && count($results)) {
            ?>
            <div class="itsp_is_events_list_id <?php echo esc_attr($uid); ?>-id" data-last-update="<?php echo esc_attr($lstupmillisec); ?>" data-lytlimit="<?php echo esc_attr($lytlimit); ?>" data-load="<?php echo esc_attr($ij); ?>">  </div>
        <?php } ?>
    </div>
</div>

<?php } ?>