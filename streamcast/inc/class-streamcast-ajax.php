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

		// SSRF Protection: only Shoutcast/Icecast metadata endpoints are proxied
		if ( ! $this->is_stream_metadata_url( $url ) ) {
			wp_send_json_error( 'Invalid URL', 400 );
		}

		// SSRF Protection: Validate the URL to prevent access to internal services
		$validated_url = wp_http_validate_url( $url );
		if ( ! $validated_url ) {
			wp_send_json_error( 'Insecure or invalid URL detected', 400 );
		}

		// Fetch the data (wp_safe_remote_get also re-validates every redirect target)
		$response = wp_safe_remote_get( $validated_url, array(
			'timeout'             => 5,
			'redirection'         => 3,
			'limit_response_size' => 512 * KB_IN_BYTES,
		) );

		if ( is_wp_error( $response ) ) {
			wp_send_json_error( 'Unable to fetch stream data', 500 );
		}

		$body = wp_remote_retrieve_body( $response );
		
		// Return the data
		wp_send_json_success( $body );
	}

	/**
	 * Check that the URL points to a Shoutcast (/currentsong) or Icecast (/status-json.xsl) metadata endpoint
	 */
	private function is_stream_metadata_url( $url ) {
		$parts = wp_parse_url( $url );
		if ( empty( $parts['scheme'] ) || empty( $parts['host'] ) || empty( $parts['path'] ) ) {
			return false;
		}

		if ( ! in_array( strtolower( $parts['scheme'] ), array( 'http', 'https' ), true ) ) {
			return false;
		}

		$path  = $parts['path'];
		$query = isset( $parts['query'] ) ? $parts['query'] : '';

		// Shoutcast: <stream>/currentsong?sid=1
		if ( preg_match( '#/currentsong$#i', $path ) ) {
			return '' === $query || (bool) preg_match( '/^sid=\d+$/', $query );
		}

		// Icecast: <stream>/status-json.xsl
		if ( preg_match( '#/status-json\.xsl$#i', $path ) ) {
			return '' === $query;
		}

		return false;
	}
}
