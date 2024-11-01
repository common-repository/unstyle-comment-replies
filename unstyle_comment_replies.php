<?php
/*
Plugin Name: Unstyle Comment Replies
Plugin URI: http://www.krues8dr.com/wordpress-unstyle-comment-replies-plugin/
Description: This plugin will remove comment reply classes and create custom classes for parents with zebra striping.
Version: 0.2b
Author: Bill Hunt
Author URI: http://krues8dr.com
*/

/*  Copyright 2009  Bill Hunt  (email : bill@krues8dr.com)

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
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/



/* Set some default values if this is the first time running. */
$not_first_time = get_option('kru_zebra_classes_run');

if(!$not_first_time) {
	do_unstyle_comment_replies_init();
}

function do_unstyle_comment_replies_init() {
	register_setting( 'kru_zebra_options', 'kru_remove_zebra_classes', '' );
	register_setting( 'kru_zebra_options', 'kru_add_zebra_classes', '' );
	register_setting( 'kru_zebra_options', 'kru_zebra_classes_run', '' );

	/* These are the correct values as of Wordpress 2.7.1 */
	add_option('kru_remove_zebra_classes', 
		join("\n",  
			array(
				'odd',
				'alt',
				'even'
			)
		)
	);
		
	/* These can be whatever. */
	add_option('kru_add_zebra_classes', 
		join("\n",  
			array(
				'even',
				'odd'
			)
		)
	);

	add_option('kru_zebra_classes_run', true);
}


/* Here's the main function */
function unstyle_comment_replies($classes, $class, $comment_id, $post_id) {
	// Keep your own counter
	static $comment_counter;
	
	// Default to true zero.
	if(!$comment_counter) {
		$comment_counter = 0;
	}
	
	// Classes to remove if depth > 1
	$catch_classes = get_option('kru_remove_zebra_classes');
	
	if($catch_classes) {

		// Split on newlines.
		$catch_classes = split("\n|\r", $catch_classes);
	}
	
	// Classes to add 
	// Note: you can list multiple classes per entry, space-separated
	// You can also use more than two.
	$zebra_stripe_class_list = get_option('kru_add_zebra_classes');
	
	
	if($zebra_stripe_class_list) {
	
		// Split on line breaks.  
		/* 
		 * NOTE: KNOWN ISSUE: This is going to have some problems if 
		 * people want blank classes in between entries. 
		*/
		$zebra_stripe_class_list = split("\r\n|\n|\r", $zebra_stripe_class_list);
	}


	foreach($classes as $index=>$current_class) {
		// Unset the existing even and odd classes
		if(is_array($catch_classes) && count($catch_classes)) {
			if(in_array($current_class, $catch_classes)) {
				unset($classes[$index]);
			}
		}
		
		if(preg_match('/^depth-([0-9]+)$/', $current_class, $matches)) {
			// If the depth is "1"
			if($matches[1] === "1") {
				
				// How many classes are there?
				if(is_array($zebra_stripe_class_list)) {
					$num_classes = count($zebra_stripe_class_list);
					
					if($num_classes) {
						if($comment_counter > 0) {
							// Get the modulus of the current comment count.
							$index = $comment_counter % $num_classes;
						} else {
							$index = 0;
						}
						
						// The modulus becomes the current class index
						$new_class = $zebra_stripe_class_list[$index];
						
						// Add the new class
						if(strlen($new_class)) {
							$classes[] = $new_class;
						}
					}
				}
				// Increment the counter
				$comment_counter++;
			}
		}
	}
		
	return $classes;
}

add_filter('comment_class', 'unstyle_comment_replies', 1, 4); 



/* Admin junk */

// We do this by default anyway.
//function unstyle_comment_replies_admin_init(){
//	register_setting( 'kru_zebra_options', 'kru_remove_zebra_classes', '' );
//	register_setting( 'kru_zebra_options', 'kru_add_zebra_classes', '' );
//	register_setting( 'kru_zebra_options', 'kru_zebra_classes_run', '' );
//}
//add_action('admin_init', 'unstyle_comment_replies_admin_init' );


function unstyle_comment_replies_menu() {
	add_options_page('Unstyle Comment Reply Options', 'Unstyle Comment Replies', 8, __FILE__, 'unstyle_comment_replies_options');
}
add_action('admin_menu', 'unstyle_comment_replies_menu');


function unstyle_comment_replies_options() {
	require(dirname(__FILE__).'/options.php');
}


?>