<?php
if(!class_exists('WP_List_Table')) require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );

class LenSlider_List_Table extends WP_List_Table {
    protected static $_requestSliderURI;
    protected static $_requestIndexURI;
    protected static $_indexFile;

    public function __construct() {
        parent::__construct(array(
            'singular'  => 'slider',
            'plural'    => 'sliders',
            'ajax'      => false
        ));
        self::$_requestSliderURI = admin_url("admin.php?page=".LenSlider::$sliderPage);
        self::$_requestIndexURI  = admin_url("admin.php?page=".LenSlider::$indexPage);
        self::$_indexFile        = ABSPATH.PLUGINDIR."/".LenSlider::$indexPage;
    }
    
    public function column_default($item, $column_name){
        switch($column_name){
            case 'skin':
            case 'shortcode':
            case 'prev':
                return $item[$column_name];
            default:
                return print_r($item,true);
        }
    }
    
    public function column_title($item){
        
        $disabled = (!empty($item['disabled']))?" class=\"ls_disabled\"":"";
        $actions = array(
            'edit'      => sprintf("<a href=\"%s&slidernum=%s&skin=%s\">%s</a>", self::$_requestSliderURI, $item['ID'], $item['skin'], __( 'Edit', 'lenslider' )),
            'duplicate' => sprintf("<a href=\"%s&action=dupslider&in_slidernum=%s&out_slidernum=%s&noheader=true\">%s</a>", self::$_requestIndexURI, $item['ID'], LenSlider::lenslider_hash(), __( 'Duplicate', 'lenslider' )),
            'delete'    => sprintf("<a class=\"ls-deletion\" href=\"".self::$_requestIndexURI."&action=delslider&slidernum=%s&noheader=true\">%s</a>", $item['ID'], __( 'Delete', 'lenslider' ))
        );
        
        $ret = "<a{$disabled} href=\"".self::$_requestSliderURI."&slidernum={$item['ID']}&skin={$item['skin']}\">{$item['ID']}</a> ";
        $ret .= (!empty($item['title']))?"<span style=\"color:black;font-weight:bold\">(".$item['title'].")</span>":"<span style=\"color:silver\">(".__('Title not provided', 'lenslider').")</span>";
        $ret .= $this->row_actions($actions);
        return $ret;
    }
    
    public function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            $this->_args['singular'],
            $item['ID']
        );
    }
    
    public function get_columns(){
        $columns = array(
            'cb'        => '<input type="checkbox" />',
            'title'     => __('Slider',    'len-slider'),
            'skin'      => __('Skin',      'len-slider'),
            'shortcode' => __('Shortcode', 'lenslider'),
            'prev'      => __('Preview',   'len-slider')
        );
        return $columns;
    }
    
    /*public function get_sortable_columns() {
        $sortable_columns = array(
            //'title'     => array('title',false),     //true means it's already sorted
            'banners_count'    => array('banners_count',false)
        );
        return $sortable_columns;
    }*/
    
    public function get_bulk_actions() {
        $actions = array(
            'delete'  => __('Delete', 'lenslider'),
            'disable' => __('Disable', 'lenslider'),
            'enable'  => __('Enable', 'lenslider')
        );
        return $actions;
    }
    
    public function process_bulk_action() {
        
        if( 'delete'===$this->current_action() ) {
            if(!empty($_GET['slider']) && is_array($_GET['slider'])) {
                $ls = new LenSlider;
                foreach ($_GET['slider'] as $slidernum) {
                    if(LenSlider::lenslider_is_slider_exists($slidernum)) {
                        $ls->lenslider_delete_slider($slidernum);
                    }
                }
            }
        }
        if( 'disable'===$this->current_action() ) {
            if(!empty($_GET['slider']) && is_array($_GET['slider']) && !empty($_GET['action2'])) {
                $ls = new LenSlider;
                $ls->lenslider_disen_bulk_actions($_GET['slider'], $_GET['action2']);
            }
        }
        if( 'enable'===$this->current_action() ) {
            if(!empty($_GET['slider']) && is_array($_GET['slider']) && !empty($_GET['action2'])) {
                $ls = new LenSlider;
                $ls->lenslider_disen_bulk_actions($_GET['slider'], $_GET['action2']);
            }
        }
        
    }

    public function prepare_items() {
        //global $wpdb;
        $this->_lenslider_generate_table_array();
        $per_page = 25;
        
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        
        $this->_column_headers = array($columns, $hidden, $sortable);
        
        $this->process_bulk_action();
        
        $data = $this->_lenslider_generate_table_array();
        
        $current_page = $this->get_pagenum();
        
        $total_items = count($data);
        
        $data = array_slice($data,(($current_page-1)*$per_page),$per_page);
        
        $this->items = array_reverse($data);
        
        $this->set_pagination_args( array(
            'total_items' => $total_items,
            'per_page'    => $per_page,
            'total_pages' => ceil($total_items/$per_page)
        ) );
    }
    
    
    protected function _lenslider_generate_table_array() {
        $array = LenSlider::lenslider_get_array_from_wp_options(LenSlider::$bannersOption);
        if(!empty($array) && is_array($array)) {
            $ret_array = array();
            $clipboard_swf = plugins_url('swf/clipboard.swf', self::$_indexFile);
            $clipboard_swf_image = plugins_url('images/clipboard_icon.png', self::$_indexFile);
            foreach ($array as $slider_k=>$banners) {
                $settings_array                    = $banners[LenSlider::$settingsTitle];
                $ret_array[$slider_k]['ID']        = $slider_k;
                $ret_array[$slider_k]['title']     = $settings_array[LenSlider::$sliderComment];
                $ret_array[$slider_k]['skin']      = $settings_array[LenSlider::$skinName];
                $ret_array[$slider_k]['shortcode'] = "<div class=\"ls_floatleft\"><code id=\"code_{$slider_k}\">[lenslider id=\"{$slider_k}\"]</code></div><div class=\"ls_floatleft ls_mtip\" title=\"".__('Copy to clipboard', 'lenslider')."\"><embed src=\"{$clipboard_swf}?normal={$clipboard_swf_image}&pressed={$clipboard_swf_image}&hover={$clipboard_swf_image}&clipboard=[lenslider id=&quot;{$slider_k}&quot;]\" width=\"16\" height=\"16\" type=\"application/x-shockwave-flash\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" /></div><div class=\"clear\"></div>";
                $ret_array[$slider_k]['prev']      = "<a class=\"button thickbox\" href=\"".plugins_url('ls-preview.php', self::$_indexFile)."?slidernum={$slider_k}&keepThis=true&TB_iframe=true&height=600&width=1000\">".__( 'Preview', 'lenslider' )."</a>";
                $ret_array[$slider_k]['disabled']  = (!empty($settings_array[LenSlider::$sliderDisenName]))?false:true;
            }
            return $ret_array;
        }
        return array();
    }
    
}
?>