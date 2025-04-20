<?php //Lets register our shortcode
function sc_radio_player_cb($atts)
{
  extract(shortcode_atts(array(

    'id' => null,
    'url' => null,
    'background' => null,

  ), $atts));

  $post_type = get_post_type($id);
  if ($post_type != 'streamcast') {
    return false;
  }

  $player_type = stp_get_meta($id, 'opt-radio', "minimal");

  $stationName = stp_get_meta($id, 'station_name', "Station Name");
  $welcomeMsg = stp_get_meta($id, 'welcome_msgs', "Welcome Message");
  $streamURL = stp_get_meta($id, 'stream_url', "https://media-ssl.musicradio.com/HeartLondon");
  $artWork = "https://templates.bplugins.com/wp-content/uploads/2025/02/streamcast-demo-ultimate-1.png";
  $backgroundColor = stp_get_meta($id, 'background', "#f09f8b");
  $playerWidth =  stp_get_meta($id, 'playerWidth', "100%");


  if ($player_type === "ultimate") {
    $stationName = stp_get_meta($id, "radioName", "Station Name");
    $streamURL = esc_attr(stp_get_meta($id, "streamURL", "http://s5-webradio.antenne.de/antenne?icy=https"));
    $playerWidth =  stp_get_meta($id, 'playerWidth', "100%");
  } elseif (in_array($player_type, ["auroraPlay", "echoStream", "wooden"])) {
    $stationName = stp_get_meta($id, 'station_name_echoXauroraXwooden', "Hello London");
    $welcomeMsg = stp_get_meta($id, 'welcomeMsg_echoXaurora', '106.2');
    $streamURL = stp_get_meta($id, 'stream_url_echoXaurora', "https://media-ssl.musicradio.com/HeartLondon");
    if ($player_type === "auroraPlay") {
      $artWork = "https://danialsabagh.com/singleaudioplayer/img/radio.jpg";
      $playerWidth =  stp_get_meta($id, 'widthXaurora', "100%");
      $backgroundColor = stp_get_meta($id, 'bgXaurora', "#000000" );
    }elseif ($player_type === "wooden") {
      $playerWidth =  stp_get_meta($id, 'widthXaurora', "100%");
      $backgroundColor = stp_get_meta($id, 'bgXwooden', "#693328");
  } else {
      $playerWidth =  stp_get_meta($id, 'widthXecho', "450px");
    }
  } elseif ($player_type === "standard") {
    $welcomeMsg = stp_get_meta($id, 'welcome_msgs', "Welcome Message");
  } else {
    $streamURL = esc_attr(stp_get_meta($id, 'stream_url', "https://media-ssl.musicradio.com/HeartLondon"));
    $backgroundColor = stp_get_meta($id, 'background', "#f09f8b");
  }

  $block = [
    "blockName" => "scb/streamcast-block",
    "attrs" => [
      "radioPlayer" => [
        "playerType" => $player_type === 'plyr' ? 'minimal' : $player_type,
        "streamURL" => $streamURL,
        "streamProvider" => esc_attr(stp_get_meta($id, 'streamProvider', 'shoutcast')),
        "streamPort" => esc_attr(stp_get_meta($id, 'streamPort', "8000")),
        "streamMountPoint" => esc_attr(stp_get_meta($id, 'streamMountPoint', "house.320k.mp3")),
        "fetchNameFromUrl" => false,
        "stationName" =>  $stationName,
        "welcomeMessage" => $welcomeMsg,
        "artWork" => ["url" => $artWork],
        "autoPlay" => false,
        "showTime" => 1,
        "playerPosition" => "center",
        "skin" => [
          "name" => "mcclean",
          "width" => 180,
          "height" => 60
        ],
        "initialVolume" => 65
      ],
      "radioStyles" => [
        "playerWidth" =>  $playerWidth,
        "ultimate" => [
          "playerColors" => "theme",
          "playerTheme" => "dodgerBlue",
          "playerOverlayColor" => "rgba(15, 17, 21, 0.5)",
          "thumbnailBorderColor" => "rgba(255, 255, 255, 0.2)",
          "contentColor" => "#fff",
          "buttonHoverColor" =>  "orangered",
          "progressActiveColor" => "orangered",
          "visualizerColor" => "orangered",
          "playerBackgroundImg" => "",
          "posterImg" => [
			  "url" => "https://templates.bplugins.com/wp-content/uploads/2025/02/streamcast-demo-ultimate-1.png"
		  ]
        ],
        "echoStream" => [
          "bgImg" => [
			  "url" => "https://danialsabagh.com/singleaudioplayer/img/radio.jpg"
		  ],
          "blur" => 7
        ],
        "backgroundColor" => $backgroundColor,
        "stationName" => [
          "color" => "white",
          "hoverColor" => "",
          "typo" => [
            "fontSize" => [
              "desktop" => 18
            ]
            ],
          "bgColor" => "#50241b",
          "hoverBgColor" => ""
        ],
        "artistFmName" => [
          "color" =>  "white",
          "typo" => [
            "fontSize" => [
              "desktop" => 15
            ]
          ]
        ],
        "contentColor" => "white",
        "playBtnColor" => "red",
        "timeStamp" => [
          "color" => "white",
          "hoverColor" => "",
          "typo" => [
            "fontSize" => [
              "desktop" => 18
            ]
            ],
          "bgColor" => "#50241b",
          "hoverBgColor" => ""
        ]
      ],
      'css' =>  esc_html(stp_get_meta($id, 'custom_css', "/* Your Custom CSS here	  */")),
    ],
    "innerBlocks" => [],
    "innerHTML" => "",
    "innerContent" => []
  ];

  return render_block($block);

}
add_shortcode('radio_player', 'sc_radio_player_cb');

