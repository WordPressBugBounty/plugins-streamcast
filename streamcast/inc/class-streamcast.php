<?php

class StreamCast {
    public static $_instance = null;

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        add_action( 'init', [$this, 'init'], 0 );
        add_action( 'plugins_loaded', [$this, 'plugins_loaded'] );
        add_action( 'plugins_loaded', [__CLASS__, 'load_textdomain'] );
        add_action( 'admin_enqueue_scripts', [__CLASS__, 'enqueue_admin_assets'] );
        add_action( 'admin_menu', [__CLASS__, 'add_help_pages'] );
        add_filter( 'admin_footer_text', [__CLASS__, 'admin_footer'] );
        add_shortcode( 'stream', [__CLASS__, 'stream_shortcode'] );
    }

    public static function init() {
        if ( !class_exists( 'CSF' ) ) {
            require_once STP_PLUGIN_PATH . 'admin/codestar-framework/codestar-framework.php';
        }
        if ( str_fs()->can_use_premium_code__premium_only() && file_exists( STP_PLUGIN_PATH . 'premium-files/metabox-pro.php' ) ) {
            require_once STP_PLUGIN_PATH . 'premium-files/metabox-pro.php';
        }
        if ( str_fs()->is_free_plan() && file_exists( STP_PLUGIN_PATH . 'admin/inc/metabox-free.php' ) ) {
            require_once STP_PLUGIN_PATH . 'admin/inc/metabox-free.php';
        }
    }

    public function plugins_loaded() {
        self::load_dependencies();
    }

    private static function load_dependencies() {
        require_once STP_PLUGIN_PATH . 'mimes/enable-mime-type.php';
        require_once STP_PLUGIN_PATH . 'inc/Streamcast_Admin.php';
        require_once STP_PLUGIN_PATH . 'inc/functions.php';
        // For All Nessesary Functions
        require_once STP_PLUGIN_PATH . 'streamcast-block.php';
        if ( str_fs()->is_free_plan() ) {
            require_once STP_PLUGIN_PATH . 'public/shortcode-free.php';
        }
        if ( str_fs()->can_use_premium_code__premium_only() && !file_exists( STP_PLUGIN_PATH . 'premium-files/shortcode-pro.php' ) ) {
            require_once STP_PLUGIN_PATH . 'public/shortcode-free.php';
        }
    }

    public static function load_textdomain() {
        load_plugin_textdomain( 'streamcast', false, dirname( __FILE__ ) . "/../languages" );
    }

    public static function enqueue_admin_assets( $hook ) {
        global $typenow;
        if ( 'streamcast' === $typenow ) {
            wp_enqueue_script(
                'stp-admin-post-js',
                STP_PLUGIN_DIR . 'build/admin-post.js',
                [],
                STP_PLUGIN_VERSION,
                true
            );
            wp_enqueue_style(
                'stp-admin-post-css',
                STP_PLUGIN_DIR . 'build/admin-post.css',
                [],
                STP_PLUGIN_VERSION
            );
        }
        if ( 'streamcast_page_help' === $hook ) {
            wp_enqueue_style(
                'stp-admin',
                STP_PLUGIN_DIR . 'admin/css/admin.css',
                [],
                STP_PLUGIN_VERSION
            );
        } elseif ( 'streamcast_page_dashboard' === $hook ) {
            wp_enqueue_script(
                'stp-dashboard-js',
                STP_PLUGIN_DIR . 'build/admin-help.js',
                ['react', 'react-dom'],
                STP_PLUGIN_VERSION,
                true
            );
            wp_enqueue_script(
                'stp-fs-js',
                STP_PLUGIN_DIR . 'public/js/fs.js',
                [],
                STP_PLUGIN_VERSION,
                true
            );
            wp_enqueue_style(
                'tlgb-dashboard-css',
                STP_PLUGIN_DIR . 'build/admin-help.css',
                [],
                STP_PLUGIN_VERSION
            );
        }
    }

    public static function add_help_pages() {
        add_submenu_page(
            'edit.php?post_type=streamcast',
            'Demo & Help',
            'Demo & Help',
            'manage_options',
            'dashboard',
            [__CLASS__, 'render_dashboard']
        );
    }

    public static function render_dashboard() {
        ?>
        <style>#wpcontent { padding-left: 0 !important; }</style>
        <div id="bplAdminHelpPage"
             data-version="<?php 
        echo esc_attr( STP_PLUGIN_VERSION );
        ?>"
             data-is-premium="<?php 
        echo esc_attr( stpIsPremium() );
        ?>">
        </div>
        <?php 
    }

    public static function admin_footer( $text ) {
        if ( 'streamcast' === get_post_type() ) {
            $url = 'https://wordpress.org/support/plugin/streamcast/reviews/?filter=5#new-post';
            return sprintf( __( 'If you like <strong>StreamCast Radio Player</strong> plugin, please leave us a <a href="%s" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a> rating.', 'streamcast' ), $url );
        }
        return $text;
    }

    public static function stream_shortcode( $atts ) {
        extract( shortcode_atts( [
            'url'        => null,
            'background' => null,
        ], $atts ) );
        ob_start();
        ?>
        <div style="width:200px;">
            <audio id="player" controls>
                <source src="<?php 
        echo esc_url( $url );
        ?>" type="audio/mp3" />
            </audio>
        </div>

        <style>
            .plyr__control {
                margin-right: 0 !important;
            }

            .plyr--audio .plyr__controls {
                <?php 
        echo 'background:' . esc_html( $background ?? 'transparent' ) . '!important; border-radius:3px !important;';
        ?>
            }
        </style>

        <script>
            const player = new Plyr('#player', {
                controls: ['play', 'mute', 'volume']
            });
        </script>
        <?php 
        return ob_get_clean();
    }

    public static function activation_redirect() {
        add_option( 'stp_do_activation_redirect', true );
    }

    public static function do_redirect_to_dashboard() {
        if ( get_option( 'stp_do_activation_redirect' ) ) {
            delete_option( 'stp_do_activation_redirect' );
            if ( !is_network_admin() && !isset( $_GET['activate-multi'] ) ) {
                wp_safe_redirect( admin_url( 'edit.php?post_type=streamcast&page=dashboard#/dashboard' ) );
                exit;
            }
        }
    }

}
