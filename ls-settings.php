<?php
function lenslider_settins_page() {
    if(!is_user_logged_in() || !current_user_can(LenSlider::$capability)) die('-1');
    $ls_settings = new LenSliderSettings();
    $site_url = site_url();
    if(isset($_REQUEST['ls_update_settings'])) {
        if(wp_verify_nonce($_REQUEST['lenslider_settings_nonce'], $ls_settings->plugin_basename.AUTH_SALT.$site_url) && check_admin_referer($ls_settings->plugin_basename.AUTH_SALT.$site_url, 'lenslider_settings_nonce')) {
            do_action('lenslider_save_settings', 
                LenSliderSettings::lenslider_make_settings_array(
                        $_POST/*array*/,
                        array(/*MAX limits default*/
                            LenSlider::$slidersLimitName => LenSlider::$slidersLimitDefault,
                            LenSlider::$bannersLimitName => LenSlider::$bannersLimitDefault,
                            LenSlider::$maxSizeName      => $ls_settings->imageFileSizeMAX
                        ),
                        array(/*MIN limits default*/),
                        array('ls_update_settings')/*to unset*/
                )
            );
        } else {
            wp_die( __('WordPress nonce not validate!', 'lenslider') );
            return;
        }
    }
    if(isset($_GET['noheader'])) require_once(ABSPATH.'wp-admin/admin-header.php');
    $settings_array = $ls_settings->ls_settings;?>
    <div class="wrap columns-2">
        <h2 class="ls_h2"><?php printf(__("LenSlider %s Settings", 'lenslider'), LenSlider::$version);?></h2>
        <?php echo LenSlider::_lenslider_check_server_capability()?>
        <?php if(isset($_GET['message'])) echo "<div class=\"updated\"><p>".__("LenSlider settings updated", 'lenslider')."</p></div>";?>
        <div id="poststuff">
            <div id="post-body" class="metabox-holder columns-2">
                <div id="post-body-content">
                    <form id="ls_settings_form" method="post" action="<?php echo admin_url("admin.php?page=".LenSlider::$settingsPage."&noheader=true")?>">
                        <?php wp_nonce_field($ls_settings->plugin_basename.AUTH_SALT.$site_url, 'lenslider_settings_nonce');?>
                        <table class="wp-list-table widefat ls_widefat" cellspacing="0">
                            <thead>
                                <tr>
                                    <th scope="col" class="manage-column" colspan="3"><?php _e('Global LenSlider Settings', 'lenslider')?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="menu-item-handle">
                                    <th scope="col" class="manage-column" colspan="3"><span class="ls_izag i_settings_20"><?php _e('General', 'lenslider');?></span></th>
                                </tr>
                                <tr class="alternate">
                                    <td width="240" align="right"><label for="<?php echo LenSlider::$cacheName?>"><?php _e('WordPress Cache', 'lenslider');?></label></td>
                                    <td width="170"><input name="<?php echo LenSlider::$cacheName?>" type="checkbox" class="ls_checkbox" id="<?php echo LenSlider::$cacheName?>" value="<?php echo $settings_array[LenSlider::$cacheName];?>" <?php @checked($settings_array[LenSlider::$cacheName], 1)?> /></td>
                                    <td><span style="color:#9d9daf"><?php _e('Cache your Sliders via WordPress standart Object Cache to make Sliders output more quickly.', 'lenslider');?></span></td>
                                </tr>
                                <tr class="menu-item-handle">
                                    <th scope="col" class="manage-column" colspan="3">
                                        <span class="ls_izag i_media_20"><?php _e('Media files', 'lenslider');?></span>
                                        <span class="description"><?php _e('These options has a lower priority for Slider settings', 'lenslider')?></span>
                                    </th>
                                </tr>
                                <tr class="alternate">
                                    <td width="240" align="right"><label for="<?php echo LenSlider::$maxSizeName?>"><?php printf(__("Upload images maximum size, MB<br /><span class=\"description\">(max: %d)</span>", 'lenslider'), $ls_settings->imageFileSizeMAX)?></label></td>
                                    <td width="170"><input name="<?php echo LenSlider::$maxSizeName?>" type="text" id="<?php echo LenSlider::$maxSizeName?>" value="<?php echo $settings_array[LenSlider::$maxSizeName];?>" maxlength="2" /></td>
                                    <td><span style="color:#9d9daf"><?php _e('Limit for uploaded files size in MB. This option is to protecting your server and WordPress core.', 'lenslider');?></span></td>
                                </tr>
                                <tr class="menu-item-handle">
                                    <th scope="col" class="manage-column" colspan="3"><span class="ls_izag i_help_20"><?php _e('Help', 'lenslider');?></span></th>
                                </tr>
                                <tr class="alternate">
                                    <td width="240" align="right"><label for="<?php echo LenSlider::$backlink?>"><?php _e("I would like to support plugin creators and place a plugin link to my site footer", 'lenslider')?></label></td>
                                    <td width="170"><input type="checkbox" class="ls_checkbox" name="<?php echo LenSlider::$backlink?>" id="<?php echo LenSlider::$backlink?>" value="<?php if(!empty($settings_array[LenSlider::$backlink])) echo $settings_array[LenSlider::$backlink];?>" <?php if(!empty($settings_array[LenSlider::$backlink])) checked($settings_array[LenSlider::$backlink], 1)?> /> <?php if(!empty($settings_array[LenSlider::$backlink]) && checked($settings_array[LenSlider::$backlink], 1, false)) echo "<span style=\"margin-left:5px;font-weight:bold;color:red\">".__("Thanks! Let Your dreams come true!")."</span>";?></td>
                                    <td><span style="color:#9d9daf"><?php _e('If you like the plugin and if it\'s possible, you can place a link to LenSlider site in your site footer just saving this option as checked. Thanks before!', 'lenslider');?></span></td>
                                </tr>
                            </tbody>
                        </table><br /><br />
                        <table class="wp-list-table widefat ls_widefat" cellspacing="0">
                            <thead>
                                <tr>
                                    <th scope="col" class="manage-column"><?php _e('Save', 'lenslider')?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="alternate">
                                    <td><input type="submit" class="button-primary" name="ls_update_settings" value="<?php _e("Update settings", 'lenslider')?>" /></td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                </div>
                <div id="postbox-container-1" class="postbox-container">
                    <div class="inner-sidebar">
                        <div class="postbox">
                            <h3 class="hndle"><span><?php //_e("About LenSlider plugin:", 'lenslider')?></span></h3>
                            <div class="inside"><?php //_e("", 'lenslider')?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!--metabox-holder-->
    </div><!--wrap-->
<?php }?>