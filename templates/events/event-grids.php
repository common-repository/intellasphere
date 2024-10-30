<?php
/**
 * Events Grids
 */
if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
 $imgurl = ITSP_INTEGRATION__PLUGIN_URL . '/assets/placeholders/event.png';  
echo '<img src='.$imgurl.'  width="100%">';
} else {
$start = round(microtime(true) * 1000);
$results = Itsp_Utility::static_events($start);
$total_offers_count = count($results);
$evtdata = '';
$nbrofcolumn = isset($instance['nbrofcolumn']) ? $instance['nbrofcolumn'] : '';
$lytlimit = (isset($instance['lytlimit'])) ? $instance['lytlimit'] : '';
if (!isset($results->errorMessage) && count($results)) {
    $wgtid = isset($args['widget_id']) ? $args['widget_id'] : '';
    ?>
    <div class="itsp_is_events_grid itsp_event-grid  <?php echo esc_attr($wgtid); ?>">
        <div class="itsp_is_events_grid_row <?php echo esc_attr($wgtid); ?>-row">
            <?php
            $lstupmillisec = '';
            $i = 0;
            foreach ($results as $key => $value) {
                $i++;
                if ($i > $lytlimit)
                    break;
                $side_by_side = '';
                $display_in_side = self::is_side_by_side($lytlimit, $i, $nbrofcolumn, $total_offers_count);
                if ($display_in_side) {
                    $side_by_side = "itsp_side_by_side_event";
                }
                $lstupmillisec = isset($value->lastUpdated->millisec) ? $value->lastUpdated->millisec : "";
                $dsc = isset($value->bannerText) ? $value->bannerText : "unknown Desc";
                $title = (isset($value->title) && $value->title != '') ? $value->title : "unknown title";
                $strdata = isset($value->eventStartDate) ? date('M d, Y', intval($value->eventStartDate / 1000)) : "";
                $eddate = isset($value->eventEndDate) ? date('M d, Y', intval($value->eventEndDate / 1000)) : "";
                $imgurl = isset($value->promos[0]) ? preg_replace("(^https?://)", "", $value->promos[0]) : "";
                $url = isset($value->postUrl) ? preg_replace("(^http?://)", "", $value->postUrl) : "";
                $ctalabel = isset($value->cta_Label) ? $value->cta_Label : "unknown Desc";
                $address = '';
                $offers = '';
                $venu = '';
                $mtiaddress = '';
                if (isset($value->venues)) {
                    foreach ($value->venues as $key => $value) {
                        $mtiaddress .= (isset($value->address) && $value->address != '') ? Itsp_Utility::is_address($value->address) . '<div style="padding-bottom:3px;" class="itsp_addr_spacer"></div>' : "";
                    }
                }
                if ($mtiaddress) {
                    $address = $mtiaddress;
                } else {
                    if (isset($value->addressType) && $value->addressType == 'physical') {
                        $address = Itsp_Utility::is_address($value->contactUs->address);
                    } else if (isset($value->addressType) && $value->addressType == 'broadcast') {
                        $address = '<a href="//' . $value->addressUrl . '" style="color: ' . esc_attr($brandkit_colorpalette['buttonBackgroundColor']) . '; font-family: ' . esc_attr($brandkit_fontinfo['fontFamily']) . '; ">' . $value->stationName . '</a>';
                    } else if (isset($value->addressType) && $value->addressType == 'online') {
                        $address = '<a href="//' . $value->addressUrl . '" style="color: ' . esc_attr($brandkit_colorpalette['buttonBackgroundColor']) . '; font-family: ' . esc_attr($brandkit_fontinfo['fontFamily']) . '; ">Link Here</a>';
                    } else {
                        if (isset($value->addressUrl) && $value->addressUrl)
                            $address = '<a href="//' . $value->addressUrl . '" style="color: ' . esc_attr($brandkit_colorpalette['buttonBackgroundColor']) . '; font-family: ' . esc_attr($brandkit_fontinfo['fontFamily']) . '; ">' . $value->addressUrl . '</a>';
                    }
                }
                $event_date = (isset($value->dayOfEvent) && $value->dayOfEvent == "single") ? date('D, M d, y, h:i A', intval($value->eventStartDate / 1000)) : $strdata . ' - ' . $eddate;
                ?>
                <div class="itsp_engage_grid_block <?php echo esc_attr($side_by_side); ?>" style="color:<?php echo esc_attr($brandkit_colorpalette['primaryColor']); ?>; width:<?php echo esc_attr($nbrofcolumn); ?>%">
                    <div class="itsp_native_coupon itsp_native_coupon_style" style="border-width:<?php echo esc_attr($brandkit_colorpalette['borderWidth']); ?>; border-color:<?php echo esc_attr($brandkit_colorpalette['borderColor']); ?>;"> 
                        <div><img src="//<?php print $imgurl; ?>"></div>
                        <div class="itsp_caption" >
                            <h1 style="color:<?php echo esc_attr($brandkit_colorpalette['secondaryTextColor']); ?>; font-size: <?php echo esc_attr($brandkit_fontinfo['h2']); ?>; font-weight: <?php echo esc_attr($brandkit_fontinfo['fontWeight']); ?>;font-family: <?php echo esc_attr($brandkit_fontinfo['fontFamily']); ?>;line-height: <?php echo esc_attr($brandkit_fontinfo['lineHeight']); ?>;"><p><span><?php echo sanitize_text_field($title); ?></span></p></h1>
                            <div class="itsp_event_desc itsp_desc" style="color:<?php echo esc_attr($brandkit_colorpalette['primaryTextColor']); ?>; font-size: <?php echo esc_attr($brandkit_fontinfo['h2']); ?>; font-weight: <?php echo esc_attr($brandkit_fontinfo['fontWeight']); ?>;font-family: <?php echo esc_attr($brandkit_fontinfo['fontFamily']); ?>;line-height: <?php echo esc_attr($brandkit_fontinfo['lineHeight']); ?>;"><p><span><?php echo sanitize_text_field($dsc); ?></p></span></div>
                            <h4 class="itsp_address itsp_cta" style="color:<?php echo esc_attr($brandkit_colorpalette['secondaryTextColor']); ?>;background:<?php echo esc_attr($brandkit_colorpalette['buttonBackgroundColor']); ?>; font-size: <?php echo esc_attr($brandkit_fontinfo['p']); ?>; font-family: <?php echo esc_attr($brandkit_fontinfo['fontFamily']); ?>;line-height: <?php echo esc_attr($brandkit_fontinfo['lineHeight']); ?>;"><a class="itsp_events_pop <?php echo esc_attr($uniq_pop); ?>" href=<?php echo esc_url($url); ?>  style="color:<?php echo esc_attr($brandkit_colorpalette['buttonTextColor']); ?>;font-family: <?php echo esc_attr($brandkit_fontinfo['fontFamily']); ?>;"> <?php echo esc_attr($ctalabel); ?> </a></h4>
                            <div class="itsp_date" style="color:<?php echo esc_attr($brandkit_colorpalette['secondaryTextColor']); ?>; font-size: <?php echo esc_attr($brandkit_fontinfo['p']); ?>; font-family: <?php echo esc_attr($brandkit_fontinfo['fontFamily']); ?>;line-height: <?php echo esc_attr($brandkit_fontinfo['lineHeight']); ?>;"><span>Event Date:</span> <?php echo esc_attr($event_date); ?></div>

                        </div>
                    </div>
                </div>
             
                <?php
                if (!isset($value->venues)) {
                    $cntaddress = isset($value->contactUs->address) ? Itsp_Utility::is_address($value->contactUs->address) : "";
                    $address = isset($value->address) ? Itsp_Utility::is_address($value->address) : "";
                    $location = (isset($cntaddress) && $cntaddress != null) ? $cntaddress : ((isset($address) && $address != null) ? $address : '');
                    $venu .= '{"@type":"Place",
                                       "name":"unknown name",
                                       "address":"' . esc_attr($location) . '"
                                     },';
                }
                if (isset($value->venues)) {
                    foreach ($value->venues as $key => $value) {
                        $vnuaddress = (isset($value->address) && $value->address != '') ? Itsp_Utility::is_address($value->address) : "Unknown adress";
                        $vnuname = (isset($value->venueName) && $value->venueName != '') ? $value->venueName : 'Unknown name';
                        $vnuaddress = (isset($vnuaddress) && $vnuaddress != '') ? $vnuaddress : 'unknown address';
                        $venu .= '{"@type":"Place",
                                      "name":"' . $vnuname . '",
                                      "address":"' . $vnuaddress . '"
                                    },';
                    }
                }
                $actprice = '';
                if (isset($value->pricingInfo)) {
                    foreach ($value->pricingInfo as $key => $value) {
                        $pricelabel = (isset($value->labelName) && $value->labelName != '') ? $value->labelName : 'Unknown name';
                        $actprice = (isset($value->actualPrice) && $value->actualPrice != '') ? $value->actualPrice : 0;
                        $currency = (isset($value->currency) && $value->currency != '') ? $value->currency : 0;

                        $offers .= '{
                                         "@type" : "Offer",
                                         "name" : "' . $pricelabel . '",
                                         "price" : "' . $actprice . '",
                                         "priceCurrency":"' . $currency . '"
                                 },';
                    }
                }
                if ($actprice != '')
                    $actprice = ',"offers":[' . rtrim($offers, ',') . ']';
                $evtdata .= '{
                                "@context":"http://schema.org",
                                "@type":"Event",
                                "name":"' . esc_attr($title) . '",
                                "image":"' . esc_url($imgurl) . '",
                                "url":"' . esc_url($url) . '",
                                "eventStatus": "EventScheduled",
                                "startDate":"' . esc_attr($strdata) . '",
                                "doorTime":"' . esc_attr($strdata) . '",
                                "endDate":"' . esc_attr($eddate) . '",
                                "description" : "' . esc_attr(trim(strip_tags($dsc))) . '",
                                "location":[' . rtrim($venu, ',') . ']
                                ' . $actprice . '
                              },';
            }
            ?>
        <div class="<?php echo isset($args['widget_id']) ? esc_attr($args['widget_id']) : "";
            ?>-id" data-last-update="<?php echo esc_attr($lstupmillisec); ?>" data-lytlimit="<?php echo esc_attr($lytlimit); ?>" data-load="<?php echo esc_attr($i); ?>">  </div>

        </div>

    </div>

    <?php
    print '<script type="application/ld+json">
                 [ ' . rtrim($evtdata, ',') . ']</script>';
} else {
    print esc_html('<div> Events Are Not found </div>');
}}