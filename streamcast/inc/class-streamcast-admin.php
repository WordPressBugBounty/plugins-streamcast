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

		/**
		 * The shortcode rail, under the title. One copy target, and the shortcode is
		 * presented as the value it is rather than as a button -- same object as the
		 * side column's copy rail, scaled up for the main column.
		 *
		 * The streamcast_shortcode_copy_btn class and the .copy-text span are the hooks
		 * assets/admin.js binds to; it toggles .copied and swaps the label.
		 */
		public function shortcode_area() {
			if ('streamcast' != get_post_type()) {
				return;
			}
			global $post;

			$shortcode = "[radio_player id='" . $post->ID . "']";
			?>
			<div class="streamcast-rail-box">
				<div class="streamcast-rail">
					<span class="streamcast-rail__slot">
						<span class="streamcast-jack" aria-hidden="true"></span>
						<span class="streamcast-rail__lbl"><?php esc_html_e('Shortcode', 'streamcast'); ?></span>
					</span>

					<code class="streamcast-rail__code"><?php echo esc_html($shortcode); ?></code>

					<button type="button"
							class="streamcast-rail__copy streamcast_shortcode_copy_btn"
							data-shortcode="<?php echo esc_attr($shortcode); ?>"
							title="<?php esc_attr_e('Copy shortcode', 'streamcast'); ?>">
						<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true" focusable="false"><rect x="9" y="9" width="12" height="12" rx="2"></rect><path d="M5 15V5a2 2 0 0 1 2-2h10"></path></svg>
						<span class="copy-text"><?php esc_html_e('Copy', 'streamcast'); ?></span>
					</button>
				</div>

				<p class="streamcast-rail__hint"><?php esc_html_e('Paste it into any post, page, or widget.', 'streamcast'); ?></p>
			</div>
			<?php
		} 

	}
}
