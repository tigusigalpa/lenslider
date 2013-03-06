function Slider( container ) {
    this.container  = container.find('.ls_dar_ser_wrapper');

    this.block      = this.container.find('ul');
    this.blocks     = this.block.find('li');
    this.blockWidth = this.blocks.first().width();
    this.blocksLen  = this.blocks.length;

    this.current    = 1;

    var fEl         = this.blocks.first().clone(),
        lEl         = this.blocks.last().clone();

    this.block.append(fEl);
    this.block.prepend(lEl);
    this.block.css('margin-left', -this.blockWidth);

    this.blocksLen += 2;
}

Slider.prototype.transition = function( coords ) {
    if(this.current === this.blocksLen - 1) {
        var self = this;

        this.container.find('ul').animate({
            'margin-left' : coords || -( this.current * self.blockWidth )
        },
        800,
        function() {
            jQuery(this).css('margin-left', -self.blockWidth);
        });

        this.current = 1;
    } else if(this.current === 0) {
        var self = this;

        this.container.find('ul').animate({
            'margin-left' : coords || -( this.current * this.blockWidth )
        },
        800,
        function() {

            jQuery(this).css('margin-left', -(self.blockWidth * (self.blocksLen - 2) ));
        });

        this.current = this.blocksLen - 2;        
    }
    else {
        this.container.find('ul').animate({
            'margin-left' : coords || -( this.current * this.blockWidth )
        },
        800);
    }
};

Slider.prototype.setCurrent = function( dir ) {
    var pos = this.current;

    pos += ( ~~( dir ) || -1 );
    this.current = ( pos < 0 ) ? this.blocksLen - 1 : pos % this.blocksLen;

    return pos;
};

var timeOut;
function auto( obj, time, hover ) {
    if( !hover ) {
        timeOut = setTimeout(function() {
            obj.setCurrent( true ); 
            obj.transition(); 

            auto( obj, time );
        }, time);
    }
    else {
        clearTimeout(timeOut);
    }
}

var dares_serpent_lenslider_fn = function(inparr) {
    var slider = new Slider( jQuery('.ls_dar_ser_ibanner') );
    jQuery('.ls_dar_ser_ibanner').find('.ls_dar_ser_ibanner_nav').on('click', function() {
        slider.setCurrent( jQuery(this).hasClass('right') );
        slider.transition();
    });
    auto( slider, inparr.slideshow.ls_autoplay_delay, false );
    jQuery('.ls_dar_ser_ibanner').hover(
        function() { auto( slider, inparr.slideshow.ls_autoplay_delay, true ); },
        function() { auto( slider, inparr.slideshow.ls_autoplay_delay, false ); }
    );
}
