<?php
/**
 *	Add plugin settings to the Genesis settings page
 *
 *	@package Genesis Author Box Reloaded
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Genesis_Author_Box_Reloaded_Settings' ) ) :

class Genesis_Author_Box_Reloaded_Settings {

	public function __construct() {

		$this->hooks();		
	}

	/**
	 *	Action/filter hooks
	 */
	public function hooks() {
		add_filter( 'genesis_theme_settings_defaults', array( $this, 'merge_settings' ) );
		add_action( 'genesis_theme_settings_metaboxes', array( $this, 'new_metabox' ) );
		add_action( 'genesis_settings_sanitizer_init', array( $this, 'sanitize' ) );
	}

	/**
	 *	Register custom Genesis theme settings
	 *
	 *	@param (array) $defaults Default theme settings
	 *	@return (array) New default theme settings
	 */
	public function merge_settings( $defaults ) {
		$defaults['genesis_author_box_reloaded_position_before'] = '';
		$defaults['genesis_author_box_reloaded_post_types'] = '';
		$defaults['genesis_author_box_reloaded_social_links'] = '';
		$defaults['genesis_author_box_reloaded_title_text'] = '';
		$defaults['genesis_author_box_reloaded_enable'] = '';
		$defaults['genesis_author_box_reloaded_display_website'] = '';
		$defaults['genesis_author_box_reloaded_display_rss_link'] = '';
		$defaults['genesis_author_box_reloaded_display_gravatar'] = '';
		return $defaults;
	}

	/**
	 * Register additional metaboxes to Genesis > Theme Settings
	 *
	 * @param (string) $_genesis_theme_settings_pagehook
	 */
	public function new_metabox( $_genesis_theme_settings_pagehook ) {
		add_meta_box( 'genesis-author-box', __( 'Genesis Author Box Reloaded Settings', 'genesis-author-box' ), array( $this, 'render_metabox' ), $_genesis_theme_settings_pagehook, 'main' );
	}

	/**
	 *	Fill in the Social Share meta box with inputs
	 *	@see summation_genesis_genesis_meta_boxes()
	 */
	public function render_metabox() {

		// Exclude post types from settings
		$excluded_post_types = apply_filters( 'engagewp_genesis_author_box_metabox_excluded_post_types', array(
			'attachment'
		) );		

		// License info
		$license = genesis_author_box_reloaded_get_license_data();
		$license_key = isset( $license['license_key'] ) ? esc_attr( $license['license_key'] ) : '';
		$license_status = isset( $license['site_status'] ) ? esc_attr( $license['site_status'] ) : '';

		// Plugin settings
		$enabled = genesis_author_box_reloaded_is_enabled();
		$avatar_enabled = genesis_author_box_reloaded_is_avatar_enabled();
		$display_website = genesis_author_box_reloaded_show_website();
		$post_types = genesis_author_box_reloaded_get_post_types();
		$links = genesis_author_box_reloaded_social_link_types();
		$enabled_links = genesis_author_box_reloaded_get_social_links();
		$position = genesis_author_box_reloaded_get_position();
		$title_text = genesis_author_box_reloaded_get_title_text();
		$output_hooks = genesis_author_box_reloaded_get_output_hooks();

		include_once GENESIS_AUTHOR_BOX_PLUGIN_TEMPLATES_DIR_PATH . 'admin/meta-box.php';
	}

	/**
	 *	Sanitize new Genesis setting meta boxes
	 */
	public function sanitize() {

		/**
		 *	Genesis sanitization filters:
		 *	one_zero, no_html, absint, safe_html, requires_unfiltered_html, url, email_address
		 */

		// No HTML
		genesis_add_option_filter( 'no_html', GENESIS_SETTINGS_FIELD, array(
			'genesis_author_box_reloaded_position_before',
		) );

		// Safe HTML
		genesis_add_option_filter( 'safe_html', GENESIS_SETTINGS_FIELD, array(
			'genesis_author_box_reloaded_title_text',
		) );

		// 0 or 1 (i.e. checkboxes, radio buttons)
		genesis_add_option_filter( 'one_zero', GENESIS_SETTINGS_FIELD, array(
			'genesis_author_box_reloaded_enable',
			'genesis_author_box_reloaded_display_website',
			'genesis_author_box_reloaded_display_rss_link',
			'genesis_author_box_reloaded_display_gravatar',
		) );
	}
}

endif;

new Genesis_Author_Box_Reloaded_Settings;