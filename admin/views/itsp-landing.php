<div id="is_loaders" style="display:none;"></div>
<div id="is_connect">
    <div class="popup" style="background:<?php echo ITSP_PRIMARY_COLOR;?>">
        <h1 style="background:<?php echo ITSP_HEADER_COLOR;?>">
            <?php _e('<a href="https://www.intellasphere.com/" target="_blank"><img src="' . ITSP_INTEGRATION__PLUGIN_URL . '/admin/images/logo.png"></a>', 'intellashpere'); ?></h1>
        <a class="close" href="#">&times;</a>
        <div class="content">
            <div id="error" style="color:red; text-align: center;padding: 0px;"> </div>
            <form action="" class="form-container" id="connet-form">
                <ul>
                    <li>
                        <label for="email"><b><?php _e('Email', 'intellashpere'); ?></b></label>
                        <div class="icon_bg"><span class="email-icon icons"></span></div>
                        <input type="text" placeholder="Enter Email" name="email" required>
                    </li>
                    <li>
                        <label for="psw"><b><?php _e('Password', 'intellashpere'); ?></b></label>
                        <div class="icon_bg_psd"><span class="password-icon icons"></span></div>
                        <input type="password" placeholder="Enter Password" name="psw" required>
                    </li>
                </ul>
                <input type="submit" class="btn is-connect-button" style="background-color:<?php echo ITSP_SECONDARY_COLOR;?>" value="Submit">
            </form>
        </div>
    </div>
</div>



<script>
    jQuery(document).on('click', '.is-connect-button', function (e) {
        e.preventDefault();
        jQuery("#is_loaders").css('display', 'block');
        var results = jQuery('#connet-form').serializeArray();
        var email;
        var psw;
        jQuery.each(results, function (key, value) {
            if (value['name'] == 'email') {
                email = value['value'];
            }
            if (value['name'] == 'psw') {
                psw = value['value'];
            }
        });
        if(email ==""||psw==""){
            alert("Email & Password Must Not Be Empty");
            jQuery("#is_loaders").css('display', 'none');
            return false;
        }
        
        var ajax_url = "<?php echo admin_url('admin-ajax.php'); ?>";
        var data = {
            action: 'process_to_connect',
            email: email,
            psw: psw
        };
        jQuery.post(ajax_url, data, function (response) {
            
            if (response.success) {
                location.reload();
            } else {
                jQuery("#error").html('Invalid Url or Username & Password');
                //alert("please enter Valid Credentials");
                jQuery("#is_loaders").css('display', 'none');
            }
        });


    });
    
     
</script>
<style>
    #loader, #is_loaders{
        background: #ffffff url(<?php echo ITSP_INTEGRATION__PLUGIN_URL;?>/admin/images/preloader.gif) no-repeat;
        background-size: 150px 19px;
        background-position: center;
        width: 100%;
        height: 100%;
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        z-index:9999;
    }
    #is_connect{
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        min-height: 100vh;
        width: 100%;
    }
    #wpcontent{
        background:#f1f1f1;
    }
    #is_connect .popup{
        max-width: 560px;
        width: 100%;
        margin: 0px auto;
        background:rgba(255, 255, 255, 0.9);
        padding-bottom: 40px;
    }
    #is_connect .popup h1{
        margin: 0px;
        font-size: 24px;
        font-weight: normal;
        line-height: 24px;
        background: #1c171e;
        text-align: center;
        padding: 10px 0px 3px 0px;
        margin-bottom: 40px;
    }
    #is_connect .popup a{
        display: block;
        margin: auto;
        color: #ffffff;
        font-family: 'Kuro-Regular' !important;
        width:146px;
    }
    #is_connect .popup a:focus{
        outline: none !important;
        border: 0 !important;
        box-shadow: none;
    }
    #is_connect .popup a img{
        width:100%;
    }
    #is_connect .close{
        display:none !important;
    }
    #connet-form label{
        display:none;
    }
    #connet-form{
        max-width: 425px;
        width: 100%;
        margin: 0px auto;
    }
    #connet-form input[type="text"], #connet-form input[type="password"]{
        background: #fff;
        border: 1px solid #d0cfd5;
        border-radius: 2px;
        outline: none;
        font-size: 15px;
        -webkit-appearance: none !important;
        width: 100%;
        height: 40px;
        margin-left: 0px;
        padding: 0 5px 0 43px;
    }
    #connet-form input[type="text"], #connet-form input[type="password"]{
        background-position: 0px 0px;
        left: 0;
        right: 0;
        margin: 5px 0px 10px 0px;
        background-size: 14px 14px;
    }
    #wpbody-content{
        padding-bottom:0px;
    }
    #connet-form input[type="password"]{
        background-position:-25px 0px;
    }
    #connet-form input[type="submit"]{
        height: 40px;
        width: 100%;
        color: #fff;
        font-weight: 600;
        cursor: pointer;
        background: none;
        background-color: #0090ef;
        text-transform: capitalize;
        border: none;
        border-radius: 2px;
        font-size: 16px;
        margin-top: 18px;
    }
    .icon_bg{
        width: 40px;
        height: 40px;
        position: absolute;
        margin-left: 1px;
        margin-top: 12px;
    }
    .email-icon {
        position: absolute;
        background-position: 0px 0px;
        left: 0;
        right: 0;
        margin: auto;
        margin-top: 5px;
    }
    .icons {
        width: 25px;
        height: 25px;
        background: url(<?php echo ITSP_INTEGRATION__PLUGIN_URL;?>/admin/images/icon-sprite.png);
        margin: auto;
        margin-left: 10px;
        cursor: pointer;
    }
    .password-icon {
        position: absolute;
        background-position: -25px 0px;
        left: 0;
        right: 0;
        margin-top: 12px;
    }
    #connet-form ul{
        margin:0px;
        padding:0px;
    }
    #connet-form ul li{
        position:relative;
    }
    @media(max-width:600px){
        #is_connect .popup h1{
            margin-bottom: 20px;
        }
        #connet-form{
            max-width:100%;
        }
        #is_connect .content{
            padding: 0px 20px;
        }
        #connet-form input[type="text"], #connet-form input[type="password"]{
            margin-bottom:15px;
        }
        #is_connect .popup{
            padding-bottom:20px;
            max-width: 300px;
        }
        .auto-fold #wpcontent{
            padding-left:0px;
        }
        #connet-form input[type="submit"]{
            margin-top: 5px;
        }
    }
</style>
