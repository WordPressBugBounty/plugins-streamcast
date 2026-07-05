<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Field: gallery
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'STREAMCAST_STREAMCAST_CSF_Field_gallery' ) ) {
  class STREAMCAST_STREAMCAST_CSF_Field_gallery extends STREAMCAST_STREAMCAST_CSF_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $args = wp_parse_args( $this->field, array(
        'add_title'   => esc_html__( 'Add Gallery', 'streamcast' ),
        'edit_title'  => esc_html__( 'Edit Gallery', 'streamcast' ),
        'clear_title' => esc_html__( 'Clear', 'streamcast' ),
      ) );

      $hidden = ( empty( $this->value ) ) ? ' hidden' : '';

      echo wp_kses_post( $this->field_before() );

      echo '<ul>';
      if ( ! empty( $this->value ) ) {

        $values = explode( ',', $this->value );

        foreach ( $values as $id ) {
          $attachment = wp_get_attachment_image_src( $id, 'thumbnail' );
          echo '<li><img src="'. esc_url( $attachment[0] ) .'" /></li>';
        }

      }
      echo '</ul>';

      echo '<a href="#" class="button button-primary streamcast-csf-button">'. wp_kses_post( $args['add_title'] ) .'</a>';
      echo '<a href="#" class="button streamcast-csf-edit-gallery'. esc_attr( $hidden ) .'">'. wp_kses_post( $args['edit_title'] ) .'</a>';
      echo '<a href="#" class="button streamcast-csf-warning-primary streamcast-csf-clear-gallery'. esc_attr( $hidden ) .'">'. wp_kses_post( $args['clear_title'] ) .'</a>';
      echo '<input type="hidden" name="'. esc_attr( $this->field_name() ) .'" value="'. esc_attr( $this->value ) .'"'. $this->field_attributes() .'/>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

      echo wp_kses_post( $this->field_after() );

    }

  }
}
