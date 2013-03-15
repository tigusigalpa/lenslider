(function() {
    tinymce.PluginManager.requireLangPack('lenslider');
    tinymce.create('tinymce.plugins.LenSliderPlugin', {
        init : function(ed, url) {
            var url2 = url.replace(/js/, '');
            ed.addButton('lenslider', {
                title : 'lenslider.title',
                image :  url2+'images/tinymce_button.png',
                cmd   : 'mceLenSlider'
            });
            ed.addCommand('mceLenSlider', function() {
                ed.windowManager.open({
                    file       : url2+'tinymce.php',
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
                version   : '2.0.9'
            };
        }
    });
    tinymce.PluginManager.add('lenslider', tinymce.plugins.LenSliderPlugin);
})();