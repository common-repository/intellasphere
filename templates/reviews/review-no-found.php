
<?php
/**
* Review Grid Templates
*/
if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
 $imgurl = ITSP_INTEGRATION__PLUGIN_URL . '/assets/placeholders/offer.png';  
echo '<img src='.$imgurl.' width="100%">';
} else { ?>
<p style='text-align:center'><?php _e('Reviews are Not found', 'intellashpere'); ?></p>;
<?php
if (isset($instance['selectid']) && $instance['selectid'] != 'no_options:NoLink') {
    $link = isset($instance['selectid']) ? explode(':', $instance['selectid']) : "";
    ?>
    <div><a href="<?php echo ITSP_INTELLASPHERE . '/review/' . Itsp_Utility::key() . '/' . $link[0] . '/null#!/;' ?>" target='_blank'>Add Review</a></div>
    <?php
}
} 