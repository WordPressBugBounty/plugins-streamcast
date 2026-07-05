<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Field: switcher
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'STREAMCAST_STREAMCAST_CSF_Field_switcher' ) ) {
  class STREAMCAST_STREAMCAST_CSF_Field_switcher extends STREAMCAST_STREAMCAST_CSF_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $active     = ( ! empty( $this->value ) ) ? ' streamcast-csf--active' : '';
      $text_on    = ( ! empty( $this->field['text_on'] ) ) ? $this->field['text_on'] : esc_html__( 'On', 'streamcast' );
      $text_off   = ( ! empty( $this->field['text_off'] ) ) ? $this->field['text_off'] : esc_html__( 'Off', 'streamcast' );
      $text_width = ( ! empty( $this->field['text_width'] ) ) ? ' style="width: '. esc_attr( $this->field['text_width'] ) .'px;"': '';

      echo wp_kses_post( $this->field_before() );

      echo '<div class="streamcast-csf--switcher'. esc_attr( $active ) .'"'. $text_width .'>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
      echo '<span class="streamcast-csf--on">'. esc_attr( $text_on ) .'</span>';
      echo '<span class="streamcast-csf--off">'. esc_attr( $text_off ) .'</span>';
      echo '<span class="streamcast-csf--ball"></span>';
      echo '<input type="hidden" name="'. esc_attr( $this->field_name() ) .'" value="'. esc_attr( $this->value ) .'"'. $this->field_attributes() .' />'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
      echo '</div>';

      echo ( ! empty( $this->field['label'] ) ) ? '<span class="streamcast-csf--label">'. esc_attr( $this->field['label'] ) . '</span>' : '';

      echo wp_kses_post( $this->field_after() );

    }

  }
}
