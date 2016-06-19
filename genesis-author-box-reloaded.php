<?php
/**
 * Plugin Name: Genesis Author Box Reloaded
 * Plugin URI: https://www.engagewp.com/downloads/genesis-author-box-reloaded/
 * Description: Create and customize an author box to display on your WordPress posts. Built for the Genesis Framework.
 * Version: 1.0.0
 * Author: Ren Ventura
 * Author URI: https://www.engagewp.com/
 * Text Domain: genesis-author-box-reloaded
 * Domain Path: /languages/
 *
 * License: GPL 2.0+
 * License URI: http://www.opensource.org/licenses/gpl-license.php
 */

/*
	Copyright 2016  Ren Ventura  (email : mail@engagewp.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

	Permission is hereby granted, free of charge, to any person obtaining a copy of this
	software and associated documentation files (the "Software"), to deal in the Software
	without restriction, including without limitation the rights to use, copy, modify, merge,
	publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons
	to whom the Software is furnished to do so, subject to the following conditions:

	The above copyright notice and this permission notice shall be included in all copies or
	substantial portions of the Software.

	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
	THE SOFTWARE.
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Genesis_Auhthor_Box_Reloaded' ) ) :

class Genesis_Auhthor_Box_Reloaded {

	private static $instance;

	public $license;

	public static function instance() {

		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Genesis_Auhthor_Box_Reloaded ) ) {
			
			self::$instance = new Genesis_Auhthor_Box_Reloaded;

			self::$instance->constants();
			self::$instance->includes();
			self::$instance->hooks();


			self::$instance->license = new EngageWP_Genesis_Author_Box_Reloaded_Licenses;
		}

		return self::$instance;
	}

	/**
	 *	Constants
	 */
	public function constants() {

		// Plugin version
		if ( ! defined( 'GENESIS_AUTHOR_BOX_PLUGIN_VERSION' ) ) {
			define( 'GENESIS_AUTHOR_BOX_PLUGIN_VERSION', '1.0.0' );
		}

		// Plugin file
		if ( ! defined( 'GENESIS_AUTHOR_BOX_PLUGIN_FILE' ) ) {
			define( 'GENESIS_AUTHOR_BOX_PLUGIN_FILE', __FILE__ );
		}

		// Plugin basename
		if ( ! defined( 'GENESIS_AUTHOR_BOX_PLUGIN_BASENAME' ) ) {
			define( 'GENESIS_AUTHOR_BOX_PLUGIN_BASENAME', plugin_basename( GENESIS_AUTHOR_BOX_PLUGIN_FILE ) );
		}

		// Plugin directory path
		if ( ! defined( 'GENESIS_AUTHOR_BOX_PLUGIN_DIR_PATH' ) ) {
			define( 'GENESIS_AUTHOR_BOX_PLUGIN_DIR_PATH', trailingslashit( plugin_dir_path( GENESIS_AUTHOR_BOX_PLUGIN_FILE )  ) );
		}

		// Plugin directory URL
		if ( ! defined( 'GENESIS_AUTHOR_BOX_PLUGIN_DIR_URL' ) ) {
			define( 'GENESIS_AUTHOR_BOX_PLUGIN_DIR_URL', trailingslashit( plugin_dir_url( GENESIS_AUTHOR_BOX_PLUGIN_FILE )  ) );
		}

		// Templates directory
		if ( ! defined( 'GENESIS_AUTHOR_BOX_PLUGIN_TEMPLATES_DIR_PATH' ) ) {
			define ( 'GENESIS_AUTHOR_BOX_PLUGIN_TEMPLATES_DIR_PATH', GENESIS_AUTHOR_BOX_PLUGIN_DIR_PATH . 'templates/' );
		}
	}

	/**
	 *	Include PHP files
	 */
	public function includes() {

		include_once 'includes/helper-functions.php';
		include_once 'includes/hooks.php';
		include_once 'includes/admin/settings.php';
		include_once 'includes/admin/ajax.php';
		include_once 'includes/admin/updater/licenses.php';
	}

	/**
	 *	Action/filter hooks
	 */
	public function hooks() {

		register_activation_hook( GENESIS_AUTHOR_BOX_PLUGIN_FILE, array( $this, 'activate' ) );
		add_action( 'plugins_loaded', array( $this, 'plugins_load' ) );
	}

	/**
	 *	Plugin activation
	 */
	public function activate() {

		// Check for Genesis
		$message = __( 'Genesis Author Box requires the ', 'genesis-author-box-reloaded' );
		$message .= sprintf( '<a href="https://www.engagewp.com/go/studiopress" target="_blank">%s</a>', __( 'Genesis Framework.', 'genesis-author-box-reloaded' ) );

		if ( ! function_exists( 'genesis' ) ) {
			wp_die( $message );
		}
	}

	/**
	 *	Load text domain
	 */
	public function plugins_load() {
		load_plugin_textdomain( 'genesis-author-box-reloaded', false, trailingslashit( WP_LANG_DIR ) . 'plugins/' );
		load_plugin_textdomain( 'genesis-author-box-reloaded', false, plugin_basename( dirname( __FILE__ ) ) . '/languages/' );
	}
}

endif;

/**
 *	Main function
 *	@return object Genesis_Auhthor_Box_Reloaded instance
 */
function Genesis_Auhthor_Box_Reloaded() {
	return Genesis_Auhthor_Box_Reloaded::instance();
}

/**
 *	Kick off!
 */
Genesis_Auhthor_Box_Reloaded();