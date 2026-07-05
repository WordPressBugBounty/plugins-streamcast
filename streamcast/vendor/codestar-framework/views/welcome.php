<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Setup Framework Class
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'STREAMCAST_STREAMCAST_CSF_Welcome' ) ) {
  class STREAMCAST_STREAMCAST_CSF_Welcome{

    private static $instance = null;

    public function __construct() {

      if ( STREAMCAST_STREAMCAST_CSF::$premium && ( ! STREAMCAST_STREAMCAST_CSF::is_active_plugin( 'codestar-framework/codestar-framework.php' ) || apply_filters( 'streamcast_csf_welcome_page', true ) === false ) ) { return; }

      add_action( 'admin_menu', array( $this, 'add_about_menu' ), 0 );
      add_filter( 'plugin_action_links', array( $this, 'add_plugin_action_links' ), 10, 5 );
      add_filter( 'plugin_row_meta', array( $this, 'add_plugin_row_meta' ), 10, 2 );

      $this->set_demo_mode();

    }

    // instance
    public static function instance() {
      if ( is_null( self::$instance ) ) {
        self::$instance = new self();
      }
      return self::$instance;
    }

    public function add_about_menu() {
      add_management_page( 'Codestar Framework', 'Codestar Framework', 'manage_options', 'streamcast-csf-welcome', array( $this, 'add_page_welcome' ) );
    }

    public function add_page_welcome() {

      $section = ( ! empty( $_GET['section'] ) ) ? sanitize_text_field( wp_unslash( $_GET['section'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

      STREAMCAST_STREAMCAST_CSF::include_plugin_file( 'views/header.php' );

      // safely include pages
      switch ( $section ) {

        case 'quickstart':
          STREAMCAST_STREAMCAST_CSF::include_plugin_file( 'views/quickstart.php' );
        break;

        case 'documentation':
          STREAMCAST_STREAMCAST_CSF::include_plugin_file( 'views/documentation.php' );
        break;

        case 'relnotes':
          STREAMCAST_STREAMCAST_CSF::include_plugin_file( 'views/relnotes.php' );
        break;

        case 'support':
          STREAMCAST_STREAMCAST_CSF::include_plugin_file( 'views/support.php' );
        break;

        case 'free-vs-premium':
          STREAMCAST_STREAMCAST_CSF::include_plugin_file( 'views/free-vs-premium.php' );
        break;

        default:
          STREAMCAST_STREAMCAST_CSF::include_plugin_file( 'views/about.php' );
        break;

      }

      STREAMCAST_STREAMCAST_CSF::include_plugin_file( 'views/footer.php' );

    }

    public static function add_plugin_action_links( $links, $plugin_file ) {

      if ( $plugin_file === 'codestar-framework/codestar-framework.php' && ! empty( $links ) ) {
        $links['streamcast-csf--welcome'] = '<a href="'. esc_url( admin_url( 'tools.php?page=streamcast-csf-welcome' ) ) .'">Settings</a>';
        if ( ! STREAMCAST_STREAMCAST_CSF::$premium ) {
          $links['streamcast-csf--upgrade'] = '<a href="http://codestarframework.com/">Upgrade</a>';
        }
      }

      return $links;

    }

    public static function add_plugin_row_meta( $links, $plugin_file ) {

      if ( $plugin_file === 'codestar-framework/codestar-framework.php' && ! empty( $links ) ) {
        $links['streamcast-csf--docs'] = '<a href="http://codestarframework.com/documentation/" target="_blank">Documentation</a>';
      }

      return $links;

    }

    public function set_demo_mode() {

      $demo_mode = get_option( 'streamcast_csf_demo_mode', false );

      $demo_activate = ( ! empty( $_GET[ 'streamcast-csf-demo' ] ) ) ? sanitize_text_field( wp_unslash( $_GET[ 'streamcast-csf-demo' ] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

      if ( ! empty( $demo_activate ) && check_admin_referer( 'streamcast-csf-demo-nonce' ) ) {

        $demo_mode = ( $demo_activate === 'activate' ) ? true : false;

        update_option( 'streamcast_csf_demo_mode', $demo_mode );

      }

      if ( ! empty( $demo_mode ) ) {

        STREAMCAST_STREAMCAST_CSF::include_plugin_file( 'samples/admin-options.php' );

        if ( STREAMCAST_STREAMCAST_CSF::$premium ) {

          STREAMCAST_STREAMCAST_CSF::include_plugin_file( 'samples/customize-options.php' );
          STREAMCAST_STREAMCAST_CSF::include_plugin_file( 'samples/metabox-options.php'   );
          STREAMCAST_STREAMCAST_CSF::include_plugin_file( 'samples/nav-menu-options.php'  );
          STREAMCAST_STREAMCAST_CSF::include_plugin_file( 'samples/profile-options.php'   );
          STREAMCAST_STREAMCAST_CSF::include_plugin_file( 'samples/shortcode-options.php' );
          STREAMCAST_STREAMCAST_CSF::include_plugin_file( 'samples/taxonomy-options.php'  );
          STREAMCAST_STREAMCAST_CSF::include_plugin_file( 'samples/widget-options.php'    );
          STREAMCAST_STREAMCAST_CSF::include_plugin_file( 'samples/comment-options.php'   );

        }

      }

    }

  }

  STREAMCAST_STREAMCAST_CSF_Welcome::instance();
}
