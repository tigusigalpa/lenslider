<?php
class LenSliderSettings extends LenSlider {
    
    protected static $_canZeroNegativeArray;

    public function __construct() {
        parent::__construct();
        add_action('lenslider_save_settings', array(&$this, 'lenslider_save_settings'));
    }

    public static function lenslider_make_settings_array($array, $limits_array, $limits_mins_array = null, $array_keys_to_unset = null, $not_int_array = null) {
        self::$_canZeroNegativeArray = array(parent::$hasThumb, parent::$marginTop, parent::$marginRight, parent::$marginBottom, parent::$marginLeft);
        $preventArray = array(
            __('slider comment', 'lenslider')
        );
        if(!empty($array_keys_to_unset) && is_array($array_keys_to_unset)) {
            foreach ($array_keys_to_unset as $to_unset) {unset($array[$to_unset]);}
        }
        if(!empty($array) && is_array($array)) {
            $ret_array = array();
            foreach ($array as $k=>$v) {
                if(in_array($v, $preventArray)) $ret_array[$k] = "";
                elseif(!empty($not_int_array) && is_array($not_int_array) && in_array($k, $not_int_array)) $ret_array[$k] = sanitize_text_field($v);
                else {
                    if(!empty($v) && intval($v) > 0) $ret_array[$k] = intval($v);
                    else {
                        if(!in_array($k, self::$_canZeroNegativeArray)) $ret_array[$k] = 1;
                        else {
                            if(is_numeric($v)) $ret_array[$k] = $v;
                        }
                    }
                }
            }
        } else return false;
        if(!empty($limits_array) && is_array($limits_array)) {
            foreach ($limits_array as $k=>$v) {if(intval($ret_array[$k]) > $v || intval($ret_array[$k]) <= 0) $ret_array[$k] = $v;}
        }
        if(!empty($limits_mins_array) && is_array($limits_mins_array)) {
            foreach ($limits_mins_array as $k=>$v) {if(intval($ret_array[$k]) < $v || intval($ret_array[$k]) <= 0) $ret_array[$k] = $v;}
        }
        return $ret_array;
    }

    public function lenslider_save_settings($args = null) {
        if(!empty($args) && is_array($args)) $this->_lenslider_update_lenslider_option(parent::$settingsOption, $args, $this->_requestSettingsURI."&message=1");
        return false;
    }
}
?>