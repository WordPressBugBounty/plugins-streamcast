<?php
$id = wp_unique_id('streamcast-');

$streamUrl = $attributes['radioPlayer']['streamURL'];
$streamPort = $attributes['radioPlayer']['streamPort'];
$playerType = $attributes['radioPlayer']['playerType'];
$welcomeMessage = $attributes['radioPlayer']['welcomeMessage'];
$skin = $attributes['radioPlayer']['skin']['name'];
$width = $attributes['radioPlayer']['skin']['width'];
$height = $attributes['radioPlayer']['skin']['height'];
$autoPlay = $attributes['radioPlayer']['autoPlay'];
$volume = $attributes['radioPlayer']['initialVolume'];
$playerPosition = $attributes['radioPlayer']['playerPosition'];

if (!function_exists('getPlayerPositionStyles')) {
    function getPlayerPositionStyles($id, $playerPosition)
    {
        $styles = "";

        if ($playerPosition === "center") {
            $styles = "margin: auto; display: block;";
        } elseif ($playerPosition === "left") {
            $styles = "margin-left: 0; margin-right: auto;";
        } elseif ($playerPosition === "right") {
            $styles = "margin-left: auto; margin-right: 0;";
        }

        return "#" . esc_attr($id) . " .musesStyleReset { $styles }";
    }
}

$dynamicPlayerStyles = getPlayerPositionStyles($id, $playerPosition);

if ($skin !== 'b_circle' && $playerType === "standard") {
    $stationName = $attributes['radioPlayer']['stationName'];
    $fetchNameFromUrl = $attributes['radioPlayer']['fetchNameFromUrl'];

?>
    <div id='<?php echo esc_attr($id); ?>'>
        <style>
            <?php echo $dynamicPlayerStyles; ?>
        </style>
        <script type="text/javascript" src="https://hosted.muses.org/mrp.js"></script>
        <script type="text/javascript">
            window.MRP.insert({
                url: "<?php echo esc_attr($streamUrl); ?>",
                lang: "en",
                codec: "mp3",
                volume: <?php echo esc_attr($volume); ?>,
                autoplay: <?php echo $autoPlay ? 'true' : 'false'; ?>,
                forceHTML5: true,
                welcome: "<?php echo esc_attr($welcomeMessage); ?>",
                jsevents: true,
                buffering: 0,
                wmode: "transparent",
                skin: "<?php echo esc_attr($skin); ?>",
                width: <?php echo esc_attr($width); ?>,
                height: <?php echo esc_attr($height); ?>,
                metadataMode: "shoutcast",
                metadataInterval: 15
            });

            var title = "<?php echo esc_attr($stationName); ?>";

            async function fetchData() {
                try {
                    const fetchUrl = "<?php echo esc_url($streamUrl); ?>/currentsong?sid=1";
                    const formData = new FormData();
                    formData.append('action', 'my_user_vote');
                    formData.append("url", fetchUrl);
                    formData.append("nonce", "wp_rest");

                    const response = await fetch("<?php echo esc_url(admin_url('admin-ajax.php')) ?>", {
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
                    const response = await fetch("<?php echo esc_url($streamUrl); ?>/status-json.xsl");
                    const data = await response.json();

                    const stream = data.icestats?.source || null;
                    if (stream) {
                        return stream.title || stream.song || stream.server_name || "No Title Available";
                    }
                    return null;
                } catch (error) {
                    return null;
                }
            }

            async function updateTitle() {
                let title = "<?php echo esc_attr($stationName); ?>";

                <?php if ($fetchNameFromUrl) { ?>
                    let fetchedTitle = await fetchData();
                    if (!fetchedTitle) {
                        fetchedTitle = await fetchIceCastData();
                    }
                    if (fetchedTitle) {
                        title = fetchedTitle;
                    }
                <?php } ?>

                MRP.setTitle(title);
            }

            updateTitle();
        </script>

    </div>
<?php
} else {
?>
    <div <?php echo get_block_wrapper_attributes(); ?> id='<?php echo esc_attr($id); ?>' data-attributes='<?php echo esc_attr(wp_json_encode($attributes)); ?>'></div>
<?php }
