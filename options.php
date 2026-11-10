<?php

// -----------------------------
// === Stream Player Options ===
// -----------------------------

if ( !defined( 'ABSPATH' ) ) exit;

// ------------------
// Set Plugin Options
// ------------------
function stream_player_get_plugin_options( $admin = false ) {

	$formats = stream_player_get_stream_formats();

	// 2.7.1: check pro defines for player preview URLs (ported from Radio Station)
	if ( defined( 'STREAM_PLAYER_PRO_FILE' ) ) {
		$pro_file = STREAM_PLAYER_PRO_FILE;
	} elseif ( defined( 'RADIO_STATION_PRO_FILE' ) ) {
		$pro_file = RADIO_STATION_PRO_FILE;
	} else {
		$pro_file = STREAM_PLAYER_FILE;
	}

	// 2.7.1: set player preview HTML
	$player_preview_html = $admin ? '<div class="play-pause-button preview-image"></div><div class="volume-controls"><div class="preview-button volume-button mute-button"></div><div class="volume-button minus-button"></div><div class="volume-slider-wrapper"><div class="volume-slider-bg"></div><input type="range" class="volume-slider" max="100" min="0" value="%%player_volume%%"><div class="volume-thumb"></div></div><div class="volume-button plus-button"></div><div class="preview-button volume-button max-button"></div></div></div>' : '';

	// 2.7.1: set player preview CSS
	$player_preview_css = $admin ? '/* Play/Pause Button */
	%%target%% .play-pause-button {display: inline-block; vertical-align: middle; width: 64px; height: 64px; background-size: 100% 100%; cursor: pointer;}
	%%target%%.light .play-pause-button, %%target%%.dark .play-pause-button {background-image: url("' . esc_url( plugins_url( '/player/images/%%player_theme%%-play-%%player_buttons%%.png', STREAM_PLAYER_FILE ) ) . '");}
	%%target%%.light .play-pause-button.active, %%target%%.dark .play-pause-button.active {background-image: url("' . esc_url( plugins_url( '/player/images/%%player_theme%%-pause-%%player_buttons%%.png', STREAM_PLAYER_FILE ) ) . '");}
	%%target%% .play-pause-button {background-image: url("' . esc_url( plugins_url( '/images/%%player_theme%%-play-%%player_buttons%%.png', $pro_file ) ) . '");}
	%%target%% .play-pause-button.active {background-image: url("' . esc_url( plugins_url( '/images/%%player_theme%%-pause-%%player_buttons%%.png', $pro_file ) ) . '");}
	/* Volume Controls */
	%%target%% .volume-controls {display: inline-block; vertical-align: middle; margin-left: 40px;}
	%%target%% .volume-button {width: 18px; height: 18px; padding: 0; margin: 0; background-size: 36px; background-repeat: no-repeat; background-image: url("' . esc_url( plugins_url( '/images/volume-controls-%%player_theme%%.png', $pro_file ) ) . '");}
	%%target%%.solid .volume-button, %%target%%.semisolid .volume-button {background-image: url("' . esc_url( plugins_url( '/images/volume-controls-%%player_theme%%-solid.png', $pro_file ) ) . '");}
	%%target%%.light .volume-button, %%target%%.dark .volume-button {background-image: url("' . esc_url( plugins_url( '/player/images/volume-controls-%%player_theme%%.png', STREAM_PLAYER_FILE ) ) . '");}
	%%target%%.light.solid .volume-button, %%target%%.dark.solid .volume-button, %%target%%.light.semisolid .volume-button, %%target%%.dark.semisolid .volume-button {background-image: url("' . esc_url( plugins_url( '/player/images/volume-controls-%%player_theme%%-solid.png', STREAM_PLAYER_FILE ) ) . '");}
	%%target%% .volume-button:hover {opacity: 0.99; scale: 1.1;}
	%%target%% .mute-button {display: none;} %%target%%.mute .mute-button {display: inline-block; vertical-align: middle; background-position: 0 0;}
	%%target%%.mute .mute-button.active, %%target%%.mute .mute-button:hover {background-position: -18px -18px;}
	%%target%% .minus-button {display: none;} %%target%%.updown .minus-button {display: inline-block; vertical-align: middle; background-position: 0 -72px;}
	%%target%%.updown .minus-button:hover {background-position: -18px -72px;}
	%%target%% .volume-slider-bg {display: none;} %%target%%.slider .volume-slider-bg {display: inline-block; vertical-align: middle; }
	%%target%% .plus-button {display: none;} %%target%%.updown .plus-button {display: inline-block; vertical-align: middle; background-position: 0 -54px;}
	%%target%%.updown .plus-button:hover {background-position: -18px -54px;}
	%%target%% .max-button {display: none;} %%target%%.max .max-button {display: inline-block; vertical-align: middle; background-position: 0 -36px;}
	%%target%%.max .max-button.active, %%target%%.max .max-button:hover {background-position: -18px -36px;}
	/* Volume Slider */
	%%target%% .volume-slider-wrapper {position: relative; height: 30px; opacity: 0.85; display: none; vertical-align: middle; width: 150px;}
	%%target%%.slider .volume-slider-wrapper {display: inline-block;}
	%%target%% .volume-slider-bg {width: calc((%%player_volume%% / 100) * 130px);}
	%%target%% .volume-slider-wrapper:hover {opacity: 0.99;}
	%%target%% .volume-slider {width: 100%;}
	%%target%% .volume-thumb {display: none; width: 18px;}
	/* Range Input */
	%%target%% input[type=range] {height: 100%; margin: 0; background-color: transparent; vertical-align: middle; -webkit-appearance: none; border: none;}
	%%target%% input[type=range]:focus {outline: none; box-shadow: none;}
	%%target%% input[type=range]::-moz-focus-inner, %%target%% .volume-controls input[type=range]::-moz-focus-outer {outline: none; box-shadow: none;}
	/* Range Thumb */
	%%target%% input[type=range]::-webkit-slider-thumb {width: 18px; height: 18px; cursor: pointer; border: 1px solid rgba(128, 128, 128, 0.5); border-radius: 9px; z-index: 2; -webkit-appearance: none; background: %%player_thumb_color%%;}
	%%target%% input[type=range]::-moz-range-thumb {width: 18px; height: 18px; cursor: pointer; border: 1px solid rgba(128, 128, 128, 0.5); border-radius: 9px; z-index: 2; background: %%player_thumb_color%%;}
	%%target%%.rounded input[type=range]::-webkit-slider-thumb {border-radius: 5px !important;}
	%%target%%.square input[type=range]::-webkit-slider-thumb {border-radius: 0px !important;}
	/* Range Track */
	%%target%% .volume-slider-bg {position: absolute; top: 10px; bottom: 10px; overflow: hidden; height: 10px; margin-left: 9px; z-index: 1; border: 1px solid rgba(128, 128, 128, 0.5); border-radius: 3px; background: %%player_range_color%%;}
	%%target%% input[type=range]::-webkit-slider-runnable-track {height: 9px; background: transparent; -webkit-appearance: none; color: transparent}
	%%target%% input[type=range]::-moz-range-track {height: 9px; background: transparent; color: transparent;}
	' : '';

	$options = array(

		// --------------
		// --- Stream ---
		// --------------

		// === Source ===

		// --- [Player] Streaming URL ---
		'streaming_url' => array(
			'type'    => 'text',
			'options' => 'URL',
			'label'   => $admin ? __( 'Streaming URL', 'stream-player' ) : '',
			'default' => '',
			'helper'  => $admin ? __( 'Enter the Streaming URL for your Stream.', 'stream-player' ) : '',
			'tab'     => 'stream',
			'section' => 'source',
		),

		// --- [Player] Stream Format ---
		'streaming_format' => array(
			'type'    => 'select',
			'options' => $formats,
			'label'   => $admin ? __( 'Streaming Format', 'stream-player' ) : '',
			'default' => 'aac',
			'helper'  => $admin ? __( 'Select streaming format for streaming URL.', 'stream-player' ) : '',
			'tab'     => 'stream',
			'section' => 'source',
		),

		// --- [Player] Fallback Stream URL ---
		'fallback_url' => array(
			'type'    => 'text',
			'options' => 'URL',
			'label'   => $admin ? __( 'Fallback Stream URL', 'stream-player' ) : '',
			'default' => '',
			'helper'  => $admin ? __( 'Enter an alternative Streaming URL for Player fallback.', 'stream-player' ) : '',
			'tab'     => 'stream',
			'section' => 'source',
		),

		// --- [Player] Fallback Stream Format ---
		'fallback_format' => array(
			'type'    => 'select',
			'options' => $formats,
			'label'   => $admin ? __( 'Fallback Format', 'stream-player' ) : '',
			'default' => 'ogg',
			'helper'  => $admin ? __( 'Select streaming fallback for fallback URL.', 'stream-player' ) : '',
			'tab'     => 'stream',
			'section' => 'source',
		),

		// --- [Player] Stream GeoBlocking ---
		'stream_geo_blocking' => array(
			'label'		=> $admin ? __( 'GeoIP Stream Blocking', 'radio-station' ) : '',
			'type'		=> 'select',
			'options' => array(
				''			=> $admin ? __( 'No GeoIP Blocking', 'radio-station' ) : '',
				'live365'	=> $admin ? __( 'Live365 (only US, UK, Canada, Mexico)', 'radio-station' ) : '',
				// 'blacklist' => $admin ? __( 'Custom Country Blacklist', 'radio-station' ) : '',
				// 'whitelist' => $admin ? __( 'Custom Country Whitelist', 'radio-station' ) : '',
			),
			'default' => '',
			'helper'  => $admin ? __( 'Block streaming according to country, detected by user IP address.', 'radio-station' ) : '',
			'tab'     => 'stream',
			'section' => 'source',
			'pro'     => true,
		),

		// --- [Pro/Stream] StreamGuys Rewind ---
		// 2.7.1: added for parity with Radio Station (Pro)
		'stream_sg_rewind' => array(
			'label'   => $admin ? __( 'SG Rewind', 'stream-player' ) : '',
			'type'    => 'checkbox',
			'value'   => 'yes',
			'default' => '',
			'helper'  => $admin ? __( 'Enable stream support for StreamGuys Rewind service.', 'stream-player' ) : '',
			'tab'     => 'stream',
			'section' => 'source',
			'pro'     => true,
		),

		// --- [Pro/Stream] StreamGuys Endpoint ---
		// 2.7.1: added for parity with Radio Station (Pro)
		'stream_sg_endpoint' => array(
			'label'   => $admin ? __( 'Rewind Endpoint', 'stream-player' ) : '',
			'type'    => 'text',
			'default' => '',
			'helper'  => $admin ? __( 'Endpoint URL for StreamGuys Rewind service.', 'stream-player' ) : '',
			'tab'     => 'stream',
			'section' => 'source',
			'pro'     => true,
		),

		// === Channel ===

		// --- [Player] Station Title ---
		'station_title' => array(
			'type'    => 'text',
			'label'   => $admin ? __( 'Stream Title', 'stream-player' ) : '',
			'default' => '',
			'helper'  => $admin ? __( 'The name of your Stream.', 'stream-player' ) : '',
			'tab'     => 'stream',
			'section' => 'channel',
		),

		// --- [Player] Player Title ---
		'player_title' => array(
			'type'    => 'checkbox',
			'label'   => $admin ? __( 'Display Stream Title', 'stream-player' ) : '',
			'default' => 'yes',
			'value'   => 'yes',
			'helper'  => $admin ? __( 'Display Stream Title in Player by default.', 'stream-player' ) : '',
			'tab'     => 'stream',
			'section' => 'channel',
		),

		// --- [Player] Stream Tagline ---
		// 2.5.18: added stream tagline
		'station_tagline' => array(
			'type'    => 'text',
			'label'   => $admin ? __( 'Stream Tagline', 'stream-player' ) : '',
			'default' => '',
			'helper'  => $admin ? __( 'Tagline for your Stream.', 'stream-player' ) : '',
			'tab'     => 'general',
			'section' => 'channel',
		),

		// --- [Player] Display Tagline ---
		// 2.5.18: added stream tagline display
		'player_meta' => array(
			'type'    => 'multicheck',
			'label'   => $admin ? __( 'Player Meta Display', 'stream-player' ) : '',
			'options' => array(
				'tagline'   => $admin ? __( 'Tagline', 'radio-station' ) : '',
				// 'frequency' => $admin ? __( 'Frequency', 'radio-station' ) : '',
				'location'  => $admin ? __( 'Location', 'radio-station' ) : '',
				'timezone'  => $admin ? __( 'Timezone', 'radio-station' ) : '',
			),
			'default' => array( 'tagline' ),
			'helper'  => $admin ? __( 'Stream Meta to display in Player by default.', 'radio-station' ) : '',
			'tab'     => 'general',
			'section' => 'channel',
		),

		// --- [Player] Stream Image ---
		'station_image' => array(
			'type'    => 'image',
			'label'   => $admin ? __( 'Stream Image', 'stream-player' ) : '',
			'default' => '',
			'helper'  => $admin ? __( 'Add a logo image for your Stream. Please ensure image is square before uploading. Recommended size 256 x 256', 'stream-player' ) : '',
			'tab'     => 'stream',
			'section' => 'channel',
		),

		// --- [Player] Display Stream Image ---
		'player_image' => array(
			'type'    => 'checkbox',
			'label'   => $admin ? __( 'Display Stream Image', 'stream-player' ) : '',
			'default' => 'yes',
			'value'   => 'yes',
			'helper'  => $admin ? __( 'Display your Stream Image in Player by default.', 'stream-player' ) : '',
			'tab'     => 'stream',
			'section' => 'channel',
		),

		// --------------
		// --- Player ---
		// --------------

		// === Basic Defaults ===

		// --- Defaults Note ---
		// 2.5.0: added note about defaults being overrideable in widgets
		'player_defaults_note' => array(
			'type'    => 'note',
			'label'   => $admin ? __( 'Player Defaults Note', 'stream-player' ) : '',
			'helper'  => $admin ? __( 'Note that you can override these defaults in specific Player Widgets.', 'stream-player' ) : '',
			'tab'     => 'player',
			'section' => 'basic',
		),

		// --- [Player] Preview Display ---
		// 2.7.1: added player preview (ported from Radio Station)
		'player_preview' => array(
			'type'    => 'preview',
			'label'   => $admin ? __( 'Player Preview', 'stream-player' ) : '',
			'html'    => $player_preview_html,
			'css'     => $player_preview_css,
			'classes' => '%%player_theme%%,%%player_buttons%%,%%player_volumes%%',
			'tab'     => 'player',
			'section' => 'basic',
		),

		// --- [Player] Player Script ---
		// 2.5.18: disabled howler script
		'player_script'       => array(
			'type'    => 'select',
			'label'   => $admin ? __( 'Player Script', 'stream-player' ) : '',
			'default' => 'jplayer',
			'options' => array(
				'amplitude' => __( 'Amplitude', 'stream-player' ),
				'jplayer'   => $admin ? __( 'jPlayer', 'stream-player' ) : '',
				// 'howler'    => $admin ? __( 'Howler', 'stream-player' ) : '',
			),
			'helper'  => $admin ? __( 'Default audio script to use for playback in the Player.', 'stream-player' ) : '',
			'tab'     => 'player',
			'section' => 'basic',
		),

		// --- [Player] Fallback Scripts ---
		'player_fallbacks' => array(
			'type'    => 'multicheck',
			'label'   => $admin ? __( 'Fallback Scripts', 'stream-player' ) : '',
			// 2.7.1: fix to remove disabled howler script from default
			'default' => array( 'amplitude', 'jplayer' ),
			'options' => array(
				'jplayer'   => $admin ? __( 'jPlayer', 'stream-player' ) : '',
				'amplitude' => $admin ? __( 'Amplitude', 'stream-player' ) : '',
				// 'howler'    => $admin ? __( 'Howler', 'stream-player' ) : '',
			),
			'helper'  => $admin ? __( 'Enabled fallback audio scripts to try when the default Player script fails.', 'stream-player' ) : '',
			'tab'     => 'player',
			'section' => 'basic',
		),

		// --- [Player] Player Theme ---
		'player_theme' => array(
			'type'    => 'select',
			'label'   => $admin ? __( 'Default Player Theme', 'stream-player' ) : '',
			'default' => 'light',
			'options' => array(
				'light' => $admin ? __( 'Light', 'stream-player' ) : '',
				'dark'  => $admin ? __( 'Dark', 'stream-player' ) : '',
			),
			'helper'  => $admin ? __( 'Default Player Controls theme style.', 'stream-player' ) : '',
			// 2.7.1: added play/pause image preview
			'preview' => array(
				'type'     => 'image',
				'width'    => 32,
				'height'   => 32,
				'sources'  => array(
					'light' => plugins_url( 'player/images/light-play-%%player_buttons%%.png', STREAM_PLAYER_FILE ),
					'dark'  => plugins_url( 'player/images/dark-play-%%player_buttons%%.png', STREAM_PLAYER_FILE ),
				),
				'sources-alt' => array(
					'light' => plugins_url( 'player/images/light-pause-%%player_buttons%%.png', STREAM_PLAYER_FILE ),
					'dark'  => plugins_url( 'player/images/dark-pause-%%player_buttons%%.png', STREAM_PLAYER_FILE ),
				),
				'css'      => '#preview-player_preview .play-pause-button {background-image: url("%%images_url%%/%%player_theme%%-play-%%player_buttons%%.png") !important;}
				#preview-player_preview .play-pause-button.active, #preview-player_preview .play-pause-button:hover {background-image: url("%%images_url%%/%%player_theme%%-pause-%%player_buttons%%.png") !important;}
				#preview-player_theme {background-image: url("%%images_url%%/%%player_theme%%-play-%%player_buttons%%.png") !important;}
				#preview-player_theme.active, #preview-player_theme:hover {background-image: url("%%images_url%%/%%player_theme%%-pause-%%player_buttons%%.png") !important;}',
				'settings' => 'player_theme,player_buttons',
			),
			'tab'     => 'player',
			'section' => 'basic',
		),

		// --- [Player] Player Buttons ---
		'player_buttons' => array(
			'type'    => 'select',
			'label'   => $admin ? __( 'Default Player Buttons', 'stream-player' ) : '',
			'default' => 'rounded',
			// 2.7.1: added solid button image options (matching Radio Station)
			'options' => array(
				'solid'     => $admin ? __( 'Solid Buttons (No Outline)', 'stream-player' ) : '',
				'semisolid' => $admin ? __( 'Solid Buttons (Transparent Outline)', 'stream-player' ) : '',
				'circular'  => $admin ? __( 'Hollow Circular Buttons', 'stream-player' ) : '',
				'rounded'   => $admin ? __( 'Hollow Rounded Buttons', 'stream-player' ) : '',
				'square'    => $admin ? __( 'Hollow Square Buttons', 'stream-player' ) : '',
			),
			'helper'  => $admin ? __( 'Default Player Buttons shape style.', 'stream-player' ) : '',
			'preview' => array(
				'linked' => 'player_theme',
			),
			'tab'     => 'player',
			'section' => 'basic',
		),

		// --- [Player] Volume Controls  ---
		'player_volumes' => array(
			'type'    => 'multicheck',
			'label'   => $admin ? __( 'Volume Controls', 'stream-player' ) : '',
			'default' => array( 'slider', 'updown', 'mute', 'max' ),
			'options' => array(
				'slider'   => $admin ? __( 'Volume Slider', 'stream-player' ) : '',
				'updown'   => $admin ? __( 'Volume Plus / Minus', 'stream-player' ) : '',
				'mute'     => $admin ? __( 'Mute Volume Toggle', 'stream-player' ) : '',
				'max'      => $admin ? __( 'Maximize Volume', 'stream-player' ) : '',
			),
			'helper'  => $admin ? __( 'Which volume controls to display in the Player by default.', 'stream-player' ) : '',
			'tab'     => 'player',
			'section' => 'basic',
		),

		// --- [Player] Player Debug Mode ---
		'player_debug' => array(
			'type'    => 'checkbox',
			'label'   => $admin ? __( 'Player Debug Mode', 'stream-player' ) : '',
			'default' => '',
			'value'   => 'yes',
			'helper'  => $admin ? __( 'Output player debug information in browser javascript console.', 'stream-player' ) : '',
			'tab'     => 'player',
			'section' => 'basic',
			'pro'     => false,
		),

		// === Player Colors ===

		// --- [Pro/Player] Playing Highlight Color ---
		'player_playing_color' => array(
			'type'    => 'color',
			'label'   => $admin ? __( 'Playing Icon Highlight Color', 'stream-player' ) : '',
			'default' => '#70E070',
			'helper'  => $admin ? __( 'Default highlight color to use for Play button icon when playing.', 'stream-player' ) : '',
			'tab'     => 'player',
			'section' => 'colors',
			'pro'     => true,
		),

		// --- [Pro/Player] Control Icons Highlight Color ---
		'player_buttons_color' => array(
			'type'    => 'color',
			'label'   => $admin ? __( 'Control Icons Highlight Color', 'stream-player' ) : '',
			'default' => '#00A0E0',
			'helper'  => $admin ? __( 'Default highlight color to use for Control button icons when active.', 'stream-player' ) : '',
			'tab'     => 'player',
			'section' => 'colors',
			'pro'     => true,
		),

		// --- [Pro/Player] Volume Knob Color ---
		'player_thumb_color' => array(
			'type'    => 'color',
			'label'   => $admin ? __( 'Volume Knob Color', 'stream-player' ) : '',
			'default' => '#80C080',
			'helper'  => $admin ? __( 'Default Knob Color for Player Volume Slider.', 'stream-player' ) : '',
			'preview' => array(
				'css' => '#preview-player_preview .volume-slider-wrapper input[type=range]::-webkit-slider-thumb {background: %%player_thumb_color%% !important;}
				#preview-player_preview .volume-slider-wrapper input[type=range]::-moz-range-thumb {background: %%player_thumb_color%% !important;}',
			),
			'tab'     => 'player',
			'section' => 'colors',
			'pro'     => true,
		),

		// --- [Pro/Player] Volume Track Color ---
		'player_range_color' => array(
			'type'    => 'coloralpha',
			'label'   => $admin ? __( 'Volume Track Color', 'stream-player' ) : '',
			'default' => '#80C080',
			'helper'  => $admin ? __( 'Default Track Color for Player Volume Slider.', 'stream-player' ) : '',
			'preview' => array(
				'css' => '#preview-player_preview .volume-slider-bg {background: %%player_range_color%% !important;}',
			),
			'tab'     => 'player',
			'section' => 'colors',
			'pro'     => true,
		),

		// === Advanced Defaults ===

		// --- [Player] Player Volume ---
		'player_volume' => array(
			'type'    => 'number',
			'label'   => $admin ? __( 'Player Start Volume', 'stream-player' ) : '',
			'default' => 77,
			'min'     => 0,
			'step'    => 1,
			'max'     => 100,
			'helper'  => $admin ? __( 'Initial volume for when the Player starts playback.', 'stream-player' ) : '',
			'tab'     => 'player',
			'section' => 'advanced',
		),

		// --- [Player] Single Player ---
		'player_single' => array(
			'type'    => 'checkbox',
			'label'   => $admin ? __( 'Single Player at Once', 'stream-player' ) : '',
			'default' => 'yes',
			'value'   => 'yes',
			'helper'  => $admin ? __( 'Stop any existing Players on the page or in other windows or tabs when a Player is started.', 'stream-player' ) : '',
			'tab'     => 'player',
			'section' => 'advanced',
		),

		// --- [Pro/Player] Player Autoresume ---
		// 2.5.15: change autoresume value to require manual activation
		// 2.5.16: updated helper text
		'player_autoresume' => array(
			'type'    => 'checkbox',
			'label'   => $admin ? __( 'Autoresume Playback', 'stream-player' ) : '',
			'default' => '',
			'value'   => 'on',
			'helper'  => $admin ? __( 'On return to site or page reload, ask the user to resume stream playback if they were playing the stream previously, using a popup a modal dialogue box.', 'stream-player' ) : '',
			'tab'     => 'player',
			'section' => 'advanced',
			'pro'     => true,
		),

		// --- [Pro/Player] Popup Player Button ---
		// 2.5.0: enabled popup player button
		'player_popup' => array(
			'type'    => 'checkbox',
			'label'   => $admin ? __( 'Popup Player Button', 'stream-player' ) : '',
			'default' => '',
			'value'   => 'yes',
			'helper'  => $admin ? __( 'Add button to open Popup Player in separate window.', 'stream-player' ) : '',
			'tab'     => 'player',
			'section' => 'advanced',
			'pro'     => true,
		),


		// === Widget Player Settings ===

		// --- [Pro/Widget] Spectrum Analyzer ---
		// 2.7.1: added spectrum analyzer settings (ported from Radio Station)
		'player_widget_analyzer' => array(
			'type'    => 'checkbox',
			'label'   => $admin ? __( 'Spectrum Analyzer', 'stream-player' ) : '',
			'value'   => 'yes',
			'default' => '',
			'helper'  => $admin ? __( 'Show a real-time audio spectrum visualization in the Stream Player Widget. Requires the streaming server to send CORS headers -- if the analyzer stays flat once enabled, check with your streaming host.', 'stream-player' ) : '',
			'tab'     => 'player',
			'section' => 'widget_player',
			'pro'     => true,
		),

		// --- [Pro/Widget] Analyzer Placement ---
		'player_widget_analyzer_placement' => array(
			'type'    => 'select',
			'label'   => $admin ? __( 'Analyzer Placement', 'stream-player' ) : '',
			'options' => array(
				'top'        => $admin ? __( 'Top (Above Widget)', 'stream-player' ) : '',
				'background' => $admin ? __( 'Background (Behind Widget Content)', 'stream-player' ) : '',
			),
			'default' => 'top',
			'helper'  => $admin ? __( 'Whether the analyzer sits as a strip above the widget (pushing the rest of the page down, never overlapping) or fills the widget as a background behind its controls.', 'stream-player' ) : '',
			'tab'     => 'player',
			'section' => 'widget_player',
			'pro'     => true,
		),

		// --- [Pro/Widget] Analyzer Gradient ---
		'player_widget_analyzer_gradient' => array(
			'type'    => 'select',
			'label'   => $admin ? __( 'Analyzer Color Theme', 'stream-player' ) : '',
			'options' => array(
				'classic'   => $admin ? __( 'Classic', 'stream-player' ) : '',
				'orangered' => $admin ? __( 'Orange Red', 'stream-player' ) : '',
				'prism'     => $admin ? __( 'Prism', 'stream-player' ) : '',
				'rainbow'   => $admin ? __( 'Rainbow', 'stream-player' ) : '',
				'steelblue' => $admin ? __( 'Steel Blue', 'stream-player' ) : '',
				// 2.7.0: custom colors option
				'custom'    => $admin ? __( 'Custom Colors', 'stream-player' ) : '',
			),
			'default' => 'classic',
			'tab'     => 'player',
			'section' => 'widget_player',
			'pro'     => true,
		),

		// --- [Pro/Widget] Analyzer Custom Top Color ---
		'player_widget_analyzer_color_top' => array(
			'type'    => 'color',
			'label'   => $admin ? __( 'Analyzer Top Color', 'stream-player' ) : '',
			'default' => '#ff0000',
			'helper'  => $admin ? __( 'Used when the Analyzer Color Theme is set to Custom Colors.', 'stream-player' ) : '',
			'tab'     => 'player',
			'section' => 'widget_player',
			'pro'     => true,
		),

		// --- [Pro/Widget] Analyzer Custom Middle Color ---
		'player_widget_analyzer_color_middle' => array(
			'type'    => 'color',
			'label'   => $admin ? __( 'Analyzer Middle Color', 'stream-player' ) : '',
			'default' => '#ffff00',
			'helper'  => $admin ? __( 'Used when the Analyzer Color Theme is set to Custom Colors.', 'stream-player' ) : '',
			'tab'     => 'player',
			'section' => 'widget_player',
			'pro'     => true,
		),

		// --- [Pro/Widget] Analyzer Custom Bottom Color ---
		'player_widget_analyzer_color_bottom' => array(
			'type'    => 'color',
			'label'   => $admin ? __( 'Analyzer Bottom Color', 'stream-player' ) : '',
			'default' => '#00ff00',
			'helper'  => $admin ? __( 'Used when the Analyzer Color Theme is set to Custom Colors.', 'stream-player' ) : '',
			'tab'     => 'player',
			'section' => 'widget_player',
			'pro'     => true,
		),

		// --- [Pro/Widget] Analyzer Style ---
		'player_widget_analyzer_style' => array(
			'type'    => 'select',
			'label'   => $admin ? __( 'Analyzer Style', 'stream-player' ) : '',
			'options' => array(
				'bars'     => $admin ? __( 'Bars', 'stream-player' ) : '',
				'finebars' => $admin ? __( 'Fine Bars', 'stream-player' ) : '',
				'graph'    => $admin ? __( 'Line Graph', 'stream-player' ) : '',
			),
			'default' => 'bars',
			'tab'     => 'player',
			'section' => 'widget_player',
			'pro'     => true,
		),

		// --- [Pro/Widget] Analyzer Sensitivity ---
		'player_widget_analyzer_sensitivity' => array(
			'type'    => 'select',
			'label'   => $admin ? __( 'Analyzer Sensitivity', 'stream-player' ) : '',
			'options' => array(
				'low'    => $admin ? __( 'Low', 'stream-player' ) : '',
				'medium' => $admin ? __( 'Medium', 'stream-player' ) : '',
				'high'   => $admin ? __( 'High', 'stream-player' ) : '',
			),
			'default' => 'medium',
			'helper'  => $admin ? __( 'Higher sensitivity reacts more to quieter passages, but can look noisier on a loud/compressed stream.', 'stream-player' ) : '',
			'tab'     => 'player',
			'section' => 'widget_player',
			'pro'     => true,
		),

		// --- [Pro/Widget] Analyzer on Mobile ---
		'player_widget_analyzer_mobile' => array(
			'type'    => 'checkbox',
			'label'   => $admin ? __( 'Enable Analyzer on Mobile', 'stream-player' ) : '',
			'value'   => 'yes',
			'default' => '',
			'helper'  => $admin ? __( 'Off by default -- canvas rendering has a real battery/performance cost on phones. Enable once you\'ve confirmed it performs acceptably on your target devices.', 'stream-player' ) : '',
			'tab'     => 'player',
			'section' => 'widget_player',
			'pro'     => true,
		),

		// --- [Pro/Widget] Analyzer Opacity ---
		'player_widget_analyzer_opacity' => array(
			'type'    => 'range',
			'label'   => $admin ? __( 'Analyzer Opacity', 'stream-player' ) : '',
			'min'     => 10,
			'max'     => 100,
			'step'    => 5,
			'default' => 100,
			'suffix'  => '%',
			'helper'  => $admin ? __( 'How visible the analyzer is. Lower values blend it more subtly behind the widget\'s content.', 'stream-player' ) : '',
			'tab'     => 'player',
			'section' => 'widget_player',
			'pro'     => true,
		),

		// --- [Pro/Widget] Analyzer Height ---
		'player_widget_analyzer_height' => array(
			'type'    => 'range',
			'label'   => $admin ? __( 'Analyzer Height', 'stream-player' ) : '',
			'min'     => 20,
			'max'     => 100,
			'step'    => 5,
			'default' => 100,
			'suffix'  => '%',
			'helper'  => $admin ? __( 'Scales the analyzer relative to its full size (100% = full height) -- for Background placement it shrinks up from the bottom of the widget, for the Top strip it shrinks down toward the widget.', 'stream-player' ) : '',
			'tab'     => 'player',
			'section' => 'widget_player',
			'pro'     => true,
		),

		// ------------------
		// --- Player Bar ---
		// ------------------

		// === Display ===

		// --- Player Bar Note ---
		'player_bar_note' => array(
			'type'    => 'note',
			'label'   => $admin ? __( 'Bar Defaults Note', 'stream-player' ) : '',
			'helper'  => $admin ? __( 'The Bar Player uses the default configurations set above.', 'stream-player' )
						. ' ' . __( 'You can override these in specific Player Widgets.', 'stream-player' ) : '',
			'tab'     => 'bar',
			'section' => 'display',
		),

		// --- [Pro/Player] Sitewide Player Bar ---
		'player_bar' => array(
			'type'    => 'select',
			'label'   => $admin ? __( 'Sitewide Player Bar', 'stream-player' ) : '',
			'default' => 'off',
			'options' => array(
				'off'    => $admin ? __( 'No Player Bar', 'stream-player' ) : '',
				'top'    => $admin ? __( 'Top Player Bar', 'stream-player' ) : '',
				'bottom' => $admin ? __( 'Bottom Player Bar', 'stream-player' ) : '',
			),
			'tab'     => 'bar',
			'section' => 'display',
			'helper'  => $admin ? __( 'Add a fixed position Player Bar which displays Sitewide.', 'stream-player' ) : '',
			'pro'     => true,
		),

		// --- [Pro/Player] Player Bar Height ---
		'player_bar_height' => array(
			'type'    => 'number',
			'min'     => 40,
			'max'     => 400,
			'step'    => 1,
			'label'   => $admin ? __( 'Player Bar Height', 'stream-player' ) : '',
			'default' => 80,
			'tab'     => 'bar',
			'section' => 'display',
			'helper'  => $admin ? __( 'Set the height of the Sitewide Player Bar in pixels.', 'stream-player' ) : '',
			'pro'     => true,
		),

		// --- [Pro/Player] Responsive Mode ---
		// 2.5.18: added option for responsive expander bar
		'player_bar_responsive' => array(
			'type'    => 'select',
			'label'   => $admin ? __( 'Bar Responsive Mode', 'radio-station' ) : '',
			'options' => array(
				'left-right' => $admin ? __( 'Horizontal Arrows', 'radio-station' ) : '',
				'expander'   => $admin ? __( 'Vertical Expander', 'radio-station' ) : '',
			),
			'default' => 'left-right',
			'tab'     => 'bar',
			'section' => 'display',
			'helper'  => $admin ? __( 'Player Bar responsive mode for mobile overflow sections.', 'radio-station' ) : '',
			'pro'     => true,
		),

		// --- [Pro/Player] Fade In Player Bar ---
		'player_bar_fadein' => array(
			'type'    => 'number',
			'label'   => $admin ? __( 'Fade In Player Bar', 'stream-player' ) : '',
			'default' => 2500,
			'min'     => 0,
			'step'    => 100,
			'max'     => 10000,
			'helper'  => $admin ? __( 'Number of milliseconds after Page load over which to fade in Player Bar. Use 0 for instant display.', 'stream-player' ) : '',
			'tab'     => 'bar',
			'section' => 'display',
			'pro'     => true,
		),

		// --- [Pro/Player] Bar Player Text Color ---
		'player_bar_text' => array(
			'type'    => 'color',
			'label'   => $admin ? __( 'Bar Player Text Color', 'stream-player' ) : '',
			'default' => '#FFFFFF',
			'helper'  => $admin ? __( 'Text color for the fixed position Sitewide Bar Player.', 'stream-player' ) : '',
			'tab'     => 'bar',
			'section' => 'display',
			'pro'     => true,
		),

		// --- [Pro/Player] Bar Player Background Color ---
		'player_bar_background' => array(
			'type'    => 'coloralpha',
			'label'   => $admin ? __( 'Bar Player Background Color', 'stream-player' ) : '',
			// 2.7.1: fix alpha value in default (range is 0 to 1)
			'default' => 'rgba(0,0,0,1)',
			'helper'  => $admin ? __( 'Background color for the fixed position Sitewide Bar Player.', 'stream-player' ) : '',
			'tab'     => 'bar',
			'section' => 'display',
			'pro'     => true,
		),

		// === Persistent Player ===

		// --- [Pro/Player] Continuous Playback ---
		'player_bar_continuous' => array(
			'type'    => 'checkbox',
			'label'   => $admin ? __( 'Continuous Playback', 'stream-player' ) : '',
			'default' => 'yes',
			'value'   => 'yes',
			'helper'  => $admin ? __( 'Uninterrupted Sitewide Bar playback while user is navigating between pages! Pages are loaded in background and faded in while Player Bar persists.', 'stream-player' ) : '',
			'tab'     => 'bar',
			'section' => 'persistent',
			'pro'     => true,
		),

		// --- [Pro/Player] Player Page Fade ---
		'player_bar_pagefade' => array(
			'type'    => 'number',
			'label'   => $admin ? __( 'Page Fade Time', 'stream-player' ) : '',
			'default' => 2000,
			'min'     => 0,
			'step'    => 100,
			'max'     => 10000,
			'helper'  => $admin ? __( 'Number of milliseconds over which to fade in new Pages (when continuous playback is enabled.) Use 0 for instant display.', 'stream-player' ) : '',
			'tab'     => 'bar',
			'section' => 'persistent',
			'pro'     => true,
		),

		// --- [Pro/Player] Page Load Timeout ---
		'player_bar_timeout' => array(
			'type'    => 'number',
			'label'   => $admin ? __( 'Page Load Timeout', 'stream-player' ) : '',
			'default' => 7000,
			'min'     => 0,
			'step'    => 500,
			'max'     => 20000,
			'helper'  => $admin ? __( 'Number of milliseconds to wait for new Page to load before fading in anyway (when continuous playback is enabled.)', 'stream-player' ) : '',
			'tab'     => 'bar',
			'section' => 'persistent',
			'pro'     => true,
		),

		// --- [Pro/Player] Page Load Prompt ---
		// 2.7.1: added load fail prompt (ported from Radio Station)
		'player_bar_prompt' => array(
			'type'    => 'select',
			'label'   => $admin ? __( 'Load Fail Prompt', 'stream-player' ) : '',
			'default' => '404',
			'options' => array(
				''     => $admin ? __( 'Off', 'stream-player' ) : '',
				'yes'  => $admin ? __( 'Prompt on Timeout only', 'stream-player' ) : '',
				'404'  => $admin ? __( 'Prompt on 404 Not Found only', 'stream-player' ) : '',
				'404t' => $admin ? __( 'Prompt on 404 or Timeout', 'stream-player' ) : '',
				'all'  => $admin ? __( 'Prompt on Timeout or any Error', 'stream-player' ) : '',
			),
			'helper'  => $admin ? __( 'Whether to show a user prompt on page timeout and/or when there is an error (if continuous playback is enabled.)', 'stream-player' ) : '',
			'tab'     => 'bar',
			'section' => 'persistent',
			'pro'     => true,
		),

		// === Metadata ===

		// --- [Pro/Player] Display Metadata ---
		'player_bar_nowplaying' => array(
			'type'    => 'checkbox',
			'label'   => $admin ? __( 'Display Now Playing', 'stream-player' ) : '',
			'value'   => 'yes',
			'default' => 'yes',
			'tab'     => 'bar',
			'section' => 'metadata',
			'helper'  => $admin ? __( 'Display the currently playing song in the Player Bar, if a supported metadata format is available. (Icy Meta, Icecast, Shoutcast 1/2, Current Playlist)', 'stream-player' ) : '',
			'pro'     => true,
		),

		// --- [Pro/Player] Metadata URL ---
		'player_bar_metadata' => array(
			'type'    => 'text',
			'options' => 'URL',
			'label'   => $admin ? __( 'Metadata URL', 'stream-player' ) : '',
			'default' => '',
			'tab'     => 'bar',
			'section' => 'metadata',
			'helper'  => $admin ? __( 'Now playing metadata is normally retrieved via the Stream URL. Use this setting if you need to provide an alternative metadata location.', 'stream-player' ) : '',
			'pro'     => true,
		),

		// --- [Pro/Player] Track Animation ---
		'player_bar_track_animation' => array(
			'type'    => 'select',
			'label'   => $admin ? __( 'Track Animation', 'stream-player' ) : '',
			'default' => 'backandforth',
			'options' => array(
				'none'         => $admin ? __( 'No Animation', 'stream-player' ) : '',
				'lefttoright'  => $admin ? __( 'Left to Right Ticker', 'stream-player' ) : '',
				'righttoleft'  => $admin ? __( 'Right to Left Ticker', 'stream-player' ) : '',
				'backandforth' => $admin ? __( 'Back and Forth', 'stream-player' ) : '',
			),
			'tab'     => 'player',
			'section' => 'bar',
			'helper'  => $admin ? __( 'How to animate the currently playing track display.', 'stream-player' ) : '',
			'pro'     => true,
		),

		// === Spectrum Analyzer ===

		// --- [Pro/Bar] Spectrum Analyzer ---
		// 2.7.1: added spectrum analyzer settings (ported from Radio Station)
		'player_bar_analyzer' => array(
			'type'    => 'checkbox',
			'label'   => $admin ? __( 'Spectrum Analyzer', 'stream-player' ) : '',
			'value'   => 'yes',
			'default' => '',
			'helper'  => $admin ? __( 'Show a real-time audio spectrum visualization in the Player Bar. Requires the streaming server to send CORS headers -- if the analyzer stays flat once enabled, check with your streaming host.', 'stream-player' ) : '',
			'tab'     => 'bar',
			'section' => 'analyzer',
			'pro'     => true,
		),

		// --- [Pro/Bar] Analyzer Placement ---
		'player_bar_analyzer_placement' => array(
			'type'    => 'select',
			'label'   => $admin ? __( 'Analyzer Placement', 'stream-player' ) : '',
			'options' => array(
				'attached'   => $admin ? __( 'Attached to Bar Edge', 'stream-player' ) : '',
				'background' => $admin ? __( 'Background (Behind Bar Content)', 'stream-player' ) : '',
			),
			'default' => 'attached',
			'helper'  => $admin ? __( 'Whether the analyzer sits as a strip along the page edge of the Player Bar (below a top bar, above a bottom bar), or fills the bar as a background behind the text and controls.', 'stream-player' ) : '',
			'tab'     => 'bar',
			'section' => 'analyzer',
			'pro'     => true,
		),

		// --- [Pro/Bar] Analyzer Gradient ---
		'player_bar_analyzer_gradient' => array(
			'type'    => 'select',
			'label'   => $admin ? __( 'Analyzer Color Theme', 'stream-player' ) : '',
			'options' => array(
				'classic'   => $admin ? __( 'Classic', 'stream-player' ) : '',
				'orangered' => $admin ? __( 'Orange Red', 'stream-player' ) : '',
				'prism'     => $admin ? __( 'Prism', 'stream-player' ) : '',
				'rainbow'   => $admin ? __( 'Rainbow', 'stream-player' ) : '',
				'steelblue' => $admin ? __( 'Steel Blue', 'stream-player' ) : '',
				// 2.7.0: custom colors option
				'custom'    => $admin ? __( 'Custom Colors', 'stream-player' ) : '',
			),
			'default' => 'classic',
			'tab'     => 'bar',
			'section' => 'analyzer',
			'pro'     => true,
		),

		// --- [Pro/Player] Analyzer Custom Top Color ---
		'player_bar_analyzer_color_top' => array(
			'type'    => 'color',
			'label'   => $admin ? __( 'Analyzer Top Color', 'stream-player' ) : '',
			'default' => '#ff0000',
			'helper'  => $admin ? __( 'Used when the Analyzer Color Theme is set to Custom Colors.', 'stream-player' ) : '',
			'tab'     => 'player',
			'section' => 'bar',
			'pro'     => true,
		),

		// --- [Pro/Player] Analyzer Custom Middle Color ---
		'player_bar_analyzer_color_middle' => array(
			'type'    => 'color',
			'label'   => $admin ? __( 'Analyzer Middle Color', 'stream-player' ) : '',
			'default' => '#ffff00',
			'helper'  => $admin ? __( 'Used when the Analyzer Color Theme is set to Custom Colors.', 'stream-player' ) : '',
			'tab'     => 'player',
			'section' => 'bar',
			'pro'     => true,
		),

		// --- [Pro/Player] Analyzer Custom Bottom Color ---
		'player_bar_analyzer_color_bottom' => array(
			'type'    => 'color',
			'label'   => $admin ? __( 'Analyzer Bottom Color', 'stream-player' ) : '',
			'default' => '#00ff00',
			'helper'  => $admin ? __( 'Used when the Analyzer Color Theme is set to Custom Colors.', 'stream-player' ) : '',
			'tab'     => 'player',
			'section' => 'bar',
			'pro'     => true,
		),

		// --- [Pro/Bar] Analyzer Style ---
		'player_bar_analyzer_style' => array(
			'type'    => 'select',
			'label'   => $admin ? __( 'Analyzer Style', 'stream-player' ) : '',
			'options' => array(
				'bars'     => $admin ? __( 'Bars', 'stream-player' ) : '',
				'finebars' => $admin ? __( 'Fine Bars', 'stream-player' ) : '',
				'graph'    => $admin ? __( 'Line Graph', 'stream-player' ) : '',
			),
			'default' => 'bars',
			'tab'     => 'bar',
			'section' => 'analyzer',
			'pro'     => true,
		),

		// --- [Pro/Bar] Analyzer Sensitivity ---
		'player_bar_analyzer_sensitivity' => array(
			'type'    => 'select',
			'label'   => $admin ? __( 'Analyzer Sensitivity', 'stream-player' ) : '',
			'options' => array(
				'low'    => $admin ? __( 'Low', 'stream-player' ) : '',
				'medium' => $admin ? __( 'Medium', 'stream-player' ) : '',
				'high'   => $admin ? __( 'High', 'stream-player' ) : '',
			),
			'default' => 'medium',
			'helper'  => $admin ? __( 'Higher sensitivity reacts more to quieter passages, but can look noisier on a loud/compressed stream.', 'stream-player' ) : '',
			'tab'     => 'bar',
			'section' => 'analyzer',
			'pro'     => true,
		),

		// --- [Pro/Bar] Analyzer on Mobile ---
		'player_bar_analyzer_mobile' => array(
			'type'    => 'checkbox',
			'label'   => $admin ? __( 'Enable Analyzer on Mobile', 'stream-player' ) : '',
			'value'   => 'yes',
			'default' => '',
			'helper'  => $admin ? __( 'Off by default -- canvas rendering has a real battery/performance cost on phones. Enable once you\'ve confirmed it performs acceptably on your target devices.', 'stream-player' ) : '',
			'tab'     => 'bar',
			'section' => 'analyzer',
			'pro'     => true,
		),

		// --- [Pro/Bar] Analyzer Opacity ---
		'player_bar_analyzer_opacity' => array(
			'type'    => 'range',
			'label'   => $admin ? __( 'Analyzer Opacity', 'stream-player' ) : '',
			'min'     => 10,
			'max'     => 100,
			'step'    => 5,
			'default' => 100,
			'suffix'  => '%',
			'helper'  => $admin ? __( 'How visible the analyzer is. Lower values blend it more subtly behind the bar\'s text and controls.', 'stream-player' ) : '',
			'tab'     => 'bar',
			'section' => 'analyzer',
			'pro'     => true,
		),

		// --- [Pro/Bar] Analyzer Height ---
		'player_bar_analyzer_height' => array(
			'type'    => 'range',
			'label'   => $admin ? __( 'Analyzer Height', 'stream-player' ) : '',
			'min'     => 20,
			'max'     => 100,
			'step'    => 5,
			'default' => 100,
			'suffix'  => '%',
			'helper'  => $admin ? __( 'Scales the analyzer relative to its full size (100% = full height) -- for Background placement it shrinks up from the bottom of the bar, for the Attached strip it shrinks down toward the bar.', 'stream-player' ) : '',
			'tab'     => 'bar',
			'section' => 'analyzer',
			'pro'     => true,
		),

		// === Tabs and Sections ===

		// --- Tab Labels ---
		'tabs' => array(
			'stream'    => $admin ? __( 'Stream', 'stream-player' ) : '',
			'player'    => $admin ? __( 'Player', 'stream-player' ) : '',
			'bar'       => $admin ? __( 'Bar', 'stream-player' ) : '',
		),

		// --- Section Labels ---
		'sections' => array(
			'source'      => $admin ? __( 'Source', 'stream-player' ) : '',
			'channel'     => $admin ? __( 'Channel', 'stream-player' ) : '',
			'basic'       => $admin ? __( 'Basic Defaults', 'stream-player' ) : '',
			'colors'      => $admin ? __( 'Player Colors', 'stream-player' ) : '',
			'advanced'    => $admin ? __( 'Advanced Defaults', 'stream-player' ) : '',
			// 2.7.1: added widget player and bar spectrum analyzer sections
			'widget_player' => $admin ? __( 'Widget Player Settings', 'stream-player' ) : '',
			'display'     => $admin ? __( 'Bar Display', 'stream-player' ) : '',
			'persistent'  => $admin ? __( 'Persistent Playback', 'stream-player' ) : '',
			'metadata'    => $admin ? __( 'Metadata Display', 'stream-player' ) : '',
			'analyzer'    => $admin ? __( 'Spectrum Analyzer', 'stream-player' ) : '',
		),
	);

	return $options;
}
