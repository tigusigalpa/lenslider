=== Len Slider ===
Contributors: tigusigalpa
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=AYEX4C4M5YMWL&lc=US&item_name=LenSlider%20Wordpress%20Plugin&amount=3%2e00&currency_code=USD&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted
Tags: slider, carousel, slideshow, banners, wordpress slider, image slider, image slider plugin, javascript rotator, javascript slider, jquery rotator, photo rotator, photo slider, picture slider, rotator, shortcode, slider plugin, slideshow plugin, slider shortcode, carousel plugin, thumbnails, css3, youtube, vimeo, video, widget, skinnable
Requires at least: 3.5
Tested up to: 3.5.1
Stable tag: 2.0.9
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

LenSlider is a skins based slider/carousel/slideshow plugin with jQuery/CSS3 effects that allows to generate and easy integrate the ones to your site.

== Description ==

LenSlider is a WordPress plugin for creation of visual sliders, carousels or slideshows for your site without writing any code. All sliders based on LenSlider skins, and skins have effects based on jQuery and CSS3. Use a shortcode in your posts/pages or static php-code in your template files to integrate any slider you made.

= LenSlider Needs Your Support. Help to keep it updated =
* If you found a bug, you are welcomed to submit it to a special [**bug report form**](http://www.lenslider.com/report-bug/)
* If you have feature idea for a plugin, feel free to [**submit it**](http://www.lenslider.com/suggest-idea/)!
* If you like this plugin, [**Please Leave A Rating here**](http://wordpress.org/support/view/plugin-reviews/len-slider "WordPress LenSlider Rating").  Also, click "**Works**" (over to the right) if you are satisfied with the plugin.
* If you find this plugin useful to you, please consider [**making a small donation**](https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=AYEX4C4M5YMWL&lc=US&item_name=LenSlider%20Wordpress%20Plugin&amount=3%2e00&currency_code=USD&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted) to help contribute to my time invested and to further development. **Because it is hard to continue development and support for this free plugin** without contributions from users like you. Your donation will help encourage and support the plugin's continued development and better user support.

= Feel LenSlider2 power! =
LenSlider2 has been considerably enhanced with regard to users opinions and plans that were set right from the start. 

= Requirements =
* WordPress version 3.3 and higher, **highly recomended 3.5 version**
* PHP 5.2+ (tested with PHP Interpreter >= 5.2.17)
* PHP GD2 library installed
* Modern browser (IE 9+, Chrome 20+, Firefox 16+, Opera 11+, Safari 4+)

= LenSlider base Features =

* Free (but donations are very welcomed)
* Slider preview in admin panel via Thickbox
* Unlimited number of Sliders
* Admin AJAX support
* LenSlider based on php classes, making the one a scalable and secure for hacking and for the system as a whole

= LenSlider2 New Features =

* Disable for banners
* WordPress 3.5+ READY!
* YouTube & Vimeo support!

* Easy creation and management of sliders within the WordPress admin
* Skins based sliders
* Fast: The plugin doesn’t use any additional MySQL database table. It uses just one query + WordPress Cache support
* A selection of different default skins to choose from
* Slider Banners Sorter: Order your banners in slider using drag & drop AJAX
* SEO Friendly images name, alt support
* Slider and Skin Settings
* Comments for slider to make a slider known (recognizable) in a list
* Easy sliders navigation in admin panel
* No Need Of Knowledge of PHP, CSS or HTML. If you have knowledge of CSS, you can create your own stylesheet
* Shortcode and php-code to integrate sliders
* Thumbnail support
* Post/Page/Category link insert helper
* [User-friendly support](http://www.lenslider.com/forum/)

**There are 3 ways to integrate sliders:**

PHP-code:
`<?php if( class_exists( 'LenSlider' ) ) {LenSlider::lenslider_output_slider( 'SLIDER_HASH' );} ?>`
or
`<?php if( class_exists( 'LenSlider' ) ) {echo LenSlider::lenslider_output_slider( 'SLIDER_HASH', false );} ?>`
or by Shortcode button:
`[lenslider id="SLIDER_HASH"]`

[Support forum](http://www.lenslider.com/forum/)

**Credits**

* Uses [jQuery Cookie plugin](https://github.com/carhartl/jquery-cookie)
* Uses [jQuery mTip plugin](http://dev.mauvm.nl/mTip/)
* Uses [jQuery UI Tabs Rotate for jQuery UI 1.9+](https://github.com/cmcculloh/jQuery-UI-Tabs-Rotate)
* Uses [jQuery UI Toggle Switch](http://taitems.github.com/UX-Lab/ToggleSwitch/index.html)
* Thanks to [Andrew Gaewski](http://www.behance.net/busty_rusty) for UX admin design

**Immediate plans to improve the plugin (sorry, author dont have enough time yet):**

* MORE skins
* Several images for a slider
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

Full list of changes you can see at [LenSlider roadmap page](http://www.lenslider.com/roadmap/)

= 2.0.8 =
= a lot of fixes for Firefox

= 2.0.1 =
- Fix for settings page
- Fix for checking key in settings array

= 2.0 =
- New well UI design
- WordPress-standart list table for Slider list & dedicated page for every slider
- YouTube & Vimeo Support
- Media Uploader Support (New for 3.5+ version and old via Thickbox)
- Autorotate & autorotate delay managed in admin
- Margins manager
- Enable/disable for every banner

= 1.2 =
- js call admin fuction fix
- init scripts fix
- new skin `Nemo Target`
- WordPress 3.5+ ready for sliders autorotate
- dropdown posts fix

= 1.1.3 =
- fix for default skin

= 1.1.2 =
- support for PHP without short tags support

= 1.1.1 =
- Even uppercase letters for slider hash

= 1.1 =
- Random banners rotate in slider
- Fixed some bugs :)
- Some visual addons like delete slider list item with slider remove
- new RegExp for unusual URLs (e.g. for some free hostings with a lot of subdomains)
- allow to add # and javascript:; for banner link
- fully custom skins support

= 1.0.1 =
- Double links fix
- default skin link replaced
- some js admin errors

= 1.0.0 =
First version

== Upgrade Notice ==

= 2.0 =
Update your browser cache in admin (javascript and CSS cache: push Ctrl+F5 or Ctrl+R for Opera). NOTE: update your custom skins output.html, use only %path% variable to output images, remove <img /> tag!

= 1.2 =
Update your browser cache in admin (javascript and CSS cache: push Ctrl+F5 or Ctrl+R for Opera)