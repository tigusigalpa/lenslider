<?php
/*
Plugin Name: LenSlider
Plugin URI: http://www.lenslider.com/
Description: This plugin allows you easy to generate multiple visual sliders as well as easy to integrate them into any place of your site via slider php-code or shortcode (plugin timyMCE button). A lot of plugin slider skins will help you to visualize your slider.
Author: Igor Sazonov
Version: 2.0
Author URI: http://www.lenslider.com/about-author/
License: GPLv2

Copyright 2013  Igor Sazonov  (email : sovletig@yandex.ru)

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
include_once(dirname(__FILE__).'/lib/lenslider.table.class.php');
include_once(dirname(__FILE__).'/lib/lenslider.widget.class.php');
include_once(dirname(__FILE__).'/ls-settings.php');
include_once(dirname(__FILE__).'/ls-skins.php');
include_once(dirname(__FILE__).'/ls-slider.php');

$ls          = new LenSlider();
function lenslider_index_gallery() {
    if(!is_user_logged_in() || !current_user_can(LenSlider::$capability)) die('-1');
    global $ls;
    /*if(isset($_POST['ls_update'])) do_action('lenslider_banners_processing', $_POST["sliderhidden"], $_POST["bannerhidden"], $_FILES["ls_image"], $_FILES["ls_thumb_image"], $_POST["binfo"], $_POST["slset"]);
    if(isset($_GET['noheader'])) require_once(ABSPATH . 'wp-admin/admin-header.php');*/
    if(!empty($_GET['action'])) {
        $action = esc_attr($_GET['action']);
        switch ($action) {
            case 'delslider':
                if(!empty($_GET['slidernum'])) {
                    $slidernum = esc_attr($_GET['slidernum']);
                    if(LenSlider::lenslider_is_slider_exists($slidernum)) {
                        $ls->lenslider_delete_slider($slidernum, false, $ls->requestIndexURI."&message=1");
                    }
                }
                break;
            case 'dupslider':
                if(!empty($_GET['in_slidernum']) && !empty($_GET['out_slidernum'])) {
                    $in_slidernum  = esc_attr($_GET['in_slidernum']);
                    $out_slidernum = esc_attr($_GET['out_slidernum']);
                    if(LenSlider::lenslider_is_slider_exists($in_slidernum) && !LenSlider::lenslider_is_slider_exists($out_slidernum)) {
                        $ls->lenslider_duplicate_slider($in_slidernum, $out_slidernum);
                    }
                }
                break;
        }
    }
    
    $LS_ListTable = new LenSlider_List_Table();
    $LS_ListTable->prepare_items();
    ?>
    <div class="wrap columns-2">
        <h2 class="ls_h2 ls_floatleft">LenSlider v<?php echo LenSliderSkins::$version?>: <?php _e('Sliders list', 'lenslider');?>
            <a href="<?php echo admin_url("admin.php?page=".LenSlider::$sliderPage."&slidernum=".LenSlider::lenslider_hash()."&lsnew=true");?>" class="add-new-h2 add_new_slider"><?php _e('Add New');?></a>
        </h2>
        <div class="ls_floatleft" style="margin:10px 8px 0 0"><?php echo LenSliderSkins::lenslider_skins_dropdown("new_slider_skin");?></div>
        <div class="ls_floatleft" style="margin-top:14px">&larr; <?php _e( 'Select a skin', 'lenslider' );?></div>
        <div class="clear"></div>
        
        <?php if(get_user_meta(get_current_user_id(), LenSlider::$ls_welcome_umeta)):?>
        <div id="ls-welcome-panel" class="welcome-panel">
            <?php wp_nonce_field('ls-welcome-panel-nonce', 'ls_welcomepanelnonce', false); ?>
            <a class="ls-welcome-panel-close" href="javascript:;"><?php _e('Dismiss', 'lenslider');?></a>
            <div class="welcome-panel-content">
                <h3>Hi everyone!</h3>
                <p class="about-description">LenSlider needs your help. Because its difficult to make FREE plugin <strong style="color:#000">by one person</strong>: idea, programming, HTML, design, site, testing, users support and so on. And then if bug/problem founded by some WP users that create WP sites for money, they can write a negative review for FREE plugin. One of this review You can see on WordPress.org plugin page.</p>
                <p class="about-description">The plugin is really perspective, but needs feature ideas, time, examples, bugs/problems report and also donations. I do not want to turn into a beggar, but I think that <strong style="color:#000">a common cause people can <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=AYEX4C4M5YMWL&lc=US&item_name=LenSlider%20Wordpress%20Plugin&amount=3%2e00&currency_code=USD&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted" target="_blank">leave a small donation</a> to keep the plugin free.</strong></p>
                <p class="about-description">Thanks for understanding!</p>
            </div>
        </div>
        <?php endif;?>
        <?php echo LenSlider::_lenslider_check_server_capability();?>
        <div id="poststuff">
            <div id="post-body" class="metabox-holder columns-2">
                <div id="post-body-content">
                    <form id="sliders-filter" method="get">
                        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
                        <!--input type="hidden" name="noheader" value="true" /-->
                        <?php $LS_ListTable->display() ?>
                    </form>
                </div>
                <div id="postbox-container-1" class="postbox-container">
                    <div class="inner-sidebar">
                        <div class="postbox">
                            <h3 class="hndle ls_cd"><span><?php _e( 'Like this plugin?', 'lenslider' );?></span></h3>
                            <div class="inside">
                                <p><?php _e('You are welcomed for following socials, subscribing for news or giving good reviews', 'lenslider');?>:</p>
                                <ul>
                                    <li class="ls_bar nm wp"><a href="http://wordpress.org/support/view/plugin-reviews/len-slider" target="_blank"><?php _e('Give a good review on WordPress.org', 'lenslider')?></a></li>
                                    <li class="ls_bar nm fb"><a href="http://www.facebook.com/wordpress.lenslider" target="_blank"><?php _e('Join/<strong>Like</strong> LenSlider on <strong>Facebook</strong>', 'lenslider')?></a></li>
                                    <li class="ls_bar nm tw"><a href="https://twitter.com/LenSlider" target="_blank"><?php _e('Follow LenSlider on <strong>Twitter</strong>', 'lenslider')?></a></li>
                                    <li class="ls_bar nm gp"><a href="https://plus.google.com/111774135842240810727" target="_blank"><?php _e('Follow LenSlider on <strong>Google+</strong>', 'lenslider')?></a></li>
                                    <li class="ls_bar nm github"><a href="https://github.com/tigusigalpa/lenslider" target="_blank"><?php _e('LenSlider <strong>Github</strong> repository', 'lenslider')?></a></li>
                                    <li class="ls_bar nm rss"><a href="http://www.lenslider.com/feed/" target="_blank"><?php _e('Subscribe with RSS', 'lenslider')?></a></li>
                                    <!--li class="ls_bar nm sub"><a href="http://wordpress.org/extend/plugins/len-slider/" target="_blank"><?php _e('Subscribe by email', 'lenslider')?></a></li-->
                                </ul>
                            </div>
                        </div>
                        <div class="postbox">
                            <h3 class="hndle ls_cd"><span><?php _e( 'Need Help? Have idea? Found a bug?', 'lenslider' );?></span></h3>
                            <div class="inside">
                                <p><?php _e('If you have any questions/problems or need any help please use some of the ways below', 'lenslider');?>:</p>
                                <ul>
                                    <li class="ls_bar nm wp"><a href="http://wordpress.org/support/plugin/len-slider" target="_blank"><?php _e('Plugin support forum on WordPress.org', 'lenslider')?></a></li>
                                    <li class="ls_bar nm help"><strong><a href="http://www.lenslider.com/forum/" target="_blank"><?php _e('LenSlider standalone support forum', 'lenslider')?></a></strong></li>
                                    <li class="ls_bar nm faq"><a href="http://www.lenslider.com/faq/" target="_blank"><?php _e('LenSlider FAQ page', 'lenslider')?></a></li>
                                    <li class="ls_bar nm idea"><strong><a href="http://www.lenslider.com/suggest-idea/" target="_blank"><?php _e('Suggest idea', 'lenslider')?></a></strong></li>
                                    <li class="ls_bar nm bug"><a href="http://www.lenslider.com/report-bug/" target="_blank"><?php _e('Report Bug', 'lenslider')?></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="postbox">
                            <h3 class="hndle ls_cd"><span><?php _e( 'Donations', 'lenslider' );?></span></h3>
                            <div class="inside">
                                <p><?php _e('Your donation will help encourage and support the plugin’s continued development and better user support', 'lenslider');?>:</p>
                                <ul>
                                    <li class="ls_bar nm wp"><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=AYEX4C4M5YMWL&lc=US&item_name=LenSlider%20Wordpress%20Plugin&amount=3%2e00&currency_code=USD&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted" target="_blank"><?php _e('Make a donation for just $3', 'lenslider')?></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>