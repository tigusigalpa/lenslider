<?php
final class Vania_fitLenSliderSkin extends LenSlider {
    public $bannerMergeArray;
    public $bannerUnsetArray;
    public $sliderMergeSettingsArray;
    protected $_jsHead;

    public function __construct() {
        $this->bannerMergeArray = array(
            'ls_text' => array(
                'title'  => __('Banner text', 'lenslider'),
                'type' => 'textarea', 'maxlength' => 82
            )
        );
        $this->sliderMergeSettingsArray = array(
            'ls_banners_limit' => array(
                'mini_title' => __( 'Banners limit', 'lenslider' ),
                'title' => sprintf(__("Limitation of banners for the slider %s <span class=\"description\">(max: %d)</span>", 'lenslider'), $n_slider, LenSlider::$bannersLimitDefault),
                'value' => 4, 'maxlength' => 1, 'invariable' => 4, 'type' => 'input', 'spinner' => true
            ),
            'ls_images_maxwidth' => array(
                'mini_title' => __( 'Max width', 'lenslider' ),
                'title' => sprintf(__("Maximum image width, px<br /><span class=\"description\">(min: %1d; max: %2d); proportions are kept</span>", 'lenslider'), $this->imageWidthMIN, $this->imageWidthMAX),
                'value' => 721, 'maxlength' => 4, 'type' => 'input', 'spinner' => true, 'ext' => 'px'
            )
        );
    }
}
?>