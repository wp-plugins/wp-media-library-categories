=== Plugin Name ===
Contributors: jeffrey-wp
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=SSNQMST6R28Q2
Tags: category, categories, media, library, medialibrary, image, images, media category, media categories
Requires at least: 3.1
Tested up to: 3.9.2
Stable tag: 1.4.12
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds the ability to use categories in the media library.

== Description ==

Adds the ability to use categories in the WordPress Media Library. When activated a dropdown of categories will show up in the media library.
You can change the category of multiple items at once with bulk actions.

== Installation ==

For an automatic installation through WordPress:

1. Go to the 'Add New' plugins screen in your WordPress admin area
2. Search for 'Media Library Categories'
3. Click 'Install Now' and activate the plugin
4. A dropdown of categories will show up in the media library


For a manual installation via FTP:

1. Upload the 'Media Library Categories' directory to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' screen in your WordPress admin area
3. A dropdown of categories will show up in the media library


To upload the plugin through WordPress, instead of FTP:

1. Upload the downloaded zip file on the 'Add New' plugins screen (see the 'Upload' tab) in your WordPress admin area and activate.
2. Activate the plugin through the 'Plugins' screen in your WordPress admin area
3. A dropdown of categories will show up in the media library

== Frequently Asked Questions ==

= By default the WordPress Media Library uses the same categories as WordPress does (such as in posts & pages). How do I use separate categories for the WordPress Media Library? =
Add this code to the file functions.php located in your theme or child-theme:
`/**
* separate media categories from post categories
* use a custom category called 'category_media' for the categories in the media library
*/
add_filter( 'wpmediacategory_taxonomy', function(){ return 'category_media'; }, 1 ); //requires PHP 5.3 or newer
`


= Can i use category in the `[gallery]` shortcode, like `[gallery category="my-category-slug"]`? =
Yes, it is possible to extend the existing WordPress default `[gallery]` to only show images from one category by adding the `'category'` parameter. 
The value passed to the `'category'` parameter can be either the `category slug`, or the `term_id`.
`[gallery category="my-category-slug"]
OR
[gallery category="14"]`
Aside from this behavior, the `[gallery]` shortcode works as it does by default with the built-in shortcode.
If you use an incorrect slug by default WordPress shows the images that are attached to the page / post that is displayed. If you use an incorrect term_id no images are shown.
For more information on using the built-in [gallery shortcode checkout the codex page](http://codex.wordpress.org/Gallery_Shortcode).


= How can I filter on categories when inserting media into a post or page? =
This feature is only available in the [premium version](http://codecanyon.net/item/media-library-categories-premium/6691290?ref=jeffrey-wp)


= I want to thank you, where can I make a donation? =
Maintaining a plugin and keeping it up to date is hard work. Please support me by making a donation. Thank you.
[Please donate here](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=SSNQMST6R28Q2)

== Screenshots ==

1. Filter by category in the media library
2. Manage categories in the media library
3. Filter by category when inserting media [(premium version)](http://codecanyon.net/item/media-library-categories-premium/6691290?ref=jeffrey-wp)

== Changelog ==

= 1.4.12 =
* Add category option to the default WordPress shortcode gallery [Read the FAQ for howto](http://wordpress.org/plugins/wp-media-library-categories/faq/)
* Improve code styling to match WordPress code standard even more strictly

= 1.4.11 =
* Remember ordering when changing categories.

= 1.4.10 =
* Stay on active page in the media library when changing categories.
* Fixed PHP 5.4 strict warning.
* Added hierarchical display of the filter menu when inserting media. [(premium only)](http://codecanyon.net/item/media-library-categories-premium/6691290?ref=jeffrey-wp)

= 1.4.9 =
* Fixed error message which in some cases appears when updating multiple items at once. [View support question](http://wordpress.org/support/topic/error-after-latest-update-3)

= 1.4.8 =
* Fixed media count on the categories page.
* Add item count in the category filter dropdown when using separate categories for the WordPress Media Library.
* Support for WordPress 3.9

= 1.4.7 =
* New images are now added to the default category (if a default category exists). I most cases the default category is called "no category". [View support question](http://wordpress.org/support/topic/new-images-arent-automatically-in-uncategorized)

= 1.4.6 =
* Fixed bug where in some rare cases the filter by category didn't work

= 1.4.5 =
* Fixed bug in version 1.4.4 that made default categories in WordPress invisible

= 1.4.4 =
* By default the WordPress Media Library uses the same categories as WordPress does (such as posts & pages). Now you can use separate categories for the WordPress Media Library. [Read the FAQ for howto](http://wordpress.org/plugins/wp-media-library-categories/faq/)

= 1.4.2 & 1.4.3 =
* [(Premium only)](http://codecanyon.net/item/media-library-categories-premium/6691290?ref=jeffrey-wp)

= 1.4.1 =
* Improved bulk actions: added option to remove category from multiple media items at once
* Improved bulk actions: arranged options in option group

= 1.4 =
* Filter on categories when inserting media [(premium only)](http://codecanyon.net/item/media-library-categories-premium/6691290?ref=jeffrey-wp)

= 1.3.2 =
* [Added taxonomy filter](http://wordpress.org/support/topic/added-taxonomy-filter)
* [Don't load unnecessary code](http://dannyvankooten.com/3882/wordpress-plugin-structure-dont-load-unnecessary-code/)

= 1.3.1 =
* Fixed bug (when having a category with apostrophe)

= 1.3 =
* Add support for bulk actions (to change category from multiple media items at once)
* Support for WordPress 3.8

= 1.2 =
* Better internationalisation

= 1.1 =
* Add a link to media categories on the plugin page

= 1.0 =
* Initial release.