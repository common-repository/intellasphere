<?php
if ($results) {
    $selected = !is_null(get_option('is_op_array')) ? get_option('is_op_array') : '';
    $comany_list = (array) $results;
    if (count($comany_list) == 1) {
        ?>
        <div class="disconnect-top-section">
            <div class="disconnect-block" style="background:<?php echo ITSP_PRIMARY_COLOR;?>">
                <h1 style="background:<?php echo ITSP_HEADER_COLOR;?>"><a href="https://www.intellasphere.com/" target="_blank"><img src="<?php echo ITSP_LOGO?>"></a></h1>
                <div class="disconnect-innerblock">
                    <p><?php echo esc_html(reset($comany_list)); ?></p>
                    <a style= "border: 1px solid <?Php echo ITSP_SECONDARY_COLOR;?>; color:<?Php echo ITSP_SECONDARY_COLOR;?>" href="admin.php?page=is-integration&type=clear" class="is-disconnect-button"><?php _e('Disconnect', 'intellashpere'); ?></a>
                    <?php include ITSP_INTEGRATION__PLUGIN_DIR . 'admin/views/itsp_setting_generator.php'; ?>
                </div>
            </div>
            <div>
                <?php
            } else {
                ?>
                <div class="disconnect-top-section">
                    <div class="disconnect-block">
                        <h1><a href="https://www.intellasphere.com/" target="_blank"><img src="<?php echo ITSP_LOGO?>"></a></h1>
                        <?php
                        $options = '<option value="" selected>Choose here</option>';
                        foreach ($comany_list as $key => $val) {
                            $current_selected = (isset($selected['customerid']) && $selected['customerid'] == $key) ? "selected" : '';
                            $options .= '<option value="' . esc_attr($key) . '" ' . esc_attr($current_selected) . '>' . esc_attr($val) . '</option>';
                        }
                        ?>

                        <div class="tabs-section">
                            <h2> <?php _e('Set Company', 'intellashpere'); ?></h2>
                        </div>
        <?php if (!isset($_GET['tab'])) { ?>
                            <div class="wrap">
                                <form method="post" action="admin-post.php">
                                    <input type="hidden" name="action" value="is_save_options" />
            <?php wp_nonce_field('is_op_verify'); ?>
                                    <select  name="customerid">
                                    <?php print $options; ?>  
                                    </select>
                                    <br />
                                    <div class="disconnect-innerblock disconnect-select-company">
                                        <input type="submit" value="<?php _e('Save', 'intellashpere'); ?>" class="button-primary"/>
                                        <a href="admin.php?page=is-integration&type=clear"><?php _e('Disconnect', 'intellashpere'); ?></a>
            <?php include ITSP_INTEGRATION__PLUGIN_DIR . 'admin/views/itsp_setting_generator.php'; ?>
                                    </div>
                                </form>
                            </div>
            <?php
        }
        ?>
                    </div>
                </div>
        <?php
    }
} else {
    include(ITSP_INTEGRATION__PLUGIN_DIR . 'admin/views/itsp-landing.php');
}
?>
        <style>
            .disconnect-top-section{
                position: relative;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-direction: column;
                min-height: 100vh;
            }

            #wpcontent{
                background:#f1f1f1;
            }
            #wpbody-content{
                padding-bottom:0px;
            }
            .disconnect-block{
                max-width:560px;
                width:100%;
                margin:0px auto;
                background: #ffffff;
                text-align:center;
            }
            .disconnect-block h1{
                background:#1c171e;
                padding:9px 0px 3px 0px;
                font-size:24px;
                line-height:24px;
                text-align:center;
                margin-bottom:0px;
                margin-top: 0px;
            }
            .disconnect-innerblock p{
                margin-top:0px !important;
                margin-bottom:20px !important;
                font-size: 14px;
            }
            .disconnect-innerblock a{
                width: 100%;
                border: 1px solid #0090ef;
                padding: 9px 0px;
                display: block;
                text-decoration: none;
                color: #0090ef;
                font-weight: 600;
                font-size:14px;
            }
            .disconnect-innerblock a:focus{
                box-shadow:none;
            }
            .disconnect-block h1 a{
                display: block;
                margin: auto;
                color: #ffffff;
                font-family: 'Kuro-Regular' !important;
                width:146px;
            }
            .disconnect-block h1 a:focus{
                outline:none;
                box-shadow:none;
            }
            .disconnect-block h1 a img{
                width:100%;
            }
            .disconnect-innerblock{
                padding:25px 25px 30px 25px;
            }
            .disconnect-innerblock.disconnect-select-company{
                padding:0px 0px 30px 0px;
            }
            .disconnect-innerblock input[type="submit"]{
                background: #0090ef;
                border: 1px solid #0090ef;
                width: 100%;
                margin-bottom: 15px;
                padding: 10px 10px 11px 10px;
                height: 100%;
                min-height: 100%;
                font-size: 15px;
                line-height: 15px;
                text-shadow: none;
                box-shadow: none;
                font-weight: 600;
            }
            .disconnect-innerblock input[type="submit"]:focus,
            .disconnect-innerblock input[type="submit"]:hover{
                background: #0090ef;
                border: 1px solid #0090ef;
            }
            .disconnect-block .wrap form{
                padding: 0px 25px;
            }
            .disconnect-block select{
                width: 100%;
                display: block;
                height: 32px;
                box-shadow: none;
            }
            .disconnect-block   .tabs-section h2{
                text-align: left;
                padding: 0px 25px 0px 27px;
                font-size: 14px;
                line-height: 16px;
                font-weight: 600;
                margin-bottom: 0;
            }
            .disconnect-block .wrap{
                margin-right:0px;
            }
            @media(max-width:600px){
                .disconnect-block{
                    max-width: 300px;
                }
                .auto-fold #wpcontent{
                    padding-left:0px;
                }
                .disconnect-innerblock {
                    padding: 20px 20px 25px 20px;
                }
            }
 </style>