<?php
if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
 $imgurl = ITSP_INTEGRATION__PLUGIN_URL . '/assets/placeholders/'.$ifmtype.'.png';  
echo '<img src='.$imgurl.'   width="100%">';
} else {
    $overrideBrandKit = "";
    if (isset($instance['brandkiton'])) {
        $overrideBrandKit = $instance['brandkiton'] == 'on' ? "true" : "false";
    } elseif (isset($attributes['brandkiton'])) {
        $overrideBrandKit = $attributes['brandkiton'] == 1 ? "true" : "false";
    }
    echo '<div style="text-align:center;" '
                        . 'class="create_embed_widget"'
                        . 'id='.esc_attr($unqid).' '
                        . 'data-url= '.ITSP_INTELLASPHERE.' '
                        . 'data-type ='.esc_attr($ifmtype).' '
                        . 'data-id='.Itsp_Utility::key().' '
                        . 'data-appId='.esc_attr($ebdid).' '
                        . 'data-dynamicType=null '
                        . 'data-bgColor=' . esc_attr($brandkit_colorpalette['primaryBackgroundColor']) . ' '
                        . 'data-txtColor=' . esc_attr($brandkit_colorpalette['primaryTextColor']) . ' '
                        . 'data-textColor='.esc_attr($brandkit_colorpalette['textColor']).' '
                        . 'data-font=' . esc_attr($brandkit_fontinfo['fontFamily']) . ' '
                        . 'data-btn='.esc_attr($brandkit_colorpalette['buttonBackgroundColor']).' '
                        . 'data-btText='.esc_attr($brandkit_colorpalette['buttonTextColor']).' '
                        . 'data-primaryColor='.esc_attr($brandkit_colorpalette['primaryColor']).' '
                        . 'data-secondaryColor='.esc_attr($brandkit_colorpalette['secondaryColor']).' '
                        . 'data-secondaryBgColor='.esc_attr($brandkit_colorpalette['secondaryBackgroundColor']).' '
                        . 'data-secondaryTxtColor='.esc_attr($brandkit_colorpalette['secondaryTextColor']).' '
                        . 'data-secondaryBtColor='.esc_attr($brandkit_colorpalette['secondaryButtonBackgroundColor']).' '
                        . 'data-secondaryBtnTxtColor='.esc_attr($brandkit_colorpalette['secondaryButtonTextColor']).' '
                        . 'data-isBorderEnable='.esc_attr($borderendn).' '
                        . 'data-borderWidth='.esc_attr($brandkit_colorpalette['borderWidth']).' '
                        . 'data-borderColor='.esc_attr($brandkit_colorpalette['borderColor']).' '
                        . 'data-warningColor='.esc_attr($brandkit_colorpalette['warningColor']).' '
                        . 'data-fontWeight='.esc_attr($brandkit_fontinfo['fontWeight']).' '
                        . 'data-lineHeight='.esc_attr($brandkit_fontinfo['lineHeight']).' '
                        . 'data-h1='.esc_attr($brandkit_fontinfo['h1']).' '
                        . 'data-h2='.esc_attr($brandkit_fontinfo['h2']).' '
                        . 'data-h3='.esc_attr($brandkit_fontinfo['h3']).' '
                        . 'data-h4='.esc_attr($brandkit_fontinfo['h4']).' '
                        . 'data-h5='.esc_attr($brandkit_fontinfo['h5']).' '
                        . 'data-h6='.esc_attr($brandkit_fontinfo['h6']).' '
                        . 'data-p='.esc_attr($brandkit_fontinfo['p']).' '
                        . 'data-buttonStyle='.esc_attr($brandkit_buttoninfo['style']).' '
                        . 'data-buttonRadius='.esc_attr($brandkit_buttoninfo['buttonCornerRadius']).' '
                        . 'data-buttonSize='.esc_attr($brandkit_buttoninfo['size']).' '
                        . 'data-formBorderStyle=BORDER_ALL '
                        . 'data-formInputStyle='.esc_attr($brandkit_forminfo['inputStyle']).' '
                        . 'data-formInputRadius='.esc_attr($brandkit_forminfo['inputCornerRadius']).' '
                        . 'data-style=Embed '
                        . 'data-layout=Single_Column '
                        . 'data-design=Design_1 '
                        . 'data-align=embed '
                        . 'data-timer=0 '
                        . 'data-width='.esc_attr($width).' '
                        . 'data-height='.esc_attr($height).' '
                        . 'data-maxwidth='.esc_attr($mxwidth).' '
                        . 'data-maxheight='.esc_attr($mxheight).' '
                        . 'data-transparentBg='.esc_attr($tsptbkground).' '
                        . 'data-hideTitle='.esc_attr($hidetitle).' '
                        . 'data-hideCompanyName='.esc_attr($showcpyname).' '
                        . 'data-hideCompanyLogo='.esc_attr($showcpylogo).' '
                        .  'data-overrideBrandKit= '.esc_attr($overrideBrandKit).'  '
                        . 'data-readOnly=false >'
                        . '</div><script>var i' . esc_attr($unqid) . ' = 0;var t' . esc_attr($unqid) . ' = setInterval(function() {if(typeof createEmbedWidget === "function"){createEmbedWidget("' . esc_attr($unqid) . '");++i' . esc_attr($unqid) . ';}if (i' . esc_attr($unqid) . ' === 1) clearInterval(t' . esc_attr($unqid) . '); }, 200);</script>';

}