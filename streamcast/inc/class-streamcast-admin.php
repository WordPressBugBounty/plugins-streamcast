<?php
namespace StreamCast;

if (!defined('ABSPATH')) exit;


if (!class_exists('STREAMCAST_Admin')) {
	class STREAMCAST_Admin {
		public function __construct() {
			add_action('init', [__CLASS__, 'register_post_type']);
			add_filter('gettext', [$this, 'change_publish_button_text'], 10, 2);
			add_filter('post_updated_messages', [$this, 'custom_updated_message']);
			add_filter('post_row_actions', [$this, 'remove_row_actions'], 10, 2);
			add_action('admin_head-post.php', [$this, 'hide_publishing_actions']);
			add_action('admin_head-post-new.php', [$this, 'hide_publishing_actions']);
			add_filter('manage_streamcast_posts_columns', [$this, 'manage_columns'], 10);
			add_action('manage_streamcast_posts_custom_column', [$this, 'manage_custom_columns'], 10, 2);
			add_action('edit_form_after_title', [$this, 'shortcode_area']);
		}

		public static function register_post_type() {
			register_post_type('streamcast', [
				'labels' => [
					'name' => __('StreamCast', 'streamcast'),
					'singular_name' => __('StreamCast', 'streamcast'),
					'add_new' => __('Add New Radio Player', 'streamcast'),
					'add_new_item' => __('Add New Radio Player', 'streamcast'),
					'edit_item' => __('Edit Radio', 'streamcast'),
					'new_item' => __('New Radio', 'streamcast'),
					'view_item' => __('View Radio', 'streamcast'),
					'all_items' => __('All Player', 'streamcast'),
					'search_items' => __('Search Radio', 'streamcast'),
					'not_found' => __('Sorry, we couldn\'t find the Radio you are looking for.', 'streamcast'),
				],
				'public' => false,
				'show_ui' => true,
				'publicly_queryable' => true,
				'exclude_from_search' => true,
				'menu_position' => 14,
				'menu_icon' => 'dashicons-microphone',
				'has_archive' => false,
				'hierarchical' => false,
				'capability_type' => 'page',
				'rewrite' => ['slug' => 'behance'],
				'supports' => ['title'],
			]);
		}

		public function change_publish_button_text($translation, $original)
		{
			global $post;
			if (is_admin() && $post && $post->post_type === 'streamcast') {
				if ($original === 'Publish') {
					return 'Save';
				}
				if ($original === 'Update') {
					return 'Updated';
				}
			}
			return $translation;
		}

		public function custom_updated_message($messages)
		{
			global $post;
			if ($post && $post->post_type === 'streamcast') {
				$messages['streamcast'][1] = __('Updated', 'streamcast');
			}
			return $messages;
		}

		public function remove_row_actions($actions) {
			global $post;
			if ($post && $post->post_type === 'streamcast') {
				unset($actions['view']);
				unset($actions['inline hide-if-no-js']);
			}
			return $actions;
		}

		public function hide_publishing_actions() {
			global $post;
			if ($post && $post->post_type === 'streamcast') {
				echo '<style>#misc-publishing-actions,#minor-publishing-actions{display:none;}</style>';
			}
		}

		public function manage_columns($columns)
		{
			unset($columns['date']);
			$columns['shortcode'] = 'Shortcode';
			$columns['date'] = 'Date';
			return $columns;
		}

		public function manage_custom_columns($column_name, $post_ID)
		{
			if ($column_name === 'shortcode') {
				echo '<div class="bPlAdminShortcode" id="bPlAdminShortcode-' . esc_attr($post_ID) . '">
                <input value="[radio_player id=' . esc_attr($post_ID) . ']" onclick="copyBPlAdminShortcode(\'' . esc_attr($post_ID) . '\')" readonly>
                <span class="tooltip">Copy To Clipboard</span>
            </div>';
			}
		}

		public function shortcode_area() {
			if ('streamcast' != get_post_type()) {
				return;
			}
			global $post;
			$id = $post->ID;

			$shortcode = "[radio_player id='" . esc_attr($id) . "']";
			?>
        <div class="streamcast_shortcode_box_after_title">
            <label><?php esc_html_e('Copy and paste this shortcode into your posts, pages and widget', 'streamcast'); ?></label>
            <div class="shortcode_area">
                <button class="button button-bplugins button-large streamcast_shortcode_copy_btn"
                        data-shortcode="<?php echo esc_attr($shortcode) ?>"><span class="copy-text"><?php echo esc_html($shortcode); ?></span></button>
                <svg class='streamcast_shortcode_copy_btn' data-type="icon"
                     data-shortcode='<?php echo esc_attr($shortcode) ?>'
                     width='22px' height='22px' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'>
                    <path
                            d='M8 4V16C8 17.1046 8.89543 18 10 18L18 18C19.1046 18 20 17.1046 20 16V7.24162C20 6.7034 19.7831 6.18789 19.3982 5.81161L16.0829 2.56999C15.7092 2.2046 15.2074 2 14.6847 2H10C8.89543 2 8 2.89543 8 4Z'
                            stroke='#000000' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/>
                    <path d='M16 18V20C16 21.1046 15.1046 22 14 22H6C4.89543 22 4 21.1046 4 20V9C4 7.89543 4.89543 7 6 7H8'
                          stroke='#000000' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/>
                </svg>
            </div>
        </div>
		<?php
		} 

	}
}
