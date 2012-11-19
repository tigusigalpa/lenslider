<?php
if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
    require("../../../wp-load.php");
    $ls = new LenSlider();
    $ret_array = array();
    switch ($_POST['act']) {
        case 'add_banner':
            $n                          = intval($_POST['count_banners']);
            $slidernum                 = $_POST['slidernum'];
            $slider_settings            = LenSlider::lenslider_get_slider_settings($slidernum);
            $ret_array['banners_limit'] = (!empty($slider_settings[LenSlider::$bannersLimitName]))?$slider_settings[LenSlider::$bannersLimitName]:$ls->bannersLimit;
            $slider_settings[LenSlider::$skinName] = sanitize_text_field($_POST['skin_name']);
            if($slider_settings[LenSlider::$skinName] != LenSlider::$defaultSkin) $skinObj = LenSlider::lenslider_get_slider_skin_object($slider_settings[LenSlider::$skinName]);
            $array_merge                = ($skinObj)?$skinObj->bannerMergeArray:false;
            $array_unset                = ($skinObj)?$skinObj->bannerUnsetArray:false;
            $ret_array['banner_item']   = $ls->lenslider_banner_item($n, $slidernum, $ret_array['banners_limit'], $array_merge, $array_unset, $slider_settings['ls_has_thumb']);
            break;
        case 'add_slider':
            $n_sliders                  = intval($_POST['count_sliders']);
            $show_controls              = ($n_sliders == 0)?false:true;
            $skin_name                  = sanitize_text_field($_POST['skin_name']);
            $ret_array['sliders_limit'] = $ls->slidersLimit;
            $ret_array['slider_item']   = $ls->lenslider_slider_item($n_sliders, $ret_array['sliders_limit'], $n_sliders, $skin_name, $show_controls);
            break;
        case 'del_image':
            $attachment_id              = intval($_POST['attachment_id']);
            $delete_thumb               = $_POST['thumb_del'];
            $thumb_id                   = intval($_POST['thumb_id']);
            $slidernum                  = $_POST['slidernum'];
            //$post_id                    = intval($_POST['post_id']);
            $ret_array['del_ret']       = $ls->lenslider_delete_banner($attachment_id, $slidernum, $delete_thumb, $thumb_id/*, $post_id*/);
            break;
        case 'del_thumb':
            $attachment_id              = intval($_POST['attachment_id']);
            $thumb_id                   = intval($_POST['thumb_id']);
            $slidernum                  = $_POST['slidernum'];
            //$post_id                    = $_POST['post_id'];
            $ret_array['del_ret']       = $ls->lenslider_delete_banner($attachment_id, $slidernum, true, $thumb_id/*, $post_id*/, true);
            break;
        case 'del_banner':
            $banner_id                  = intval($_POST['banner_id']);
            $slidernum                  = $_POST['slidernum'];
            $thumb_id                   = intval($_POST['thumb_id']);
            //$post_id                    = intval($_POST['post_id']);
            $ret_array['del_ret']       = $ls->lenslider_delete_banner($banner_id, $slidernum, true, $thumb_id/*, $post_id*/);
            break;
        case 'del_slider':
            $slidernum                 = $_POST['slidernum'];
            $ret_array['del_ret']       = $ls->lenslider_delete_slider($slidernum);
            break;
        case 'get_settings_global':
            $ret_array['ret']           = wp_cache_get('get_settings_global');
            if (false == $ret_array['ret']) {
                $ret_array['ret']       = LenSlider::lenslider_get_array_from_wp_options(LenSlider::$settingsOption);
                wp_cache_set('get_settings_global', $ret_array['ret']);
            }
            break;
        case 'get_settings_local':
            $ret_array['ret']           = wp_cache_get('get_settings_local');
            $slidernum                 = sanitize_text_field($_POST['slidernum']);
            if (false == $ret_array['ret']) {
                $ret_array['ret']       = LenSlider::lenslider_get_slider_settings($slidernum);
                wp_cache_set('get_settings_local', $ret_array['ret']);
            }
            break;
        case 'get_settings_skin':
            $ret_array['ret']           = wp_cache_get('get_settings_skin');
            $slidernum                 = sanitize_text_field($_POST['slidernum']);
            if (false == $ret_array['ret']) {
                $ret_array['ret']       = LenSlider::lenslider_get_skin_settings($slidernum);
                wp_cache_set('get_settings_skin', $ret_array['ret']);
            }
            break;
        case 'del_skin':
            $skin_name                  = sanitize_text_field($_POST['skin_name']);
            $ret_array['del_ret']       = (LenSliderSkins::lenslider_delete_skin($skin_name))?true:false;
            break;
        /*case 'append_post_slider':
            $post_id = $_POST['ls_post_id'];
            $slidernum = $_POST['slidernum'];
            $ret_array['ret'] = $ls->lenslider_post_page_form($post_id, $ls->lenslider_make_post_sliders_fields_array($ls->lenslider_get_slider_banner_fields($slidernum), $slidernum), $slidernum, false, false, true);
            break;
        case 'delete_post_slider':
            $post_id      = intval($_POST['post_id']);
            $slidernum    = $_POST['slidernum'];
            $att_id       = intval($_POST['att_id']);
            $att_thumb_id = intval($_POST['att_thumb_id']);
            $ret_array['del_ret'] = $ls->lenslider_delete_post_banner($post_id, $slidernum, $att_id, $att_thumb_id);
            break;*/
        
        case 'links_variants':
            $to_list = $_POST['to_list'];
            $slidernum = $_POST['slidernum'];
            $name = $_POST['name'];
            $n = $_POST['n'];
            $banner_k = $_POST['banner_k'];
            switch ($to_list) {
                case 'blink_post':
                    $ret_array['ret'] = LenSlider::lenslider_dropdown_posts($slidernum, $banner_k, $n);
                    $ret_array['uth'] = LenSlider::lenslider_banner_hidden($slidernum, 'url_type', 'post');
                    break;
                case 'blink_page':
                    $ret_array['ret'] = LenSlider::lenslider_dropdown_pages($slidernum, $banner_k, $n);
                    $ret_array['uth'] = LenSlider::lenslider_banner_hidden($slidernum, 'url_type', 'page');
                    break;
                case 'blink_cat':
                    $ret_array['ret'] = LenSlider::lenslider_dropdown_categories($slidernum, $banner_k, $n);
                    $ret_array['uth'] = LenSlider::lenslider_banner_hidden($slidernum, 'url_type', 'cat');
                    break;
                default :
                    $ret_array['ret'] = '';
                    $ret_array['uth'] = LenSlider::lenslider_banner_hidden($slidernum, 'url_type', 'ls_simple');
            }
            break;
        
        case 'link_variants_url':
            $slidernum = $_POST['slidernum'];
            $banner_k = $_POST['banner_k'];
            $id = intval($_POST['id']);
            $n = $_POST['n'];
            $url_type = $_POST['url_type'];
            switch ($url_type) {
                case 'page':
                case 'post':
                    $ret_array['url'] = get_permalink($id);
                    $ret_array['uti'] = LenSlider::lenslider_banner_hidden($slidernum, 'url_type_id', $id);
                    $ret_array['ret'] = LenSlider::lenslider_banner_hidden($slidernum, 'ls_link', $ret_array['url']);
                    break;
                case 'cat':
                    $ret_array['url'] = get_category_link($id);
                    $ret_array['uti'] = LenSlider::lenslider_banner_hidden($slidernum, 'url_type_id', $id);
                    $ret_array['ret'] = LenSlider::lenslider_banner_hidden($slidernum, 'ls_link', $ret_array['url']);
                    break;
            }
            break;
    }
    die(json_encode($ret_array));
}?>
