<?php
namespace StreamCast;

if ( ! defined( 'ABSPATH' ) ) exit;

if (!class_exists('STREAMCAST_Block')) {
	class STREAMCAST_Block {
		public function __construct() {
			add_action('init', [__CLASS__, 'onInit']);
			add_action('enqueue_block_assets', [__CLASS__, 'scb_enqueue_block_assets']);
		}
		public static function onInit() {
			register_block_type(STREAMCAST_PLUGIN_PATH . '/build');

			// Register external Muses player script.
			wp_register_script('muses-player', STREAMCAST_PLUGIN_DIR . 'assets/vendor/muses-player/mrp.js', [], '1.0', false);
		}

		public static function scb_enqueue_block_assets() {
			wp_enqueue_style('scb-style', STREAMCAST_PLUGIN_DIR . 'public/css/radio.css', array(), STREAMCAST_PLUGIN_VERSION, 'all');
			wp_enqueue_style('scb-player-style', STREAMCAST_PLUGIN_DIR . 'public/css/styles.css', array(), STREAMCAST_PLUGIN_VERSION, 'all');
			wp_enqueue_script('scb-script', STREAMCAST_PLUGIN_DIR . 'public/js/streamcast-final.js', array('jquery'), STREAMCAST_PLUGIN_VERSION, true);

			// Enqueue Muses player script.
			wp_enqueue_script('muses-player');

			$data = array(
				'iframePath' => STREAMCAST_PLUGIN_DIR . 'iframe.html',
				'ajaxUrl' => admin_url('admin-ajax.php'),
				'plyrSvg' => STREAMCAST_PLUGIN_DIR . 'assets/vendor/plyr/plyr.svg',
				'plyrBlankVideo' => STREAMCAST_PLUGIN_DIR . 'assets/vendor/plyr/blank.mp4',
				'nonce' => wp_create_nonce('streamcast_fetch_nonce'),
			);

			// Pass data to JavaScript.
			wp_localize_script('scb-streamcast-block-editor-script', 'streamcastData', $data);
			wp_localize_script('scb-streamcast-block-view-script', 'streamcastData', $data);
			wp_localize_script('scb-script', 'streamcastData', $data);
		}

	}
}

