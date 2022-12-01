=== Tematres WP Integration Plugin ===
Contributors: becahp, lucasrodri
Tags: Tematres, Custom Tags
Requires PHP: 7.4
Requires at least: 5.4
Tested up to: 6.1
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Plugin that implements the integration of a Tematres with WordPress.

== Description ==

When informing a Tematres API URL, WP recognizes the terms registered in Tematres as Tags available to publish to posts.

= Usage =

Install and activate the plugin. Go to the "Tematres WP Integration" menu in the panel and configure the requirements:
- Tematres API URL
- Tag Name
- Post where the tags will be applied

= FrontEnd Observation =

To return the tags in the frontend of a post which uses, for example, the ``get_the_tag_list`` function (as the Twenty Twenty One Theme) of WordPress, it is necessary to manually change the theme's template files, to not call this function, since it exclusively calls tags of type `post_tag`, which are standard in WP. So we created the functions `has_tag_thematres_wp` and `tmwpi_get_the_tag_list` that look for the tag created by the plugin.


Usage example in Twenty Twenty One theme:

``
    if ( has_category() || has_tag() || has_tag_tematres_wp() ) {
        ...
        if ( function_exists( 'tmwpi_get_the_tag_list' ) ) {
            $tags_list = tmwpi_get_the_tag_list( '', __( ' ', 'twentytwentyone' ) );
        } else {
            $tags_list = get_the_tag_list( '', __( ' ', 'twentytwentyone' ) );
        }
        ...
    }
``

= GitHub =

Please reach out to make pull requests or issues on the <a href="https://github.com/becahp/tematres-wp-integration">Tematres WP Integration GitHub repository</a>.

== Screenshots ==
1. Settings page.
2. Tematres Tag metabox at the post or custom post type edit page.