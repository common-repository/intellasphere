<?php


/**
 * Sliders Template
 */

if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
 $imgurl = ITSP_INTEGRATION__PLUGIN_URL . '/assets/placeholders/event.png';  
echo '<img src='.$imgurl.' width="100%">';
} else {
$evtdata = '';
$start = round(microtime(true) * 1000);
$results = Itsp_Utility::static_events($start);
$uid = isset($args['widget_id']) ? $args['widget_id'] : "";
if (!isset($results->errorMessage) && count($results)) {
    
    ?>
    <div class="itsp_is_engage_slider_block itsp_event_slider" id="<?php print $uid; ?>">
        <!-- Indicators -->
        <div class="owl-carousel owl-theme itsp_is_engage_carousel" id="<?php print $uid; ?>owl_slider">
            <?php
            if (count($results)) {
                $i = 0;
                $j = 0;
                foreach ($results as $key => $value) {
                    $j++;
                    if ($j > $lytlimit)
                        break;
                    $active = $i == 0 ? 'active' : '';
                    $dsc = isset($value->bannerText) ? $value->bannerText : "unknown Desc";
                    $title = (isset($value->title) && $value->title != '') ? $value->title : "unknown title";
                    $strdata = isset($value->eventStartDate) ? date('M d, Y', intval($value->eventStartDate / 1000)) : "";
                    $eddate = isset($value->eventEndDate) ? date('M d, Y', intval($value->eventEndDate / 1000)) : "";
                    $imgurl = isset($value->promos[0]) ? preg_replace("(^https?://)", "", $value->promos[0]) : "";
                    $url = isset($value->postUrl) ? preg_replace("(^https?://)", "", $value->postUrl) : "";
                    $address = '';
                    $ctalabel = isset($value->cta_Label) ? $value->cta_Label : "unknown Label";
                    $mtiaddress = '';
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
                            if (isset($value->addressUrl))
                                $address = '<a href="' . $value->addressUrl . '">' . $value->addressUrl . '</a>';
                        }
                    }
                    $event_date = (isset($value->dayOfEvent) && $value->dayOfEvent == "single") ? date('D, M d, y, h:i A', intval($value->eventStartDate / 1000)) : $strdata . '-' . $eddate;
                    ?>
                    <div class="itsp_item <?php print $active; ?> itsp_native_coupon" style="border-width: <?php echo esc_attr($brandkit_colorpalette['borderWidth']); ?>;border-color:<?php print $brandkit_colorpalette['borderColor']; ?>;">
                        <div class="itsp_slider_img"><img src="//<?php print $imgurl; ?>" ></div>
                        <div class="itsp_caption">
                            <h1 style="color:<?php echo esc_attr($brandkit_colorpalette['secondaryTextColor']); ?>; font-size: <?php echo esc_attr($brandkit_fontinfo['h2']); ?>; font-weight: <?php echo esc_attr($brandkit_fontinfo['fontWeight']); ?>;font-family: <?php echo esc_attr($brandkit_fontinfo['fontFamily']); ?>;line-height: <?php echo esc_attr($brandkit_fontinfo['lineHeight']); ?>;"><p><span><?php echo esc_html($title); ?></span></p></h1>
                            <div class="itsp_event_desc itsp_desc" style="color:<?php echo esc_attr($brandkit_colorpalette['primaryTextColor']); ?>; font-size: <?php echo esc_attr($brandkit_fontinfo['h2']); ?>; font-weight: <?php echo esc_attr($brandkit_fontinfo['fontWeight']); ?>;font-family: <?php echo esc_attr($brandkit_fontinfo['fontFamily']); ?>;line-height: <?php echo esc_attr($brandkit_fontinfo['lineHeight']); ?>;"><p><span><?php echo esc_html($dsc); ?></span></p></div>
                            <h4 class="itsp_cta" style="color:<?php echo esc_attr($brandkit_colorpalette['secondaryTextColor']); ?>; font-size: <?php echo esc_attr($brandkit_fontinfo['p']); ?>;background:<?php echo esc_attr($brandkit_colorpalette['buttonBackgroundColor']); ?>;font-family: <?php echo esc_attr($brandkit_fontinfo['fontFamily']); ?>;line-height: <?php echo esc_attr($brandkit_fontinfo['lineHeight']); ?>;"><a class="itsp_events_pop <?php echo esc_attr($uniq_pop); ?>" href=<?php echo esc_url($url); ?>  style="color:<?php echo esc_attr($brandkit_colorpalette['buttonTextColor']); ?>;font-weight: <?php echo esc_attr($brandkit_fontinfo['fontWeight']); ?>;font-family:<?php echo esc_attr($brandkit_fontinfo['fontFamily']); ?>;"><?php echo esc_html($ctalabel); ?></a> </h4>
                            <div class="itsp_event_date itsp_date" style="color:<?php echo esc_attr($brandkit_colorpalette['secondaryTextColor']); ?>; font-size: <?php echo esc_attr($brandkit_fontinfo['p']); ?>; font-weight: <?php echo esc_attr($brandkit_fontinfo['fontWeight']); ?>;font-family: <?php echo esc_attr($brandkit_fontinfo['fontFamily']); ?>;line-height: <?php echo esc_attr($brandkit_fontinfo['lineHeight']); ?>;"><span>Event Date:</span><?php echo esc_html($event_date); ?></div>

                        </div>

                    </div>
                    <?php
                    $i++;
                    $venu = '';
                    if (isset($value->venues) && !count($value->venues)) {
                        $cntaddress = Itsp_Utility::is_address($value->contactUs->address);
                        $address = Itsp_Utility::is_address($value->address);
                        $location = (isset($cntaddress) && $cntaddress != null) ? $cntaddress : ((isset($address) && $address != null) ? $address : 'unknown address');
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
                                      "name":"' . esc_attr($vnuname) . '",
                                      "address":"' . esc_attr($vnuaddress) . '"
                                    },';
                        }
                    }
                    $actprice = '';
                    $offers = '';
                    if (isset($value->pricingInfo)) {
                        foreach ($value->pricingInfo as $key => $value) {
                            $pricelabel = (isset($value->labelName) && $value->labelName != '') ? $value->labelName : 'Unknown name';
                            $actprice = (isset($value->actualPrice) && $value->actualPrice != '') ? $value->actualPrice : 0;
                            $currency = (isset($value->currency) && $value->currency != '') ? $value->currency : '$';
                            $offers .= '{
                                         "@type" : "Offer",
                                         "name" : "' . esc_attr($pricelabel) . '",
                                         "price" : "' . esc_attr($actprice) . '",
                                         "priceCurrency":"' . esc_attr($currency) . '"
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
            }
            ?>
        </div>
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
    <?php
    echo '<script type="application/ld+json">
                 [ ' . rtrim($evtdata, ',') . ']</script>';
} else {
    _e('Events Are Not Found.', 'intellasphere');
}
}