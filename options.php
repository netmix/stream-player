<?php

// -----------------------------
// === Stream Player Options ===
// -----------------------------

if ( !defined( 'ABSPATH' ) ) exit;

// ------------------
// Set Plugin Options
// ------------------
$options = array(

	// --------------
	// --- Stream ---
	// --------------

	// === Source ===

	// --- [Player] Streaming URL ---
	'streaming_url' => array(
		'type'    => 'text',
		'options' => 'URL',
		'label'   => __( 'Streaming URL', 'stream-player' ),
		'default' => '',
		'helper'  => __( 'Enter the Streaming URL for your Stream.', 'stream-player' ),
		'tab'     => 'stream',
		'section' => 'source',
	),

	// --- [Player] Stream Format ---
	'streaming_format' => array(
		'type'    => 'select',
		'options' => $formats,
		'label'   => __( 'Streaming Format', 'stream-player' ),
		'default' => 'aac',
		'helper'  => __( 'Select streaming format for streaming URL.', 'stream-player' ),
		'tab'     => 'stream',
		'section' => 'source',
	),

	// --- [Player] Fallback Stream URL ---
	'fallback_url' => array(
		'type'    => 'text',
		'options' => 'URL',
		'label'   => __( 'Fallback Stream URL', 'stream-player' ),
		'default' => '',
		'helper'  => __( 'Enter an alternative Streaming URL for Player fallback.', 'stream-player' ),
		'tab'     => 'stream',
		'section' => 'source',
	),

	// --- [Player] Fallback Stream Format ---
	'fallback_format' => array(
		'type'    => 'select',
		'options' => $formats,
		'label'   => __( 'Fallback Format', 'stream-player' ),
		'default' => 'ogg',
		'helper'  => __( 'Select streaming fallback for fallback URL.', 'stream-player' ),
		'tab'     => 'stream',
		'section' => 'source',
	),

	// === Channel ===

	// --- [Player] Station Title ---
	'station_title' => array(
		'type'    => 'text',
		'label'   => __( 'Stream Title', 'stream-player' ),
		'default' => '',
		'helper'  => __( 'The name of your Stream.', 'stream-player' ),
		'tab'     => 'stream',
		'section' => 'channel',
	),

	// --- [Player] Player Title ---
	'player_title' => array(
		'type'    => 'checkbox',
		'label'   => __( 'Display Stream Title', 'stream-player' ),
		'default' => 'yes',
		'value'   => 'yes',
		'helper'  => __( 'Display your Stream Title in Player by default.', 'stream-player' ),
		'tab'     => 'stream',
		'section' => 'channel',
	),

	// --- [Player] Stream Image ---
	'station_image' => array(
		'type'    => 'image',
		'label'   => __( 'Stream Image', 'stream-player' ),
		'default' => '',
		'helper'  => __( 'Add a logo image for your Stream. Please ensure image is square before uploading. Recommended size 256 x 256', 'stream-player' ),
		'tab'     => 'stream',
		'section' => 'channel',
	),

	// --- [Player] Display Stream Image ---
	'player_image' => array(
		'type'    => 'checkbox',
		'label'   => __( 'Display Stream Image', 'stream-player' ),
		'default' => 'yes',
		'value'   => 'yes',
		'helper'  => __( 'Display your Stream Image in Player by default.', 'stream-player' ),
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
		'label'   => __( 'Player Defaults Note', 'stream-player' ),
		'helper'  => __( 'Note that you can override these defaults in specific Player Widgets.', 'stream-player' ),
		'tab'     => 'player',
		'section' => 'basic',
	),

	// --- [Player] Player Script ---
	'player_script'       => array(
		'type'    => 'select',
		'label'   => __( 'Player Script', 'stream-player' ),
		'default' => 'jplayer',
		'options' => array(
			'jplayer'   => __( 'jPlayer', 'stream-player' ),
			'howler'    => __( 'Howler', 'stream-player' ),
			'amplitude' => __( 'Amplitude', 'stream-player' ),
		),
		'helper'  => __( 'Default audio script to use for playback in the Player.', 'stream-player' ),
		'tab'     => 'player',
		'section' => 'basic',
	),

	// --- [Player] Fallback Scripts ---
	'player_fallbacks' => array(
		'type'    => 'multicheck',
		'label'   => __( 'Fallback Scripts', 'stream-player' ),
		'default' => array( 'amplitude', 'howler', 'jplayer' ),
		'options' => array(
			'jplayer'   => __( 'jPlayer', 'stream-player' ),
			'amplitude' => __( 'Amplitude', 'stream-player' ),
			// 'howler'    => __( 'Howler', 'stream-player' ),
		),
		'helper'  => __( 'Enabled fallback audio scripts to try when the default Player script fails.', 'stream-player' ),
		'tab'     => 'player',
		'section' => 'basic',
	),

	// --- [Player] Player Theme ---
	'player_theme' => array(
		'type'    => 'select',
		'label'   => __( 'Default Player Theme', 'stream-player' ),
		'default' => 'light',
		'options' => array(
			'light' => __( 'Light', 'stream-player' ),
			'dark'  => __( 'Dark', 'stream-player' ),
		),
		'helper'  => __( 'Default Player Controls theme style.', 'stream-player' ),
		'tab'     => 'player',
		'section' => 'basic',
	),

	// --- [Player] Player Buttons ---
	'player_buttons' => array(
		'type'    => 'select',
		'label'   => __( 'Default Player Buttons', 'stream-player' ),
		'default' => 'rounded',
		'options' => array(
			'circular' => __( 'Circular Buttons', 'stream-player' ),
			'rounded'  => __( 'Rounded Buttons', 'stream-player' ),
			'square'   => __( 'Square Buttons', 'stream-player' ),
		),
		'helper'  => __( 'Default Player Buttons shape style.', 'stream-player' ),
		'tab'     => 'player',
		'section' => 'basic',
	),

	// --- [Player] Volume Controls  ---
	'player_volumes' => array(
		'type'    => 'multicheck',
		'label'   => __( 'Volume Controls', 'stream-player' ),
		'default' => array( 'slider', 'updown', 'mute', 'max' ),
		'options' => array(
			'slider'   => __( 'Volume Slider', 'stream-player' ),
			'updown'   => __( 'Volume Plus / Minus', 'stream-player' ),
			'mute'     => __( 'Mute Volume Toggle', 'stream-player' ),
			'max'      => __( 'Maximize Volume', 'stream-player' ),
		),
		'helper'  => __( 'Which volume controls to display in the Player by default.', 'stream-player' ),
		'tab'     => 'player',
		'section' => 'basic',
	),

	// --- [Player] Player Debug Mode ---
	'player_debug' => array(
		'type'    => 'checkbox',
		'label'   => __( 'Player Debug Mode', 'stream-player' ),
		'default' => '',
		'value'   => 'yes',
		'helper'  => __( 'Output player debug information in browser javascript console.', 'stream-player' ),
		'tab'     => 'player',
		'section' => 'basic',
		'pro'     => false,
	),

	// === Player Colors ===

	// --- [Pro/Player] Playing Highlight Color ---
	'player_playing_color' => array(
		'type'    => 'color',
		'label'   => __( 'Playing Icon Highlight Color', 'stream-player' ),
		'default' => '#70E070',
		'helper'  => __( 'Default highlight color to use for Play button icon when playing.', 'stream-player' ),
		'tab'     => 'player',
		'section' => 'colors',
		'pro'     => true,
	),

	// --- [Pro/Player] Control Icons Highlight Color ---
	'player_buttons_color' => array(
		'type'    => 'color',
		'label'   => __( 'Control Icons Highlight Color', 'stream-player' ),
		'default' => '#00A0E0',
		'helper'  => __( 'Default highlight color to use for Control button icons when active.', 'stream-player' ),
		'tab'     => 'player',
		'section' => 'colors',
		'pro'     => true,
	),

	// --- [Pro/Player] Volume Knob Color ---
	'player_thumb_color' => array(
		'type'    => 'color',
		'label'   => __( 'Volume Knob Color', 'stream-player' ),
		'default' => '#80C080',
		'helper'  => __( 'Default Knob Color for Player Volume Slider.', 'stream-player' ),
		'tab'     => 'player',
		'section' => 'colors',
		'pro'     => true,
	),

	// --- [Pro/Player] Volume Track Color ---
	'player_range_color' => array(
		'type'    => 'coloralpha',
		'label'   => __( 'Volume Track Color', 'stream-player' ),
		'default' => '#80C080',
		'helper'  => __( 'Default Track Color for Player Volume Slider.', 'stream-player' ),
		'tab'     => 'player',
		'section' => 'colors',
		'pro'     => true,
	),

	// === Advanced Defaults ===

	// --- [Player] Player Volume ---
	'player_volume' => array(
		'type'    => 'number',
		'label'   => __( 'Player Start Volume', 'stream-player' ),
		'default' => 77,
		'min'     => 0,
		'step'    => 1,
		'max'     => 100,
		'helper'  => __( 'Initial volume for when the Player starts playback.', 'stream-player' ),
		'tab'     => 'player',
		'section' => 'advanced',
	),

	// --- [Player] Single Player ---
	'player_single' => array(
		'type'    => 'checkbox',
		'label'   => __( 'Single Player at Once', 'stream-player' ),
		'default' => 'yes',
		'value'   => 'yes',
		'helper'  => __( 'Stop any existing Players on the page or in other windows or tabs when a Player is started.', 'stream-player' ),
		'tab'     => 'player',
		'section' => 'advanced',
	),

	// --- [Pro/Player] Player Autoresume ---
	'player_autoresume' => array(
		'type'    => 'checkbox',
		'label'   => __( 'Autoresume Playback', 'stream-player' ),
		'default' => 'yes',
		'value'   => 'yes',
		'helper'  => __( 'Attempt to resume playback if visitor was playing. Only triggered when the user first interacts with the page.', 'stream-player' ),
		'tab'     => 'player',
		'section' => 'advanced',
		'pro'     => true,
	),

	// --- [Pro/Player] Popup Player Button ---
	// 2.5.0: enabled popup player button
	'player_popup' => array(
		'type'    => 'checkbox',
		'label'   => __( 'Popup Player Button', 'stream-player' ),
		'default' => '',
		'value'   => 'yes',
		'helper'  => __( 'Add button to open Popup Player in separate window.', 'stream-player' ),
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
		'label'   => __( 'Bar Defaults Note', 'stream-player' ),
		'helper'  => __( 'The Bar Player uses the default configurations set above.', 'stream-player' )
					. ' ' . __( 'You can override these in specific Player Widgets.', 'stream-player' ),
		'tab'     => 'bar',
		'section' => 'display',
	),

	// --- [Pro/Player] Sitewide Player Bar ---
	'player_bar' => array(
		'type'    => 'select',
		'label'   => __( 'Sitewide Player Bar', 'stream-player' ),
		'default' => 'off',
		'options' => array(
			'off'    => __( 'No Player Bar', 'stream-player' ),
			'top'    => __( 'Top Player Bar', 'stream-player' ),
			'bottom' => __( 'Bottom Player Bar', 'stream-player' ),
		),
		'tab'     => 'bar',
		'section' => 'display',
		'helper'  => __( 'Add a fixed position Player Bar which displays Sitewide.', 'stream-player' ),
		'pro'     => true,
	),

	// --- [Pro/Player] Player Bar Height ---
	'player_bar_height' => array(
		'type'    => 'number',
		'min'     => 40,
		'max'     => 400,
		'step'    => 1,
		'label'   => __( 'Player Bar Height', 'stream-player' ),
		'default' => 80,
		'tab'     => 'bar',
		'section' => 'display',
		'helper'  => __( 'Set the height of the Sitewide Player Bar in pixels.', 'stream-player' ),
		'pro'     => true,
	),

	// --- [Pro/Player] Fade In Player Bar ---
	'player_bar_fadein' => array(
		'type'    => 'number',
		'label'   => __( 'Fade In Player Bar', 'stream-player' ),
		'default' => 2500,
		'min'     => 0,
		'step'    => 100,
		'max'     => 10000,
		'helper'  => __( 'Number of milliseconds after Page load over which to fade in Player Bar. Use 0 for instant display.', 'stream-player' ),
		'tab'     => 'bar',
		'section' => 'display',
		'pro'     => true,
	),

	// --- [Pro/Player] Bar Player Text Color ---
	'player_bar_text' => array(
		'type'    => 'color',
		'label'   => __( 'Bar Player Text Color', 'stream-player' ),
		'default' => '#FFFFFF',
		'helper'  => __( 'Text color for the fixed position Sitewide Bar Player.', 'stream-player' ),
		'tab'     => 'bar',
		'section' => 'display',
		'pro'     => true,
	),

	// --- [Pro/Player] Bar Player Background Color ---
	'player_bar_background' => array(
		'type'    => 'coloralpha',
		'label'   => __( 'Bar Player Background Color', 'stream-player' ),
		'default' => 'rgba(0,0,0,255)',
		'helper'  => __( 'Background color for the fixed position Sitewide Bar Player.', 'stream-player' ),
		'tab'     => 'bar',
		'section' => 'display',
		'pro'     => true,
	),

	// === Persistent Player ===

	// --- [Pro/Player] Continuous Playback ---
	'player_bar_continuous' => array(
		'type'    => 'checkbox',
		'label'   => __( 'Continuous Playback', 'stream-player' ),
		'default' => 'yes',
		'value'   => 'yes',
		'helper'  => __( 'Uninterrupted Sitewide Bar playback while user is navigating between pages! Pages are loaded in background and faded in while Player Bar persists.', 'stream-player' ),
		'tab'     => 'bar',
		'section' => 'persistent',
		'pro'     => true,
	),

	// --- [Pro/Player] Player Page Fade ---
	'player_bar_pagefade' => array(
		'type'    => 'number',
		'label'   => __( 'Page Fade Time', 'stream-player' ),
		'default' => 2000,
		'min'     => 0,
		'step'    => 100,
		'max'     => 10000,
		'helper'  => __( 'Number of milliseconds over which to fade in new Pages (when continuous playback is enabled.) Use 0 for instant display.', 'stream-player' ),
		'tab'     => 'bar',
		'section' => 'persistent',
		'pro'     => true,
	),

	// --- [Pro/Player] Page Load Timeout ---
	'player_bar_timeout' => array(
		'type'    => 'number',
		'label'   => __( 'Page Load Timeout', 'stream-player' ),
		'default' => 7000,
		'min'     => 0,
		'step'    => 500,
		'max'     => 20000,
		'helper'  => __( 'Number of milliseconds to wait for new Page to load before fading in anyway (when continuous playback is enabled.)', 'stream-player' ),
		'tab'     => 'bar',
		'section' => 'persistent',
		'pro'     => true,
	),

	// === Metadata ===

	// --- [Pro/Player] Display Metadata ---
	'player_bar_nowplaying' => array(
		'type'    => 'checkbox',
		'label'   => __( 'Display Now Playing', 'stream-player' ),
		'value'   => 'yes',
		'default' => 'yes',
		'tab'     => 'bar',
		'section' => 'metadata',
		'helper'  => __( 'Display the currently playing song in the Player Bar, if a supported metadata format is available. (Icy Meta, Icecast, Shoutcast 1/2, Current Playlist)', 'stream-player' ),
		'pro'     => true,
	),

	// --- [Pro/Player] Metadata URL ---
	'player_bar_metadata' => array(
		'type'    => 'text',
		'options' => 'URL',
		'label'   => __( 'Metadata URL', 'stream-player' ),
		'default' => '',
		'tab'     => 'bar',
		'section' => 'metadata',
		'helper'  => __( 'Now playing metadata is normally retrieved via the Stream URL. Use this setting if you need to provide an alternative metadata location.', 'stream-player' ),
		'pro'     => true,
	),

	// --- [Pro/Player] Track Animation ---
	'player_bar_track_animation' => array(
		'type'    => 'select',
		'label'   => __( 'Track Animation', 'stream-player' ),
		'default' => 'backandforth',
		'options' => array(
			'none'         => __( 'No Animation', 'stream-player' ),
			'lefttoright'  => __( 'Left to Right Ticker', 'stream-player' ),
			'righttoleft'  => __( 'Right to Left Ticker', 'stream-player' ),
			'backandforth' => __( 'Back and Forth', 'stream-player' ),
		),
		'tab'     => 'player',
		'section' => 'bar',
		'helper'  => __( 'How to animate the currently playing track display.', 'stream-player' ),
		'pro'     => true,
	),

	// === Tabs and Sections ===

	// --- Tab Labels ---
	'tabs' => array(
		'stream'    => __( 'Stream', 'stream-player' ),
		'player'    => __( 'Player', 'stream-player' ),
		'bar'       => __( 'Bar', 'stream-player' ),
	),

	// --- Section Labels ---
	'sections' => array(
		'source'      => __( 'Source', 'stream-player' ),
		'channel'     => __( 'Channel', 'stream-player' ),
		'basic'       => __( 'Basic Defaults', 'stream-player' ),
		'colors'      => __( 'Player Colors', 'stream-player' ),
		'advanced'    => __( 'Advanced Defaults', 'stream-player' ),
		'display'     => __( 'Bar Display', 'stream-player' ),
		'persistent'  => __( 'Persistent Playback', 'stream-player' ),
		'metadata'    => __( 'Metadata Display', 'stream-player' ),
	),
);
