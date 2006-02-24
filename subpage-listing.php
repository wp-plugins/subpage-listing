<?php
/*
Plugin Name: Subpage Listing
Plugin URI: http://txfx.net/code/wordpress/subpage-listing/
Description: Displays a directory-like listing of subpages where &lt;!--%subpages%--&gt; exists in the content of pages.  It will be displayed if a page is blank. <code>txfx_wp_subpages()</code> can be used to display subpages in the sidebar.  See this plugin's site for details.
Author: Mark Jaquith
Version: 0.6
Author URI: http://txfx.net
*/

/*  Copyright 2005  Mark Jaquith (email: mark.gpl@txfx.net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

function txfx_wp_subpage_display($text='', $depth=5, $show_parent=false, $show_siblings=false) {

	if ( !is_page() ) return $text;

	global $posts, $post;
	$post = $posts[0];

	$params = txfx_wp_subpages_tag_extraction($post->post_content);

	if ( is_array($params) || is_array($text) || '' == $text || '' == $post->post_content || $post->post_content == "<br />\n" ) {

		if ( isset($params[0]) )	$depth = $params[0];
		if ( isset($params[1]) )	$show_parent = $params[1];
		if ( isset($params[2]) )	$show_siblings = $params[2];

		$subpage_text = wp_list_pages('child_of=' . $post->ID . '&depth=' . $depth . '&echo=0&title_li=0');

		if ( $show_parent && $post->post_parent ) {
			$parent = &get_post($post->post_parent);
			$before = '<li class="page_item">&uarr;<a href="' . get_page_link($parent->ID) . '">' . wp_specialchars($parent->post_title) . '</a><ul>';
			$after = '</ul></li>';
		}

		if ( $show_siblings ) {
			$siblings = wp_list_pages('child_of=' . $post->post_parent . '&depth=1&echo=0&title_li=0');
			$subpage_text = preg_replace('#<li (.*?) href="' . get_permalink() . '"(.*?)</li>#', '<li $1 href="' . get_permalink() . '"' . '$2<ul>' . $subpage_text . '</ul></li>', $siblings); // insert marker for current post
		}

		// for the preformatted plugin, which will have wrapped the tag in a paragraph
		$text = preg_replace('#<p><!--%subpages(.*?)%--></p>#i', '<!--%subpages$1%-->', $text);

		if ( strpos($subpage_text, '</li>') === FALSE ) { // no subpages or siblings
			if ( !$show_parent || !$post->post_parent )
				return $text;
		} else { // subpages or siblings exist
			$output = "\n $subpage_text \n";
		}

		$output = $before . $output . $after; // add parent stuff

		if ( is_array($text) ) // if this is called via txfx_wp_subpages()
			$output = '<ul>' . $output . '</ul>';

		if ( strpos($text, '<!--%subpages') !== FALSE )
			return preg_replace('#<!--%subpages(.*?)%-->#', $output, $text);

		return $output;
	}

return $text;
}

function txfx_wp_subpages_tag_extraction($text) {
	if ( strpos($text, '<!--%subpages') === false )
		return false;
	preg_match('#<!--%subpages\(?([^)]*?)\)?%-->#i', $text, $matches);
	if ( !strlen($matches[1]) )
		return array('default', 'default', 'default');
	$params = explode(',', $matches[1]);
	return $params;
}


function txfx_wp_subpages($depth=5, $show_parent=false, $show_sibling=false, $before='<ul>', $after='</ul>', $echo=true) {
	$subpages = txfx_wp_subpage_display(array(), $depth, $show_parent, $show_sibling);

	if ( !$subpages )
		return false;

	$output = $before . $subpages . $after;

	if ( !$echo )
		return $output;
	echo $output;
}


function txfx_wp_subpage_display_js() {
global $post_status;
	if( strpos($_SERVER['REQUEST_URI'], 'page-new.php') ||  (strpos($_SERVER['REQUEST_URI'], 'post.php') && ($post_status == 'static')) ) : ?>
<script type="text/javascript">
<!--
function txfx_insertAtCursor(myField, myValue) {
		  //IE support
		  if (document.selection) {
			myField.focus();
			sel = document.selection.createRange();
			sel.text = myValue;
		  }
		  //MOZILLA/NETSCAPE support
		  else if (myField.selectionStart || myField.selectionStart == '0') {
			var startPos = myField.selectionStart;
			var endPos = myField.selectionEnd;
			myField.value = myField.value.substring(0, startPos)
						  + myValue 
						  + myField.value.substring(endPos, myField.value.length);
		  } else {
			myField.value += myValue;
		}
	}

document.getElementById("quicktags").innerHTML += "<input type=\"button\" class=\"ed_button\" id=\"txfx_subpages\" value=\"Subpage List\" onclick=\"txfx_insertAtCursor(document.post.content, '\\n\\n<!--%subpages%-->\\n\\n');\" />";
//-->
</script>

<?php endif;
}


// For the quicktag button
add_filter('admin_footer', 'txfx_wp_subpage_display_js');

// doing it this way for compatibility with the Preformatted plugin
add_filter('init', create_function('$a', 'add_filter(\'the_content\', \'txfx_wp_subpage_display\', 9);'));

?>