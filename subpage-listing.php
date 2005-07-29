<?php
/*
Plugin Name: Subpage Listing
Plugin URI: http://txfx.net/code/wordpress/subpage-listing/
Description: Displays a directory-like listing of subpages where &lt;!--%subpages%--&gt; exists in the content of pages.  Also, it will be displayed if a page is blank.
Author: Mark Jaquith
Version: 0.3
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

/* Many thanks to Owen Winker for his Edit Button Framework [ http://www.asymptomatic.net/wp-hacks ] */

function txfx_wp_subpage_display($text) {

/* --- SETTINGS ----------------------------------- */

$depth = 5; // how many levels down do you want to go?

/* ------------------------------------------------ */

if ( !is_page() ) return $text;

global $post;

if ( strpos($text, '<!--%subpages%-->') !== FALSE || empty($post->post_content) || $post->post_content == "<br />\n" ) {
	$subpage_text = wp_list_pages('child_of=' . $post->ID . '&depth=' . $depth . '&echo=0&title_li=0');

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
	<script language="JavaScript" type="text/javascript"><!--
		var toolbar = document.getElementById("ed_toolbar");

	<?php
	edit_insert_button("Subpage List", "txfx_subpage_list_display", "Subpage List");
	?>

	function txfx_subpage_list_display() {
		edInsertContent(edCanvas, '\n\n<!--%subpages%-->\n\n');	
	}

	//--></script>
	<?php endif;
}


if(!function_exists('edit_insert_button'))
{
	//edit_insert_button: Inserts a button into the editor
	function edit_insert_button($caption, $js_onclick, $title = '')
	{
	?>
	if(toolbar)
	{
		var theButton = document.createElement('input');
		theButton.type = 'button';
		theButton.value = '<?php echo $caption; ?>';
		theButton.onclick = <?php echo $js_onclick; ?>;
		theButton.className = 'ed_button';
		theButton.title = "<?php echo $title; ?>";
		theButton.id = "<?php echo "ed_{$caption}"; ?>";
		toolbar.appendChild(theButton);
	}
	<?php

	}
}


// For the quicktag button
add_filter('admin_footer', 'txfx_wp_subpage_display_js');

// doing it this way for compatibility with the Preformatted plugin
add_filter('init', create_function('$a', 'add_filter(\'the_content\', \'txfx_wp_subpage_display\', 9);'));

?>