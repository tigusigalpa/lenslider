<?php require("../../../wp-load.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{#lenslider.title}</title>
<?php $site_url = site_url();?>
<script type="text/javascript" src="<?=$site_url?>/wp-includes/js/jquery/jquery.js"></script>
<script type="text/javascript" src="<?=$site_url?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
<script type="text/javascript" src="<?=$site_url?>/wp-includes/js/tinymce/utils/form_utils.js"></script>
<script type="text/javascript" src="<?=$site_url?>/wp-includes/js/tinymce/utils/validate.js"></script>
<script type="text/javascript" src="<?=$site_url?>/wp-includes/js/tinymce/utils/editable_selects.js"></script>
<script type="text/javascript" src="<?=$site_url?>/wp-content/plugins/lenslider/js/tinymce_local.js"></script>
</head>
<body>
    <form onsubmit="LenSliderDialog.insert(LenSliderDialog.local_ed);return false;" action="#">
        <h3 align="center"><?php _e("Select slider to insert:", 'lenslider')?></h3>
        <div align="center" style="margin:3px 0 12px 0;"><?php echo LenSlider::lenslider_dropdown_sliders("slider_shortcode", false, "slider_shortcode", "font-size:12px;padding:2px;")?></div>
        <div class="mceActionPanel">
            <div style="float: left">
                <input type="button" id="insert" name="insert" value="<?php _e("Insert", 'lenslider')?>" onclick="javascript:LenSliderDialog.insert(LenSliderDialog.local_ed);" />
            </div>
            <div style="float: right">
                <input type="button" id="cancel" name="cancel" value="<?php _e("Cancel", 'lenslider')?>" onclick="tinyMCEPopup.close();" />
            </div>
            <div style="clear:both;"></div>
        </div>
    </form>
</body>
</html>