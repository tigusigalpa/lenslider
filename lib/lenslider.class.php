<?php
class LenSlider {
    static $settingsOption         = 'lenslider_settings';
    static $postsSliders           = 'lenslider_post_sliders';
    public $indexPage              = 'lenslider/ls-index.php';
    public $settingsPage           = 'lenslider-settings-page';
    public $skinsPage              = 'lenslider-skins-page';
    public $indexFile;
    public $imageFileSizeMAX;
    public $imageWidth;
    public $imageWidthMAX          = 1200;
    public $imageWidthMIN          = 30;
    public $slidersLimit;
    public $slidersLimitDefault    = 30;
    public $bannersLimit;
    public $bannersLimitDefault    = 20;
    public $imageQuality;
    public $imageQualityMIN        = 60;
    public $imageQualityMAX        = 100;
    public $thumbWidthMAX          = 300;
    public $thumbWidthMIN          = 20;
    public $tipsyDefault           = 1;
    
    static $defaultSkinWidth       = 936;

    static $version                = '1.0';
    static $maxWidthName           = 'ls_images_maxwidth';
    static $maxSizeName            = 'ls_images_maxsize';
    static $slidersLimitName       = 'ls_sliders_limit';
    static $bannersLimitName       = 'ls_banners_limit';
    static $qualityName            = 'ls_slider_images_quality';
    static $skinName               = 'ls_slider_skin';
    static $hasThumb               = 'ls_has_thumb';
    static $thumbMaxWidth          = 'ls_thumb_max_width';
    static $tipsyName              = 'ls_slider_tipsy';
    static $sliderComment          = 'ls_slider_comment';
    static $sliderRandom           = 'ls_slider_random';
    static $sliderDisenName        = 'ls_slider_disen';
    static $backlink               = 'ls_backlink';
    static $cacheName              = 'ls_cache';
    static $defaultSkin            = 'default';
    static $siteurl;
    static $preventDefaultArray;
    static $slider_comment_prevent;
    static $skin_change_note;
    static $toJSVars;


    protected $_requestSettingsURI;
    protected $_requestSkinsURI;
    protected static $_pluginName  = 'lenslider';
    
    private static $_settingsTitle = 'settings';
    private static $_bannersOption = 'lenslider_banners';
    private static $_roleName      = 'LenSlider Manager';
    private static $_role          = 'lenslider_manager';
    private static $_capability    = 'lenslider_manage';
    private static $_settingsDefault;
    private $_ajaxURL;
    private $_tipsy;
    private $_plugin_basename;
    private $_requestIndexURI;
    
    public function __construct() {
        $options_array             = self::lenslider_get_array_from_wp_options(self::$settingsOption);
        $this->imageFileSizeMAX    = intval(ini_get('upload_max_filesize'));
        $this->imageWidth          = (!empty($options_array[self::$maxWidthName]))    ?$options_array[self::$maxWidthName]    :$this->imageWidthMAX;
        $this->slidersLimit        = (!empty($options_array[self::$slidersLimitName]))?$options_array[self::$slidersLimitName]:$this->slidersLimitDefault;
        $this->bannersLimit        = (!empty($options_array[self::$bannersLimitName]))?$options_array[self::$bannersLimitName]:$this->bannersLimitDefault;
        $this->imageQuality        = (!empty($options_array[self::$qualityName]))     ?$options_array[self::$qualityName]     :$this->imageQualityMAX;
        $this->_tipsy              = (!empty($options_array[self::$tipsyName]))       ?$options_array[self::$tipsyName]       :0;
        self::$_settingsDefault    = array(
                                        self::$slidersLimitName => $this->slidersLimitDefault,
                                        self::$bannersLimitName => $this->bannersLimitDefault,
                                        self::$maxSizeName      => 6,
                                        self::$maxWidthName     => 500,
                                        self::$qualityName      => 90,
                                        self::$backlink         => 0,
                                        self::$skinName         => self::$defaultSkin,
                                        self::$hasThumb         => 0,
                                        self::$thumbMaxWidth    => 50,
                                        self::$sliderDisenName  => 1,
                                        self::$cacheName        => 0,
                                        self::$sliderRandom     => 0,
                                        $this->_tipsy           => $this->tipsyDefault
                                    );
        self::$skin_change_note       = __("NOTE: After you change the skin for the slider and update the data, slider will be resaved as disabled. Then you’ll be able to change its status on your own to enable it and then update the data.", 'lenslider');
        
        self::$siteurl             = site_url();
        $this->_requestIndexURI    = admin_url("admin.php?page={$this->indexPage}");
        $this->indexFile           = ABSPATH.PLUGINDIR."/".$this->indexPage;  
        $this->_requestSettingsURI = admin_url("admin.php?page={$this->settingsPage}");
        $this->_requestSkinsURI    = admin_url("admin.php?page={$this->skinsPage}");
        $this->_plugin_basename    = plugin_basename($this->indexFile);
        $this->_ajaxURL            = plugins_url('ls-ajax.php', $this->indexFile);
        
        register_activation_hook($this->_plugin_basename, array(&$this, 'lenslider_register_activation_hook'));
        register_deactivation_hook($this->_plugin_basename, array('LenSlider', 'lenslider_plugin_deactivate'));
        register_uninstall_hook($this->_plugin_basename, array('LenSlider', 'lenslider_plugin_uninstall'));
        add_action('init',                array(&$this, 'lenslider_init'));
        add_action('admin_init',          array(&$this, 'lenslider_scripts_init'));
        add_action('admin_menu',          array(&$this, 'lenslider_menu_add'));
        add_action('admin_head',          array(&$this, 'lenslider_admin_head'));
        add_action('wp_head',             array(&$this, 'lenslider_make_skins_files_wp_head'));
        add_action('wp_footer',           array(&$this, 'lenslider_footer_link'));
        add_action('save_post',           array(&$this, 'lenslider_save_postdata'));
        add_action('save_post',           array(&$this, 'lenslider_check_post_url_update'));
        add_action('lenslider_banners_processing', array(&$this, 'lenslider_banners_processing'), 10, 6);
        add_filter('plugin_action_links', array(&$this, 'lenslider_action_links'), 10, 2);
        add_filter('plugin_row_meta',     array(&$this, 'lenslider_plugin_links'), 10, 2);
        add_shortcode('lenslider',        array(&$this, 'lenslider_shortcode'));
    }
    
    /*---------------INITS METHODS---------------*/
    public function lenslider_register_activation_hook() {
        add_option(self::$_bannersOption);
        add_option(self::$settingsOption, self::$_settingsDefault);
        add_option(self::$postsSliders);
        add_role(self::$_role, self::$_roleName, array(self::$_capability));
        $role = get_role('administrator');
        $role->add_cap(self::$_capability);
    }

    public static function lenslider_plugin_deactivate() {
        remove_role(self::$_role);
        $role = get_role('administrator');
        $role->remove_cap(self::$_capability);
    }
    
    public static function lenslider_plugin_uninstall() {
        $sliders_array = self::lenslider_get_array_from_wp_options(self::$_bannersOption);
        if(!empty($sliders_array) && is_array($sliders_array)) {
            foreach (array_keys($sliders_array) as $slider_k) {self::_lenslider_delete_slider_banners_ids($slider_k, $sliders_array);}
        }
        delete_option(self::$_bannersOption);
        delete_option(self::$settingsOption);
        remove_action('lenslider_banners_processing', array(&$this, 'lenslider_banners_processing'));
        self::lenslider_plugin_deactivate();
    }

    public function lenslider_scripts_init() {
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script('thickbox');
        wp_enqueue_style ('thickbox');
        
        wp_register_script('ls_admin',    plugins_url('js/ls_admin.js',                  $this->indexFile));
        wp_enqueue_script('ls_admin');
        
        wp_register_script('ls_tipsy',    plugins_url('js/jquery.tipsy.js',              $this->indexFile));
        wp_enqueue_script('ls_tipsy');
        
        wp_register_script('ls_cookie',   plugins_url('js/jquery.cookie.js',             $this->indexFile));
        wp_enqueue_script('ls_cookie');
        
        wp_register_script('ls_tinymce',  plugins_url('js/tinymce.js',                   $this->indexFile));
        wp_enqueue_script('ls_tinymce');
        
        wp_register_script('ls_scrollto', plugins_url('js/jquery.scrollTo-1.4.2-min.js', $this->indexFile));
        wp_enqueue_script('ls_scrollto');
        
        wp_register_script('ls_jnav',     plugins_url('js/jquery.nav.min.js',            $this->indexFile));
        wp_enqueue_script('ls_jnav');
        
        wp_register_script('ls_alerts',   plugins_url('js/jquery.alerts.js',             $this->indexFile));
        wp_enqueue_script('ls_alerts');
        
        wp_register_style('ls_admin_css', plugins_url('css/ls_admin.css',                $this->indexFile));
        wp_enqueue_style('ls_admin_css');
        
        wp_register_style('ls_tipsy_css', plugins_url('css/tipsy.css',                   $this->indexFile));
        wp_enqueue_style('ls_tipsy_css');
        
        wp_register_style('ls_alerts',    plugins_url('css/jquery.alerts.css',           $this->indexFile));
        wp_enqueue_style('ls_alerts');
    }
    
    public function lenslider_shortcode($args, $content = null) {
        return self::lenslider_output_slider($args['id'], false);
    }
    
    public function lenslider_action_links($links, $file) {
        if($file == $this->_plugin_basename) {
            $settings_link = "<a href=\"{$this->_requestSettingsURI}\">".__("Settings", 'lenslider')."</a>";
            array_unshift($links, $settings_link);
        }
        return $links;
    }
    
    //to do
    public function lenslider_plugin_links($links, $file) {
            if (!current_user_can('install_plugins'))
                    return $links;
            /*if ($file == $this->_plugin_basename) {
                    $links[] = '<a href="http://backwpup.com/faq/" target="_blank">'.__('FAQ', 'lenslider').'</a>';
                    $links[] = '<a href="http://backwpup.com/forum/" target="_blank">'.__('Support', 'lenslider').'</a>';
                    $links[] = '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=Q3QSVRSFXBLSE" target="_blank">'.__('Donate', 'lenslider').'</a>';
            }*/
            return $links;
    }
    
    public function lenslider_footer_link() {
        $settings_array = self::lenslider_get_array_from_wp_options(self::$settingsOption);
        if ($settings_array[self::$backlink] == 1) {
            echo "<a href=\"http://www.lenslider.com/\" target=\"_blank\" title=\"".__("WordPress LenSlider", 'lenslider')."\">".__("Free slider plugin for WordPress", 'lenslider')."</a>\n";
        }
    }
    
    private function _lenslider_make_default_fields_array($banner_array = false) {
        return
        array(
            'ls_link' =>
                array(
                    'title'  => __('Banner link', 'lenslider'),
                    'value'  => (empty($banner_array['ls_link']))?"http://":$banner_array['ls_link'], 'type' => 'input',
                    'tipsy'  => __("Banner URL path. A minimum of 10 characters", 'lenslider'),
                    'tcheck' => true
                ),
            'ls_title' =>
                array(
                    'title'  => __('Banner title', 'lenslider'),
                    'value'  => $banner_array['ls_title'], 'type' => 'input',
                    'tipsy'  => __("Banner title. A minimum of 10 characters", 'lenslider'),
                    'tcheck' => true
                ),
            'ls_text' =>
                array(
                    'title'  => __('Banner text', 'lenslider'),
                    'value'  => $banner_array['ls_text'], 'type' => 'textarea'
                )
        );
    }
    
    private function _lenslider_make_images_fields_array($has_thumb = false) {
        $array = array();
        $array['ls_image'] = array(
            'title'    => __('Banner image', 'lenslider'),
            'value'    => null,
            'tipsy'    => null,
            'tcheck'   => null,
            'optgroup' => __('Post image', 'lenslider')
        );
        if($has_thumb) {
            $array['ls_thumb_image'] = array(
                'title'    => __('Banner thumbnail', 'lenslider'),
                'value'    => null,
                'tipsy'    => null,
                'tcheck'   => null,
                'optgroup' => __('Post thumb', 'lenslider')
            );
        }
        return $array;
    }

