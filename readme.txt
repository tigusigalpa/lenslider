=== Len Slider ===
Contributors: tigusigalpa
Tags: slider, carousel, slideshow, banners, video slider, image slider, image slider plugin, javascript rotator, javascript slider, jquery rotator, photo rotator, Photo Slider, picture slider, rotator, shortcode, slider plugin, slideshow plugin, slider shortcode, carousel plugin, thumbnails, css3, css
Requires at least: 3.3
Tested up to: 3.4.2
Stable tag: 1.0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

LenSlider is a skins based slider/carousel/slideshow plugin with jQuery/CSS3 effects that allows to generate and easy integrate the ones to your site.

== Description ==

LenSlider is a WordPress plugin for creation of visual sliders, carousels or slideshows for your site without writing any code. All sliders based on LenSlider skins, and skins have effects based on jQuery and CSS3. Use a shortcode in your posts/pages or static php-code in your template files to integrate any slider you made.

**Features**

* Easy creation and management of sliders within the WordPress admin
* Skins based sliders
* Unlimited number of Sliders
* Slider preview in admin panel via Thickbox
* Fast: The plugin doesn’t use any additional MySQL database table. It uses just one query + WordPress Cache support
* A selection of different default skins to choose from
* Slider Banners Sorter: Order your banners in slider using drag & drop AJAX
* Responsive
* SEO Friendly
* Admin AJAX support
* Slider and Skin Settings
* Comments for slider to make a slider known (recognizable) in a list
* Easy sliders navigation in admin panel
* No Need Of Knowledge of PHP, CSS or HTML. If you have knowledge of CSS, you can create your own stylesheet
* Shortcode and php-code to integrate sliders
* Thumbnail support
* Post/Page/Category link insert helper
* Free
* LenSlider based on php classes, making the one a scalable and secure for hacking and for the system as a whole
* [User-friendly support](http://www.lenslider.com/support/)

**There are 3 ways to integrate sliders:**

PHP-code:
`<?php if( class_exists( 'LenSlider' ) ) {LenSlider::lenslider_output_slider( $id );} ?>`
or
`<?php if( class_exists( 'LenSlider' ) ) {echo LenSlider::lenslider_output_slider( $id, false );} ?>`
or by Shortcode button:
`[lenslider id=""]`

[Support forum](http://www.lenslider.com/forum/)

**Credits**

* Uses [jQuery Cookie plugin](https://github.com/carhartl/jquery-cookie)
* Uses [jQuery tipsy plugin](http://onehackoranother.com/projects/jquery/tipsy/)
* Uses [jQuery One Page Nav Plugin](http://github.com/davist11/jQuery-One-Page-Nav)
* Uses [jQuery scrollTo](http://flesler.blogspot.com/2007/10/jqueryscrollto.html) as a part of `One Page Nav`
* Uses [jQuery Alert Dialogs Plugin](http://www.abeautifulsite.net/blog/2008/12/jquery-alert-dialogs/)

**Immediate plans to improve the plugin (sorry, author didnt have enough time yet):**

* MORE skins
* Better admin UI (user interface)
* Slider output view settings (for example: padding, margins, background color, width etc)
* Slider JavaScript settings (autoplay on/off, time limit for slider banners etc)
* Upload images from WP media-library
* Several images for a slider
* Easy to make sliders based on post/page/custom post type data
* The ability to use sliders without images - just content slider
* Widget support
* HTML-editor support
* Some wonderful but secret yet plans

== Installation ==

1. Search for "Len Slider" in the Add New Plugin section of your WordPress site admin panel or manually upload the `len-slider` folder (unziped) to the `/wp-content/plugins/` directory
2. Activate the plugin
3. Create Sliders under the new **LenSlider** Menu
4. Use the shortcode `[lenslider]` (shortode button is already installed on your WYSIWYG bar) in the content area of a page or post where you want the image slider to appear or PHP code `<?php if( class_exists( 'LenSlider' ) ) {LenSlider::lenslider_output_slider( 'your slider hash ID here - with lower(!) case letters' );}?>` in your template files
5. Enjoy!


== Frequently Asked Questions ==

= I’ve made slider with default template, then decided to change the skin, chose the required one, saved the data and the slider became disabled. =

Yes, this is right. Activate the slider with help of radiobuttons and save the data. Note: when you change the skin of the slider, it becomes inactive (turned off), because each skin has its own settings that may not correspond to the slider’s filled fields. When you save the data again the mistakes can be revealed and corrected afterwards.

= What does "maximum width of uploaded image, px" mean? =

Each banner of the slider has some image/photo and it has maximal (recommended) width in pixels (usually on websites width dominates over height). This figure limits the width of the image that you download, i.e. if the image is wider, then it will be automatically resized to the required width with preserved ratio. In case the width is equal to the maximal possible parameter or smaller than that, then the image will not change. One can set any figure in the range 30-1200 pixels.

= How can I insert necessary slider with shortcode if I do not remember its hash-number? =

If you have installed plugin, then the button LenSlider should appear in the admin panel in the section of adding new post/page/custom post type in the bar WYSIWYG of the editor. Press this button and you will understand how it works.

= What does "Maximal quality of uploaded image" mean? =

The default parameter is 90. That means that the image that you download will lose in quality a little, but its weight in kilobytes will be lower and this will increase download speed of a page with a slider. In other words, this is a small contribution to download speed in client’s area. You can change this figure from 60 to 100.

= Can I display multiple sliders on one page? =

Yes you can!

= Where can I edit CSS for slider and how can I change width and height of the slider’s block? =

All files of skins are in the folder `lenslider/skins/your-skin-name`, and css-file, js-scripts and technical images are located in the folder `lenslider/skins/your-skin-name/output`. Unfortunately, this is not possible to change slider’s CSS parameters In the current version of plugin, such as margin, padding and so on, but *it is planned to develop the plugin and add these features in the future*.

= What kind of setting is "floating hint tipsy"? =

If you switch on this option hints in the admin LenSlider will be shown in nice floating title with help of jQuery-plugin [tipsy](http://onehackoranother.com/projects/jquery/tipsy/).

== Screenshots ==

1. Sliders admin manager. Skin `Vania Fit`
2. `Vania Fit` skin output
3. Sliders admin manager. Skin `Dares Serpent`
4. `Dares Serpent` skin output
5. Sliders admin manager. `Default` skin

== Changelog ==

= 1.0.0 =
First version

= 1.0.1 =
Fixed much of bugs:
* Double links
* `default` skin link replaced
* some js admin errors