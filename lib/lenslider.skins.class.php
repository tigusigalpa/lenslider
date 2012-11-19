<?php
class LenSliderSkins extends LenSlider {
    private static $_skinsCatalog  = 'skins';
    static $settings_file = 'settings.xml';
    static $skinFilePrf   = 'ls_skin';

    public function __construct() {
        parent::__construct();
    }
    
    protected static function _lenslider_skins_abspath() {
        return strtolower(ABSPATH.PLUGINDIR."/".parent::$_pluginName."/".self::$_skinsCatalog);
    }
    
    protected static function _lenslider_skins_httppath() {
        return strtolower(LenSlider::$siteurl."/".PLUGINDIR."/".parent::$_pluginName."/".self::$_skinsCatalog);
    }

    public static function lenslider_is_skin($skin_name, $path_to_skins_folder = false) {
        if($skin_name != LenSlider::$defaultSkin) {
            if(!$path_to_skins_folder) $path_to_skins_folder = self::_lenslider_skins_abspath();
            if(file_exists(strtolower("{$path_to_skins_folder}/{$skin_name}/".self::$settings_file))) return true;
            return false;
        } else return true;
    }

    protected static function _lenslider_skin_exists($skin_name, $path_to_skins_folder = false) {
        if(!$path_to_skins_folder) $path_to_skins_folder = self::_lenslider_skins_abspath();
        if(file_exists(strtolower("{$path_to_skins_folder}/{$skin_name}"))) return true;
        return false;
    }

    public static function lenslider_get_skin_params_object($skin_name) {
        if(self::lenslider_is_skin($skin_name)) {
            $retObj = new stdClass;
            if($skin_name != LenSlider::$defaultSkin) {
                $retObj->absPath    = self::_lenslider_skins_abspath()."/{$skin_name}";
                $retObj->httpPath   = str_ireplace(ABSPATH, LenSlider::$siteurl."/", $retObj->absPath);
                $retObj->imagesPath = str_ireplace(ABSPATH, LenSlider::$siteurl."/", $retObj->absPath."/images");
                $retObj->cssFiles   = glob($retObj->absPath."/output/css/*.css");
                $retObj->jsFiles    = glob($retObj->absPath."/output/js/*.js");
            } else {
                $retObj->cssFiles = array(strtolower(LenSlider::$siteurl."/".PLUGINDIR."/".parent::$_pluginName)."/css/defaultskin.css");
                $retObj->jsFiles  = array(strtolower(LenSlider::$siteurl."/".PLUGINDIR."/".parent::$_pluginName)."/js/default-skin-custom.js");
            }
            return $retObj;
        }
    }
    
    public static function lenslider_skins_folders_array($default_skin = true) {
        $ret_array = array();
        $path_to_skins_folder = self::_lenslider_skins_abspath();
        $dir = opendir($path_to_skins_folder);
        while(($folder = readdir($dir)) !== false) {
            if($folder[0] == '.' || is_file($folder) || $folder == parent::$defaultSkin.".html" || $folder == parent::$defaultSkin) continue;
            if(self::lenslider_is_skin($folder, &$path_to_skins_folder) && is_object(@simplexml_load_file("{$path_to_skins_folder}/{$folder}/".self::$settings_file)->maindata)) $ret_array[] = $folder;
        }
        if($default_skin) $ret_array[] = parent::$defaultSkin;
        asort($ret_array);
        return $ret_array;
    }
    
    protected static function _lenslider_skin_merge_array($merge_array, $slider_array) {
        if(!empty($merge_array) && is_array($merge_array)) {
            foreach ($merge_array as $k=>$v) {
                $v['value'] = @$slider_array[$k];
                $merge_array[$k] = $v;
            }
            return $merge_array;
        }
    }
    
    protected static function _lenslider_skin_xml_object($skin_name) {
        $settings_path = self::_lenslider_skins_abspath()."/{$skin_name}/".self::$settings_file;
        return simplexml_load_file(&$settings_path)->maindata;
    }
    
    protected static function _lenslider_skin_default_js_scripts_array($skin_name) {
        return explode(";", self::_lenslider_skin_xml_object($skin_name)->wpscripts);
    }

