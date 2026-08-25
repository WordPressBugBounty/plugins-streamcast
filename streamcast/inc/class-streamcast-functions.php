<?php
namespace StreamCast;

if ( ! defined( 'ABSPATH' ) ) exit;

class Functions {
	public static function get_meta( $id, $key, $default = null ) {
		$value = get_post_meta( $id, $key, true );
		return $value ?: $default;
	}

	/**
	 * The padlock used by every locked-feature list -- the side column's Pro tuner
	 * and the in-form locked bank. Inherits currentColor, so each caller sets the
	 * tone from its own surface.
	 */
	public static function lock_icon() {
		return '<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true" focusable="false"><rect x="4" y="11" width="16" height="10" rx="1.5"></rect><path d="M8 11V7a4 4 0 0 1 8 0v4"></path></svg>';
	}

	/**
	 * One destination for every "what does Pro unlock" link on the screen. Two links
	 * carrying the same label must not land in two different places.
	 */
	public static function pro_url() {
		if ( function_exists( 'str_fs' ) ) {
			return str_fs()->get_upgrade_url();
		}

		return admin_url( 'edit.php?post_type=streamcast&page=streamcast#/pricing' );
	}

	/**
	 * The shut marquee: how many settings this player type cannot reach, named.
	 *
	 * Three panels across a full-width blue field -- the count as a large amber
	 * numeral, the headline and the setting names as chips, and the call to action.
	 * The amber is the same #FFD166 as the side column's tuner needle, the only warm
	 * colour in the palette, which is what makes the numeral carry at a glance
	 * without introducing a second accent.
	 *
	 * The vertical pinstripe behind it is the tuner dial's tick rhythm reused as
	 * texture, so the panel reads as the same instrument at a different scale.
	 *
	 * Descriptions are not printed -- they ride along as chip tooltips, since the
	 * marquee trades detail for reach.
	 *
	 * @param array $features List of ['name' => string, 'desc' => string].
	 * @return string
	 */
	public static function shut_marquee( $features ) {
		if ( empty( $features ) || ! is_array( $features ) ) {
			return '';
		}

		$lock  = self::lock_icon();
		$chips = '';
		$count = 0;

		foreach ( $features as $feature ) {
			$name = isset( $feature['name'] ) ? $feature['name'] : '';

			if ( '' === $name ) {
				continue;
			}

			$desc   = isset( $feature['desc'] ) ? $feature['desc'] : '';
			$title  = '' !== $desc ? ' title="' . esc_attr( $desc ) . '"' : '';
			$chips .= '<span' . $title . '>' . $lock . esc_html( $name ) . '</span>';
			$count++;
		}

		if ( 0 === $count ) {
			return '';
		}

		return '<div class="streamcast-marquee">'
			. '<div class="streamcast-marquee__count">'
				. '<b>' . esc_html( str_pad( (string) $count, 2, '0', STR_PAD_LEFT ) ) . '</b>'
				. '<span>' . esc_html( _n( 'Setting shut', 'Settings shut', $count, 'streamcast' ) ) . '</span>'
			. '</div>'
			. '<div class="streamcast-marquee__body">'
				. '<div class="streamcast-marquee__hl">'
					. esc_html(
						sprintf(
							/* translators: %d: number of settings unavailable in the free version. */
							_n(
								'%d switch on this screen is shut.',
								'%d switches on this screen are shut.',
								$count,
								'streamcast'
							),
							$count
						)
					)
				. '</div>'
				. '<div class="streamcast-marquee__chips">' . $chips . '</div>'
			. '</div>'
			. '<div class="streamcast-marquee__cta">'
				. '<a href="' . esc_url( self::pro_url() ) . '" target="_blank" rel="noopener">'
					. esc_html__( 'See what Pro unlocks', 'streamcast' )
					. '<em>' . esc_html__( '14-day refund', 'streamcast' ) . '</em>'
				. '</a>'
			. '</div>'
		. '</div>';
	}

}

// Global wrapper for convenience and backward compatibility
if ( ! function_exists( 'streamcast_get_meta' ) ) {
	function streamcast_get_meta( $id, $key, $default = null ) {
		return \StreamCast\Functions::get_meta( $id, $key, $default );
	}
}
