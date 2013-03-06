<?php
final class Smart_energyLenSliderSkin extends LenSlider {
    public $bannerMergeArray;
    public $bannerUnsetArray;
    public $sliderMergeSettingsArray;
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
        $this->sliderMergeSettingsArray = array(
            'ls_banners_limit' => array(
                'desc' => __("Maximum of enabled banners for this slider", 'lenslider'),
                'title' => __("Banners limit", 'lenslider'),
                'type' => 'input', 'size' => 5, 'maxlength' => 2, 'spectype' => 'int', 'spinner' => true
            ),
            'ls_has_thumb' => array(
                'desc' => __("Make thumbs", 'lenslider'),
                'title' => __("Make thumbs", 'lenslider'),
                'type' => 'checkbox', 'class' => 'chbx_is_thumb'
            ),
            'ls_thumb_max_width' => array(
                'mini_title' => __("Thumb max width", 'lenslider'),
                'title' => __("Maximum thumbnail width, px", 'lenslider'),
                'type' => 'input', 'size' => 5, 'maxlength' => 2, 'spectype' => 'int', 'value' => 40, 'spinner' => true, 'ext' => 'px'
            )
        );
    }
}
?>