<?php

// =====================
// === Stream Player ===
// ------- Blocks ------
// =====================
// @since 2.5.0

if ( !defined( 'ABSPATH' ) ) exit;

// === Block Editor Support ===
// - Get Block Callbacks
// - Get Block Attributes
// - Register Blocks
// - Enqueue Block Editor Assets
// - Enqueue Frontend Block Styles


// ----------------------------
// === Block Editor Support ===
// ----------------------------

// -------------------
// Get Block Callbacks
// -------------------
function stream_player_get_block_callbacks() {

	// --- set block names and related callbacks ---
	// 2.5.12: fix to mismatched stream player shortcode function name
	$callbacks = array( 'player' => 'stream_player_block_shortcode' );

	// --- filter and return ---
	$callbacks = apply_filters( 'stream_player_block_callbacks', $callbacks );
	return $callbacks;
}

// --------------------
// Get Block Attributes
// --------------------
function stream_player_get_block_attributes() {

	// --- set block names and related attributes ---
	$attributes = array(
		'player' => array(
			// --- Player Content ---
			'url' => array( 'type' => 'string', 'default' => '' ),
			'title' => array( 'type' => 'string', 'default' => '' ),
			'image' => array( 'type' => 'string', 'default' => 'default' ),
			// ---- Player Options ---
			'script' => array( 'type' => 'string', 'default' => 'default' ),
			'volume' => array( 'type' => 'number', 'default' => 77 ),
			// 2.5.14: added missing volumes attribute
			'volumes' => array( 'type' => 'array', 'default' => array( 'slider' ) ),
			'default' => array( 'type' => 'boolean', 'default' => false ),
			// --- Player Styles ---
			'layout' => array( 'type' => 'string', 'default' => 'horizontal' ),
			'theme' => array( 'type' => 'string', 'default' => 'default' ),
			'buttons' => array( 'type' => 'string', 'default' => 'default' ),
			'channel' => array( 'type' => 'number', 'default' => 0 ),
		),
		// 'jukebox' => ...
	);

	// --- add default switches to each block ---
	foreach ( $attributes as $block_slug => $attribute_list ) {
		$attribute_list['block'] = array( 'type' => 'boolean', 'default' => true );
		$attribute_list['pro'] = array( 'type' => 'boolean', 'default' => false );
		$attributes[$block_slug] = $attribute_list;
	}

	// --- filter and return ---
	$attributes = apply_filters( 'stream_player_block_attributes', $attributes );
	return $attributes;
}

// ---------------
// Register Blocks
// ---------------
add_action( 'init', 'stream_player_register_blocks' );
function stream_player_register_blocks() {

	// --- get block callbacks and attributes ---
	$callbacks = stream_player_get_block_callbacks();
	$attributes = stream_player_get_block_attributes();

	// --- loop block names to register blocks ---
	foreach ( $callbacks as $block_slug => $callback ) {
		// 2.5.14: sync block prefix with radio station plugin
		// (to allow for changing plugins without affecting blocks)
		$block_key = 'radio-station/' . $block_slug;
		$args = array(
			'render_callback' => $callback,
			'attributes'      => $attributes[$block_slug],
			'category'        => 'media',
		);
		$args = apply_filters( 'stream_player_block_args', $args, $block_slug, $callback );
		register_block_type( $block_key, $args );
	}
}

