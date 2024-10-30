<?php
if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
 $imgurl = ITSP_INTEGRATION__PLUGIN_URL . '/assets/images/img-placeholder.jpg';  
echo '<img src='.$imgurl.'  width="100%">';
} else {
echo '<div class="create_embed_widget" id="' . esc_attr($unqid) . '"
     data-url="'.ITSP_INTELLASPHERE.'"
     data-type =banner 
     data-id='.Itsp_Utility::key().'
     data-appId=' . esc_attr($response->postId) . ' 
     data-dynamicType=  ' . esc_attr($name) . '
     data-bgColor='.esc_attr($secbgcolor).' 
     data-txtColor=' .esc_attr($pritextcolor). ' 
     data-textColor=' . esc_attr($txtcolor) . ' 
     data-font=' . esc_attr($fntfamily) . ' 
     data-btn='. esc_attr($bttxtcolor).' 
     data-btText='.esc_attr($bttxtcolor) . '
     data-primaryColor=' . esc_attr($pricolor ) . '
     data-secondaryColor=' . esc_attr($seccolor) . '
     data-secondaryBgColor=' . esc_attr($secbgcolor) . '
     data-secondaryTxtColor=' . esc_attr($sectextcolor) . ' 
     data-secondaryBtColor=' . esc_attr($secbtbgcolor) . '
     data-secondaryBtnTxtColor='. esc_attr($secbtntxtcolor) .'
     data-isBorderEnable=' . esc_attr($showborder) . '  
     data-borderWidth=' . esc_attr($borderwidth) . '  
     data-borderColor=' . esc_attr($borcolor). '
     data-warningColor='. esc_attr($warcolor). ' 
     data-fontWeight=' . esc_attr($fntweight) . '  
     data-lineHeight=' . esc_attr($linheight) . ' 
     data-h1=' . esc_attr($h1) . ' 
     data-h2=' . esc_attr($h2) . ' 
     data-h3=' . esc_attr($h3) . ' 
     data-h4=' . esc_attr($h4) . ' 
     data-h5=' . esc_attr($h5) . ' 
     data-h6=' . esc_attr($h6) . ' 
     data-p=' .  esc_attr($ptag) . ' 
     data-buttonStyle=' . esc_attr($btnstyle) . '  
     data-btncorradius=' . esc_attr($btncrnradius) . ' 
     data-buttonSize=' . esc_attr($btnsize) . '
     data-formBorderStyle=' . esc_attr($iptborder) . '  
     data-formInputStyle=' . esc_attr($iptstyle) . ' 
     data-trxtfdshape= '. esc_attr($iptcrnradius) . '
     data-style=' . esc_attr($dtastyle) . ' 
     data-layout=Single_Column 
     data-design=Design_1 
     data-align=' . esc_attr($align) . '
     data-timer=' . esc_attr($response->displayDelay) . ' 
     data-transparentBg=' . esc_attr($tsptbkground) . ' 
     data-height=' . esc_attr($height) . '  
     data-hideTitle=' . esc_attr($hidetitle) . '  
     data-hideCompanyLogo=' . esc_attr($showcpylogo) . '   
     data-hideCompanyName=' . esc_attr($showcpyname) . '  
     data-readOnly=false>
</div>';
}