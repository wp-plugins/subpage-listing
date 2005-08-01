<?php
/*
Plugin Name: Subpage Listing
Plugin URI: http://txfx.net/code/wordpress/subpage-listing/
Description: Displays a directory-like listing of subpages where &lt;!--%subpages%--&gt; exists in the content of pages.  Also, it will be displayed if a page is blank.
Author: Mark Jaquith
Version: 0.5
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

function txfx_wp_subpage_display($text) {

/* --- SETTINGS ----------------------------------- */

$depth = 5; // how many levels down do you want to go?

/* ------------------------------------------------ */

if ( !is_page() ) return $text;

global $post;

if ( strpos($text, '<!--%subpages%-->') !== FALSE || empty($post->post_content) || $post->post_content == "<br />\n" ) {
	$subpage_text = wp_list_pages('child_of=' . $post->ID . '&depth=' . $depth . '&echo=0&title_li=0');

	// for the preformatted plugin, which will have wrapped the tag in a paragraph
	$text = str_replace('<p><!--%subpages%--></p>', '<!--%subpages%-->', $text);

	if ( strpos($subpage_text, '</li>') === FALSE )
		return str_replace('<!--%subpages%-->', '', $text);

	if ( strpos($text, '<!--%subpages%-->') !== FALSE ) {
		return str_replace('<!--%subpages%-->', "<ul>\n $subpage_text \n </ul>", $text);
	} else {
		return "<ul>\n $subpage_text \n </ul>";
	}
}

return $text;
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