<?php
namespace StreamCast;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Handle AJAX requests for StreamCast
 */
class AJAX {
	public function __construct() {
		add_action( 'wp_ajax_streamcast_fetch_stream', array( $this, 'fetch_stream_data' ) );
		add_action( 'wp_ajax_nopriv_streamcast_fetch_stream', array( $this, 'fetch_stream_data' ) );
	}

	/**
	 * Fetch stream data (proxy request to avoid CORS issues)
	 */
	public function fetch_stream_data() {
		// Verify nonce
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
		if ( empty( $nonce ) || ! wp_verify_nonce( $nonce, 'streamcast_fetch_nonce' ) ) {
			wp_send_json_error( 'Invalid nonce', 403 );
		}

		// Get and validate URL
		$url = isset( $_POST['url'] ) ? esc_url_raw( wp_unslash( $_POST['url'] ) ) : '';
		if ( empty( $url ) ) {
			wp_send_json_error( 'Invalid URL', 400 );
		}

		// SSRF Protection: Validate the URL to prevent access to internal services
		$validated_url = wp_http_validate_url( $url );
		if ( ! $validated_url ) {
			wp_send_json_error( 'Insecure or invalid URL detected', 400 );
		}

		// Fetch the data
		$response = wp_remote_get( $validated_url, array(
			'timeout' => 5,
		) );

		if ( is_wp_error( $response ) ) {
			wp_send_json_error( $response->get_error_message(), 500 );
		}

		$body = wp_remote_retrieve_body( $response );
		
		// Return the data
		wp_send_json_success( $body );
	}
}
