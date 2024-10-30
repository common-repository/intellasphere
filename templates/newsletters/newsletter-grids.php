 <?php
if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
 $imgurl = ITSP_INTEGRATION__PLUGIN_URL . '/assets/placeholders/newslettersubscription.png';  
echo '<img src='.$imgurl.'  width="100%">';
} else { ?>
<div class="itsp_newsletters_grid <?php echo esc_attr($uid); ?>">
                        <div class="itsp_is_engage_row <?php echo esc_attr($uid) . '-row'; ?>">
                            <?php
                            $lstupmillisec = '';
                            $nstdata = '';
                            $i = 0;
                            foreach ($results as $key => $value) {
                                $i++;
                                if ($i > $lytlimit)
                                    break;
                                $lstupmillisec = $value->lastUpdated->millisec;
                                $title = isset($value->title) ? $value->title : "";
                                $url = isset($value->postUrl) ? preg_replace("(^https?://)", "", $value->postUrl) : '';
                                $organizer = isset($value->owner->channel) ? $value->owner->channel : "";
                                $cpylogo = (isset($value->companyLogo) && $value->companyLogo != '') ? $value->companyLogo : ITSP_INTEGRATION__PLUGIN_URL . '/assets/images/img-placeholder.jpg';
                                $des = isset($value->description) ? $value->description : "";
                                $imgurl = preg_replace("(^https?://)", "", ITSP_INTEGRATION__PLUGIN_URL . '/assets/images/img-placeholder.jpg');
                                if (isset($value->resources[0]->type) && $value->resources[0]->type == 'image') {
                                    $imgurl = isset($value->resources[0]->path) ? preg_replace("(^https?://)", "", $value->resources[0]->path) : '';
                                }
                                ?>
                                <div class="itsp_engage_grid_block"  style="width:<?php print $nbrofcolumn; ?>%;color:<?php echo esc_attr($brandkit_colorpalette['primaryColor']); ?>">
                                    <div class="itsp_native_coupon itsp_native_coupon_style" style="border-width: <?php echo esc_attr($brandkit_colorpalette['borderWidth']); ?>;border-color:<?php echo esc_attr($brandkit_colorpalette['borderColor']); ?>;"> 
                                        <?php if ($imgurl != '')  ?>
                                        <div>  <img src="//<?php print $imgurl; ?>"></div>
                                        <div class="itsp_caption">
                                            <h4 style="color:<?php echo esc_attr($brandkit_colorpalette['primaryTextColor']); ?>; font-size: <?php echo esc_attr($brandkit_fontinfo['h2']); ?>; font-weight: <?php echo esc_attr($brandkit_fontinfo['fontWeight']); ?>;font-family: <?php echo esc_attr($brandkit_fontinfo['fontFamily']); ?>;line-height: <?php echo esc_attr($brandkit_fontinfo['lineHeight']); ?>;"><a class="itsp_coupon_pop <?php echo esc_attr($uniq_pop); ?>" href=<?php echo esc_url($url); ?> style="color:<?php echo esc_attr($brandkit_colorpalette['primaryTextColor']); ?>; font-family: <?php echo esc_attr($brandkit_fontinfo['fontFamily']); ?>;"><?php echo sanitize_text_field($title); ?></a></h4>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                $nstdata .= '{
                                "@context":"http://schema.org",
                                "@type":"NewsArticle",
                                 "mainEntityOfPage": {
                                    "@type": "WebPage",
                                    "@id": "https://google.com/article"
                                  },
                                "headline":"' . esc_attr($title) . '",
                                "image":"' . esc_url($imgurl) . '",
                                "url":"' . esc_url($url) . '",
                                "datePublished":"' . date('M d, Y F Y h:i:s A', (int) ($lstupmillisec / 1000)) . '",
                                "dateModified": "' . date('M d, Y F Y h:i:s A', (int) ($lstupmillisec / 1000)) . '",
                                "description" : "' . trim(strip_tags($des)) . '",
                                "publisher": {
                                "@type": "Organization",
                                "name": "' . esc_attr($organizer) . '",
                                    "logo": {
                                    "@type": "ImageObject",
                                    "url":"' . esc_attr($cpylogo) . '"
                                  }},
                                 "author": {
                                  "@type": "Person",
                                 "name": "' . $organizer . '"
                                }
                              },';
                            }
                           
                            ?>         
                        </div>
                        <div class="<?php echo esc_attr($uid) . '-id'; ?>" data-last-update="<?php echo esc_attr($lstupmillisec); ?>" data-lytlimit="<?php echo esc_attr($lytlimit); ?>" data-load="<?php echo esc_attr($i); ?>">  </div>
                        <!-- The Modal -->
                    </div>   
                    <?php
                    print '<script type="application/ld+json"> [ ' . rtrim($nstdata, ',') . ']</script>';
                    
                    
} 