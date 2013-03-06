    var ls_wp_pointer = function(id, handle, content, position, pointerWidth) {
        if(jQuery(id).length) {
            position = (!position)?{'edge':'top','align':'left'}:position;
            pointerWidth = (!pointerWidth)?320:pointerWidth;
            var options = {
                content: content,
                position: position,
                pointerWidth: pointerWidth
            }
            options = jQuery.extend(options, {
                close: function() {
                    jQuery.post(ajaxurl, {
                        pointer: handle,
                        action: 'dismiss-wp-pointer'
                    });
                }
            });
            jQuery(id).pointer(options).pointer('open');
        }
    }