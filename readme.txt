=== Plugin Name ===
Contributors: jeffrey-wp
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=SSNQMST6R28Q2
Tags: category, categories, media, library, medialibrary
Requires at least: 3.1
Tested up to: 3.8.1
Stable tag: 1.4.7
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

= Where can I request new features? =

You can request new features on the [support page](http://wordpress.org/support/plugin/wp-media-library-categories)

= I want to thank you, where can I make a donation? =
Maintaining a plugin and keeping it up to date is hard work. Please support me by making a donation. Thank you.
[Please donate here](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=SSNQMST6R28Q2)

= How can I filter on categories when inserting media into a post or page? =
This feature is only available in the [premium version](http://codecanyon.net/item/media-library-categories-premium/6691290?ref=jeffrey-wp)

= By default the WordPress Media Library uses the same categories as WordPress does (such as in posts & pages). How do I use separate categories for the WordPress Media Library? =
Add this code to the file functions.php located in your theme or child-theme:
`/**
* separate media categories from post categories
* use a custom category called 'category_media' for the categories in the media library
*/
add_filter( 'wpmediacategory_taxonomy', function(){ return 'category_media'; }, 1 ); //requires PHP 5.3 or newer
`

== Screenshots ==

1. Filter by category in the media library
2. Manage categories in the media library
3. Filter by category when inserting media [(premium version)](http://codecanyon.net/item/media-library-categories-premium/6691290?ref=jeffrey-wp)

== Changelog ==

= 1.4.7 =
* New images are now added to the default category (if a default category exists). I most cases the default category is "Uncategorized".(http://wordpress.org/support/topic/new-images-arent-automatically-in-uncategorized)

= 1.4.6 =
* fixed bug where in some rare cases the filter by category didn't work

= 1.4.5 =
* fixed bug in version 1.4.4 that made default categories in WordPress invisible

= 1.4.4 =
* By default the WordPress Media Library uses the same categories as WordPress does (such as posts & pages). Now you can use separate categories for the WordPress Media Library. [see the FAQ for howto](http://wordpress.org/plugins/wp-media-library-categories/faq/)

= 1.4.2 & 1.4.3 =
* [(premium only)](http://codecanyon.net/item/media-library-categories-premium/6691290?ref=jeffrey-wp)

= 1.4.1 =
* improved bulk actions: added option to remove category from multiple media items at once
* improved bulk actions: arranged options in option group

= 1.4 =
* filter on categories when inserting media [(premium only)](http://codecanyon.net/item/media-library-categories-premium/6691290?ref=jeffrey-wp)

= 1.3.2 =
* [added taxonomy filter](http://wordpress.org/support/topic/added-taxonomy-filter)
* [don't load unnecessary code](http://dannyvankooten.com/3882/wordpress-plugin-structure-dont-load-unnecessary-code/)

= 1.3.1 =
* fixed bug (when having a category with apostrophe)

= 1.3 =
* add support for bulk actions (to change category from multiple media items at once)
* support for WordPress 3.8

= 1.2 =
* better internationalisation

= 1.1 =
* add a link to media categories on the plugin page

= 1.0 =
* initial release.