    private function _lenslider_make_default_slider_settings_array($slidernum, $settings_array = false, $new_slider = false, $skin_name = false, $type = 'default') {
        if(!$settings_array && $new_slider) $settings_array = self::$_settingsDefault;
        $settings_array[self::$skinName] = $skin_name;
        $thumbMaxWidthArray = array(
            'title' => __("Maximum thumbnail width, px", 'lenslider'),
            'type' => 'input', 'size' => 5, 'maxlength' => 3, 'spectype' => 'int'
            );
        if(!$settings_array[self::$hasThumb]) $thumbMaxWidthArray['disabled'] = true;
        switch ($type) {
            case 'default':
                return
                array(
                    self::$bannersLimitName =>
                        array(
                            'title' => sprintf(__("Limitation of banners for the slider %s <span class=\"description\">(max: %d)</span>", 'lenslider'), $slidernum, $this->bannersLimitDefault),
                            'type' => 'input', 'size' => 5, 'maxlength' => 2, 'spectype' => 'int'
                        ),
                    self::$maxSizeName =>
                        array(
                            'title' => sprintf(__("Upload images maximum size, MB<br /><span class=\"description\">(max: %d)</span>", 'lenslider'), $this->imageFileSizeMAX),
                            'type' => 'input', 'size' => 5, 'maxlength' => 2, 'spectype' => 'int'
                        ),
                    self::$maxWidthName =>
                        array(
                            'title' => sprintf(__("Maximum image width, px<br /><span class=\"description\">(min: %1d; max: %2d); proportions are kept</span>", 'lenslider'), $this->imageWidthMIN, $this->imageWidthMAX),
                            'type' => 'input', 'size' => 5, 'maxlength' => 4, 'spectype' => 'int'
                        ),
                    self::$qualityName =>
                        array(
                            'title' => sprintf(__("Maximum quality of uploaded image<br /><span class=\"description\">(min: %1d, max: %2d)</span>", 'lenslider'), $this->imageQualityMIN, $this->imageQualityMAX),
                            'type' => 'input', 'size' => 5, 'maxlength' => 3, 'spectype' => 'int'
                        ),
                    self::$hasThumb => 
                        array(
                            'title' => sprintf(__("Enable banners thumbnails for Slider %s", 'lenslider'), $slidernum),
                            'type' => 'checkbox', 'class' => 'chbx_is_thumb'
                        ),
                    self::$thumbMaxWidth => $thumbMaxWidthArray
                );
        }
    }

    public function lenslider_make_skins_files_wp_head() {
        $enabled_sliders = self::lenslider_get_enabled_sliders_array_slidernums();
        if(!empty($enabled_sliders) && is_array($enabled_sliders)) {
            wp_enqueue_script('jquery');
            wp_enqueue_script('jquery-ui-core');
            wp_enqueue_script('jquery-ui-tabs');
            wp_register_script('default-skin-custom', plugins_url('js/default-skin-custom.js', $this->indexFile));
            wp_enqueue_script('default-skin-custom');
            wp_register_style('default-skin-css', plugins_url('css/defaultskin.css', $this->indexFile));
            wp_enqueue_style('default-skin-css');
            foreach ($enabled_sliders as $slidernum) {
                $skin_name = self::_lenslider_get_slider_skin_name($slidernum);
                if($skin_name != self::$defaultSkin) {
                    $skinObjStatic = LenSliderSkins::lenslider_get_skin_params_object($skin_name);
                    if(!empty($skinObjStatic->cssFiles) && is_array($skinObjStatic->cssFiles)) {
                        foreach ($skinObjStatic->cssFiles as $filename) {
                            $reg_name = str_ireplace(".css", '', basename($filename)."-{$skin_name}");
                            wp_register_style($reg_name, str_ireplace(ABSPATH, self::$siteurl."/", $filename));
                            wp_enqueue_style($reg_name);
                        }
                    }
                    $default_js_array = LenSliderSkins::_lenslider_skin_default_js_scripts_array($skin_name);
                    if(!empty($default_js_array) && is_array($default_js_array)) {
                        foreach ($default_js_array as $jsHandle) {
                            wp_enqueue_script($jsHandle);
                        }
                    }
                    if(!empty($skinObjStatic->jsFiles) && is_array($skinObjStatic->jsFiles)) {
                        foreach ($skinObjStatic->jsFiles as $filename) {
                            $reg_name = $reg_name = str_ireplace(".js", '', basename($filename)."-{$skin_name}");
                            wp_register_script($reg_name, str_ireplace(ABSPATH, self::$siteurl."/", $filename), array(), false, true);
                            wp_enqueue_script($reg_name);
                        }
                    }
                    $skinObj = self::lenslider_get_slider_skin_object($skin_name);
                    if(!empty($skinObj->_jsHead)) echo $skinObj->_jsHead;
                }
            }
        }
    }

    public function lenslider_menu_add() {
        add_menu_page('LenSlider', 'LenSlider', self::$_capability, $this->indexFile, 'lenslider_index_gallery', plugins_url('images/icon_menu.png', $this->indexFile));

        add_submenu_page($this->indexFile, __('Available LenSlider skins', 'lenslider'), __('Skins', 'lenslider'),    self::$_capability, $this->skinsPage,    'lenslider_skins_page');
        add_submenu_page($this->indexFile, __('LenSlider settings', 'lenslider'),        __('Settings', 'lenslider'), self::$_capability, $this->settingsPage, 'lenslider_settins_page');
    }
    
