<?php
class LenSlider {
    static $settingsOption         = 'lenslider_settings';
    static $indexPage              = 'len-slider/ls-index.php';
    static $indexPointer           = 'ls_index';
    static $admPagesPointer        = 'ls_adminpages_pointer';
    static $settingsPage           = 'lenslider-settings-page';
    static $skinsPage              = 'lenslider-skins-page';
    static $sliderPage             = 'lenslider-slider-page';
    public $allowed_url_strs       = array('#', 'javascript:;');
    public $indexFile;
    public $imageFileSizeMAX;
    public $bannerWidth;
    public $bannerHeight;
    public $bannerWidthMAX         = 2400;
    public $bannerHeigthMAX        = 1000;
    public $imageWidthMIN          = 30;
    public $slidersLimit;
    static $slidersLimitDefault    = 30;
    public $bannersLimit;
    static $bannersLimitDefault    = 20;
    public $ban_poinersLimitMIN    = 2;
    public $thumbWidthMAX          = 300;
    public $thumbWidthMIN          = 20;
    public $delayMIN               = 1000;
    public $delayMAX               = 20000;
    
    static $defaultSkinWidth       = 936;

    static $version                = '2.0';
    static $bannersOption          = 'lenslider_banners';
    static $settingsTitle          = 'settings';
    static $bannerWidthName        = 'ls_banner_width';
    static $bannerHeightName       = 'ls_banner_height';
    static $maxSizeName            = 'ls_images_maxsize';
    static $slidersLimitName       = 'ls_sliders_limit';
    static $bannersLimitName       = 'ls_banners_limit';
    static $nowVersionName         = 'ls_nowversion';
    static $skinName               = 'ls_slider_skin';
    static $hasThumb               = 'ls_has_thumb';
    static $thumbMaxWidth          = 'ls_thumb_max_width';
    static $hasAutoplay            = 'ls_has_autoplay';
    static $autoplayDelay          = 'ls_autoplay_delay';
    static $autoplayHoverPause     = 'ls_autoplay_hover_pause';
    static $easingEffect           = 'ls_easing_effect';
    static $marginTop              = 'margin-top';
    static $marginRight            = 'margin-right';
    static $marginBottom           = 'margin-bottom';
    static $marginLeft             = 'margin-left';
    static $sliderComment          = 'ls_slider_comment';
    static $sliderRandom           = 'ls_slider_random';
    static $sliderDisenName        = 'ls_slider_disen';
    static $ls_sys_umeta           = 'lenslider_sys_umeta';
    static $ls_welcome_umeta       = 'lenslider_show_welcome_panel';
    static $bannerDisenName        = 'banners_disen';
    static $backlink               = 'ls_backlink';
    static $cacheName              = 'ls_cache';
    static $defaultSkin            = 'default';
    static $siteurl;
    static $toJSVars;
    static $toReplaceUrl           = '{lssiteurl}';

    public $requestSliderURI;
    protected $_requestSettingsURI;
    protected $_requestSkinsURI;
    protected $_settings_grouped_array;
    protected $_allowURIs;
    static $pluginName  = 'len-slider';
    static $pluginNameLoc = 'lenslider';
    public $easing_effects = array(
        'linear', 'swing', 'easeInQuad', 'easeOutQuad', 'easeInOutQuad', 
        'easeInCubic', 'easeOutCubic', 'easeInOutCubic', 'easeInQuart', 'easeOutQuart', 
        'easeInOutQuart', 'easeInQuint', 'easeOutQuint', 'easeInOutQuint', 'easeInExpo', 
        'easeOutExpo', 'easeInOutExpo', 'easeInSine', 'easeOutSine', 'easeInOutSine', 
        'easeInCirc', 'easeOutCirc', 'easeInOutCirc', 'easeInElastic', 'easeOutElastic', 
        'easeInOutElastic', 'easeInBack', 'easeOutBack', 'easeInOutBack', 'easeInBounce', 
        'easeOutBounce', 'easeInOutBounce'
    );
    protected static $_wp35_deregister = array('jquery-effects-core',' jquery-ui-widget', 'jquery-ui-position');
    protected static $_wp35_effects = array('jquery-effects-blind', 
        'jquery-effects-bounce', 'jquery-effects-clip', 'jquery-effects-drop', 
        'jquery-effects-explode', 'jquery-effects-fade', 'jquery-effects-fold', 
        'jquery-effects-highlight', 'jquery-effects-pulsate', 'jquery-effects-scale', 
        'jquery-effects-shake', 'jquery-effects-slide', 'jquery-effects-transfer'
    );
    protected static $_requiredJSHandles = array('jquery', 'jquery-ui-core', 'jquery-effects-core', 'jquery-ui-tabs');
    protected static $_requiredAdminJSHandles = array(
        'jquery', 'jquery-ui-core', 'jquery-ui-sortable', 
        'jquery-ui-spinner', 'jquery-ui-slider', 
        'thickbox', 'media-upload', 'wp-pointer'
    );
    protected static $_requiredAdminJSHandlesOutside = array(
        'wp-pointer'
    );
    protected static $_requiredAdminCSSHandles = array(
        'thickbox', 'wp-pointer', 'media'
    );
    protected static $_requiredAdminCSSHandlesOutside = array(
        'wp-pointer', 'thickbox'
    );
    
    protected static $_roleName = 'LenSlider Manager';
    protected static $_role     = 'lenslider_manager';
    static $capability          = 'lenslider_manage';
    protected $_settingsDefault;
    public $plugin_basename;
    public $requestIndexURI;
    protected $_enabledSliders;
    protected $_sliders_array;
    public $ls_settings;
    protected $_ls_plugin_hook         = array();
    protected $_footer_scripts         = '';

    public function __construct() {
        $this->ls_settings             = $options_array = self::lenslider_get_array_from_wp_options(self::$settingsOption);
        $this->imageFileSizeMAX        = intval(ini_get('upload_max_filesize'));
        $this->bannerWidth             = (!empty($options_array[self::$bannerWidthName])) ?$options_array[self::$bannerWidthName] :$this->bannerWidthMAX;
        $this->bannerHeight            = (!empty($options_array[self::$bannerHeightName]))?$options_array[self::$bannerHeightName]:$this->bannerHeigthMAX;
        $this->slidersLimit            = (!empty($options_array[self::$slidersLimitName]))?$options_array[self::$slidersLimitName]:self::$slidersLimitDefault;
        $this->bannersLimit            = (!empty($options_array[self::$bannersLimitName]))?$options_array[self::$bannersLimitName]:self::$bannersLimitDefault;
        $this->_enabledSliders         = self::_lenslider_get_enabled_sliders_array_slidernums();
        $this->_sliders_array          = self::lenslider_get_array_from_wp_options(self::$bannersOption);
        $this->_sliders_array          = (!empty($this->_sliders_array))?$this->_sliders_array:array();
        $this->_settingsDefault        = self::lenslider_get_default_settings();
        
        self::$siteurl                 = site_url();
        $this->requestIndexURI         = admin_url("admin.php?page=".self::$indexPage);
        $this->indexFile               = ABSPATH.PLUGINDIR."/".self::$indexPage;
        $this->requestSliderURI        = admin_url("admin.php?page=".self::$sliderPage);
        $this->_requestSettingsURI     = admin_url("admin.php?page=".self::$settingsPage);
        $this->_requestSkinsURI        = admin_url("admin.php?page=".self::$skinsPage);
        
        $this->_allowURIs              = array($this->requestIndexURI, $this->requestSliderURI, $this->_requestSettingsURI, $this->_requestSkinsURI);
        $this->plugin_basename         = plugin_basename($this->indexFile);
        $this->_settings_grouped_array = array(
            'general'   => array(self::$bannersLimitName/*, self::$bannerWidthName, self::$bannerHeightName*/),
            'position'  => array(self::$marginTop, self::$marginRight, self::$marginBottom, self::$marginLeft),
            'thumbs'    => array(self::$hasThumb, self::$thumbMaxWidth),
            'slideshow' => array(self::$hasAutoplay, self::$autoplayDelay, self::$autoplayHoverPause, self::$easingEffect)
        );
        
        register_activation_hook($this->plugin_basename,    array(&$this, 'lenslider_register_activation_hook'));
        register_deactivation_hook($this->plugin_basename,  array('LenSlider', 'lenslider_plugin_deactivate'));
        register_uninstall_hook($this->plugin_basename,     array('LenSlider', 'lenslider_plugin_uninstall'));
        add_action('init',                                  array(&$this, 'lenslider_init'));
        add_action('admin_enqueue_scripts',                 array(&$this, 'lenslider_admin_scripts_init'));
        add_action('admin_menu',                            array(&$this, 'lenslider_menu_add'));
        add_action('admin_head',                            array(&$this, 'lenslider_admin_head'));
        add_action('wp_enqueue_scripts',                    array(&$this, 'lenslider_make_skins_files_wp_head'), 90);
        add_action('wp_print_footer_scripts',               array(&$this, 'lenslider_make_skins_files_wp_head_after'), 100);
        add_action('wp_footer',                             array(&$this, 'lenslider_footer_link'));
        //add_action('delete_attachment',                     array(&$this, 'lenslider_check_delete_attachment'));
        add_action('lenslider_banners_processing',          array(&$this, 'lenslider_banners_processing'), 10, 6);
        add_action('admin_bar_menu',                        array(&$this, 'lenslider_wp_admin_bar'), 999);
        $ls_widget = new LenSlider_Widget;
        add_action('widgets_init',                          array($ls_widget, 'registerLenSliderWidget'));
        
        add_action('wp_ajax_ls_welcome_panel',              array(&$this, 'lenslider_welcome_panel'));
        add_action('wp_ajax_ls_ajax_add_banner',            array(&$this, 'lenslider_ajax_add_banner'));
        add_action('wp_ajax_ls_ajax_links_variants',        array(&$this, 'lenslider_ajax_links_variants'));
        add_action('wp_ajax_ls_ajax_link_variants_url',     array(&$this, 'lenslider_ajax_link_variants_url'));
        add_action('wp_ajax_ls_ajax_titles_variants',       array(&$this, 'lenslider_ajax_titles_variants'));
        add_action('wp_ajax_ls_ajax_titles_variants_title', array(&$this, 'lenslider_ajax_titles_variants_title'));
        add_action('wp_ajax_ls_ajax_delete_banner',         array(&$this, 'lenslider_ajax_delete_banner'));
        add_action('wp_ajax_ls_ajax_delete_attachment',     array(&$this, 'lenslider_ajax_delete_attachment'));
        add_action('wp_ajax_ls_ajax_new_media',             array(&$this, 'lenslider_ajax_new_media'));
        add_action('wp_ajax_ls_ajax_del_sys_umeta',         array(&$this, 'lenslider_ajax_del_sys_umeta'));
        add_action('wp_ajax_ls_ajax_new_thumb',             array(&$this, 'lenslider_ajax_new_thumb'));
        add_action('wp_ajax_ls_ajax_delete_thumb',          array(&$this, 'lenslider_ajax_delete_thumb'));
        add_action('wp_ajax_ls_ajax_get_settings_skin',     array(&$this, 'lenslider_ajax_get_settings_skin'));
        add_action('wp_ajax_ls_ajax_delete_skin',           array(&$this, 'lenslider_ajax_delete_skin'));
        
        add_filter('plugin_action_links',                   array(&$this, 'lenslider_action_links'), 10, 2);
        add_filter('plugin_row_meta',                       array(&$this, 'lenslider_plugin_links'), 10, 2);
        //add_filter('contextual_help',                       array(&$this, 'lenslider_plugin_help'), 10, 3);
        add_filter('mce_css',                               array(&$this, 'lenslider_mce_css'));
        add_shortcode('lenslider',                          array(&$this, 'lenslider_shortcode'));
    }
    
    /*---------------INITS METHODS---------------*/
    public function lenslider_register_activation_hook() {
        $user_id = get_current_user_id();
        add_option(self::$bannersOption);
        if(!array_key_exists(self::$nowVersionName, $this->ls_settings)) $this->_lenslider_set_sliders_banners_active();
        update_option(self::$settingsOption, array_merge($this->_settingsDefault, $this->ls_settings));
        add_user_meta($user_id, self::$ls_welcome_umeta, 1, true);
        add_role(self::$_role, self::$_roleName, array(self::$capability));
        add_user_meta($user_id, self::$ls_sys_umeta, 1, true);
        $role = get_role('administrator');
        $role->add_cap(self::$capability);
        $this->_leslider_check_unused_skins_to_default();
        $skins_custom_catalog = LenSliderSkins::_lenslider_skins_custom_abspath();
        if(!file_exists($skins_custom_catalog)) wp_mkdir_p($skins_custom_catalog);
    }

    public static function lenslider_plugin_deactivate() {
        $user_id = get_current_user_id();
        remove_role(self::$_role);
        $role = get_role('administrator');
        $role->remove_cap(self::$capability);
        update_user_meta($user_id, self::$ls_welcome_umeta, 1);
        update_user_meta($user_id, self::$ls_sys_umeta, 1);
    }
    
    public static function lenslider_plugin_uninstall() {
        $user_id = get_current_user_id();
        $sliders_array = self::lenslider_get_array_from_wp_options(self::$bannersOption);
        if(!empty($sliders_array) && is_array($sliders_array)) {
            foreach (array_keys($sliders_array) as $slider_k) {
                self::_lenslider_static_delete_sliders($slider_k, $sliders_array);
            }
        }
        delete_option(self::$bannersOption);
        delete_option(self::$settingsOption);
        $skins_custom_catalog = LenSliderSkins::_lenslider_skins_custom_abspath();
        if(!file_exists($skins_custom_catalog)) self::_lenslider_delete_dir($skins_custom_catalog);
        remove_action('lenslider_banners_processing', array(&$this, 'lenslider_banners_processing'));
        self::lenslider_plugin_deactivate();
        delete_user_meta($user_id, self::$ls_sys_umeta);
        delete_user_meta($user_id, self::$ls_welcome_umeta);
        self::_lenslider_dismiss_pointers();
    }
    
    protected static function _lenslider_dismiss_pointers() {
        $user_id = get_current_user_id();
        $dismissed = explode(',', (string) get_user_meta($user_id, 'dismissed_wp_pointers', true));
        $ls_pointers_array = self::_lenslider_wp_pointer_content();
        $ls_pointers_keys = array_keys($ls_pointers_array);
        $ret_array = array();
        foreach($dismissed as $dismiss) {
            if(!in_array($dismiss, $ls_pointers_keys)) $ret_array[] = $dismiss;
        }
        return update_user_meta($user_id, 'dismissed_wp_pointers', join(',', $ret_array));
    }

    public function lenslider_admin_scripts_init() {
        if($this->_lenslider_is_plugin_page()) {
            $current_wp_ver = self::lenslider_get_wp_version();
            foreach (self::$_requiredAdminJSHandles as $hndl) {
                if($hndl == 'media-upload') {
                    if(function_exists('wp_enqueue_media') && version_compare($current_wp_ver, 3.5, ">=")) wp_enqueue_media();
                    wp_enqueue_script($hndl);
                    continue;
                }
                if($hndl == 'jquery-ui-spinner' && version_compare($current_wp_ver, 3.5, '<')) {
                    wp_deregister_script($hndl);
                    wp_register_script($hndl, plugins_url("js/ui19/".str_ireplace("-", ".", $hndl).".min.js", $this->indexFile), self::$_requiredAdminJSHandles);
                }
                wp_enqueue_script($hndl);
            }
            
            foreach (self::$_requiredAdminCSSHandles as $hndl) {
                wp_enqueue_style($hndl);
            }
            
            wp_register_script('ls_toggleswitch', plugins_url('js/jquery-ui.toggleSwitch.js',    $this->indexFile), self::$_requiredAdminJSHandles);
            wp_enqueue_script('ls_toggleswitch');
            
            wp_register_script('ls_cookie',       plugins_url('js/jquery.cookie.js',             $this->indexFile), self::$_requiredAdminJSHandles);
            wp_enqueue_script('ls_cookie');

            wp_register_script('ls_mtip',         plugins_url('js/mTip-v1.0.3.js',               $this->indexFile), self::$_requiredAdminJSHandles);
            wp_enqueue_script('ls_mtip');
            
            wp_register_script('ls_admin',        plugins_url('js/ls_admin.js',                  $this->indexFile), self::$_requiredAdminJSHandles);
            wp_enqueue_script('ls_admin');
            
            wp_register_script('ls_pointer',      plugins_url('js/ls_pointer.js',                $this->indexFile), self::$_requiredAdminJSHandles);
            wp_enqueue_script('ls_pointer');
            
            wp_register_script('ls_alert',        plugins_url('js/alert.min.js',                 $this->indexFile), self::$_requiredAdminJSHandles);
            wp_enqueue_script('ls_alert');

            wp_register_style('ls_admin_css',     plugins_url('css/ls_admin.css',                $this->indexFile));
            wp_enqueue_style('ls_admin_css');

            wp_register_style('ls_mtip_css',      plugins_url('css/mTip.black.css',              $this->indexFile));
            wp_enqueue_style('ls_mtip_css');
            
            wp_register_style('delta-jquery-ui',  plugins_url('css/delta-jquery-ui-theme.css',   $this->indexFile));
            wp_enqueue_style('delta-jquery-ui');
            
            wp_register_style('jquery-ui-flick',  plugins_url('css/jquery-ui-flick.css',         $this->indexFile));
            wp_enqueue_style('jquery-ui-flick');
            
            wp_register_style('ls_alert_css',     plugins_url('css/alert.min.css',               $this->indexFile));
            wp_enqueue_style('ls_alert_css');
            
            wp_register_style('alert-default',    plugins_url('css/theme.min.css',               $this->indexFile));
            wp_enqueue_style('alert-default');
        } else {
            foreach (self::$_requiredAdminJSHandlesOutside as $hndl) {
                wp_enqueue_script($hndl);
            }
            wp_register_script('ls_pointer',      plugins_url('js/ls_pointer.js',                $this->indexFile), self::$_requiredAdminJSHandlesOutside);
            wp_enqueue_script('ls_pointer');
            foreach (self::$_requiredAdminCSSHandlesOutside as $hndl) {
                wp_enqueue_style($hndl);
            }
        }
    }
    
    public function lenslider_mce_css($mce_css) {
        if($this->_lenslider_is_plugin_page()) {
            $merge_array = array();
            $enabled_skins_data = $this->_lenslider_get_enabled_sliders_data();
            if(!empty($enabled_skins_data['skins']) && is_array($enabled_skins_data['skins'])) {
                foreach ($enabled_skins_data['skins'] as $skin_name) {
                    if($skin_name != self::$defaultSkin) {
                        $skinObjStatic = LenSliderSkins::lenslider_get_skin_params_object($skin_name);
                        if(!empty($skinObjStatic->cssFiles) && is_array($skinObjStatic->cssFiles)) {
                            foreach ($skinObjStatic->cssFiles as $filename) {
                                $merge_array[] = self::$siteurl."/".str_ireplace(ABSPATH, '', $filename);
                            }
                        }
                    }
                }
            }
            $merge_array[] = get_stylesheet_uri();            
            if(!empty($mce_css)) $mce_css .= ',';
            $mce_css .= join(',', $merge_array);
        }
        return $mce_css;
    }
    
    public function lenslider_shortcode($args, $content = null) {
        return self::lenslider_output_slider($args['id'], false);
    }
    
    public function lenslider_action_links($links, $file) {
        if($file == $this->plugin_basename) {
            $settings_link = "<a href=\"{$this->_requestSettingsURI}\">".__("Settings", 'lenslider')."</a>";
            array_unshift($links, $settings_link);
        }
        return $links;
    }
    
    /*public function lenslider_plugin_help($contextual_help, $screen_id, $screen) {
	if(in_array($screen_id, $this->_ls_plugin_hook)) {
            $screen->add_help_tab(array(
                        'id'	  => 'overview',
                        'title'	  => __('Overview', 'lenslider'),
                        'content' => '<p>' . __( 'Descriptive content that will show in My Help Tab-body goes here.', 'lenslider' ) . '</p>'
            ));
            $screen->add_help_tab(array(
                        'id'	  => 'slider_types',
                        'title'	  => __('Slider types', 'lenslider'),
                        'content' => '<p>' . __( '1111Descriptive content that will show in My Help Tab-body goes here.', 'lenslider' ) . '</p>'
            ));
	}
    }*/

