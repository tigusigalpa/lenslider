var vania_fit_lenslider_fn = function(inparr) {
    if(parseInt(inparr.slideshow.ls_has_autoplay) > 0 && parseInt(inparr.slideshow.ls_autoplay_delay) > 0) {
        jQuery(".ls_van_fit_ibanner").tabs().tabs("rotate", inparr.slideshow.ls_autoplay_delay);
    } else {
        jQuery(".ls_van_fit_ibanner").tabs();
    }
}