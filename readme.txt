=== Coupon Creator ===
Contributors: brianjessee
Plugin Name: Coupon Creator
Plugin URI: http://jesseeproductions.com/coupon-creator/
Tags: custom post type, coupon, shortcode
Requires at least: 3.5
Tested up to: 3.6
Stable tag: 1.41
License: GPLv2
License URI: http://www.opensource.org/licenses/GPL-2.0

Create coupons and display on your site by using a shortcode. Customize the look or use an image.

== Description ==

Create your own coupon with the Coupon Creator for WordPress or upload an image of a coupon instead.

Create a coupon by going to the coupon custom post type and filling in all the settings in the custom meta box.

Insert the coupon into a post or page using a shortcode.

Coupon displays until the expiration date chosen by you.

If you check the Ignore Expiration Checkbox the coupon will display on the site past the expiration date or with no date at all.

The Coupon Shortcode:
	[coupon couponid="xx" category="Category Name(optional)" coupon_align="cctor_aligncenter" name="Coupon Name"]

Manually replace fields in shortcode:

couponid - replace xx with ID of Coupon custom post

couponalign - align coupon options:  cctor_aligncenter,  cctor_alignnone,  cctor_alignleft, and  cctor_alignright

name -optional and for your reference only

Coupon Loop (Version 1.40)

Set couponid to "loop" to display all coupons. (couponid="loop")

All the coupons in the loop will use the same couponalign.

Coupon Categories (Version 1.40)

Assign categories to a coupon and with the loop option only display coupons from a specific category.

category - add the category name to display coupons only from it

For example if you have a category called Coupon Home Page, call it by:

category="Coupon Home Page"

Coupon Shortcode Inserter

Above the post editor click the "Add Coupon" button to open the inserter.

Select an individual coupon or coupon loop.

If you select coupon loop an option will appear to select a specific category for the loop or leave it blank for all coupons (default).

The third option to select is the couponalign.

Once you have all the options selected, press "Insert Coupon" to create the shortcode into the editor.

Find examples of coupons on the [Coupon Creator Home Page](http://jesseeproductions.com/coupon-creator/)

Coupons will work in Sidebar Text Widgets, but make sure to add

add_filter('widget_text', 'do_shortcode');

To your theme's function.php

== Installation ==

1. Upload `/coupon_creator/` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Create a coupon under the coupon custom post type and insert shortcode into content using shortcode
	[coupon couponid="xx" coupon_align="cctor_aligncenter" name="Coupon Name"]

== Screenshots ==

1. Coupons Displayed on a Website
2. Custom Meta Box to Create a Coupon with Open Date Picker
3. Custom Meta Box with Image as the Coupon
4. Custom Meta Box to Create a Coupon with Open Color Picker
5. Coupon Shortcode Inserter on Editor
6. Shortcode in WordPress Editor

== Changelog ==
= 1.41 =
* Added Post Reset Function to the shortcode to fix an issue preventing comments from showing

= 1.40 =
* Added a loop option to the shortcode to display more then one coupon
* Added coupon categories to use with the loop option of the shortcode
* Fixed css for box-sizing: content-box for the coupons or in themes like Twenty Thirteen the styling broke
* Added plugin version into WordPress Database options for future updates
* Updated Colobox script and fixed background images

= 1.37 =
* Fixed SVN to latest version

= 1.35 =
* Changed Insert Coupon Icon on Editor for WordPress 3.5
* Added CSS for inside .widget-wrap to fit 100% into space
* Coupon Images are now links to larger view. Thanks to the coding from Darin of VzPro
* Made the Click to Open in Print View font a little larger

= 1.31 =
* Modifed Click to Open in New Window Coding so it will open new window without javascript.
* Note this may not open a new window in all browsers.

= 1.30 =
* Added checkbox to ignore expiration date so coupon will always display one website
* "Expire On" will not show if no expiration is added
* Added some more comments to coding and fixed line spacing issue
* Added donation link

= 1.20 =
* Bug fixes to remove php notices in shortcode and in meta box

= 1.1.5 =
* Fixed SVN to latest version

= 1.1 =
* Bug Fixes preventing images, js, and css from loading - Thanks for heads up from Tom Ewer of WPMU.org

= 1.0 =
* Initial Release and 1st Version and 1st Plugin!

== Upgrade Notice ==
= 1.41 =
New Shortcode Options: Coupon Loop with Coupon Categories.(1.41 fixes comment section conflict)


== Frequently Asked Questions ==
How big of an image is the coupon?
There are two sizes, but the image uploaded should be at least 400 pixels by 200 pixels to display correctly.

What if I have support questions?
Please use the [Coupon Creator Support Forum](http://wordpress.org/support/plugin/coupon-creator) on WordPress.