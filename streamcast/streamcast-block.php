<?php

function stpIsPremium()
{
	return  STP_HAS_PRO ? str_fs()->can_use_premium_code() : false;
}


if (!class_exists('SCBPlugin')) {
	class SCBPlugin
	{
		function __construct()
		{
			add_action('init', [$this, 'onInit']);
			add_action('enqueue_block_assets', [$this, 'scb_enqueue_block_assets']);

			add_action('wp_ajax_stpPipeChecker', [$this, 'stpPipeChecker']);
			add_action('wp_ajax_nopriv_stpPipeChecker', [$this, 'stpPipeChecker']);
			add_action('admin_init', [$this, 'registerSettings']);
			add_action('rest_api_init', [$this, 'registerSettings']);
		}

		function stpPipeChecker()
		{
			$nonce = $_POST['_wpnonce'] ?? null;

			if (!wp_verify_nonce($nonce, 'wp_ajax')) {
				wp_send_json_error('Invalid Request');
			}

			wp_send_json_success([
				'isPipe' => stpIsPremium()
			]);
		}

		function registerSettings()
		{
			register_setting('stpUtils', 'stpUtils', [
				'show_in_rest' => [
					'name' => 'stpUtils',
					'schema' => ['type' => 'string']
				],
				'type' => 'string',
				'default' => wp_json_encode(['nonce' => wp_create_nonce('wp_ajax')]),
				'sanitize_callback' => 'sanitize_text_field'
			]);
		}

		function onInit()
		{
			register_block_type(__DIR__ . '/build');
		}

		function scb_enqueue_block_assets()
		{
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
	}
	new SCBPlugin();
}
