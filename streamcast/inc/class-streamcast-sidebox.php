<?php
/**
 * The two panels in the side column of the streamcast edit screen: the builder
 * support card, and the Pro tuner.
 *
 * This is the free build, so the Pro card always renders -- there is no premium
 * code path to check for.
 *
 * Both draw their own surface, so the postbox chrome around them is stripped by the
 * streamcast-flat-box class added in flat_postbox_class(). The streamcast post type
 * supports 'title' only and is not exposed to REST, so the classic editor is the only
 * editor this screen ever runs and no block-editor fallback is needed.
 *
 * @package StreamCast
 */

namespace StreamCast;

if (!defined('ABSPATH')) exit;

if (!class_exists('StreamCast\STREAMCAST_SideBox')) {
	class STREAMCAST_SideBox {

		const POST_TYPE = 'streamcast';
		const BUILDERS_ID = 'streamcast_where_it_plays';
		const PRO_ID = 'streamcast_pro_tuner';

		public function register() {
			if (!is_admin()) {
				return;
			}

			add_action('add_meta_boxes', [$this, 'add_boxes']);
			add_filter('get_user_option_meta-box-order_' . self::POST_TYPE, [$this, 'release_boxes']);
		}

		/**
		 * The side column is ordered by priority: submitdiv registers at 'core', so
		 * 'default' puts the builder card directly under Save, and 'low' closes the
		 * column out with the Pro card.
		 */
		public function add_boxes() {
			add_meta_box(
				self::BUILDERS_ID,
				esc_html__('Where It Plays', 'streamcast'),
				[$this, 'render_builders'],
				self::POST_TYPE,
				'side',
				'default'
			);

			add_filter('postbox_classes_' . self::POST_TYPE . '_' . self::BUILDERS_ID, [$this, 'flat_postbox_class']);

			add_meta_box(
				self::PRO_ID,
				esc_html__('StreamCast Pro', 'streamcast'),
				[$this, 'render_pro'],
				self::POST_TYPE,
				'side',
				'low'
			);

			add_filter('postbox_classes_' . self::POST_TYPE . '_' . self::PRO_ID, [$this, 'flat_postbox_class']);
		}

		/**
		 * A box named in the user's stored meta-box order is rendered from the 'sorted'
		 * bucket, which WordPress draws ahead of 'core' -- that is how a card like this
		 * ends up above Save once a layout has been saved, and hiding the drag handle
		 * means it cannot be dragged back. Dropping our IDs out of the stored string
		 * hands them back to their priorities and leaves every other box the user
		 * arranged exactly where they put it. Nothing is written back.
		 */
		public function release_boxes($order) {
			if (!is_array($order) || empty($order['side'])) {
				return $order;
			}

			$ours = [self::BUILDERS_ID, self::PRO_ID];
			$kept = array_diff(explode(',', $order['side']), $ours);

			$order['side'] = implode(',', $kept);

			return $order;
		}

		/**
		 * Stripping .postbox-header also removes the toggle and the drag handle, which is
		 * what keeps these cards open and in place.
		 */
		public function flat_postbox_class($classes) {
			$classes[] = 'streamcast-flat-box';

			return $classes;
		}

		/**
		 * Short label for the two-column grid, full name for its tooltip -- "Beaver
		 * Builder" does not fit a 10px cell in a 280px column without wrapping the row.
		 */
		private function builders() {
			return [
				__('Elementor', 'streamcast')   => __('Elementor', 'streamcast'),
				__('Divi', 'streamcast')        => __('Divi Builder', 'streamcast'),
				__('Bricks', 'streamcast')      => __('Bricks Builder', 'streamcast'),
				__('WPBakery', 'streamcast')    => __('WPBakery Page Builder', 'streamcast'),
				__('Beaver', 'streamcast')      => __('Beaver Builder', 'streamcast'),
				__('Oxygen', 'streamcast')      => __('Oxygen Builder', 'streamcast'),
				__('Breakdance', 'streamcast')  => __('Breakdance', 'streamcast'),
				__('Widgets', 'streamcast')     => __('Widget areas & sidebars', 'streamcast'),
			];
		}

		/**
		 * The settings this build gates. Kept in step with the crown-marked and locked
		 * fields in class-streamcast-metabox.php.
		 */
		private function locked_features() {
			return [
				__('Auto play & initial volume', 'streamcast'),
				__('Artwork & background image', 'streamcast'),
				__('Player colors & themes', 'streamcast'),
				__('Fetch station name from URL', 'streamcast'),
				__('Sticky player position', 'streamcast'),
				__('Visualizer & progress colors', 'streamcast'),
			];
		}

		/**
		 * Capability statement, not detection: the jacks are lit as a matter of fact. The
		 * card never claims a builder is installed, which is what keeps it static and
		 * free of any runtime check.
		 */
		public function render_builders($post) {
			$builders = $this->builders();
			$outputs  = count($builders) + 1; // the builders, plus the native block.
			$shortcode = "[radio_player id='" . $post->ID . "']";
			?>
			<div class="streamcast-bay">
				<div class="streamcast-bay__top">
					<span class="streamcast-bay__lbl"><?php esc_html_e('Where it plays', 'streamcast'); ?></span>
					<span class="streamcast-bay__count">
						<?php echo absint($outputs) . ' / ' . absint($outputs); ?> &#10003;
					</span>
				</div>

				<div class="streamcast-bay__native">
					<span class="streamcast-jack" aria-hidden="true"></span>
					<span class="streamcast-bay__names">
						<span class="streamcast-bay__n1"><?php esc_html_e('Block editor', 'streamcast'); ?></span>
						<span class="streamcast-bay__n2"><?php esc_html_e('Native block', 'streamcast'); ?></span>
					</span>
					<span class="streamcast-bay__chip"><?php esc_html_e('No code', 'streamcast'); ?></span>
				</div>

				<button type="button"
						class="streamcast-bay__rail streamcast_shortcode_copy_btn"
						data-shortcode="<?php echo esc_attr($shortcode); ?>"
						title="<?php esc_attr_e('Copy shortcode', 'streamcast'); ?>">
					<span class="streamcast-jack streamcast-jack--sm" aria-hidden="true"></span>
					<span class="streamcast-bay__code copy-text"><?php echo esc_html($shortcode); ?></span>
					<span class="streamcast-bay__arrow" aria-hidden="true">&rarr;</span>
				</button>

				<div class="streamcast-bay__grid">
					<?php foreach ($builders as $short => $full) { ?>
						<span title="<?php echo esc_attr($full); ?>">
							<i class="streamcast-jack streamcast-jack--sm" aria-hidden="true"></i>
							<?php echo esc_html($short); ?>
						</span>
					<?php } ?>
				</div>

				<p class="streamcast-bay__foot">
					<?php esc_html_e('Drop the shortcode into any of these. Nothing extra to install — no add-on, no per-builder widget.', 'streamcast'); ?>
				</p>
			</div>
			<?php
		}

		public function render_pro() {
			$features = $this->locked_features();
			$upgrade  = \StreamCast\Functions::pro_url();
			?>
			<div class="streamcast-tuner">
				<div class="streamcast-tuner__scale" aria-hidden="true">
					<div class="streamcast-tuner__ticks">
						<?php for ($i = 0; $i < 24; $i++) { ?><i></i><?php } ?>
					</div>
					<span class="streamcast-tuner__needle"></span>
				</div>

				<div class="streamcast-tuner__lbl" aria-hidden="true">
					<span class="on"><?php esc_html_e('Free', 'streamcast'); ?></span>
					<span>&middot;</span>
					<span>&middot;</span>
					<span><?php esc_html_e('Pro — 24 settings', 'streamcast'); ?></span>
				</div>
			</div>

			<div class="streamcast-tuner__body">
				<h4>
					<?php
					printf(
						/* translators: %d: number of settings available only in the Pro version. */
						esc_html__('%d switches on this screen are shut.', 'streamcast'),
						absint(count($features))
					);
					?>
				</h4>

				<ul class="streamcast-tuner__locks">
					<?php foreach ($features as $feature) { ?>
						<li>
							<?php
							// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static inline icon markup.
							echo \StreamCast\Functions::lock_icon();
							echo esc_html($feature);
							?>
						</li>
					<?php } ?>
				</ul>

				<a class="streamcast-tuner__cta" href="<?php echo esc_url($upgrade); ?>" target="_blank" rel="noopener">
					<?php esc_html_e('See what Pro unlocks', 'streamcast'); ?>
				</a>

				<p class="streamcast-tuner__foot"><?php esc_html_e('14-day refund policy', 'streamcast'); ?></p>
			</div>
			<?php
		}
	}
}
