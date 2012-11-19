<?php
final class Vania_fitLenSliderSkin extends LenSlider {
    public $bannerMergeArray;
    public $bannerUnsetArray;
    protected $_sliderSettingsArray;
    protected $_sliderMergeSettingsArray;
    protected $_jsHead;

    public function __construct() {
        $this->bannerMergeArray = array(
            'ls_text' => array(
                'title'  => __('Banner text', 'lenslider'),
                'type' => 'textarea', 'maxlength' => 82
            )
        );
        $this->_sliderMergeSettingsArray = array(
            'ls_banners_limit' => array(
                'title' => sprintf(__("Limitation of banners for the slider %s <span class=\"description\">(max: %d)</span>", 'lenslider'), $n_slider, $this->bannersLimitDefault),
                'value' => 4, 'maxlength' => 1, 'invariable' => 4, 'type' => 'input'
            ),
            'ls_images_maxwidth' => array(
                'title' => sprintf(__("Maximum image width, px<br /><span class=\"description\">(min: %1d; max: %2d); proportions are kept</span>", 'lenslider'), $this->imageWidthMIN, $this->imageWidthMAX),
                'value' => 721, 'maxlength' => 4, 'type' => 'input'
            )
        );
    }
}
?>