    var lenSliderJSReady = function(/*ajaxServerURL, */isPluginPage, allowedUrlsArr, inparr) {
        if(isPluginPage) {
            jQuery.alert.setup({
                types: {
                    error: {
                        title: inparr.errorTitle
                    },
                    warning: {
                        title: inparr.warningTitle
                    },
                    confirm: {
                        title: inparr.confirmTitle,
                        buttons: {
                            yes: inparr.yes,
                            no: inparr.no
                        }
                    }
                }
            });
            if(jQuery(".lmman").length) {
                jQuery(document).on('click', '.lmman', function() {
                    if(!jQuery(this).hasClass("lmdis")) {
                        var $n = parseInt(jQuery(this).attr('class').split(" ")[1].replace(/lmman_/, '')),
                        $to_switch = jQuery(this).attr('class').split(" ")[2].replace(/ls-type-/, ''),
                        $href = jQuery(this).attr('href');
                        jQuery(".bimagehide_"+$n).hide();
                        jQuery($href).show();
                        jQuery(".limman_"+$n).removeClass('act');
                        jQuery(this).parent("li").addClass('act');
                        jQuery("#bannertype_"+$n).val($to_switch);
                    }
                    return false;
                });
            }
            if(jQuery(".ls-crop-gal").length) {
                jQuery(document).on('click', '.ls-crop-gal', function() {
                    if(!jQuery(this).hasClass('disb') && parseInt(jQuery(this).next(".ls_input").val()) > 0) {
                        var $n = parseInt(jQuery(this).attr('class').split(" ")[1].replace(/ls-crop-gal-/, '')),
                        $val = jQuery(this).attr('class').split(" ")[2].replace(/ls-crop-gal-/, '');
                        if(jQuery(this).hasClass('act')) {
                            //jQuery(this).removeClass('act');
                            //$val = 'crop';
                        } else {
                            jQuery(".ls-crop-gal-"+$n).removeClass('act');
                            jQuery(this).addClass('act');
                        }
                        jQuery("#imageprior_"+$n).val($val);
                    }
                    return false;
                });
            }
            if(jQuery(".ls_del_sys_umeta").length) {
                jQuery(".ls_del_sys_umeta").click(function() {
                    jQuery.post(ajaxurl,
                        {user_id:inparr.user_id,sec:inparr.ajaxNonce,action:'ls_ajax_del_sys_umeta'},
                        function(data) {
                            if(data.res) jQuery("#ls_sys_message").remove();
                        }, "json"
                    );
                });
            }
            
            if(jQuery(".ls_media_upload").length) {
                //old WP
                if(inparr.wp_version < 3.5) {
                    jQuery(document).on('click', '.ls_media_upload', function() {
                        window.old_wp_33_this = jQuery(this);
                        var $sn    = window.old_wp_33_this.attr('id').replace(/ls_media_upload_/, '').split('_');
                        window.old_wp_33_slidernum = $sn[0],
                        window.old_wp_33_n         = $sn[1],
                        window.old_wp_33_width     = parseInt(jQuery("#ls-bimg-width-"+window.old_wp_33_n).val()),
                        window.old_wp_33_height    = parseInt(jQuery("#ls-bimg-height-"+window.old_wp_33_n).val()),
                        window.old_wp_33_prior     = jQuery("#imageprior_"+window.old_wp_33_n).val(),
                        window.old_wp_33_type      = jQuery("#bannertype_"+window.old_wp_33_n).val(),
                        window.old_wp_33_title     = jQuery("#ls-bimg-title-"+window.old_wp_33_n).val(),
                        window.old_wp_33_exist_id  = $sn[2];
                        tb_show('','media-upload.php?TB_iframe=true');
                        //return false;
                        if((window.original_tb_remove == undefined) && (window.tb_remove != undefined)) {
                            window.original_tb_remove = window.tb_remove;
                            window.tb_remove = function() {
                                window.original_tb_remove();
                            };
                        }
                        window.original_send_to_editor = window.send_to_editor;
                        window.send_to_editor = function(html) {
                            var $added_id = lsGetAttachmentId(html);
                            tb_remove();
                            jQuery(".ls_media_abs_"+window.old_wp_33_slidernum+"_"+window.old_wp_33_n).show();
                            setTimeout(function () {
                                switch(window.old_wp_33_type) {
                                    case 'image':
                                        jQuery.post(ajaxurl,
                                            {added_id:$added_id,slidernum:window.old_wp_33_slidernum,width:window.old_wp_33_width,height:window.old_wp_33_height,prior:window.old_wp_33_prior,title:window.old_wp_33_title,n:window.old_wp_33_n,type:window.old_wp_33_type,exist_id:window.old_wp_33_exist_id,sec:inparr.ajaxNonce,action:'ls_ajax_new_media'},
                                            function(data) {
                                                jQuery(".ls_media_abs_"+window.old_wp_33_slidernum+"_"+window.old_wp_33_n).hide();
                                                if(data.res) {
                                                    jQuery(".limman_"+window.old_wp_33_n+" a").addClass("lmdis");
                                                    jQuery("#ls_media_upload_"+window.old_wp_33_slidernum+"_"+window.old_wp_33_n).hide();
                                                    jQuery("#ls_image_mu_"+window.old_wp_33_slidernum+"_"+window.old_wp_33_n).val(data.id);
                                                    jQuery("#ls-cont-media-mu-"+window.old_wp_33_n).show().html(data.img);
                                                    jQuery("#bannertype_"+window.old_wp_33_n).val(window.old_wp_33_type);
                                                    jQuery(".ls-crop-gal-"+window.old_wp_33_type).addClass('disb');
                                                    jQuery("#ls-img-code-"+window.old_wp_33_type).show().html(data.code);
                                                    var $width_name = jQuery("#ls-bimg-width-"+window.old_wp_33_type).attr('name'),
                                                    $width_val      = parseInt(jQuery("#ls-bimg-width-"+window.old_wp_33_type).val()),
                                                    $height_name    = jQuery("#ls-bimg-height-"+window.old_wp_33_type).attr('name'),
                                                    $height_val     = parseInt(jQuery("#ls-bimg-height-"+window.old_wp_33_type).val());
                                                    $width_val      = (!isNaN($width_val))?$width_val:'';
                                                    $height_val     = (!isNaN($height_val))?$height_val:'';
                                                    jQuery("#ls-bimg-width-"+window.old_wp_33_n).removeAttr('id').removeAttr('name').attr('disabled','disabled').next(".ls_hidden").attr({name:$width_name,id:'ls-bimg-width-'+window.old_wp_33_n}).val($width_val);
                                                    jQuery("#ls-bimg-height-"+window.old_wp_33_n).removeAttr('id').removeAttr('name').attr('disabled','disabled').next(".ls_hidden").attr({name:$height_name,id:'ls-bimg-height-'+window.old_wp_33_n}).val($height_val);
                                                }
                                            }, "json"
                                        );
                                        break;
                                }
                            }, 300);
                        }
                    });
                }
                //new WP
                if(inparr.wp_version >= 3.5) {
                    var custom_file_frame;
                    jQuery(document).on('click', '.ls_media_upload', function() {
                        var $this = jQuery(this),
                        $sn        = $this.attr('id').replace(/ls_media_upload_/, '').split('_'),
                        $slidernum = $sn[0],
                        $n         = $sn[1],
                        $width     = parseInt(jQuery("#ls-bimg-width-"+$n).val()),
                        $height    = parseInt(jQuery("#ls-bimg-height-"+$n).val()),
                        $prior     = jQuery("#imageprior_"+$n).val(),
                        $type      = jQuery("#bannertype_"+$n).val(),
                        $title     = jQuery("#ls-bimg-title-"+$n).val(),
                        $exist_id  = $sn[2];
                        event.preventDefault();
                        if (typeof(custom_file_frame)!=="undefined") {
                            custom_file_frame.open();
                            return;
                        }
                        
                        if(($prior == 'width' && $width > 0) || ($prior == 'height' && $height > 0)) {
                            custom_file_frame = wp.media.frames.customHeader = wp.media({
                                title: inparr.wp_uploader_title,
                                library: {
                                    type: 'image'
                                },
                                button: {
                                    text: inparr.wp_uploader_button
                                },
                                multiple: false
                            });
                            custom_file_frame.on('select', function() {
                                var attachment = custom_file_frame.state().get('selection').first().toJSON();
                                jQuery(".ls_media_abs_"+$slidernum+"_"+$n).show();
                                setTimeout(function () {
                                    switch($type) {
                                        case 'image':
                                            jQuery.post(ajaxurl,
                                                {added_id:attachment.id,slidernum:$slidernum,width:$width,height:$height,prior:$prior,title:$title,n:$n,type:$type,exist_id:$exist_id,sec:inparr.ajaxNonce,action:'ls_ajax_new_media'},
                                                function(data) {
                                                    jQuery(".ls_media_abs_"+$slidernum+"_"+$n).hide();
                                                    if(data.res) {
                                                        jQuery(".limman_"+$n+" a").addClass("lmdis");
                                                        jQuery("#ls_media_upload_"+$slidernum+"_"+$n+"_"+$exist_id).hide();
                                                        jQuery("#ls_image_mu_"+$slidernum+"_"+$n).val(data.id);
                                                        jQuery("#ls-cont-media-mu-"+$n).show().html(data.img);
                                                        jQuery("#bannertype_"+$n).val($type);
                                                        jQuery(".ls-crop-gal-"+$n).addClass('disb');
                                                        jQuery("#ls-img-code-"+$n).show().html(data.code);
                                                        var $width_name = jQuery("#ls-bimg-width-"+$n).attr('name'),
                                                        $width_val      = parseInt(jQuery("#ls-bimg-width-"+$n).val()),
                                                        $height_name    = jQuery("#ls-bimg-height-"+$n).attr('name'),
                                                        $height_val     = parseInt(jQuery("#ls-bimg-height-"+$n).val());
                                                        $width_val      = (!isNaN($width_val))?$width_val:'';
                                                        $height_val     = (!isNaN($height_val))?$height_val:'';
                                                        jQuery("#ls-bimg-width-"+$n).removeAttr('id').removeAttr('name').attr('disabled','disabled').next(".ls_hidden").attr({name:$width_name,id:'ls-bimg-width-'+$n}).val($width_val);
                                                        jQuery("#ls-bimg-height-"+$n).removeAttr('id').removeAttr('name').attr('disabled','disabled').next(".ls_hidden").attr({name:$height_name,id:'ls-bimg-height-'+$n}).val($height_val);
                                                    } else console.dir('dddd');
                                                }, "json"
                                            );
                                            break;
                                    }
                                }, 300);
                            });
                            custom_file_frame.open();
                        } else jQuery.alert.open('error', inparr.emptySizeStr);
                    });
                }
            }
            if(jQuery(".ls_textonly").length) {
                jQuery(document).on('click', '.ls_textonly', function() {
                    var $this = jQuery(this),
                    $split = $this.attr('id').replace(/ls_textonly_/, '').split("_"),
                    $slidernum = $split[0],
                    $n         = $split[1],
                    $att_id    = $split[2],
                    $type      = jQuery("#bannertype_"+$n).val();
                    jQuery(".ls_media_abs_"+$slidernum+"_"+$n).show();
                    if(!$this.hasClass('act')) {
                        setTimeout(function () {
                            jQuery.post(ajaxurl,
                                {type:$type,slidernum:$slidernum,insert:1,sec:inparr.ajaxNonce,action:'ls_ajax_new_media'},
                                function(data) {
                                    jQuery(".ls_media_abs_"+$slidernum+"_"+$n).hide();
                                    if(data.res) {
                                        jQuery(".limman_"+$n+" a").addClass('lmdis');
                                        $this.addClass('act');
                                        jQuery("#ls_image_mu_"+$slidernum+"_"+$n).val(data.id);
                                        $this.attr({id:'ls_textonly_'+$slidernum+'_'+$n+'_'+data.id});
                                    }
                                }, "json"
                            );
                        }, 300);
                    } else {
                        setTimeout(function () {
                            jQuery.post(ajaxurl,
                                {type:$type,slidernum:$slidernum,insert:0,att_id:$att_id,sec:inparr.ajaxNonce,action:'ls_ajax_new_media'},
                                function(data) {
                                    jQuery(".ls_media_abs_"+$slidernum+"_"+$n).hide();
                                    if(data.res) {
                                        jQuery(".limman_"+$n+" a").removeClass('lmdis');
                                        $this.removeClass('act');
                                        jQuery("#ls_image_mu_"+$slidernum+"_"+$n).val('');
                                        $this.attr({id:'ls_textonly_'+$slidernum+'_'+$n+'_0'});
                                        jQuery("#ls_image_mu_"+$slidernum+"_"+$n).val('');
                                    }
                                }, "json"
                            );
                        }, 300);
                    }
                    jQuery("#bannertype_"+$n).val($type);
                });
            }
            if(jQuery(".yt_button").length) {
                jQuery(document).on('click', '.yt_button', function() {
                    var $this = jQuery(this),
                    $split = $this.attr('id').replace(/yt_button_/, '').split("_"),
                    $slidernum = $split[0],
                    $n         = $split[1],
                    $url       = jQuery("#ls-yt-url-"+$n).val(),
                    $type      = jQuery("#bannertype_"+$n).val();
                    jQuery(".ls_media_abs_"+$slidernum+"_"+$n).show();
                    setTimeout(function () {
                        jQuery.post(ajaxurl,
                            {url:$url,slidernum:$slidernum,type:$type,sec:inparr.ajaxNonce,action:'ls_ajax_new_media'},
                            function(data) {
                                jQuery(".ls_media_abs_"+$slidernum+"_"+$n).hide();
                                if(data.res) {
                                    $this.attr('disabled','disabled');
                                    jQuery("#ls-yt-url-"+$n).val(data.yt_url).attr('disabled','disabled');
                                    jQuery(".limman_"+$n+" a").addClass("lmdis");
                                    jQuery("#ls_yt_upload_"+$slidernum+"_"+$n).hide();
                                    jQuery("#ls_image_mu_"+$slidernum+"_"+$n).val(data.id);
                                    jQuery("#ls-cont-media-yt-"+$n).show().html(data.code);
                                    jQuery("#bannertype_"+$n).val($type);
                                    var $url_name = jQuery("#ls-yt-url-"+$n).attr('name'),
                                    $url_val      = data.yt_url;
                                    jQuery("#ls-yt-url-"+$n).removeAttr('id').removeAttr('name').attr('disabled','disabled').next(".ls_hidden").attr({name:$url_name,id:'ls-yt-url-'+$n}).val($url_val);
                                }
                            }, "json"
                        );
                    }, 300);
                });
            }
            if(jQuery(".vm_button").length) {
                jQuery(document).on('click', '.vm_button', function() {
                    var $this = jQuery(this),
                    $split = $this.attr('id').replace(/vm_button_/, '').split("_"),
                    $slidernum = $split[0],
                    $n         = $split[1],
                    $url       = jQuery("#ls-vm-url-"+$n).val(),
                    $type      = jQuery("#bannertype_"+$n).val();
                    jQuery(".ls_media_abs_"+$slidernum+"_"+$n).show();
                    setTimeout(function () {
                        jQuery.post(ajaxurl,
                            {url:$url,slidernum:$slidernum,type:$type,sec:inparr.ajaxNonce,action:'ls_ajax_new_media'},
                            function(data) {
                                jQuery(".ls_media_abs_"+$slidernum+"_"+$n).hide();
                                if(data.res) {
                                    $this.attr('disabled','disabled');
                                    jQuery("#ls-vm-url-"+$n).attr('disabled','disabled');
                                    jQuery(".limman_"+$n+" a").addClass("lmdis");
                                    jQuery("#ls_vm_upload_"+$slidernum+"_"+$n).hide();
                                    jQuery("#ls_image_mu_"+$slidernum+"_"+$n).val(data.id);
                                    jQuery("#ls-cont-media-vm-"+$n).show().html(data.code);
                                    jQuery("#bannertype_"+$n).val($type);
                                    var $url_name = jQuery("#ls-vm-url-"+$n).attr('name'),
                                    $url_val      = jQuery("#ls-vm-url-"+$n).val();
                                    jQuery("#ls-vm-url-"+$n).removeAttr('id').removeAttr('name').attr('disabled','disabled').next(".ls_hidden").attr({name:$url_name,id:'ls-vm-url-'+$n}).val($url_val);
                                }
                            }, "json"
                        );
                    }, 300);
                });
            }
            if(jQuery(".a_ls_del").length) {
                jQuery(document).on('click', '.a_ls_del', function() {
                    var $this = jQuery(this);
                    jQuery.alert.open('confirm', inparr.confirmText, function(button) {
                        if(button == 'yes') {
                            var $arr      = $this.attr('id').replace(/ls_box_del_/, '').split('_'),
                            $slidernum    = $arr[0],
                            $n            = $arr[1],
                            $att_id       = $arr[2],
                            $att_thumb_id = jQuery("#ls_image_thumb_mu_"+$slidernum+"_"+$n).val(),
                            $type         = jQuery("#bannertype_"+$n).val(),
                            $width_name   = '',
                            $width_val    = '',
                            $height_name  = '',
                            $height_val   = '',
                            $url_name     = '';
                            jQuery(".ls_media_abs_"+$slidernum+"_"+$n).show();
                            setTimeout(function () {
                                jQuery.post(ajaxurl,
                                    {att_id:$att_id,thumb_id:$att_thumb_id,slidernum:$slidernum,n:$n,sec:inparr.ajaxNonce,action:'ls_ajax_delete_attachment'},
                                    function(data) {
                                        jQuery(".ls_media_abs_"+$slidernum+"_"+$n).hide();
                                        switch($type) {
                                            case 'image':
                                                if(data.res) {
                                                    jQuery("#ls-cont-media-mu-"+$n).hide().html('');
                                                    jQuery("#ls-img-code-"+$n).hide().html('');
                                                    jQuery("#ls_media_upload_"+$slidernum+"_"+$n+"_"+$att_id).show();
                                                    jQuery(".limman_"+$n+" a").removeClass("lmdis");
                                                    jQuery("#ls_image_mu_"+$slidernum+"_"+$n).val('');
                                                    jQuery(".ls-crop-gal-"+$n).removeClass('disb');
                                                    $width_name  = jQuery("#ls-bimg-width-"+$n).attr('name'),
                                                    $width_val   = parseInt(jQuery("#ls-bimg-width-"+$n).val()),
                                                    $height_name = jQuery("#ls-bimg-height-"+$n).attr('name'),
                                                    $height_val  = parseInt(jQuery("#ls-bimg-height-"+$n).val());
                                                    $width_val   = (!isNaN($width_val))?$width_val:'';
                                                    $height_val  = (!isNaN($height_val))?$height_val:'';
                                                    jQuery("#ls-bimg-width-"+$n).removeAttr('id').removeAttr('name').prev(".ls_input").attr({name:$width_name,id:'ls-bimg-width-'+$n}).removeAttr('disabled');
                                                    jQuery("#ls-bimg-height-"+$n).removeAttr('id').removeAttr('name').prev(".ls_input").attr({name:$height_name,id:'ls-bimg-height-'+$n}).removeAttr('disabled');
                                                }
                                                break;
                                            case 'youtube':
                                                if(data.res) {
                                                    jQuery("#yt_button_"+$slidernum+"_"+$n).removeAttr('disabled');
                                                    jQuery(".limman_"+$n+" a").removeClass("lmdis");
                                                    jQuery("#ls_yt_upload_"+$slidernum+"_"+$n).show();
                                                    jQuery("#ls_image_mu_"+$slidernum+"_"+$n).val(data.id);
                                                    jQuery("#ls-cont-media-yt-"+$n).hide().html('');
                                                    $url_name = jQuery("#ls-yt-url-"+$n).attr('name');
                                                    jQuery("#ls-yt-url-"+$n).removeAttr('id').removeAttr('name').prev(".ls_input").removeAttr('disabled').attr({name:$url_name,id:'ls-yt-url-'+$n});
                                                }
                                                break;
                                            case 'vimeo':
                                                if(data.res) {
                                                    jQuery("#vm_button_"+$slidernum+"_"+$n).removeAttr('disabled');
                                                    jQuery(".limman_"+$n+" a").removeClass("lmdis");
                                                    jQuery("#ls_vm_upload_"+$slidernum+"_"+$n).show();
                                                    jQuery("#ls_image_mu_"+$slidernum+"_"+$n).val(data.id);
                                                    jQuery("#ls-cont-media-vm-"+$n).hide().html('');
                                                    $url_name = jQuery("#ls-vm-url-"+$n).attr('name');
                                                    jQuery("#ls-vm-url-"+$n).removeAttr('id').removeAttr('name').prev(".ls_input").removeAttr('disabled').attr({name:$url_name,id:'ls-vm-url-'+$n});
                                                }
                                                break;
                                        }
                                    }, "json"
                                );
                            }, 300);
                        }
                    });
                });
            }
            
            if(jQuery(".a_ls_thumb_del").length) {
                jQuery(document).on('click', '.a_ls_thumb_del', function() {
                    var $this = jQuery(this);
                    jQuery.alert.open('confirm', inparr.confirmText, function(button) {
                        if(button == 'yes') {
                            var $arr      = $this.attr('id').replace(/ls_box_thumb_del_/, '').split('_'),
                            $slidernum    = $arr[0],
                            $n            = $arr[1],
                            $att_id       = $arr[2],
                            $thumb_id     = $arr[3];
                            jQuery(".ls_media_thumb_abs_"+$slidernum+"_"+$n).show();
                            setTimeout(function () {
                                jQuery.post(ajaxurl,
                                    {att_id:$att_id,thumb_id:$thumb_id,slidernum:$slidernum,n:$n,sec:inparr.ajaxNonce,action:'ls_ajax_delete_thumb'},
                                    function(data) {
                                        jQuery(".ls_media_thumb_abs_"+$slidernum+"_"+$n).hide();
                                        if(data.res) {
                                            jQuery("#ls-cont-thumb-media-mu-"+$n).hide().html('');
                                            jQuery("#ls_media_thumb_upload_"+$slidernum+"_"+$n).show();
                                            jQuery("#ls_image_thumb_mu_"+$slidernum+"_"+$n).val('');
                                        }
                                    }, "json"
                                );
                            }, 300);
                        }
                    });
                });
            }
            
            if(jQuery(".ls_media_thumb_upload").length) {
                //old WP
                if(inparr.wp_version < 3.5) {
                    jQuery(document).on('click', '.ls_media_thumb_upload', function() {
                        window.old_wp_33_this = jQuery(this);
                        var $sn    = window.old_wp_33_this.attr('id').replace(/ls_media_upload_/, '').split('_');
                        window.old_wp_33_slidernum = $sn[0],
                        window.old_wp_33_n         = $sn[1],
                        window.old_wp_33_width     = parseInt(jQuery("#ls-bimg-width-"+window.old_wp_33_n).val()),
                        window.old_wp_33_height    = parseInt(jQuery("#ls-bimg-height-"+window.old_wp_33_n).val()),
                        window.old_wp_33_prior     = jQuery("#imageprior_"+window.old_wp_33_n).val(),
                        window.old_wp_33_type      = jQuery("#bannertype_"+window.old_wp_33_n).val(),
                        window.old_wp_33_title     = jQuery("#ls-bimg-title-"+window.old_wp_33_n).val(),
                        window.old_wp_33_exist_id  = $sn[2];
                        tb_show('','media-upload.php?TB_iframe=true');
                        return false;
                    });
                    if((window.original_tb_remove == undefined) && (window.tb_remove != undefined)) {
                        window.original_tb_remove = window.tb_remove;
                        window.tb_remove = function() {
                            window.original_tb_remove();
                        };
                    }
                    window.original_send_to_editor = window.send_to_editor;
                    window.send_to_editor = function(html) {
                        var $added_id = lsGetAttachmentId(html);
                        tb_remove();
                        jQuery(".ls_media_abs_"+window.old_wp_33_slidernum+"_"+window.old_wp_33_n).show();
                        setTimeout(function () {
                            switch(window.old_wp_33_type) {
                                case 'image':
                                    jQuery.post(ajaxurl,
                                        {added_id:$added_id,slidernum:window.old_wp_33_slidernum,width:window.old_wp_33_width,height:window.old_wp_33_height,prior:window.old_wp_33_prior,title:window.old_wp_33_title,n:window.old_wp_33_n,type:window.old_wp_33_type,exist_id:window.old_wp_33_exist_id,sec:inparr.ajaxNonce,action:'ls_ajax_new_media'},
                                        function(data) {
                                            jQuery(".ls_media_abs_"+window.old_wp_33_slidernum+"_"+window.old_wp_33_n).hide();
                                            if(data.res) {
                                                jQuery(".limman_"+window.old_wp_33_n+" a").addClass("lmdis");
                                                jQuery("#ls_media_upload_"+window.old_wp_33_slidernum+"_"+window.old_wp_33_n).hide();
                                                jQuery("#ls_image_mu_"+window.old_wp_33_slidernum+"_"+window.old_wp_33_n).val(data.id);
                                                jQuery("#ls-cont-media-mu-"+window.old_wp_33_n).show().html(data.img);
                                                jQuery("#bannertype_"+window.old_wp_33_n).val(window.old_wp_33_type);
                                                jQuery(".ls-crop-gal-"+window.old_wp_33_type).addClass('disb');
                                                jQuery("#ls-img-code-"+window.old_wp_33_type).show().html(data.code);
                                                var $width_name = jQuery("#ls-bimg-width-"+window.old_wp_33_type).attr('name'),
                                                $width_val      = parseInt(jQuery("#ls-bimg-width-"+window.old_wp_33_type).val()),
                                                $height_name    = jQuery("#ls-bimg-height-"+window.old_wp_33_type).attr('name'),
                                                $height_val     = parseInt(jQuery("#ls-bimg-height-"+window.old_wp_33_type).val());
                                                $width_val      = (!isNaN($width_val))?$width_val:'';
                                                $height_val     = (!isNaN($height_val))?$height_val:'';
                                                jQuery("#ls-bimg-width-"+window.old_wp_33_n).removeAttr('id').removeAttr('name').attr('disabled','disabled').next(".ls_hidden").attr({name:$width_name,id:'ls-bimg-width-'+window.old_wp_33_n}).val($width_val);
                                                jQuery("#ls-bimg-height-"+window.old_wp_33_n).removeAttr('id').removeAttr('name').attr('disabled','disabled').next(".ls_hidden").attr({name:$height_name,id:'ls-bimg-height-'+window.old_wp_33_n}).val($height_val);
                                            }
                                        }, "json"
                                    );
                                    break;
                            }
                        }, 300);
                    }
                }
                //new WP
                if(inparr.wp_version >= 3.5) {
                    var custom_thumb_file_frame;
                    jQuery(document).on('click', '.ls_media_thumb_upload', function() {
                        var $this = jQuery(this),
                        $sn        = $this.attr('id').replace(/ls_media_thumb_upload_/, '').split('_'),
                        $slidernum = $sn[0],
                        $n         = $sn[1],
                        $width     = parseInt(jQuery("#ls_thumb_max_width_"+$slidernum).val());
                        event.preventDefault();
                        if (typeof(custom_thumb_file_frame)!=="undefined") {
                            custom_thumb_file_frame.open();
                            return;
                        }
                        
                        if($width > 0) {
                            custom_thumb_file_frame = wp.media.frames.customHeader = wp.media({
                                title: inparr.wp_uploader_title,
                                library: {
                                    type: 'image'
                                },
                                button: {
                                    text: inparr.wp_uploader_button
                                },
                                multiple: false
                            });
                            custom_thumb_file_frame.on('select', function() {
                                var attachment = custom_thumb_file_frame.state().get('selection').first().toJSON();
                                jQuery(".ls_media_thumb_abs_"+$slidernum+"_"+$n).show();
                                setTimeout(function () {
                                    jQuery.post(ajaxurl,
                                        {added_id:attachment.id,slidernum:$slidernum,width:$width,n:$n,sec:inparr.ajaxNonce,action:'ls_ajax_new_thumb'},
                                        function(data) {
                                            jQuery(".ls_media_thumb_abs_"+$slidernum+"_"+$n).hide();
                                            if(data.res) {
                                                jQuery("#ls_media_thumb_upload_"+$slidernum+"_"+$n).hide();
                                                jQuery("#ls_image_thumb_mu_"+$slidernum+"_"+$n).val(data.id);
                                                jQuery("#ls-cont-thumb-media-mu-"+$n).show().html(data.img);
                                            }
                                        }, "json"
                                    );
                                }, 300);
                            });
                            custom_thumb_file_frame.open();
                        } else jQuery.alert.open('error', 'Lorem ipsum dolor sit amet');
                    });
                }
            }
            
            if(jQuery(".ls_switch").length) {
                jQuery(".ls_switch").toggleSwitch({
                    highlight: true,
                    width: 25,
                    change: function() {
                        jQuery("body").removeClass().addClass(jQuery(this).val().toLowerCase());
                    }
                });
            }
            
            if(jQuery(".ls-sortable").length) {
                var $slidernum = jQuery(".ls-sortable").attr('id').replace(/slidernum_/, ''),
                $plhldr = (jQuery.cookie("folding_"+$slidernum) == "svernuto") ? "ushmin" : "ls-state-highlight";
                jQuery(".ls-sortable").sortable({
                        axis: 'y',
                        placeholder: $plhldr,
                        handle: 'h3',
                        forcePlaceholderSize: true,
                        opacity: 0.65,
                        start: function(e, ui) {
                            jQuery(this).find('textarea:not(.ls_nmce)').each(function() {
                                tinyMCE.execCommand('mceRemoveControl', false, jQuery(this).attr('id'));
                            });
                        },
                        stop: function(e, ui) {
                            jQuery(this).find('textarea:not(.ls_nmce)').each(function() {
                                tinyMCE.execCommand('mceAddControl', true, jQuery(this).attr('id'));
                            });
                        }
                    });
            }
            
            if(jQuery(".ls_spinner").length) {
                jQuery(".ls_spinner").each(function() {
                    jQuery(this).spinner({
                        min: 0
                    });
                });
            }
            
            if(jQuery(".ls_mtip").length) {
                jQuery(".ls_mtip").mTip({
                    align: 'left'
                });
            }
        }

        var minLength = 3;
        if(jQuery(".ls_slide_image_inner_controls").length) jQuery(".ls_slide_image_inner_controls ul li a").css({'opacity':.6});
        if(jQuery(".ls_abs_size").length) jQuery(".ls_abs_size").css({'opacity':.5});
        if(jQuery(".ls_banner_close").length) jQuery(".ls_banner_close").css({'opacity':.7});
        if(jQuery(".ls_slide_image_inner_overlay").length) jQuery(".ls_slide_image_inner_overlay").hide();
        
        if(jQuery(".ls-welcome-panel-close").length) {
            jQuery(".ls-welcome-panel-close").click(function() {
                jQuery.post(ajaxurl,
                    {action:'ls_welcome_panel',ls_welcomepanelnonce:jQuery('#ls_welcomepanelnonce').val()},
                    function(data) {
                        if(data == 1) jQuery("#ls-welcome-panel").hide();
                    }
                );
                return false;
            });
        }

        jQuery(document).on('change', '.chbx_is_thumb', function() {
            var $slidernum = jQuery(this).attr('id').split("_")[3];
            if(jQuery(this).prop("checked")) {
                if(jQuery("#ls_thumb_max_width_"+$slidernum).attr("disabled") == "disabled") {
                    jQuery("#ls_thumb_max_width_"+$slidernum).removeAttr("disabled");
                    jQuery("#ls_thumb_max_width_"+$slidernum).spinner();
                    jQuery(".ls_thumb_box").show();
                }
            } else {
                if(jQuery("#ls_thumb_max_width_"+$slidernum).attr("disabled") != "disabled") {
                    jQuery("#ls_thumb_max_width_"+$slidernum).attr("disabled", "disabled");
                    jQuery("#ls_thumb_max_width_"+$slidernum).spinner("destroy");
                    jQuery(".ls_thumb_box").hide();
                }
            }
        });
        
        jQuery(document).on('change', '.chbx_is_autoplay', function() {
            var $slidernum = jQuery(this).attr('id').split("_")[3];
            if(jQuery(this).prop("checked")) {
                if(jQuery("#ls_autoplay_delay_"+$slidernum).attr("disabled") == "disabled") {
                    jQuery("#ls_autoplay_delay_"+$slidernum).removeAttr("disabled");
                    jQuery("#ls_autoplay_delay_"+$slidernum).addClass("ls_spinner");
                    jQuery("#ls_autoplay_delay_"+$slidernum).spinner();
                }
            } else {
                if(jQuery("#ls_autoplay_delay_"+$slidernum).attr("disabled") != "disabled") {
                    jQuery("#ls_autoplay_delay_"+$slidernum).attr("disabled", "disabled");
                    jQuery("#ls_autoplay_delay_"+$slidernum).removeClass("ls_spinner");
                    jQuery("#ls_autoplay_delay_"+$slidernum).spinner("destroy");
                }
            }
        });
        
        jQuery(".add_new_slider").click(function() {
            var $skin_name = jQuery("select[name=new_slider_skin] option:selected").val(),
            $id            = jQuery(this).attr('id'),
            $href          = jQuery(this).attr('href');
            if($id) $href  = $href+"&slidernum="+$id;
            jQuery(this).attr('href', $href+"&skin="+$skin_name);
        });

        jQuery(".ls_banner_close").hover(
            function() {jQuery(this).css({'opacity':1});},
            function() {jQuery(this).css({'opacity':.6});}
        );

        jQuery(document).on('click', '.handlediv', function() {
            var $thisElem = jQuery(this).parents("li");
            if($thisElem.hasClass("min")) $thisElem.removeClass("min");
            else $thisElem.addClass("min");
        });
        
        jQuery(document).on('change', 'input.blink', function() {
            var $to_list = jQuery.trim(jQuery(this).val()),
            $pre_info    = jQuery(this).attr('id').replace(/blink_/, '').split('_'),
            $name        = $pre_info[0],
            $slidernum   = $pre_info[1],
            $n           = $pre_info[2],
            $banner_k    = $pre_info[3];
            jQuery("#blink_append_"+$slidernum+"_"+$n).html('');
            if($to_list != 'blink_lsurl') {
                jQuery("#blink_append_"+$slidernum+"_"+$n).addClass('bload2');
                jQuery("#ls_link_"+$slidernum+"_"+$n).removeClass('tcheck').attr("disabled", "disabled");
            } else jQuery("#ls_link_"+$slidernum+"_"+$n).addClass('tcheck').removeAttr("disabled");
            jQuery.post(ajaxurl,
                {slidernum:$slidernum,name:$name,n:$n,banner_k:$banner_k,to_list:$to_list,sec:inparr.ajaxNonce,action:'ls_ajax_links_variants'},
                function(data) {
                    if($to_list != 'blink_lsurl') {
                        jQuery("#ls_link_"+$slidernum+"_"+$n).removeClass('tcheck').val('');
                        jQuery("#blink_append_"+$slidernum+"_"+$n).removeClass('bload2');
                        if(data.ret != '') jQuery("#blink_append_"+$slidernum+"_"+$n).html(data.ret);
                        else jQuery("#blink_append_"+$slidernum+"_"+$n).html('');
                        jQuery("#post_hidden_uth_ls_link_"+$slidernum+"_"+$n).html(data.uth);
                    } else {
                        jQuery("#ls_link_"+$slidernum+"_"+$n).addClass('tcheck');
                        ls_blinkurl(data.uth, $slidernum, $n);
                    }
                }, "json"
            );
        });

        jQuery(document).on('change', '.ls_post_url select', function() {
            var $pre_info = jQuery(this).attr('id').replace(/blink_select_/, '').split('_'),
            $slidernum    = $pre_info[0],
            $banner_k     = $pre_info[1],
            $n            = $pre_info[2],
            $id           = parseInt(jQuery(this).val()),
            $url_type     = jQuery("#post_hidden_uth_ls_link_"+$slidernum+"_"+$n+" input").val();
            jQuery.post(ajaxurl,
                {slidernum:$slidernum,n:$n,id:$id,banner_k:$banner_k,url_type:$url_type,sec:inparr.ajaxNonce,action:'ls_ajax_link_variants_url'},
                function(data) {
                    jQuery("#ls_link_"+$slidernum+"_"+$n).removeAttr("name").val(data.url);
                    jQuery("#url_type_id_"+$slidernum+"_"+$n).html(data.uti);
                    jQuery("#post_hidden_ls_link_"+$slidernum+"_"+$n).html(data.ret);
                }, "json"
            );
        });
        
        jQuery(document).on('change', 'input.btitle', function() {
            var $to_list = jQuery.trim(jQuery(this).val()),
            $pre_info    = jQuery(this).attr('id').replace(/btitle_/, '').split('_'),
            $name        = $pre_info[0],
            $slidernum   = $pre_info[1],
            $n           = $pre_info[2],
            $banner_k    = $pre_info[3];
            jQuery("#btitle_append_"+$slidernum+"_"+$n).html('');
            if($to_list != 'btitle_lstitle') {
                jQuery("#btitle_append_"+$slidernum+"_"+$n).addClass('bload2');
                jQuery("#ls_title_"+$slidernum+"_"+$n).removeClass('tcheck').attr("disabled", "disabled");
            } else jQuery("#ls_title_"+$slidernum+"_"+$n).addClass('tcheck').removeAttr("disabled");
            jQuery.post(ajaxurl,
                {slidernum:$slidernum,name:$name,n:$n,banner_k:$banner_k,to_list:$to_list,sec:inparr.ajaxNonce,action:'ls_ajax_titles_variants'},
                function(data) {
                    if($to_list != 'btitle_lstitle') {
                        jQuery("#ls_title_"+$slidernum+"_"+$n).removeClass('tcheck').val('');
                        jQuery("#btitle_append_"+$slidernum+"_"+$n).removeClass('bload2');
                        if(data.ret != '') jQuery("#btitle_append_"+$slidernum+"_"+$n).html(data.ret);
                        else jQuery("#btitle_append_"+$slidernum+"_"+$n).html('');
                        jQuery("#post_hidden_uth_ls_title_"+$slidernum+"_"+$n).html(data.uth);
                    } else {
                        jQuery("#ls_title_"+$slidernum+"_"+$n).addClass('tcheck');
                        ls_blinktitle(data.uth, $slidernum, $n);
                    }
                }, "json"
            );
        });

        jQuery(document).on('change', '.ls_post_title select', function() {
            var $pre_info = jQuery(this).attr('id').replace(/btitle_select_/, '').split('_'),
            $slidernum    = $pre_info[0],
            $banner_k     = $pre_info[1],
            $n            = $pre_info[2],
            $id           = parseInt(jQuery(this).val()),
            $title_type   = jQuery("#post_hidden_uth_ls_title_"+$slidernum+"_"+$n+" input").val();
            jQuery.post(ajaxurl,
                {slidernum:$slidernum,n:$n,id:$id,banner_k:$banner_k,title_type:$title_type,sec:inparr.ajaxNonce,action:'ls_ajax_titles_variants_title'},
                function(data) {
                    jQuery("#ls_title_"+$slidernum+"_"+$n).removeAttr("name").val(data.title);
                    jQuery("#title_type_id_"+$slidernum+"_"+$n).html(data.uti);
                    jQuery("#post_hidden_ls_title_"+$slidernum+"_"+$n).html(data.ret);
                }, "json"
            );
        });

        /*jQuery(document).on('change', '.swskin', function() {
            var $slidernum = jQuery(this).attr('class').split(' ')[1].replace(/swskin_/, ''),
            $skin_name     = jQuery(this).val();//skin name
            jQuery(".swskin_"+$slidernum).val($skin_name);
            if(jQuery.alert.open('confirm', inparr.skinSettingsConfirmStr)) {
                jQuery.cookie('skin_set_'+$slidernum, $skin_name, {expires:1,path:'/'});
            }
        });*/

        //Set global settings for slider custom settings
        /*jQuery(document).on('click', '.set_global_set_sldr', function() {
            var $slidernum = jQuery(this).attr('id').replace(/set_glob_/, ''),
            $this_li       = jQuery(this).parent("div");
            $this_li.addClass("bload");
            jQuery.post(ajaxServerURL,
                {sec:inparr.ajaxNonce,act:'get_settings_global'},
                function(data) {
                    jQuery(".ls_images_maxsize_" +$slidernum).val(data.ret.ls_images_maxsize);
                    jQuery(".ls_images_maxwidth_"+$slidernum).val(data.ret.ls_images_maxwidth);
                    jQuery(".ls_banners_limit_"  +$slidernum).val(data.ret.ls_banners_limit);
                    jQuery(".ls_slider_images_quality_" +$slidernum).val(data.ret.ls_slider_images_quality);
                    $this_li.removeClass("bload");
                    jQuery("#set_local_"+$slidernum).show();
                }, "json"
            );
        });

        //Return local slider settings
        jQuery(document).on('click', '.set_local_set_sldr', function() {
            var $slidernum = jQuery(this).attr('id').replace(/set_local_/, ''),
            $this_li       = jQuery(this).parent("div");
            $this_li.addClass("bload");
            jQuery.post(ajaxServerURL,
                {slidernum:$slidernum,sec:inparr.ajaxNonce,act:'get_settings_local'},
                function(data) {
                    jQuery(".ls_images_maxsize_" +$slidernum).val(data.ret.ls_images_maxsize);
                    jQuery(".ls_images_maxwidth_"+$slidernum).val(data.ret.ls_images_maxwidth);
                    jQuery(".ls_banners_limit_"  +$slidernum).val(data.ret.ls_banners_limit);
                    jQuery(".ls_slider_images_quality_" +$slidernum).val(data.ret.ls_slider_images_quality);
                    $this_li.removeClass("bload");
                    jQuery("#set_local_"+$slidernum).hide();
                }, "json"
            );
        });*/

        //Set skin settings for slider
        jQuery(document).on('click', '.ls_set_skin_settings', function() {
            var $this  = jQuery(this),
            $slidernum = $this.attr('id').replace(/ls_set_skin_settings_/, '');
            $this.attr('disabled','disabled');
            jQuery.post(ajaxurl,
                {slidernum:$slidernum,sec:inparr.ajaxNonce,action:'ls_ajax_get_settings_skin'},
                function(data) {
                    if(data.res) {
                        $this.removeAttr('disabled');
                        if(jQuery.isPlainObject(data.arr)) {
                            jQuery.each(data.arr, function(key, value) {
                                if(jQuery("#"+key+"_"+$slidernum).length) {
                                    var handler = "#"+key+"_"+$slidernum;
                                    jQuery(handler).val(value);
                                    if(jQuery(handler).prev(".ls_input").length) jQuery(handler).prev(".ls_input").val(value);
                                    if(jQuery(handler).next(".ls_hidden").length) jQuery(handler).next(".ls_hidden").val(value);
                                }
                            });
                        }
                    }
                }, "json"
            );
        });

        //Delete banner
        jQuery(document).on('click', '.liveajaxbdel', function() {
            var $this = jQuery(this);
            jQuery.alert.open('confirm', inparr.confirmText, function(button) {
                if(button == 'yes') {
                    var $slidernum = $this.parents("ul.ls-sortable").attr('id').replace(/slidernum_/, ''),
                    $this_arr      = $this.attr('id').replace(/liveajaxbdel_/, '').split("_"),
                    $n             = $this_arr[0],
                    $att_id        = jQuery("#ls_image_mu_"+$slidernum+"_"+$n).val(),
                    $att_thumb_id  = jQuery("#ls_image_thumb_mu_"+$slidernum+"_"+$n).val();
                    if(parseInt($att_id) <= 0) $att_id = 0;
                    jQuery("#boverlay_"+$n).show();
                    setTimeout(function () {
                        jQuery.post(ajaxurl,
                            {att_id:$att_id,thumb_id:$att_thumb_id,slidernum:$slidernum,sec:inparr.ajaxNonce,action:'ls_ajax_delete_banner'},
                            function(data) {
                                jQuery("#bitem_"+$n).remove();
                                if(jQuery(".bitem").length == 0) addBannerAjax($slidernum, 0, false, jQuery("#skin_for_"+$slidernum).val(), 1);
                            }, "json"
                        );
                    }, 500);
                }
            });
            return false;
        });

        //Add new banner form
        jQuery(document).on('click', '.add_banner', function() {
            var $this        = jQuery(this),
            $this_parent     = $this.parent("div"),
            $slidernum       = $this.attr('id').replace(/banner_slider_/, ''),
            $skin_name       = jQuery("#skin_for_"+$slidernum).val(),
            $count_banners   = jQuery("#slidernum_"+$slidernum+" li.bitem").length,
            $count_enabled   = jQuery(".banner_switch option[value=1]:selected").length;
            if(jQuery("#static_first").length && parseInt(jQuery("#static_first").val()) == 1) $count_enabled+=1;
            $this_parent.addClass("bload");
            $this.attr('disabled','disabled');
            setTimeout(function () {
                addBannerAjax($slidernum,$count_banners,$this_parent,$skin_name,$count_enabled);
                $count_banners++;
                $this.removeAttr('disabled');
            }, 200);
            return false;
        });

        var addBannerAjax = function(slidernum, count_banners, removeEl, skin_name, count_enabled) {
            jQuery.post(ajaxurl,
                {count_banners:count_banners,slidernum:slidernum,count_enabled:count_enabled,skin_name:skin_name,sec:inparr.ajaxNonce,action:'ls_ajax_add_banner'},
                function(data) {
                    if(count_enabled < data.banners_limit && data.banner_item != false) {
                        if(removeEl) removeEl.removeClass("bload");
                        jQuery('#slidernum_'+slidernum).append(jQuery(data.banner_item).fadeIn('slow'));
                        var $tars = jQuery(data.banner_item).find('textarea:not(.ls_nmce)');
                        if($tars.length) {
                            $tars.each(function() {
                                tinyMCE.execCommand('mceAddControl', true, jQuery(this).attr('id'));
                            });
                        }
                        scrollToAnchor('anchor_'+slidernum+'_'+count_banners);
                    } else {
                        if(removeEl) removeEl.removeClass("bload");
                        jQuery.alert.open('error', inparr.fullBannersLimitError);
                    }
                }, "json"
            );
            return true;
        };

        //Checking values while form submiting
        jQuery("#ls_form").submit(function() {
            var $errors = false,
            $tmp_err = false;
            jQuery(".tcheck").each(function() {
                jQuery(this).removeClass('txt_error');
                var this_val = jQuery(this).val();
                if(this_val == '') {
                    jQuery(this).addClass('txt_error');
                    $errors = true;
                }
                if(this_val.length < minLength) {
                    jQuery(this).addClass('txt_error');
                    $errors = true;
                    $tmp_err = true;
                }
                var $name = jQuery(this).attr("id").split('_')[1];
                if($name == 'link') {
                    if(!isValidUrl(this_val)) {
                        jQuery(this).addClass('txt_error');
                        $errors = true;
                        $tmp_err = true;
                    }
                    else {
                        jQuery(this).removeClass('txt_error');
                        if($tmp_err) $errors = false;
                    }
                }
                if($tmp_err && jQuery.inArray(this_val, allowedUrlsArr) <= -1) {
                    jQuery(this).addClass('txt_error');
                    $errors = true;
                }
            });
            return ($errors)?false:true;
        });

        jQuery("#ls_skins_submit").submit(function() {
            if(jQuery(this).find("input[type=file]").val()!='') jQuery(this).submit();
            else return false;
        });

        //Delete skin
        jQuery(document).on('click', '.skin_allow_delete', function() {
            var $this = jQuery(this);
            jQuery.alert.open('confirm', inparr.confirmText, function(button) {
                if(button == 'yes') {
                    var $skin = $this.attr('id').replace(/skin_/, '');
                    jQuery.post(ajaxurl,
                        {skin:$skin,sec:inparr.ajaxNonce,action:'ls_ajax_delete_skin'},
                        function(data) {
                            if(data.res == true) {
                                jQuery("#skin_item_"+$skin).fadeOut('slow', function() {
                                    jQuery(this).remove();
                                });
                            }
                        }, "json"
                    );
                }
            });
        });

        var isValidUrl = function(data) {
            var pattern = new RegExp(/^((http|https):\/\/)?([a-z0-9\-]+\.)?([a-z0-9\-]+\.)?[a-z0-9\-]+\.[a-z0-9]{2,4}(\.[a-z0-9]{2,4})?(\/.*)?$/i);
            if(pattern.test(data) || (jQuery.inArray(data, allowedUrlsArr)) > -1) return true;
            return false;
        };
        
        var isValidYoutubeUrl = function(data) {
            //var pattern = new RegExp(/^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be/|youtube\.com(?:/embed/|/v/|/watch\?v=|/watch\?.+&v=))([\w-]{11})(?:.+)?$/x);
            //if(pattern.test(data) || (jQuery.inArray(data, allowedUrlsArr)) > -1) return true;
            return false;
        };

        var jDelCookie = function(cookieName) {
            jQuery.removeCookie(cookieName, {path:'/'});
            return false;
        };
        
        var lsGetAttachmentId = function(data) {
            var regexp = new RegExp(/wp-image-(\d+)/i),
            matches = regexp.exec(data);
            return parseInt(matches[1]);
        };

        var scrollToAnchor = function(id) {
            var $this_el  = jQuery("#"+id),
            $final_offset = $this_el.offset().top - ((jQuery(window).height() - $this_el.height())/2);
            jQuery('html,body').animate({scrollTop: $final_offset},'slow');
            return false;
        };
        var tb_init = function(domChunk) {
            jQuery(domChunk).live('click', tb_click);
        };
        
        var lsReplace = function(str, r) {
            return str.replace("{%torep%}", r);
        }
    };
    
    var ls_blinkurl = function($data, $slidernum, $n) {
        jQuery("#post_hidden_uth_ls_link_"+$slidernum+"_"+$n).html($data);
        jQuery("#post_hidden_ls_link_"+$slidernum+"_"+$n).html('');
        jQuery("#ls_link_"+$slidernum+"_"+$n).removeAttr("disabled").attr('name', 'binfo['+$slidernum+'][ls_link][]');
        jQuery("#url_type_id_"+$slidernum+"_"+$n).find('input').val('');
        return false;
    }
    
    var ls_blinktitle = function($data, $slidernum, $n) {
        jQuery("#post_hidden_uth_ls_title_"+$slidernum+"_"+$n).html($data);
        jQuery("#post_hidden_ls_title_"+$slidernum+"_"+$n).html('');
        jQuery("#ls_title_"+$slidernum+"_"+$n).removeAttr("disabled").attr('name', 'binfo['+$slidernum+'][ls_title][]');
        jQuery("#title_type_id_"+$slidernum+"_"+$n).find('input').val('');
        return false;
    }