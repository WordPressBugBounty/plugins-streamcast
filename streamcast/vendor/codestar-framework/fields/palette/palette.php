<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Field: palette
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'STREAMCAST_STREAMCAST_CSF_Field_palette' ) ) {
  class STREAMCAST_STREAMCAST_CSF_Field_palette extends STREAMCAST_STREAMCAST_CSF_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $palette = ( ! empty( $this->field['options'] ) ) ? $this->field['options'] : array();

      echo wp_kses_post( $this->field_before() );

      if ( ! empty( $palette ) ) {

        echo '<div class="streamcast-csf-siblings streamcast-csf--palettes">';

        foreach ( $palette as $key => $colors ) {

          $active  = ( $key === $this->value ) ? ' streamcast-csf--active' : '';
          $checked = ( $key === $this->value ) ? ' checked' : '';

          echo '<div class="streamcast-csf--sibling streamcast-csf--palette'. esc_attr( $active ) .'">';

          if ( ! empty( $colors ) ) {

            foreach ( $colors as $color ) {

              echo '<span style="background-color: '. esc_attr( $color ) .';"></span>';

            }

          }

          echo '<input type="radio" name="'. esc_attr( $this->field_name() ) .'" value="'. esc_attr( $key ) .'"'. $this->field_attributes() . esc_attr( $checked ) .'/>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
          echo '</div>';

        }

        echo '</div>';

      }

      echo wp_kses_post( $this->field_after() );

    }

  }
}