// ---------------------------
// Enqueue Block Editor Assets
// ---------------------------
add_action( 'enqueue_block_editor_assets', 'stream_player_block_editor_assets' );
function stream_player_block_editor_assets() {

	// --- get block callbacks ---
	$callbacks = stream_player_get_block_callbacks();

	// --- set block dependencies ---
	// 2.9.0: wp-server-side-render became its own package in WP 6.3.
	$deps = array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-components', 'wp-editor', 'wp-server-side-render' );

	// --- resolve plugin root reliably ---
	// STREAM_PLAYER_FILE / STREAM_PLAYER_DIR may not be defined yet when this
	// callback fires depending on load order. Use dirname(__DIR__) as a safe
	// fallback since this file is always at <plugin-root>/includes/blocks.php.
	$plugin_dir  = defined( 'STREAM_PLAYER_DIR' )  ? STREAM_PLAYER_DIR  : dirname( __DIR__ );
	$plugin_file = defined( 'STREAM_PLAYER_FILE' ) ? STREAM_PLAYER_FILE : $plugin_dir . '/stream-player.php';

	// --- set base block URL and path ---
	$blocks_url  = plugins_url( '/blocks/', $plugin_file );
	$blocks_path = $plugin_dir . '/blocks/';

	// --- loop callbacks to enqueue scripts ---
	$block_scripts = array();
	foreach ( $callbacks as $block_slug => $callback ) {
		$block_path = $blocks_path . $block_slug . '.js';
		if ( file_exists( $block_path ) ) {

			// --- set script data ---
			$block_scripts[$block_slug] = array(
				'slug'      => $block_slug,
				'handle'    => $block_slug . '-js',
				'url'       => $blocks_url . $block_slug . '.js',
				'version'   => filemtime( $block_path ),
				'deps'      => $deps,
			);

		}
	}

	// --- filter scripts and loop to enqueue ---
	$block_scripts = apply_filters( 'stream_player_block_scripts', $block_scripts );
	foreach ( $block_scripts as $script ) {
		wp_enqueue_script( $script['handle'], $script['url'], $script['deps'], $script['version'], true );
	}

	// --- block editor support for conditional loading ---
	$script_url  = plugins_url( '/blocks/editor.js', $plugin_file );
	$script_path = $plugin_dir . '/blocks/editor.js';
	$version     = file_exists( $script_path ) ? filemtime( $script_path ) : false;
	wp_enqueue_script( 'stream-blockedit-js', $script_url, $deps, $version, true );

	// 2.5.0: added for script loading
	// 2.9.0: use $plugin_file (resolved above) so the URL is correct even when
	//        STREAM_PLAYER_FILE is not yet defined.
	$js = "var stream_player_script = '" . esc_js( plugins_url( '/player/js/radio-player.js', $plugin_file ) ) . "';";
	// 2.5.12: add stream player settings
	$js .= stream_player_get_player_settings();
	wp_add_inline_script( 'stream-blockedit-js', $js, 'before' );

	// --- add block control style fix inline ---
	// - fix cutoff label widths -
	$css = '.components-panel .components-panel__body.stream-block-controls .components-panel__row label {
	width: 100%; max-width: 100%; min-width: 150px; overflow: visible;}' . "\n";
	$css .= '.components-panel .components-panel__body.stream-block-controls .components-panel__row label.components-toggle-control__label {max-width: unset;}' . "\n";
	// - multiple select minimum height fix -
	// ref: https://github.com/WordPress/gutenberg/issues/27166
	$css .= '.components-panel .components-panel__body.stream-block-controls .components-select-control__input[multiple] {min-height: 100px;}';
	$css = apply_filters( 'stream_player_block_control_styles', $css );
	wp_add_inline_style( 'wp-edit-blocks', $css );

	// --- enqueue radio player styles ---
	if ( array_key_exists( 'player', $callbacks ) ) {
		$suffix     = ''; // dev temp
		$style_path = $plugin_dir . '/player/css/radio-player' . $suffix . '.css';
		$style_url  = plugins_url( '/player/css/radio-player' . $suffix . '.css', $plugin_file );
		$version    = file_exists( $style_path ) ? filemtime( $style_path ) : false;
		wp_enqueue_style( 'radio-player', $style_url, array(), $version, 'all' );

		// --- enqueue player control styles inline ---
		$control_styles = stream_player_control_styles( false );
		wp_add_inline_style( 'radio-player', $control_styles );
	}
}

// ----------------------
// AJAX Load Block Script
// ----------------------
// add_action( 'wp_ajax_stream_player_block_script', 'stream_player_block_script' );
/*
function stream_player_block_script() {

	if ( !isset( $_REQUEST['handle'] ) ) {
		exit;
	}

	$js = '';
	$handle = sanitize_text_field( $_REQUEST['handle'] );
	if ( 'player' == $handle ) {
		$js .= file_get_contents( STREAM_PLAYER_DIR . '/player/js/radio-player.js' );
	}

	// --- filter javascript ---
	$js = apply_filters( 'stream_player_block_script', $js, $handle );

	// --- output javascript ---
	header( 'Content-Type: application/javascript' );
	if ( '' != $js ) {
		echo $js;
	}
	exit;
}
*/
