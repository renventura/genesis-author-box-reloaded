<?php
/**
 *	AJAX Requests
 *
 *	@package Genesis Author Box Reloaded
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 *	License action request
 */
add_action( 'wp_ajax_genesis_author_box_reloaded_license_action', 'genesis_author_box_reloaded_license_action' );
function genesis_author_box_reloaded_license_action() {

	// Security check
	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'genesis_author_box_reloaded_license_action' ) ) {
		die( __( 'Security', 'genesis-author-box-reloaded' ) );
	}

	$license_key = isset( $_POST['license_key'] ) ? sanitize_text_field( $_POST['license_key'] ) : '';

	if ( ! $license_key ) {
		die( __( 'No license key provided.', 'genesis-author-box-reloaded' ) );
	}

	$response = array();

	// License class
	$license_class = Genesis_Auhthor_Box_Reloaded()->license;

	// Action to be taken (either activate_license or deactivate_license)
	$action = sanitize_text_field( $_POST['action_type'] );

	// Assemble the success response message
	switch ( $action ) {

		case 'activate_license':
			$success_message = __( 'Your license was successfully activated!', 'genesis-author-box-reloaded' );
			break;

		case 'deactivate_license':
			$success_message = __( 'Your license was successfully deactivated.', 'genesis-author-box-reloaded' );
			break;
		
		default:
			$success_message = __( 'License checked.', 'genesis-author-box-reloaded' );
			break;
	}

	// Call the proper method
	if ( method_exists( $license_class, $action ) ) {
		$run = call_user_func( array( $license_class, $action ), $license_key );
	}

	// Good request
	if ( $run && ! is_wp_error( $run ) ) {
		$response['status'] = 'success';
		$response['message'] = $success_message;
	} elseif ( is_wp_error( $run ) ) {
		$response['status'] = 'failed';
		$response['message'] = $run->get_error_message();
	} else {
		$response['status'] = 'failed';
		$response['message'] = __( 'Error', 'genesis-author-box-reloaded' );
	}

	wp_send_json( $response );
}