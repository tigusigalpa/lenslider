<?php
final class Smart_energyLenSliderSkin extends LenSlider {
    public $bannerMergeArray;
    public $bannerUnsetArray;
    protected $_sliderMergeSettingsArray;
    protected $_jsHead;

    public function __construct() {
        $this->bannerMergeArray = array(
            'ls_thumb_title' =>
                array(
                    'title' => __("Title near thumb", 'lenslider'),
                    'type' => 'input',
                    'tcheck' => true
                ),
            'ls_thumb_text' =>
                array(
                    'title' => __("Text near thumb", 'lenslider'),
                    'type' => 'input',
                    'tcheck' => true
                )
        );
        $this->_sliderMergeSettingsArray = array(
            'ls_banners_limit' => array(
                'title' => sprintf(__("Limitation of banners for the slider %s <span class=\"description\">(max: %d)</span>", 'lenslider'), $n_slider, $this->bannersLimitDefault),
                'value' => 4, 'maxlength' => 1, 'invariable' => 4, 'type' => 'input'
            ),
            'ls_images_maxwidth' => array(
                'title' => sprintf(__("Maximum image width, px<br /><span class=\"description\">(min: %1d; max: %2d); proportions are kept</span>", 'lenslider'), $this->imageWidthMIN, $this->imageWidthMAX),
                'value' => 960, 'maxlength' => 4, 'type' => 'input'
            ),
            'ls_has_thumb' => array(
                'title' => sprintf(__("Enable banners thumbnails for Slider %s", 'lenslider'), $n_slider),
                'type' => 'checkbox', 'class' => 'chbx_is_thumb', 'value' => 'on'
            ),
            'ls_thumb_max_width' => array(
                'title' => __("Maximum thumbnail width, px", 'lenslider'),
                'type' => 'input', 'size' => 5, 'maxlength' => 2, 'spectype' => 'int', 'value' => 40
            )
        );
    }
}
?>