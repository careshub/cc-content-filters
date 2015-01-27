<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * Dashboard. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.communitycommons.org
 * @since             1.0.0
 * @package           CC Content Filters
 *
 * @wordpress-plugin
 * Plugin Name:       CC Content Filters
 * Plugin URI:        http://example.com/plugin-name-uri/
 * Description:       Content-related filters and short codes.
 * Version:           1.0.0
 * Author:            David Cavins
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cc-content-filters
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * Creates instance of CC_Functionality_BP_Dependent_Extras
 * BuddyPress-dependent filters should be added to this class.
 *
 * @package CC Functionality Plugin
 * @since 1.0.0
 */

function cc_content_filters_class_init(){
	// Get the class fired up
	require( dirname( __FILE__ ) . '/class-cc-content-filters.php' );
	add_action( 'bp_include', array( 'CC_Content_Filters', 'get_instance' ), 17 );
}
add_action( 'bp_include', 'cc_content_filters_class_init' );