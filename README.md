=== TWG AMP Related Posts ===
Contributors: Deepanker Verma
Tags: AMP, related posts, amp-content, related-posts, custom AMP plugin, AMP related posts
Requires at least: 5.0
Tested up to: 6.1
Requires PHP: 7.0
Stable tag: 1.0
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Donate link: https://example.com/donate
Plugin URI: https://example.com/twg-amp-related-posts
Author URI: https://example.com

== Description ==
TWG AMP Related Posts displays related posts on AMP pages with titles, images, and links for users to discover more content. This plugin integrates with AMP-enabled WordPress sites and ensures that related posts are only shown when viewing AMP content.

The related posts are displayed below the main content on single post pages, based on shared categories. If no post thumbnail is found for any related post, a placeholder image is shown instead.

== Features ==
- Automatically displays related posts on AMP pages.
- Fetches related posts based on categories.
- Displays a thumbnail (or a placeholder image if not available) along with the post title.
- Customizable related posts query to show a desired number of related posts.
- Compatible with the official AMP plugin for WordPress.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/twg-amp-related-posts` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. The related posts will automatically be shown on AMP-enabled posts.

== Frequently Asked Questions ==

= Will this plugin work with my theme? =

This plugin is designed to work with any theme that supports AMP. If your theme is AMP-compatible or you're using the official AMP plugin for WordPress, the related posts will display properly.

= How do I customize the number of related posts? =

The number of related posts displayed can be adjusted by modifying the `'posts_per_page'` parameter in the code. The default is set to 5 related posts.

= Can I change the placeholder image? =

Yes, you can replace the placeholder image located in the `/assets/placeholder.jpg` file within the plugin directory. Just upload your own image there to use as the placeholder.

== Screenshots ==

1. Screenshot showing related posts displayed below the main content on an AMP page.
2. Screenshot showing the related posts with a placeholder image.

== Changelog ==

= 1.0 =
* Initial release.

== Upgrade Notice ==

= 1.0 =
This is the first version of the plugin, which displays related posts on AMP pages with images and titles.

== Acknowledgments ==
- This plugin is built with the help of the official AMP plugin for WordPress.
- Placeholder image is provided by [placeholder.com](https://placeholder.com).

== Support ==
For support or questions, please visit the [plugin support page](https://example.com/support).
