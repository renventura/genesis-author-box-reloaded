<?php
/**
 *	Run various action and filter hooks
 *
 *	@package Genesis Author Box Reloaded
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 *	Globally disable default Genesis author box when the plugin is active
 */
add_filter( 'get_the_author_genesis_author_box_single', 'genesis_author_box_filter_default_author_box' );
add_filter( 'get_the_author_genesis_author_box_archive', 'genesis_author_box_filter_default_author_box' );
function genesis_author_box_filter_default_author_box( $default ) {
	return false;
}

/**
 *	Modify contact methods
 */
add_filter( 'user_contactmethods', 'genesis_author_box_reloaded_contact_methods' );
function genesis_author_box_reloaded_contact_methods( $fields ) {

	unset( $fields['url'] );
	unset( $fields['yim'] );
	unset( $fields['jabber'] );

	$links = genesis_author_box_reloaded_social_link_types();

	foreach ( $links as $link => $label ) {
		if ( ! isset( $fields[$link] ) ) {
			$fields[$link] = $label;
		}
	}

	return $fields;
}

/**
 *	Add the author box to the desired location
 */
add_action( 'init', 'genesis_author_box_reloaded_determine_hook' );
function genesis_author_box_reloaded_determine_hook() {

	$position = genesis_author_box_reloaded_get_position();

	// Bail if no position
	if ( ! $position ) {
		return;
	}

	$hook = apply_filters( 'genesis_author_box_reloaded_output_hook', $position );
	$priority = apply_filters( 'genesis_author_box_reloaded_output_hook_priority', 5 );

	// Bail if the output hook is not registered
	if ( ! array_key_exists( $hook, genesis_author_box_reloaded_get_output_hooks() ) ) {
		return;
	}

	add_action( $hook, 'genesis_author_box_reloaded_render_output', $priority );
}

/**
 *	Render the output
 */
function genesis_author_box_reloaded_render_output() {

	global $post;

	// Check if enabled before anything else
	$enabled = genesis_author_box_reloaded_is_enabled();

	if ( ! is_singular( $post->post_type ) ) {
		$enabled = false;
	}

	// Bail if disabled
	if ( ! $enabled ) {
		return;
	}

	// Proceed only if the current post's type is in the list of enabled post types
	if ( ! array_key_exists( $post->post_type, genesis_author_box_reloaded_get_post_types() ) ) {
		return;
	}

	// Get all the settings
	$author_id = $post->post_author;
	$options = get_option( 'genesis-settings' );
	$show_website = genesis_author_box_reloaded_show_website();
	$show_rss = genesis_author_box_reloaded_show_rss_link();
	$title_text = genesis_author_box_reloaded_render_title_text();
	$gravatar_url = genesis_author_box_reloaded_get_avatar_url();
	$bio = genesis_author_box_reloaded_get_bio();
	$links = genesis_author_box_reloaded_get_social_links();
	$icons = genesis_author_box_reloaded_get_link_icons();
	$avatar_enabled = genesis_author_box_reloaded_is_avatar_enabled();

	// Include markup
	$path = GENESIS_AUTHOR_BOX_PLUGIN_TEMPLATES_DIR_PATH . 'author-box-output.php';
	include apply_filters( 'genesis_author_box_output_template_path', $path );
}