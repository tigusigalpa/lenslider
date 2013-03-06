<?php
final class Dares_serpentLenSliderSkin extends LenSlider {
    public $bannerMergeArray;
    public $bannerUnsetArray;
    public $sliderMergeSettingsArray;
    protected $_jsHead;

    public function __construct() {
        $this->bannerMergeArray = array(
            'ls_ds_subtite' => array(
                'title' => 'Banner subtitle',
                'type' => 'input',
                //'tipsy'  => "Subtitle under main title"
            )
        );
        $this->sliderMergeSettingsArray = array(
            'ls_images_maxwidth' => array(
                'mini_title' => __( 'Max width', 'lenslider' ),
                'title' => "image max width", 'value' => 450,
                'maxlength' => 3, 'type' => 'input', 'spinner' => true, 'ext' => 'px'
            )/*,
            'ls_has_thumb' => array(
                'title' => sprintf(__("Enable banners thumbnails for Slider %s", 'lenslider'), $n_slider),
                'type' => 'checkbox', 'class' => 'chbx_is_thumb', 'value' => 'on'
            ),
            'ls_thumb_max_width' => array(
                'title' => __("Maximum thumbnail width, px", 'lenslider'),
                'type' => 'input', 'size' => 5, 'maxlength' => 3, 'spectype' => 'int', 'value' => 100
            )*/
        );
    }
}
?>