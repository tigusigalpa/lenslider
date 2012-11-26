<?php
/*
Plugin Name: LenSlider
Plugin URI: http://www.lenslider.com/
Description: This plugin allows you easy to generate multiple visual sliders as well as easy to integrate them into any place of your site via slider php-code or shortcode (plugin timyMCE button). A lot of plugin slider skins will help you to visualize your slider.
Author: Igor Sazonov
Version: 1.1.1
Author URI: http://www.lenslider.com/about-author/
License: GPLv2

Copyright 2012  Igor Sazonov  (email : sovletig@yandex.ru)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 3, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

include_once(dirname(__FILE__).'/lib/lenslider.class.php');
include_once(dirname(__FILE__).'/lib/lenslider.settings.class.php');
include_once(dirname(__FILE__).'/lib/lenslider.skins.class.php');
include_once(dirname(__FILE__).'/ls-settings.php');
include_once(dirname(__FILE__).'/ls-skins.php');

$ls          = new LenSlider();
$skins_array = $ls->lenslider_get_used_skins();
function lenslider_index_gallery() {
    global $ls, $skins_array;
    if(isset($_POST['ls_update'])) do_action('lenslider_banners_processing', $_POST["sliderhidden"], $_POST["bannerhidden"], $_FILES["ls_image"], $_FILES["ls_thumb_image"], $_POST["binfo"], $_POST["slset"]);
    if(isset($_GET['noheader'])) require_once(ABSPATH . 'wp-admin/admin-header.php');?>
    <div class="wrap">
        <h2 class="ls_h2">LenSlider v<?=LenSliderSkins::$version?></h2>
        <form id="ls_form" method="post" action="<?=admin_url("admin.php?page={$ls->indexPage}&noheader=true")?>" enctype="multipart/form-data">
            <table border="0" width="100%">
                <tr>
                    <td valign="top">
                        <div id="lensliders"></div>
                        <?php echo $ls->lenslider_get_sliders_admin($skins_array);?>
                    </td>
                    <td valign="top" width="280" style="padding-left:20px">
                        <div style="position:relative;">
                            <div class="ls_metabox2 ls_rounded ls_shadow" id="ls_save_metabox">
                                <div class="ls_box_header">
                                    <span class="ls_title"><?php _e('Save', 'lenslider');?></span>
                                </div><!--ls_box_header-->
                                <ul class="ls_linear">
                                    <li>
                                        <span class="ls_zag"><?php _e('Select skin:', 'lenslider')?></span>
                                        <?=LenSliderSkins::lenslider_skins_dropdown("slider_ajax_skins", LenSlider::$defaultSkin, false, "style=\"width:100%;margin-bottom:15px\"")?>
                                        <div class="ls_load"><a class="ls_minibutton add_slider" href="javascript:;"><span class="plus"><?php _e('Add new slider', 'lenslider');?></span></a></div>
                                    </li>
                                    <li>
                                        <span class="ls_zag"><?php _e('Sliders list', 'lenslider')?></span>
                                        <?php echo LenSlider::lenslider_sliders_list()?>
                                    </li>
                                    <li>
                                        <div align="center"><input type="submit" class="ls_bbutton" name="ls_update" value="<?php _e('Submit LenSlider data', 'lenslider');?>" /></div>
                                    </li>
                                </ul><!--ls_linear-->
                            </div><!--ls_metabox2-->
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </div><!--wrap-->
<?php } ?>