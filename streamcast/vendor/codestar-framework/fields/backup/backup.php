<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Field: backup
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'STREAMCAST_STREAMCAST_CSF_Field_backup' ) ) {
  class STREAMCAST_STREAMCAST_CSF_Field_backup extends STREAMCAST_STREAMCAST_CSF_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $unique = $this->unique;
      $nonce  = wp_create_nonce( 'streamcast_csf_backup_nonce' );
      $export = add_query_arg( array( 'action' => 'streamcast-csf-export', 'unique' => $unique, 'nonce' => $nonce ), admin_url( 'admin-ajax.php' ) );

      echo wp_kses_post( $this->field_before() );

      echo '<textarea name="streamcast_csf_import_data" class="streamcast-csf-import-data"></textarea>';
      echo '<button type="submit" class="button button-primary streamcast-csf-confirm streamcast-csf-import" data-unique="'. esc_attr( $unique ) .'" data-nonce="'. esc_attr( $nonce ) .'">'. esc_html__( 'Import', 'streamcast' ) .'</button>';
      echo '<hr />';
      echo '<textarea readonly="readonly" class="streamcast-csf-export-data">'. esc_attr( wp_json_encode( get_option( $unique ) ) ) .'</textarea>';
      echo '<a href="'. esc_url( $export ) .'" class="button button-primary streamcast-csf-export" target="_blank">'. esc_html__( 'Export & Download', 'streamcast' ) .'</a>';
      echo '<hr />';
      echo '<button type="submit" name="streamcast_csf_transient[reset]" value="reset" class="button streamcast-csf-warning-primary streamcast-csf-confirm streamcast-csf-reset" data-unique="'. esc_attr( $unique ) .'" data-nonce="'. esc_attr( $nonce ) .'">'. esc_html__( 'Reset', 'streamcast' ) .'</button>';

      echo wp_kses_post( $this->field_after() );

    }

  }
}
