<?php
namespace StreamCast;

if ( ! defined( 'ABSPATH' ) ) exit;

class Functions {
	public static function get_meta( $id, $key, $default = null ) {
		$value = get_post_meta( $id, $key, true );
		return $value ?: $default;
	}
}

// Global wrapper for convenience and backward compatibility
if ( ! function_exists( 'streamcast_get_meta' ) ) {
	function streamcast_get_meta( $id, $key, $default = null ) {
		return \StreamCast\Functions::get_meta( $id, $key, $default );
	}
}
