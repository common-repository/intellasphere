<?php
/**
 * Review Grid Templates
 */
if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
 $imgurl = ITSP_INTEGRATION__PLUGIN_URL . '/assets/placeholders/offer.png';   
echo '<img src='.$imgurl.' width="100%">';
} else {
?>
<div class="itsp_is_engage_container itsp_review_container itsp_list_review_container" style="background:<?php echo esc_attr(isset($instance['reviewbgcolor']) ? $instance['reviewbgcolor'] : ''); ?>; width:100%;">
    <div class="itsp_is_engage_row">
<!--        <h3 style="color:<?php //echo esc_attr($brandkit_colorpalette['primaryTextColor']); ?>;font-size:<?php //echo esc_attr($brandkit_fontinfo['h1']); ?>;font-family:<?php //echo esc_attr($brandkit_fontinfo['fontFamily']); ?>;line-height:<?php //echo esc_attr($brandkit_fontinfo['lineHeight']); ?>;text-align:center;width:100%;"> <?php //echo esc_attr(isset($instance['reviewtitle']) ? $instance['reviewtitle'] : 'Our Client\'s Testmonials'); ?></h3>
        <p style="color:<?php //echo esc_attr($brandkit_colorpalette['primaryTextColor']); ?>;font-family:<?php //echo esc_attr($brandkit_fontinfo['fontFamily']); ?>;line-height:<?php //echo esc_attr($brandkit_fontinfo['lineHeight']); ?>;text-align:center;width:100%;"> We Do The Work.... You Get More Customers & More Sales!</p>-->
        <?php
        $ci = 0;
        foreach ($revresults as $revdata) {// $revdata['rating']  
            $ci++;
            if ($ci > $lytlimit)
                break;
            $pstrating = ($revdata['rating'] == -2) ? 1 : ($revdata['rating'] == -1 ? 2 : ($revdata['rating'] == 0 ? 3 : ($revdata['rating'] == 1 ? 4 : ($revdata['rating'] == 2 ? 5 : ''))));
            $ngtrating = ($pstrating == 5) ? 0 : (($pstrating == 4) ? 1 : (($pstrating == 3) ? 2 : (($pstrating == 2) ? 3 : (($pstrating == 1) ? 4 : ($pstrating == 0 ? 5 : '')))));
            $crttime = isset($revdata['createdTime']) ? date('M d y, h:i:s A', intVal($revdata['createdTime'] / 1000)) : "";
            $revstructure[] = array(
                "datePublished" => date("Y-m-d", intVal($revdata['createdTime'] / 1000)),
                "description" => esc_attr($revdata['message']),
                "author" => esc_attr($revdata['displayName']),
                "name" => esc_attr($revdata['displayName']),
                "reviewRating" => array(
                    "@type" => "Rating",
                    "bestRating" => "5",
                    "ratingValue" => esc_attr($revdata['rating']),
                    "worstRating" => "1"
                )
            );
            ?>
            <div class="itsp_review_block_main">
                <div class="tsp_review_block"  style="border-width: <?php echo esc_attr($brandkit_colorpalette['borderWidth']); ?>;border-color: <?php echo esc_attr($brandkit_colorpalette['borderColor']); ?>; background-color:<?php echo esc_attr($brandkit_colorpalette['primaryBackgroundColor']); ?>" >
                    <div class="">
                        <div class="itsp_rating_input">
                            <?php for ($i = 0; $i < $pstrating; $i++) { ?>
                                <span data-value="<?php print $i; ?>" class="fa fa-star" style="color:<?php echo esc_attr($brandkit_colorpalette['secondaryButtonTextColor']); ?>; font-size:<?php echo esc_attr($brandkit_fontinfo['h5']); ?>"></span>  
                                <?php
                            }
                            for ($i = 0; $i < $ngtrating; $i++) {
                                ?>
                                <span data-value="5" class="fa fa-star-o"  style="color:<?php echo esc_attr($brandkit_colorpalette['secondaryButtonTextColor']); ?>; font-size:<?php echo esc_attr($brandkit_fontinfo['h5']); ?>;"></span> 
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                    <p class='itsp_review_description' style='color:<?php echo esc_attr($brandkit_colorpalette['primaryTextColor']); ?>; font-size:<?php echo esc_attr($brandkit_fontinfo['p']); ?>;line-height:<?php echo esc_attr($brandkit_fontinfo['lineHeight']); ?>;font-family:<?php echo esc_attr($brandkit_fontinfo['fontFamily']); ?>;'><?php echo esc_attr($revdata['message']); ?></p>
                    <h3 class="itsp_company_name" style='color:<?php echo esc_attr($brandkit_colorpalette['secondaryTextColor']); ?>; font-size:<?php echo esc_attr($brandkit_fontinfo['h2']); ?>;line-height:<?php echo esc_attr($brandkit_fontinfo['lineHeight']); ?>;font-weight:<?php echo esc_attr($brandkit_fontinfo['fontWeight']); ?>;font-family:<?php echo esc_attr($brandkit_fontinfo['fontFamily']); ?>;'><?php echo esc_attr($revdata['displayName']); ?></h3>
                </div>

            </div>
        <?php }
        ?>
    </div>
    <?php
    if (isset($instance['selectid']) && $instance['selectid'] != 'no_options:NoLink' && $instance['selectid']) {
        $addreview = explode(':', $instance['selectid']);
        $revlink = isset($addreview[0]) ? $addreview[0] : '';
        ?>
        <div class="itsp_add_review"  style="border-radius: <?php echo ($brandkit_buttoninfo['style'] == "RECTANGLE") ? "0px" : esc_attr($brandkit_buttoninfo['buttonCornerRadius']); ?>;background:<?php echo esc_attr($brandkit_colorpalette['buttonBackgroundColor']); ?>;font-size:<?php echo esc_attr($brandkit_fontinfo['p']); ?>;line-height:<?php echo esc_attr($brandkit_fontinfo['lineHeight']); ?>;font-family:<?php echo esc_attr($brandkit_fontinfo['fontFamily']); ?>;"><a  style="color:<?php echo esc_attr($brandkit_colorpalette['buttonTextColor']); ?>  !important;background:<?php echo esc_attr($brandkit_colorpalette['buttonBackgroundColor']); ?>;font-size:<?php echo esc_attr($brandkit_fontinfo['p']); ?>;line-height:<?php echo esc_attr($brandkit_fontinfo['lineHeight']); ?>;font-family:<?php echo esc_attr($brandkit_fontinfo['fontFamily']); ?>;" href="<?php echo ITSP_INTELLASPHERE . '/review/' . Itsp_Utility::key() . '/' . $revlink . '/null#!/;' ?>" target='_blank'>Add Review</a></div>
    <?php } ?>
</div>

<?php } ?>