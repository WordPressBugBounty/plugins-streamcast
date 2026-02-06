<?php


if (!class_exists('SCBPlugin')) {
	class SCBPlugin {
		function __construct() {
			add_action('init', [$this, 'onInit']);
			add_action('enqueue_block_assets', [$this, 'scb_enqueue_block_assets']);
			
			add_action('enqueue_block_editor_assets', [$this, 'scb_enqueue_editor_scripts']);
			
		}

		function onInit() {
			register_block_type(__DIR__ . '/build');
		}

		function scb_enqueue_block_assets() {
			wp_enqueue_style('scb-style', STP_PLUGIN_DIR . 'public/css/radio.css', array(), STP_PLUGIN_VERSION, 'all');
			wp_enqueue_style('scb-player-style', STP_PLUGIN_DIR . 'public/css/styles.css', array(), STP_PLUGIN_VERSION, 'all');

			wp_enqueue_script('scb-script', STP_PLUGIN_DIR . 'public/js/streamcast-final.js', array('jquery'), STP_PLUGIN_VERSION, true);

			$data = array(
				'iframePath'  => STP_PLUGIN_DIR . 'iframe.html',
				"ajaxUrl" => admin_url( 'admin-ajax.php' )
			);
		
			// Pass data to JavaScript
			wp_localize_script( 'scb-streamcast-block-editor-script', 'myScriptData', $data );
			wp_localize_script( 'scb-streamcast-block-view-script', 'myScriptData', $data );
		}

		function scb_enqueue_editor_scripts() {
			wp_add_inline_script(
				'scb-streamcast-block-editor-script',
				'const scbIsPipeChecker = ' . wp_json_encode( str_fs()->can_use_premium_code() ) . ';',
				'before'
			);
		}
	}
	new SCBPlugin();
}
