var lenSliderJSReady = function($, ajaxServerURL, tipsy_check, isPluginPage, confirmText, confirmThumbText, noSkinsText, sliderComment, errTitle, errGeneral, maximizeStr, minimizeStr, skinSettingsConfirmStr, allowedUrlsArr, sliderErrStr, bannerErrStr) {
    if(isPluginPage) {
        if($("#ls_save_metabox").length) {
            var needleTop = ($("#ls_save_metabox").offset().top) - parseInt($("#wpadminbar").css('height')) - 10;
            $(window).scroll(function() {
                var scrollTop = $(window).scrollTop();
                if(scrollTop >= needleTop) {
                    $("#ls_save_metabox").addClass('ls_fixed');
                } else {
                    $("#ls_save_metabox").removeClass('ls_fixed');
                }
            });
        }
        if($(".sortable").length) {
            $(".sortable").each(function() {
                var $slidernum = $(this).attr('id').replace(/slidernum_/, '');
                var $plhldr =($.cookie("folding_"+$slidernum) == "svernuto") ? "ushmin" : "ls-state-highlight";
                $(this).sortable({
                    axis: 'y',
                    placeholder: $plhldr,
                    handle: 'h3'
                });
            });
        }
        if($(".sliders_nav").length) $(".sliders_nav").onePageNav();
        if(tipsy_check) {
            if($(".atipsy").length)   $(".atipsy").tipsy({gravity: 'e'});
            if($(".atipsy_s").length) $(".atipsy_s").tipsy({gravity: 's'});
            if($(".atipsy_w").length) $(".atipsy_w").tipsy({gravity: 'w'});
        }
    }
    
    var minLength = 3;
    $(".ls_slide_image_inner_controls ul li a").css({'opacity':.6});
    $(".ls_abs_size").css({'opacity':.5});
    $(".ls_banner_close, .ps_del").css({'opacity':.7});
    $(".ls_slide_image_inner_overlay").hide();
    //$("#post").attr("enctype", "multipart/form-data");
    $(".ls_bbutton").mouseup(function(){$(this).removeClass('push')}).mousedown(function(){$(this).addClass('push');});
    
    $("input.prevent").focus(function() {
        if($(this).val() == sliderComment) {
            $(this).val('');
            $(this).removeClass('prevent');
        }
    }).blur(function() {
        if($(this).val() == '') {
            $(this).val(sliderComment);
            if(!$(this).hasClass('prevent')) $(this).addClass('prevent');
        }
    });
    
    //Sliders tabs
    $("ul.tit_tabs li").live('click', function() {
        var $link  = $(this).find("a"),
        $slidernum = $link.attr('class').replace(/sl_tabs_/, '');
        $(".sl_content_"+$slidernum).hide();
        $($link.attr("href")).show();
        $("ul.tit_tabs_"+$slidernum+" li").removeClass("active");
        $(this).addClass("active");
        return false;
    });
    
    $(".chbx_is_thumb").live('click', function() {
        var $id = $(this).attr('id').split("_")[3];
        if($(this).prop("checked")) {
            if($("#ls_thumb_max_width_"+$id).attr("disabled") == "disabled") {
                $("#ls_thumb_max_width_"+$id).removeAttr("disabled");
                $(".tgl_thumb_"+$id).show();
            }
        } else {
            if($("#ls_thumb_max_width_"+$id).attr("disabled") != "disabled") {
                $("#ls_thumb_max_width_"+$id).attr("disabled", "disabled");
                $(".tgl_thumb_"+$id).hide();
            }
        }
    });
    
    /*$(".pls_add").live('click', function() {
        $(".pls_add_load").show();
        var $slidernum = $('select#post_slider option:selected').val();
        var $ls_post_id = $("#ls_post_id").val();
        $.post(ajaxServerURL,
            {slidernum:$slidernum, ls_post_id:$ls_post_id, act:'append_post_slider'},
            function(data) {
                $("#post_slider_append").prepend($(data.ret).fadeIn('slow'));
                //$("select#post_slider option:contains('"+$slidernum+"')").attr('disabled','disabled');
                $(".pls_add_load").hide();
            }, "json"
        );
    });*/
    
    $(".ls_slide_image_inner_controls ul li a").hover(
        function() {$(this).css({'opacity': 1});},
        function() {$(this).css({'opacity': .7});}
    );
    
    $(".ls_banner_close, .ps_del").hover(
        function() {$(this).css({'opacity':1});},
        function() {$(this).css({'opacity':.6});}
    );
    
    $(".ls_slide_image_inner").hover(
        function() {$(this).find(".ls_abs_size").fadeIn("fast");},
        function() {$(this).find(".ls_abs_size").hide();}
    );
    
    $(".handlediv").live('click', function() {
        var $thisElem = $(this).parents("li");
        if($thisElem.hasClass("min")) $thisElem.removeClass("min");
        else $thisElem.addClass("min");
    });
    
    //simple remove post banner form
    $(".ls_ps_new").live('click', function() {
        var $pre_info = $(this).attr('id').replace(/a_ls_ps_/, '').split('_');
        $("#ls_ps_"+$pre_info[0]).fadeOut(500, function() {$(this).remove();});
    });
    
    $("input.blink").live('change', function() {
        var $to_list = $.trim($(this).val()),
        $pre_info    = $(this).attr('id').replace(/blink_/, '').split('_'),
        $name        = $pre_info[0],
        $slidernum   = $pre_info[1],
        $n           = $pre_info[2],
        $banner_k    = $pre_info[3];
        $("#blink_append_"+$slidernum+"_"+$n).html('');
        if($to_list != 'blink_lsurl') {
            $("#blink_append_"+$slidernum+"_"+$n).addClass('bload2');
            $("#ls_link_"+$slidernum+"_"+$n).attr("disabled", "disabled");
        } else $("#ls_link_"+$slidernum+"_"+$n).removeAttr("disabled");
        $.post(ajaxServerURL,
            {slidernum:$slidernum,name:$name,n:$n,banner_k:$banner_k,to_list:$to_list,act:'links_variants'},
            function(data) {
                if($to_list != 'blink_lsurl') {
                    $("#blink_append_"+$slidernum+"_"+$n).removeClass('bload2');
                    if(data.ret != '') $("#blink_append_"+$slidernum+"_"+$n).html(data.ret);
                    else $("#blink_append_"+$slidernum+"_"+$n).html('');
                    $("#post_hidden_uth_ls_link_"+$slidernum+"_"+$n).html(data.uth);
                } else {
                    $("#post_hidden_uth_ls_link_"+$slidernum+"_"+$n).html(data.uth);
                    $("#post_hidden_ls_link_"+$slidernum+"_"+$n).html('');
                    $("#ls_link_"+$slidernum+"_"+$n).removeAttr("disabled").attr('name', 'binfo['+$slidernum+'][ls_link][]');
                    $("#url_type_id_"+$slidernum+"_"+$n).find('input').val('');
                }
            }, "json"
        );
    });
    
    $(".ls_post_url select").live('change', function() {
        var $pre_info = $(this).attr('id').replace(/blink_select_/, '').split('_'),
        $slidernum    = $pre_info[0],
        $banner_k     = $pre_info[1],
        $n            = $pre_info[2],
        $id           = parseInt($(this).val()),
        $url_type     = $("#post_hidden_uth_ls_link_"+$slidernum+"_"+$n+" input").val();
        $.post(ajaxServerURL,
            {slidernum:$slidernum,n:$n,id:$id,banner_k:$banner_k,url_type:$url_type,act:'link_variants_url'},
            function(data) {
                $("#ls_link_"+$slidernum+"_"+$n).removeAttr("name").val(data.url);
                $("#url_type_id_"+$slidernum+"_"+$n).html(data.uti);
                $("#post_hidden_ls_link_"+$slidernum+"_"+$n).html(data.ret);
            }, "json"
        );
    });
    
    //delete post banner
    $(".ls_ps_todel").live('click', function() {
        if(confirm(confirmText)) {
            var $pre_info = $(this).attr('id').replace(/a_ls_ps_/, '').split('_'),
            $post_id      = $pre_info[1],
            $slidernum    = $pre_info[0],
            $att_id       = $pre_info[2],
            $att_thumb_id = $pre_info[3];
            $("#del_"+$slidernum).hide();
            $("#del2_"+$slidernum).show();
            $.post(ajaxServerURL,
                {slidernum:$slidernum, post_id:$post_id, att_id:$att_id, att_thumb_id:$att_thumb_id, act:'delete_post_slider'},
                function(data) {
                    if(data.del_ret) $("#ls_ps_"+$slidernum).fadeOut(500, function() {$(this).remove();});
                    else {
                        $("#del_"+$slidernum).show();
                        $("#del2_"+$slidernum).hide();
                    }
                }, "json"
            );
        }
    });
    
    $(".swskin").live('change', function() {
        var $slidernum = $(this).attr('class').split(' ')[1].replace(/swskin_/, ''),
        $skin_name     = $(this).val();//skin name
        $(".swskin_"+$slidernum).val($skin_name);
        if(confirm(skinSettingsConfirmStr)) {
            $.cookie('skin_set_'+$slidernum, $skin_name);
        }
    });
    
    //Set global settings for slider custom settings
    $(".set_global_set_sldr").live('click', function() {
        var $slidernum = $(this).attr('id').replace(/set_glob_/, ''),
        $this_li       = $(this).parent("div");
        $this_li.addClass("bload");
        $.post(ajaxServerURL,
            {act:'get_settings_global'},
            function(data) {
                $(".ls_images_maxsize_" +$slidernum).val(data.ret.ls_images_maxsize);
                $(".ls_images_maxwidth_"+$slidernum).val(data.ret.ls_images_maxwidth);
                $(".ls_banners_limit_"  +$slidernum).val(data.ret.ls_banners_limit);
                $(".ls_slider_images_quality_" +$slidernum).val(data.ret.ls_slider_images_quality);
                $this_li.removeClass("bload");
                $("#set_local_"+$slidernum).show();
            }, "json"
        );
    });
    
    //Return local slider settings
    $(".set_local_set_sldr").live('click', function() {
        var $slidernum = $(this).attr('id').replace(/set_local_/, ''),
        $this_li       = $(this).parent("div");
        $this_li.addClass("bload");
        $.post(ajaxServerURL,
            {slidernum:$slidernum, act:'get_settings_local'},
            function(data) {
                $(".ls_images_maxsize_" +$slidernum).val(data.ret.ls_images_maxsize);
                $(".ls_images_maxwidth_"+$slidernum).val(data.ret.ls_images_maxwidth);
                $(".ls_banners_limit_"  +$slidernum).val(data.ret.ls_banners_limit);
                $(".ls_slider_images_quality_" +$slidernum).val(data.ret.ls_slider_images_quality);
                $this_li.removeClass("bload");
                $("#set_local_"+$slidernum).hide();
            }, "json"
        );
    });
    
    //Set skin settings for slider
    $(".set_skin_set_sldr").live('click', function() {
        var $slidernum = $(this).attr('id').replace(/set_skin_/, ''),
        $this_li       = $(this).parent("div");
        $this_li.addClass("bload");
        $.post(ajaxServerURL,
            {slidernum:$slidernum, act:'get_settings_skin'},
            function(data) {
                $(".ls_images_maxsize_" +$slidernum).val(data.ret.ls_images_maxsize);
                $(".ls_images_maxwidth_"+$slidernum).val(data.ret.ls_images_maxwidth);
                $(".ls_banners_limit_"  +$slidernum).val(data.ret.ls_banners_limit);
                $(".ls_slider_images_quality_" +$slidernum).val(data.ret.ls_slider_images_quality);
                $this_li.removeClass("bload");
            }, "json"
        );
    });
    
    //Delete image
    $(".ls_slide_image_inner_controls ul li a.c_del").live('click', function() {
        var $delThumb = (confirm(confirmThumbText))?true:false,
        $thisarr      = $(this).attr('id').replace(/mbgdel_/, '').split("_"),
        $this_id      = $thisarr[0],
        $thumb_id     = $thisarr[1],
        $slidernum    = $thisarr[2];
        //$post_id      = $thisarr[3];
        $(".c_del, .c_thdel").hide();
        if($delThumb) {
            $("#overlay_"+$this_id).show();
            if($thumb_id) $("#overlay_"+$thumb_id).show();
            setTimeout(function () {
                $.post(ajaxServerURL,
                    {attachment_id:$this_id, thumb_del:$delThumb, thumb_id:$thumb_id, slidernum:$slidernum/*, post_id:$post_id*/, act:'del_image'},
                    function(data) {
                        if(data.del_ret == true) {
                            $(".c_del, .c_thdel").show();
                            $("#overlay_"+$this_id).hide();
                            $("#delatt_"+$this_id).val('');
                            $("#slide_image_"+$this_id).remove();
                            if($delThumb) {
                                $("#overlay_"+$thumb_id).hide();
                                $("#delthatt_"+$thumb_id).val('');
                                $("#slide_image_thumb_"+$thumb_id).remove();
                            }
                        }
                    }, "json"
                );
            }, 500);
        }
        return false;
    });
    
    //Delete thumb
    $(".ls_slide_image_inner_controls ul li a.c_thdel").live('click', function() {
        if(confirm(confirmText)) {
            var $thisarr = $(this).attr('id').replace(/mbgthdel_/, '').split("_"),
            $this_id     = $thisarr[0],
            $thumb_id    = $thisarr[1],
            $slidernum   = $thisarr[2];
            //$post_id     = $thisarr[3];
            $(".c_del, .c_thdel").hide();
            $("#overlay_"+$thumb_id).show();
            setTimeout(function () {
                $.post(ajaxServerURL,
                    {attachment_id:$this_id, thumb_id:$thumb_id, slidernum:$slidernum/*, post_id:$post_id*/, act:'del_thumb'},
                    function(data) {
                        if(data.del_ret == true) {
                            $(".c_del, .c_thdel").show();
                            $("#overlay_"+$thumb_id).hide();
                            $("#delthatt_"+$thumb_id).val('');
                            $("#slide_image_thumb_"+$thumb_id).remove();
                        }
                    }, "json"
                );
            }, 500);
            return false;
        }
    });
    
    //Delete banner
    $(".liveajaxbdel").live('click', function() {
        if(confirm(confirmText)) {
            var $slidernum = $(this).parents("ul.sortable:first").attr('id').replace(/slidernum_/, ''),
            $this_arr      = $(this).attr('id').replace(/liveajaxbdel_/, '').split("_"),
            $this_id       = $this_arr[0],
            $thumb_id      = $this_arr[1],
            $post_id       = $this_arr[2];
            $(".c_del").hide();
            $("#boverlay_"+$this_id).show();
            setTimeout(function () {
                $.post(ajaxServerURL,
                    {banner_id:$this_id, thumb_id:$thumb_id, slidernum:$slidernum, post_id:$post_id, act:'del_banner'},
                    function(data) {
                        if(data.del_ret == true) {
                            $("#bitem_"+$this_id).remove();
                            $(".c_del").show();
                            if($("#slidernum_"+$slidernum+" li").length == 0) {
                                if($slidernum == 0) addBannerAjax($slidernum, 0);
                                else $("#slidernum_"+$slidernum).parents(".ls_metabox").remove();
                            }
                        }
                    }, "json"
                );
            }, 500);
        }
        return false;
    });
    
    //Ajax added banner form delete
    $(".livebdel").live('click', function() {
        $(this).parents("li").fadeOut(500, function() {$(this).remove();});
        return false;
    });
    
    //Ajax added slider form delete
    $(".slremove").live('click', function() {
        var $sliders_length = ($(".ls_metabox").length)-1;
        $(this).parents(".ls_metabox").remove();
        if($sliders_length <= 0) addSliderAjax(0);
        return false;
    });
    
    var addSliderAjax = function(count_sliders, removeEl, skin_name) {
        $.post(ajaxServerURL,
            {count_sliders:count_sliders, skin_name:skin_name, act:'add_slider'},
            function(data) {
                if(count_sliders <= data.sliders_limit && data.slider_item != false) {
                    if(removeEl) removeEl.removeClass("bload");
                    $('#lensliders').append($(data.slider_item).fadeIn('slow'));
                    scrollToAnchor('slider_metabox_'+count_sliders);
                } else if(removeEl) removeEl.removeClass("bload");
            }, "json"
        );
    };
    
    //Delete slider
    $(".slajaxdel").live('click', function() {
        if(confirm(confirmText)) {
            var $sliders_length = $(".ls_metabox").length,
            $slidernum = $(this).attr("id").replace(/delslider_/, '');
            $.post(ajaxServerURL,
                {slidernum:$slidernum, act:'del_slider'},
                function(data) {
                    if(data.del_ret == true) {
                        $(".slnum_"+$slidernum).fadeTo(2000, 0, function() {
                            $(this).remove();
                        });
                        $("#sliders_nav_li_"+$slidernum).fadeTo(2000, 0, function() {
                            $(this).remove();
                        });
                        jDelCookie('folding_'+$slidernum);
                        $sliders_length--;
                        if($sliders_length <= 0) addSliderAjax(0);
                    }
                }, "json"
            );
        }
    });
    
    //Add new slider form
    $(".add_slider").live('click', function() {
        var $this_parent = $(this).parent("div"),
        $skin_name       = $('select[name=slider_ajax_skins] option:selected').val(),
        $count_sliders   = $(".ls_metabox").length;
        $this_parent.addClass("bload");
        setTimeout(function () {
            addSliderAjax($count_sliders, $this_parent, $skin_name);
            $count_sliders++;
        }, 200);
    });
    
    //Add new banner form
    $(".add_banner").live('click', function() {
        var $this_parent = $(this).parent("div"),
        $slidernum       = $(this).attr('id').replace(/banner_slider_/, ''),
        $skin_name       = $('input:hidden[name=slider_skin_name_'+$slidernum+']').val(),
        $count_banners   = $("#slidernum_"+$slidernum+" li.bitem").length;
        $this_parent.addClass("bload");
        $(".ls_box_content_"+$slidernum).hide();
        $("#banners_"+$slidernum).show();
        $("ul.tit_tabs_"+$slidernum+" li").removeClass("active");
        $(".first_li_"+$slidernum).addClass("active");
        setTimeout(function () {
            addBannerAjax($slidernum, $count_banners, $this_parent, $skin_name);
            $count_banners++;
        }, 200);
    });
    
    var addBannerAjax = function(slidernum, count_banners, removeEl, skin_name) {
        $.post(ajaxServerURL,
            {count_banners:count_banners, slidernum:slidernum, skin_name:skin_name, act:'add_banner'},
            function(data) {
                if(count_banners <= data.banners_limit && data.banner_item != false) {
                    if(removeEl) removeEl.removeClass("bload");
                    $('#slidernum_'+slidernum).append($(data.banner_item).fadeIn('slow'));
                    scrollToAnchor('anchor_'+slidernum+count_banners);
                } else if(removeEl) removeEl.removeClass("bload");
            }, "json"
        );
        return false;
    };
    
    //Checking values while form submiting
    $("#ls_form").submit(function() {
        //var $errors = new Object();
        //var $i=0;
        $(".tcheck").each(function() {
            //var $slidernum = $(this).attr('id').split("_")[2];
            //var $bannernum = parseInt($(this).attr('id').split("_")[3])+1;
            $(this).removeClass('txt_error');
            var this_val = $(this).val().toString();
            //$errors[$slidernum] = new Object();
            //$errors[$slidernum][$bannernum] = new Object();
            //$errors[$slidernum][$bannernum][$i] = new Array();
            //var $n=0;
            if(this_val == '') {
                $(this).addClass('txt_error');
                //$errors[$slidernum][$bannernum][$i][$n] = "Field #"+$name+" is empty<br />";
                //$n++;
            }
            if(this_val.length < minLength) {
                $(this).addClass('txt_error');
                //$errors[$slidernum][$bannernum][$i][$n] = "Value of field #"+$name+" less than "+minLength+"<br />";
                //$n++;
            }
            var $name = $(this).attr("id").split('_')[1];
            if($name == 'link') {
                if(!isValidUrl(this_val)) $(this).addClass('txt_error');
                else $(this).removeClass('txt_error');
                //$errors[$slidernum][$bannernum][$i][$n] = "Field #"+$name+" did not pass validation<br />";
                //$n++;
            }
            //$i++;
        });
        if($(".ls_maxinput").hasClass('txt_error')) {
            /*var $ret = '';
            if($.isPlainObject($errors)) {
                //sliders
                $.each($errors, function(sl_index, b_arr) {
                    $ret += "<br /><strong>"+lsReplace(sliderErrStr, sl_index)+"</strong><br />";
                    if($.isPlainObject(b_arr)) {
                        //banners
                        $.each(b_arr, function(b_index, banner_fields) {
                            $ret += "<br />"+lsReplace(bannerErrStr, parseInt(b_index))+"<br />";
                            if($.isPlainObject(banner_fields)) {
                                //fields
                                $.each(banner_fields, function(field_index, error_arr) {
                                    if($.isArray(error_arr)) {
                                        $ret += "Field #"+field_index+" errors<br />";
                                        //err_num
                                        $.each(error_arr, function(index, err) {
                                            $ret += "&mdash;&nbsp;<em>"+err+"</em>";
                                        });
                                    }
                                });
                            }
                        });
                    }
                });
            }*/
            //if($ret != '') {
                jAlert(errGeneral, errTitle);
                return false;
            //}
        }
        else $(this).submit();
    });
    
    $("#ls_slins_submit").submit(function() {
        if($(this).find("input[type=file]").val()!='') $(this).submit();
        else return false;
    });
    
    //Slide all blocks
    $(".utm_ul a.sl_banners").live('click', function() {
        var $slidernum = $(this).attr("id").replace(/sl_banner_/, ''),
        $thisElem      = $(this);
        if($thisElem.find("span").hasClass("minus")) {
            //Razvernuto - svora4ivaem
            $.cookie('folding_'+$slidernum, 'svernuto', {expires: 2});
            $("#slidernum_"+$slidernum+" li").addClass("min");
            $("#slidernum_"+$slidernum).sortable("option", "placeholder", 'ushmin');
            $thisElem.find("span").removeClass("minus").addClass("plus").html(maximizeStr);
            
            $("#ls_slider_set_"+$slidernum).hide();
            jDelCookie('foldset_'+$slidernum);
            hideLSSliderButtlive($slidernum);
        } else {
            //Svernuto - razvora4ivaem
            jDelCookie('folding_'+$slidernum);
            $("#slidernum_"+$slidernum+" li").removeClass("min");
            $("#slidernum_"+$slidernum).sortable("option", "placeholder", 'ls-state-highlight');
            $thisElem.find("span").removeClass("plus").addClass("minus").html(minimizeStr);
        }
    });
    
    //Delete skin
    $(".skin_allow_delete").live('click', function() {
        if(confirm(confirmText)) {
            var t         = $(this),
            $skin_name    = t.attr('id').replace(/skin_/, ''),
            $skins_length = $("ul.fullwidth li.skinli").length;
            $.post(ajaxServerURL,
                    {skin_name:$skin_name, act:'del_skin'},
                    function(data) {
                        if(data.del_ret == true) {
                            t.parents("li.skinli").fadeOut('slow', function() {
                                $(this).remove();
                                $skins_length--;
                                if($skins_length == 0) $(".ls_box_content").html(noSkinsText);
                            });
                        }
                    }, "json"
            );
        }
    });
    
    var isValidUrl = function(data) {
        var pattern = new RegExp(/^((http|https):\/\/)?([a-z0-9\-]+\.)?([a-z0-9\-]+\.)?[a-z0-9\-]+\.[a-z0-9]{2,4}(\.[a-z0-9]{2,4})?(\/.*)?$/i);
        if(pattern.test(data) || ($.inArray(data, allowedUrlsArr)) > -1) return true;
        return false;
    };
    
    var jDelCookie = function(cookieName) {
        $.cookie(cookieName, null);
        return false;
    };
    
    var scrollToAnchor = function(id) {
        var $this_el  = $("#"+id),
        $final_offset = $this_el.offset().top - (($(window).height() - $this_el.height())/2);
        $('html,body').animate({scrollTop: $final_offset},'slow');
        return false;
    };
    
    var lsReplace = function(str, r) {
        return str.replace("{%torep%}", r);
    }
};