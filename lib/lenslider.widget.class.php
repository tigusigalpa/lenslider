<?php
class LenSlider_Widget extends WP_Widget {
    public function __construct() {
        $widget_options = array(
            'classname'   => LenSlider::$pluginNameLoc."_widget",
            'description' => __('Add LenSlider Sliders to your widget areas', 'lenslider')
        );
        $this->WP_Widget($widget_options['classname'], 'LenSlider Widget', $widget_options);
    }
    
    public function update($new_instance, $old_instance) {
        $locname = LenSlider::$pluginNameLoc;
        $instance = $old_instance;
        $instance['slidernum'] = $new_instance['slidernum'];
        $instance[$locname."_title"]  = $new_instance[$locname."_title"];
        $instance[$locname."_intro"]  = $new_instance[$locname."_intro"];
        $instance[$locname."_footer"] = $new_instance[$locname."_footer"];
        
        return $instance;
    }
    
    public function widget($args, $instance) {
        $locname = LenSlider::$pluginNameLoc;
        $ret = "";
        extract($args, EXTR_SKIP);
        
        $title  = isset($instance[$locname."_title"])?$instance[$locname."_title"]:'';
        $intro  = isset($instance[$locname."_intro"])?$instance[$locname."_intro"]:'';
        $footer = isset($instance[$locname."_footer"])?$instance[$locname."_footer"]:'';
        $title = apply_filters('widget_title', $instance['title']);
        $ret .= $before_widget;
        if(!empty($title)) $ret .= $before_title.$title.$after_title;
        $shortcode = "[lenslider id=\"{$instance['slidernum']}\"]";
        if(!empty($intro)) $ret .= '<div class="sd2-before">'.$intro.'</div>';
        $ret .= do_shortcode($shortcode);
        if($footer) $ret .= '<div class="sd2-after">'.$footer.'</div>';
        $ret .= $after_widget;
        echo $ret;
    }
    
    public function form($instance) {
        $locname = LenSlider::$pluginNameLoc;
        $instance = wp_parse_args((array) $instance, array(
            'slidernum' => ''
        ));
        
        $slidernum = strip_tags($instance['slidernum']);
        $title     = strip_tags($instance[$locname."_title"]);
        $intro     = $instance[$locname.'_intro'];
        $footer    = $instance[$locname."_footer"];
        
        $slidernums = LenSlider::lenslider_get_slidernums_list();?>
        <p><?php _e("Display a LenSlider in a widget area.", 'lenslider'); ?></p>
        <p>
            <label><strong><?php _e("Choose a Slider", 'lenslider'); ?>:</strong></label><br />
            <select name="<?php echo $this->get_field_name('slidernum'); ?>" id="<?php echo $this->get_field_id('slidernum'); ?>" class="widefat">
                <?php foreach($slidernums as $slidernum_arr): ?>
                <option value="<?php echo $slidernum_arr['slidernum']; ?>"<?php selected($slidernum, $slidernum_arr['slidernum']); ?>><?php echo $slidernum_arr['title']; ?></option>
                <?php endforeach; ?>
            </select>
        </p>
        <p>
            <label><?php _e('Title:', 'lenslider'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id($locname."_title"); ?>" name="<?php echo $this->get_field_name($locname."_title"); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <p>
            <label><?php _e('Intro text:', 'lenslider'); ?></label>
            <textarea class="widefat" id="<?php echo $this->get_field_id($locname."_intro"); ?>" name="<?php echo $this->get_field_name($locname."_intro"); ?>"><?php echo esc_attr($intro); ?></textarea>
        </p>
        <p>
            <label><?php _e('Footer text:', 'lenslider'); ?></label>
            <textarea class="widefat" id="<?php echo $this->get_field_id($locname."_footer"); ?>" name="<?php echo $this->get_field_name($locname."_footer"); ?>"><?php echo esc_attr($footer); ?></textarea>
        </p>
<?php
    }
    
    public function registerLenSliderWidget() {
        register_widget('LenSlider_Widget');
    }
}?>