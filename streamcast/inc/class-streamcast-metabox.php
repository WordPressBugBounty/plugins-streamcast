<?php

if (!defined('ABSPATH')) {
	exit;
}

if (class_exists('STREAMCAST_STREAMCAST_CSF')) {
	$streamcast_prefix = 'sc_';
	$streamcast_post_type = 'streamcast';

	$streamcast_crown_icon = '<span class="streamcast-crown-icon" style="display: inline-flex; width: 18px; height: 18px; vertical-align: middle; margin-right: 5px; align-items: center; justify-content: center;"><img src="' . STREAMCAST_PLUGIN_DIR . 'assets/crown.png" alt="pro-icon" style="width: 18px; height: 16px; display: block;" /></span>';

	// Create a metabox
	\STREAMCAST_STREAMCAST_CSF::createMetabox($streamcast_prefix, array(
		'title' => __('Radio Player Configuration', 'streamcast'),
		'post_type' => $streamcast_post_type,
		'data_type' => 'unserialize',
		'context' => 'normal',
	));


	\STREAMCAST_STREAMCAST_CSF::createSection($streamcast_prefix, array(
		'title' => __('Required fields are marked with an * (asterisk)', 'streamcast'),
		'fields' => array(
			array(
				'id' => 'opt-radio',
				'type' => 'radio',
				'title' => __('Radio Player Type*', 'streamcast'),
				'desc' => __('You must choose radio player type first to get related settings fields.', 'streamcast'),
				'options' => array(
					'minimal' => __('Minimal', 'streamcast'),
					'standard' => __('Standard', 'streamcast'),
					'advanced' => __('Advanced', 'streamcast'),
					'ultimate' => __('Ultimate', 'streamcast'),
					'echoStream' => __('EchoStream', 'streamcast'),
					'auroraPlay' => __('AuroraPlay', 'streamcast'),
					"wooden" => __("Wooden", 'streamcast')
				),
				'default' => 'minimal',
				'inline' => true,
			),

			array(
				'id' => 'stream_url',
				'type' => 'text',
				'dependency' => array('opt-radio|opt-radio|opt-radio|opt-radio', '!=|!=|!=|!=', 'ultimate|echoStream|auroraPlay|wooden'),
				'title' => __('Stream URL*', 'streamcast'),
				'button_title' => __('Add or Upload File', 'streamcast'),
				'remove_title' => __('Remove Mp3', 'streamcast'),
				'default' => 'https://media-ssl.musicradio.com/HeartLondon'
			),

			array(
				'id' => 'stream_url_echoXaurora',
				'type' => 'text',
				'dependency' => array('opt-radio', 'any', 'echoStream,auroraPlay,wooden'),
				'title' => __('Stream URL', 'streamcast'),
				'desc' => 'Enter a stream url. <br/><i>Important</i><li>If your site is secured ( https://) then the stream url must be secure (https://)</li> ',
				'button_title' => __('Select a .pls file', 'streamcast'),
				'remove_title' => __('Remove pls', 'streamcast'),
				'default' => "https://media-ssl.musicradio.com/HeartLondon"
			),

			array(
				'id' => 'station_name',
				'type' => 'text',
				'dependency' => array('opt-radio|opt-radio|opt-radio|opt-radio|opt-radio', '!=|!=|!=|!=|!=', 'minimal|ultimate|echoStream|auroraPlay|wooden'),
				'default' => 'Station Name',
				'title' => __('Station Name*', 'streamcast'),
			),

			array(
				'id' => 'station_name_echoXauroraXwooden',
				'type' => 'text',
				'dependency' => array('opt-radio', 'any', 'echoStream,auroraPlay,wooden'),
				'title' => __('Station Name*', 'streamcast'),
				'default' => 'Hello London',
			),

			array(
				'id' => 'welcome_msgs',
				'type' => 'text',
				'dependency' => array('opt-radio|opt-radio|opt-radio|opt-radio|opt-radio', '!=|!=|!=|!=|!=', 'minimal|ultimate|echoStream|auroraPlay|wooden'),
				'default' => "Welcome Message",
				'title' => __('Welcome Message*', 'streamcast'),
			),

			array(
				'id' => 'welcomeMsg_echoXaurora',
				'type' => 'text',
				'dependency' => array('opt-radio', 'any', 'echoStream,auroraPlay'),
				'title' => __('Artist/Fm Name*', 'streamcast'),
				'default' => '106.2',
			),

			array(
				'id' => 'widthXaurora',
				'type' => 'text',
				'dependency' => array('opt-radio', 'any', 'auroraPlay,wooden'),
				'title' => __('Player Width', 'streamcast'),
				'default' => '100%',
			),

			array(
				'id' => 'widthXecho',
				'type' => 'text',
				'dependency' => array('opt-radio', '==', 'echoStream'),
				'title' => __('Player Width', 'streamcast'),
				'default' => '450px',
			),

			array(
				'id' => 'player_skin',
				'type' => 'select',
				'title' => __('Skin', 'streamcast'),
				'default' => 'mcclean',
				'dependency' => array('opt-radio', '==', 'standard'),
				'options' => array(
					'' => '==Official Skins==',
					'mcclean' => 'McClean (180x60)',
					'radiovoz' => 'RadioVoz (220x69)',
					'faredirfare' => 'Faredirfare (269x52)',
					'tweety' => 'Tweety (189x62)',
					'compact' => 'Compact (191x46)',
					'cassette' => 'Tim Simz - Cassette (200x120)',
					'repvku-100' => 'Repvku-100 (100x25)',
					'darkconsole' => 'DarkConsole (190x62)',
					'tiny' => 'Tiny (130x60)',
					'universelle' => 'Universelle (155x65)',
					'uuskin' => 'UUSkin (166x83)',
					'e76' => 'E76 (130x75)',
					'original' => 'Original (329x21)',
					'arvyskin' => 'Arvy Skin [M] (560x30)',
					'eastanbul' => 'Eastanbul (467x26)',
					'substream' => 'Substream (180x30)',
					'banita' => 'BANita (110x25)',
					'listen-live' => 'Listen Live (250x100)',
					'easyplay' => 'EasyPlay (231x30)',
					'stockblue' => 'Stockblue (476x26)',
					'largebayfm' => 'LargeBayFM (451x90)',
					'simple-blue' => 'Simple Blue [M] (300x122)',
					'simple-gray' => 'Simple Gray [M] (300x122)',
					'simple-green' => 'Simple Green [M] (300x122)',
					'simple-orange' => 'Simple Orange [M] (300x122)',
					'simple-red' => 'Simple Red [M] (300x122)',
					'simple-violet' => 'Simple Violet [M] (300x122)',
					'scradio' => 'SCRadio (160x100)',
					'repvku-115' => 'Repvku-115 (115x25)',
					'rb1' => 'RB1 (250x70)',
					'tandem-115' => 'Tandem-115 (115x25)',
					'simcha-232-toggle' => 'Simcha-232 [T] (232x58)',
					'simcha-232' => 'Simcha-232 (232x58)',
					'simcha-320' => 'Simcha-320 (320x58)',
					'kplayer' => 'KPlayer (220x200)',
					'appy' => 'Appy [T] (250x213)',
					'blueberry' => 'Blueberry (338x102)',
					'oldradio' => 'OldRadio (205x132)',
					'oldradio-christmas' => 'OldRadio Christmas (205x132)',
					'oldstereo' => 'OldStereo (318x130)',
					'xm' => 'Xm (234x66)',
					'abrahadabra' => 'Abrahadabra (100x141)',
					'abrahadabra2' => 'Abrahadabra 2 (100x141)',
					'wmp' => 'WMP (386x47)',
					'radioport' => 'Radioport (700x150)',
					'alberto' => 'Alberto (250x95)',
					'ff' => 'FF (288x68)',
					'neon' => 'Neon (240x76)',
					'player-stm' => 'Player STM (128x30)',
					'neonslim' => 'NeonSlim (501x32)',
					'greyslim' => 'GreySlim (494x35)',
					'demon' => 'Demon (468x117)',
					'xavi' => 'Xavi (250x95)',
					'xavi2' => 'Xavi2 (95x95)',
					'xavi3' => 'Xavi3 (250x95)',
					'minimal' => 'Minimal (220x80)',
					'grind' => 'Grind (400x336)',
					'cpr-180' => 'CPR-180 (180x40)',
					'ammascota' => 'Am Mascota (290x100)',
					'miniradio' => 'MiniRadio (275x112)',
					'myradio' => 'My Radio (262x165)',
					'terawhite' => 'Terawhite (255x100)',
					'kelabu-yellow' => 'Kelabu Yellow (253x100)',
					'cristal' => 'Cristal (300x113)',
					'bintang' => 'Bintang (300x113)',
					'tatarradiosi' => 'Tatar Radiosi (418x150)',
					'redsradiosml' => 'Reds Radio SML (500x158)',
					'bogusblue' => 'BogusBlue (660x266)',
					'bones' => 'Bones (341x125)',
					'combat' => 'Combat (675x247)',
					'dragonblues' => 'DragonBlues (400x145)',
					'lemon' => 'Lemon (410x60)',
					'limed' => 'Limed (397x115)',
					'longtail' => 'Longtail (498x61)',
					'pinhead' => 'Pinhead (421x120)',
					'retro' => 'Retro (669x259)',
					'silvertune' => 'Silvertune (200x104)',
					'testskin' => 'Test/Develop (189x61)',
					'retromatic' => 'Retromatic (700x150)',
					'retromaticsmall' => 'Retromatic Small (298x150)',
					'e90' => 'E90 (190x59)',
					'shmusic' => 'SH Music (300x190)',
					'brujulalatina' => 'Brújula Latina (330x100)',
					'adn' => 'ADN (700x150)',
				)
			),

			// Ultimate
			array(
				'id' => 'streamProvider',
				'type' => 'button_set',
				'title' => __('Stream Provider*', 'streamcast'),
				'options' => array(
					'shout-cast' => __('SHOUT cast', 'streamcast'),
					'ice-cast' => __('Ice cast', 'streamcast'),
					'other' => __('Other', 'streamcast')
				),
				'default' => 'shout-cast',
				'dependency' => array('opt-radio', '==', 'ultimate'),
			),

			array(
				'id' => 'streamURL',
				'type' => 'text',
				'title' => __('Stream URL *', 'streamcast'),
				'default' => 'http://s5-webradio.antenne.de/antenne?icy=https',
				'dependency' => array('opt-radio', '==', 'ultimate'),
			),

			array(
				'id' => 'streamPort',
				'type' => 'number',
				'title' => __('Stream Port *', 'streamcast'),
				'default' => '8009',
				'dependency' => array('opt-radio|streamProvider', '==|!=', 'ultimate|other'),
			),

			array(
				'id' => 'streamMountPoint',
				'type' => 'text',
				'title' => __('Stream Mount Point *', 'streamcast'),
				'dependency' => array('streamProvider|opt-radio', '==|==', 'ice-cast|ultimate'),
				'default' => '/stream',
			),

			array(
				'id' => 'radioName',
				'type' => 'text',
				'title' => __('Station Name', 'streamcast'),
				'default' => 'Station Name',
				'dependency' => array('opt-radio', '==', 'ultimate'),
			),

			// Customization
			array(
				'id' => 'playerWidth',
				'type' => 'text',
				'title' => __('Player Width', 'streamcast'),
				'default' => '100%',
				'dependency' => array('opt-radio', '==', 'ultimate'),
			),

			array(
				'id' => 'contentColor',
				'type' => 'color',
				'title' => __('Content Color', 'streamcast'),
				'dependency' => array('playerColors|opt-radio', '==|==', 'custom|ultimate'),
				'default' => '#fff',
			),

			array(
				'id' => 'blur_effect',
				'type' => 'spinner',
				'dependency' => array('opt-radio', '==', 'echoStream'),
				'title' => __('Blur Effect', 'streamcast'),
				'default' => 7,
				'min' => 0,
				'max' => 100,
				'unit' => 'px',
			),

			array(
				'id' => 'bgXaurora',
				'type' => 'color',
				'dependency' => array('opt-radio', '==', 'auroraPlay'),
				'title' => __('Background Color', 'streamcast'),
				'default' => '#000000',
			),

			array(
				'id' => 'bgXwooden',
				'type' => 'color',
				'dependency' => array('opt-radio', '==', 'wooden'),
				'title' => __('Background Color', 'streamcast'),
				'default' => '#693328',
			),

			array(
				'id' => 'contentColorEchoXauroraXwooden',
				'type' => 'color',
				'dependency' => array('opt-radio', 'any', 'echoStream,auroraPlay,wooden'),
				'title' => __('Content Color', 'streamcast'),
				'default' => '#ffffff',
			),


			// --- PRO Features Announcement Fields ---
			// Pro Features for Minimal
			array(
				'type' => 'content',
				'content' => '
                    <div class="streamcast-pro-notice-box">
                        <h4 class="streamcast-pro-notice-title">🚀 Get More with Premium Version</h4>
                        <p class="streamcast-pro-notice-desc">The following features are available in the Premium Version:</p>
                        <ul class="streamcast-pro-notice-list">
                            <li><strong>Custom CSS:</strong> Add your own styles to match your site perfectly.</li>
                            <li><strong>Player Position:</strong> Align your player to Left, Center, or Right.</li>
                        </ul>
                        <a href="edit.php?post_type=streamcast&page=streamcast#/pricing" class="streamcast-pro-notice-button">' . $streamcast_crown_icon . ' Get Preemium</a>
                    </div>
                ',
				'dependency' => array('opt-radio', '==', 'minimal'),
			),
			// Pro Features for Standard
			array(
				'type' => 'content',
				'content' => '
                    <div class="streamcast-pro-notice-box">
                        <h4 class="streamcast-pro-notice-title">🚀 Get More with Premium Version</h4>
                        <p class="streamcast-pro-notice-desc">The following features are available in the Premium Version:</p>
                        <ul class="streamcast-pro-notice-list">
                            <li><strong>Auto Play & Volume:</strong> Automatically start playing with preset volume.</li>
                            <li><strong>Fetch Name From URL:</strong> Get station name automatically from the stream.</li>
                            <li><strong>Custom CSS & Position:</strong> Full control over styling and placement.</li>
                        </ul>
                        <a href="edit.php?post_type=streamcast&page=streamcast#/pricing" class="streamcast-pro-notice-button">' . $streamcast_crown_icon . ' Get Preemium</a>
                    </div>
                ',
				'dependency' => array('opt-radio', '==', 'standard'),
			),
			// Pro Features for Advanced
			array(
				'type' => 'content',
				'content' => '
                    <div class="streamcast-pro-notice-box">
                        <h4 class="streamcast-pro-notice-title">🚀 Get More with Premium Version</h4>
                        <p class="streamcast-pro-notice-desc">The following features are available in the Premium Version:</p>
                        <ul class="streamcast-pro-notice-list">
                            <li><strong>Custom Artwork:</strong> Show beautiful station images (94x94 px).</li>
                            <li><strong>Show Time & BG Color:</strong> Display duration and customize the layout color.</li>
                            <li><strong>Full Control:</strong> Autoplay, Volume, and Metadata fetching included.</li>
                        </ul>
                        <a href="edit.php?post_type=streamcast&page=streamcast#/pricing" class="streamcast-pro-notice-button">' . $streamcast_crown_icon . ' Get Preemium</a>
                    </div>
                ',
				'dependency' => array('opt-radio', '==', 'advanced'),
			),
			// Pro Features for Ultimate
			array(
				'type' => 'content',
				'content' => '
                    <div class="streamcast-pro-notice-box">
                        <h4 class="streamcast-pro-notice-title">🚀 Get More with Premium Version</h4>
                        <p class="streamcast-pro-notice-desc">The following features are available in the Premium Version:</p>
                        <ul class="streamcast-pro-notice-list">
                            <li><strong>Visualizer:</strong> Stunning dynamic audio waves while playing.</li>
                            <li><strong>Advanced Themes:</strong> Choice of Dodger Blue, Bittersweet, and more.</li>
                            <li><strong>Custom Branding:</strong> Poster images, BG images, and custom color overlays.</li>
                            <li><strong>Full Metadata:</strong> Fetch station and artist name from URL.</li>
                        </ul>
                        <a href="edit.php?post_type=streamcast&page=streamcast#/pricing" class="streamcast-pro-notice-button">' . $streamcast_crown_icon . ' Get Preemium</a>
                    </div>
                ',
				'dependency' => array('opt-radio', '==', 'ultimate'),
			),
			// Pro Features for Echo, Aurora, Wooden (Combined Message)
			array(
				'type' => 'content',
				'content' => '
                    <div class="streamcast-pro-notice-box">
                        <h4 class="streamcast-pro-notice-title">🚀 Get More with Premium Version</h4>
                        <p class="streamcast-pro-notice-desc">The following features are available in the Premium Version:</p>
                        <ul class="streamcast-pro-notice-list">
                            <li><strong>Background & Blur:</strong> Set background images with stylish blur effects.</li>
                            <li><strong>Detailed Styling:</strong> Customize Station Name, Artist Name, and Hover colors.</li>
                            <li><strong>Wooden Special:</strong> Full control over Timestamp and Hover backgrounds.</li>
                        </ul>
                        <a href="edit.php?post_type=streamcast&page=streamcast#/pricing" class="streamcast-pro-notice-button">' . $streamcast_crown_icon . ' Get Preemium</a>
                    </div>
                ',
				'dependency' => array('opt-radio', 'any', 'echoStream,auroraPlay,wooden'),
			),



		)
	));

}






