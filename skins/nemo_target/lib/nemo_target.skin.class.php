<?php
final class Nemo_targetLenSliderSkin extends LenSlider {
    public $bannerMergeArray;
    public $bannerUnsetArray;
    public $sliderMergeSettingsArray;
    protected $_jsHead;

    public function __construct() {
        $this->bannerUnsetArray = array('ls_text');
        $this->sliderMergeSettingsArray = array(
            'ls_banners_limit' => array(
                'mini_title' => __( 'Banners limit', 'lenslider' ),
                'title' => sprintf(__("Limitation of banners for the slider %s <span class=\"description\">(max: %d)</span>", 'lenslider'), $n_slider, LenSlider::$bannersLimitDefault),
                'value' => 4, 'maxlength' => 1, 'invariable' => 4, 'type' => 'input', 'spinner' => true
            ),
            'ls_images_maxwidth' => array(
                'mini_title' => __( 'Max width', 'lenslider' ),
                'title' => sprintf(__("Maximum image width, px<br /><span class=\"description\">(min: %1d; max: %2d); proportions are kept</span>", 'lenslider'), $this->imageWidthMIN, $this->imageWidthMAX),
                'value' => 960, 'maxlength' => 4, 'type' => 'input', 'spinner' => true, 'ext' => 'px'
            ),
            'ls_has_thumb' => array(
                'mini_title' => __("Make thumbs", 'lenslider'),
                'title' => sprintf(__("Enable banners thumbnails for Slider %s", 'lenslider'), $n_slider),
                'type' => 'checkbox', 'class' => 'chbx_is_thumb', 'value' => 'on'
            ),
            'ls_thumb_max_width' => array(
                'mini_title' => __("Thumb max width", 'lenslider'),
                'title' => __("Maximum thumbnail width, px", 'lenslider'),
                'type' => 'input', 'size' => 5, 'maxlength' => 2, 'spectype' => 'int', 'value' => 25, 'spinner' => true, 'ext' => 'px'
            )
        );
    }
}
?>