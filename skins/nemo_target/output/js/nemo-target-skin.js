var nemo_target_lenslider_fn = function(inparr) {
    if(parseInt(inparr.slideshow.ls_has_autoplay) > 0 && parseInt(inparr.slideshow.ls_autoplay_delay) > 0) {
        jQuery(".ls_nemotar_ibanner").tabs({
            fx:{opacity:"toggle"}
        }).tabs("rotate", inparr.slideshow.ls_autoplay_delay);
    } else {
        jQuery(".ls_nemotar_ibanner").tabs({
            fx:{opacity:"toggle"}
        });
    }
}