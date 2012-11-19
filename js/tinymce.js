(function() {
    tinymce.PluginManager.requireLangPack('lenslider');
    tinymce.create('tinymce.plugins.LenSliderPlugin', {
        init : function(ed, url) {
            url = url.replace(/\/js/, '');
            ed.addButton('lenslider', {
                title : 'lenslider.title',
                image : url+'/images/tinymce_button.png',
                cmd   : 'lenslider'
            });
            ed.addCommand('lenslider', function() {
                ed.windowManager.open({
                    file       : url+'/tinymce.php',
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
                authorurl : 'http://lenslider.com/',
                infourl   : 'http://lenslider.com/',
                version   : '1.0'
            };
        }
    });
    tinymce.PluginManager.add('lenslider', tinymce.plugins.LenSliderPlugin);
})();