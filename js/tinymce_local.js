var LenSliderDialog = {
    local_ed : 'ed',
    init: function(ed) {
        LenSliderDialog.local_ed = ed;
        tinyMCEPopup.resizeToInnerSize();
    },
    insert: function() {
        var slidernum = jQuery("select[name=slider_shortcode] option:selected").val();
        if(slidernum != '') {
            var output    = "[lenslider id=\""+slidernum+"\"]";
            tinyMCEPopup.execCommand('mceReplaceContent', false, output);
        }
        tinyMCEPopup.close();
        return;
    }
};
tinyMCEPopup.onInit.add(LenSliderDialog.init, LenSliderDialog);