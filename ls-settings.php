<?php
function lenslider_settins_page() {
    $ls_settings = new LenSliderSettings();
    if(isset($_POST['ls_update_settings'])) {
        do_action('lenslider_save_settings', 
            LenSliderSettings::lenslider_make_settings_array(
                    $_POST/*array*/,
                    array(/*MAX limits default*/
                        LenSlider::$slidersLimitName => $ls_settings->slidersLimitDefault,
                        LenSlider::$bannersLimitName => $ls_settings->bannersLimitDefault,
                        LenSlider::$maxWidthName     => $ls_settings->imageWidthMAX,
                        LenSlider::$qualityName      => $ls_settings->imageQualityMAX,
                        LenSlider::$maxSizeName      => $ls_settings->imageFileSizeMAX
                    ),
                    array(/*MIN limits default*/
                        LenSlider::$maxWidthName     => $ls_settings->imageWidthMIN,
                        LenSlider::$qualityName      => $ls_settings->imageQualityMIN
                    ),
                    array('ls_update_settings')/*to unset*/
            )
        );
    }
    if(isset($_GET['noheader'])) require_once(ABSPATH.'wp-admin/admin-header.php');
    $settings_array = LenSlider::lenslider_get_array_from_wp_options(LenSlider::$settingsOption);?>
    <div class="wrap columns-2">
        <?php if(isset($_GET['message'])) echo "<div class=\"updated\"><p>".__("LenSlider settings updated", 'lenslider')."</p></div>";?>
        <div id="poststuff" class="metabox-holder has-right-sidebar">
            <!--div class="inner-sidebar">
                <div class="postbox">
                    <h3 class="hndle"><span><?php //_e("About LenSlider plugin:", 'lenslider')?></span></h3>
                    <div class="inside"><?php //_e("", 'lenslider')?></div>
                </div>
            </div-->
            <div class="has-sidebar">
                <div id="post-body-content" class="has-sidebar-content">
                    <h2><?php _e("LenSlider Settings", 'lenslider')?></h2>
                    <form id="ls_settings_form" method="post" action="<?=admin_url("admin.php?page={$ls_settings->settingsPage}&noheader=true")?>">
                        <h3><?php _e("General", 'lenslider')?></h3>
                        <table class="form-table flag-options">
                            <tr>
                                <th scope="row" width="250"><label for="<?=LenSlider::$slidersLimitName?>"><?php printf(__("Sliders limit<br /><span class=\"description\">(max: %d)</span>", 'lenslider'), $ls_settings->slidersLimitDefault)?></label></th>
                                <td><input name="<?=LenSlider::$slidersLimitName?>" type="text" id="<?=LenSlider::$slidersLimitName?>" size="5" value="<?=$settings_array[LenSlider::$slidersLimitName];?>" maxlength="2" /></td>
                            </tr>
                            <tr>
                                <th scope="row" width="250"><label for="<?=LenSlider::$bannersLimitName?>"><?php printf(__("Banners limit for each slider<br /><span class=\"description\">(max: %d)</span>", 'lenslider'), $ls_settings->bannersLimitDefault)?></label></th>
                                <td><input name="<?=LenSlider::$bannersLimitName?>" type="text" id="<?=LenSlider::$bannersLimitName?>" size="5" value="<?=$settings_array[LenSlider::$bannersLimitName];?>" maxlength="2" /></td>
                            </tr>
                            <tr>
                                <th scope="row" width="250"><label for="<?=LenSlider::$cacheName?>"><?php _e("Enable WordPress cache for sliders", 'lenslider')?></label></th>
                                <td><input name="<?=LenSlider::$cacheName?>" type="checkbox" id="<?=LenSlider::$cacheName?>" value="<?=$settings_array[LenSlider::$cacheName];?>" <?php @checked($settings_array[LenSlider::$cacheName], 1)?> /></td>
                            </tr>
                            <tr>
                                <th scope="row" width="250"><label for="<?=LenSlider::$tipsyName?>"><?php _e("Tooltip hint", 'lenslider')?></label></th>
                                <td><input type="checkbox" name="<?=LenSlider::$tipsyName?>" id="<?=LenSlider::$tipsyName?>" value="<?=$settings_array[LenSlider::$tipsyName]?>" <?php @checked($settings_array[LenSlider::$tipsyName], 1)?> />  <label for="ls_tipsy"><?php _e("Enable <a href=\"http://onehackoranother.com/projects/jquery/tipsy/\" target=\"_blank\">tipsy</a> tooltip for control buttons", 'lenslider')?></label></td>
                            </tr>
                        </table>
                        <h3><?php _e("Images", 'lenslider')?></h3>
                        <table class="form-table flag-options">
                            <tr>
                                <th scope="row" width="250"><label for="<?=LenSlider::$maxSizeName?>"><?php printf(__("Upload images maximum size, MB<br /><span class=\"description\">(max: %d)</span>", 'lenslider'), $ls_settings->imageFileSizeMAX)?></label></th>
                                <td><input name="<?=LenSlider::$maxSizeName?>" type="text" id="<?=LenSlider::$maxSizeName?>" size="5" value="<?=$settings_array[LenSlider::$maxSizeName];?>" maxlength="2" /></td>
                            </tr>
                            <tr>
                                <th scope="row" width="250"><label for="<?=LenSlider::$maxWidthName?>"><?php printf(__("Maximum image width, px<br /><span class=\"description\">(min: %1d; max: %2d)<br />proportions are kept</span>", 'lenslider'), $ls_settings->imageWidthMIN, $ls_settings->imageWidthMAX)?></label></th>
                                <td><input name="<?=LenSlider::$maxWidthName?>" type="text" id="<?=LenSlider::$maxWidthName?>" size="5" value="<?=$settings_array[LenSlider::$maxWidthName];?>" maxlength="4" /></td>
                            </tr>
                            <tr>
                                <th scope="row" width="250"><label for="<?=LenSlider::$qualityName?>"><?php printf(__("Maximum quality of uploaded image<br /><span class=\"description\">(min: %1d; max: %2d)<br />percents</span>", 'lenslider'), $ls_settings->imageQualityMIN, $ls_settings->imageQualityMAX)?></label></th>
                                <td><input name="<?=LenSlider::$qualityName?>" type="text" id="<?=LenSlider::$qualityName?>" size="5" value="<?=$settings_array[LenSlider::$qualityName];?>" maxlength="3" /></td>
                            </tr>
                        </table>
                        <h3><?php _e("Help", 'lenslider')?></h3>
                        <table class="form-table flag-options">
                            <tr>
                                <th scope="row" width="250"><label for="<?=LenSlider::$backlink?>"><?php _e("I would like to support plugin creators and place a plugin link to my site", 'lenslider')?></label></th>
                                <td><input type="checkbox" name="<?=LenSlider::$backlink?>" id="<?=LenSlider::$backlink?>" value="<?php if(!empty($settings_array[LenSlider::$backlink])) echo $settings_array[LenSlider::$backlink];?>" <?php if(!empty($settings_array[LenSlider::$backlink])) checked($settings_array[LenSlider::$backlink], 1)?> /> <?php if(!empty($settings_array[LenSlider::$backlink]) && checked($settings_array[LenSlider::$backlink], 1, false)) echo "<span style=\"margin-left:5px;font-weight:bold;color:red\">".__("Thanks! Let Your dreams come true!")."</span>";?></td>
                            </tr>
                        </table>
                        <br /><input type="submit" class="button-primary" name="ls_update_settings" value="<?php _e("Update settings", 'lenslider')?>" />
                    </form>
                </div><!--has-sidebar-content-->
            </div><!--has-sidebar-->
        </div><!--metabox-holder-->
    </div><!--wrap-->
<?php }?>