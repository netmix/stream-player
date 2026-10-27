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
			// 2.7.1: fix to use existing stream tab (no general tab)
			'tab'     => 'stream',
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
			// 2.7.1: fix to use existing stream tab (no general tab)
			'tab'     => 'stream',
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
			'default' => array( 'amplitude', 'howler', 'jplayer' ),
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
			'tab'     => 'player',
			'section' => 'basic',
		),

		// --- [Player] Player Buttons ---
		'player_buttons' => array(
			'type'    => 'select',
			'label'   => $admin ? __( 'Default Player Buttons', 'stream-player' ) : '',
			'default' => 'rounded',
			'options' => array(
				'circular' => $admin ? __( 'Circular Buttons', 'stream-player' ) : '',
				'rounded'  => $admin ? __( 'Rounded Buttons', 'stream-player' ) : '',
				'square'   => $admin ? __( 'Square Buttons', 'stream-player' ) : '',
			),
			'helper'  => $admin ? __( 'Default Player Buttons shape style.', 'stream-player' ) : '',
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
			'default' => 'rgba(0,0,0,255)',
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
			'display'     => $admin ? __( 'Bar Display', 'stream-player' ) : '',
			'persistent'  => $admin ? __( 'Persistent Playback', 'stream-player' ) : '',
			'metadata'    => $admin ? __( 'Metadata Display', 'stream-player' ) : '',
		),
	);

	return $options;
}
