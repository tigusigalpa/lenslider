var smart_energy_lenslider_fn = function(inparr) {
    jQuery(".ls_sm_en_ib_manage li").hover(
        function() {
            jQuery(this).addClass("hvr");
        },
        function() {
            jQuery(this).removeClass("hvr");
        }
    );
    jQuery(".ls_sm_en_ib_manage").prepend("<div class=\"labs\"></div>");
    jQuery(".ls_sm_en_ibanner").tabs(
        {
            fx:{opacity:"toggle"},
            select: function(event, ui) {
                jQuery(".labs").animate({'margin-left':ui.index*240}, 200);
            },
            show: function(event, ui) {
                jQuery(".ls_sm_en_ibanner_overlay").hide();
                jQuery("#ibo_"+ui.index).fadeIn(300);
            }
        }
    ).tabs("rotate", inparr.slideshow.ls_autoplay_delay);
}