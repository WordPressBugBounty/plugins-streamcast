<?php
if ( ! defined( 'ABSPATH' ) ) exit;

$id = wp_unique_id( 'streamcast-' );

$streamcast_stream_url            = $attributes['radioPlayer']['streamURL'];
$streamcast_stream_port           = $attributes['radioPlayer']['streamPort'];
$streamcast_player_type           = $attributes['radioPlayer']['playerType'];
$streamcast_welcome_message       = $attributes['radioPlayer']['welcomeMessage'];
$streamcast_skin                  = $attributes['radioPlayer']['skin']['name'];
$streamcast_width                 = $attributes['radioPlayer']['skin']['width'];
$streamcast_height                = $attributes['radioPlayer']['skin']['height'];
$streamcast_auto_play             = $attributes['radioPlayer']['autoPlay'];
$streamcast_volume                = $attributes['radioPlayer']['initialVolume'];
$streamcast_player_position       = $attributes['radioPlayer']['playerPosition'];
$streamcast_nonce                 = wp_create_nonce( 'streamcast_fetch_nonce' );

if ( ! function_exists( 'streamcast_get_player_position_styles' ) ) {
	function streamcast_get_player_position_styles( $id, $playerPosition ) {
		$styles = '';
		if ( $playerPosition === 'center' ) {
			$styles = 'margin: auto; display: block;';
		} elseif ( $playerPosition === 'left' ) {
			$styles = 'margin-left: 0; margin-right: auto;';
		} elseif ( $playerPosition === 'right' ) {
			$styles = 'margin-left: auto; margin-right: 0;';
		}

		return '#' . esc_attr( $id ) . ' .musesStyleReset { ' . $styles . ' }';
	}
}

$streamcast_dynamic_player_styles = streamcast_get_player_position_styles( $id, $streamcast_player_position );

if ( $streamcast_skin !== 'b_circle' && $streamcast_player_type === 'standard' ) {
	$streamcast_station_name          = $attributes['radioPlayer']['stationName'];
	$streamcast_fetch_name_from_url   = $attributes['radioPlayer']['fetchNameFromUrl'];

	?>
	<div id='<?php echo esc_attr( $id ); ?>'>
		<style>
			<?php echo esc_html( wp_strip_all_tags( $streamcast_dynamic_player_styles ) ); ?>
		</style>
		<script type="text/javascript">
			window.MRP?.insert({
				url: <?php echo wp_json_encode( $streamcast_stream_url ); ?>,
				lang: "en",
				codec: "mp3",
				volume: <?php echo (int) $streamcast_volume; ?>,
				autoplay: <?php echo $streamcast_auto_play ? 'true' : 'false'; ?>,
				forceHTML5: true,
				welcome: <?php echo wp_json_encode( $streamcast_welcome_message ); ?>,
				jsevents: true,
				buffering: 0,
				wmode: "transparent",
				skin: <?php echo wp_json_encode( $streamcast_skin ); ?>,
				width: <?php echo (int) $streamcast_width; ?>,
				height: <?php echo (int) $streamcast_height; ?>,
				metadataMode: "shoutcast",
				metadataInterval: 15
			});

			var title = <?php echo wp_json_encode( $streamcast_station_name ); ?>;

			async function fetchData() {
				try {
					const fetchUrl = <?php echo wp_json_encode( esc_url( $streamcast_stream_url ) . '/currentsong?sid=1' ); ?>;
					const formData = new FormData();
					formData.append('action', 'streamcast_fetch_stream');
					formData.append("url", fetchUrl);
					formData.append("nonce", <?php echo wp_json_encode( $streamcast_nonce ); ?>);

					const response = await fetch(<?php echo wp_json_encode( admin_url( 'admin-ajax.php' ) ); ?>, {
						method: "POST",
						body: formData,
					});

					if (!response.ok) {
						throw new Error('Network response was not ok');
					}

					const data = await response.json();
					return data?.data || null;
				} catch (error) {
					return null;
				}
			}

			async function fetchIceCastData() {
				try {
					const fetchUrl = <?php echo wp_json_encode( esc_url( $streamcast_stream_url ) . '/status-json.xsl' ); ?>;
					const formData = new FormData();
					formData.append('action', 'streamcast_fetch_stream');
					formData.append("url", fetchUrl);
					formData.append("nonce", <?php echo wp_json_encode( $streamcast_nonce ); ?>);

					const response = await fetch(<?php echo wp_json_encode( admin_url( 'admin-ajax.php' ) ); ?>, {
						method: "POST",
						body: formData,
					});

					if (!response.ok) {
						throw new Error('Network response was not ok');
					}

					const result = await response.json();
					if (result?.success && result?.data) {
						const data = JSON.parse(result.data);
						const stream = data.icestats?.source || null;
						if (stream) {
							return stream.title || stream.song || stream.server_name || "No Title Available";
						}
					}
					return null;
				} catch (error) {
					return null;
				}
			}

			async function updateTitle() {
				let title = <?php echo wp_json_encode( $streamcast_station_name ); ?>;

				<?php if ( $streamcast_fetch_name_from_url ) { ?>
					let fetchedTitle = await fetchData();
					if (!fetchedTitle) {
						fetchedTitle = await fetchIceCastData();
					}
					if (fetchedTitle) {
						title = fetchedTitle;
					}
				<?php } ?>

				window.MRP?.setTitle(title);
			}

			updateTitle();
		</script>

	</div>
	<?php
} else {
	?>
	<div <?php echo wp_kses_post( get_block_wrapper_attributes() ); ?> id='<?php echo esc_attr( $id ); ?>' data-attributes='<?php echo esc_attr( wp_json_encode( $attributes ) ); ?>'></div>
<?php }