    public function lenslider_plugin_links($links, $file) {
            if (!current_user_can('install_plugins')) return $links;
            if ($file == $this->plugin_basename) {
                    $links[] = '<a href="http://www.lenslider.com/faq/" target="_blank">'.__('FAQ', 'lenslider').'</a>';
                    $links[] = '<a href="http://www.lenslider.com/support/" target="_blank">'.__('Support', 'lenslider').'</a>';
                    $links[] = '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=KAGVMDNSS8EEN" target="_blank">'.__('Donate', 'lenslider').'</a>';
            }
            return $links;
    }
    
    public function lenslider_footer_link() {
        if ($this->ls_settings[self::$backlink] == 1) {
            echo "<a href=\"http://www.lenslider.com/\" target=\"_blank\" title=\"".__("WordPress LenSlider", 'lenslider')."\">".__("Free slider plugin for WordPress", 'lenslider')."</a>\n";
        }
    }
    
    protected function _lenslider_make_default_fields_array($banner_array = false) {
        return
        array(
            'ls_link' =>
                array(
                    'title'  => __('Link', 'lenslider'),
                    'value'  => (empty($banner_array['ls_link']))?"http://":$banner_array['ls_link'], 'type' => 'input',
                    'tcheck' => true
                ),
            'ls_title' =>
                array(
                    'title'  => __('Title', 'lenslider'),
                    'value'  => $banner_array['ls_title'], 'type' => 'input',
                    'tcheck' => true
                ),
            'ls_text' =>
                array(
                    'title'  => __('Text', 'lenslider'),
                    'value'  => $banner_array['ls_text'], 'type' => 'textarea', 'mce' => true
                )
        );
    }

    public function lenslider_make_default_slider_settings_array($slidernum, $settings_array = false, $new_slider = false, $skin_name = false, $type = 'general') {
        if(!$settings_array && $new_slider) $settings_array = $this->_settingsDefault;
        $settings_array[self::$skinName] = $skin_name;
        $thumbMaxWidthArray = array(
            'desc' => __("Thumb max width", 'lenslider'),
            'title' => __("Width", 'lenslider'),
            'type' => 'input', 'size' => 5, 'maxlength' => 3, 'spectype' => 'int', 'spinner' => true, 'ext' => 'px'
        );
        if(!$settings_array[self::$hasThumb]) $thumbMaxWidthArray['disabled'] = true;
        switch ($type) {
            case 'general':
                return
                array(
                    self::$bannersLimitName =>
                        array(
                            'desc' => __("Maximum of enabled banners for this slider", 'lenslider'),
                            'title' => __("Banners limit", 'lenslider'),
                            'type' => 'input', 'size' => 5, 'maxlength' => 2, 'spectype' => 'int', 'spinner' => true
                        ),
                    /*self::$bannerWidthName =>
                        array(
                            'desc' => __("Width of the slider content area, pixels", 'lenslider'),
                            'title' => __("Area width", 'lenslider'),
                            'type' => 'input', 'size' => 5, 'maxlength' => 4, 'spectype' => 'int', 'spinner' => true, 'ext' => 'px'
                        ),
                    self::$bannerHeightName =>
                        array(
                            'desc' => __("Height of the slider content area, pixels", 'lenslider'),
                            'title' => __("Area height", 'lenslider'),
                            'type' => 'input', 'size' => 5, 'maxlength' => 4, 'spectype' => 'int', 'spinner' => true, 'ext' => 'px'
                        )*/
                );
            case 'position':
                return array(
                    self::$marginTop =>
                        array(
                            'desc' => __("Margin top for the slider content area", 'lenslider'),
                            'title' => __("Margin top", 'lenslider'),
                            'type' => 'input', 'size' => 5, 'maxlength' => 2, 'spectype' => 'int', 'spinner' => true, 'ext' => 'px'
                        ),
                    self::$marginRight =>
                        array(
                            'desc' => __("Margin right for the slider content area", 'lenslider'),
                            'title' => __("Margin right", 'lenslider'),
                            'type' => 'input', 'size' => 5, 'maxlength' => 2, 'spectype' => 'int', 'spinner' => true, 'ext' => 'px'
                        ),
                    self::$marginBottom =>
                        array(
                            'desc' => __("Margin bottom for the slider content area", 'lenslider'),
                            'title' => __("Margin bottom", 'lenslider'),
                            'type' => 'input', 'size' => 5, 'maxlength' => 2, 'spectype' => 'int', 'spinner' => true, 'ext' => 'px'
                        ),
                    self::$marginLeft =>
                        array(
                            'desc' => __("Margin left for the slider content area", 'lenslider'),
                            'title' => __("Margin left", 'lenslider'),
                            'type' => 'input', 'size' => 5, 'maxlength' => 2, 'spectype' => 'int', 'spinner' => true, 'ext' => 'px'
                        )
                );
            case 'thumbs':
                return array(
                    self::$hasThumb => 
                        array(
                            'desc' => __("Make thumbs", 'lenslider'),
                            'title' => __("Make thumbs", 'lenslider'),
                            'type' => 'checkbox', 'class' => 'chbx_is_thumb'
                        ),
                    self::$thumbMaxWidth => $thumbMaxWidthArray
                );
                break;
            case 'slideshow':
                return array(
                    self::$hasAutoplay => 
                        array(
                            'desc' => __("Enable banners autorotate for the slider", 'lenslider'),
                            'title' => __("Autorotate", 'lenslider'),
                            'type' => 'checkbox', 'class' => 'chbx_is_autoplay'
                        ),
                    self::$autoplayDelay => 
                        array(
                            'desc' => __("Autorotate delay in milliseconds, 1000 = 1 sec.", 'lenslider'),
                            'title' => __("Autorotate delay", 'lenslider'),
                            'type' => 'input', 'spinner' => true, 'ext' => 'ms'
                        ),
                    self::$autoplayHoverPause => 
                        array(
                            'desc' => __("Pause on hover", 'lenslider'),
                            'title' => __("Pause on hover", 'lenslider'),
                            'type' => 'checkbox'
                        ),
                    self::$easingEffect => 
                        array(
                            'desc' => __("Easing effect for banners", 'lenslider'),
                            'title' => __("Easing", 'lenslider'),
                            'type' => 'select', 'values' => $this->easing_effects
                        )
                );
        }
        return array();
    }
    
    protected function _lenslider_make_grouped_slider_settings_array($array, $key = false) {
        $ret_array = array();
        if(!empty($array) && is_array($array)) {
            $tmp_array = $array;
            foreach ($array as $k=>$v) {
                foreach ($this->_settings_grouped_array as $kk=>$vv) {
                    if(in_array($k, $vv)) {
                        $ret_array[$kk][$k] = $v;
                        unset($tmp_array[$k]);
                    }
                }
                if(!empty($tmp_array) && is_array($tmp_array)) $ret_array['skin'] = $tmp_array;
            }
        }
        return (!empty($key) && !empty($ret_array[$key]))?$ret_array[$key]:$ret_array;
    }

    public function lenslider_make_skins_files_wp_head() {
        if(!empty($this->_enabledSliders) && is_array($this->_enabledSliders)) {
            $cur_wp_ver = self::lenslider_get_wp_version();
            $i=0;
            foreach (self::$_requiredJSHandles as $hndl) {
                if(version_compare($cur_wp_ver, 3.5, '<')) {
                    if($i==0) {
                        foreach(self::$_wp35_deregister as $wp35_hndl) {
                            wp_deregister_script($wp35_hndl);
                            wp_register_script($wp35_hndl, plugins_url("js/ui19/".str_ireplace("-", ".", $wp35_hndl).".min.js", $this->indexFile), array('jquery'));
                        }
                        foreach(self::$_wp35_effects as $wp35_ehndl) {
                            $name_arr = explode('-', $wp35_ehndl);
                            $name = $name_arr[2];
                            wp_deregister_script($wp35_ehndl);
                            wp_register_script($wp35_ehndl, plugins_url("js/ui19/jquery.ui.effect-{$name}.min.js", $this->indexFile), array('jquery-effects-core'));
                        }
                    }
                    if($hndl == 'jquery-ui-core') {
                        wp_deregister_script($hndl);
                        wp_register_script($hndl, plugins_url('js/ui19/jquery.ui.core.min.js', $this->indexFile), array('jquery'));
                        wp_enqueue_script($hndl);
                        $i++;
                        continue;
                    }
                    if($hndl == 'jquery-effects-core') {
                        wp_deregister_script($hndl);
                        wp_register_script($hndl, plugins_url('js/ui19/jquery.ui.effect.min.js', $this->indexFile), array('jquery'));
                        wp_enqueue_script($hndl);
                        $i++;
                        continue;
                    }
                    if($hndl == 'jquery-ui-tabs') {
                        wp_deregister_script($hndl);
                        wp_register_script($hndl, plugins_url('js/ui19/jquery.ui.tabs.min.js', $this->indexFile), self::$_requiredJSHandles);
                        wp_enqueue_script($hndl);
                        $i++;
                        continue;
                    }
                }
                wp_enqueue_script($hndl);
            }
            wp_register_script('default-skin-custom', plugins_url('js/default-skin-custom.js', $this->indexFile), self::$_requiredJSHandles, self::$version, true);
            wp_enqueue_script('default-skin-custom');
            if(version_compare($cur_wp_ver, 3.5, '>=')) {
                wp_register_script('jquery-ui-tabs-rotate', plugins_url('js/jquery-ui-tabs-rotate.js', $this->indexFile), self::$_requiredJSHandles);
                wp_enqueue_script('jquery-ui-tabs-rotate');
            }
            $enabled_skins_data = $this->_lenslider_get_enabled_sliders_data();//die(var_dump($this->_lenslider_make_grouped_slider_settings_array($enabled_skins_data['settings'])));
            if(!empty($enabled_skins_data['skins']) && is_array($enabled_skins_data['skins'])) {
                foreach ($enabled_skins_data['skins'] as $skin_name) {
                    if($skin_name != self::$defaultSkin) {
                        $skinObjStatic = LenSliderSkins::lenslider_get_skin_params_object($skin_name);
                        if(!empty($skinObjStatic->jsFiles) && is_array($skinObjStatic->jsFiles)) {
                            foreach ($skinObjStatic->jsFiles as $filename) {
                                $reg_name = str_ireplace(".js", '', basename($filename)."-{$skin_name}");
                                wp_register_script($reg_name, self::$siteurl."/".str_ireplace(ABSPATH, '', $filename), self::$_requiredJSHandles, self::$version, true);
                                wp_enqueue_script($reg_name);
                            }
                        }
                        if(!empty($skinObjStatic->cssFiles) && is_array($skinObjStatic->cssFiles)) {
                            foreach ($skinObjStatic->cssFiles as $filename) {
                                $reg_name = str_ireplace(".css", '', basename($filename)."-{$skin_name}");
                                wp_register_style($reg_name, self::$siteurl."/".str_ireplace(ABSPATH, '', $filename));
                                wp_enqueue_style($reg_name);
                            }
                        }
                    }
                    if(!empty($enabled_skins_data['settings']) && is_array($enabled_skins_data['settings'])) {
                        $sliders_skins_array = $this->lenslider_get_sliders_skins_names();
                        foreach ($enabled_skins_data['settings'] as $slidernum => $sett_arr) {
                            $tmp_settings = $this->_lenslider_make_grouped_slider_settings_array($sett_arr);
                            unset($tmp_settings['general']);
                            if(!empty($tmp_settings) && is_array($tmp_settings)) {
                                if(array_key_exists($slidernum, $sliders_skins_array)) $this->_footer_scripts .= "{$skin_name}_lenslider_fn(".json_encode($tmp_settings).");";
                            }
                        }
                    }
                }
            }
            wp_register_style('default-skin-css', plugins_url('css/defaultskin.css', $this->indexFile));
            wp_enqueue_style('default-skin-css');
        }
    }
    
    public function lenslider_make_skins_files_wp_head_after() {
        if(!empty($this->_enabledSliders)) {
            echo '<style type="text/css">';
            foreach ($this->_enabledSliders as $slidernum) {
                if(!empty($this->_sliders_array[$slidernum][self::$settingsTitle][self::$marginTop]) || !empty($this->_sliders_array[$slidernum][self::$settingsTitle][self::$marginRight]) || !empty($this->_sliders_array[$slidernum][self::$settingsTitle][self::$marginBottom]) || !empty($this->_sliders_array[$slidernum][self::$settingsTitle][self::$marginLeft])) {
                    echo "#ls_slider_outer_{$slidernum}{";
                    if(!empty($this->_sliders_array[$slidernum][self::$settingsTitle][self::$marginTop])) echo self::$marginTop.":".$this->_sliders_array[$slidernum][self::$settingsTitle][self::$marginTop]."px;";
                    if(!empty($this->_sliders_array[$slidernum][self::$settingsTitle][self::$marginRight])) echo self::$marginRight.":".$this->_sliders_array[$slidernum][self::$settingsTitle][self::$marginRight]."px;";
                    if(!empty($this->_sliders_array[$slidernum][self::$settingsTitle][self::$marginBottom])) echo self::$marginBottom.":".$this->_sliders_array[$slidernum][self::$settingsTitle][self::$marginBottom]."px;";
                    if(!empty($this->_sliders_array[$slidernum][self::$settingsTitle][self::$marginLeft])) echo self::$marginLeft.":".$this->_sliders_array[$slidernum][self::$settingsTitle][self::$marginLeft]."px;";
                    echo "}";
                }
            }
            echo '</style>';
        }
        echo (!empty($this->_footer_scripts))?"<script type=\"text/javascript\">jQuery(document).ready(function(){".$this->_footer_scripts."});</script>":'';
    }

    public function lenslider_menu_add() {
        add_menu_page('Lenslider', 'LenSlider', 'activate_plugins', $this->indexFile, 'lenslider_index_gallery', plugins_url('images/icon_menu.png', $this->indexFile));
        
        $this->_ls_plugin_hook[] = add_submenu_page($this->indexFile, __('LenSlider Sliders', 'lenslider'),         __('Sliders', 'lenslider'),         'activate_plugins', $this->indexFile,    'lenslider_index_gallery');
        $this->_ls_plugin_hook[] = add_submenu_page($this->indexFile, __('Add/Edit Slider', 'lenslider'),           __('Add/Edit Slider', 'lenslider'), 'activate_plugins', self::$sliderPage,   'lenslider_slider_page');
        $this->_ls_plugin_hook[] = add_submenu_page($this->indexFile, __('Available LenSlider skins', 'lenslider'), __('Skins', 'lenslider'),           'activate_plugins', self::$skinsPage,    'lenslider_skins_page');
        $this->_ls_plugin_hook[] = add_submenu_page($this->indexFile, __('LenSlider settings', 'lenslider'),        __('Settings', 'lenslider'),        'activate_plugins', self::$settingsPage, 'lenslider_settins_page');
    }
    
    public function lenslider_wp_admin_bar() {
        if(!is_admin() && current_user_can('edit_posts')) {
            global $wp_admin_bar;
            $wp_admin_bar->add_node(array(
                'parent' => 'site-name',
                'id' => 'lenslider',
                'title' => 'LenSlider',
                'href' => $this->requestIndexURI
            ));
        }
        return;
    }

    protected static function _lenslider_wp_pointer_content($page = false) {
        $ret_array = array(
            self::$admPagesPointer => array(
                'content' => "<h3>".__('LenSlider2: Feel new power', 'lenslider')."</h3><p>".sprintf(__('LenSlider2 has been considerably enhanced with regard to users opinions and plans that were set right from the start.<br /><br /><strong><a href=\"%s\">View/manage the Sliders list</a></strong>', 'lenslider'), admin_url("admin.php?page=".self::$indexPage))."</p>",
                'pointerWidth' => 400
            ),
            self::$indexPointer => array(
                'content' => "<h3>".__('LenSlider Sliders list', 'lenslider')."</h3><p>".__('This is the LenSlider Sliders list, here you can manage the ones as posts, pages or custom post types in WordPress-standart list table.', 'lenslider')."</p>",
                'pointerWidth' => 400
            ),
            self::$sliderPage => array(
                'content' => "<h3>".__('Slider item manager', 'lenslider')."</h3><p>".__('This is were you can edit Slider banners and settings or add a new Slider. If you add a new Slider, you need to choose a skin for it before, but you can change it after.', 'lenslider')."</p>",
                'pointerWidth' => 400
            ),
            self::$skinsPage => array(
                'content' => "<h3>".__('LenSlider skins available for you', 'lenslider')."</h3><p>".__('Here listed LenSliders skins available for you. Default skin is invisible and always with you. You can upload custom skins from LenSlider site catalog. So manage your skins here, but remember: skins that currently used for Sliders can not to be deleted.', 'lenslider')."</p>",
                'pointerWidth' => 400
            ),
            self::$settingsPage => array(
                'content' => "<h3>".__('General plugin settings', 'lenslider')."</h3><p>".__('On this page you can set your own plugin global settings. &laquo;Media files&raquo; global settings that duplicate Slider settings have a lower priority than Slider settings.', 'lenslider')."</p>",
                'pointerWidth' => 400
            )
        );
        if(!empty($page)) {
            if(!empty($ret_array[$page])) return $ret_array[$page];
        } else return $ret_array;
        return false;
    }

