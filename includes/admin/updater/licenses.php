<?php
/**
 *	License handler
 *
 *	@package Genesis Author Box Reloaded
 */

//* Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'EngageWP_Genesis_Author_Box_Reloaded_Licenses' ) ) :

class EngageWP_Genesis_Author_Box_Reloaded_Licenses {

	private $home_url,
			$plugin_name,
			$options,
			$license_key,
			$site_status,
			$license_exp_date,
			$license_limit,
			$activations_left;

	public function __construct() {

		// Require main license API class
		if ( ! class_exists( 'EngageWP_Genesis_Author_Box_Reloaded_Licenses_API' ) ) {
			require_once 'licenses-api.php';
		}

		// Data for sending requests
		$this->home_url = 'https://www.engagewp.com';
		$this->plugin_name = 'Genesis Author Box Reloaded';

		// Global setttings
		$options = genesis_author_box_reloaded_get_license_data();
		$this->options = $options ? $options : array();

		// Retrieve existing license data
		$this->license_key = isset( $this->options['license_key'] ) ? trim( $this->options['license_key'] ) : '';
		$this->site_status = isset( $this->options['site_status'] ) ? trim( $this->options['site_status'] ) : '';
		$this->license_exp_date = isset( $this->options['license_exp_date'] ) ? trim( $this->options['license_exp_date'] ) : '';
		$this->license_limit = isset( $this->options['license_limit'] ) ? trim( $this->options['license_limit'] ) : '';
		$this->activations_left = isset( $this->options['activations_left'] ) ? trim( $this->options['activations_left'] ) : '';

		$this->hooks();
	}

	/**
	 *	Hooks
	 */
	public function hooks() {
		add_action( 'admin_init', array( $this, 'initialize' ) );
		add_action( 'admin_init', array( $this, 'check_license' ) );
	}

	/**
	 *	Initialize the setup
	 */
	public function initialize() {

		// Setup the updater
		new EngageWP_Genesis_Author_Box_Reloaded_Licenses_API( $this->home_url, GENESIS_AUTHOR_BOX_PLUGIN_FILE, array(
				'version' => GENESIS_AUTHOR_BOX_PLUGIN_VERSION,
				'license' => $this->license_key,
				'item_name' => $this->plugin_name,
				'author' => 'Ren Ventura'
			)
		);
	}

	/**
	 *	Sends a request to process a license activation, deactivation, or check
	 *	@param (string) $license_key - The license key
	 *	@param (string) $action - The type of EDD action to run
	 *	@return (array) Response
	 */
	public function send_request( $license_key, $action ) {

		// Request data
		$api_params = array(
			'edd_action' => sanitize_text_field( $action ),
			'license' => sanitize_text_field( $license_key ),
			'item_name' => urlencode( $this->plugin_name ),
			'url' => home_url()
		);

		// GET request
		$response = wp_remote_get( add_query_arg( $api_params, $this->home_url ), array( 'timeout' => 35, 'sslverify' => false ) );

		// Make sure the response came back okay
		if ( is_wp_error( $response ) ) {
			return new WP_Error( 'genesis_author_box_reloaded_license_api_bad_request', __( 'Bad response in send_request', 'genesis-author-box-reloaded' ) );
		}

		// Decode and return the license data
		return json_decode( wp_remote_retrieve_body( $response ) );
	}

	/**
	 *	Store the license data, and set daily checks
	 *	@param (string) $license_key - License key to save
	 *	@param (array) $license_data - Data about the license received from the API request
	 */
	public function save_license_data( $license_key = '', $license_data = '' ) {

		if ( ! $license_key || ! $license_data || ! is_object( $license_data ) ) {
			return;
		}

		$options = genesis_author_box_reloaded_get_license_data();

		$new = array(
			'license_key' => sanitize_text_field( $license_key ),
			'site_status' => sanitize_text_field( $license_data->license ),
			'license_exp_date' => sanitize_text_field( $license_data->expires ),
			'license_limit' => sanitize_text_field( $license_data->license_limit ),
			'activations_left' => sanitize_text_field( $license_data->activations_left ),
		);

		$update_option = update_option( 'genesis_author_box_reloaded_license', $new );
		$update_trans = set_transient( 'genesis_author_box_reloaded_license_transient', $license_data->license, DAY_IN_SECONDS );

		if ( $update_option && $update_trans ) {
			return true;
		} else {
			return new WP_Error( 'genesis_author_box_reloaded_license_api_data_not_saved', __( 'License data not saved', 'genesis-author-box-reloaded' ) );
		}
	}

	/**
	 *	Delete the license data, and clear daily checks
	 */
	public function delete_license_data() {

		$option = delete_option( 'genesis_author_box_reloaded_license' );
		$trans = delete_transient( 'genesis_author_box_reloaded_license_transient' );

		if ( $option && $trans ) {
			return true;
		} else {
			return new WP_Error( 'genesis_author_box_reloaded_license_api_data_not_deleted', __( 'License data not deleted', 'genesis-author-box-reloaded' ) );
		}
	}

	/**
	 *	Send activation request
	 *	@param (string) $license_key - License key
	 */
	public function activate_license( $license_key = '' ) {

		if ( ! $license_key ) {
			$license_key = $this->license_key;
		}

		// Send request
		$license_data = $this->send_request( $license_key, 'activate_license' );

		// Update the license data
		if ( is_object( $license_data ) && ! is_wp_error( $license_data ) ) {
			$return = $this->save_license_data( $license_key, $license_data );
		} else {
			$return = new WP_Error( 'genesis_author_box_reloaded_license_api_activation_error', __( 'Error while activating license', 'genesis-author-box-reloaded' ) );
		}

		return $return;
	}

	/**
	 *	Run an API check for license data to confirm validity
	 *	@param (string) $license_key - License key
	 *	@return (string) $status - Value of transient for license status
	 */
	public function check_license( $license_key = '' ) {

		if ( ! $license_key ) {
			$license_key = $this->license_key;
		}

		// Get license key and transient
		$status = genesis_author_box_reloaded_get_license_transient();

		if ( $status ) {
			$return = true;
		} else {
			$return = false;
		}

		// Run the license check a maximum of once per day
		if ( $license_key && ! $status ) {

			// Make the activation API request
			$license_data = $this->send_request( $license_key, 'check_license' );

			// Update the license data 
			if ( is_object( $license_data ) && ! is_wp_error( $license_data ) ) {
				$return = $this->save_license_data( $license_key, $license_data );
			} else {
				$return = new WP_Error( 'genesis_author_box_reloaded_license_api_check_error', __( 'Error while checking license', 'genesis-author-box-reloaded' ) );
			}

			// Set the status
			$status = isset( $license_data->license ) ? $license_data->license : '';
		}

		return array( 'success' => $return, 'status' => $status );
	}

	/**
	 *	Deactivate the license
	 *	@param (string) $license_key - License key
	 */
	public function deactivate_license( $license_key = '' ) {

		if ( ! $license_key ) {
			$license_key = $this->license_key;
		}

		// Make the activation API request
		$license_data = $this->send_request( $license_key, 'deactivate_license' );

		if ( is_object( $license_data ) && ! is_wp_error( $license_data ) ) {
			if ( $license_data->license == 'deactivated' ) {
				$return = $this->delete_license_data();
			} else {
				$return = false;
			}
		} else {
			$return = new WP_Error( 'genesis_author_box_reloaded_license_api_deactivate_error', __( 'Error while deactivating license', 'genesis-author-box-reloaded' ) );
		}

		return $return;
	}
}

endif;