<?php
function lenslider_skins_page() {
    $ls_skins = new LenSliderSkins();
    $errors_arr = array(
        1 => __("A folder with the same skin name already exists. Perhaps, the skin is already installed.", 'lenslider'),
        2 => __("You can’t upload the skin called the 'default', the one is pre-installed.", 'lenslider'),
        3 => __("Uploadable skin zip-archive can't be extracted. Internal ZipArchive Class error.", 'lenslider'),
        4 => sprintf(__("Your PHP version (%s) doesnt include a ZipArchive class. You need to upgrade to php 5.2+ or manually upload archive files into skins folder.", 'lenslider'), phpversion()),
        5 => __("This skin is already installed.", 'lenslider'),
        6 => __("Uploadable zip-file is invalid: invalid archive mime-type or incorrect name of skin zip-archive.", 'lenslider')
    );
    if(isset($_FILES['skin_file_zip']['tmp_name'])) $ls_skins->lenslider_unzip_skin($_FILES['skin_file_zip']);
    if(isset($_GET['noheader'])) require_once(ABSPATH . 'wp-admin/admin-header.php');?>
    <div class="wrap columns-2">
        <?php 
        if(isset($_GET['message']) && !isset($_GET['error'])) echo "<div class=\"updated\"><p>".__("LenSlider skins list updated", 'lenslider')."</p></div>";
        elseif(isset($_GET['error'])) echo "<div class=\"updated\"><p>{$errors_arr[$_GET['error']]}</p></div>";
        ?>
        <div id="poststuff" class="metabox-holder has-right-sidebar">
            <!--div class="inner-sidebar">
                <div class="postbox">
                    <h3 class="hndle"><span><?php //_e("About this plugin:", 'lenslider')?></span></h3>
                    <div class="inside"><?php //_e("", 'lenslider');?></div>
                </div>
            </div-->
            <div class="has-sidebar">
                <div id="post-body-content" class="has-sidebar-content">
                    <h2><?php printf(__("LenSlider v%s skins", 'lenslider'), LenSliderSkins::$version);?></h2>
                    <div class="postbox" style="padding:10px;">
                        <form id="ls_slins_submit" action="<?=admin_url("admin.php?page={$ls_skins->skinsPage}&noheader=true")?>" method="post" enctype="multipart/form-data">
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
                    </div><!--postbox-->
                    <div class="ls_metabox ls_rounded ls_shadow">
                        <div class="ls_box_header"><span class="ls_title"><?php _e("Skins", 'lenslider');?></span></div>
                        <div class="ls_box_content">
                            <?php $skins_array = LenSliderSkins::lenslider_skins_folders_array(false);
                            if(!empty($skins_array) && is_array($skins_array)) {?>
                            <ul class="fullwidth">
                                <?php foreach ($skins_array as $skin) {$ls_skins->lenslider_skin_item($skin);}?>
                            </ul>
                            <?php } else _e("No skins available", 'lenslider');?>
                        </div><!--ls_box_content-->
                    </div><!--ls_metabox-->
                </div><!--post-body-content-->
            </div><!--has-sidebar-->
        </div><!--poststuff-->
    </div><!--wrap-->
<?php    
}?>
