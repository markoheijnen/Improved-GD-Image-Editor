<?php
/*
	Plugin Name: Improved GD Image Editor
	Description: Adds some improved GD handling
	Version: 1.1

	Plugin URI: http://wordpress.org/extend/plugins/improved-gd-editor/
	
	Author: Marko Heijnen
	Author URI: https://github.com/markoheijnen/improved-gd-editor/
	Donate link: https://markoheijnen.com/donate
*/

function image_editors_add_improved_gd_editor( $editors ) {
	if ( ! class_exists( 'WP_Image_Editor_GD_Improved' ) ) {
		include_once 'editor.php';
	}

	array_pop( $editors );
	array_push( $editors, 'WP_Image_Editor_GD_Improved' );

	return $editors;
}

add_filter( 'wp_image_editors', 'image_editors_add_improved_gd_editor' );
