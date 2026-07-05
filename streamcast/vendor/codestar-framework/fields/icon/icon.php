<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Field: icon
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'STREAMCAST_STREAMCAST_CSF_Field_icon' ) ) {
  class STREAMCAST_STREAMCAST_CSF_Field_icon extends STREAMCAST_STREAMCAST_CSF_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $args = wp_parse_args( $this->field, array(
        'button_title' => esc_html__( 'Add Icon', 'streamcast' ),
        'remove_title' => esc_html__( 'Remove Icon', 'streamcast' ),
      ) );

      echo wp_kses_post( $this->field_before() );

      $nonce  = wp_create_nonce( 'streamcast_csf_icon_nonce' );
      $hidden = ( empty( $this->value ) ) ? ' hidden' : '';

      echo '<div class="streamcast-csf-icon-select">';
      echo '<span class="streamcast-csf-icon-preview'. esc_attr( $hidden ) .'"><i class="'. esc_attr( $this->value ) .'"></i></span>';
      echo '<a href="#" class="button button-primary streamcast-csf-icon-add" data-nonce="'. esc_attr( $nonce ) .'">'. wp_kses_post( $args['button_title'] ) .'</a>';
      echo '<a href="#" class="button streamcast-csf-warning-primary streamcast-csf-icon-remove'. esc_attr( $hidden ) .'">'. wp_kses_post( $args['remove_title'] ) .'</a>';
      echo '<input type="hidden" name="'. esc_attr( $this->field_name() ) .'" value="'. esc_attr( $this->value ) .'" class="streamcast-csf-icon-value"'. $this->field_attributes() .' />'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
      echo '</div>';

      echo wp_kses_post( $this->field_after() );

    }

    public function enqueue() {
      add_action( 'admin_footer', array( 'STREAMCAST_STREAMCAST_CSF_Field_icon', 'add_footer_modal_icon' ) );
      add_action( 'customize_controls_print_footer_scripts', array( 'STREAMCAST_STREAMCAST_CSF_Field_icon', 'add_footer_modal_icon' ) );
    }

    public static function add_footer_modal_icon() {
    ?>
      <div id="streamcast-csf-modal-icon" class="streamcast-csf-modal streamcast-csf-modal-icon hidden">
        <div class="streamcast-csf-modal-table">
          <div class="streamcast-csf-modal-table-cell">
            <div class="streamcast-csf-modal-overlay"></div>
            <div class="streamcast-csf-modal-inner">
              <div class="streamcast-csf-modal-title">
                <?php esc_html_e( 'Add Icon', 'streamcast' ); ?>
                <div class="streamcast-csf-modal-close streamcast-csf-icon-close"></div>
              </div>
              <div class="streamcast-csf-modal-header">
                <input type="text" placeholder="<?php esc_html_e( 'Search...', 'streamcast' ); ?>" class="streamcast-csf-icon-search" />
              </div>
              <div class="streamcast-csf-modal-content">
                <div class="streamcast-csf-modal-loading"><div class="streamcast-csf-loading"></div></div>
                <div class="streamcast-csf-modal-load"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php
    }

  }
}
