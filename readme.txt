=== Subpage Listing ===
Contributors: markjaquith
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=mark%2epaypal%40txfx%2enet&item_name=Mark%20Jaquith%20Coding&no_shipping=0&no_note=1&tax=0&currency_code=USD&charset=UTF%2d8&charset=UTF%2d8
Tags: pages, subpages, hierarchy, tree
Requires at least: 2.0
Tested up to: 2.1.2
Stable tag: trunk

Allows you to display a list of the child pages of the currently viewed page.

== Description ==

Subpage Listing allows you to take full advantage of WordPress Pages' hierarchy by generating a navigational tree of the pages below them (subpages).

For example, say you have a parent page called "Parent." Now, say you have 3 pages under "Parent," called "Child1," "Child2," and "Child3." Now, say that you have 2 pages under "Child2" called "Grandchild1" and "Grandchild2."

Subpage Listing would create a navigation tree for "Parent" that looks like this:

* Child1
* Child2
** Grandchild1
** Grandchild2
* Child3

This allows you to very easily create a complex hierarchical structure that can be browsed.

== Installation ==

1. Upload `plugin-name.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Place `<!--%subpages%-->` in an page's content and it will be replaced with a hierarchical list of subpages
4. See "Advanced Usage" for more detail

== Advanced Usage ==

Blank pages will automatically be given a navigation tree. This allows you to quickly create "container" pages (all you do is fill in a title, and choose the page's parent.)

If you would like to insert the navigational tree manually (that is, surrounded by text of your choosing), use the "Subpage Listing" quicktag that will show up on the Write Page screen (sorry, no RTE support at this time). This will insert the tag: <!--%subpages%--> which will be replaced by the listing of the subpages.

Note: when inserting the tag manually, make sure that there is a blank line both above and below the tag, so as to ensure proper handling of your surrounding paragraphs by WordPress.

Version 0.6 was a massive update that added a bunch of new functionality. The ability to show the current page's parent has been added, as well as the ability to show the current page's siblings. You can also suppress the showing of children, and show only siblings, parents, or both. To use these features within a post, use this syntax: <!--%subpages(5,1,1)%--> The first "parameter" is the depth you want to show. You can set this to 0 to suppress display of children. The second "parameter" is a boolean switch for display of the parent page. The third "parameter" is a boolean switch for display of sibling pages. All are optional, although if you want to set the second "parameter", you also have to set the first, and if you want to set the third, you have to set all three.

There is also a new function for use in your templates. Many people wanted to show subpages or siblings in their sidebar, so now you can do that. `<?php txfx_wp_subpages(); ?>` is the most basic form, but it can take many parameters.

`<?php txfx_wp_subpages(5, false, false, '<ul>', '</ul>', true); ?>` will show 5 pages deep (first parameter), hide the parent (second parameter), hide the siblings (third parameter), wrap the whole thing in '<ul>' and '</ul>' (fourth and fifth parameters), and will echo the result (sixth parameter). These happen to be the default settings, that I have just illustrated.