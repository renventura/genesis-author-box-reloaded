<?php
/**
 *	Helper functions
 *
 *	@package Genesis Author Box Reloaded
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 *	Get license data
 */
function genesis_author_box_reloaded_get_license_data() {
	$data = get_option( 'genesis_author_box_reloaded_license' );
	return $data;
}

/**
 *	Get license transient
 */
function genesis_author_box_reloaded_get_license_transient() {

	$transient = get_transient( 'genesis_author_box_reloaded_license_transient' );

	if ( ! $transient ) {
		$transient = false;
	}

	return $transient;
}

/**
 *	Whether the author box feature is enabled
 */
function genesis_author_box_reloaded_is_enabled() {
	$options = get_option( 'genesis-settings' );
	$enabled = isset( $options['genesis_author_box_reloaded_enable'] ) ? intval( $options['genesis_author_box_reloaded_enable'] ) : '';
	return $enabled;
}

/**
 *	Whether to show an author's avatar
 */
function genesis_author_box_reloaded_is_avatar_enabled() {
	$options = get_option( 'genesis-settings' );
	$avatar_enabled = isset( $options['genesis_author_box_reloaded_display_gravatar'] ) ? true : false;
	return $avatar_enabled;
}

/**
 *	Whether the author box links to author's website
 */
function genesis_author_box_reloaded_show_website() {
	$options = get_option( 'genesis-settings' );
	$enabled = isset( $options['genesis_author_box_reloaded_display_website'] ) ? intval( $options['genesis_author_box_reloaded_display_website'] ) : '';
	return $enabled;
}

/**
 *	Whether the author box links to author's RSS feed
 */
function genesis_author_box_reloaded_show_rss_link() {
	$options = get_option( 'genesis-settings' );
	$enabled = isset( $options['genesis_author_box_reloaded_display_rss_link'] ) ? intval( $options['genesis_author_box_reloaded_display_rss_link'] ) : '';
	return $enabled;
}

/**
 *	Get the post types on which the author box is enabled
 */
function genesis_author_box_reloaded_get_post_types() {
	$options = get_option( 'genesis-settings' );
	return isset( $options['genesis_author_box_reloaded_post_types'] ) ? $options['genesis_author_box_reloaded_post_types'] : array();
}

/**
 *	Get the available social links
 */
function genesis_author_box_reloaded_social_link_types() {

	$types = array(
		'twitter' => __( 'Twitter', 'genesis-author-box-reloaded' ),
		'facebook' => __( 'Facebook', 'genesis-author-box-reloaded' ),
		'linkedin' => __( 'Linkedin', 'genesis-author-box-reloaded' ),
		'pinterest' => __( 'Pinterest', 'genesis-author-box-reloaded' ),
		'googleplus' => __( 'Google+', 'genesis-author-box-reloaded' ),
	);

	return apply_filters( 'genesis_author_box_reloaded_social_link_types', $types );
}

/**
 *	Get the social links enabled on the author box
 */
function genesis_author_box_reloaded_get_social_links() {

	$options = get_option( 'genesis-settings' );

	if ( isset( $options['genesis_author_box_reloaded_social_links'] ) ) {

		$return = array();
		
		foreach ( $options['genesis_author_box_reloaded_social_links'] as $key => $val ) {

			$return[$key] = $val;
		}

	} else {
		$return = '';
	}

	return $return;
}

/**
 *	Get the link icons
 */
function genesis_author_box_reloaded_get_link_icons() {

	$links = genesis_author_box_reloaded_social_link_types();

	if ( ! $links ) {
		return;
	}

	$icons = array();

	foreach ( $links as $key => $label ) {
		$icons[$key] = GENESIS_AUTHOR_BOX_PLUGIN_DIR_URL . "assets/images/{$key}.png";
	}

	return apply_filters( 'genesis_author_box_reloaded_link_icons', $icons, $links );
}

