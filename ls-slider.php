<?php
function lenslider_slider_page() {
    if(!is_user_logged_in() || !current_user_can(LenSlider::$capability)) die('-1');
    $ls                 = new LenSlider;
    $site_url           = site_url();
    $slidernum          = esc_attr($_GET['slidernum']);
    if(isset($_REQUEST['ls_update'])) {
        if(wp_verify_nonce($_REQUEST['lenslider_slider_nonce'], $ls->plugin_basename.AUTH_KEY.$site_url) && check_admin_referer($ls->plugin_basename.AUTH_KEY.$site_url, 'lenslider_slider_nonce')) {
            do_action('lenslider_banners_processing', $slidernum, $_POST["bannerhidden"], $_POST['ls_image_mu'], $_POST['ls_image_thumb_mu'], $_POST["binfo"], $_POST["slset"]);
        } else {
            wp_die( __('WordPress nonce not validate!', 'lenslider') );
            return;
        }
    }
    if(isset($_GET['noheader'])) require_once(ABSPATH . 'wp-admin/admin-header.php');
    $sliders_array = $ls->lenslider_get_sliders_array();
    $skin_name          = esc_attr($_GET['skin']);
    $skinObj            = LenSlider::lenslider_get_slider_skin_object($skin_name);
    $skin_change_note   = __("NOTE: After you change the skin for the slider and update the data, slider will be resaved as disabled. Then you’ll be able to change its status on your own to enable it and then update the data.", 'lenslider');
    $empty = false;
    if(!empty($slidernum) && LenSlider::lenslider_is_slider_exists($slidernum) && !empty($skin_name) && LenSliderSkins::lenslider_skin_exists($skin_name)) {
        $title          = sprintf(__('Edit Slider #%s', 'len-sliders'), $slidernum);
        $new_slider     = false;
        $skins_disabled = false;
        $settings_array = LenSlider::lenslider_get_slider_settings($slidernum);
        if(empty($settings_array[LenSlider::$sliderDisenName])) $title .= "&nbsp;&nbsp;<span style=\"color:red\">(".__('Disabled', 'lenslider').")</span>";
        $skin_check     = $settings_array[LenSlider::$skinName];
    }
    elseif(!empty($slidernum) && !empty($_GET['lsnew']) && esc_attr($_GET['lsnew']) == 'true' && !LenSlider::lenslider_is_slider_exists($slidernum) && !empty($skin_name) && LenSliderSkins::lenslider_skin_exists($skin_name)) {
        $title          = sprintf(__('Add new Slider #%s', 'len-sliders'), $slidernum);
        $new_slider     = true;
        $skins_disabled = true;
        $settings_array = $ls->ls_settings;
        $skin_check     = $skin_name;
    }
    else {
        $title = __('Choose a skin for new Slider', 'lenslider');
        $empty = true;
        $new_slider = true;
    }
    ?>
    <div class="wrap columns-2">
        <!--div id="dialog-confirm" title="Empty the recycle bin?">
            <p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>These items will be permanently deleted and cannot be recovered. Are you sure?</p>
        </div-->
        
        <a href="<?php echo admin_url("admin.php?page=".LenSlider::$indexPage);?>" style="text-decoration:none">&larr; <?php _e( 'Back to LenSlider sliders list', 'lenslider');?></a>
        <h2 class="ls_h2">
            <?php echo $title;?>
            <?php if(!$new_slider) :?>
            <a href="<?php echo admin_url("admin.php?page=".LenSlider::$sliderPage."&slidernum=".LenSlider::lenslider_hash()."&lsnew=true");?>" class="add-new-h2 add_new_slider"><?php _e('Add New');?></a>
            <?php endif;?>
        </h2>
        <?php if(!$empty) :?>
        <form id="ls_form" method="post" action="<?php echo admin_url("admin.php?page=".LenSlider::$sliderPage."&slidernum={$slidernum}&skin={$skin_name}&noheader=true")?>" enctype="multipart/form-data">
            <?php wp_nonce_field($ls->plugin_basename.AUTH_KEY.$site_url, 'lenslider_slider_nonce');?>
            <?php echo LenSlider::_lenslider_check_server_capability()?>
            <div id="poststuff">
                <div id="post-body" class="metabox-holder columns-2">
                    <div id="post-body-content">
                        <div id="titlediv">
                            <div id="titlewrap">
                                <label for="title" style="cursor: pointer"><?php printf( __( 'Slider #%s title', 'lenslider' ), $slidernum );?></label>
                                <input type="text" name="slset[<?php echo $slidernum;?>][<?php echo LenSlider::$sliderComment;?>]" size="30" value="<?php echo (array_key_exists(LenSlider::$sliderComment, $settings_array) && !empty($settings_array[LenSlider::$sliderComment]))?$settings_array[LenSlider::$sliderComment]:"";?>" id="title" autocomplete="off" />
                            </div>
                        </div><!-- /titlediv -->
                        <ul id="slidernum_<?php echo $slidernum;?>" class="ls-sortable meta-box-sortables">
                            <?php echo (!empty($skinObj))?$ls->lenslider_banners_items($slidernum, $new_slider, $skinObj):$ls->lenslider_banners_items($slidernum, $new_slider);?>
                        </ul>
                        <div id="postbox-container-2" class="postbox-container">
                            <div class="postbox">
                                <h3 class="hndle ls_cd"><?php _e( 'Manage', 'lenslider' );?></h3>
                                <div class="inside">
                                    <div class="ls_floatleft" style="margin-right:20px;"><div class="ls_load"><a id="banner_slider_<?php echo $slidernum;?>" class="button add_banner" href="javascript:;">+ <?php _e("Add new banner", 'lenslider');?></a></div></div>
                                    <div class="ls_floatleft"><input type="submit" class="button button-primary" name="ls_update" value="<?php _e('Save', 'lenslider');?>" /></div>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="postbox-container-1" class="postbox-container">
                        <div class="inner-sidebar">
                            <div class="postbox">
                                <h3 class="hndle ls_cd"><span><?php _e( 'Control', 'lenslider' );?></span></h3>
                                <div class="inside">
                                    <div class="submitbox">
                                        <div class="misc-pub-section">
                                            <table border="0" width="100%"><tr>
                                                    <td width="50%"><div class="ls_load"><a id="banner_slider_<?php echo $slidernum;?>" class="button add_banner" href="javascript:;">+ <?php _e("Add new banner", 'lenslider');?></a></div></td>
                                                    <td width="50%" align="right"><?php if(LenSlider::lenslider_is_slider_exists($slidernum, $sliders_array)):?><strong><a class="button thickbox" href="<?php echo plugins_url('ls-preview.php', $ls->indexFile)."?slidernum={$slidernum}&keepThis=true&TB_iframe=true&height=600&width=1000";?>"><?php _e( 'Preview', 'lenslider' );?></a></strong><?php endif;?></td>
                                            </tr></table>
                                        </div>
                                        <div class="misc-pub-section">
                                            <table border="0" width="100%"><tr>
                                                    <td width="50%"><input type="submit" class="button button-primary" name="ls_update" value="<?php _e('Save', 'lenslider');?>" /></td>
                                                    <td width="50%" align="right"><?php if(LenSlider::lenslider_is_slider_exists($slidernum, $sliders_array)):?><a class="submitdelete deletion ls-deletion" href="<?php echo $ls->requestIndexURI."&action=delslider&slidernum={$slidernum}&noheader=true";?>"><?php _e( 'Delete Slider', 'lenslider' );?></a><?php endif;?></td>
                                            </tr></table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <?php $clipboard_swf_image = plugins_url('images/clipboard_icon.png', $ls->indexFile);?>
                            <div class="postbox">
                                <h3 class="hndle ls_cd"><span><?php _e( 'Using this Slider', 'lenslider' );?></span></h3>
                                <div class="inside">
                                    <div class="submitbox">
                                        <p><?php _e('Use this slider on posts, pages or custom post types with the following shortcode:', 'lenslider')?></p>
                                        <div class="ls_floatleft"><code>[lenslider id="<?php echo $slidernum;?>"]</code></div>
                                        <div class="ls_floatleft"><embed src="<?php echo plugins_url('swf/clipboard.swf', $ls->indexFile);?>?normal=<?php echo $clipboard_swf_image?>&pressed=<?php echo $clipboard_swf_image?>&hover=<?php echo $clipboard_swf_image?>&clipboard=[lenslider id=&quot;<?php echo $slidernum;?>&quot;]" width="16" height="16"  type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" /></div>
                                        <div class="clear"></div>
                                        <?php $info = "<p><strong>".__('LenSlider WordPress shortcode', 'lenslider')."</strong></p><p><code>[lenslider id=\'{$slidernum}\']</code></p><p></p><p><strong>".__('Also you can insert static PHP-code in your theme files to output slider via following ones:', 'lenslider')."</strong></p><p><code>LenSlider::lenslider_output_slider(\'{$slidernum}\');</code></p><p><code>echo LenSlider::lenslider_output_slider(\'{$slidernum}\', false);</code></p><p><code>echo do_shortcode(\'[lenslider id={$slidernum}]\');</code></p>";?>
                                        <div align="center" style="margin-top:5px"><a class="button" href="javascript:;" onclick="jQuery.alert.open('<?php echo $info;?>')"><?php _e('Additional integrate methods', 'lenslider');?></a></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="postbox">
                                <h3 class="hndle ls_cd"><span><?php _e( 'General Options', 'lenslider' );?></span></h3>
                                <div class="inside">
                                    <div class="submitbox">
                                        <div class="misc-pub-section" align="center">
                                            <a class="button ls_set_skin_settings" id="ls_set_skin_settings_<?php echo $slidernum;?>" href="javascript:;"><?php printf(__("Set %s skin <strong>default settings</strong>", 'lenslider'), $skin_name)?></a>
                                        </div>
                                        <div class="misc-pub-section">
                                            <select class="ls_switch" name="slset[<?php echo $slidernum;?>][<?php echo LenSlider::$sliderDisenName;?>]">
                                                <option value="1"<?php (array_key_exists(LenSlider::$sliderDisenName, $settings_array) && !empty($settings_array[LenSlider::$sliderDisenName]))?selected($settings_array[LenSlider::$sliderDisenName], 1):"";?>><?php _e('Enabled', 'lenslider');?></option>
                                                <option value="0"<?php (array_key_exists(LenSlider::$sliderDisenName, $settings_array) && !empty($settings_array[LenSlider::$sliderDisenName]))?selected($settings_array[LenSlider::$sliderDisenName], 0):"";?>><?php _e('Disabled', 'lenslider');?></option>
                                            </select>
                                        </div>
                                        <div class="misc-pub-section" style="background:#c5ffb8">
                                            <table border="0" width="100%"><tr>
                                                    <td width="50%"><label class="ls_label" for="skin_for_<?php echo $slidernum;?>"><?php _e('Slider skin', 'lenslider');?></label></td>
                                                    <td width="50%"><?php echo LenSliderSkins::lenslider_skins_dropdown("slset[{$slidernum}][".LenSlider::$skinName."]", $skin_check, "skin_for_{$slidernum}", $skins_disabled, "style=\"width:100px\" class=\"swskin swskin_{$slidernum} ls_mtip\" title=\"".sprintf($skin_change_note, $slidernum)."\"");?></td>
                                            </tr></table>
                                        </div>
                                        <div class="misc-pub-section">
                                            <table border="0" width="100%"><tr>
                                                    <td width="50%"><label class="ls_label" for="<?php echo LenSlider::$sliderRandom."_".$slidernum?>"><?php _e('Random', 'lenslider');?></label></td>
                                                    <td width="50%"><input type="checkbox" name="slset[<?php echo $slidernum;?>][<?php echo LenSlider::$sliderRandom;?>]" class="ls_checkbox" id="<?php echo LenSlider::$sliderRandom."_".$slidernum?>"<?php checked($settings_array[LenSlider::$sliderRandom], 1);?> /></td>
                                            </tr></table>
                                        </div>
                                        <?php echo (!empty($skinObj))?$ls->lenslider_slider_settings_add($slidernum, $settings_array, $ls->lenslider_make_default_slider_settings_array($slidernum, $settings_array, $new_slider, $skin_name), 'general', $skinObj->sliderMergeSettingsArray, $skinObj->sliderUnsetSettingsArray):$ls->lenslider_slider_settings_add($slidernum, $settings_array, $ls->lenslider_make_default_slider_settings_array($slidernum, $settings_array, $new_slider, $skin_name));?>
                                    </div>
                                </div>
                            </div>
                            
                            <?php
                            $slider_settings_position = (!empty($skinObj))?$ls->lenslider_slider_settings_add($slidernum, $settings_array, $ls->lenslider_make_default_slider_settings_array($slidernum, $settings_array, $new_slider, $skin_name, 'position'), 'position', $skinObj->sliderMergeSettingsArray, $skinObj->sliderUnsetSettingsArray):$ls->lenslider_slider_settings_add($slidernum, $settings_array, $ls->lenslider_make_default_slider_settings_array($slidernum, $settings_array, $new_slider, $skin_name, 'position'), 'position');
                            if(!empty($slider_settings_position)) :
                            ?>
                            <div class="postbox">
                                <h3 class="hndle ls_cd"><span><?php _e( 'Position', 'lenslider' );?></span></h3>
                                <div class="inside">
                                    <div class="submitbox">
                                        <?php echo $slider_settings_position;?>
                                    </div>
                                </div>
                            </div>
                            <?php endif;?>
                            
                            <?php
                            $slider_settings_thumbs = (!empty($skinObj))?$ls->lenslider_slider_settings_add($slidernum, $settings_array, $ls->lenslider_make_default_slider_settings_array($slidernum, $settings_array, $new_slider, $skin_name, 'thumbs'), 'thumbs', $skinObj->sliderMergeSettingsArray, $skinObj->sliderUnsetSettingsArray):$slider_settings_slidershow = $ls->lenslider_slider_settings_add($slidernum, $settings_array, $ls->lenslider_make_default_slider_settings_array($slidernum, $settings_array, $new_slider, $skin_name, 'thumbs'), 'thumbs');
                            if(!empty($slider_settings_thumbs)) :?>
                                <div class="postbox">
                                    <h3 class="hndle ls_cd"><span><?php _e( 'Thumbs settings', 'lenslider' );?></span></h3>
                                    <div class="inside">
                                        <div class="submitbox">
                                            <?php echo $slider_settings_thumbs;?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif;?>
                            
                            <?php
                            $slider_settings_slidershow = (!empty($skinObj))?$ls->lenslider_slider_settings_add($slidernum, $settings_array, $ls->lenslider_make_default_slider_settings_array($slidernum, $settings_array, $new_slider, $skin_name, 'slideshow'), 'slideshow', $skinObj->sliderMergeSettingsArray, $skinObj->sliderUnsetSettingsArray):$slider_settings_slidershow = $ls->lenslider_slider_settings_add($slidernum, $settings_array, $ls->lenslider_make_default_slider_settings_array($slidernum, $settings_array, $new_slider, $skin_name, 'slideshow'), 'slideshow');
                            if(!empty($slider_settings_slidershow)) :?>
                                <div class="postbox">
                                    <h3 class="hndle ls_cd"><span><?php _e( 'Slideshow', 'lenslider' );?></span></h3>
                                    <div class="inside">
                                        <div class="submitbox">
                                            <?php echo $slider_settings_slidershow;?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif;?>
                            
                            <?php
                            $slider_settings_skin = '';
                            if(!empty($skinObj)) $slider_settings_skin = $ls->lenslider_slider_settings_add($slidernum, $settings_array, $ls->lenslider_make_default_slider_settings_array($slidernum, $settings_array, $new_slider, $skin_name), 'skin', $skinObj->sliderMergeSettingsArray, $skinObj->sliderUnsetSettingsArray);
                            if(!empty($slider_settings_skin)) :?>
                            <div class="postbox">
                                <h3 class="hndle ls_cd"><span><?php _e( 'Skin settings', 'lenslider' );?></span></h3>
                                <div class="inside">
                                    <div class="submitbox">
                                        <?php echo $slider_settings_skin;?>
                                    </div>
                                </div>
                            </div>
                            <?php endif;?>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <?php else:?>
            <?php echo LenSliderSkins::lenslider_skins_dropdown("new_slider_skin");?>
            <br /><br /><a class="button button-hero add_new_slider" id="<?php echo LenSlider::lenslider_hash();?>" href="<?php echo admin_url("admin.php?page=".LenSlider::$sliderPage."&lsnew=true");?>"><?php _e('Select', 'lenslider');?></a>
        <?php endif;?>
    </div>
<?php
}
?>