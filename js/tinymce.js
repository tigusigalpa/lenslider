(function() {
    tinymce.PluginManager.requireLangPack('len-slider');
    tinymce.create('tinymce.plugins.LenSliderPlugin', {
        init : function(ed, url) {
            url = url.replace(/wp-admin/, '');
            ed.addButton('len-slider', {
                title : 'lenslider.title',
                image :  url+'wp-content/plugins/len-slider/images/tinymce_button.png',
                cmd   : 'lenslider'
            });
            ed.addCommand('lenslider', function() {
                ed.windowManager.open({
                    file       : url+'wp-content/plugins/len-slider/tinymce.php',
                    width      : 350,
                    height     : 120,
                    inline     : 1,
                    resizable  : "yes",
                    scrollbars : "yes"
                }, {
                    plugin_url : url
                });
            });
        },
        
        createControl : function(n, cm) {
            return null;
        },
        
        getInfo : function() {
            return {
                longname  : 'LenSlider shortcode insert',
                author    : 'Igor Sazonov',
                authorurl : 'http://www.lenslider.com/',
                infourl   : 'http://www.lenslider.com/',
                version   : '1.1.1'
            };
        }
    });
    tinymce.PluginManager.add('lenslider', tinymce.plugins.LenSliderPlugin);
})();