    public function lenslider_skin_item($skin) {
        if(self::lenslider_is_skin($skin)) {
            $xml_obj    = self::_lenslider_skin_xml_object($skin);
            $used_skins = parent::lenslider_get_enabled_skins_array_names();
            $is_used = (is_array($used_skins) && !in_array($skin, $used_skins))?false:true;?>
                                <li class="skinli">
                                    <table border="0" width="100%">
                                        <tr>
                                            <td width="122" valign="top">
                                                <?php
                                                $skin_logo_abs = self::_lenslider_skins_abspath()."/{$skin}"."/images/logo.jpg";
                                                if(file_exists($skin_logo_abs)) {?>
                                                <img class="ls_rounded" width="100" src="<?=str_ireplace(ABSPATH, LenSlider::$siteurl."/", $skin_logo_abs);?>" />
                                                <?php }?>
                                            </td>
                                            <td valign="top">
                                                <div style="position:relative;padding-bottom:10px;">
                                                    <span class="title"><?=$xml_obj->name?></span>
                                                    <?php if(!empty($xml_obj->description)) {?><p><?=$xml_obj->description?></p><?php }?>
                                                    <p><?php _e("Skin files location:", 'lenslider')?> <code><?="/".self::$_skinsCatalog."/{$skin}/"?></code></p>
                                                    <!--div class="ls_meta_data"><?php _e("Date:", 'lenslider')?> <?=$xml_obj->date?> | <?php _e("Author:", 'lenslider')?> <?=$xml_obj->author?> | <?php _e("Version:", 'lenslider')?> <?=$xml_obj->version?> | <?php _e("url:", 'lenslider')?> <a href="<?=esc_url($xml_obj->url)?>" target="_blank"><?=$xml_obj->urltitle?></a></div-->
                                                    <div class="ls_theme_min_buttons">
                                                        <ul>
                                                            <!--li><a class="ls_min_a ls_rounded_small" href="#"><?php _e("view", 'lenslider')?></a></li-->
                                                            <li><a class="ls_min_a ls_min_a_del ls_rounded_small<?php if(!$is_used) echo " skin_allow_delete";?>" id="skin_<?=$skin?>" href="<?php echo (!$is_used)?"javascript:;":"javascript:alert(&quot;".__("You couldn't delete uses skin!", 'lenslider')."&quot;);";?>"><?php _e("Delete", 'lenslider')?></a></li>
                                                        </ul><div class="clear"></div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </li>
                                <?php
        }//file_exists
    }
    
    public static function lenslider_skins_dropdown($select_name, $check = false, $disabled = false, $options = "") {
        if(!$check) $check = parent::$defaultSkin;
        $skins = self::lenslider_skins_folders_array();
        if(!empty($skins) && is_array($skins)) {
            $return  = "<select name=\"{$select_name}\" {$options}";
            if($disabled) $return .= " disabled=\"disabled\"";
            $return .= ">";
            foreach ($skins as $skin) {
                $return .= "<option value=\"{$skin}\"".selected($skin, $check, false).">{$skin}</option>";
            }
            $return .= "</select>";
            if($disabled) $return .= "<input type=\"hidden\" name=\"{$select_name}\" value=\"{$check}\" />";
        }
        return $return;
    }
    
    //проверить на существование скина
    public function lenslider_unzip_skin($file) {
        $redir_url = "{$this->_requestSkinsURI}&message=1";
        $filename_array = explode(".", $file['name']);
        if($this->lenslider_is_needle_mime_type($file, array('application/zip',         'application/x-zip',
                                                        'application/x-zip-compressed', 'application/octet-stream',
                                                        'application/x-compress',       'application/x-compressed',
                                                        'multipart/x-zip'),
                                                    array('zip')) && $filename_array[1] == self::$skinFilePrf && !empty($file['tmp_name'])) {
            $skins = self::lenslider_skins_folders_array();
            if(!in_array($filename_array[0], $skins)) {
                if(class_exists('ZipArchive')) {
                    $zip = new ZipArchive;
                    $res = $zip->open($file['tmp_name']);
                    if($res === true) {
                        if($filename_array[0] != LenSlider::$defaultSkin) {
                            $maybe_path = self::_lenslider_skins_abspath()."/".$filename_array[0]."/";
                            if(!file_exists($maybe_path)) {
                                mkdir($maybe_path, 0755);
                                $zip->extractTo($maybe_path);
                                $zip->close();
                                unset($_POST);
                                unset($_FILES);
                                wp_safe_redirect("{$redir_url}");
                                exit;
                            } else wp_safe_redirect("{$redir_url}&error=1");
                        } else wp_safe_redirect("{$redir_url}&error=2");
                    } else wp_safe_redirect("{$redir_url}&error=3");
                } else wp_safe_redirect("{$redir_url}&error=4");
            } else wp_safe_redirect("{$redir_url}&error=5");
        } else wp_safe_redirect("{$redir_url}&error=6");
    }
    
    private static function _lenslider_delete_dir($dir) {
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
    
    public static function lenslider_delete_skin($skin_name) {
        if(!in_array($skin_name, parent::lenslider_get_enabled_skins_array_names())) return (self::_lenslider_delete_dir(self::_lenslider_skins_abspath()."/{$skin_name}"))?true:false;
        return false;
    }
}

?>