/**
 *	Check to see if the user has set any of their social profile links
 */
function genesis_author_box_reloaded_author_has_profiles( $author_id = null ) {

	if ( ! $author_id ) {
		return;
	}

	$profiles = genesis_author_box_reloaded_social_link_types();

	$return = false;

	foreach ( $profiles as $key => $val ) {
		if ( get_the_author_meta( $key, $author_id ) ) {
			$return = true;
		}
	}

	if ( get_the_author_meta( 'user_url', $author_id ) ) {
		$return = true;
	}

	if ( genesis_author_box_reloaded_show_rss_link() ) {
		$return = true;
	}

	return $return;
}

/**
 *	List of Genesis hooks for outputting the author box (i.e. Before Entry, After Entry, etc.)
 */
function genesis_author_box_reloaded_get_output_hooks() {
	
	$hooks = array(
		'genesis_before_entry' => __( 'Before Entry', 'genesis-author-box-reloaded' ),
		'genesis_after_entry' => __( 'After Entry', 'genesis-author-box-reloaded' )
	);

	return apply_filters( 'genesis_author_box_reloaded_output_hooks', $hooks );
}

/**
 *	Get the position for the author box output
 */
function genesis_author_box_reloaded_get_position() {
	$options = get_option( 'genesis-settings' );
	return isset( $options['genesis_author_box_reloaded_position'] ) ? esc_attr( $options['genesis_author_box_reloaded_position'] ) : '';
}

/**
 *	Get the raw title text
 */
function genesis_author_box_reloaded_get_title_text() {
	$options = get_option( 'genesis-settings' );
	$title_text = isset( $options['genesis_author_box_reloaded_title_text'] ) ? esc_attr( $options['genesis_author_box_reloaded_title_text'] ) : '';
	return $title_text;
}

/**
 *	Render the title text
 */
function genesis_author_box_reloaded_render_title_text() {

	global $post;

	$author_id = $post->post_author;

	$title_text = genesis_author_box_reloaded_get_title_text();

	$tags = apply_filters( 'genesis_author_box_reloaded_title_text_tags', array(
		'{full_name}',
		'{display_name}'
	) );

	$replacements = apply_filters( 'genesis_author_box_reloaded_title_text_tag_replacements', array(
		sprintf( '<span itemprop="name">%s %s</span>', get_the_author_meta( 'first_name', $author_id ), get_the_author_meta( 'last_name', $author_id ) ),
		sprintf( '<span itemprop="name">%s</span>', get_the_author_meta( 'nicename', $author_id ) ),
	) );

	$title_text = str_replace( $tags, $replacements, $title_text );

	return $title_text;
}

/**
 *	Get the author's Gravatar URL
 */
function genesis_author_box_reloaded_get_avatar_url() {

	global $post;

	$author_id = $post->post_author;

	$args = apply_filters( 'genesis_author_box_reloaded_get_avatar_args', array(
		'size' => 2 * intval( apply_filters( 'genesis_author_box_reloaded_gravatar_output_size', 96 ) ),
	) );

	return get_avatar_url( $author_id, $args );
}

/**
 *	Get the author's bio
 */
function genesis_author_box_reloaded_get_bio() {

	global $post;

	$author_id = $post->post_author;

	$bio = get_the_author_meta( 'description', $author_id );

	return apply_filters( 'genesis_author_box_reloaded_author_bio', $bio );
}

/**
 *	Sanitizes a given URL
 */
function genesis_author_box_reloaded_sanitize_url( $url ) {
	return filter_var( $url, FILTER_SANITIZE_URL );
}

/**
 *	Verifies that a given input is a valid URL
 */
function genesis_author_box_reloaded_is_valid_url( $url ) {

	$url = genesis_author_box_reloaded_sanitize_url( $url );

	if ( filter_var( $url, FILTER_VALIDATE_URL ) ) {
		return $url;
	} else {
		return false;
	}
}