    public function lenslider_admin_head() {
        $page = esc_attr($_GET['page']);
        $slidernum = esc_attr($_GET['slidernum']);
        if($page == self::$indexPage) $page = self::$indexPointer;
        if($this->_lenslider_is_plugin_page()) {
            $arr = array(
                'yes' => __('Yes', 'lenslider'),
                'no' => __('No', 'lenslider'),
                'confirmTitle' => __('Confirm', 'lenslider'),
                'warningTitle' => __('Warning', 'lenslider'),
                'errorTitle' => __('Error', 'lenslider'),
                'emptySizeStr' => __('Size is empty', 'lenslider'),
                'fullBannersLimitError' => __('Banners limit is full', 'lenslider'),
                'confirmText' => __('Are you sure?', 'lenslider'),
                'skinSettingsConfirmStr' => __("Do you want to set skin settings for the slider?", 'lenslider'),
                'ajaxNonce' => wp_create_nonce($this->plugin_basename.LOGGED_IN_KEY.site_url()),
                'wp_version' => self::lenslider_get_wp_version(),
                'user_id' => get_current_user_id(),
                'wp_uploader_title' => __('LenSlider Media Manager', 'lenslider'),
                'wp_uploader_button' => __('Select', 'lenslider')
            );
            echo "<script type=\"text/javascript\">
                    jQuery(function() {
                        lenSliderJSReady(".$this->_lenslider_is_plugin_page().", ".json_encode($this->allowed_url_strs).", ".json_encode($arr).");";
                        if(self::_lenslider_check_pointer_issue($page)) {
                            $pointer_array = self::_lenslider_wp_pointer_content($page);
                            echo "ls_wp_pointer(\".ls_h2\", \"{$page}\", \"{$pointer_array['content']}\", \"{$pointer_array['position']}\", \"{$pointer_array['pointerWidth']}\");";
                        }
                        if(!empty($slidernum) && !empty($this->_sliders_array[$slidernum])) echo "jQuery(\"#".self::$bannersLimitName."_{$slidernum}\").spinner(\"option\", \"min\", {$this->_sliders_array[$slidernum][self::$settingsTitle][self::$bannersLimitName]});";
                        echo "
                    });
                    </script>\n";
        } else {
            if(self::_lenslider_check_pointer_issue(self::$admPagesPointer)) {
                $pointer_array = self::_lenslider_wp_pointer_content(self::$admPagesPointer);
                echo "<script type=\"text/javascript\">
                        jQuery(function() {
                            ls_wp_pointer(\"#toplevel_page_len-slider-ls-index\", \"".self::$admPagesPointer."\", \"{$pointer_array['content']}\", ".json_encode(array('edge'=>'left','align'=>'left')).");
                        });
                        </script>";
            }
        }
    }
    
    public function lenslider_init() {
        if(is_admin() && current_user_can('edit_posts') && current_user_can('edit_pages') && current_user_can(self::$capability)) {
            if(get_user_option('rich_editing') == 'true') {
                add_filter('mce_external_plugins', array(&$this, 'lenslider_add_tinymce_plugin'));
                add_filter('mce_buttons',          array(&$this, 'lenslider_register_tinymce_button'));
            }
        }
        $current_locale = get_locale();
        if(!empty($current_locale)) load_plugin_textdomain(self::$pluginNameLoc, false, self::$pluginName."/languages/");
    }
    
    public function lenslider_register_tinymce_button($buttons) {
        array_push($buttons, "", self::$pluginNameLoc);
        return $buttons;
    }
    
    public function lenslider_add_tinymce_plugin($plugin_array) {
        if(!$this->_lenslider_is_plugin_page()) $plugin_array[self::$pluginNameLoc] = plugins_url('js/tinymce.js', $this->indexFile);
        return $plugin_array;
    }
    
    protected function _leslider_check_unused_skins_to_default() {
        if(!empty($this->_sliders_array) && is_array($this->_sliders_array)) {
            $ret_array = $this->_sliders_array;
            foreach (array_keys($this->_sliders_array) as $slidernum) {
                $slider_settings = self::lenslider_get_slider_settings($slidernum);
                if(!in_array($slider_settings[self::$skinName], LenSliderSkins::lenslider_skins_folders_array())) $slider_settings[self::$skinName] = self::$defaultSkin;
                $ret_array[$slidernum][self::$settingsTitle] = $slider_settings;
            }
            $this->_lenslider_update_lenslider_option(self::$bannersOption, $ret_array);
        }
        return true;
    }
    /*---------------/INITS METHODS---------------*/
    
    
    /*---------------HELPER METHODS---------------*/
    public static function lenslider_hash() {
        return substr(md5(microtime().mt_rand(10000, 9999999999)), 3, 10);
    }
    
    public function lenslider_size_str($width, $height) {
        return "{$width} &times; {$height}";
    }

    public function lenslider_get_attachment_params($att_id) {
        if($att_id) {
            $retObj            = new stdClass;
            $retObj->httpPath  = wp_get_attachment_url($att_id);
            $retObj->absAttUrl = ABSPATH.str_ireplace(self::$siteurl."/", '', $retObj->httpPath);
            $retObj->ext       = strtolower(pathinfo($retObj->absAttUrl, PATHINFO_EXTENSION));
            $size_array        = @getimagesize($retObj->absAttUrl);
            if(empty($size_array)) $size_array = @getimagesize($retObj->httpPath);
            $retObj->width     = $size_array[0];
            $retObj->height    = $size_array[1];
            $retObj->size      = $this->lenslider_size_str($size_array[0], $size_array[1]);
            $retObj->mime      = $size_array['mime'];
            return $retObj;
        }
        return false;
    }
    
    public static function lenslider_dropdown_sliders($select_name, $check = false, $select_id = false, $style_string = false) {
        $enabled_sliders = self::_lenslider_get_enabled_sliders_array_slidernums();
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
    
    public static function lenslider_dropdown_posts($slidernum, $banner_k, $n, $prefix, $check = false) {
        $posts = get_posts(array(
            'numberposts' => -1
        ));
        if(!empty($posts)) {
            $ret = "<select name=\"blink_select_{$slidernum}_{$banner_k}_{$n}\" id=\"{$prefix}_select_{$slidernum}_{$banner_k}_{$n}\" style=\"max-width:220px;\">";
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
    
    public static function lenslider_dropdown_pages($slidernum, $banner_k, $n, $prefix, $check = -1) {
        return wp_dropdown_pages(array('echo'=>0, 'show_option_none'=>__('Select...', 'lenslider'), 'name'=>"{$prefix}_select_{$slidernum}_{$banner_k}_{$n}", 'selected'=>$check));
    }
    
    public static function lenslider_dropdown_categories($slidernum, $banner_k, $n, $prefix, $check = -1) {
        return wp_dropdown_categories(array('echo'=>0, 'hide_empty'=>0, 'show_option_none'=>__('Select...', 'lenslider'), 'class'=>'pb_url_select', 'id'=>"{$prefix}_select_{$slidernum}_{$banner_k}_{$n}", 'selected'=>$check));
    }
    
    protected static function _lenslider_crop_vars() {
        return array(
            'left_top'      => __('From left top corner',      'lenslider'),
            'center_top'    => __('From center top corner',    'lenslider'),
            'right_top'     => __('From right top corner',     'lenslider'),
            'left_middle'   => __('From left middle corner',   'lenslider'),
            'center_middle' => __('From center middle corner', 'lenslider'),
            'right_middle'  => __('From right middle corner',  'lenslider'),
            'left_bottom'   => __('From left bottom corner',   'lenslider'),
            'center_bottom' => __('From center bottom corner', 'lenslider'),
            'right_bottom'  => __('From right bottom corner',  'lenslider')
        );
    }

    public static function lenslider_dropdown_crop_vars($name, $attrs = '', $check = false) {
        $arr = self::_lenslider_crop_vars();
        $ret = "<select {$attrs} name=\"{$name}\">";
        foreach ($arr as $k=>$v) {
            $ret .= "<option value=\"{$k}\"";
            if($check && $check == $k) $ret .= " selected=\"selected\"";
            $ret .= ">{$v}</option>";
        }
        $ret .= "</select>";
        return $ret;
    }

    public static function lenslider_allowed_images_mime_types() {
        return array('image/jpg','image/jpeg','image/gif','image/png');
    }

    protected function _lenslider_make_fields_array($input_array, $array_unset, $array_merge) {
        if(!empty($array_unset) && is_array($array_unset)) {
            foreach ($array_unset as $to_unset) {
                unset($input_array[$to_unset]);
            }
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
    
    protected function _lenslider_sanitize_array($array) {
        $ret_array = array();
        if(!empty($array) && is_array($array)) {
            foreach ($array as $k=>$v) {
                $ret_array[$k] = str_ireplace(self::$siteurl, self::$toReplaceUrl, str_replace('"', "&quot;", $v));
            }
        }
        return $ret_array;
    }
    
    protected static function _lenslider_escape_array($array) {
        $ret_array = array();
        if(!empty($array) && is_array($array)) {
            $site_url = site_url();
            foreach ($array as $k=>$v) {
                if(!empty($v) && is_array($v)) {
                    foreach ($v as $kk=>$vv) {
                        $ret_array[$k][$kk] = str_ireplace(self::$toReplaceUrl, $site_url, html_entity_decode(stripcslashes($vv)));
                    }
                }
            }
        }
        return $ret_array;
    }
    
    protected function _lenslider_decode_url($url) {
        return str_ireplace(self::$toReplaceUrl, self::$siteurl, $url);
    }

    public function lenslider_is_english_characters($str) {
        return (strlen(urldecode($str)) != strlen(utf8_decode(urldecode($str))))?false:true;
    }
    
    public function lenslider_make_keytag($keytag) {
        $keytag = sanitize_title($keytag);
        return ($this->lenslider_is_english_characters($keytag))?$keytag:substr(md5(time().mt_rand(1111,9999999)),2,9);
    }
    
    public static function lenslider_get_youtube_id($url) {
        $matches = array();
        preg_match('#^(?:https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com(?:/embed/|/v/|/watch\?v=|/watch\?.+&v=))([\w-]{11})(?:.+)?$#x', $url, $matches);
        return (is_array($matches) && !empty($matches[1]))?$matches[1]:false;
    }
    
    public static function lenslider_get_vimeo_id($link) {
        $matches = array();
        preg_match('/^http:\/\/(www\.)?vimeo\.com\/(clip\:)?(\d+).*$/', $link, $matches);
        return (is_array($matches) && !empty($matches[3]))?$matches[3]:false;
    }
    /*---------------/HELPER METHODS---------------*/
    
    
    /*---------------CHECK METHODS---------------*/
    protected static function _lenslider_is_allowed_option($option) {
        return (in_array($option, array(self::$bannersOption, self::$settingsOption)))?true:false;
    }

    public function lenslider_is_valid_url($data) {
        return (preg_match("/^((http|https):\/\/)?([a-z0-9\-]+\.)?([a-z0-9\-]+\.)?[a-z0-9\-]+\.[a-z0-9]{2,4}(\.[a-z0-9]{2,4})?(\/.*)?$/i", $data) || in_array($data, $this->allowed_url_strs))?true:false;
    }
    
    public static function lenslider_is_slider_exists($slidernum, $sliders_array = false) {
        $sliders_array = (!$sliders_array)?self::lenslider_get_array_from_wp_options(self::$bannersOption):$sliders_array;
        return (!empty($sliders_array) && is_array($sliders_array) && !empty($sliders_array[$slidernum]))?true:false;
        return false;
    }

    public static function lenslider_is_enabled_slider($slidernum) {
        if(self::lenslider_is_slider_exists($slidernum)) {
            $slider_settings = self::lenslider_get_slider_settings($slidernum);
            if($slider_settings[self::$sliderDisenName] == 1) return true;
        }
        return false;
    }
    
    protected function _lenslider_is_plugin_page() {
        $server_uri = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
        foreach ($this->_allowURIs as $allowURI) {
            if(stristr($server_uri, $allowURI)) return true;
        }
        return false;
    }
    
    public function lenslider_is_needle_mime_type($file, $mime_array, $format_array = false) {
        if(!is_array($file)) {
            $to_filesize = ABSPATH.str_ireplace(self::$siteurl."/", '', $file);
            $file_info = @getimagesize($to_filesize);
            if(!empty($file_info)) $file_info = @getimagesize($file);
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
    
    protected function _lenslider_skin_has_settings($skin_name) {
        if($skin_name != self::$defaultSkin) {
            $skinObj = self::lenslider_get_slider_skin_object($skin_name);
            if($skinObj && !empty($skinObj->sliderMergeSettingsArray) && is_array($skinObj->sliderMergeSettingsArray)) {
                foreach ($skinObj->sliderMergeSettingsArray as $arr) {
                    if(array_key_exists('value', $arr)) return true;
                }
            }
        }
        return false;
    }
    
    protected static function _lenslider_check_pointer_issue($handle) {
        $dismissed = explode(',', (string) get_user_meta(get_current_user_id(), 'dismissed_wp_pointers', true));
        return (!in_array($handle, $dismissed))?true:false;
    }
    
    public static function _lenslider_check_server_capability() {
        if(get_user_meta(get_current_user_id(), self::$ls_sys_umeta)) {
            $ret = true;
            $arr = array();
            $ret_echo = '';
            $ret_echo_arr = array();
            $fatal = false;
            $r = '';
            $now_wp_ver = self::lenslider_get_wp_version();
            if(version_compare(floatval(phpversion()), 5.2, '<')) {
                $arr['phpver'] = floatval(phpversion());
                $ret = false;
                $fatal = true;
            }
            if(!extension_loaded('gd') && !function_exists('gd_info')) {
                $arr['gd'] = true;
                $ret = false;
                $fatal = true;
            }
            if(!class_exists('ZipArchive')) {
                $arr['ziparc'] = true;
                $ret = false;
            }
            if(!function_exists('glob')) {
                $arr['glob'] = true;
                $ret = false;
                $fatal = true;
            }
            if($now_wp_ver < 3.5) {
                if($now_wp_ver < 3.3) {
                    $arr['wpver33'] = $now_wp_ver;
                    $ret = false;
                    $fatal = true;
                } else {
                    $arr['wpver'] = $now_wp_ver;
                    $ret = false;
                }
            }
            if(!$ret) {
                $ret_echo .= "<strong>";
                $ret_echo .= ($fatal)?__("LenSlider can't works fine with your PHP settings", 'lenslider'):__("Some <u>not fatal</u> errors found", 'lenslider');
                $ret_echo .= ":</strong><br />";
                if($arr['phpver'])  $ret_echo_arr[] = sprintf(__("Your php version is <strong>%s</strong>. You need <strong>PHP 5.2+ version</strong> for stable plugin work, so you need to install/update the one for stable work.", 'lenslider'), $arr['phpver']);
                if($arr['gd'])      $ret_echo_arr[] = __("PHP GD library is not installed on your web server! You need to install it.", 'lenslider');
                if($arr['ziparc'])  $ret_echo_arr[] = __("ZipClass class not exists. So you can't upload skins zip-archives. You need update your PHP version to 5.2+ version or add ZipArchive class manually.", 'lenslider');
                if($arr['glob'])    $ret_echo_arr[] = __("PHP function <a href=\"http://php.net/manual/en/function.glob.php\" target=\"_blank\">glob()</a> not exists. It's bad: you'll not has access for skins against <strong>default</strong>. There are much reasons for it, google the problem.", 'lenslider');
                if($arr['wpver'])   $ret_echo_arr[] = sprintf(__("Your WordPress version is <strong>%s</strong>. Recommended is <strong>WordPress 3.5</strong> version for right output sliders, but it's <strong>not fatal</strong> if all works fine for you. <strong>LenSlider2</strong> has 3.5 less compatibility, but it's <strong>no guarantee</strong> for sliders output fine work or some js-scripts based on old jQuery UI < 1.9 fine work.", 'lenslider'), $now_wp_ver);
                if($arr['wpver33']) $ret_echo_arr[] = sprintf(__("Your WordPress version is <strong>%s</strong>, it's even less than <strong>WordpPess 3.3</strong>. Recommended is <strong>WordPress 3.5</strong> or <strong>3.3+</strong> version for right output sliders, your WordPress version is too old and <strong>this is fatal</strong>, so it's <strong>no guarantee</strong> for sliders output fine work or some js-scripts based on old jQuery UI < 1.9 fine work.", 'lenslider'), $now_wp_ver);
                if(!empty($ret_echo) && !empty($ret_echo_arr) && is_array($ret_echo_arr)) {
                    $r = $ret_echo."<ul>";
                    foreach ($ret_echo_arr as $s) {
                        $r .= "<li>{$s}</li>";
                    }
                    $r .= "</ul>";
                }
            } else $r = __("LenSlider tested your PHP/WordPress settings, <strong>seems all fine</strong>. <strong>Have a nice day! Enjoy the plugin.</strong> <em>Also, you can spend just a few seconds to click &laquo;<strong>Works</strong>&raquo; (<a href=\"http://wordpress.org/extend/plugins/len-slider/\" target=\"_blank\">WordPress.org LenSlider plugin page</a> on right side) if you are satisfied with the plugin.</em>", 'lenslider');
            $class = ($fatal)?"ls_error":"updated ls_fine";
            return "<div id=\"ls_sys_message\" class=\"{$class}\"><table border=\"0\" width=\"100%\"><tr><td style=\"padding-right:20px\"><p>{$r}</p></td><td><p><a class=\"button ls_del_sys_umeta\" href=\"javascript:;\">".__("Thanks, I know, don't bug me", 'lenslider')."</a></td></tr></table></div>";
        }
    }
    /*---------------/CHECK METHODS---------------*/
    
    
    /*---------------GETTER METHODS---------------*/
    public function lenslider_get_sliders_array() {
        return $this->_sliders_array;
    }

    public static function lenslider_get_array_from_wp_options($option_name) {
        if(self::_lenslider_is_allowed_option($option_name)) return self::_lenslider_get_option($option_name);
    }
    
    public static function lenslider_get_wp_version() {
        global $wp_version;
        return floatval($wp_version);
    }
    
    public static function lenslider_get_default_settings() {
        return array(
            self::$nowVersionName   => self::$version,
            self::$slidersLimitName => self::$slidersLimitDefault,
            self::$bannersLimitName => self::$bannersLimitDefault,
            self::$maxSizeName      => 6,
            self::$bannerWidthName  => 500,
            self::$bannerHeightName => 200,
            self::$backlink         => 0,
            self::$skinName         => self::$defaultSkin,
            self::$hasThumb         => 0,
            self::$thumbMaxWidth    => 50,
            self::$sliderDisenName  => 1,
            self::$cacheName        => 0,
            self::$sliderRandom     => 0,
            self::$marginTop        => 0,
            self::$marginRight      => 0,
            self::$marginBottom     => 0,
            self::$marginLeft       => 0,
            );
    }

    public static function lenslider_get_slider_settings($slidernum, $ret_array = false) {
        $ret_array = (!$ret_array)?self::lenslider_get_array_from_wp_options(self::$bannersOption):$ret_array;
        return (!empty($ret_array[$slidernum][self::$settingsTitle]))?$ret_array[$slidernum][self::$settingsTitle]:self::lenslider_get_default_settings();
    }
    
    public static function lenslider_get_skin_settings($slidernum, $skin_name = false, $needle_slider_settings = true) {
        $slider_settings = ($needle_slider_settings)?self::lenslider_get_slider_settings($slidernum):array();
        if(!$skin_name) $skin_name = self::_lenslider_get_slider_skin_name($slidernum);
        $merge_array = array();
        if($skin_name != self::$defaultSkin) {
            $skinObj = self::lenslider_get_slider_skin_object($skin_name);
            $merge_array = ($skinObj)?$skinObj->sliderMergeSettingsArray:array();
            $unset_array = ($skinObj)?$skinObj->sliderUnsetSettingsArray:array();
        } else $merge_array = self::_lenslider_get_default_skin_settings();
        if(!empty($unset_array) && is_array($unset_array)) {
            foreach ($unset_array as $to_unset_key) {
                unset($merge_array[$to_unset_key]);
            }
        }
        if(!empty($merge_array) && is_array($merge_array)) {
            foreach ($merge_array as $k=>$arr) {
                if(array_key_exists('value', $arr)) $slider_settings[$k] = $arr['value'];
            }
        }
        return $slider_settings;
    }

    public static function lenslider_get_slider_banners($slidernum, $array_values = true) {
        $ret_array = array();
        $sliders_array = self::lenslider_get_array_from_wp_options(self::$bannersOption);
        if(!empty($sliders_array[$slidernum]) && is_array($sliders_array[$slidernum])) {
            $settings_array = $sliders_array[$slidernum][self::$settingsTitle];
            unset($sliders_array[$slidernum][self::$settingsTitle]);
            $i=0;
            $enabled_count = 0;
            foreach ($sliders_array[$slidernum] as $k=>$banner_v) {
                if(array_key_exists($i, $settings_array[self::$bannerDisenName])) {
                    if($array_values) $ret_array[$i] = $banner_v;
                    else $ret_array[$k] = $banner_v;
                    $enabled_count++;
                    if($settings_array[self::$bannersLimitName] == $enabled_count) break;
                }
                $i++;
            }
        }
        return $ret_array;
    }
    
    public static function lenslider_get_slider_skin_object($skin_name, $require = true) {
        if($skin_name != self::$defaultSkin) {
            $require_path = LenSliderSkins::_lenslider_skins_abspath()."/{$skin_name}/lib/{$skin_name}.skin.class.php";
            if(!file_exists($require_path)) $require_path = LenSliderSkins::_lenslider_skins_custom_abspath()."/{$skin_name}/lib/{$skin_name}.skin.class.php";
            if(file_exists($require_path)) {
                if($require) require_once($require_path);
                $class = ucfirst($skin_name)."LenSliderSkin";
                if(class_exists($class)) return new $class;
                return false;
            }
            return false;
        }
        return false;
    }
    
    public function lenslider_get_sliders_skins_names() {
        if(!empty($this->_sliders_array) && is_array($this->_sliders_array)) {
            $ret_array = array();
            foreach (array_keys($this->_sliders_array) as $slidernum) {
                $slider_settings = $this->_sliders_array[$slidernum][self::$settingsTitle];
                $ret_array[$slidernum] = $slider_settings[self::$skinName];
            }
            return array_unique($ret_array);
        }
        return false;
    }
    
    public function lenslider_get_used_skins() {
        if(is_array($this->_sliders_array)) {
            $arr = array();
            foreach (array_keys($this->_sliders_array) as $key) {
                $arr[] = $this->_sliders_array[$key][self::$settingsTitle][self::$skinName];
            }
            return $arr;
        }
    }
    
    protected function _lenslider_get_enabled_sliders_data() {
        $ret_array = array();
        $tmp_set = array();
        if(!empty($this->_enabledSliders) && is_array($this->_enabledSliders)) {
            foreach ($this->_enabledSliders as $slidernum) {
                $ret_array['settings'][$slidernum] = $tmp_set = array_merge($tmp_set, $this->_sliders_array[$slidernum][self::$settingsTitle]);//die(var_dump($this->_sliders_array[$slidernum][self::$settingsTitle]));
                $ret_array['skins'][] = $tmp_set[self::$skinName];
            }
        }
        return $ret_array;
    }
    
    public function lenslider_get_slider_banner_fields($slidernum) {
        $skin_name = $this->_lenslider_get_slider_skin_name($slidernum);
        $skinObj = self::lenslider_get_slider_skin_object($skin_name);
        return ($skinObj)?$this->_lenslider_make_fields_array($this->_lenslider_make_default_fields_array(), $skinObj->bannerUnsetArray, $skinObj->bannerMergeArray):false;
    }
    
    protected static function _lenslider_get_option($option_name) {
        if(self::_lenslider_is_allowed_option($option_name)) return @get_option($option_name);
    }
    
    public static function lenslider_get_slidernums_list() {
        $ret_array = array();
        $sliders_array = self::lenslider_get_array_from_wp_options(self::$bannersOption);
        if(!empty($sliders_array) && is_array($sliders_array)) {
            $i=0;
            foreach (array_keys($sliders_array) as $slidernum) {
                $slider_settings = $sliders_array[$slidernum][self::$settingsTitle];
                $ret_array[$i]['slidernum'] = $slidernum;
                $ret_array[$i]['title'] = $slidernum;
                if(!empty($slider_settings[self::$sliderComment])) $ret_array[$i]['title'] .= " ({$slider_settings[self::$sliderComment]})";
                $i++;
            }
        }
        return $ret_array;
    }

    protected static function _lenslider_get_enabled_sliders_array_slidernums() {
        $sliders_array = self::lenslider_get_array_from_wp_options(self::$bannersOption);
        if(!empty($sliders_array) && is_array($sliders_array)) {
            $ret_array = array();
            foreach (array_keys($sliders_array) as $slidernum) {
                $slider_settings = $sliders_array[$slidernum][self::$settingsTitle];
                if($slider_settings[self::$sliderDisenName] == 1) $ret_array[] = $slidernum;
            }
            return $ret_array;
        }
        return false;
    }
    
    protected static function _lenslider_get_slider_skin_name($slidernum) {
        $slider_settings_array = self::lenslider_get_slider_settings($slidernum);
        return $slider_settings_array[self::$skinName];
    }

    protected static function _lenslider_get_default_skin_settings() {
        return array(
            self::$bannerWidthName => array('value' => self::$defaultSkinWidth),
            self::$hasThumb => array('value' => 'off')
        );
    }
    /*--------------/GETTER METHODS------------------*/
    
    
    /*---------------SETTER METHODS---------------*/
    public function lenslider_disen_bulk_actions($slidernums_array, $action) {
        if(wp_verify_nonce($_REQUEST['_wpnonce'])) {
            switch ($action) {
                case 'disable':
                    foreach ($slidernums_array as $slidernum) {
                        if(self::lenslider_is_slider_exists($slidernum)) {
                            $this->_sliders_array[$slidernum][self::$settingsTitle][self::$sliderDisenName] = 0;
                        }
                    }
                    $this->_lenslider_update_option_sliders_array($this->_sliders_array);
                    break;
                case 'enable':
                    foreach ($slidernums_array as $slidernum) {
                        if(self::lenslider_is_slider_exists($slidernum)) {
                            $this->_sliders_array[$slidernum][self::$settingsTitle][self::$sliderDisenName] = 1;
                        }
                    }
                    $this->_lenslider_update_option_sliders_array($this->_sliders_array);
                    break;
            }
        }
        return true;
    }

    protected function _lenslider_update_option($option_name, $option_value) {
        if(self::_lenslider_is_allowed_option($option_name)) return @update_option($option_name, $option_value);
        return false;
    }
    
    protected function _lenslider_set_sliders_banners_active() {
        if(!empty($this->_sliders_array) && is_array($this->_sliders_array)) {
            foreach ($this->_sliders_array as $slidernum => $array) {
                $tmp_arr = array();
                for($i=0;$i<(count($array)-2);$i++) {
                    $tmp_arr[$i] = 1;
                }
                $this->_sliders_array[$slidernum][self::$settingsTitle][self::$bannerDisenName] = $tmp_arr;
            }
            update_option(self::$bannersOption, $this->_sliders_array);
        }
    }

    protected function _lenslider_update_option_sliders_array($sliders_array) {
        $ins = (!empty($sliders_array))?$sliders_array:'';
        return ($this->_lenslider_update_option(self::$bannersOption, $ins))?true:false;
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
    
    protected function _lenslider_insert_attachment($attachment, $foto_abs_path) {
        return wp_insert_attachment($attachment, $foto_abs_path);
    }
    
    public function lenslider_simple_insert_attachment($file_path, $slidernum = false, $id = false, $attachment = false) {
        $ret_array = array();
        $http_path = (!$id)?plugins_url('images/', $this->indexFile).$file_path:$http_path = $this->lenslider_get_attachment_params($id)->httpPath;
        $file_path = ABSPATH.str_ireplace(self::$siteurl."/", '', $http_path);
        $ext       = pathinfo($file_path, PATHINFO_EXTENSION);
        $file_info = getimagesize($file_path);
        $mime      = $file_info['mime'];
        $upload_dir_array = wp_upload_dir();
        if(!file_exists($upload_dir_array['path'])) mkdir($upload_dir_array['path'], 0777);
        $newpath = $upload_dir_array['path'].'/'.strtolower(sanitize_title(self::lenslider_hash()).'.'.$ext);
        @copy($http_path, $newpath);
        @chmod($upload_dir_array['path'], 0755);
        
        if(!$attachment) {
            $attachment = array(
                'post_title'     => ($slidernum)?sprintf(__('Inserted attachment for LenSlider %s banner. Dont delete manually', 'lenslider'), $slidernum):__('Inserted attachment for LenSlider. Dont delete manually', 'lenslider'),
                'post_content'   => __('Inserted attachment for LenSlider. Dont delete manually', 'lenslider'),
                'post_type'      => 'attachment',
                'post_mime_type' => $mime
            );
            
        }
        $ret_array['id']   = intval($this->_lenslider_insert_attachment($attachment, $newpath));
        $ret_array['path'] = $this->_lenslider_update_attachment($ret_array['id'], $attachment);
        return $ret_array;
        
    }

    protected function _lenslider_update_attachment($id, $attachment = false, $newname = false, $only_update = false) {
        if($newname) {
            $obj = $this->lenslider_get_attachment_params($id);
            $oldname = strtolower(wp_basename($obj->absAttUrl));
            $newpath = str_ireplace($oldname, sanitize_title(strtolower($newname." ".self::lenslider_hash())).".".$obj->ext, $obj->absAttUrl);
            $attachment = array(
                'post_title'     => (!empty($newname))?sanitize_text_field($newname):"",
                'post_content'   => (!empty($newname))?sanitize_text_field($newname):"",
                'post_type'      => 'attachment',
                'post_mime_type' => $obj->mime
            );
            rename($obj->absAttUrl, $newpath);
            update_attached_file($id, $newpath);
        }
        if($attachment) wp_update_attachment_metadata($id, $attachment);
        if(!$only_update) return $this->lenslider_get_attachment_params($id)->httpPath;
    }
    
    /*protected function _lenslider_update_attachment_urltitle($id, $newtitle, $uid) {
        $obj = $this->lenslider_get_attachment_params($id);
        $newname = sanitize_title(strtolower($newtitle." ".$uid)).".".$obj->ext;
        $oldname = strtolower(wp_basename($obj->absAttUrl));
        $newpath = str_ireplace($oldname, $newname, $obj->absAttUrl);
    }*/
    /*---------------/SETTER METHODS---------------*/
    
    
    /*---------------DELETE METHODS---------------*/
    public function lenslider_delete_banner($banner_id, $slidernum = false, $delete_thumb = true, $thumb_id = false, $delete_only_thumb = false, $sliders_array = false, $update_option = true, $delete_slider = false) {
        if(!$sliders_array) $sliders_array = $this->_sliders_array;
        if($slidernum) {
            if($delete_thumb && !empty($thumb_id)) {
                if(@wp_delete_attachment($thumb_id) && !$delete_slider) {
                    if(!empty($sliders_array[$slidernum][$banner_id]['path_thumb'])) unset($sliders_array[$slidernum][$banner_id]['path_thumb']);
                    if(!empty($sliders_array[$slidernum][$banner_id]['size_thumb'])) unset($sliders_array[$slidernum][$banner_id]['size_thumb']);
                    if(!empty($sliders_array[$slidernum][$banner_id]['thumb_id']))   unset($sliders_array[$slidernum][$banner_id]['thumb_id']);
                }
            }
            if(!$delete_only_thumb) {
                if($banner_id) @wp_delete_attachment($banner_id);
                if(!$delete_slider) {
                    if(!empty($banner_id) && array_key_exists($banner_id, $sliders_array[$slidernum])) unset($sliders_array[$slidernum][$banner_id]);
                    if(!empty($sliders_array[$slidernum][self::$settingsTitle]) && count($sliders_array[$slidernum]) == 1 && array_key_exists($slidernum, $sliders_array)) unset($sliders_array[$slidernum]);
                }
            }
        }
        return ($update_option)?$this->_lenslider_update_option_sliders_array($sliders_array):true;
    }
    
    public function lenslider_check_delete_attachment($id) {
        if(!empty($this->_sliders_array) && is_array($this->_sliders_array)) {
            foreach ($this->_sliders_array as $slidernum => $arr) {
                if(array_key_exists($id, $arr) && !empty($arr[$id])) $this->lenslider_delete_banner($id, $slidernum);
            }
        }
    }

    public function lenslider_delete_slider($slidernum, $sliders_array = false, $redirect_url = false) {
        if(!$sliders_array) $sliders_array = $this->_sliders_array;
        if(!empty($sliders_array[$slidernum]) && is_array($sliders_array[$slidernum])) {
            foreach($sliders_array[$slidernum] as $k=>$v) {
                if($this->lenslider_delete_banner($k, $slidernum, true, $v['thumb_id'], false, $sliders_array, false, true)) continue;
            }
            unset($sliders_array[$slidernum]);
            $this->_lenslider_update_option_sliders_array($sliders_array);
        }
        if($redirect_url) {
            unset($_FILES);
            unset($_POST);
            wp_safe_redirect($redirect_url);
        }
    }
    
    protected static function _lenslider_delete_dir($dir) {
        if(!file_exists($dir)) return true;
        if(!is_dir($dir) || is_link($dir)) return unlink($dir);
        foreach (scandir($dir) as $item) {
            if($item == '.' || $item == '..') continue;
            if(!self::_lenslider_delete_dir($dir . "/" . $item)) {
                chmod($dir . "/" . $item, 0777);
                if(!self::_lenslider_delete_dir($dir . "/" . $item)) return false;
            }
        }
        return rmdir($dir);
    }
    
    protected static function _lenslider_static_delete_sliders($slidernum, $sliders_array = false) {
        if(!$sliders_array) $sliders_array = self::lenslider_get_array_from_wp_options(self::$bannersOption);
        if(!empty($sliders_array[$slidernum]) && is_array($sliders_array[$slidernum])) {
            foreach (array_keys($sliders_array[$slidernum]) as $banner_k) {
                @wp_delete_attachment($banner_k);
                if(!empty($sliders_array[$slidernum][$banner_k]['thumb_id'])) @wp_delete_attachment($sliders_array[$slidernum][$banner_k]['thumb_id']);
            }
        }
        return true;
    }
    /*---------------/DELETE METHODS---------------*/
    
    
    /*---------------AJAX METHODS---------------*/
    protected static function _lenslider_is_ajax() {
        if($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') die('-1');
    }

    public function lenslider_welcome_panel() {
        self::_lenslider_is_ajax();
        check_ajax_referer('ls-welcome-panel-nonce', 'ls_welcomepanelnonce');
        @delete_user_meta(get_current_user_id(), self::$ls_welcome_umeta);
        wp_die(1);
    }
    
    public function lenslider_ajax_add_banner() {
        self::_lenslider_is_ajax();
        check_ajax_referer($this->plugin_basename.LOGGED_IN_KEY.self::$siteurl, 'sec');
        $ret_array = array();
        $n                          = intval($_POST['count_banners']);
        $slidernum                  = $_POST['slidernum'];
        $slider_settings            = self::lenslider_get_slider_settings($slidernum);
        $count_enabled              = intval($_POST['count_enabled']);
        $ret_array['banners_limit'] = (!empty($slider_settings[self::$bannersLimitName]))?$slider_settings[self::$bannersLimitName]:$this->bannersLimit;
        $slider_settings[self::$skinName] = sanitize_text_field($_POST['skin_name']);
        $skinObj = ($slider_settings[self::$skinName] != self::$defaultSkin)?self::lenslider_get_slider_skin_object($slider_settings[self::$skinName]):false;
        $array_merge                = ($skinObj)?$skinObj->bannerMergeArray:false;
        $array_unset                = ($skinObj)?$skinObj->bannerUnsetArray:false;
        $ret_array['banner_item']   = $this->lenslider_banner_item($n, $slidernum, $ret_array['banners_limit'], $count_enabled, $array_merge, $array_unset, $slider_settings['ls_has_thumb']);
        die(json_encode($ret_array));
    }
    
    public function lenslider_ajax_links_variants() {
        self::_lenslider_is_ajax();
        check_ajax_referer($this->plugin_basename.LOGGED_IN_KEY.self::$siteurl, 'sec');
        $ret_array = array();
        $to_list   = $_POST['to_list'];
        $slidernum = $_POST['slidernum'];
        $name      = $_POST['name'];
        $n         = $_POST['n'];
        $banner_k  = $_POST['banner_k'];
        switch ($to_list) {
            case 'blink_post':
                $ret_array['ret'] = self::lenslider_dropdown_posts($slidernum, $banner_k, $n, "blink");
                $ret_array['uth'] = self::lenslider_banner_hidden($slidernum, 'url_type', 'post');
                break;
            case 'blink_page':
                $ret_array['ret'] = self::lenslider_dropdown_pages($slidernum, $banner_k, $n, "blink");
                $ret_array['uth'] = self::lenslider_banner_hidden($slidernum, 'url_type', 'page');
                break;
            case 'blink_cat':
                $ret_array['ret'] = self::lenslider_dropdown_categories($slidernum, $banner_k, $n, "blink");
                $ret_array['uth'] = self::lenslider_banner_hidden($slidernum, 'url_type', 'cat');
                break;
            default :
                $ret_array['ret'] = '';
                $ret_array['uth'] = self::lenslider_banner_hidden($slidernum, 'url_type', 'lsurl');
        }
        die(json_encode($ret_array));
    }
    
    public function lenslider_ajax_link_variants_url() {
        self::_lenslider_is_ajax();
        check_ajax_referer($this->plugin_basename.LOGGED_IN_KEY.self::$siteurl, 'sec');
        $ret_array = array();
        $slidernum = $_POST['slidernum'];
        $banner_k  = $_POST['banner_k'];
        $id        = intval($_POST['id']);
        $n         = $_POST['n'];
        $url_type  = $_POST['url_type'];
        switch ($url_type) {
            case 'page':
            case 'post':
                $ret_array['url'] = get_permalink($id);
                $ret_array['uti'] = self::lenslider_banner_hidden($slidernum, 'url_type_id', $id);
                $ret_array['ret'] = self::lenslider_banner_hidden($slidernum, 'ls_link', $ret_array['url']);
                break;
            case 'cat':
                $ret_array['url'] = get_category_link($id);
                $ret_array['uti'] = self::lenslider_banner_hidden($slidernum, 'url_type_id', $id);
                $ret_array['ret'] = self::lenslider_banner_hidden($slidernum, 'ls_link', $ret_array['url']);
                break;
        }
        die(json_encode($ret_array));
    }
    
    public function lenslider_ajax_titles_variants() {
        self::_lenslider_is_ajax();
        check_ajax_referer($this->plugin_basename.LOGGED_IN_KEY.self::$siteurl, 'sec');
        $ret_array = array();
        $to_list   = $_POST['to_list'];
        $slidernum = $_POST['slidernum'];
        $name      = $_POST['name'];
        $n         = $_POST['n'];
        $banner_k  = $_POST['banner_k'];
        switch ($to_list) {
            case 'btitle_post':
                $ret_array['ret'] = self::lenslider_dropdown_posts($slidernum, $banner_k, $n, "btitle");
                $ret_array['uth'] = self::lenslider_banner_hidden($slidernum, 'title_type', 'post');
                break;
            case 'btitle_page':
                $ret_array['ret'] = self::lenslider_dropdown_pages($slidernum, $banner_k, $n, "btitle");
                $ret_array['uth'] = self::lenslider_banner_hidden($slidernum, 'title_type', 'page');
                break;
            case 'btitle_cat':
                $ret_array['ret'] = self::lenslider_dropdown_categories($slidernum, $banner_k, $n, "btitle");
                $ret_array['uth'] = self::lenslider_banner_hidden($slidernum, 'title_type', 'cat');
                break;
            default :
                $ret_array['ret'] = '';
                $ret_array['uth'] = self::lenslider_banner_hidden($slidernum, 'title_type', 'lstitle');
        }
        die(json_encode($ret_array));
    }
    
    public function lenslider_ajax_titles_variants_title() {
        self::_lenslider_is_ajax();
        check_ajax_referer($this->plugin_basename.LOGGED_IN_KEY.self::$siteurl, 'sec');
        $ret_array = array();
        $slidernum  = trim(stripcslashes($_POST['slidernum']));
        $banner_k   = trim(stripcslashes($_POST['banner_k']));
        $id         = intval($_POST['id']);
        $n          = trim(stripcslashes($_POST['n']));
        $title_type = trim(stripcslashes($_POST['title_type']));
        switch ($title_type) {
            case 'page':
            case 'post':
                $ret_array['title'] = get_the_title($id);
                $ret_array['uti'] = self::lenslider_banner_hidden($slidernum, 'title_type_id', $id);
                $ret_array['ret'] = self::lenslider_banner_hidden($slidernum, 'ls_title', $ret_array['title']);
                break;
            case 'cat':
                $ret_array['title'] = get_cat_name($id);
                $ret_array['uti'] = self::lenslider_banner_hidden($slidernum, 'title_type_id', $id);
                $ret_array['ret'] = self::lenslider_banner_hidden($slidernum, 'ls_title', $ret_array['title']);
                break;
        }
        die(json_encode($ret_array));
    }
    
    public function lenslider_ajax_new_media() {
        self::_lenslider_is_ajax();
        check_ajax_referer($this->plugin_basename.LOGGED_IN_KEY.self::$siteurl, 'sec');
        $ret_array = array();
        $type       = trim(stripcslashes($_POST['type']));
        if($type) {
            switch ($type) {
                case 'image':
                    $id         = intval($_POST['added_id']);
                    $slidernum  = trim(stripcslashes($_POST['slidernum']));
                    $width      = intval($_POST['width']);
                    $height     = intval($_POST['height']);
                    $title      = (!empty($_POST['title']))?$_POST['title']:"";
                    $prior      = trim(stripcslashes($_POST['prior']));
                    $exist_id   = intval($_POST['exist_id']);
                    $ret_array['res'] = false;
                    $maked_attachment  = $this->lenslider_resize_image_library($id, $title, $width, $height, $prior, $exist_id, $slidernum);
                    $ret_array['id']   = $maked_attachment['id'];
                    $ret_array['path'] = $maked_attachment['path'];
                    $ret_array['code'] = "<code style=\"display:block\">".str_ireplace(self::$siteurl, '', $ret_array['path'])."</code>";
                    $ret_array['img']  = "<a href=\"{$ret_array['path']}\" class=\"thickbox\"><img src=\"{$ret_array['path']}\" height=\"164\" /></a>";
                    if(!empty($ret_array['id']) && !empty($ret_array['path']) && !empty($ret_array['img']) && !empty($ret_array['code'])) $ret_array['res'] = true;
                    break;
                case 'youtube':
                    $url       = trim(stripcslashes($_POST['url']));
                    $slidernum = trim(stripcslashes($_POST['slidernum']));
                    $id        = self::lenslider_get_youtube_id($url);
                    $ret_array['res']      = false;
                    if($id) {
                        $obj                 = $this->lenslider_simple_insert_attachment('youtube-lenslider.jpg');
                        $ret_array['id']     = $obj['id'];
                        $ret_array['code']   = self::lenslider_youtube_obj_output($id, 276);
                        $ret_array['yt_url'] = "http://www.youtube.com/watch?v={$id}";
                        $ret_array['res']    = true;
                    }
                    break;
                case 'vimeo':
                    $url       = trim(stripcslashes($_POST['url']));
                    $slidernum = trim(stripcslashes($_POST['slidernum']));
                    $id        = self::lenslider_get_vimeo_id($url);
                    $ret_array['res']      = false;
                    if($id) {
                        $obj               = $this->lenslider_simple_insert_attachment('vimeo-lenslider.jpg');
                        $ret_array['id']   = $obj['id'];
                        $ret_array['code'] = self::lenslider_vimeo_obj_output($id, 276);
                        $ret_array['res']  = true;
                    }
                    break;
                case 'text':
                    $slidernum             = trim(stripcslashes($_POST['slidernum']));
                    $ret_array['res']      = false;
                    if($_POST['insert']) {
                        $obj               = $this->lenslider_simple_insert_attachment('text-lenslider.jpg');
                        $ret_array['id']   = $obj['id'];
                        $ret_array['res']  = true;
                    } else {
                        $id        = intval($_POST['exist_id']);
                        $slidernum = trim(stripcslashes($_POST['slidernum']));
                        $ret_array['res'] = $this->lenslider_delete_banner($id, $slidernum);
                    }
                    break;
            }
        }
        die(json_encode($ret_array));
    }
    
    public function lenslider_ajax_delete_banner() {
        self::_lenslider_is_ajax();
        check_ajax_referer($this->plugin_basename.LOGGED_IN_KEY.self::$siteurl, 'sec');
        $ret_array = array();
        $id               = intval($_POST['att_id']);
        $thumb_id         = intval($_POST['thumb_id']);
        $slidernum        = trim(stripcslashes($_POST['slidernum']));
        $ret_array['res'] = $this->lenslider_delete_banner($id, $slidernum, true, $thumb_id);
        die(json_encode($ret_array));
    }
    
    public function lenslider_ajax_delete_attachment() {
        self::_lenslider_is_ajax();
        check_ajax_referer($this->plugin_basename.LOGGED_IN_KEY.self::$siteurl, 'sec');
        $ret_array = array();
        $id               = intval($_POST['att_id']);
        $slidernum        = trim(stripcslashes($_POST['slidernum']));
        $ret_array['res'] = $this->lenslider_delete_banner($id, $slidernum);
    }

    public function lenslider_ajax_delete_thumb() {
        self::_lenslider_is_ajax();
        check_ajax_referer($this->plugin_basename.LOGGED_IN_KEY.self::$siteurl, 'sec');
        $ret_array        = array();
        $id               = intval($_POST['att_id']);
        $thumb_id         = intval($_POST['thumb_id']);
        $slidernum        = trim(stripcslashes($_POST['slidernum']));
        $ret_array['res'] = $this->lenslider_delete_banner($id, $slidernum, true, $thumb_id, true);
        die(json_encode($ret_array));
    }

    public function lenslider_ajax_del_sys_umeta() {
        self::_lenslider_is_ajax();
        check_ajax_referer($this->plugin_basename.LOGGED_IN_KEY.self::$siteurl, 'sec');
        $ret_array = array();
        @delete_user_meta(intval($_POST['user_id']), self::$ls_sys_umeta);
        $ret_array['res'] = true;
        die(json_encode($ret_array));
    }
    
    public function lenslider_ajax_get_settings_skin() {
        self::_lenslider_is_ajax();
        check_ajax_referer($this->plugin_basename.LOGGED_IN_KEY.self::$siteurl, 'sec');
        $ret_array        = array();
        $ret_array['res'] = false;
        $slidernum        = trim(stripcslashes($_POST['slidernum']));
        $ret_array['arr'] = self::lenslider_get_skin_settings($slidernum);
        $ret_array['res'] = true;
        die(json_encode($ret_array));
    }

    public function lenslider_ajax_new_thumb() {
        self::_lenslider_is_ajax();
        check_ajax_referer($this->plugin_basename.LOGGED_IN_KEY.self::$siteurl, 'sec');
        $ret_array = array();
        $id        = intval($_POST['added_id']);
        $slidernum = trim(stripcslashes($_POST['slidernum']));
        $width     = intval($_POST['width']);
        $ret_array['res']  = false;
        $maked_attachment  = $this->lenslider_resize_image_library($id, false, $width, false, 'width', 0, $slidernum);
        $ret_array['id']   = $maked_attachment['id'];
        $ret_array['path'] = $maked_attachment['path'];
        $ret_array['img']  = "<a href=\"{$ret_array['path']}\" class=\"thickbox\"><img src=\"{$ret_array['path']}\" height=\"94\" /></a>";
        if(!empty($ret_array['id']) && !empty($ret_array['path']) && !empty($ret_array['img'])) $ret_array['res'] = true;
        die(json_encode($ret_array));
    }
    
    public function lenslider_ajax_delete_skin() {
        self::_lenslider_is_ajax();
        check_ajax_referer($this->plugin_basename.LOGGED_IN_KEY.self::$siteurl, 'sec');
        $ret_array        = array();
        $skin_name        = trim(stripcslashes($_POST['skin']));
        $LenSliderSkins   = new LenSliderSkins;
        $ret_array['res'] = ($LenSliderSkins->lenslider_delete_skin($skin_name))?true:false;
        die(json_encode($ret_array));
    }

    /*---------------/AJAX METHODS---------------*/
    
    /*---------------PROCESSING METHODS---------------*/
    protected function _lenslider_copy_image_abspath($file, $keytag, $uid) {
        $tmp_file = $file;
        $abspath = ABSPATH.str_ireplace(self::$siteurl."/", '', $tmp_file);
        $img_info = @getimagesize($abspath);
        $file = array(
            'name' => $tmp_file,
            'size' => filesize($abspath),
            'type' => $img_info['mime']
        );
        
        if(stristr(strtolower($file['name']),    '.gif'))                                               $format = 'gif';
        elseif(stristr(strtolower($file['name']),'.jpg') || stristr(strtolower($file['name']),'.jpeg')) $format = 'jpg';
        elseif(stristr(strtolower($file['name']),'.png'))                                               $format = 'png';
        
        $upload_dir_array = wp_upload_dir();
        if(!file_exists($upload_dir_array['path'])) mkdir($upload_dir_array['path'], 0777);
        $newpath = $upload_dir_array['path'].'/'.strtolower(sanitize_title($keytag." ".$uid).'.'.$format);
        @copy($tmp_file, $newpath);
        return array(
            'path' => $newpath,
            'type' => $file['type']
        );
    }
    
    protected function _lenslider_resize_image($format, $newpath, $from_width, $from_height, $to_width, $to_height, $quality, $dst_x = 0, $dst_y = 0, $src_x = 0, $src_y = 0) {
        $src_x = (!empty($src_x))?$src_x:$to_width;
        $src_y = (!empty($src_y))?$src_y:$to_height;
        $resImage = imagecreatetruecolor($to_width, $to_height);
        switch ($format) {
            case 'gif':
                $oldimage = imagecreatefromgif($newpath);
                imagealphablending($resImage, false);
                imagesavealpha($resImage, true);
                $transparent = imagecolorallocatealpha($resImage, 255, 255, 255, 127);
                imagefilledrectangle($resImage, 0, 0, $src_x, $src_y, $transparent);
                imagecopyresampled($resImage, $oldimage, 0, 0, 0, 0, $to_width, $to_height, $from_width, $from_height);
                imagegif($resImage, $newpath);
                break;
            case 'jpg':
                $oldimage = imagecreatefromjpeg($newpath);
                imagecopyresampled($resImage, $oldimage, 0, 0, 0, 0, $to_width, $to_height, $from_width, $from_height);
                imagejpeg($resImage, $newpath, $quality);
                break;
            case 'png':
                $oldimage = imagecreatefrompng($newpath);
                imagealphablending($resImage, false);
                imagesavealpha($resImage, true);
                $transparent = imagecolorallocatealpha($resImage, 255, 255, 255, 127);
                imagefilledrectangle($resImage, 0, 0, $src_x, $src_y, $transparent);
                imagecopyresampled($resImage, $oldimage, 0, 0, 0, 0, $to_width, $to_height, $from_width, $from_height);
                imagepng($resImage, $newpath, floatval(intval($quality)/10)-1);
                break;
        }
        @chmod($newpath, 0644);
        imagedestroy($resImage);
    }

    public function lenslider_resize_image_library($id, $needle_title = false, $needle_width = false, $needle_height = false, $prior = 'width', $exist_att_id = 0, $slidernum = false, $crop_for = false, $quality = 100) {
        $ret_array = array();
        if(!empty($exist_att_id) && $id != $exist_att_id) @wp_delete_attachment($exist_att_id);
        $obj = $this->lenslider_get_attachment_params($id);
        if(in_array($obj->mime, self::lenslider_allowed_images_mime_types())) {
            if(!empty($prior)) {
                $upload_dir_array = wp_upload_dir();
                if(!file_exists($upload_dir_array['path'])) mkdir($upload_dir_array['path'], 0777);
                $needle_title = (!empty($needle_title) && $this->lenslider_is_english_characters($needle_title))?$needle_title:self::lenslider_hash();
                $newpath = $upload_dir_array['path'].'/'.strtolower(sanitize_title($needle_title." ".self::lenslider_hash()).'.'.$obj->ext);
                @copy($obj->httpPath, $newpath);
                switch ($prior) {
                    case 'width':
                        if(!empty($needle_width) && $needle_width < $obj->width) {
                            $image_heigth_resize = intval(($needle_width * $obj->height) / $obj->width);
                            $this->_lenslider_resize_image($obj->ext, $newpath, $obj->width, $obj->height, $needle_width, $image_heigth_resize, $quality);
                        } else {
                            //todo
                        }
                        break;
                    case 'height':
                        if(!empty($needle_height) && $needle_height < $obj->height) {
                            $image_width_resize = intval(($needle_height * $obj->width) / $obj->height);
                            $this->_lenslider_resize_image($obj->ext, $newpath, $obj->width, $obj->height, $image_width_resize, $needle_height, $quality);
                        } else {
                            //todo
                        }
                        break;
                    /*case 'crop':
                        if((!empty($needle_height) && $needle_height < $obj->height) || (!empty($needle_width) && $needle_width < $obj->width)) {
                            $crop_vars = self::_lenslider_crop_vars();
                            if(!empty($crop_for) && in_array($crop_for, $crop_vars)) {
                                switch ($crop_for) {
                                    case 'left_top':
                                        $dst_x = 0;
                                        $dst_y = 0;
                                        $src_x = $needle_width;
                                        $src_y = $needle_height;
                                        break;
                                }
                            }
                        }
                        break;*/
                }
                $attachment = array(
                    'post_title'     => ($slidernum)?sprintf(__('Maked and using for LenSlider %s banner. Dont delete from media library.', 'lenslider'), $slidernum):__('Maked and using for LenSlider banner. Dont delete from media library.', 'lenslider'),
                    'post_content'   => (!empty($needle_title))?sanitize_text_field($needle_title):"",
                    'post_type'      => 'attachment',
                    'post_mime_type' => $obj->mime
                );
                $ret_array['id']   = intval($this->_lenslider_insert_attachment($attachment, $newpath));
                //$attachment2       = wp_generate_attachment_metadata($ret_array['id'], $newpath);
                $ret_array['path'] = $this->_lenslider_update_attachment($ret_array['id'], $attachment);
                $ret_array['size'] = $obj->size;
            } else {
                
            }
        }
        return $ret_array;
    }
    
    protected function _lenslider_resize_image_abspath($file, $maxwidth, $maxsize_mb, $keytag, $uid, $quality = 100) {
        $return_logs = array();
        $return_logs['errors'] = array();
        if(!is_array($file)) {
            $tmp_file = $file;
            $abspath = ABSPATH.str_ireplace(self::$siteurl."/", '', $tmp_file);
            $img_info = @getimagesize($abspath);
            if(empty($img_info) && !is_array($img_info)) $img_info = @getimagesize($tmp_file);
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
                
                if($format && in_array($uploaded_file_type, self::lenslider_allowed_images_mime_types())) {
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
                                imagealphablending($resImage, false);
                                imagesavealpha($resImage, true);
                                $transparent = imagecolorallocatealpha($resImage, 255, 255, 255, 127);
                                imagefilledrectangle($resImage, 0, 0, $maxwidth, $image_heigth_resize, $transparent);
                                imagecopyresampled($resImage, $oldimage, 0, 0, 0, 0, $maxwidth, $image_heigth_resize, $image_width, $image_height);
                                imagegif($resImage, $newpath);
                                break;
                            case 'jpg':
                                $oldimage = imagecreatefromjpeg($newpath);
                                imagecopyresampled($resImage, $oldimage, 0, 0, 0, 0, $maxwidth, $image_heigth_resize, $image_width, $image_height);
                                imagejpeg($resImage, $newpath, $quality);
                                break;
                            case 'png':
                                $oldimage = imagecreatefrompng($newpath);
                                imagealphablending($resImage, false);
                                imagesavealpha($resImage, true);
                                $transparent = imagecolorallocatealpha($resImage, 255, 255, 255, 127);
                                imagefilledrectangle($resImage, 0, 0, $maxwidth, $image_heigth_resize, $transparent);
                                imagecopyresampled($resImage, $oldimage, 0, 0, 0, 0, $maxwidth, $image_heigth_resize, $image_width, $image_height);
                                imagepng($resImage, $newpath, floatval(intval($quality)/10)-1);
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
                        $return_logs['path']   = $newpath;
                        $return_logs['size']   = $this->lenslider_size_str($image_width, $image_height);
                        $return_logs['width']  = $image_width;
                        $return_logs['height'] = $image_height;
                    }
                    $return_logs['type'] = $size_array['mime'];
                }
            }
        }
        
        return $return_logs;
    }

    protected static function _lenslider_join_slider_elements($slider_banners, $html_el, $slider_settings, $slidernum) {
        $array          = array();
        $slider_banners = self::_lenslider_escape_array($slider_banners);
        preg_match_all('#%(.+?)%#iu', $html_el, $array);
        if(!empty($array[1]) && is_array($array[1])) {
            $join_array = array();
            if($slider_settings[self::$sliderRandom] == 1) shuffle($slider_banners);
            foreach ($slider_banners as $k=>$slider_banner) {
                $slider_banner['slidernum']      = $slidernum;
                $slider_banner['banner_key']     = $k;
                $slider_banner['banner_key_inc'] = $k+1;
                $img = $slider_banner['path'];
                $slider_banner['path'] = "";
                if(!empty($slider_banner['ls_link'])) $slider_banner['path'] .= "<a href=\"{$slider_banner['ls_link']}\">";
                $slider_banner['path'] .= "<img src=\"{$img}\"";
                if(!empty($slider_banner['banneralt'])) $slider_banner['path'] .= " alt=\"{$slider_banner['banneralt']}\"";
                $slider_banner['path'] .= " />";
                if(!empty($slider_banner['ls_link'])) $slider_banner['path'] .= "</a>";
                if(!empty($slider_banner['bannertype'])) {
                    switch ($slider_banner['bannertype']) {
                        case 'text':
                            $slider_banner['path'] = "";
                            break;
                        case 'youtube':
                            $slider_banner['yt_link'] = $slider_banner['banneryoutube'];
                            $slider_banner['yt_id']   = self::lenslider_get_youtube_id($slider_banner['yt_link']);
                            $slider_banner['path']    = self::lenslider_youtube_obj_output($slider_banner['yt_id'], $slider_banner['bannerwidth_yt'], $slider_banner['bannerheight_yt']);
                            break;
                        case 'vimeo':
                            $slider_banner['vm_link'] = $slider_banner['bannervimeo'];
                            $slider_banner['vm_id']   = self::lenslider_get_vimeo_id($slider_banner['vm_link']);
                            $slider_banner['path']    = self::lenslider_vimeo_obj_output($slider_banner['vm_id'], $slider_banner['bannerwidth_vm'], $slider_banner['bannerheight_vm']);
                            break;
                    }
                }
                $slider_banner['path_thumb']     = ($slider_banner['path_thumb'])?$slider_banner['path_thumb']:false;
                $html_out                        = $html_el;
                /*preg_match_all("|{if}(.*?){/if}|i", $html_out, $m);
                if(!empty($m[1]) && is_array($m[1])) {
                    foreach ($m[1] as $line) {
                        if(preg_match('#%(.+?)%#iu', $line, $line_arr)) {
                            $html_out = (!empty($slider_banner[$line_arr[1]]))?str_ireplace("{if}{$line}{/if}", $line, $html_out):str_ireplace("{if}{$line}{/if}", '', $html_out);
                        }
                    }
                }*/
                //$array = array();
                //preg_match_all('#%(.+?)%#iu', $html_el, $array);
                for($i=0;$i<count($array[1]);$i++) $html_out = str_ireplace("%{$array[1][$i]}%", $slider_banner[$array[1][$i]], $html_out);
                $join_array[] = $html_out;
            }
            return join('', $join_array);
        }
        return false;
    }

    protected static function _lenslider_replace_file_text($slider_banners, $file_text, $el_prefix, $slider_settings, $slidernum) {
        $id_maybepos = stripos($file_text, "<!--slidernum-->");
        $id_start    = $id_maybepos+16;
        $id_end      = stripos($file_text, "<!--/slidernum-->");
        $id_substr   = substr($file_text, $id_start, $id_end-$id_start);
        $id_replace  = str_ireplace("%slidernum%", $slidernum, $id_substr);
        $file_text   = substr_replace($file_text, $id_replace, $id_start, strlen($id_substr));
        
        $start_plus  = 13+strlen($el_prefix);
        $maybepos    = stripos($file_text, "<!--{$el_prefix}_start-->");
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
    
    protected function _lenslider_settings_titled_array($settings_array, $array, $merge_array = false) {
        if($merge_array) $array = array_merge($array, $merge_array);
        $ret_array = array();
        if(empty($settings_array)) {
            $this->_lenslider_update_lenslider_option(self::$settingsOption, $this->_settingsDefault);
            $settings_array = $this->ls_settings;
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
    
    public function lenslider_make_attachment($file, $width, $title, $slidernum, $todel_id = false, $banner_k = '', $mime = false) {
        $mime = (!$mime)?$file['type']:$mime;
        $ret_array = array();
        $uid    = self::lenslider_hash();
        $foto_abs_path = $this->_lenslider_resize_image_abspath($file, $width, $this->ls_settings[self::$maxSizeName], $this->lenslider_make_keytag($title), $uid);
        if(!empty($foto_abs_path['path'])) {
            if($todel_id) @wp_delete_attachment($todel_id);
            $attachment = array(
                'post_title'     => (!empty($title))?sanitize_text_field($title):$slidernum."=>".$banner_k,
                'post_content'   => (!empty($title))?sanitize_text_field($title):$slidernum."=>".$banner_k,
                'post_type'      => 'attachment',
                'post_mime_type' => $mime
            );
            $ret_array['id']   = intval($this->_lenslider_insert_attachment($attachment, $foto_abs_path['path']));
            $ret_array['path'] = $this->_lenslider_update_attachment($ret_array['id'], $attachment);
            $ret_array['size'] = $foto_abs_path['size'];
            
            $ret_array['type'] = $mime;
        }
        return $ret_array;
    }

    public function lenslider_duplicate_slider($in_slidernum, $out_slidernum) {
        $in_slider_array = $this->_sliders_array[$in_slidernum];
        $in_slider_settings_array = $in_slider_array[self::$settingsTitle];
        unset($in_slider_array[self::$settingsTitle]);
        if(!empty($in_slider_array) && is_array($in_slider_array)) {
            foreach ($in_slider_array as $att_id=>$banner_array) {
                $uid      = self::lenslider_hash();
                $att_url  = wp_get_attachment_url($att_id);
                $copy_array = $this->_lenslider_copy_image_abspath($att_url, $this->lenslider_make_keytag($banner_array["ls_title"]), $uid);
                $att_metadata = array(
                    'post_title'     => sanitize_text_field($banner_array["ls_title"]." duplicated to {$out_slidernum}"),
                    'post_content'   => sanitize_text_field($banner_array["ls_text"]." duplicated to {$out_slidernum}"),
                    'post_type'      => 'attachment',
                    'post_mime_type' => $copy_array["type"]
                );
                $id                   = intval($this->_lenslider_insert_attachment($att_metadata, $copy_array['path']));
                $banner_array['path'] = $this->_lenslider_update_attachment($id, $att_metadata);
                
                $att_thumb_metadata = wp_get_attachment_metadata($banner_array['thumb_id']);
                if(!empty($att_thumb_metadata)) {
                    $thumb_id       = intval($banner_array['thumb_id']);
                    $uid            = self::lenslider_hash();
                    $att_thumb_url  = wp_get_attachment_url($thumb_id);
                    $copy_thumb_array = $this->_lenslider_copy_image_abspath($att_thumb_url, $this->lenslider_make_keytag($banner_array["ls_title"]), $uid);
                    $att_thumb_metadata = array(
                        'post_title'     => sanitize_text_field($banner_array["ls_title"]." thumb duplicated to {$out_slidernum}"),
                        'post_content'   => sanitize_text_field($banner_array["ls_text"]." thumb duplicated to {$out_slidernum}"),
                        'post_type'      => 'attachment',
                        'post_mime_type' => $copy_thumb_array["type"]
                    );
                    //$banner_array[self::$sliderComment] = 
                    $banner_array['thumb_id']   = intval($this->_lenslider_insert_attachment($att_thumb_metadata, $copy_thumb_array['path']));
                    $banner_array['path_thumb'] = $this->_lenslider_update_attachment($thumb_id, $att_thumb_metadata);
                }
                $this->_sliders_array[$out_slidernum][$id] = $this->_lenslider_sanitize_array($banner_array);
            }
            if(!empty($this->_sliders_array[$out_slidernum]) && is_array($this->_sliders_array[$out_slidernum])) {
                $this->_sliders_array[$out_slidernum][self::$settingsTitle] = $in_slider_settings_array;
            }
        }
        $this->_lenslider_update_lenslider_option(self::$bannersOption, $this->_sliders_array, $this->requestIndexURI);
    }
    
    public function lenslider_banners_processing($slidernum, $checkBannerArray, $ids_array, $ids_thumbs_array, $array, $settings_post_array = false) {
        $this_slider = (!empty($this->_sliders_array[$slidernum]))?$this->_sliders_array[$slidernum]:array();//die(var_dump($array));
        $this->_sliders_array[$slidernum] = array();
        $slider_settings_array = self::lenslider_get_slider_settings($slidernum);
        foreach(array_keys($checkBannerArray[$slidernum]) as $banner_k) {
            /*if(!empty($_COOKIE["skin_set_{$slidernum}"])) {
                $settings_post_array[$slidernum] = array_merge($settings_post_array[$slidernum], self::lenslider_get_skin_settings($slidernum, $_COOKIE["skin_set_{$slidernum}"], false));
                setcookie("skin_set_{$slidernum}","",mktime(0,0,0,1,1,1970));
            }*/
            
            $slidercomment = (!empty($settings_post_array[$slidernum][self::$sliderComment]))   ?$settings_post_array[$slidernum][self::$sliderComment]   :"";
            $sliderrandom  = (!empty($settings_post_array[$slidernum][self::$sliderRandom])   && $settings_post_array[$slidernum][self::$sliderRandom] == 'on')?1:0;
            $bannerslimit  = (!empty($settings_post_array[$slidernum][self::$bannersLimitName]))?$settings_post_array[$slidernum][self::$bannersLimitName]:0;
            $bannerWidth   = (!empty($settings_post_array[$slidernum][self::$bannerWidthName])) ?$settings_post_array[$slidernum][self::$bannerWidthName] :0;
            $bannerHeight  = (!empty($settings_post_array[$slidernum][self::$bannerHeightName]))?$settings_post_array[$slidernum][self::$bannerHeightName]:0;
            $disen         =         $settings_post_array[$slidernum][self::$sliderDisenName];
            $skin_name     = (!empty($settings_post_array[$slidernum][self::$skinName]))        ?$settings_post_array[$slidernum][self::$skinName]        :self::$defaultSkin;
            $has_thumb     = (!empty($settings_post_array[$slidernum][self::$hasThumb])       && $settings_post_array[$slidernum][self::$hasThumb] == 'on')?1:0;
            $maxthumbwidth = (!empty($settings_post_array[$slidernum][self::$thumbMaxWidth])  && !empty($settings_post_array[$slidernum][self::$hasThumb]))?$settings_post_array[$slidernum][self::$thumbMaxWidth]:0;
            $has_autoplay  = (!empty($settings_post_array[$slidernum][self::$hasAutoplay])    && $settings_post_array[$slidernum][self::$hasAutoplay] == 'on')?1:0;
            $autoplayDelay = (!empty($settings_post_array[$slidernum][self::$autoplayDelay])  && !empty($settings_post_array[$slidernum][self::$hasAutoplay]))?$settings_post_array[$slidernum][self::$autoplayDelay]:0;
            $pauseOnHover  = (!empty($settings_post_array[$slidernum][self::$autoplayHoverPause]) && $settings_post_array[$slidernum][self::$autoplayHoverPause] == 'on' && !empty($settings_post_array[$slidernum][self::$hasAutoplay]))?1:0;
            $easingEffect  = (!empty($settings_post_array[$slidernum][self::$easingEffect]))    ?$settings_post_array[$slidernum][self::$easingEffect]:'linear';
            $marginTop     = (!empty($settings_post_array[$slidernum][self::$marginTop]))       ?$settings_post_array[$slidernum][self::$marginTop]:0;
            $marginRight   = (!empty($settings_post_array[$slidernum][self::$marginRight]))     ?$settings_post_array[$slidernum][self::$marginRight]:0;
            $marginBottom  = (!empty($settings_post_array[$slidernum][self::$marginBottom]))    ?$settings_post_array[$slidernum][self::$marginBottom]:0;
            $marginLeft    = (!empty($settings_post_array[$slidernum][self::$marginLeft]))      ?$settings_post_array[$slidernum][self::$marginLeft]:0;
            if(!LenSliderSkins::lenslider_skin_exists($skin_name)) $skin_name = self::$defaultSkin;
            if(!empty($slider_settings_array) && $slider_settings_array[self::$skinName] != $skin_name) $disen = 0;
            //if(array_key_exists("ls_link", $array[$slidernum]) && $this->lenslider_is_valid_url($array[$slidernum]["ls_link"][$banner_k])) {//die(var_dump($ids_array));
                if(!empty($ids_array[$slidernum][$banner_k]) && !empty($array[$slidernum]['bannertype'][$banner_k])) {
                    $id         = intval($ids_array[$slidernum][$banner_k]);
                    $attObj     = $this->lenslider_get_attachment_params($id);
                    $image_path = $attObj->httpPath;
                    $image_size = $attObj->size;
                    switch ($array[$slidernum]['bannertype'][$banner_k]) {
                        case 'image':
                            if(!empty($array[$slidernum]['bannertitle'][$banner_k]) && !empty($this_slider[$id]['bannertitle']) && $array[$slidernum]['bannertitle'][$banner_k] != $this_slider[$id]['bannertitle']) {
                                $this->_lenslider_update_attachment($id, false, $array[$slidernum]['bannertitle'][$banner_k]);
                            }
                            break;
                    }
                }
                /*THUMBS*/
                if($has_thumb && !empty($ids_thumbs_array)/* && !empty($maxthumbwidth) && intval($maxthumbwidth) >= $this->thumbWidthMIN && intval($maxthumbwidth) <= $this->thumbWidthMAX*/) {
                    if(!empty($ids_thumbs_array[$slidernum][$banner_k]) && !empty($array[$slidernum]['bannertype'][$banner_k])) {
                        $thumb_id         = intval($ids_thumbs_array[$slidernum][$banner_k]);
                        $attObj           = $this->lenslider_get_attachment_params($thumb_id);
                        $image_thumb_path = $attObj->httpPath;
                        $image_thumb_size = $attObj->size;
                    } else {
                        if(!empty($ids_array[$slidernum][$banner_k])) {
                            $maked_attachment  = $this->lenslider_resize_image_library($ids_array[$slidernum][$banner_k], false, $maxthumbwidth, false, 'width', false, $slidernum);
                            $thumb_id          = $maked_attachment['id'];
                            $image_thumb_path  = $maked_attachment['path'];
                            $image_thumb_size  = $maked_attachment['size'];
                        }
                    }
                }
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
                foreach ($array[$slidernum] as $k=>$v) {
                    if(!empty($v[$banner_k])) $info_array_merge[$k] = $v[$banner_k];
                }
                $info_array = $this->_lenslider_sanitize_array(array_merge($info_array_neccessary, $info_array_merge));
                if(!empty($image_path) && $id && !empty($info_array)) $this->_sliders_array[$slidernum][$id] = $info_array;
            //}
        }
        
        $not_int_settings_array   = array();
        $to_unset_settings_array  = array();
        $maxvalues_settings_array = array();
        $disen_banners            = array();
        foreach ($settings_post_array[$slidernum] as $k=>$v) {
            if(is_array($v)) {
                if($k == 'notint') {
                    $to_unset_settings_array[] = $k;
                    foreach ($v as $v_k=>$v_v) {$not_int_settings_array[] = $v_k;}
                }
                if($k == 'maxvalue') {
                    $to_unset_settings_array[] = $k;
                    foreach ($v as $v_k=>$v_v) {$maxvalues_settings_array[$v_k] = intval($v_v);}
                }
                if($k == self::$bannerDisenName) {
                    $to_unset_settings_array[] = $k;
                    foreach ($v as $v_k=>$v_v) {if($v_v == 1) $disen_banners[$v_k] = intval($v_v);}
                }
            }
        }
        
        $slider_settings_array = array_merge(
                array(
                    self::$sliderComment      => sanitize_text_field($slidercomment),
                    self::$sliderRandom       => intval($sliderrandom),
                    self::$bannersLimitName   => intval($bannerslimit),
                    self::$bannerWidthName    => intval($bannerWidth),
                    self::$bannerHeightName   => intval($bannerHeight),
                    self::$sliderDisenName    => intval($disen),
                    self::$skinName           => $skin_name,
                    self::$hasThumb           => $has_thumb,
                    self::$thumbMaxWidth      => $maxthumbwidth,
                    self::$hasAutoplay        => $has_autoplay,
                    self::$autoplayDelay      => intval($autoplayDelay),
                    self::$autoplayHoverPause => $pauseOnHover,
                    self::$easingEffect       => $easingEffect,
                    self::$bannerDisenName    => $disen_banners
                ),
                LenSliderSettings::lenslider_make_settings_array(
                        $settings_post_array[$slidernum]/*array*/,
                        array_merge(
                                array(/*MAX limits default*/
                                    self::$bannersLimitName => self::$bannersLimitDefault,
                                    self::$bannerWidthName  => $this->bannerWidthMAX,
                                    self::$thumbMaxWidth    => $this->thumbWidthMAX,
                                    self::$autoplayDelay    => $this->delayMAX
                                ), $maxvalues_settings_array),
                                array(/*MIN limits default*/
                                    self::$bannerWidthName  => $this->imageWidthMIN,
                                    self::$thumbMaxWidth    => $this->thumbWidthMIN,
                                    self::$autoplayDelay    => $this->delayMIN
                                ),
                        array_merge(array(self::$sliderDisenName), $to_unset_settings_array),
                        array_merge(array(self::$sliderComment, self::$skinName, self::$easingEffect), $not_int_settings_array)
                )
        );//die(var_dump($slider_settings_array));
        if(!empty($this->_sliders_array[$slidernum])) $this->_sliders_array[$slidernum][self::$settingsTitle] = $slider_settings_array;
        else {
            unset($this->_sliders_array[$slidernum]);
            $this->_sliders_array[$slidernum] = null;
        }
        if(!empty($this->_sliders_array) || count($this->_sliders_array) > 1) $this->_lenslider_update_lenslider_option(self::$bannersOption, $this->_sliders_array, $this->requestSliderURI."&slidernum={$slidernum}&skin={$skin_name}");
    }
    /*---------------/PROCESSING METHODS---------------*/
    
    
    /*--------------OUTPUT METHODS---------------*/
    protected function _lenslider_link_variants($slidernum, $n, $title, $attachment_id = false, $check = false, $url_type_id = false) {
        $array = array(
            'lsurl'  => __('Manual', 'lenslider'),
            'post'   => __('Post', 'lenslider'),
            'page'   => __('Page', 'lenslider'),
            'cat'    => __('Category', 'lenslider')
        );
        $ret = "<div class=\"ls_post_url\"><table border=\"0\"><tr>";
        $i=0;
        foreach ($array as $k=>$v) {
            if($i==0) {
                $ret .= "<td style=\"padding-right:20px;\"><label class=\"ls_label sm\" for=\"{$k}_{$slidernum}_{$n}_{$attachment_id}\">{$title}</label></td>";
                foreach ($this->allowed_url_strs as $str) {
                    $ret .= "<td style=\"padding-right:6px;\"><a href=\"javascript:;\" onclick='javascript:ls_blinkurl(\"".self::lenslider_banner_hidden($slidernum, 'url_type', 'lsurl', false)."\", \"{$slidernum}\", \"{$n}\");jQuery(\"#ls_link_{$slidernum}_{$n}\").val(\"{$str}\");jQuery(\"#blink_lsurl_{$slidernum}_{$n}_{$attachment_id}\").attr(\"checked\", \"checked\");return false;'>{$str}</a></td>";
                }
            }
            $ret .= "<td><input class=\"blink ls_radio\" type=\"radio\" name=\"blink[{$slidernum}][{$attachment_id}][]\" id=\"blink_{$k}_{$slidernum}_{$n}_{$attachment_id}\" value=\"blink_{$k}\"";
            if($check == $k || (empty($attachment_id) && $i==0)) $ret .= " checked=\"checked\"";
            $ret .= " /></td><td style=\"padding-right:10px;\"><label class=\"ls_label sm\" for=\"blink_{$k}_{$slidernum}_{$n}_{$attachment_id}\">{$v}</label></td>";
            $i++;
        }
        $ret .= "<td><div class=\"blink_append\" id=\"blink_append_{$slidernum}_{$n}\">";
        if(!empty($check) && !empty($url_type_id) && !empty($attachment_id)) {
            switch ($check) {
                case 'post':
                    $ret .= self::lenslider_dropdown_posts($slidernum, $attachment_id, $n, "blink", $url_type_id);
                    break;
                case 'page':
                    $ret .= self::lenslider_dropdown_pages($slidernum, $attachment_id, $n, "blink", $url_type_id);
                    break;
                case 'cat':
                    $ret .= self::lenslider_dropdown_categories($slidernum, $attachment_id, $n, "blink", $url_type_id);
                    break;
            }
        }
        $ret .= "</div></td>";
        $ret .= "</tr></table></div>";
        return $ret;
    }
    
    protected function _lenslider_title_variants($slidernum, $n, $title, $attachment_id = false, $check = false, $title_type_id = false) {
        $array = array(
            'lstitle' => __('Manual', 'lenslider'),
            'post'    => __('Post', 'lenslider'),
            'page'    => __('Page', 'lenslider'),
            'cat'     => __('Category', 'lenslider')
        );
        $ret = "<div class=\"ls_post_title\"><table border=\"0\"><tr>";
        $i=0;
        foreach ($array as $k=>$v) {
            if($i==0) $ret .= "<td style=\"padding-right:20px;\"><label class=\"ls_label sm\" for=\"{$k}_{$slidernum}_{$n}_{$attachment_id}\">{$title}</label></td>";
            $ret .= "<td><input class=\"btitle ls_radio\" type=\"radio\" name=\"btitle[{$slidernum}][{$attachment_id}][]\" id=\"btitle_{$k}_{$slidernum}_{$n}_{$attachment_id}\" value=\"btitle_{$k}\"";
            if($check == $k || (empty($attachment_id) && $i==0)) $ret .= " checked=\"checked\"";
            $ret .= " /></td><td style=\"padding-right:10px;\"><label class=\"ls_label sm\" for=\"btitle_{$k}_{$slidernum}_{$n}_{$attachment_id}\">{$v}</label></td>";
            $i++;
        }
        $ret .= "<td><div class=\"btitle_append\" id=\"btitle_append_{$slidernum}_{$n}\">";
        if(!empty($check) && !empty($title_type_id) && !empty($attachment_id)) {
            switch ($check) {
                case 'post':
                    $ret .= self::lenslider_dropdown_posts($slidernum, $attachment_id, $n, "btitle", $title_type_id);
                    break;
                case 'page':
                    $ret .= self::lenslider_dropdown_pages($slidernum, $attachment_id, $n, "btitle", $title_type_id);
                    break;
                case 'cat':
                    $ret .= self::lenslider_dropdown_categories($slidernum, $attachment_id, $n, "btitle", $title_type_id);
                    break;
            }
        }
        $ret .= "</div></td>";
        $ret .= "</tr></table></div>";
        return $ret;
    }
    
    public static function lenslider_banner_hidden($slidernum, $k, $value = false, $doubleslashes = true) {
        $return = ($doubleslashes)?"<input type=\"hidden\" name=\"binfo[{$slidernum}][{$k}][]\" value=\"":"<input type=\\\"hidden\\\" name=\\\"binfo[{$slidernum}][{$k}][]\\\" value=\\\"";
        if(!empty($value)) $return .= $value;
        $return .= ($doubleslashes)?"\" />":"\\\" />";
        return $return;
    }
    
    protected function lenslider_wp_editor($value, $name, $settings_array) {
        ob_start();
        wp_editor($value, $name, $settings_array);
        $ret = ob_get_contents();
        ob_end_clean();
        return $ret;
    }
    
    protected function _lenslider_banner_item_add($slidernum, $n, $array, $array_merge = false, $array_unset = false, $attachment_id = false, $url_type = false, $url_type_id = false, $title_type = false, $title_type_id = false) {
        if(!empty($array) && is_array($array)) {
            $array = self::_lenslider_escape_array($this->_lenslider_make_fields_array($array, $array_unset, $array_merge));
            $ret = "";
            foreach ($array as $k=>$v) {
                $disabled = false;
                if($k == 'ls_link') $ret .= $this->_lenslider_link_variants($slidernum, $n, $v['title'], $attachment_id, $url_type, $url_type_id);
                elseif($k == 'ls_title') $ret .= $this->_lenslider_title_variants($slidernum, $n, $v['title'], $attachment_id, $title_type, $title_type_id);
                else $ret .= "<label class=\"ls_label\" style=\"margin-top:10px\" for=\"{$k}_{$slidernum}_{$n}\">{$v['title']}</label>";
                switch ($v['type']) {
                    case 'input':
                        $ret .= "<input style=\"width:100%\" type=\"text\" id=\"{$k}_{$slidernum}_{$n}\"";
                        $ret .= " value=\"";
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
                                case 'lsurl':
                                    $disabled = false;
                                    $ret .= esc_textarea($v['value']);
                                    break;
                            }
                        }
                        elseif($k == 'ls_title' && !empty($title_type) && !empty($title_type_id)) {
                            $disabled = true;
                            switch ($title_type) {
                                case 'post':
                                case 'page':
                                    $ret .= get_the_title($title_type_id);
                                    break;
                                case 'cat':
                                    $ret .= get_cat_name($title_type_id);
                                    break;
                                case 'lstitle':
                                    $disabled = false;
                                    $ret .= esc_textarea($v['value']);
                                    break;
                            }
                        }
                        else $ret .= esc_textarea($v['value']);
                        $ret .= "\" class=\"ls_input ls_maxinput ls_rounded_small";
                        if($v['tcheck'] && !$disabled) $ret .= " tcheck";
                        $ret .= "\"";
                        if(!$disabled) $ret .= " name=\"binfo[{$slidernum}][{$k}][]\"";
                        if(!empty($v['maxlength'])) $ret .= " maxlength=\"{$v['maxlength']}\"";
                        if($disabled) $ret .= " disabled=\"disabled\"";
                        $ret .= " /><div id=\"post_hidden_{$k}_{$slidernum}_{$n}\">";
                        if($disabled) $ret .= self::lenslider_banner_hidden($slidernum, $k, $v['value']);
                        $ret .= "</div>";
                        //if($v['tipsy']) $ret .= "<div class=\"ques\"><a href=\"javascript:;\" class=\"atipsy\" title=\"{$v['tipsy']}\"></a></div>";
                        //$ret .= "<div class=\"clear\"></div>";
                        break;
                    case 'textarea':
                        if($v['mce']) {
                            $settings_array = array(
                                'teeny' => true,
                                'textarea_name' => "binfo[{$slidernum}][{$k}][]",
                                'textarea_rows' => 15
                            );
                            $settings_array['media_buttons'] = ($v['media_buttons'])?true:false;
                            $ret .= $this->lenslider_wp_editor($v['value'], "{$k}_{$slidernum}_{$n}", $settings_array);
                        } else {
                            $ret .= "<textarea style=\"width:100%\" id=\"{$k}_{$slidernum}_{$n}\"";
                            $ret .= " name=\"binfo[{$slidernum}][{$k}][]\"";
                            $ret .= " class=\"ls_input ls_maxtextarea ls_rounded ls_nmce";
                            if(!empty($v['tcheck'])) $ret .= " tcheck";
                            $ret .= "\"";
                            if(!empty($v['maxlength'])) $ret .= " maxlength=\"{$v['maxlength']}\"";
                            $ret .= ">".esc_textarea($v['value'])."</textarea><div id=\"post_hidden_{$slidernum}_{$n}\">";
                            $ret .= "</div>";
                        }
                        break;
                }
            }
            return $ret;
        }
    }
    
    public function lenslider_slider_settings_add($n_slider, $settings_array, $array, $type = 'general', $array_merge = false, $array_unset = false) {
        if(!empty($array_merge) && is_array($array_merge)) $array = array_merge($array, $array_merge);
        if(!empty($array_unset) && is_array($array_unset)) {
            foreach ($array_unset as $key_to_unset) {
                unset($array[$key_to_unset]);
            }
        }
        $array = $this->_lenslider_make_grouped_slider_settings_array($array, $type);
        $ret = "";
        if(!empty($array) && is_array($array)) {
            $question_icon = plugins_url('images/i_question.png', $this->indexFile);
            foreach (array_keys($array) as $k) {
                $ret .= "<div class=\"misc-pub-section ls-pub-section\"><table border=\"0\" width=\"100%\"><tr>
                        <td width=\"5%\"><img class=\"ls_mtip\" src=\"{$question_icon}\"";
                        $ret .= (!empty($array[$k]['desc']))?" title=\"{$array[$k]['desc']}\"":"";
                        $ret .= " /></td>
                        <td width=\"45%\"><label class=\"ls_label\" for=\"{$k}_{$n_slider}\">{$array[$k]['title']}</label></td>
                        <td width=\"";
                        $ret .= (!empty($array[$k]['ext']))?"40":"50";
                        $ret .= "%\">";
                        if(!empty($array[$k]['type'])) {
                            switch($array[$k]['type']) {
                                case 'input':
                                    if(empty($array[$k]['size'])) $array[$k]['size'] = 5;
                                    $ret .= "<input ";
                                    if(empty($array[$k]['invariable'])) $ret .= "name=\"slset[{$n_slider}][{$k}]\"";
                                    $ret .= " type=\"text\" size=\"{$array[$k]['size']}\" id=\"{$k}_{$n_slider}\" ";
                                    $ret .= (!empty($array[$k]['invariable']))?"value=\"".intval($array[$k]['invariable'])."\"":"value=\"{$settings_array[$k]}\"";
                                    $ret .= " class=\"ls_input ls_input_settings {$k}_{$n_slider}";
                                    if(!empty($array[$k]['class'])) $ret .= " ".$array[$k]['class'];
                                    //exception
                                    if(empty($settings_array[self::$hasAutoplay]) && $k == self::$autoplayDelay) {
                                        $array[$k]['disabled'] = true;
                                        $array[$k]['spinner'] = false;
                                    }
                                    //
                                    if($array[$k]['spinner'] === true) $ret .= " ls_spinner";
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
                                    $ret .= "<input name=\"slset[{$n_slider}][{$k}]\" type=\"checkbox\" id=\"{$k}_{$n_slider}\" class=\"ls_checkbox";
                                    if(!empty($array[$k]['class'])) $ret .= " {$array[$k]['class']}";
                                    $ret .= "\"";
                                    if(($settings_array[$k] == 1 || $settings_array[$k] == true) && empty($array[$k]['invariable'])) $ret .= " checked=\"checked\"";
                                    if(!empty($array[$k]['invariable']) && $array[$k]['invariable'] != 'off') $ret .= " checked=\"checked\"";
                                    //exception
                                    if(empty($settings_array[self::$hasAutoplay]) && $k == self::$autoplayHoverPause) $array[$k]['disabled'] = true;
                                    if(!empty($array[$k]['disabled']) || !empty($array[$k]['invariable'])) $ret .= " disabled=\"disabled\"";
                                    $ret .= " />";
                                    if(!empty($array[$k]['invariable'])) $ret .= "<input type=\"hidden\" name=\"slset[{$n_slider}][{$k}]\" value=\"{$array[$k]['invariable']}\" />";//
                                    break;
                                case 'select':
                                    if(!empty($array[$k]['values'])) {
                                        $ret .= "<select name=\"slset[{$n_slider}]";
                                        $ret .= "[{$k}]\" style=\"width:130px\">";
                                        foreach ($array[$k]['values'] as $val) {
                                            $ret .= "<option value=\"{$val}\"";
                                            if($val == $settings_array[$k]) $ret .= " selected=\"selected\"";
                                            $ret .= ">{$val}</option>";
                                        }
                                        $ret .= "</select>";
                                    }
                                    break;
                            }
                        }
                        if(!empty($array[$k]['customtype'])) $ret .= $array[$k]['customtype'];
                        $ret .= "</td>";
                        if(!empty($array[$k]['ext'])) $ret .= "<td width=\"10%\">{$array[$k]['ext']}</td>";
                        $ret .= "</tr></table></div>";
            }
        }
        return $ret;
    }
    
    public static function lenslider_image_upload_inputs($slidernum = '', $att_id = false, $input_name = 'pbi', $hidden_name = 'ls_att_id') {
        $ret = "<input type=\"file\" name=\"{$input_name}[{$slidernum}]\" class=\"if_ovrflw\" accept=\"image/*\" />";
        if($att_id) $ret .= "<br /><a href=\"".wp_get_attachment_url($att_id)."\"><img src=\"".wp_get_attachment_url($att_id)."\" style=\"width:50px;\" /></a><input type=\"hidden\" name=\"{$hidden_name}[{$slidernum}]\" value=\"{$att_id}\" />";
        return $ret;
    }
    
    public static function lenslider_youtube_obj_output($id, $width = false, $height = false) {
        $ratio = 16/9;
        if(!$height && $width) $height = intval(floor($width/$ratio));
        if(!$width && $height) $width  = intval(floor($ratio*$height));
        $width = intval($width);
        $height = intval($height);
        if($id && $width && $height) {
            return "<object width=\"{$width}\" height=\"{$height}\"><param name=\"movie\" value=\"https://www.youtube.com/v/{$id}?version=3\"></param><param name=\"allowFullScreen\" value=\"true\"></param><param name=\"allowScriptAccess\" value=\"always\"></param><embed src=\"https://www.youtube.com/v/{$id}?version=3\" type=\"application/x-shockwave-flash\" allowfullscreen=\"true\" allowScriptAccess=\"always\" width=\"{$width}\" height=\"{$height}\"></embed></object>";
        }
        return false;
    }
    
    public static function lenslider_vimeo_obj_output($id, $width = false, $height = false) {
        $ratio = 16/9;
        if(!$height && $width) $height = intval(floor($width/$ratio));
        if(!$width && $height) $width  = intval(floor($ratio*$height));
        $width = intval($width);
        $height = intval($height);
        if($id && $width && $height) {
            return "<object width=\"{$width}\" height=\"{$height}\"><param name=\"allowfullscreen\" value=\"true\" /><param name=\"allowscriptaccess\" value=\"always\" /><param name=\"movie\" value=\"http://vimeo.com/moogaloop.swf?clip_id={$id}&amp;server=vimeo.com&amp;color=00adef&amp;fullscreen=1\" /><embed src=\"http://vimeo.com/moogaloop.swf?clip_id={$id}&amp;server=vimeo.com&amp;color=00adef&amp;fullscreen=1\" type=\"application/x-shockwave-flash\" allowfullscreen=\"true\" allowscriptaccess=\"always\" width=\"{$width}\" height=\"{$height}\"></embed></object>";
        }
        return false;
    }

    /*вывод баннеров для слайдера*/
    public function lenslider_banners_items($slidernum, $new_slider = true, $skinObj = false) {
        $return = "";
        $count_enabled = intval(count($this->_sliders_array[$slidernum][self::$bannerDisenName]));
        $array_merge = ($skinObj && $skinObj->bannerMergeArray)?$skinObj->bannerMergeArray:false;
        $array_unset = ($skinObj && $skinObj->bannerUnsetArray)?$skinObj->bannerUnsetArray:false;
        if(!$new_slider) {
            $sliders_array = $this->_sliders_array;
            if(!empty($sliders_array[$slidernum]) && is_array($sliders_array[$slidernum])) {
                $n=0;
                foreach ($sliders_array[$slidernum] as /*banner_key*/$k=>$banner_array) {
                    if($k != self::$settingsTitle) {
                        if($skinObj) {
                            $array_merge  = LenSliderSkins::_lenslider_skin_merge_array($skinObj->bannerMergeArray, $banner_array);
                            $array_unset  = $skinObj->bannerUnsetArray;
                        }
                        $return .= $this->lenslider_banner_item($n, $slidernum, $this->bannersLimit, $count_enabled, $array_merge, $array_unset, $sliders_array[$slidernum][self::$settingsTitle]['ls_has_thumb'], false, $this->_lenslider_make_default_fields_array($banner_array), $k, $banner_array['path_thumb'], $banner_array['thumb_id']);
                        $n++;
                    }
                }
            } else $return .= $this->lenslider_banner_item($slidernum, $slidernum, $this->bannersLimit, $count_enabled, $array_merge, $array_unset, $sliders_array[$slidernum][self::$settingsTitle]['ls_has_thumb'], false);
        } else $return = $this->lenslider_banner_item(0, $slidernum, $this->bannersLimit, $count_enabled, $array_merge, $array_unset, false, true);
        return $return;
    }

    public static function lenslider_output_slider($slidernum, $echo = true, $check_enable = true) {
        $slidernum = strtolower($slidernum);
        if(($check_enable && self::lenslider_is_enabled_slider($slidernum)) || !$check_enable) {
            $skin_name      = self::_lenslider_get_slider_skin_name($slidernum);
            $slider_banners = self::lenslider_get_slider_banners($slidernum);
            if(file_exists(LenSliderSkins::_lenslider_skins_abspath()."/".$skin_name."/output/output.html")) {
                $file_text = file_get_contents(LenSliderSkins::_lenslider_skins_abspath()."/".$skin_name."/output/output.html");
            } elseif(file_exists(LenSliderSkins::_lenslider_skins_custom_abspath()."/".$skin_name."/output/output.html")) {
                $file_text = file_get_contents(LenSliderSkins::_lenslider_skins_custom_abspath()."/".$skin_name."/output/output.html");
            } else {
                if($skin_name == self::$defaultSkin) $file_text = file_get_contents(LenSliderSkins::_lenslider_skins_abspath()."/default.html");
                else {
                    if($echo) {
                        echo '';
                        return;
                    } else return '';
                }
            }
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
    
    public function lenslider_banner_item($n, $slidernum, $banners_limit, $count_enabled = false, $array_merge = false, $array_unset = false, $has_thumb = false, $static_first = false, $array = false, $attachment_id = 0, $img_thumb = false, $attachment_thumb_id = 0) {
        $nn=$n+1;
        $settings_array = $this->_sliders_array[$slidernum][self::$settingsTitle];
        $banner_array   = (!empty($attachment_id))?$this->_sliders_array[$slidernum][$attachment_id]:array();
        $lmman_dis      = (!empty($banner_array['bannertype']) || $attachment_id)?" lmdis":"";
        $img            = ($banner_array['path'])?$this->_lenslider_decode_url($banner_array['path']):false;
        $img_thumb      = ($has_thumb && $banner_array['path_thumb'])?$this->_lenslider_decode_url($banner_array['path_thumb']):false;
        $url_type       = (!empty($banner_array['url_type']))?$banner_array['url_type']:'lsurl';
        $url_type_id    = (!empty($banner_array['url_type_id']))?$banner_array['url_type_id']:false;
        $title_type     = (!empty($banner_array['title_type']))?$banner_array['title_type']:'lstitle';
        $title_type_id  = (!empty($banner_array['title_type_id']))?$banner_array['title_type_id']:false;
        $post_title     = '';
        $count_enabled  = (!$count_enabled)?intval(count($this->_sliders_array[$slidernum][self::$bannerDisenName])):$count_enabled;
        $static_first_html = ($static_first)?"<input type=\"hidden\" id=\"static_first\" value=\"1\" />":"";
        if($count_enabled <= $banners_limit) {
            $ret  = "
            <li class=\"postbox bitem bitem_{$n}";if(@$_COOKIE["folding_{$slidernum}"] == 'svernuto') $ret .= " min";$ret .= "\" id=\"bitem_{$n}\">
                <input type=\"hidden\" name=\"bannerhidden[{$slidernum}][]\" />{$static_first_html}
                <div id=\"anchor_{$slidernum}_{$n}\"></div>
                <div id=\"post_hidden_uth_ls_link_{$slidernum}_{$n}\">".self::lenslider_banner_hidden($slidernum, 'url_type', $url_type)."</div>
                <div id=\"post_hidden_uth_ls_title_{$slidernum}_{$n}\">".self::lenslider_banner_hidden($slidernum, 'title_type', $title_type)."</div>";
                $ret .= "<div id=\"url_type_id_{$slidernum}_{$n}\">";
                if($url_type != 'lsurl' && !empty($array) && is_array($array) && !empty($array['ls_link']['value'])) $ret .= self::lenslider_banner_hidden($slidernum, 'url_type_id', $url_type_id);
                elseif($url_type == 'lsurl') $ret .= self::lenslider_banner_hidden($slidernum, 'url_type_id', '-1');
                $ret .= "</div>";
                $ret .= "<div id=\"title_type_id_{$slidernum}_{$n}\">";
                if($title_type != 'lstitle' && !empty($array) && is_array($array) && !empty($array['ls_title']['value'])) $ret .= self::lenslider_banner_hidden($slidernum, 'title_type_id', $title_type_id);
                elseif($title_type == 'lstitle') $ret .= self::lenslider_banner_hidden($slidernum, 'title_type_id', '-1');
                $ret .= "</div>";
                $ret .= "<div class=\"ls_slide_image_inner_overlay\" id=\"boverlay_{$n}\" style=\"display:none;\"></div>";
                if($attachment_id) {$ret .= "<div class=\"handlediv ls_mtip\" title=\"".sprintf(__("Banner %d expand / collapse", 'lenslider'), $nn)."\"><a href=\"javascript:;\"></a></div>
                <div class=\"ls_banner_control ls_banner_close\"><a class=\"liveajaxbdel\" id=\"liveajaxbdel_{$n}\" href=\"javascript:;\"></a></div>
                <div class=\"ls_banner_control\" style=\"width:auto !important;margin-top:8px\"><select class=\"ls_switch banner_switch\" id=\"disen_banner_switch_{$n}\" name=\"slset[{$slidernum}][".self::$bannerDisenName."][]\"><option value=\"1\" ".selected($settings_array[self::$bannerDisenName][$n], 1, false).">".__('On', 'lenslider')."</option><option value=\"0\"";if(empty($settings_array[self::$bannerDisenName][$n])) {$ret .= " selected=\"selected\"";}$ret .= ">".__('Off', 'lenslider')."</option></select></div>";}
                elseif($n!=0) $ret .= "<div class=\"ls_banner_control ls_banner_close\"><a class=\"liveajaxbdel\" id=\"liveajaxbdel_{$n}\" href=\"javascript:;\"></a></div>";
                if(!$attachment_id) $ret .= "<input type=\"hidden\" id=\"disen_banner_switch_{$n}\" name=\"slset[{$slidernum}][".self::$bannerDisenName."][]\" value=\"1\" />";
                $ret .= "
                <h3 class=\"hndle\"><span>".sprintf(__("Banner %d", 'lenslider'), $nn)."{$post_title}</span></h3>
                <div class=\"inside\">
                    <table border=\"0\" width=\"100%\">
                        <tr>
                            <td width=\"322\" valign=\"top\" style=\"padding-right:20px\">
                                <div class=\"bimage_outer\">
                                    <div class=\"ls_metabox2 ls_rounded ls_shadow\">
                                        <div class=\"ls_box_header\"><span class=\"ls_title\">".sprintf(__("Banner %d main content", 'lenslider'), $nn)."</span></div>
                                        <div class=\"ls_box_content\">
                                            <div id=\"lup_{$n}\" class=\"bimagehide_{$n}\"";$ret.=($banner_array['bannertype'] && $banner_array['bannertype'] != 'image')?" style=\"display:none;\"":"";$ret.=">
                                                <div class=\"bimge_div\" id=\"bimge_image_div_{$slidernum}_{$n}\">
                                                    <div class=\"abs bload2 ls_media_abs_{$slidernum}_{$n}\" style=\"display:none\"></div>
                                                    <a class=\"bimge_content ls-cont-uploadphoto ls_media_upload b_dashed\" id=\"ls_media_upload_{$slidernum}_{$n}_{$attachment_id}\" href=\"javascript:;\"";
                                                    if((!empty($banner_array['bannertype']) && $banner_array['bannertype'] == 'image') || (!$banner_array['bannertype'] && $attachment_id)) $ret .= " style=\"display:none;\"";
                                                    $ret .= "></a>
                                                    <div id=\"ls-cont-media-mu-{$n}\"";
                                                        if(!$img && !$attachment_id) $ret .= " style=\"display:none;\"";
                                                        $ret .= ">";
                                                        if((!empty($banner_array['bannertype']) && $banner_array['bannertype'] == 'image') || $img) $ret .= "<div class=\"ls_box_cont_abs\"><a class=\"a_ls_del\" id=\"ls_box_del_{$slidernum}_{$n}_{$attachment_id}\" href=\"javascript:;\">".__('delete', 'lenslider')."</a></div><a href=\"{$img}\" class=\"thickbox\"><img src=\"{$img}\" height=\"164\" /></a>";
                                                        $ret .= "
                                                    </div>";
                                                $ret .= "
                                                </div>
                                                <div class=\"ls-img-code\" id=\"ls-img-code-{$n}\"";
                                                    if(($banner_array['bannertype'] && $banner_array['bannertype'] != 'image') || !$attachment_id) $ret .= " style=\"display:none\"";
                                                    $ret .= ">";
                                                    if((!empty($banner_array['bannertype']) && $banner_array['bannertype'] == 'image') || !$banner_array['bannertype']) $ret .= "<code style=\"display:block\">".str_ireplace(self::$siteurl, '', $img)."</code>";
                                                $ret .= "
                                                </div>
                                                <label class=\"ls_label\" for=\"ls-bimg-alt-{$n}\">".__('Alt Text for SEO', 'lenslider').":</label><br />
                                                <input type=\"text\" class=\"ls_input\" id=\"ls-bimg-alt-{$n}\" name=\"binfo[{$slidernum}][banneralt][]\" style=\"width:100%\" value=\"";
                                                if(!empty($banner_array['banneralt'])) $ret .= $banner_array['banneralt'];
                                                $ret .= "\" />
                                                <label class=\"ls_label\" for=\"ls-bimg-title-{$n}\">".__('SEO Title (latin/translit chars req)', 'lenslider').":</label><br />
                                                <input type=\"text\" class=\"ls_input\" id=\"ls-bimg-title-{$n}\" name=\"binfo[{$slidernum}][bannertitle][]\" style=\"width:100%\" value=\"";
                                                if(!empty($banner_array['bannertitle'])) $ret .= $banner_array['bannertitle'];
                                                $ret .= "\" />
                                                <label class=\"ls_label\">".__('Width &times; Height (pixels)', 'lenslider').":</label><br />
                                                <table border=\"0\" width=\"100%\"><tr>
                                                <td width=\"45%\">
                                                    <div class=\"ls-relative\" style=\"padding-right:25px\">
                                                        <a class=\"ls-crop-gal ls-crop-gal-{$n} ls-crop-gal-width";
                                                        if(!$attachment_id || (!empty($banner_array['imageprior']) && $banner_array['imageprior'] == 'width') || !$banner_array['imageprior']) $ret .= " act";
                                                        if((!empty($banner_array['bannertype']) && $banner_array['bannertype'] == 'image' && $attachment_id) || (!$banner_array['bannertype'] && $attachment_id)) $ret .= " disb";
                                                        $ret .= "\" href=\"javascript:;\"></a>
                                                        <input type=\"text\" class=\"ls_input\"";
                                                        if($banner_array['bannertype'] != 'image') $ret .= " id=\"ls-bimg-width-{$n}\" name=\"binfo[{$slidernum}][bannerwidth][]\"";
                                                        $ret .= " style=\"width:100%\" value=\"";
                                                        $ret .= (!empty($banner_array['bannerwidth']))?$banner_array['bannerwidth']:$this->bannerWidth;
                                                        $ret .= "\"";
                                                        if(!empty($banner_array['bannertype']) && $banner_array['bannertype'] == 'image') $ret .= " disabled=\"disabled\"";
                                                        $ret .= " /><input type=\"hidden\" class=\"ls_hidden\"";
                                                        if(!empty($banner_array['bannertype']) && $banner_array['bannertype'] == 'image') $ret .= " id=\"ls-bimg-width-{$n}\" name=\"binfo[{$slidernum}][bannerwidth][]\"";
                                                        $ret .= " value=\"";
                                                        if(!empty($banner_array['bannerwidth'])) $ret .= $banner_array['bannerwidth'];
                                                        $ret .= "\" />
                                                    </div><!--ls-relative-->
                                                </td>
                                                <td width=\"10%\" align=\"center\">&times;</td>
                                                <td width=\"45%\">
                                                    <div class=\"ls-relative\" style=\"padding-right:25px\">
                                                        <a class=\"ls-crop-gal ls-crop-gal-{$n} ls-crop-gal-height";
                                                        if(!empty($banner_array['imageprior']) && $banner_array['imageprior'] == 'height') $ret .= " act";
                                                        if((!empty($banner_array['bannertype']) && $banner_array['bannertype'] == 'image')) $ret .= " disb";
                                                        $ret .= "\" href=\"javascript:;\"></a>
                                                        <input type=\"text\" class=\"ls_input\"";
                                                        if($banner_array['bannertype'] != 'image') $ret .= " id=\"ls-bimg-height-{$n}\" name=\"binfo[{$slidernum}][bannerheight][]\"";
                                                        $ret .= " style=\"width:100%\" value=\"";$ret .= (!empty($banner_array['bannerheight']))?$banner_array['bannerheight']:$this->bannerHeight;$ret .= "\"";
                                                        if(!empty($banner_array['bannertype']) && $banner_array['bannertype'] == 'image') $ret .= " disabled=\"disabled\"";
                                                        $ret .= " /><input type=\"hidden\" class=\"ls_hidden\"";
                                                        if(!empty($banner_array['bannertype']) && $banner_array['bannertype'] == 'image') $ret .= " id=\"ls-bimg-height-{$n}\" name=\"binfo[{$slidernum}][bannerheight][]\"";
                                                        $ret .= " value=\"";
                                                        if(!empty($banner_array['bannerheight'])) $ret .= $banner_array['bannerheight'];
                                                        $ret .= "\" />
                                                    </div><!--ls-relative-->
                                                </td>
                                                </tr></table>
                                                <!--table border=\"0\" width=\"100%\"><tr><td width=\"40%\"><label class=\"ls_label\" for=\"ls-bimg-crop-{$n}\">".__('Crop', 'lenslider').":</label></td>
                                                <td width=\"60%\" align=\"right\">
                                                <input type=\"checkbox\" class=\"ls_checkbox\" style=\"margin-top:7px\" id=\"ls-bimg-usercrop-{$n}\" /> <label class=\"ls_label\" for=\"ls-bimg-usercrop-{$n}\">".__('Manual jCrop', 'lenslider')."</label>
                                                </td></tr></table-->
                                                <!--input type=\"text\" class=\"ls_input\" id=\"ls-bimg-crop-{$n}\" style=\"width:100%\" value=\"\" /-->";
                                                //$ret .= self::lenslider_dropdown_crop_vars("binfo[{$slidernum}][bannercrop][]", " id=\"ls-bimg-crop-{$n}\" style=\"width:100%\"", $banner_array['bannercrop']);
                                                $ret .= "<input type=\"hidden\" id=\"imageprior_{$n}\" name=\"binfo[{$slidernum}][imageprior][]\" value=\"";
                                                if($banner_array['bannertype'] != 'image') $ret .= "width";
                                                if(!empty($banner_array['imageprior'])) $ret .= $banner_array['imageprior'];
                                                $ret .= "\" />
                                            </div><!--bimagehide-->
                                            <div id=\"lto_{$n}\" class=\"bimagehide_{$n}\"";$ret.=(($banner_array['bannertype'] && $banner_array['bannertype'] != 'text') || !$banner_array['bannertype'])?" style=\"display:none;\"":"";$ret.=">
                                                <div class=\"bimge_div\" id=\"bimge_text_div_{$slidernum}_{$n}\">
                                                    <div class=\"abs bload2 ls_media_abs_{$slidernum}_{$n}\" style=\"display:none\"></div>
                                                    <a class=\"bimge_content ls-cont-textonly ls_textonly b_dashed";
                                                    if($banner_array['bannertype'] && $banner_array['bannertype'] == 'text') $ret .= " act";
                                                    $ret .= "\" id=\"ls_textonly_{$slidernum}_{$n}_{$attachment_id}\" href=\"javascript:;\"></a>
                                                </div><!--bimge_div-->
                                            </div><!--bimagehide-->
                                            <!--div id=\"lum_{$n}\" class=\"bimagehide_{$n}\" style=\"display:none\"><a class=\"bimge_content b_dashed ls-cont-uploadmovie\" href=\"javascript:;\"></a></div-->
                                            <div id=\"lyt_{$n}\" class=\"bimagehide_{$n}\"";$ret.=(($banner_array['bannertype'] && $banner_array['bannertype'] != 'youtube') || !$banner_array['bannertype'])?" style=\"display:none;\"":"";$ret.=">
                                                <div class=\"bimge_div\" id=\"bimge_yt_div_{$slidernum}_{$n}\">
                                                    <div class=\"abs bload2 ls_media_abs_{$slidernum}_{$n}\" style=\"display:none\"></div>
                                                    
                                                    <div class=\"bimge_content b_dashed ls-cont-uploadyoutube\" id=\"ls_yt_upload_{$slidernum}_{$n}\"";
                                                    if(!empty($banner_array['banneryoutube'])) $ret .= " style=\"display:none\"";
                                                    $ret .= "></div>
                                                    
                                                    <div id=\"ls-cont-media-yt-{$n}\"";if(empty($banner_array['banneryoutube'])) $ret .= " style=\"display:none\"";$ret .= ">";
                                                        if(!empty($banner_array['banneryoutube'])) {
                                                            $ret .= "<div class=\"ls_box_cont_abs\"><a class=\"a_ls_del\" id=\"ls_box_del_{$slidernum}_{$n}_{$attachment_id}\" href=\"javascript:;\">".__('delete', 'lenslider')."</a></div>";
                                                            $youtube_id  = self::lenslider_get_youtube_id($banner_array['banneryoutube']);
                                                            $ret .= self::lenslider_youtube_obj_output($youtube_id, 276);
                                                        }
                                                        $ret .= "
                                                    </div><!--ls-cont-media-yt-->
                                                </div><!--bimge_div-->
                                                <label class=\"ls_label\" for=\"ls-yt-url-{$n}\">".__('YouTube link', 'lenslider').":</label>
                                                <table border=\"0\" width=\"100%\"></tr><td><input type=\"text\" class=\"ls_input\"";
                                                if(empty($banner_array['banneryoutube'])) $ret .= " id=\"ls-yt-url-{$n}\" name=\"binfo[{$slidernum}][banneryoutube][]\"";
                                                $ret .= ($banner_array['bannertype'] && $banner_array['bannertype'] == 'youtube')?" disabled=\"disabled\"":"";
                                                $ret .= " style=\"width:100%\" value=\"";
                                                if(!empty($banner_array['banneryoutube'])) $ret .= $banner_array['banneryoutube'];
                                                $ret .= "\" /><input type=\"hidden\" class=\"ls_hidden\"";
                                                if(!empty($banner_array['banneryoutube'])) $ret .= " id=\"ls-yt-url-{$n}\" name=\"binfo[{$slidernum}][banneryoutube][]\"";
                                                $ret .= " value=\"";
                                                if(!empty($banner_array['banneryoutube'])) $ret .= $banner_array['banneryoutube'];
                                                $ret .= "\" /></td><td><button type=\"button\" id=\"yt_button_{$slidernum}_{$n}\" class=\"button yt_button\"";
                                                $ret .= ($banner_array['bannertype'] && $banner_array['bannertype'] == 'youtube')?" disabled=\"disabled\"":"";
                                                $ret .= ">".__('load', 'lenslider')."</button></td></tr></table>
                                                <label class=\"ls_label\">".__('Width &times; Height (pixels)', 'lenslider').":</label><br />
                                                <table border=\"0\" width=\"100%\"><tr>
                                                <td width=\"45%\">
                                                    <div class=\"ls-relative\">
                                                        <input type=\"text\" class=\"ls_input\" id=\"ls-yt-width-{$n}\" name=\"binfo[{$slidernum}][bannerwidth_yt][]\" style=\"width:100%\" value=\"";
                                                        $ret .= (!empty($banner_array['bannerwidth_yt']))?$banner_array['bannerwidth_yt']:$this->bannerWidth;$ret .= "\" />
                                                    </div>
                                                </td>
                                                <td width=\"10%\" align=\"center\">&times;</td>
                                                <td width=\"45%\">
                                                    <div class=\"ls-relative\">
                                                        <input type=\"text\" class=\"ls_input\" id=\"ls-yt-height-{$n}\" name=\"binfo[{$slidernum}][bannerheight_yt][]\" style=\"width:100%\" value=\"";
                                                        $ret .= (!empty($banner_array['bannerheight_yt']))?$banner_array['bannerheight_yt']:'';$ret .= "\" />
                                                    </div>
                                                </td>
                                                </tr></table>
                                            </div><!--bimagehide-->
                                            <div id=\"lvm_{$n}\" class=\"bimagehide_{$n}\"";$ret.=(($banner_array['bannertype'] && $banner_array['bannertype'] != 'vimeo') || !$banner_array['bannertype'])?" style=\"display:none;\"":"";$ret.=">
                                                <div class=\"bimge_div\" id=\"bimge_vm_div_{$slidernum}_{$n}\">
                                                    <div class=\"abs bload2 ls_media_abs_{$slidernum}_{$n}\" style=\"display:none\"></div>
                                                    <div class=\"bimge_content b_dashed ls-cont-uploadvimeo\" id=\"ls_vm_upload_{$slidernum}_{$n}\"";
                                                        if(!empty($banner_array['bannervimeo'])) $ret .= " style=\"display:none\"";
                                                        $ret .= ">
                                                    </div>
                                                    <div id=\"ls-cont-media-vm-{$n}\"";
                                                        if(empty($banner_array['bannervimeo'])) $ret .= " style=\"display:none\"";
                                                        $ret .= ">";
                                                        if(!empty($banner_array['bannervimeo'])) {
                                                            $ret .= "<div class=\"ls_box_cont_abs\"><a class=\"a_ls_del\" id=\"ls_box_del_{$slidernum}_{$n}_{$attachment_id}\" href=\"javascript:;\">".__('delete', 'lenslider')."</a></div>";
                                                            $youtube_id  = self::lenslider_get_vimeo_id($banner_array['bannervimeo']);
                                                            $ret .= self::lenslider_vimeo_obj_output($youtube_id, 276);
                                                        }
                                                        $ret .= "
                                                    </div><!--ls-cont-media-vm-->
                                                </div><!--bimge_div-->
                                                <label class=\"ls_label\" for=\"ls-vm-url-{$n}\">".__('Vimeo link', 'lenslider').":</label>
                                                <table border=\"0\" width=\"100%\"></tr><td><input type=\"text\" class=\"ls_input\" ";
                                                if(empty($banner_array['bannervimeo'])) $ret .= "id=\"ls-vm-url-{$n}\" name=\"binfo[{$slidernum}][bannervimeo][]\"";
                                                $ret .= " style=\"width:100%\" value=\"";
                                                if(!empty($banner_array['bannervimeo'])) $ret .= $banner_array['bannervimeo'];
                                                $ret .= "\" /><input type=\"hidden\" class=\"ls_hidden\"";
                                                if(!empty($banner_array['bannervimeo'])) $ret .= " id=\"ls-vm-url-{$n}\" name=\"binfo[{$slidernum}][bannervimeo][]\"";
                                                $ret .= " value=\"";
                                                if(!empty($banner_array['bannervimeo'])) $ret .= $banner_array['bannervimeo'];
                                                $ret .= "\" /></td><td><button type=\"button\" id=\"vm_button_{$slidernum}_{$n}\" class=\"button vm_button\"";
                                                $ret .= ($banner_array['bannertype'] && $banner_array['bannertype'] == 'vimeo')?" disabled=\"disabled\"":"";
                                                $ret .= ">".__('load', 'lenslider')."</button></td></tr></table>
                                                <label class=\"ls_label\">".__('Width &times; Height (pixels)', 'lenslider').":</label><br />
                                                <table border=\"0\" width=\"100%\"><tr>
                                                <td width=\"45%\">
                                                    <div class=\"ls-relative\">
                                                        <input type=\"text\" class=\"ls_input\"";
                                                        if(!$attachment_id) $ret .= " id=\"ls-vm-width-{$n}\" name=\"binfo[{$slidernum}][bannerwidth_vm][]\"";
                                                        $ret .= " style=\"width:100%\" value=\"";
                                                        $ret .= (!empty($banner_array['bannerwidth_vm']))?$banner_array['bannerwidth_vm']:$this->bannerWidth;
                                                        $ret .= "\"";
                                                        if($attachment_id) $ret .= " disabled=\"disabled\"";
                                                        $ret .= " /><input type=\"hidden\" class=\"ls_hidden\"";
                                                        if($attachment_id) $ret .= " id=\"ls-vm-width-{$n}\" name=\"binfo[{$slidernum}][bannerwidth_vm][]\"";
                                                        $ret .= " value=\"";
                                                        if(!empty($banner_array['bannerwidth_vm'])) $ret .= $banner_array['bannerwidth_vm'];
                                                        $ret .= "\" />
                                                    </div><!--ls-relative-->
                                                </td>
                                                <td width=\"10%\" align=\"center\">&times;</td>
                                                <td width=\"45%\">
                                                    <div class=\"ls-relative\">
                                                        <input type=\"text\" class=\"ls_input\"";
                                                        if(!$attachment_id) $ret .= " id=\"ls-vm-height-{$n}\" name=\"binfo[{$slidernum}][bannerheight_vm][]\"";
                                                        $ret .= " style=\"width:100%\" value=\"";
                                                        $ret .= (!empty($banner_array['bannerheight_vm']))?$banner_array['bannerheight_vm']:'';
                                                        $ret .= "\"";
                                                        if($attachment_id) $ret .= " disabled=\"disabled\"";
                                                        $ret .= " /><input type=\"hidden\" class=\"ls_hidden\"";
                                                        if($attachment_id) $ret .= " id=\"ls-vm-height-{$n}\" name=\"binfo[{$slidernum}][bannerheight_vm][]\"";
                                                        $ret .= " value=\"";
                                                        if(!empty($banner_array['bannerheight_vm'])) $ret .= $banner_array['bannerheight_vm'];
                                                        $ret .= "\" />
                                                    </div><!--ls-relative-->
                                                </td>
                                                </tr></table>
                                            </div><!--bimagehide-->
                                        </div><!--ls_box_content-->
                                    </div><!--ls_metabox2-->
                                    <div class=\"ls_metabox_manage\">
                                        <ul>
                                            <li class=\"limman_{$n}";$ret.=(!$banner_array['bannertype'] || (!$banner_array['bannertype'] && !$attachment_id) || $banner_array['bannertype'] == 'image')?" act":"";$ret.="\"><a class=\"lmman lmman_{$n} ls-type-image{$lmman_dis}\" href=\"#lup_{$n}\"></a></li>
                                            <li class=\"limman_{$n}";$ret.=($banner_array['bannertype'] == 'text')?" act":"";$ret.="\"><a class=\"lmman lmman_{$n} ls-type-text{$lmman_dis}\" href=\"#lto_{$n}\"></a></li>
                                            <!--li class=\"limman_{$n}\"><a class=\"lmman lmman_{$n} ls-uploadmovie{$lmman_dis}\" href=\"#lum_{$n}\"></a></li-->
                                            <li class=\"limman_{$n}";$ret.=($banner_array['bannertype'] == 'youtube')?" act":"";$ret.="\"><a class=\"lmman lmman_{$n} ls-type-youtube{$lmman_dis}\" href=\"#lyt_{$n}\"></a></li>
                                            <li class=\"limman_{$n}";$ret.=($banner_array['bannertype'] == 'vimeo')?" act":"";$ret.="\"><a class=\"lmman lmman_{$n} ls-type-vimeo{$lmman_dis}\" href=\"#lvm_{$n}\"></a></li>
                                        </ul><div class=\"clear\"></div>
                                    </div><!--ls_metabox_manage-->
                                    <input type=\"hidden\" id=\"ls_image_mu_{$slidernum}_{$n}\" name=\"ls_image_mu[{$slidernum}][]\" value=\"{$attachment_id}\" />
                                    <input type=\"hidden\" id=\"bannertype_{$n}\" name=\"binfo[{$slidernum}][bannertype][]\" value=\"";
                                    $ret .= (!empty($banner_array['bannertype']))?$banner_array['bannertype']:"image";
                                    $ret .= "\" />
                                </div><!--bimage_outer-->
                                <div class=\"ls_metabox2 ls_rounded ls_shadow ls_thumb_box\" id=\"ls_thumb_box_{$attachment_thumb_id}\"";
                                    if(!$attachment_thumb_id && !$has_thumb) $ret .= " style=\"display:none\"";
                                    $ret .= ">
                                    <div class=\"ls_box_header\"><span class=\"ls_title\">".sprintf(__("Banner %d thumbnail", 'lenslider'), $nn)."</span></div>
                                    <div class=\"ls_box_content\">
                                        <div class=\"timge_div\" id=\"timge_image_div_{$slidernum}_{$n}\">
                                            <div class=\"abs bload2 ls_media_thumb_abs_{$slidernum}_{$n}\" style=\"display:none\"></div>
                                            <a class=\"ls_thumb_cont b_dashed ls_media_thumb_upload\" id=\"ls_media_thumb_upload_{$slidernum}_{$n}\"";
                                            if($attachment_thumb_id) $ret .= " style=\"display:none\"";
                                            $ret .= " href=\"javascript:;\"></a>
                                            <div id=\"ls-cont-thumb-media-mu-{$n}\"";
                                                if(!$attachment_thumb_id) $ret .= " style=\"display:none;\"";
                                                $ret .= ">";
                                                if($attachment_thumb_id) $ret .= "<div class=\"ls_box_cont_abs\"><a class=\"a_ls_thumb_del\" id=\"ls_box_thumb_del_{$slidernum}_{$n}_{$attachment_id}_{$attachment_thumb_id}\" href=\"javascript:;\">".__('delete', 'lenslider')."</a></div><a href=\"{$img_thumb}\" class=\"thickbox\"><img src=\"{$img_thumb}\" height=\"94\" /></a>";
                                                $ret .= "
                                            </div>
                                        </div>
                                    </div><!--ls_box_content-->
                                </div><!--ls_thumb_box-->
                                <input type=\"hidden\" id=\"ls_image_thumb_mu_{$slidernum}_{$n}\" name=\"ls_image_thumb_mu[{$slidernum}][]\" value=\"{$attachment_thumb_id}\" />
                            </td>
                            <td valign=\"top\">";
                                $array = (!$array)?$this->_lenslider_make_default_fields_array():$array;
                                $ret  .= $this->_lenslider_banner_item_add($slidernum, $n, $array, $array_merge, $array_unset, $attachment_id, $url_type, $url_type_id, $title_type, $title_type_id);
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
}?>