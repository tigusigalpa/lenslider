<?php require("../../../wp-load.php");
$slidernum = strtolower($_GET['slidernum']);
$slider_settings = LenSlider::lenslider_get_slider_settings($slidernum);
$skin_name = $slider_settings['ls_slider_skin'];
$skinObjStatic = LenSliderSkins::lenslider_get_skin_params_object($skin_name);
wp_deregister_script('admin-bar');
wp_deregister_style('admin-bar');
remove_action('wp_footer','wp_admin_bar_render',1000);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('stylesheet_url');?>" />
    <?php
    if(!empty($skinObjStatic->cssFiles) && is_array($skinObjStatic->cssFiles)) {
        foreach ($skinObjStatic->cssFiles as $filename) {
            $reg_name = str_ireplace(".css", '', basename($filename)."-{$skin_name}");
            wp_register_style($reg_name, str_ireplace(ABSPATH, LenSlider::$siteurl."/", $filename));
            wp_enqueue_style($reg_name);
        }
    }
    if(!empty($skinObjStatic->jsFiles) && is_array($skinObjStatic->jsFiles)) {
        foreach ($skinObjStatic->jsFiles as $filename) {
            $reg_name = $reg_name = str_ireplace(".js", '', basename($filename)."-{$skin_name}");
            wp_register_script($reg_name, str_ireplace(ABSPATH, LenSlider::$siteurl."/", $filename), array(), false, true);
            wp_enqueue_script($reg_name);
        }
    }
    wp_head();
    echo '<style>html { margin-top: 0 !important; } * html body { margin-top: 0 !important; }</style>';
    ?>
</head>
<body>
    <?php LenSlider::lenslider_output_slider($slidernum, true, false);?>
<?php wp_footer()?>
</body>
</html>