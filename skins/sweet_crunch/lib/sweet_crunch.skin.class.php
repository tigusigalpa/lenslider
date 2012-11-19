<?php
final class Dares_serpentLenSliderSkin extends LenSlider {
    public $bannerMergeArray;
    public $bannerUnsetArray;
    protected $_sliderSettingsArray;
    protected $_sliderMergeSettingsArray;
    protected $_jsHead;

    public function __construct() {
        $this->bannerMergeArray = array(
            'ls_ds_subtite' => array(
                'title' => 'Banner subtitle',
                'type' => 'input',
                'tipsy'  => "Subtitle under main title"
            )
        );
        $this->_sliderMergeSettingsArray = array(
            'ls_images_maxwidth' => array(
                'title' => "image max width", 'value' => 450,
                'maxlength' => 3, 'type' => 'input'
            )
        );
    }
}
?>