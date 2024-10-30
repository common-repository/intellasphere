<div>
    <br>
    <form method="post">
        <input type="submit" id="submit" name="submit" value="Flash" style= "cursor:pointer; background:<?Php echo ITSP_SECONDARY_COLOR;?>">
    </form>
</div>
<?php
if (isset($_POST['submit'])) {
    $option_name = 'get_post_details';
    $post_details = get_option($option_name);
    $cache_clear = array(
        'get_brandkit_data',
        'activities_by_since_events',
        'activities_by_since_events_EVENT',
        'activities_by_since_events_PAGE_NEWSLETTER',
        'activities_by_since_events_Reviews',
        'activities_reviews0',
        'activities_reviews1',
        'activities_reviews2',
        'activities_reviews3',
        'activities_reviews4',
        'activities_reviews5',
        'activities_reviews6',
        'activities_reviews7',
        'activities_reviews0on',
        'activities_reviews1on',
        'activities_reviews2on',
        'activities_reviews3on',
        'activities_reviews4on',
        'activities_reviews5on',
        'activities_reviews6on',
        'activities_reviews7on',
        'activities_by_since_events_COUPON'
    );
    if ($post_details) {
        $cache_clear = array_merge($cache_clear, $post_details);
    }
    foreach ($cache_clear as $key => $option_name) {
        $new_value = '';
        if (get_option($option_name) !== false) {
            // The option already exists, so update it.
            update_option($option_name, $new_value);
        } else {
            // The option hasn't been created yet, so add it with $autoload set to 'no'.
            $deprecated = null;
            $autoload = 'no';
            add_option($option_name, $new_value, $deprecated, $autoload);
        }
    }
    
}
