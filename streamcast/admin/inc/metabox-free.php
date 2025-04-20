<?php // Silence is golden.
// Control core classes for avoid errors
if (class_exists('CSF')) {

    add_action('admin_footer', function () {
?>
        <style>
            .bplugins-meta-readonly {
                /* pointer-events: none; */
                opacity: 0.6;
            }

            .csf-field.bplugins-meta-readonly:hover::after {
                display: block;
            }

            .csf-field.bplugins-meta-readonly::before {
                display: block;
                width: 100%;
                height: 100%;
                content: "";
                position: absolute;
                z-index: 999;
                overflow: hidden;
                top: 0;
                left: 0;
            }

            .csf-field.bplugins-meta-readonly::after {
                display: none;
                content: "The option is available in the pro version only";
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                z-index: 9999999;
                font-size: 22px;
                background: #673ab7;
                color: #fff;
                padding: 10px 13px;
                border-radius: 3px;
            }
        </style>
<?php
    });

    //
    // Set a unique slug-like ID
    $prefix = 'sc_';

    // Create a metabox
    CSF::createMetabox($prefix, array(
        'title'     => 'Radio Player Configuration',
        'post_type' => 'streamcast',
        'data_type' => 'unserialize',
        'context'   => 'normal', // The context within the screen where the boxes should display. `normal`, `side`, `advanced`
    ));

    //
    // Create a section
    CSF::createSection($prefix, array(
        'title'  => 'Required fields are marked with an * (asterisk)',
        'fields' => array(
            array(
                'id'      => 'opt-radio',
                'type'    => 'radio',
                'title'   => 'Radio Player Type *',
                'desc'    =>  'You must choose radio player type first to get related settings fields.', 
                'options' => array(
                    'minimal'     => 'Minimal',
                    'standard' => 'Standard',
                    'advanced' => 'Advanced',
                    'ultimate' => 'Ultimate',
                    'echoStream' => 'EchoStream',
                    'auroraPlay' => 'AuroraPlay',
                    "wooden" => "Wooden"
                ),
                'default' => 'minimal',
                'inline'  => true,
            ),


            array(
                'id'            => 'stream_url',
                'type'          => 'text',
                'dependency'   => array('opt-radio|opt-radio|opt-radio|opt-radio', '!=|!=|!=|!=', 'ultimate|echoStream|auroraPlay|wooden'),
                'title'         =>  'Stream URL*', 
                'button_title'  => 'Add or Upload File', 
                'remove_title'  => 'Remove Mp3', 
                'default'      => 'https://media-ssl.musicradio.com/HeartLondon'
            ),
            array(
                'id'           => 'stream_url_echoXaurora',
                'type'         => 'text',
                'dependency'   => array('opt-radio', 'any', 'echoStream,auroraPlay,wooden'),
                'title'        => 'Stream URL', 'streamcast',
                'desc'         => 'Enter a stream url. <br/><i>Important</i><li>If your site is secured ( https://) then the stream url must be secure (https://)</li> ',
                'button_title' => 'Select a .pls file', 
                'remove_title' => 'Remove pls', 
                'default'      => "https://media-ssl.musicradio.com/HeartLondon"
            ),


            array(
                'id'    => 'station_name',
                'type'  => 'text',
                'dependency' => array('opt-radio|opt-radio|opt-radio|opt-radio', '!=|!=|!=|!=', 'ultimate|echoStream|auroraPlay|wooden'),
                'default' => 'Station Name',
                'title' => 'Station Name*',
            ),

            array(
                'id'         => 'station_name_echoXauroraXwooden',
                'type'       => 'text',
                'dependency' => array('opt-radio', 'any', 'echoStream,auroraPlay,wooden'),
                'title'      => 'Station Name*', 
                'default'    => 'Hello London',
            ),
            array(
                'id'    => 'fetch_name_from_url',
                'type'  => 'switcher',
                'class' => 'bplugins-meta-readonly',
                'desc'       => "If station name can't access from URL then will use the given station name",
                'dependency' => array('opt-radio|opt-radio', '!=|!=', 'minimal|ultimate'),
                'title' => 'Fetch Name From URL', 
                'default' => false
            ),
            array(
                'id'    => 'welcome_msgs',
                'type'  => 'text',
                'dependency' => array('opt-radio|opt-radio|opt-radio|opt-radio', '!=|!=|!=|!=', 'ultimate|echoStream|auroraPlay|wooden'),
                'default' => "Welcome Message",
                'title' => 'Welcome Message*', 
            ),

            array(
                'id'         => 'welcomeMsg_echoXaurora',
                'type'       => 'text',
                'dependency' => array('opt-radio', 'any', 'echoStream,auroraPlay'),
                'title'      => 'Artist/Fm Name*', 
                'default'    => '106.2',
            ),
            array(
                'id'         => 'widthXaurora',
                'type'       => 'text',
                'dependency' => array('opt-radio', 'any', 'auroraPlay,wooden'),
                'title'      => 'Player Width', 
                'default'    => '100%',
            ),
            array(
                'id'         => 'widthXecho',
                'type'       => 'text',
                'dependency' => array('opt-radio', '==', 'echoStream'),
                'title'      => 'Player Width', 
                'default'    => '450px',
            ),

            array(
                'id'       => 'player_skin',
                'type'     => 'select',
                'title'    => 'Skin',
                'default'  => 'mcclean',
                'dependency' => array('opt-radio', '==', 'standard'),
                'class' => 'bplugins-meta-readonly',
                'options'     => array(
                    ''  => '==Official Skins==',
                    'mcclean'  => 'McClean (180x60)',
                    'radiovoz'  => 'RadioVoz (220x69)',
                    'faredirfare'  => 'Faredirfare (269x52)',
                    'tweety'  => 'Tweety (189x62)',
                    'compact'  => 'Compact (191x46)',
                    'cassette'  => 'Tim Simz - Cassette (200x120)',
                    'repvku-100'  => 'Repvku-100 (100x25)',
                    'darkconsole'  => 'DarkConsole (190x62)',
                    'tiny'  => 'Tiny (130x60)',
                    'universelle'  => 'Universelle (155x65)',
                    'uuskin'  => 'UUSkin (166x83)',
                    'e76'  => 'E76 (130x75)',
                    'original'  => 'Original (329x21)',
                    'arvyskin'  => 'Arvy Skin [M] (560x30)',
                    'eastanbul'  => 'Eastanbul (467x26)',
                    'substream'  => 'Substream (180x30)',
                    'banita'  => 'BANita (110x25)',
                    'listen-live'  => 'Listen Live (250x100)',
                    'easyplay'  => 'EasyPlay (231x30)',
                    'stockblue'  => 'Stockblue (476x26)',
                    'largebayfm'  => 'LargeBayFM (451x90)',
                    'simple-blue'  => 'Simple Blue [M] (300x122)',
                    'simple-gray'  => 'Simple Gray [M] (300x122)',
                    'simple-green'  => 'Simple Green [M] (300x122)',
                    'simple-orange'  => 'Simple Orange [M] (300x122)',
                    'simple-red'  => 'Simple Red [M] (300x122)',
                    'simple-violet'  => 'Simple Violet [M] (300x122)',
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

            array(
                'id'      => 'autoplay',
                'type'    => 'switcher',
                'dependency' => array('opt-radio', '==', 'standard'),
                'title'   => 'Auto Play', 
                'class' => 'bplugins-meta-readonly',
                'default' => false // or false
            ),



            array(
                'id'       => 'volume',
                'type'     => 'spinner',
                'dependency' => array('opt-radio', '==', 'standard'),
                'title'    => 'Initial Volume', 
                'class' => 'bplugins-meta-readonly',
                'default'  => '65',
                'min'      => '0',
                'max'      => '100',
                'unit'     => '%',
            ),

            array(
                'id'            => 'artwork',
                'library'    => 'image',
                'type'       => 'media',
                'dependency'   => array('opt-radio', '==', 'advanced'),
                'title'         => 'ArtWork', 
                'default'    => array(
                    'url'         => 'https://templates.bplugins.com/wp-content/uploads/2025/02/streamcast-demo-ultimate-1.png',
                    'id'          => '',
                    "width"       => 400,
                    "height"      => 400,
                    "thumbnail"   => 'https://templates.bplugins.com/wp-content/uploads/2025/02/streamcast-demo-ultimate-1.png',
                    "alt"         => "on-air",
                    "title"       => "On Air",
                    "description" => "",
                ),
                'class' => "bplugins-meta-readonly",
                'desc'  => '94x94 px photo is the standard artwork size, accepted file type .png, .jpeg, .jpg ', 
            ),
            
            array(
                'id'      => 'autoplay',
                'type'    => 'switcher',
                'dependency' => array('opt-radio', '==', 'advanced'),
                'title'   => 'Auto Play', 
                'class' => 'bplugins-meta-readonly',
                'default' => false // or false
            ),
            array(
                'id'       => 'volume',
                'type'     => 'spinner',
                'dependency' => array('opt-radio', '==', 'advanced'),
                'title'    => 'Initial Volume', 
                'class' => 'bplugins-meta-readonly',
                'default'  => '65',
                'min'      => '0',
                'max'      => '100',
                'unit'     => '%',
            ),
            array(
                'id'      => 'timeholder',
                'type'    => 'switcher',
                'class' => 'bplugins-meta-readonly',
                'dependency' => array('opt-radio', '==', 'advanced'),
                'title'   => 'Show Time', 
                'default' => true // or false
            ),
            array(
                'id'      => 'background',
                'type'    => 'color',
                'class' => 'bplugins-meta-readonly',
                'dependency' => array('opt-radio', '==', 'advanced'),
                'title'   => 'Background color', 
                'default' => '#f09f8b' // or false
            ),

            // Ultimate


            array(
                'id'         => 'streamProvider',
                'type'       => 'button_set',
                'title'      => 'Stream Provider*',
                'options'    => array(
                    'shout-cast' => 'SHOUT cast',
                    'ice-cast'   => 'Ice cast',
                    'other' => 'Other'
                ),
                'default'    => 'shout-cast',
                'dependency' => array('opt-radio', '==', 'ultimate'),
            ),

            array(
                'id'         => 'streamURL',
                'type'       => 'text',
                'title'      => 'Stream URL *',
                'default'    => 'http://s5-webradio.antenne.de/antenne?icy=https',
                'dependency' => array('opt-radio', '==', 'ultimate'),
            ),

            array(
                'id'         => 'streamPort',
                'type'       => 'number',
                'title'      => 'Stream Port *',
                'default'    => '8009',
                'dependency' => array('opt-radio|streamProvider', '==|!=', 'ultimate|other'),
            ),

            array(
                'id'         => 'streamMountPoint',
                'type'       => 'text',
                'title'      => 'Stream Mount Point *',
                'dependency' => array('streamProvider|opt-radio', '==|==', 'ice-cast|ultimate'),
                'default'    => '/stream',
            ),



            array(
                'id'         => 'radioName',
                'type'       => 'text',
                'title'      => 'Station Name',
                'default'    => 'Station Name',
                'dependency' => array('opt-radio', '==', 'ultimate'),
            ),

            array(
                'id'    => 'fetch_name_from_url',
                'type'  => 'switcher',
                'class' => 'bplugins-meta-readonly',
                'desc'       => "If station name can't access from URL then will use the given station name",
                'dependency' => array('opt-radio', '==', 'ultimate'),
                'title' => 'Fetch Name From URL', 
                'default' => false
            ),

            // Customization
            array(
                'id'         => 'playerWidth',
                'type'       => 'text',
                'title'      => 'Player Width',
                'default'    => '100%',
                'dependency' => array('opt-radio', '==', 'ultimate'),
            ),
            array(
                'id'         => 'radioImage',
                'library'       => 'image',
                'type'       => 'media',
                'class' => 'bplugins-meta-readonly',
                'title'      => 'Poster Image',
                'default'    => array(
                    'url'         => 'https://templates.bplugins.com/wp-content/uploads/2025/02/streamcast-demo-ultimate-1.png',
                    'id'          => '',
                    "width"       => 400,
                    "height"      => 400,
                    "thumbnail"   => 'https://templates.bplugins.com/wp-content/uploads/2025/02/streamcast-demo-ultimate-1.png',
                    "alt"         => "on-air",
                    "title"       => "On Air",
                    "description" => "",
                ),
                'dependency' => array('opt-radio', '==', 'ultimate'),
            ),

            array(
                'id'         => 'bgImage',
                'library'       => 'image',
                'type'       => 'media',
                'class' => 'bplugins-meta-readonly',
                'title'      => 'Player Background Image',
                'dependency' => array('opt-radio', '==', 'ultimate'),
            ),

            array(
                'id'         => 'player_postiion',
                'type'       => 'radio',
                'title'      => 'Player Position',
                'class' => 'bplugins-meta-readonly',
                //   'dependency' => array( 'opt-radio|opt-radio', '!=|!=', 'standard|ultimate' ),
                'options'    => array(
                    'left'     => 'Left',
                    'center'   => 'Center',
                    'right'    => 'Right',

                ),
                'default'    => 'center',
                'inline'    => true
            ),

            array(
                'id'         => 'playerColors',
                'type'       => 'button_set',
                'title'      => 'Player Colors',
                'options'    => array(
                    'theme'  => 'Theme',
                    'custom' => 'Custom Color',
                ),
                'default'    => 'theme',
                'class' => 'bplugins-meta-readonly',
                'dependency' => array('opt-radio', '==', 'ultimate'),
            ),

            // Themes
            array(
                'id'         => 'playerThemes',
                'type'       => 'button_set',
                'title'      => 'Player Themes',
                'options'    => array(
                    'dodgerBlue'    => 'Dodger Blue',
                    'bittersweet'   => 'Bittersweet',
                    'lightSeaGreen' => 'Light Sea Green',
                ),
                'dependency' => array('playerColors|opt-radio', '==|==', 'theme|ultimate'),
                'default'    => 'dodgerBlue',
                'class' => 'bplugins-meta-readonly',
            ),

            // Custom Colors
            array(
                'id'         => 'playerOverlayColor',
                'type'       => 'color',
                'title'      => 'Player Overlay Color',
                'dependency' => array('playerColors|opt-radio', '==|==', 'custom|ultimate'),
                'class' => 'bplugins-meta-readonly',
                'default'    => 'rgba(15, 17, 21, 0.5)',
            ),
            array(
                'id'         => 'imgBorderColor',
                'type'       => 'color',
                'title'      => 'Thumbnail Border Color',
                'dependency' => array('playerColors|opt-radio', '==|==', 'custom|ultimate'),
                'class' => 'bplugins-meta-readonly',
                'default'    => 'rgba(255, 255, 255, 0.2)',
            ),
            array(
                'id'         => 'contentColor',
                'type'       => 'color',
                'title'      => 'Content Color',
                'dependency' => array('playerColors|opt-radio', '==|==', 'custom|ultimate'),
                'class' => 'bplugins-meta-readonly',
                'default'    => '#fff',
            ),
            array(
                'id'         => 'btnHoverColor',
                'type'       => 'color',
                'title'      => 'Button Hover Color',
                'dependency' => array('playerColors|opt-radio', '==|==', 'custom|ultimate'),
                'class' => 'bplugins-meta-readonly',
                'default'    => 'orangered',
            ),
            array(
                'id'         => 'progressColor',
                'type'       => 'color',
                'title'      => 'Progress Active Color',
                'dependency' => array('playerColors|opt-radio', '==|==', 'custom|ultimate'),
                'class' => 'bplugins-meta-readonly',
                'default'    => 'orangered',
            ),
            array(
                'id'         => 'visualizerColor',
                'type'       => 'color',
                'title'      => 'Visualizer Color',
                'dependency' => array('playerColors|opt-radio', '==|==', 'custom|ultimate'),
                'default'    => 'orangered',
                'class' => 'bplugins-meta-readonly',
            ),



        // EchoStream 
        array(
            'id'         => 'echo_bg_image',
            'library'       => 'image',
            'type'       => 'media',
            'class'      => 'bplugins-meta-readonly',
            'title'      => 'Upload Background Image',
            'default'    => array(
                'url'         => 'https://danialsabagh.com/singleaudioplayer/img/radio.jpg',
                'id'          => '',
                "width" => 612,
                "height" => 408,
                "thumbnail" => 'https://danialsabagh.com/singleaudioplayer/img/radio.jpg',
                "alt" => "",
                "title" => "radio-player-image",
                "description" => "",
            ),
            'dependency' => array('opt-radio', '==', 'echoStream'),
        ),
        array(
            'id'         => 'blur_effect',
            'type'       => 'spinner',
            'class'      => 'bplugins-meta-readonly',
            'dependency' => array('opt-radio', '==', 'echoStream'),
            'title'      => 'Blur Effect', 
            'default'    => 7,
            'min'        => 0,
            'max'        => 100,
            'unit'       => 'px',
        ),
        array(
            'id'         => 'bgXaurora',
            'type'       => 'color',
            'class'      => 'bplugins-meta-readonly',
            'dependency' => array('opt-radio', '==', 'auroraPlay'),
            'title'      => 'Background Color', 
            'default'    => '#000000', 
        ),
        array(
            'id'         => 'bgXwooden',
            'type'       => 'color',
            'class'      => 'bplugins-meta-readonly',
            'dependency' => array('opt-radio', '==', 'wooden'),
            'title'      => 'Background Color', 
            'default'    => '#693328', 
        ),

        array(
            'id'         => 'contentColorEchoXauroraXwooden',
            'type'       => 'color',
            'class'      => 'bplugins-meta-readonly',
            'dependency' => array('opt-radio', 'any', 'echoStream,auroraPlay,wooden'),
            'title'      => 'Content Color', 
            'default'    => '#ffffff', // or false
        ),

        array(
            'id'         => 'station_name_colorEchoXAuroraXwooden',
            'type'       => 'color',
            'class'      => 'bplugins-meta-readonly',
            'dependency' => array('opt-radio', 'any', 'echoStream,auroraPlay,wooden'),
            'title'      => 'Station Name Color', 
            'default'    => 'white', 
        ),
        array(
            'id'         => 'welcome_msg_colorEchoXAurora',
            'type'       => 'color',
            'class'      => 'bplugins-meta-readonly',
            'dependency' => array('opt-radio', 'any', 'echoStream,auroraPlay'),
            'title'      => 'Artist/FM Name Color', 
            'default'    => 'white', 
        ),
        array(
            'id'         => 'play_btn_color',
            'type'       => 'color',
            'class'      => 'bplugins-meta-readonly',
            'dependency' => array('opt-radio', '==', 'echoStream'),
            'title'      => 'Play Button Color', 
            'default'    => 'red', // or false
        ),

        // AuroraPlay
        array(
            'id'         => 'aurora_art_image',
            'library'    => 'image',
            'type'       => 'media',
            'class'      => 'bplugins-meta-readonly',
            'title'      => 'Upload Art Work Image',
            'default'    => array(
                'url'         => 'https://danialsabagh.com/singleaudioplayer/img/radio.jpg',
                'id'          => '',
                "width" => 612,
                "height" => 408,
                "thumbnail" => 'https://danialsabagh.com/singleaudioplayer/img/radio.jpg',
                "alt" => "",
                "title" => "radio-player-image",
                "description" => "",
            ),
            'dependency' => array('opt-radio', '==', 'auroraPlay'),
        ),

        
        // Wooden
        array(
            'id'         => 'station_name_hover_color',
            'type'       => 'color',
            'class'      => 'bplugins-meta-readonly',
            'dependency' => array('opt-radio', '==', 'wooden'),
            'title'      => 'Station Name Hover Color', 
            'default'    => ''
        ),
        array(
            'id'         => 'station_name_bg_color',
            'type'       => 'color',
            'class'      => 'bplugins-meta-readonly',
            'dependency' => array('opt-radio', '==', 'wooden'),
            'title'      => 'Station Name Background Color', 
            'default'    => '#50241b' 
        ),
        array(
            'id'         => 'station_name_bg_hover_color',
            'type'       => 'color',
            'class'      => 'bplugins-meta-readonly',
            'dependency' => array('opt-radio', '==', 'wooden'),
            'title'      => 'Station Name Hover Background Color', 
            'default'    => '' 
        ),

        // TimeStamp
        array(
            'id'         => 'timeStamp_color',
            'type'       => 'color',
            'class'      => 'bplugins-meta-readonly',
            'dependency' => array('opt-radio', '==', 'wooden'),
            'title'      => 'Timestamp Color', 
            'default'    => '#fff' 
        ),
        array(
            'id'         => 'timeStamp_hover_color',
            'type'       => 'color',
            'class'      => 'bplugins-meta-readonly',
            'dependency' => array('opt-radio', '==', 'wooden'),
            'title'      => 'Timestamp Hover Color', 
            'default'    => '' 
        ),
        array(
            'id'         => 'timeStamp_bg_color',
            'type'       => 'color',
            'class'      => 'bplugins-meta-readonly',
            'dependency' => array('opt-radio', '==', 'wooden'),
            'title'      => 'Timestamp Background Color', 
            'default'    => '#50241b' 
        ),
        array(
            'id'         => 'timeStamp_bg_hover_color',
            'type'       => 'color',
            'class'      => 'bplugins-meta-readonly',
            'dependency' => array('opt-radio', '==', 'wooden'),
            'title'      => 'Timestamp Hover Background Color', 
            'default'    => '' 
        ),


            array(
                'id'       => 'custom_css',
                'type'     => 'code_editor',
                'title'    => 'Custom CSS',
                'desc'     => 'This field is optional. ',
                'default'  => '/* Your Custom CSS here	  */',
                'class' => 'bplugins-meta-readonly',
                'sanitize' => false,
                'settings' => array(
                    'mode' => 'css',
                ),

            ),
        )
    ));
}


function streamcast_exclude_fields_before_save($data)
{

    $exclude = array(
        'player_skin',
        'autoplay',
        'volume',
        'artwork',
        'timeholder',
        'background',
        'player_postiion',
        'custom_css',
    );

    foreach ($exclude as $id) {
        unset($data[$id]);
    }

    return $data;
}

add_filter('csf_sc__save', 'streamcast_exclude_fields_before_save', 10, 1);
