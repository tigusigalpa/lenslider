var default_lenslider_fn = function(inparr) {
    if(parseInt(inparr.slideshow.ls_has_autoplay) > 0 && parseInt(inparr.slideshow.ls_autoplay_delay) > 0) {
        var psehover = (parseInt(inparr.slideshow.ls_hover_pause) > 0)?false:true;
        jQuery(".ls_def_ibanner").tabs().tabs("rotate", parseInt(inparr.slideshow.ls_autoplay_delay), psehover);
    } else {
        jQuery(".ls_def_ibanner").tabs().tabs("rotate", 6000, true);
    }
}