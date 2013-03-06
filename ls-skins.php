<?php
function lenslider_skins_page() {
    if(!is_user_logged_in() || !current_user_can(LenSlider::$capability)) die('-1');
    $ls_skins = new LenSliderSkins();
    $site_url = site_url();
    $errors_arr = array(
        1 => __("A folder with the same skin name already exists. Perhaps, the skin is already installed.", 'lenslider'),
        2 => __("You can’t upload the skin called the 'default', the one is pre-installed.", 'lenslider'),
        3 => __("Uploadable skin zip-archive can't be extracted. Internal ZipArchive Class error.", 'lenslider'),
        4 => sprintf(__("Your PHP version (%s) doesnt include a ZipArchive class. You need to upgrade to php 5.2+ or manually upload archive files into skins folder.", 'lenslider'), phpversion()),
        5 => __("This skin is already installed.", 'lenslider'),
        6 => __("Uploadable zip-file is invalid: invalid archive mime-type or incorrect name of skin zip-archive.", 'lenslider')
    );
    if(isset($_FILES['skin_file_zip']['tmp_name'])) {
        if(wp_verify_nonce($_REQUEST['lenslider_skins_nonce'], $ls_skins->plugin_basename.$site_url.AUTH_SALT) && check_admin_referer($ls_skins->plugin_basename.$site_url.AUTH_SALT, 'lenslider_skins_nonce')) {
            $ls_skins->lenslider_unzip_skin($_FILES['skin_file_zip']);
        } else {
            wp_die( __('WordPress nonce not validate!', 'lenslider') );
            return;
        }
    }
    if(isset($_GET['noheader'])) require_once(ABSPATH . 'wp-admin/admin-header.php');?>
    <div class="wrap columns-2">
        <h2 class="ls_h2"><?php _e( 'LenSlider Skins', 'lenslider' );?></h2>
        <?php echo LenSlider::_lenslider_check_server_capability()?>
        <?php 
        if(isset($_GET['message']) && !isset($_GET['error'])) echo "<div class=\"updated\"><p>".__("LenSlider skins list updated", 'lenslider')."</p></div>";
        elseif(isset($_GET['error'])) echo "<div class=\"updated\"><p>{$errors_arr[$_GET['error']]}</p></div>";
        ?>
        <div id="poststuff">
            <div id="post-body" class="metabox-holder columns-2">
                <div id="post-body-content">
                    <form id="ls_slins_submit" action="<?php echo admin_url("admin.php?page=".LenSlider::$skinsPage."&noheader=true")?>" method="post" enctype="multipart/form-data">
                        <?php wp_nonce_field($ls_skins->plugin_basename.$site_url.AUTH_SALT, 'lenslider_skins_nonce');?>
                        <table border="0" width="100%">
                            <tr>
                                <td width="70%">
                                    <table border="0">
                                        <tr>
                                            <td><?php _e("Upload a new skin (*.zip)", 'lenslider');?></td>
                                            <td style="padding-left:20px;"><input type="file" name="skin_file_zip" /></td>
                                            <td style="padding-left:20px;"><input type="submit" class="button-primary" name="ls_update_settings" value="<?php _e("Upload skin", 'lenslider');?>" /></td>
                                        </tr>
                                    </table>
                                </td>
                                <td width="30%" align="right"></td>
                            </tr>
                        </table>
                    </form>
                    <!--div class="ls_metabox ls_rounded ls_shadow">
                        <div class="ls_box_header"><span class="ls_title"><?php //_e("Skins", 'lenslider');?></span></div>
                        <div class="ls_box_content">
                            <?php //$skins_array = LenSliderSkins::lenslider_skins_folders_array(false);//die(var_dump($skins_array));
                            //if(!empty($skins_array) && is_array($skins_array)) {?>
                            <ul class="fullwidth">
                                <?php //foreach ($skins_array as $skin) {$ls_skins->lenslider_skin_item($skin);}?>
                            </ul>
                            <?php //} else _e("No skins available", 'lenslider');?>
                        </div>
                    </div-->
                    <?php
                    $skins_array = LenSliderSkins::lenslider_skins_folders_array(false);
                    if(!empty($skins_array) && is_array($skins_array)) {
                        foreach ($skins_array as $skin) {
                            $ls_skins->lenslider_skin_item($skin);
                        }
                    }
                    ?>
                    <div class="clear"></div>
                </div>
                <div id="postbox-container-1" class="postbox-container">
                    <div class="inner-sidebar">
                        
                    </div>
                </div>
            </div>
        </div><!--poststuff-->
    </div><!--wrap-->
<?php    
}?>
