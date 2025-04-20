<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class StreamCast_Admin {

    public function __construct() {
        // Hooks
        add_action('init', [$this, 'register_post_type']);
        add_filter('gettext', [$this, 'change_publish_button_text'], 10, 2);
        add_filter('post_updated_messages', [$this, 'custom_updated_message']);
        add_filter('post_row_actions', [$this, 'remove_row_actions'], 10, 2);
        add_action('admin_head-post.php', [$this, 'hide_publishing_actions']);
        add_action('admin_head-post-new.php', [$this, 'hide_publishing_actions']);
        add_filter('manage_streamcast_posts_columns', [$this, 'manage_columns'], 10);
        add_action('manage_streamcast_posts_custom_column', [$this, 'manage_custom_columns'], 10, 2);
        add_action('edit_form_after_title', [$this, 'shortcode_area']);
    }

    public function register_post_type() {
        register_post_type('streamcast', [
            'labels' => [
                'name'               => __( 'StreamCast' ),
                'singular_name'      => __( 'StreamCast' ),
                'add_new'            => __( 'Add New Radio Player' ),
                'add_new_item'       => __( 'Add New Radio Player' ),
                'edit_item'          => __( 'Edit Radio' ),
                'new_item'           => __( 'New Radio' ),
                'view_item'          => __( 'View Portfolio' ),
                'search_items'       => __( 'Search Portfolio' ),
                'not_found'          => __( 'Sorry, we couldn\'t find the Portfolio you are looking for.' ),
            ],
            'public'              => false,
            'show_ui'             => true,
            'publicly_queryable'  => true,
            'exclude_from_search' => true,
            'menu_position'       => 14,
            'menu_icon'           => 'dashicons-microphone',
            'has_archive'         => false,
            'hierarchical'        => false,
            'capability_type'     => 'page',
            'rewrite'             => [ 'slug' => 'behance' ],
            'supports'            => [ 'title' ],
        ]);
    }

    public function change_publish_button_text( $translation, $original ) {
        global $post;
        if ( is_admin() && $post && $post->post_type === 'streamcast' ) {
            if ( $original === 'Publish' ) return 'Save';
            if ( $original === 'Update' ) return 'Updated';
        }
        return $translation;
    }

    public function custom_updated_message( $messages ) {
        global $post;
        if ( $post->post_type === 'streamcast' ) {
            $messages['streamcast'][1] = __('Updated', 'streamcast');
        }
        return $messages;
    }

    public function remove_row_actions( $actions ) {
        global $post;
        if ( $post->post_type === 'streamcast' ) {
            unset( $actions['view'] );
            unset( $actions['inline hide-if-no-js'] );
        }
        return $actions;
    }

    public function hide_publishing_actions() {
        global $post;
        if ( $post && $post->post_type === 'streamcast' ) {
            echo '<style>#misc-publishing-actions,#minor-publishing-actions{display:none;}</style>';
        }
    }

    public function manage_columns( $columns ) {
        unset($columns['date']);
        $columns['shortcode'] = 'Shortcode';
        $columns['date'] = 'Date';
        return $columns;
    }

    public function manage_custom_columns( $column_name, $post_ID ) {
        if ( $column_name === 'shortcode' ) {
            echo '<div class="bPlAdminShortcode" id="bPlAdminShortcode-' . esc_attr($post_ID) . '">
                <input value="[radio_player id=' . esc_attr($post_ID) . ']" onclick="copyBPlAdminShortcode(\'' . esc_attr($post_ID) . '\')" readonly>
                <span class="tooltip">Copy To Clipboard</span>
            </div>';
        }
    }

    public function shortcode_area() {
        global $post;
        if ( $post->post_type == 'streamcast' ) {
            ?>
        <style>
            #btss_meta .postbox-header{display:none}.bshortcode{margin-top:30px;border:5px solid #4527a4;overflow:hidden}.shortcode-heading{background:#4527a4;padding:15px;overflow:hidden;color:#fff}.shortcode-heading .icon{float:left;overflow:hidden;width:50%}.shortcode-heading .text{float:right;overflow:hidden;text-align:right}.shortcode-heading .text a{color:#fff;display:block;text-decoration:none}.bshortcode .shortcode-left{width:50%;float:left;overflow:hidden;padding:20px 0 30px;text-align:center;background:#fff;border-right:5px solid #4527a4;box-sizing:border-box}.bshortcode .shortcode-right{width:50%;float:left;overflow:hidden;padding:20px 0 30px;text-align:center;background:#fff}.bshortcode .shortcode{padding:8px 15px;background:#eae6f9;display:inline-block;user-select:all;font-size:16px}
        </style>
    
        <div class="bshortcode">
            <div class="shortcode-heading">
                <div class="icon"><span class="dashicons dashicons-format-audio"></span> <?php _e( 'Radio Player', 'radio-player' )?></div>
                <div class="text"> <a href="https://bplugins.com/support/" target="_blank"><?php _e( 'Supports', 'radio-player' )?></a></div>
            </div>
            <div class="shortcode-left">
                <h3><?php _e( 'Shortcode', 'radio-player' )?></h3>
                <p><?php _e( 'Copy and paste this shortcode into your posts or pages or widget content:', 'radio-player' )?></p>
                <div class="shortcode" selectable>[radio_player id='<?php echo esc_attr($post->ID); ?>']</div>
            </div>
            <div class="shortcode-right">
                <h3><?php _e( 'Template Include', 'radio-player' )?></h3>
                <p><?php _e( 'Copy and paste the PHP code into your template file:', 'radio-player' )?></p>
                <div class="shortcode">&lt;?php echo do_shortcode('[radio_player id="<?php echo esc_attr($post->ID); ?>"]');
                ?&gt;</div>
            </div>
        </div>
     <?php
    }}

}

// Initialize the admin logic
new StreamCast_Admin();
