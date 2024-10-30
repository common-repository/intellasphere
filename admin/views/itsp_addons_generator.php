<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="addon-block">
    <h1><a href="https://www.intellasphere.com/" target="_blank"><img src="<?php echo ITSP_INTEGRATION__PLUGIN_URL; ?>/admin/images/logo.png"></a></h1>
</div>
<div class="addon-container">
    <?php
	$active_plugins = apply_filters('active_plugins', get_option('active_plugins'));
    if (in_array('ninja-forms/ninja-forms.php', $active_plugins)) {
        if (isset($_GET['ninja']) && $_GET['ninja'] != '') {
            if (get_option('is_ninja_addon') !== false) {
                update_option('is_ninja_addon', sanitize_text_field($_GET['ninja']));
            } else {
                add_option('is_ninja_addon', sanitize_text_field($_GET['ninja']));
            }
        }
        $ninja = get_option('is_ninja_addon');
     
        if ($ninja == 'inactive') {
          echo "<div class='ninja-form-block'><img src=" . ITSP_INTEGRATION__PLUGIN_URL . "/admin/images/ninjaform.png> <a href=" . admin_url('admin.php?page=is-addons-page&ninja=active') . "> Ninja Form Active</a></div>";
        } else if ($ninja == 'active') {
            echo "<div class='ninja-form-block'><img src=" . ITSP_INTEGRATION__PLUGIN_URL . "/admin/images/ninjaform.png> <a href=" . admin_url('admin.php?page=is-addons-page&ninja=inactive') . "> Ninja Form inActive</a></div>";
        } else {
            echo "<div class='ninja-form-block'><img src=" . ITSP_INTEGRATION__PLUGIN_URL . "/admin/images/ninjaform.png> <a href=" . admin_url('admin.php?page=is-addons-page&ninja=active') . "> Ninja Form Active</a></div>";
        }
    } else {
        echo "<div class='gravity-form-block'><img src=" . ITSP_INTEGRATION__PLUGIN_URL . "/admin/images/ninjaform.png> <a href='#'>Please Active Ninja Form Plugin</a></div>";
    }

    if (method_exists('GFForms', 'include_addon_framework')) {
        if (isset($_GET['gravity']) && $_GET['gravity'] != '') {
            if (get_option('is_gravity_addon') !== false) {
                update_option('is_gravity_addon', sanitize_text_field(['gravity']));
            } else {
                add_option('is_gravity_addon', sanitize_text_field($_GET['gravity']));
            }
        }
        $gravity = get_option('is_gravity_addon');
        if ($gravity == 'inactive') {

            echo "<div class='gravity-form-block'><img src=" . ITSP_INTEGRATION__PLUGIN_URL . "/admin/images/gravity.png> <a href=" . admin_url('admin.php?page=is-addons-page&gravity=active') . "> Gravity Form Active</a></div>";
        } else if ($gravity == 'active') {
            echo "<div class='gravity-form-block'><img src=" . ITSP_INTEGRATION__PLUGIN_URL . "/admin/images/gravity.png> <a href=" . admin_url('admin.php?page=is-addons-page&gravity=inactive') . ">Gravity Form InActive</a></div>";
        } else {
            echo "<div  class='gravity-form-block'><img src=" . ITSP_INTEGRATION__PLUGIN_URL . "/admin/images/gravity.png> <a href=" . admin_url('admin.php?page=is-addons-page&gravity=active') . ">Gravity Form Active</a></div>";
        }
    } else {
        echo "<div class='gravity-form-block'><img src=" . ITSP_INTEGRATION__PLUGIN_URL . "/admin/images/gravity.png> <a href='#'>Please Active Gravity Form Plugin</a></div>";
    }
    ?>
</div>
<style>
    #wpcontent{
        background:#f1f1f1;
    }
    #wpcontent{
        padding-left:0px;
    }
    .addon-block h1{
        margin: 0px;
        font-size: 24px;
        font-weight: normal;
        line-height: 24px;
        background: #1c171e;
        text-align: center;
        padding: 10px 0px 3px 0px;
        margin-bottom: 40px;
    }
    .addon-block a{
        display: block;
        margin: auto;
        color: #ffffff;
        font-family: 'Kuro-Regular' !important;
        width:146px;
    }
    .addon-block a:focus{
        outline: none !important;
        border: 0 !important;
        box-shadow: none;
    }
    .addon-block a img{
        width:100%;
    }
    .ninja-form-block, .gravity-form-block{
        width:400px;
        max-width:100%;
        display:inline-block;
        padding:10px;
    }
    .ninja-form-block img, .gravity-form-block img{
        width:100%;
    }
    .addon-container{
        max-width:991px;
        width:100%;
        margin:0px auto;
        text-align:center;
    }
    .addon-container a{
        padding: 10px 0px 0px 0px;
        text-align: center;
        color: #444;
        font-size: 16px;
        display:block;
        font-weight:600;
    }
    .addon-container a:focus, .addon-container a:hover{
        box-shadow:none;
    }
    @media(max-width:991px){
        .addon-container{
            max-width:100%;
        }
    }
</style>

