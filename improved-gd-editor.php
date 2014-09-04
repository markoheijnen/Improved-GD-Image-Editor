<?php
/*
Plugin Name: Improved GD Image Editor
Plugin URI: http://wordpress.org/extend/plugins/improved-gd-editor/
Description: Adds some improved GD handling
Author: Marko Heijnen
Version: 1.0
Author URI: https://github.com/markoheijnen/improved-gd-editor/
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
