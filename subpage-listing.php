<?php
/*
Plugin Name: Subpage Listing
Plugin URI: http://txfx.net/code/wordpress/subpage-listing/
Description: Displays a directory-like listing of subpages where &lt;!--%subpages%--&gt; exists in the content of pages.  Also, it will be displayed if a page is blank.
Author: Mark Jaquith
Version: 0.2
Author URI: http://txfx.net
*/

function txfx_wp_subpage_display($text) {
global $post;

$depth = 5; // how many levels down do you want to go?

if ( !is_page() ) return $text;

$options = 'child_of=' . $post->ID . '&depth=' . $depth . '&echo=0&title_li=0';

if ( strpos($text, '<!--%subpages%-->') !== FALSE ) {
    $subpage_text = wp_list_pages($options);
    $text = str_replace('<!--%subpages%-->', "<ul>\n $subpage_text \n </ul>", $text);
} elseif ( empty($post->post_content) || $post->post_content == "<br />\n" ) {
    $subpage_text = wp_list_pages($options);
    $text = "<ul>\n $subpage_text \n </ul>";    
}

return $text;    
}

// doing it this way for compatibility with the Preformatted plugin
add_filter('init', create_function('$a', 'add_filter(\'the_content\', \'txfx_wp_subpage_display\', 9);'));

?>