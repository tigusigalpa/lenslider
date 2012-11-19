jQuery(document).ready(function() {
    //jQuery(".ibanner_banner:first").show();
    jQuery(".ibanner_outer").hover(
        function() {
            jQuery(this).find(".ibanner_nav.left").stop().show(50).animate({'left':-50},50);
            jQuery(this).find(".ibanner_nav.right").stop().show(50).animate({'right':-50},50);
        },
        function() {
            jQuery(this).find(".ibanner_nav.left").stop().hide(50).animate({'left':-100},50);
            jQuery(this).find(".ibanner_nav.right").stop().hide(50).animate({'right':-100},50);
        }
    );
    
    var cur=0;
    var totWidth=0;
    var positions = new Array();
    jQuery(".ibanner_banner").each(function(i) {
        positions[i]= totWidth;
        totWidth += jQuery(this).width();
        if(!jQuery(this).width()) {
            alert("Please, fill in width & height for all your images!");
            return false;
        }
    });
    //jQuery(".ibanner").width(totWidth);
    jQuery(".ibanner_nav a").click(function(e) {
        cur++;
        jQuery(".ibanner_banner").stop().animate({
            marginLeft:-positions[cur]+'px'
        },990);
        e.preventDefault();
    });
    //jQuery('#menu_sl ul li.menuItem:first').addClass('act').siblings().addClass('inact');
});