    public function lenslider_admin_head() {
        echo "<script type=\"text/javascript\">
                jQuery(document).ready(function($) {
                    $(this).load(lenSliderJSReady($, \"{$this->_ajaxURL}\", {$this->_tipsy}, ".$this->_lenslider_is_plugin_page().", \"".__("Are you sure?", 'lenslider')."\", \"".__("You also want to delete thumbnail for this banner?", 'lenslider')."\", \"".__("No skins available", 'lenslider')."\", \"".__('slider comment', 'lenslider')."\", \"".__("Errors", 'lenslider')."\", \"".__("If  there are errors while filling fields, then check the fields marked with red border.", 'lenslider')."\", \"".__("Maximize", 'lenslider')."\", \"".__("Minimize", 'lenslider')."\", \"".__("Do you want to set skin settings for the slider?", 'lenslider')."\", \"".__("Slider #{%torep%} errors:", 'lenslider')."\", \"".__("Banner {%torep%} errors:", 'lenslider')."\"));
                })(jQuery);
            </script>\n";
    }
    
    public function lenslider_init() {
        if(is_admin() && current_user_can('edit_posts') && current_user_can('edit_pages') && current_user_can(self::$_capability)) {
            if(get_user_option('rich_editing') == 'true') {
                add_filter('mce_external_plugins', array(&$this, 'lenslider_add_tinymce_plugin'));
                add_filter('mce_buttons',          array(&$this, 'lenslider_register_tinymce_button'));
            }
        }
        $current_locale = get_locale();
        if(!empty($current_locale)) load_plugin_textdomain('lenslider', false, self::$_pluginName."/languages/");
    }
    
    public function lenslider_register_tinymce_button($buttons) {
        array_push($buttons, "", self::$_pluginName);
        return $buttons;
    }
    
    public function lenslider_add_tinymce_plugin($plugin_array) {
        $plugin_array[self::$_pluginName] = plugins_url('js/tinymce.js', $this->indexFile);
        return $plugin_array;
    }
    /*---------------/INITS METHODS---------------*/
    
    
    /*---------------HELPER METHODS---------------*/
    public function lenslider_hash() {
        return substr(md5(microtime().mt_rand(10000, 9999999999)), 3, 10);
    }
    
    public function lenslider_size_str($width, $height) {
        return "{$width} &times; {$height}";
    }
    
    public function lenslider_replace_att_url($att_id) {
        if($att_id) {
            $retObj            = new stdClass;
            $retObj->absAttUrl = str_ireplace(self::$siteurl."/", ABSPATH, wp_get_attachment_url($att_id));
            $retObj->httpPath  = str_ireplace(ABSPATH, '/', &$retObj->absAttUrl);
            $size_array        = @getimagesize(&$retObj->absAttUrl);
            $retObj->size      = $this->lenslider_size_str($size_array[0], $size_array[1]);
            $retObj->mime      = $size_array['mime'];
            return $retObj;
        }
        return false;
    }
    
    public static function lenslider_dropdown_sliders($select_name, $check = false, $select_id = false, $style_string = false) {
        $enabled_sliders = self::lenslider_get_enabled_sliders_array_slidernums();
        if(!empty($enabled_sliders) && is_array($enabled_sliders)) {
            $ret = "<select name=\"{$select_name}\" class=\"ls_chosen\"";
            if(isset($style_string)) $ret .= " style=\"{$style_string}\"";
            if(isset($select_id))    $ret .= " id=\"{$select_id}\"";
            $ret .= ">";
            foreach ($enabled_sliders as $enable_slider_slidernum) {
                $slider_settings = self::lenslider_get_slider_settings($enable_slider_slidernum);
                $ret .= "<option value=\"{$enable_slider_slidernum}\"";
                $ret .= (!empty($check))?selected($check, $enable_slider_slidernum, false):"";
                $ret .= ">{$enable_slider_slidernum}";
                $ret .= (!empty($slider_settings[self::$sliderComment]))?" ({$slider_settings[self::$sliderComment]})":"";
                $ret .= "</option>";
            }
            $ret .= "</select>";
            return $ret;
        } else return __('No sliders available', 'lenslider');
    }
    
    public static function lenslider_dropdown_posts($slidernum, $banner_k, $n, $check = false) {
        $posts = get_posts();
        if(!empty($posts)) {
            $ret = "<select name=\"blink_select_{$slidernum}_{$banner_k}_{$n}\" id=\"blink_select_{$slidernum}_{$banner_k}_{$n}\" style=\"max-width:220px;\">";
            $ret .= "<option value=\"-1\">".__('Select...', 'lenslider')."</option>";
            foreach ($posts as $post) {
                $ret .= "<option value=\"{$post->ID}\"";
                if($check && $check == $post->ID) $ret .= " selected=\"selected\"";
                $ret .= ">{$post->post_title}</option>";
            }
            $ret .= "</select>";
            return $ret;
        }
    }
    
    public static function lenslider_dropdown_pages($slidernum, $banner_k, $n, $check = -1) {
        return wp_dropdown_pages(array('echo'=>0, 'show_option_none'=>__('Select...', 'lenslider'), 'name'=>"blink_select_{$slidernum}_{$banner_k}_{$n}", 'selected'=>$check));
    }
    
    public static function lenslider_dropdown_categories($slidernum, $banner_k, $n, $check = -1) {
        return wp_dropdown_categories(array('echo'=>0, 'show_option_none'=>__('Select...', 'lenslider'), 'class'=>'pb_url_select', 'id'=>"blink_select_{$slidernum}_{$banner_k}_{$n}", 'selected'=>$check));
    }

    protected function _lenslider_make_fields_array($input_array, $array_unset, $array_merge) {
        if(!empty($array_unset) && is_array($array_unset)) {
            foreach ($array_unset as $to_unset) {unset($input_array[$to_unset]);}
        }
        if(!empty($array_merge) && is_array($array_merge)) $input_array = array_merge($input_array, $array_merge);
        return $input_array;
    }
    
    protected static function _lenslider_make_post_customfields_array($post_id) {
        $ret_array = array();
        $cf_array = get_post_custom_keys($post_id);
        if(!empty($cf_array) && is_array($cf_array)) {
            foreach ($cf_array as $v) {
                $ret_array[$v] = $v;
            }
            return $ret_array;
        }
    }
    
    public function lenslider_make_post_sliders_fields_array($slider_array, $slidernum) {
        $fields_array = $this->lenslider_get_slider_banner_fields($slidernum);
        if(!empty($fields_array) && is_array($fields_array)) {
            foreach (array_keys($fields_array) as $k) {
                $fields_array[$k]['value'] = $slider_array[$k];
            }
            $slider_settings = self::lenslider_get_slider_settings($slidernum);
            return array_merge($this->_lenslider_make_images_fields_array($slider_settings[self::$hasThumb]), $fields_array);
        }
    }
    
    public function lenslider_sanitize_quotes($str) {
        return str_replace('"', "&quot;", $str);
    }
    
    public function lenslider_is_english_characters($str) {
        return (strlen(urldecode($str)) != strlen(utf8_decode(urldecode($str))))?false:true;
    }
    
    public function lenslider_make_keytag($keytag) {
        $keytag = sanitize_title($keytag);
        return ($this->lenslider_is_english_characters($keytag))?$keytag:substr(md5(time().mt_rand(1111,9999999)),2,9);
    }
    /*---------------/HELPER METHODS---------------*/
    
    
    /*---------------CHECK METHODS---------------*/
    private static function _lenslider_is_allowed_option($option) {
        return (in_array($option, array(self::$_bannersOption, self::$settingsOption, self::$postsSliders)))?true:false;
    }

    public function lenslider_is_valid_url($data) {
        return preg_match("/^((http|https):\/\/)?([a-z0-9\-]+\.)?[a-z0-9\-]+\.[a-z0-9]{2,4}(\.[a-z0-9]{2,4})?(\/.*)?$/i", $data);
        return true;
    }
    
    private static function _lenslider_is_slider_exists($slidernum) {
        $sliders_array = self::lenslider_get_array_from_wp_options(self::$_bannersOption);
        if(!empty($sliders_array) && is_array($sliders_array)) return (array_key_exists($slidernum, $sliders_array))?true:false;
        return false;
    }

    public static function lenslider_is_enabled_slider($slidernum) {
        if(self::_lenslider_is_slider_exists($slidernum)) {
            $slider_settings = self::lenslider_get_slider_settings($slidernum);
            if($slider_settings[self::$sliderDisenName] == 1) return true;
        }
        return false;
    }
    
    protected function _lenslider_is_plugin_page() {
        $server_uri = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
        $allow_uris = array($this->_requestIndexURI, $this->_requestSettingsURI, $this->_requestSkinsURI);
        return (in_array($server_uri, $allow_uris))?1:0;
    }
    
    public function lenslider_is_needle_mime_type($file, $mime_array, $format_array = false) {
        if(!is_array($file)) {
            $to_filesize = ABSPATH.str_ireplace(self::$siteurl."/", '', $file);
            $file_info = @getimagesize($to_filesize);
            $file = array('name' => $file, 'size' => filesize($to_filesize), 'type' => $file_info['mime']);
        }
        $arr_file_type      = wp_check_filetype(basename($file['name']));
        $uploaded_file_type = $arr_file_type['type'];
        if($format_array) {
            $format = false;
            if(is_array($format_array)) {
                foreach ($format_array as $format_array_item) {
                    $file_ext = substr(strrchr(basename($file['name']), '.'), 1);
                    if($format_array_item == $file_ext) {
                        $format = true;
                        break;
                    }
                }
            }
        } else $format = true;
        if(in_array($file["type"], $mime_array) && in_array($uploaded_file_type, $mime_array) && $format) return $uploaded_file_type;
        return false;
    }
    
    private function _lenslider_skin_has_settings($skin_name) {
        if($skin_name != self::$defaultSkin) {
            $skinObj = self::lenslider_get_slider_skin_object($skin_name);
            if(!empty($skinObj->_sliderMergeSettingsArray) && is_array($skinObj->_sliderMergeSettingsArray)) {
                foreach ($skinObj->_sliderMergeSettingsArray as $k=>$arr) {
                    if(array_key_exists('value', $arr)) return true;
                }
            }
        }
        return false;
    }
    /*---------------/CHECK METHODS---------------*/
    
    
    /*---------------GETTER METHODS---------------*/
    public static function lenslider_get_array_from_wp_options($option_name) {
        if(self::_lenslider_is_allowed_option($option_name)) return self::_lenslider_get_option($option_name);
    }
    
    public static function lenslider_get_slider_settings($slidernum) {
        $ret_array = self::lenslider_get_array_from_wp_options(self::$_bannersOption);
        return (!empty($ret_array[$slidernum][self::$_settingsTitle]))?$ret_array[$slidernum][self::$_settingsTitle]:self::$_settingsDefault;
    }
    
    public static function lenslider_get_skin_settings($slidernum, $skin_name = false, $needle_slider_settings = true) {
        $slider_settings = ($needle_slider_settings)?self::lenslider_get_slider_settings($slidernum):array();
        if(!$skin_name) $skin_name = self::_lenslider_get_slider_skin_name($slidernum);
        $merge_array = array();
        if($skin_name != self::$defaultSkin) {
            $skinObj = self::lenslider_get_slider_skin_object($skin_name);
            $merge_array = $skinObj->_sliderMergeSettingsArray;
        } else $merge_array = self::_lenslider_get_default_skin_settings();
        if(!empty($merge_array) && is_array($merge_array)) {
            foreach ($merge_array as $k=>$arr) {
                if(array_key_exists('value', $arr)) $slider_settings[$k] = $arr['value'];
            }
        }
        return $slider_settings;
    }

    public static function lenslider_get_slider_banners($slidernum, $array_values = true) {
        $sliders_array = self::lenslider_get_array_from_wp_options(self::$_bannersOption);
        if(!empty($sliders_array[$slidernum]) && is_array($sliders_array[$slidernum])) {
            $ret_array = array();
            unset($sliders_array[$slidernum][self::$_settingsTitle]);
            $i=0;
            foreach ($sliders_array[$slidernum] as $k=>$banner_v) {
                if($array_values) $ret_array[$i] = $banner_v;
                else $ret_array[$k] = $banner_v;
                $i++;
            }
            return $ret_array;
        }
    }
    
    public static function lenslider_get_slider_skin_object($skin_name, $require = true) {
        if($skin_name != self::$defaultSkin) {
            if($require) require_once(LenSliderSkins::_lenslider_skins_abspath()."/{$skin_name}/lib/{$skin_name}.skin.class.php");
            $class = ucfirst($skin_name)."LenSliderSkin";
            return new $class;
        }
        return false;
    }
    
    protected function lenslider_get_enabled_skins_array_names() {
        $sliders_array = self::lenslider_get_array_from_wp_options(self::$_bannersOption);
        if(!empty($sliders_array) && is_array($sliders_array)) {
            $ret_array = array();
            foreach (array_keys($sliders_array) as $slidernum) {
                $slider_settings = self::lenslider_get_slider_settings($slidernum);
                $ret_array[] = $slider_settings[self::$skinName];
            }
            return $ret_array;
        }
        return false;
    }
    
    public function lenslider_get_used_skins() {
        $sliders_array = self::lenslider_get_array_from_wp_options(self::$_bannersOption);
        if(is_array($sliders_array)) {
            $arr = array();
            foreach (array_keys($sliders_array) as $key) {
                $temp_array = $sliders_array[$key][self::$_settingsTitle];
                $arr[] = $temp_array[self::$skinName];
            }
            return $arr;
        }
    }
    
    public function lenslider_get_sliders_admin($skins_array) {
        $ret = "";
        $sliders_array = self::lenslider_get_array_from_wp_options(self::$_bannersOption);
        if(!empty($sliders_array) && is_array($sliders_array)) {
            $i=0;
            foreach (array_keys($sliders_array) as $k) {
                if($skins_array[$i] == self::$defaultSkin) $ret .= $this->lenslider_slider_item($k, $this->slidersLimit, false, self::$defaultSkin, true, self::lenslider_get_slider_settings($k), false);
                else {
                    if(LenSliderSkins::lenslider_is_skin($skins_array[$i])) {
                        $ret .= $this->lenslider_slider_item($k, $this->slidersLimit, false, $skins_array[$i], true, self::lenslider_get_slider_settings($k), false);
                    } else $ret .= $this->lenslider_slider_item($k, $this->slidersLimit, false, false, true, self::lenslider_get_slider_settings($k), false);
                }
                $i++;
            }
        } else $ret = $this->lenslider_slider_item($this->lenslider_hash(), $this->slidersLimit, false, self::$defaultSkin, false);
        
        return $ret;
    }
    
    public function lenslider_get_slider_banner_fields($slidernum) {
        $skin_name = $this->_lenslider_get_slider_skin_name($slidernum);
        $skinObj = self::lenslider_get_slider_skin_object($skin_name);
        return $this->_lenslider_make_fields_array($this->_lenslider_make_default_fields_array(), $skinObj->bannerUnsetArray, $skinObj->bannerMergeArray);
    }
    
    private static function _lenslider_get_option($option_name) {
        if(self::_lenslider_is_allowed_option($option_name)) return @get_option($option_name);
    }
    
    private static function lenslider_get_enabled_sliders_array_slidernums() {
        $sliders_array = self::lenslider_get_array_from_wp_options(self::$_bannersOption);
        if(!empty($sliders_array) && is_array($sliders_array)) {
            $ret_array = array();
            foreach (array_keys($sliders_array) as $slidernum) {
                $slider_settings = self::lenslider_get_slider_settings($slidernum);
                if($slider_settings[self::$sliderDisenName] == 1) $ret_array[] = $slidernum;
            }
            return $ret_array;
        }
        return false;
    }
    
    private static function _lenslider_get_slider_skin_name($slidernum) {
        $slider_settings_array = self::lenslider_get_slider_settings($slidernum);
        return $slider_settings_array[self::$skinName];
    }
    
    private static function _lenslider_get_default_skin_settings() {
        return array(
            self::$maxWidthName => array('value' => self::$defaultSkinWidth),
            self::$hasThumb => array('value' => 'off')
        );
    }
    /*--------------/GETTER METHODS------------------*/
    
    
    /*---------------SETTER METHODS---------------*/
    private function _lenslider_update_option($option_name, $option_value) {
        if(self::_lenslider_is_allowed_option($option_name)) return @update_option($option_name, $option_value);
        return false;
    }

    private function _lenslider_update_option_sliders_array($sliders_array) {
        $ins = (!empty($sliders_array))?$sliders_array:'';
        return ($this->_lenslider_update_option(self::$_bannersOption, $ins))?true:false;
    }
    
    protected function _lenslider_update_lenslider_option($option_name, $ret_array, $redirect_url = false) {
        if(self::_lenslider_is_allowed_option($option_name)) {
            $ins = (!empty($ret_array))?$ret_array:'';
            $this->_lenslider_update_option($option_name, $ins);
            if($redirect_url) {
                unset($_FILES);
                unset($_POST);
                wp_safe_redirect($redirect_url);
                exit;
            }
        }
    }
    
    private function _lenslider_insert_attachment($attachment, $foto_abs_path) {
        return wp_insert_attachment($attachment, $foto_abs_path);
    }

    private function _lenslider_update_attachment($id, $attachment, $only_update = false) {
        wp_update_attachment_metadata($id, $attachment);
        if(!$only_update) return $this->lenslider_replace_att_url($id)->httpPath;
    }
    /*---------------/SETTER METHODS---------------*/
    
    
    /*---------------DELETE METHODS---------------*/
    public function lenslider_delete_banner($banner_id, $slidernum = false, $delete_thumb = true, $thumb_id = false, $delete_only_thumb = false, $sliders_array = false, $update_option = true, $delete_slider = false) {
        if(!$sliders_array) $sliders_array = self::lenslider_get_array_from_wp_options(self::$_bannersOption);
        if($slidernum) {
            if($delete_thumb && !empty($thumb_id)) {
                if(@wp_delete_attachment($thumb_id) && !$delete_slider) {
                    if(!empty($sliders_array[$slidernum][$banner_id]['path_thumb'])) unset($sliders_array[$slidernum][$banner_id]['path_thumb']);
                    if(!empty($sliders_array[$slidernum][$banner_id]['size_thumb'])) unset($sliders_array[$slidernum][$banner_id]['size_thumb']);
                    if(!empty($sliders_array[$slidernum][$banner_id]['thumb_id']))   unset($sliders_array[$slidernum][$banner_id]['thumb_id']);
                }
            }
            if(!$delete_only_thumb) {
                @wp_delete_attachment($banner_id);
                if(!$delete_slider) {
                    unset($sliders_array[$slidernum][$banner_id]);
                    if(!empty($sliders_array[$slidernum][self::$_settingsTitle]) && count($sliders_array[$slidernum]) == 1) unset($sliders_array[$slidernum]);
                }
            }
        }
        return ($update_option)?$this->_lenslider_update_option_sliders_array($sliders_array):true;
    }

    public function lenslider_delete_slider($slidernum) {
        $sliders_array = self::lenslider_get_array_from_wp_options(self::$_bannersOption);
        if(!empty($sliders_array[$slidernum]) && is_array($sliders_array[$slidernum])) {
            foreach($sliders_array[$slidernum] as $k=>$v) {
                if($this->lenslider_delete_banner($k, $slidernum, true, $v['thumb_id'], false, $sliders_array, false, true)) continue;
            }
            unset($sliders_array[$slidernum]);
        }
        return $this->_lenslider_update_option_sliders_array($sliders_array);
    }

    private static function _lenslider_delete_slider_banners_ids($slidernum, $sliders_array = false) {
        if(!$sliders_array) $sliders_array = self::lenslider_get_array_from_wp_options(self::$_bannersOption);
        if(!empty($sliders_array[$slidernum]) && is_array($sliders_array[$slidernum])) {
            //$ret_array = array();
            foreach (array_keys($sliders_array[$slidernum]) as $banner_k) {
                @wp_delete_attachment($banner_k);
            }
        }
        return true;
    }
    /*---------------/DELETE METHODS---------------*/
    
    
    /*---------------PROCESSING METHODS---------------*/
    private function _lenslider_resize_image_abspath($file, $maxwidth, $maxsize_mb, $quality, $keytag, $uid) {
        $return_logs = array();
        $return_logs['errors'] = array();
        if(!is_array($file)) {
            $tmp_file = $file;
            $abspath = ABSPATH.str_ireplace(self::$siteurl."/", '', $tmp_file);
            $img_info = @getimagesize($abspath);
            $file = array(
                'name' => $tmp_file,
                'size' => filesize($abspath),
                'type' => $img_info['mime']
            );
            $quality = 100;
        }
        if($file['size'] <= 1024*$maxsize_mb*1024) {
            if(@is_uploaded_file($file['tmp_name']) || $tmp_file) {
                if(stristr(strtolower($file['name']),'.gif'))                                                   $format = 'gif';
                elseif(stristr(strtolower($file['name']),'.jpg') || stristr(strtolower($file['name']),'.jpeg')) $format = 'jpg';
                elseif(stristr(strtolower($file['name']),'.png'))                                               $format = 'png';
                
                $arr_file_type      = wp_check_filetype(basename($file['name']));
                $uploaded_file_type = $arr_file_type['type'];
                $allowed_file_types = array('image/jpg','image/jpeg','image/gif','image/png');
                
                if($format && in_array($uploaded_file_type, $allowed_file_types)) {
                    $upload_dir_array = wp_upload_dir();
                    if(!file_exists($upload_dir_array['path'])) mkdir($upload_dir_array['path'], 0777);
                    $newpath = $upload_dir_array['path'].'/'.strtolower(sanitize_title($keytag." ".$uid).'.'.$format);
                    
                    if(!$tmp_file) @move_uploaded_file($file['tmp_name'], $newpath);
                    else @copy($tmp_file, $newpath);
                    /*wp_constrain_dimensions*/
                    $size_array   = @getimagesize($newpath);
                    $image_width  = $size_array[0];
                    $image_height = $size_array[1];
                    
                    if(($image_width > $maxwidth)) {
                        $image_heigth_resize = intval(($maxwidth * $image_height) / $image_width);
                        $resImage = imagecreatetruecolor($maxwidth, $image_heigth_resize);
                        switch ($format) {
                            case 'gif':
                                $oldimage = imagecreatefromgif($newpath);
                                imagecopyresampled($resImage, $oldimage, 0, 0, 0, 0, $maxwidth, $image_heigth_resize, $image_width, $image_height);
                                imagegif($resImage, $newpath, $quality);
                                break;
                            case 'jpg':
                                $oldimage = imagecreatefromjpeg($newpath);
                                imagecopyresampled($resImage, $oldimage, 0, 0, 0, 0, $maxwidth, $image_heigth_resize, $image_width, $image_height);
                                imagejpeg($resImage, $newpath, $quality);
                                break;
                            case 'png':
                                $oldimage = imagecreatefrompng($newpath);
                                imagecopyresampled($resImage, $oldimage, 0, 0, 0, 0, $maxwidth, $image_heigth_resize, $image_width, $image_height);
                                imagepng($resImage, $newpath, $quality);
                                break;
                        }
                        @chmod($newpath, 0644);
                        imagedestroy($resImage);
                        $return_logs['path'] = $newpath;
                        $return_logs['size'] = $this->lenslider_size_str($maxwidth, $image_heigth_resize);
                    } else {
                        if(!$tmp_file) @move_uploaded_file($file['tmp_name'], $newpath);
                        else @copy($tmp_file, $newpath);
                        @chmod($newpath, 0644);
                        $return_logs['path'] = $newpath;
                        $return_logs['size'] = $this->lenslider_size_str($image_width, $image_height);
                    }
                    $return_logs['type'] = $size_array['mime'];
                }
            }
        }
        
        return $return_logs;
    }

    private static function _lenslider_join_slider_elements($slider_banners, $html_el, $slider_settings, $slidernum) {
        $array = array();
        preg_match_all('#%(.+?)%#iu', $html_el, $array);
        if(!empty($array[1]) && is_array($array[1])) {
            $join_array = array();
            if($slider_settings[self::$sliderRandom] == 1) shuffle($slider_banners);
            foreach ($slider_banners as $k=>$slider_banner) {
                $slider_banner['slidernum'] = $slidernum;
                $slider_banner['banner_key'] = $k;
                $slider_banner['banner_key_inc'] = $k+1;
                $slider_banner['path']       = self::$siteurl.$slider_banner['path'];
                $slider_banner['path_thumb'] = ($slider_banner['path_thumb'])?self::$siteurl.$slider_banner['path_thumb']:false;
                $html_out = $html_el;
                for($i=0;$i<count($array[1]);$i++) $html_out = str_ireplace("%{$array[1][$i]}%", $slider_banner[$array[1][$i]], $html_out);
                $join_array[] = $html_out;
            }
            return join('', $join_array);
        }
        return false;
    }

    private static function _lenslider_replace_file_text($slider_banners, $file_text, $el_prefix, $slider_settings, $slidernum) {
        $start_plus = 13+strlen($el_prefix);
        $maybepos   = stripos($file_text, "<!--{$el_prefix}_start-->");
        if($maybepos) {
            $repl_plus  = $start_plus+11+strlen($el_prefix);
            $el_start   = $maybepos+$start_plus;
            $el_end     = stripos($file_text, "<!--{$el_prefix}_end-->");
            $el_html    = substr($file_text, $el_start, $el_end-$el_start);
            $el_join    = self::_lenslider_join_slider_elements($slider_banners, $el_html, $slider_settings, $slidernum);
            if($el_join) return substr_replace($file_text, $el_join, $el_start-$start_plus, $el_end-$el_start+$repl_plus);
        }
        return $file_text;
    }
    
    private function _lenslider_settings_titled_array($settings_array, $array, $merge_array = false) {
        if($merge_array) $array = array_merge($array, $merge_array);
        $ret_array = array();
        if(empty($settings_array)) {
            $this->_lenslider_update_lenslider_option(self::$settingsOption, self::$_settingsDefault);
            $settings_array = self::lenslider_get_array_from_wp_options(self::$settingsOption);
        }
        foreach ($settings_array as $set_k=>$set_v) {
            foreach ($array as $arr_k=>$arr_v) {
                if($arr_k == $set_k) {
                    $ret_array[$arr_v['title']] = $set_v;
                    break;
                }
            }
        }
        return $ret_array;
    }

    public function lenslider_banners_processing($sliderarray, $checkBannerArray, $file, $file_thumb, $array, $settings_post_array = false) {
        $ret_array    = array();
        if(isset($sliderarray) && is_array($sliderarray)) {
            foreach (array_keys($sliderarray) as $slider_k) {
                $slider_settings_array = self::lenslider_get_slider_settings($slider_k);
                foreach(array_keys($checkBannerArray[$slider_k]) as $banner_k) {
                    if(!empty($_COOKIE["skin_set_{$slider_k}"])) {
                        $settings_post_array[$slider_k] = array_merge($settings_post_array[$slider_k], self::lenslider_get_skin_settings($slider_k, $_COOKIE["skin_set_{$slider_k}"], false));
                        unset($_COOKIE["skin_set_{$slider_k}"]);
                    }
                    $slidercomment = (!empty($settings_post_array[$slider_k][self::$sliderComment]))   ?$settings_post_array[$slider_k][self::$sliderComment]   :"";
                    $sliderrandom  = (!empty($settings_post_array[$slider_k][self::$sliderRandom])   && $settings_post_array[$slider_k][self::$sliderRandom] == 'on')?1:0;
                    $bannerslimit  = (!empty($settings_post_array[$slider_k][self::$bannersLimitName]))?$settings_post_array[$slider_k][self::$bannersLimitName]:"";
                    $maxwidth      = (!empty($settings_post_array[$slider_k][self::$maxWidthName]))    ?$settings_post_array[$slider_k][self::$maxWidthName]    :"";
                    $maxsize_mb    = (!empty($settings_post_array[$slider_k][self::$maxSizeName]))     ?$settings_post_array[$slider_k][self::$maxSizeName]     :"";
                    $quality       = (!empty($settings_post_array[$slider_k][self::$qualityName]))     ?$settings_post_array[$slider_k][self::$qualityName]     :"";
                    $disen         = $settings_post_array[$slider_k][self::$sliderDisenName];
                    $skin_name     = (!empty($settings_post_array[$slider_k][self::$skinName]))        ?$settings_post_array[$slider_k][self::$skinName]        :self::$defaultSkin;
                    $has_thumb     = (!empty($settings_post_array[$slider_k][self::$hasThumb])       && $settings_post_array[$slider_k][self::$hasThumb] == 'on')?1:0;
                    $maxthumbwidth = (!empty($settings_post_array[$slider_k][self::$thumbMaxWidth]))   ?$settings_post_array[$slider_k][self::$thumbMaxWidth]   :$this->thumbWidthMAX;
                    if(!LenSliderSkins::_lenslider_skin_exists($skin_name)) $skin_name = self::$defaultSkin;
                    if(!empty($slider_settings_array) && $slider_settings_array[self::$skinName] != $skin_name) $disen = 0;
                    if(array_key_exists("ls_link", $array[$slider_k]) && $this->lenslider_is_valid_url($array[$slider_k]["ls_link"][$banner_k])) {
                        if(!empty($file['tmp_name'][$slider_k][$banner_k])) {
                            $afile              = array();
                            $afile['tmp_name']  = $file['tmp_name'][$slider_k][$banner_k];
                            $afile["name"]      = $file["name"][$slider_k][$banner_k];
                            $afile["size"]      = $file["size"][$slider_k][$banner_k];
                            $afile["type"]      = $file["type"][$slider_k][$banner_k];
                            if($this->lenslider_is_needle_mime_type($afile, array('image/jpg','image/jpeg','image/gif','image/png'))) {
                                $uid    = $this->lenslider_hash();
                                $foto_abs_path = $this->_lenslider_resize_image_abspath($afile, $maxwidth, $maxsize_mb, $quality, $this->lenslider_make_keytag($array[$slider_k]["ls_title"][$banner_k]), $uid);
                                if(!empty($foto_abs_path['path'])) {
                                    @wp_delete_attachment($array[$slider_k]['ls_att_id'][$banner_k]);
                                    $attachment = array(
                                        'post_title'     => (!empty($array[$slider_k]["ls_title"][$banner_k]))?sanitize_text_field($array[$slider_k]["ls_title"][$banner_k]):$slider_k."=>".$banner_k,
                                        'post_content'   => (!empty($array[$slider_k]["ls_text"][$banner_k]))?sanitize_text_field($array[$slider_k]["ls_text"][$banner_k]):$slider_k."=>".$banner_k,
                                        'post_type'      => 'attachment',
                                        'post_mime_type' => $afile["type"]
                                    );
                                    $id = $this->_lenslider_insert_attachment($attachment, $foto_abs_path['path']);
                                    $image_path = $this->_lenslider_update_attachment($id, $attachment);
                                    $image_size = $foto_abs_path['size'];
                                }
                            }
                        } else {
                            $id         = $array[$slider_k]['ls_att_id'][$banner_k];
                            $rauObj     = $this->lenslider_replace_att_url($id);
                            $image_path = $rauObj->httpPath;
                            $image_size = $rauObj->size;
                            $attachment = wp_generate_attachment_metadata($id, $rauObj->absAttUrl);
                            $this->_lenslider_update_attachment($id, $attachment, true);
                        }
                        /*THUMBS*/
                        if($has_thumb && !empty($maxthumbwidth) && intval($maxthumbwidth) >= $this->thumbWidthMIN && intval($maxthumbwidth) <= $this->thumbWidthMAX) {
                            if(!empty($file_thumb['tmp_name'][$slider_k][$banner_k])) {
                                $afile              = array();
                                $afile['tmp_name']  = $file_thumb['tmp_name'][$slider_k][$banner_k];
                                $afile["name"]      = $file_thumb["name"][$slider_k][$banner_k];
                                $afile["size"]      = $file_thumb["size"][$slider_k][$banner_k];
                                $afile["type"]      = $file_thumb["type"][$slider_k][$banner_k];
                                if($this->lenslider_is_needle_mime_type($afile, array('image/jpg','image/jpeg','image/gif','image/png'))) {
                                    $uid = $this->lenslider_hash();
                                    $foto_thumb_abs_path = $this->_lenslider_resize_image_abspath($afile, $maxthumbwidth, $maxsize_mb, 100, $this->lenslider_make_keytag($array[$slider_k]["ls_title"][$banner_k]), $uid);
                                    if(!empty($foto_thumb_abs_path['path'])) {
                                        @wp_delete_attachment($array[$slider_k]['ls_att_thumb_id'][$banner_k]);
                                        $attachment = array(
                                            'post_title'     => (!empty($array[$slider_k]["ls_title"][$banner_k]))?sanitize_text_field($array[$slider_k]["ls_title"][$banner_k]):$slider_k."=>".$banner_k,
                                            'post_content'   => (!empty($array[$slider_k]["ls_text"][$banner_k]))?sanitize_text_field($array[$slider_k]["ls_text"][$banner_k]):$slider_k."=>".$banner_k,
                                            'post_type'      => 'attachment',
                                            'post_mime_type' => $afile["type"]
                                        );
                                        $thumb_id = $this->_lenslider_insert_attachment($attachment, $foto_thumb_abs_path['path']);
                                        $image_thumb_path = $this->_lenslider_update_attachment($thumb_id, $attachment);
                                        $image_thumb_size = $foto_thumb_abs_path['size'];
                                    }
                                }
                            } else {
                                if(!empty($array[$slider_k]['ls_att_thumb_id'][$banner_k])) {
                                    $thumb_id         = $array[$slider_k]['ls_att_thumb_id'][$banner_k];
                                    $rauObj           = $this->lenslider_replace_att_url($thumb_id);
                                    $image_thumb_path = $rauObj->httpPath;
                                    $image_thumb_size = $rauObj->size;
                                    $attachment       = wp_generate_attachment_metadata($thumb_id, $rauObj->absAttUrl);
                                    $this->_lenslider_update_attachment($thumb_id, $attachment, true);
                                } else {
                                    /*ресайзим большую фотку*/
                                    $uid = $this->lenslider_hash();
                                    $foto_thumb_abs_path = $this->_lenslider_resize_image_abspath(self::$siteurl.$image_path, $maxthumbwidth, $maxsize_mb, 100, $this->lenslider_make_keytag($array[$slider_k]["ls_title"][$banner_k]), $uid);
                                    if(!empty($foto_thumb_abs_path['path'])) {
                                        @wp_delete_attachment($array[$slider_k]['ls_att_thumb_id'][$banner_k]);
                                        $attachment = array(
                                            'post_title'     => (!empty($array[$slider_k]["ls_title"][$banner_k]))?sanitize_text_field($array[$slider_k]["ls_title"][$banner_k]):$slider_k."=>".$banner_k,
                                            'post_content'   => (!empty($array[$slider_k]["ls_text"][$banner_k]))?sanitize_text_field($array[$slider_k]["ls_text"][$banner_k]):$slider_k."=>".$banner_k,
                                            'post_type'      => 'attachment',
                                            'post_mime_type' => $rauObj->mime
                                        );
                                        $thumb_id = $this->_lenslider_insert_attachment($attachment, $foto_thumb_abs_path['path']);
                                        $image_thumb_path = $this->_lenslider_update_attachment($thumb_id, $attachment);
                                        $image_thumb_size = $foto_thumb_abs_path['size'];
                                    }
                                }
                            }
                        } else @wp_delete_attachment($array[$slider_k]['ls_att_thumb_id'][$banner_k]);
                        $info_array_neccessary = array(
                            'path'  => sanitize_text_field($image_path),
                            'size'  => $image_size
                        );
                        if($image_thumb_path) $info_array_neccessary['path_thumb'] = sanitize_text_field($image_thumb_path);
                        if($image_thumb_size) $info_array_neccessary['size_thumb'] = $image_thumb_size;
                        if($thumb_id)         $info_array_neccessary['thumb_id']   = $thumb_id;
                        $image_thumb_path = null;
                        $image_thumb_size = null;
                        $thumb_id         = null;
                        
                        $info_array_merge = array();
                        foreach ($array[$slider_k] as $k=>$v) {
                            if($k != 'ls_att_id' && $k != 'ls_att_thumb_id') $info_array_merge[$k] = $this->lenslider_sanitize_quotes($array[$slider_k][$k][$banner_k]);
                        }
                        $info_array = array_merge($info_array_neccessary, $info_array_merge);
                        if(!empty($image_path)) $ret_array[$slider_k][$id] = $info_array;
                    }
                }
                
                $not_int_settings_array   = array();
                $to_unset_settings_array  = array();
                $maxvalues_settings_array = array();
                foreach ($settings_post_array[$slider_k] as $k=>$v) {
                    if(is_array($v)) {
                        if($k == 'notint') {
                            $to_unset_settings_array[] = $k;
                            foreach ($v as $v_k=>$v_v) {$not_int_settings_array[] = $v_k;}
                        }
                        if($k == 'maxvalue') {
                            $to_unset_settings_array[] = $k;
                            foreach ($v as $v_k=>$v_v) {$maxvalues_settings_array[$v_k] = intval($v_v);}
                        }
                    }
                }
                
                $slider_settings_array = array_merge(
                        array(
                            self::$sliderComment    => sanitize_text_field($slidercomment),
                            self::$sliderRandom     => intval($sliderrandom),
                            self::$bannersLimitName => intval($bannerslimit),
                            self::$maxWidthName     => intval($maxwidth),
                            self::$maxSizeName      => intval($maxsize_mb),
                            self::$qualityName      => intval($quality),
                            self::$sliderDisenName  => intval($disen),
                            self::$skinName         => $skin_name,
                            self::$hasThumb         => $has_thumb,
                            self::$thumbMaxWidth    => $maxthumbwidth
                        ),
                        LenSliderSettings::lenslider_make_settings_array(
                                $settings_post_array[$slider_k]/*array*/,
                                array_merge(
                                        array(/*MAX limits default*/
                                            self::$bannersLimitName => $this->bannersLimitDefault,
                                            self::$maxWidthName     => $this->imageWidthMAX,
                                            self::$maxSizeName      => $this->imageFileSizeMAX,
                                            self::$qualityName      => $this->imageQualityMAX,
                                            self::$thumbMaxWidth    => $this->thumbWidthMAX
                                ), $maxvalues_settings_array),
                                array(/*MIN limits default*/
                                    self::$maxWidthName     => $this->imageWidthMIN,
                                    self::$qualityName      => $this->imageQualityMIN,
                                    self::$thumbMaxWidth    => $this->thumbWidthMIN
                                ),
                                array_merge(array(self::$sliderDisenName), $to_unset_settings_array),
                                array_merge(array(self::$sliderComment, self::$skinName), $not_int_settings_array)
                        )
                );
                if(!empty($ret_array[$slider_k])) $ret_array[$slider_k][self::$_settingsTitle] = $slider_settings_array;
            }
        }
        $this->_lenslider_update_lenslider_option(self::$_bannersOption, $ret_array, $this->_requestIndexURI);
    }
    
    //$post_siders - то что посылаем
    public function lenslider_save_postdata($post_id) {
        if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if(defined('DOING_AJAX') && DOING_AJAX) return;
        if(!wp_verify_nonce($_POST['lenslider_nonce'], $this->_plugin_basename)) return;
        if(!wp_is_post_revision($post_id)) {
            if(!empty($_POST['ls_post_slider_fields']) && is_array($_POST['ls_post_slider_fields'])) {
                foreach ($_POST['ls_post_slider_fields'] as $slidernum=>$array) {
                    $slider_settings = self::lenslider_get_slider_settings($slidernum);
                    if(!empty($_FILES['pbi']['tmp_name'][$slidernum])) {
                        //$post_image = (empty($_POST['ls_att_id'][$slidernum]))?$this->_lenslider_save_post_image($_FILES['pbi'], $slidernum, $array, $post_id/*, $posts_sliders*/):$this->_lenslider_save_post_image($_FILES['pbi'], $slidernum, $array, $post_id/*, $posts_sliders*/, false);
                    } else {
                        //хз, по-идее можно и закоментить....
                        /*if(!empty($_POST['ls_att_id'][$slidernum])) {
                            $att_id = intval($_POST['ls_att_id'][$slidernum]);
                            if(!empty($att_id)) {
                                $attObj = $this->lenslider_replace_att_url($att_id);
                                $post_image = array(
                                    'id'   => $att_id,
                                    'path' => $attObj->httpPath,
                                    'size' => $attObj->size
                                );
                            }
                        }*/
                    }
                    /*THUMBS*/
                    if($slider_settings[self::$hasThumb]) {
                        if(!empty($_FILES['pbi_thumb']['tmp_name'][$slidernum])) {
                            //$post_image_thumb = (empty($_POST['ls_att_thumb_id'][$slidernum]))?$this->_lenslider_save_post_image($_FILES['pbi_thumb'], $slidernum, $array, $post_id/*, $post_image['posts_sliders']*/, true, 'ls_att_thumb_id', $post_image['id']):$post_image_thumb = $this->_lenslider_save_post_image($_FILES['pbi_thumb'], $slidernum, $array, $post_id/*, $post_image['posts_sliders']*/, false, 'ls_att_thumb_id', $post_image['id']);
                        } else {
                            if(!empty($_POST['ls_att_thumb_id'][$slidernum])) {
                                $att_id_thumb = intval($_POST['ls_att_thumb_id'][$slidernum]);
                                if(!empty($att_id_thumb)) {
                                    $attObj = $this->lenslider_replace_att_url($att_id_thumb);
                                    $post_image_thumb = array(
                                        'thumb_id'   => $att_id_thumb,
                                        'path_thumb' => $attObj->httpPath,
                                        'size_thumb' => $attObj->size
                                    );
                                    $post_image_thumb['id'] = /*(!empty($post_image['id']))?$post_image['id']:*/intval($_POST['ls_att_id']);
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    
    private function _lenslider_update_post_banners_checked($post_id, $val_array, $sliders_array = false) {
        $sliders_array = (!$sliders_array)?self::lenslider_get_array_from_wp_options(self::$_bannersOption):$sliders_array;
        if(!empty($sliders_array) && is_array($sliders_array)) {
            foreach ($sliders_array as $slidernum => $banners_array) {
                if(!empty($banners_array) && is_array($banners_array)) {
                    foreach ($banners_array as $k=>$arr) {
                        if($k != self::$_settingsTitle) {
                            if(is_array($val_array)) {
                                if(!empty($val_array['url'])) {
                                    if(($arr['url_type'] == 'post' || $arr['url_type'] == 'page') && $arr['url_type_id'] == $post_id) $sliders_array[$slidernum][$k]['ls_link'] = $val_array['url'];
                                }
                            }
                        }
                    }
                }
            }
            return $sliders_array;
        }
    }

    public function lenslider_check_post_url_update($post_id) {
        $this->_lenslider_update_lenslider_option(self::$_bannersOption, $this->_lenslider_update_post_banners_checked($post_id, array('url' => get_permalink($post_id))));
    }
    /*---------------/PROCESSING METHODS---------------*/
    
    
    /*--------------OUTPUT METHODS---------------*/
    //if(!$attachment_id) => new banner
    private function _lenslider_link_variants($slidernum, $n, $title, $attachment_id = false, $check = false, $url_type_id = false) {
        $array = array(
            'ls_url' => __('Url', 'lenslider'),
            'post'   => __('Post', 'lenslider'),
            'page'   => __('Page', 'lenslider'),
            'cat'    => __('Category', 'lenslider')
        );
        $ret = "<div class=\"ls_post_url\"><table border=\"0\"><tr>";
        $i=0;
        foreach ($array as $k=>$v) {
            if($i==0) $ret .= "<td style=\"padding-right:30px;\"><label class=\"ls_label sm\" for=\"{$k}_{$slidernum}_{$n}_{$attachment_id}\">{$title}</label></td>";
            $ret .= "<td><input class=\"blink\" type=\"radio\" name=\"blink[{$slidernum}][{$attachment_id}][]\" id=\"blink_{$k}_{$slidernum}_{$n}_{$attachment_id}\" value=\"blink_{$k}\"";
            if($check == $k || (empty($attachment_id) && $i==0)) $ret .= " checked=\"checked\"";
            $ret .= " /></td><td style=\"padding-right:10px;\"><label class=\"ls_label sm\" for=\"blink_{$k}_{$slidernum}_{$n}_{$attachment_id}\">{$v}</label></td>";
            $i++;
        }
        $ret .= "<td><div class=\"blink_append\" id=\"blink_append_{$slidernum}_{$n}\">";
        if(!empty($check) && !empty($url_type_id) && !empty($attachment_id)) {
            switch ($check) {
                case 'post':
                    $ret .= self::lenslider_dropdown_posts($slidernum, $attachment_id, $n, $url_type_id);
                    break;
                case 'page':
                    $ret .= self::lenslider_dropdown_pages($slidernum, $attachment_id, $n, $url_type_id);
                    break;
                case 'cat':
                    $ret .= self::lenslider_dropdown_categories($slidernum, $attachment_id, $n, $url_type_id);
                    break;
            }
        }
        $ret .= "</div></td>";
        $ret .= "</tr></table></div>";
        return $ret;
    }
    
    public static function lenslider_banner_hidden($slidernum, $k, $value = false) {
        $return = "<input type=\"hidden\" name=\"binfo[{$slidernum}][{$k}][]\" value=\"";
        if(!empty($value)) $return .= $value;
        $return .= "\" />";
        return $return;
    }
    
    //if(!$attachment_id) => new banner
    private function _lenslider_banner_item_add($slidernum, $n, $array, $array_merge = false, $array_unset = false, $attachment_id = false, $url_type = false, $url_type_id = false) {
        if(!empty($array) && is_array($array)) {
            $array = $this->_lenslider_make_fields_array($array, $array_unset, $array_merge);
            $ret = "";
            foreach ($array as $k=>$v) {
                $disabled = false;
                if($k == 'ls_link') $ret .= $this->_lenslider_link_variants($slidernum, $n, $v['title'], $attachment_id, $url_type, $url_type_id);
                else $ret .= "<label class=\"ls_label\" style=\"margin-top:10px\" for=\"{$k}_{$slidernum}_{$n}\">{$v['title']}</label>";
                switch ($v['type']) {
                    case 'input':
                        $ret .= "<input style=\"width:95%\" type=\"text\" id=\"{$k}_{$slidernum}_{$n}\"";
                        $ret .= " class=\"ls_input ls_maxinput ls_floatleft ls_rounded_big";
                        if($v['tcheck']) $ret .= " tcheck";
                        $ret .= "\" value=\"";
                        if($k == 'ls_link' && !empty($url_type) && !empty($url_type_id)) {
                            $disabled = true;
                            switch ($url_type) {
                                case 'post':
                                case 'page':
                                    $ret .= get_permalink($url_type_id);
                                    break;
                                case 'cat':
                                    $ret .= get_category_link($url_type_id);
                                    break;
                            }
                        } else $ret .= stripcslashes($v['value']);
                        $ret .= "\"";
                        if(!$disabled) $ret .= " name=\"binfo[{$slidernum}][{$k}][]\"";
                        if(!empty($v['maxlength'])) $ret .= " maxlength=\"{$v['maxlength']}\"";
                        if($disabled) $ret .= " disabled=\"disabled\"";
                        $ret .= " /><div id=\"post_hidden_{$k}_{$slidernum}_{$n}\">";
                        if($disabled) $ret .= self::lenslider_banner_hidden($slidernum, $k, $v['value']);
                        $ret .= "</div>";
                        if($v['tipsy']) $ret .= "<div class=\"ques\"><a href=\"javascript:;\" class=\"atipsy\" title=\"{$v['tipsy']}\"></a></div>";
                        $ret .= "<div class=\"clear\"></div>";
                        break;
                    case 'textarea':
                        $ret .= "<textarea style=\"width:100%\" id=\"{$k}_{$slidernum}_{$n}\"";
                        $ret .= " name=\"binfo[{$slidernum}][{$k}][]\"";
                        $ret .= " class=\"ls_input ls_maxtextarea ls_rounded";
                        if(!empty($v['tcheck'])) $ret .= " tcheck";
                        $ret .= "\"";
                        if(!empty($v['maxlength'])) $ret .= " maxlength=\"{$v['maxlength']}\"";
                        $ret .= ">".stripcslashes($v['value'])."</textarea><div id=\"post_hidden_{$slidernum}_{$n}\">";
                        $ret .= "</div>";
                        break;
                }
            }
            return $ret;
        }
    }
    
    public function lenslider_slider_settings_add($n_slider, $settings_array, $array, $array_merge = false) {
        if(!empty($array) && is_array($array)) {
            if(!empty($array_merge) && is_array($array_merge)) $array = array_merge($array, $array_merge);
            $ret = "<table class=\"ls_table\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">";
            $i=0;
            foreach (array_keys($array) as $k) {
                if($i%2==0) $ret .= "<tr>";
                $ret .= "<td><label class=\"ls_label\" for=\"{$k}_{$n_slider}\">{$array[$k]['title']}</label></td>";
                $ret .= "<td align=\"center\">";
                if(!empty($array[$k]['type'])) {
                    switch($array[$k]['type']) {
                        case 'input':
                            if(empty($array[$k]['size'])) $array[$k]['size'] = 5;
                            $ret .= "<input ";
                            if(empty($array[$k]['invariable'])) $ret .= "name=\"slset[{$n_slider}][{$k}]\"";
                            $ret .= " type=\"text\" size=\"{$array[$k]['size']}\" ";
                            $ret .= (!empty($array[$k]['invariable']))?"value=\"".intval($array[$k]['invariable'])."\"":"value=\"{$settings_array[$k]}\"";
                            $ret .= " class=\"ls_input {$k}_{$n_slider}";
                            if(!empty($array[$k]['class'])) $ret .= " ".$array[$k]['class'];
                            $ret .= "\"";
                            if(!empty($array[$k]['maxlength'])) $ret .= " maxlength=\"{$array[$k]['maxlength']}\"";
                            if(!empty($array[$k]['disabled']) || !empty($array[$k]['invariable'])) $ret .= " disabled=\"disabled\"";
                            $ret .= " />";
                            if(!empty($array[$k]['invariable'])) $ret .= "<input type=\"hidden\" name=\"slset[{$n_slider}][{$k}]\" value=\"{$array[$k]['invariable']}\" />";
                            if(isset($array[$k]['int'])) {
                                if($array[$k]['int'] === false) $ret .= "<input type=\"hidden\" name=\"slset[{$n_slider}][notint][{$k}]\" value=\"1\" />";
                            }
                            elseif(!empty($array[$k]['maxvalue'])) $ret .= "<input type=\"hidden\" name=\"slset[{$n_slider}][maxvalue][{$k}]\" value=\"{$array[$k]['maxvalue']}\" />";
                            break;
                        case 'textarea':
                            $ret .= "<textarea name=\"slset[{$n_slider}][{$k}]\" class=\"ls_input ls_maxtextarea ls_rounded {$k}_{$n_slider}";
                            if(!empty($array[$k]['class'])) $ret .= " {$array[$k]['class']}";
                            $ret .= "\"";
                            if(!empty($array[$k]['maxlength'])) $ret .= " maxlength=\"{$array[$k]['maxlength']}\"";
                            if(!empty($array[$k]['disabled']) || !empty($array[$k]['invariable'])) $ret .= " disabled=\"disabled\"";
                            $ret .= ">";
                            $ret .= (empty($array[$k]['invariable']))?$settings_array[$k]:$array[$k]['invariable'];
                            $ret .= "</textarea>";
                            if(!empty($array[$k]['invariable'])) $ret .= "<input type=\"hidden\" name=\"slset[{$n_slider}][{$k}]\" value=\"{$array[$k]['invariable']}\" />";
                            if(isset($array[$k]['int'])) {
                                if($array[$k]['int'] === false) $ret .= "<input type=\"hidden\" name=\"slset[{$n_slider}][notint][{$k}]\" value=\"1\" />";
                            }
                            elseif(!empty($array[$k]['maxvalue'])) $ret .= "<input type=\"hidden\" name=\"slset[{$n_slider}][maxvalue][{$k}]\" value=\"{$array[$k]['maxvalue']}\" />";
                            break;
                        case 'checkbox':
                            $ret .= "<input name=\"slset[{$n_slider}][{$k}]\" type=\"checkbox\" id=\"{$k}_{$n_slider}\"";
                            if(!empty($array[$k]['class'])) $ret .= " class=\"{$array[$k]['class']}\"";
                            if($settings_array[$k] == 1 || $settings_array[$k] == true) $ret .= " checked=\"checked\"";
                            $ret .= " />";
                            break;
                    }
                }
                if(!empty($array[$k]['customtype'])) $ret .= $array[$k]['customtype'];
                $ret .= "</td>";
                $i++;
                if(($i-1)%2!=0 || $i==count($array)) $ret .= "</tr>";
            }
            $ret .= "</table>";
            return $ret;
        }
    }
    
    public static function lenslider_image_upload_inputs($slidernum = '', $att_id = false, $input_name = 'pbi', $hidden_name = 'ls_att_id') {
        $ret = "<input type=\"file\" name=\"{$input_name}[{$slidernum}]\" class=\"if_ovrflw\" accept=\"image/*\" />";
        if($att_id) $ret .= "<br /><a href=\"".wp_get_attachment_url($att_id)."\"><img src=\"".wp_get_attachment_url($att_id)."\" style=\"width:50px;\" /></a><input type=\"hidden\" name=\"{$hidden_name}[{$slidernum}]\" value=\"{$att_id}\" />";
        return $ret;
    }

    /*вывод баннеров для слайдера*/
    public function lenslider_banners_items($slidernum, $new_slider = true, $skinObj = false) {
        $return = "";
        $array_merge = ($skinObj && $skinObj->bannerMergeArray)?$skinObj->bannerMergeArray:false;
        $array_unset = ($skinObj && $skinObj->bannerUnsetArray)?$skinObj->bannerUnsetArray:false;
        if(!$new_slider) {
            $sliders_array = self::lenslider_get_array_from_wp_options(self::$_bannersOption);
            if(!empty($sliders_array[$slidernum]) && is_array($sliders_array[$slidernum])) {
                $n=0;
                foreach ($sliders_array[$slidernum] as /*banner_key*/$k=>$banner_array) {
                    if($k != self::$_settingsTitle) {
                        if($skinObj) {
                            $array_merge  = LenSliderSkins::_lenslider_skin_merge_array($skinObj->bannerMergeArray, $banner_array);
                            $array_unset  = $skinObj->bannerUnsetArray;
                        }
                        $return .= $this->lenslider_banner_item($n, $slidernum, $this->bannersLimit, &$array_merge, &$array_unset, $sliders_array[$slidernum][self::$_settingsTitle]['ls_has_thumb'], $banner_array['url_type'], $banner_array['url_type_id'], $this->_lenslider_make_default_fields_array($banner_array), array(&$banner_array['size'], &$banner_array['size_thumb']), &$banner_array['path'], $k, &$banner_array['path_thumb'], &$banner_array['thumb_id']);
                        $n++;
                    }
                }
            } else $return .= $this->lenslider_banner_item($slidernum, $slidernum, $this->bannersLimit, &$array_merge, &$array_unset, $sliders_array[$slidernum][self::$_settingsTitle]['ls_has_thumb'], $banner_array['url_type'], $banner_array['url_type_id']);
        } else $return = $this->lenslider_banner_item(0, $slidernum, $this->bannersLimit, &$array_merge, &$array_unset, false, $banner_array['url_type'], $banner_array['url_type_id']);
        return $return;
    }

    public static function lenslider_output_slider($slidernum, $echo = true, $check_enable = true) {
        if(($check_enable && self::lenslider_is_enabled_slider($slidernum)) || !$check_enable) {
            $skin_name      = self::_lenslider_get_slider_skin_name($slidernum);
            $slider_banners = self::lenslider_get_slider_banners($slidernum);
            $file_text = ($skin_name != self::$defaultSkin)?file_get_contents(LenSliderSkins::_lenslider_skins_abspath()."/".self::_lenslider_get_slider_skin_name($slidernum)."/output/output.html"):file_get_contents(LenSliderSkins::_lenslider_skins_abspath()."/default.html");
            if(!empty($file_text)) {
                if(!empty($slider_banners) && is_array($slider_banners)) {
                    $slider_settings = self::lenslider_get_slider_settings($slidernum);
                    $el_prefix_array = array("banner", "slidermanage", "additionalinfo");
                    foreach ($el_prefix_array as $el_prefix) {$file_text = self::_lenslider_replace_file_text($slider_banners, $file_text, $el_prefix, $slider_settings, $slidernum);}
                }
                $settings_array = self::lenslider_get_array_from_wp_options(self::$settingsOption);
                if(!empty($settings_array[self::$cacheName])) {
                    $file_text_cache = wp_cache_get("lenslider_cache_{$slidernum}");
                    if (false == $file_text_cache) {
                        $file_text_cache = $file_text;
                        wp_cache_set("lenslider_cache_{$slidernum}", $file_text_cache);
                    }
                    $file_text = $file_text_cache;
                }
                if($echo) {
                    echo $file_text;
                    return;
                }
                return $file_text;
            }
        }
    }
    
    //if $attachment_id == 0 => new banner
    public function lenslider_banner_item($n, $slidernum, $banners_limit, $array_merge = false, $array_unset = false, $has_thumb = false, $url_type = 'ls_url', $url_type_id = false, $array = false, $sizes_array = false, $img = false, $attachment_id = 0, $img_thumb = false, $attachment_thumb_id = 0) {
        $nn=$n+1;
        
        $img = ($img)?self::$siteurl.$img:false;
        $img_thumb = ($has_thumb && $img_thumb)?self::$siteurl.$img_thumb:false;
        $hidden = (!$has_thumb && !$img_thumb && !$attachment_thumb_id)?" style=\"display:none;\"":"";
        $post_title = '';
        if($nn <= $banners_limit) {
            $ret  = "
            <li class=\"postbox bitem bitem_{$slidernum}";if(@$_COOKIE["folding_{$slidernum}"] == 'svernuto') $ret .= " min";$ret .= "\" id=\"bitem_{$attachment_id}\">
                <input type=\"hidden\" name=\"bannerhidden[{$slidernum}][]\" />
                <div id=\"anchor_{$slidernum}{$n}\"></div>
                <div id=\"post_hidden_uth_ls_link_{$slidernum}_{$n}\">".self::lenslider_banner_hidden($slidernum, 'url_type', $url_type)."</div><div id=\"post_hidden_ls_link_{$slidernum}_{$n}\">";
                if($url_type != 'ls_url' && !empty($array) && is_array($array) && !empty($array['ls_link']['value'])) $ret .= self::lenslider_banner_hidden($slidernum, 'ls_link', $array['ls_link']['value']);
                $ret .= "</div><div id=\"url_type_id_{$slidernum}_{$n}\">";
                if($url_type != 'ls_url' && !empty($array) && is_array($array) && !empty($array['ls_link']['value'])) $ret .= self::lenslider_banner_hidden($slidernum, 'url_type_id', $url_type_id);
                $ret .= "
                </div><div class=\"ls_slide_image_inner_overlay\" id=\"boverlay_{$attachment_id}\" style=\"display:none;\"></div>";
                if($attachment_id) $ret .= "<div class=\"handlediv atipsy\" title=\"".sprintf(__("Banner %d expand / collapse", 'lenslider'), $nn)."\"><a href=\"javascript:;\"></a></div>
                <div class=\"ls_banner_control ls_banner_close\"><a class=\"liveajaxbdel atipsy\" id=\"liveajaxbdel_{$attachment_id}_{$attachment_thumb_id}\" href=\"javascript:;\" title=\"".sprintf(__("Delete banner %d of Slider %s", 'lenslider'), $nn, $slidernum)."\"></a></div>";
                elseif($n!=0) $ret .= "<div class=\"ls_banner_control ls_banner_close\"><a class=\"livebdel\" href=\"javascript:;\"></a></div>";
                $ret .= "
                <h3 class=\"hndle\"><span>".sprintf(__("Banner %d", 'lenslider'), $nn)."{$post_title}</span></h3>
                <div class=\"inside\">
                    <table border=\"0\" width=\"100%\">
                        <tr>
                            <td width=\"300\" valign=\"top\">
                                <label class=\"ls_label\">".sprintf(__("Banner %d image", 'lenslider'), $nn)."</label>";
                                if($img && $attachment_id) {
                                    $ret .= "<div class=\"ls_slide_image\" id=\"slide_image_{$attachment_id}\">
                                                <div class=\"ls_slide_image_inner\">";
                                                if($sizes_array[0]) $ret .= "<div class=\"ls_abs_size ls_rounded\" style=\"display:none;\">{$sizes_array[0]}</div>";
                                    $ret .= "<div class=\"ls_slide_image_inner_controls ls_rounded\">
                                                <ul>
                                                    <li>";
                                                        $ret .= "<a class=\"c_del atipsy\" id=\"mbgdel_{$attachment_id}_{$attachment_thumb_id}_{$slidernum}\" href=\"javascript:;\" title=\"".__("Delete image", 'lenslider')."\"></a>";
                                                    $ret .= "</li>
                                                </ul><div class=\"clear\"></div>
                                            </div><!--ls_slide_image_inner_controls-->
                                            <div class=\"ls_slide_image_inner_overlay\" style=\"display:none;\" id=\"overlay_{$attachment_id}\"></div>
                                            <a href=\"{$img}\" class=\"thickbox\" rel=\"thbgal_{$slidernum}\"><img class=\"himg150\" src=\"{$img}\" style=\"height:150px;\" /></a>
                                        </div><!--ls_slide_image_inner-->
                                    </div><!--ls_slide_image-->";
                                }
                                $ret .= "
                                <input type=\"hidden\" id=\"delatt_{$attachment_id}\" name=\"binfo[{$slidernum}][ls_att_id][]\" value=\"{$attachment_id}\" />
                                <input type=\"file\" name=\"ls_image[{$slidernum}][]\" accept=\"image/*\" />";
                                if($has_thumb) {
                                $ret.="
                                <div class=\"tgl_thumb_{$slidernum}\"{$hidden}>
                                <label class=\"ls_label\" style=\"margin-top:10px\">".sprintf(__("Banner %d thumbnail", 'lenslider'), $nn)."</label><br />";
                                if($img_thumb && $attachment_thumb_id) {
                                    $ret .= "<div class=\"ls_slide_image\" id=\"slide_image_thumb_{$attachment_thumb_id}\">
                                        <div class=\"ls_slide_image_inner thmb\">";
                                        if($sizes_array[1]) $ret .= "<div class=\"ls_abs_size ls_rounded\" style=\"display:none;\">{$sizes_array[1]}</div>";
                                        $ret .= "<div class=\"ls_slide_image_inner_controls ls_rounded\">
                                                <ul>
                                                    <li>";
                                                        $ret .= "<a class=\"c_thdel atipsy\" id=\"mbgthdel_{$attachment_id}_{$attachment_thumb_id}_{$slidernum}\" href=\"javascript:;\" title=\"".__("Delete thumbnail", 'lenslider')."\"></a>";
                                                    $ret .= "</li>
                                                </ul><div class=\"clear\"></div>
                                            </div><!--ls_slide_image_inner_controls-->
                                            <div class=\"ls_slide_image_inner_overlay\" style=\"display:none;\" id=\"overlay_{$attachment_thumb_id}\"></div>
                                            <a href=\"{$img_thumb}\" class=\"thickbox\" rel=\"thbthgal_{$slidernum}\"><img class=\"himg50\" src=\"{$img_thumb}\" style=\"height:50px;\" /></a>
                                        </div><!--ls_slide_image_inner-->
                                    </div><!--ls_slide_image-->";
                                }
                                $ret .= "
                                    <input type=\"hidden\" id=\"delthatt_{$attachment_thumb_id}\" name=\"binfo[{$slidernum}][ls_att_thumb_id][]\" value=\"{$attachment_thumb_id}\" />
                                    <input type=\"file\" name=\"ls_thumb_image[{$slidernum}][]\" accept=\"image/*\" />";
                                }
                                $ret.="
                                </div>
                            </td>
                            <td valign=\"top\">";
                                $array = (!$array)?$this->_lenslider_make_default_fields_array():$array;
                                $ret  .= $this->_lenslider_banner_item_add($slidernum, $n, $array, $array_merge, $array_unset, $attachment_id, $url_type, $url_type_id);
                                $ret  .= "
                            </td>
                        </tr>
                    </table>
                </div><!--inside-->
            </li><!--postbox-->";
            return $ret;
        }
        return false;
    }

    public function lenslider_slider_item($sliderhash, $sliders_limit, $sliders_count = false, $skin_name = false, $show_controls = true, $settings_array = false, $new_slider = true) {
        $n_slider       = ($new_slider || !$sliderhash)?$this->lenslider_hash():$sliderhash;
        $settings_array = ($new_slider)?self::lenslider_get_array_from_wp_options(self::$settingsOption):self::lenslider_get_slider_settings($n_slider);
        $skins_disabled = ($new_slider)?true:false;
        $skin_check     = (!$new_slider)?$settings_array[self::$skinName]:$skin_name;
        $nn_slider = 0;
        if($sliders_count && $new_slider)   $nn_slider = $sliders_count+1;
        if(!$sliders_count && !$new_slider) $sliders_count = $n_slider;
        if($nn_slider <= $sliders_limit) {
            if($skin_name) $skinObj = self::lenslider_get_slider_skin_object($skin_name);
            $titled_array = ($skinObj)?$this->_lenslider_settings_titled_array($settings_array, $this->_lenslider_make_default_slider_settings_array($n_slider, $settings_array, $new_slider, $skin_name), $skinObj->_sliderMergeSettingsArray):$this->_lenslider_settings_titled_array($settings_array, $this->_lenslider_make_default_slider_settings_array($n_slider, $settings_array, $new_slider, $skin_name));
            $ret = "<div class=\"ls_metabox ls_rounded ls_shadow slnum_{$n_slider}\" id=\"slider_metabox_{$sliders_count}\">
                        <input type=\"hidden\" name=\"sliderhidden[{$n_slider}]\" />
                        <input type=\"hidden\" name=\"slider_skin_name_{$n_slider}\" value=\"{$skin_name}\" />
                        <div class=\"ls_box_header\">
                            <span class=\"ls_title\">".sprintf(__("Slider <u>%s</u>", 'lenslider'), $n_slider)."</span>
                            <div class=\"ls_floatleft\">
                                <ul class=\"tit_tabs tit_tabs_{$n_slider}\">
                                    <li class=\"first_li_{$n_slider} active\"><a class=\"sl_tabs_{$n_slider}\" href=\"#banners_{$n_slider}\"><span class=\"images\">".__('Banners', 'lenslider')."</span></a></li>
                                    <li><a class=\"sl_tabs_{$n_slider}\" href=\"#settings_{$n_slider}\"><span class=\"stngs\">".__('Settings', 'lenslider')."</span></a></li>
                                </ul><div class=\"clear\"></div>
                            </div><!--ls_floatleft-->
                            <div class=\"ls_floatright\">
                                <ul class=\"utm_ul\">";
                                if(!$new_slider) {
                                    $ret.="<li class=\"r\"><a id=\"delslider_{$n_slider}\" href=\"javascript:;\" class=\"ls_minibutton slajaxdel atipsy\"><span class=\"del\">".__("Full delete Slider", 'lenslider')."</span></a></li>
                                    <li class=\"r\"><a id=\"sl_banner_{$n_slider}\" class=\"ls_minibutton sl_banners atipsy\" href=\"javascript:;\" title=\"".sprintf(__("Expand / Collapse Slider %s banners", 'lenslider'), $n_slider)."\"><span class=\"";$ret.=(@$_COOKIE["folding_{$n_slider}"]=='svernuto')?"plus":"minus";$ret.="\">";$ret.=(@$_COOKIE["folding_{$n_slider}"]=='svernuto')?__("Maximize", 'lenslider'):__("Minimize", 'lenslider');$ret.="</span></a></li>";
                                } elseif($show_controls) {
                                    $ret.="<li class=\"r\"><a id=\"delslider_{$n_slider}\" href=\"javascript:;\" class=\"ls_minibutton atipsy slremove\"><span class=\"del\">".__("Remove Slider form", 'lenslider')."</span></a></li>";
                                }
                                $ret.="
                                </ul>
                            </div><!--ls_floatright-->
                            <div class=\"clear\"></div>
                        </div><!--ls_box_header-->";
                        if($settings_array[self::$sliderDisenName]==0 && !$new_slider) $ret.="<div class=\"ls_notactive\">";
                        $ret.="
                        <div class=\"ls_under_title_menu\">
                            <label class=\"ls_label ls_floatleft\" style=\"margin:5px 6px 0 0\" for=\"skin_for_{$n_slider}\">".__('Slider skin', 'lenslider')."</label>
                            <div class=\"ls_floatleft\" style=\"margin-right:20px\">".LenSliderSkins::lenslider_skins_dropdown("slset[{$n_slider}][".self::$skinName."]", $skin_check, $skins_disabled, "style=\"width:100px\" class=\"swskin swskin_{$n_slider} atipsy_w\" title=\"".sprintf(self::$skin_change_note, $n_slider)."\" id=\"skin_for_{$n_slider}\"")."</div>
                            <div class=\"ls_floatleft\" style=\"padding-top:5px;\">
                                <input type=\"radio\" name=\"slset[{$n_slider}][".self::$sliderDisenName."]\" id=\"".self::$sliderDisenName."_en_{$n_slider}\" value=\"1\" ";if($new_slider) {$ret.="checked=\"checked\"";}$ret.=checked($settings_array[self::$sliderDisenName], 1, false);$ret.=" /><label class=\"sl_en";if($settings_array[self::$sliderDisenName]==1 || $new_slider) {$ret.=" sl_disen_cur";}$ret.="\" for=\"".self::$sliderDisenName."_en_{$n_slider}\">&nbsp;".__("Enable", 'lenslider')."</label>
                                <span class=\"ls_sep\"></span>
                                <input type=\"radio\" name=\"slset[{$n_slider}][".self::$sliderDisenName."]\" id=\"".self::$sliderDisenName."_dis_{$n_slider}\" value=\"0\" ";$ret.=checked($settings_array[self::$sliderDisenName], 0, false);$ret.=" /><label class=\"sl_dis";if($settings_array[self::$sliderDisenName]==0) {$ret.=" sl_disen_cur";}$ret.="\" for=\"".self::$sliderDisenName."_dis_{$n_slider}\">&nbsp;".__("Disable", 'lenslider')."</label>
                                <span class=\"ls_sep\"></span>
                            </div>
                            <div class=\"ls_title_comment ls_floatleft\">
                                <input type=\"text\" maxlength=\"60\" name=\"slset[{$n_slider}][".self::$sliderComment."]\" id=\"".self::$sliderComment."_{$n_slider}\" value=\"";
                                $ret .= (!empty($settings_array[self::$sliderComment]))?$settings_array[self::$sliderComment]:__('slider comment', 'lenslider');
                                $ret .= "\" ";
                                if(empty($settings_array[self::$sliderComment])) $ret .= "class=\"ls_input prevent\" ";
                                $ret .= "/>
                            </div>
                            <div class=\"ls_floatright\">
                                <ul class=\"utm_ul\">
                                    <!--li class=\"r\"><a class=\"ls_minibutton\" href=\"#\"><span class=\"rolldown\">".__('Quick info', 'lenslider')."</span></a></li-->
                                    <li class=\"r\"><a class=\"ls_minibutton thickbox\" title=\"".sprintf(__("Slider #%s preview", 'lenslider'), $n_slider)."\" href=\"".plugins_url('ls-preview.php', $this->indexFile)."?slidernum={$n_slider}&keepThis=true&TB_iframe=true&height=600&width=1000\"><span class=\"export\">".__('Preview', 'lenslider')."</span></a></li>
                                </ul><div class=\"clear\"></div>
                            </div><!--ls_floatright-->
                            <div class=\"clear\"></div>
                        </div><!--ls_under_title_menu-->
                        <div id=\"banners_{$n_slider}\" class=\"metabox-holder ls_box_content ls_box_content_{$n_slider} sl_content_{$n_slider}\">
                            <ul id=\"slidernum_{$n_slider}\" class=\"sortable meta-box-sortables\">";
                                $ret .= (!empty($skinObj))?$this->lenslider_banners_items($n_slider, $new_slider, $skinObj):$this->lenslider_banners_items($n_slider, $new_slider);
                            $ret .= "
                            </ul>
                        </div><!--ls_box_content-->
                        <div id=\"settings_{$n_slider}\" class=\"ls_box_content ls_box_content_{$n_slider} sl_content_{$n_slider}\" style=\"display:none;\">
                            <div class=\"ls_h3\"><h3>".sprintf(__("Slider %s settings", 'lenslider'), $n_slider)."</h3></div>";
                            $ret .= (!empty($skinObj))?$this->lenslider_slider_settings_add($n_slider, $settings_array, $this->_lenslider_make_default_slider_settings_array($n_slider, $settings_array, $new_slider, $skin_name), $skinObj->_sliderMergeSettingsArray):$this->lenslider_slider_settings_add($n_slider, $settings_array, $this->_lenslider_make_default_slider_settings_array($n_slider, $settings_array, $new_slider, $skin_name));
                            $ret .= 
                            "<div class=\"ls_slider_sets\">
                                <div class=\"ls_load ls_floatleft\" style=\"margin-right:12px;\"><a id=\"set_glob_{$n_slider}\" class=\"ls_minibutton set_global_set_sldr\" href=\"javascript:;\"><span class=\"getdown\">".__("Get global settings", 'lenslider')."</span></a></div>
                                <div class=\"ls_load ls_floatleft\" style=\"margin-right:12px;\"><a id=\"set_local_{$n_slider}\" class=\"ls_minibutton set_local_set_sldr\" href=\"javascript:;\" style=\"display:none;\"><span class=\"getup\">".__("Return local settings", 'lenslider')."</span></a></div>";
                                if($this->_lenslider_skin_has_settings($skin_name)) $ret .= "<div class=\"ls_load ls_floatleft\" style=\"margin-right:12px;\"><a id=\"set_skin_{$n_slider}\" class=\"ls_minibutton set_skin_set_sldr\" href=\"javascript:;\"><span class=\"getdown\">".__("Set skin settings", 'lenslider')."</span></a></div>";
                                $ret .= "<div class=\"clear\"></div>
                            </div>
                            <div class=\"clear\"></div>
                        </div><!--ls_box_content-->";
                        if($settings_array[self::$sliderDisenName]==0 && !$new_slider) $ret.="</div>";
                        $ret.="
                        <div class=\"ls_footer\">
                            <div class=\"ls_floatleft\">
                                <div class=\"ls_load\"><a id=\"banner_slider_{$n_slider}\" class=\"ls_minibutton add_banner\" href=\"javascript:;\"><span class=\"plus\">".sprintf(__("Add new banner for Slider %s", 'lenslider'), $n_slider)."</span></a></div>
                            </div><!--ls_floatleft-->
                            <div class=\"clear\"></div>
                        </div><!--ls_footer-->
                    </div><!--ls_metabox-->";
            return $ret;
        }
        return false;
    }
    
    public static function lenslider_sliders_list() {
        $ret = "";
        $sliders_array = self::lenslider_get_array_from_wp_options(self::$_bannersOption);
        if(!empty($sliders_array) && is_array($sliders_array)) {
            $ret .= "<ul class=\"sliders_nav\">";
                    foreach (array_keys($sliders_array) as $k) {
                        $slider_settings_array = self::lenslider_get_slider_settings($k);
                        $ret .= "<li";
                        if($slider_settings_array[self::$sliderDisenName] == 0) $ret .= " class=\"dis\"";
                        $ret .= "><a href=\"#slider_metabox_{$k}\">";
                        $ret .= (!empty($slider_settings_array[self::$sliderComment]))?sprintf(__("Slider <u>#%s</u><br />(<em>{$slider_settings_array[self::$sliderComment]}</em>)", 'lenslider'), $k):sprintf(__("Slider <u>#%s</u>", 'lenslider'), $k);
                        $ret .= "</a></li>";
                    }
            $ret .= "</ul>";
            return $ret;
        }
    }
}?>