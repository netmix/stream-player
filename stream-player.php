<?php

/*

Plugin Name: Stream Player
Plugin URI: https://radiostation.pro/stream-player/
Description: Adds an advanced Streaming Audio Player your site.
Author: Tony Hayes, Tony Zeoli
Version: 2.5.5.2
Requires at least: 4.0.0
Text Domain: stream-player
Domain Path: /languages
Author URI: https://netmix.com/
GitHub Plugin URI: netmix/stream-player

*/

// === Setup ===
// - Define Plugin Constants
// - Set Debug Mode Constant
// - Define Plugin Data Slugs
// - Include Plugin Files
// - Plugin Options and Defaults
// - Plugin Loader Settings
// - Bundle Plan Settings Filter
// - Start Plugin Loader Instance
// - Include Plugin Admin Files
// - Load Plugin Text Domain
// - Filter Freemius Plugin Icon Path
// - Add Pricing Page Path Filter
// - Pricing Page Path Filter
// === Helper Functions ===
// - Stream Player Shortcode
// - Check Plan Options
// - Get Stream Formats
// - Get Streaming URL
// - Get Allowed HTML
// - Filter Anchor Tag Allowed HTML


// -------------
// === Setup ===
// -------------

// -----------------------
// Define Plugin Constants
// -----------------------
// TODO: set accurate documentation URL constants
define( 'STREAM_PLAYER_SLUG', 'stream-player' );
define( 'STREAM_PLAYER_FILE', __FILE__ );
define( 'STREAM_PLAYER_DIR', dirname( __FILE__ ) );
define( 'STREAM_PLAYER_BASENAME', plugin_basename( __FILE__ ) );
define( 'STREAM_PLAYER_HOME_URL', 'https://radiostation.pro/stream-player/' );
define( 'STREAM_PLAYER_DOCS_URL', 'https://radiostation.pro/docs/player/' );
define( 'STREAM_PLAYER_PRO_URL', 'https://radiostation.pro/stream-player-pro/' );

// -----------------------
// Set Debug Mode Constant
// -----------------------
if ( !defined( 'STREAM_PLAYER_DEBUG' ) ) {
	$sp_debug = ( isset( $_REQUEST['sp-debug'] ) && ( '1' == sanitize_text_field( $_REQUEST['sp-debug'] ) ) ) ? true : false;
	define( 'STREAM_PLAYER_DEBUG', $sp_debug );
}

// --------------------
// Include Plugin Files
// --------------------

// --- Admin ---
if ( is_admin() ) {
	include STREAM_PLAYER_DIR . '/stream-player-admin.php';
}

// --- Player ---
require STREAM_PLAYER_DIR . '/player/radio-player.php';

// --- Widgets ---
require STREAM_PLAYER_DIR . '/widgets/class-stream-player-widget.php';

// --- Blocks ---
if ( function_exists( 'register_block_type' ) ) {
	include STREAM_PLAYER_DIR . '/includes/blocks.php';
}


// ---------------------------
// Plugin Options and Defaults
// ---------------------------
$formats = stream_player_get_stream_formats();
$plan_options = stream_player_check_plan_options();
require STREAM_PLAYER_DIR . '/options.php';

// ----------------------
// Plugin Loader Settings
// ----------------------

// --- settings array ---
$settings = array(

	// --- Plugin Info ---
	'slug'         => STREAM_PLAYER_SLUG,
	'file'         => STREAM_PLAYER_FILE,
	'version'      => '0.0.1',

	// --- Menus and Links ---
	'title'        => __( 'Stream Player', 'stream-player' ),
	'parentmenu'   => STREAM_PLAYER_SLUG,
	'home'         => STREAM_PLAYER_HOME_URL,
	'docs'         => STREAM_PLAYER_DOCS_URL,
	'support'      => 'https://github.com/netmix/stream-player/issues/',
	'ratetext'     => __( 'Rate on WordPress.org', 'stream-player' ),
	'share'        => STREAM_PLAYER_HOME_URL . '#share',
	'sharetext'    => __( 'Share the Plugin Love', 'stream-player' ),
	'donate'       => 'https://patreon.com/radiostation',
	'donatetext'   => __( 'Support this Plugin', 'stream-player' ),
	'readme'       => false,
	'settingsmenu' => false,

	// --- Options ---
	'namespace'    => 'stream_player',
	'settings'     => 'sp',
	'option'       => 'stream_player',
	'options'      => $options,

	// --- WordPress.Org ---
	'wporgslug'    => STREAM_PLAYER_SLUG,
	'wporg'        => true,
	'textdomain'   => 'stream-player',

	// --- Freemius ---
	'hasplans'     => $plan_options['has_plans'],
	'upgrade_link' => add_query_arg( 'page', STREAM_PLAYER_SLUG . '-pricing', admin_url( 'admin.php' ) ),
	'pro_link'     => STREAM_PLAYER_PRO_URL . 'pricing/',
	'hasaddons'    => $plan_options['has_addons'],
	'addons_link'  => add_query_arg( 'page', STREAM_PLAYER_SLUG . '-addons', admin_url( 'admin.php' ) ),
	'plan'         => $plan_options['plan_type'],

	/* --- for Stream Player standalone version --- */
	'freemius_id'  => '11590',
	'freemius_key' => 'pk_290b70e902c7f036c0254c1c8d920',

);

// -----------------------------
// Freemius Init Settings Filter
// -----------------------------
add_filter( 'freemius_init_settings_stream_player', 'stream_player_freemius_config' );
function stream_player_freemius_config( $settings ) {

	// --- set to free version ---
	$settings['is_premium'] = false;
	$settings['has_premium_version'] = false;
	$settings['has_addons'] = false;
	$settings['has_paid_plans'] = true;

	// --- bundle configuration ---
	$settings['bundle_id'] = '11589';
	$settings['bundle_public_key'] = 'pk_ce082d29a1904c4f31f6631648f32';
	$settings['bundle_license_auto_activation'] = true;

	return $settings;
}

// -------------------------
// Set Plugin Option Globals
// -------------------------
global $stream_player_data;
$stream_player_data['options'] = $options;
$stream_player_data['settings'] = $settings;
if ( STREAM_PLAYER_DEBUG ) {
	echo '<span style="display:none;">Stream Player Settings: ' . esc_html( print_r( $settings, true ) ) . '</span>';
}

// ----------------------------
// Start Plugin Loader Instance
// ----------------------------
require STREAM_PLAYER_DIR . '/loader.php';
$instance = new stream_player_loader( $settings );


// -----------------------
// Load Plugin Text Domain
// -----------------------
add_action( 'plugins_loaded', 'stream_player_init' );
function stream_player_init() {
	load_plugin_textdomain( 'stream-player', false, STREAM_PLAYER_DIR . '/languages' );
}

// --------------------------------
// Filter Freemius Plugin Icon Path
// --------------------------------
add_filter( 'fs_plugin_icon_stream-player', 'stream_player_freemius_plugin_url_path' );
function stream_player_freemius_plugin_url_path( $default_path ) {
	$icon_path = STREAM_PLAYER_DIR . '/assets/icon-256x256.png';
	if ( file_exists( $icon_path ) ) {
		return $icon_path;
	}
	$icon_path = STREAM_PLAYER_DIR . '/images/' . STREAM_PLAYER_SLUG . '.png';
	if ( file_exists( $default_path ) ) {
		return $icon_path;
	}
	return $default_path;
}

// ----------------------------
// Add Pricing Page Path Filter
// ----------------------------
// 2.5.0: added for Freemius Pricing Page v2
add_action( 'stream_player_loaded', 'stream_player_add_pricing_path_filter' );
function stream_player_add_pricing_path_filter() {
	global $stream_player_freemius;
	if ( method_exists( $stream_player_freemius, 'add_filter' ) ) {
		$stream_player_freemius->add_filter( 'freemius_pricing_js_path', 'stream_player_pricing_page_path' );
	}
}

// ------------------------
// Pricing Page Path Filter
// ------------------------
// 2.5.0: added for Freemius Pricing Page v2
function stream_player_pricing_page_path( $default_pricing_js_path ) {
	return STREAM_PLAYER_DIR . '/freemius-pricing/freemius-pricing.js';
}


// ------------------------
// === Helper Functions ===
// ------------------------

// -----------------------
// Stream Player Shortcode
// -----------------------
function stream_player_shortcode( $settings = array() ) {
	return radio_player_shortcode( $settings );
}

// ------------------
// Check Plan Options
// ------------------
function stream_player_check_plan_options() {

	$has_addons = false;
	$has_plans = true;
	$plan = 'free';

	// --- check for deactivated pro plugin ---
	$plugins = wp_cache_get( 'plugins' );
	if ( !$plugins ) {
		if ( function_exists( 'get_plugins' ) ) {
			$plugins = get_plugins();
		} else {
			$plugin_path = ABSPATH . 'wp-admin/includes/plugin.php';
			if ( file_exists( $plugin_path ) ) {
				include $plugin_path;
				$plugins = get_plugins();
			}
		}
	}
	if ( $plugins && is_array( $plugins ) && ( count( $plugins ) > 0 ) ) {
		foreach ( $plugins as $slug => $plugin ) {
			if ( strstr( $slug, 'stream-player-pro.php' ) || strstr( $slug, 'radio-station-pro.php' ) ) {
				$plan = 'premium';
				break;
			}
		}
	}

	$plan_options = array(
		'has_plans'  => $has_plans,
		'has_addons' => $has_addons,
		'plan_type'  => $plan,
	);
	return $plan_options;
}

// ------------------
// Get Stream Formats
// ------------------
function stream_player_get_stream_formats() {

	// TODO: recheck amplitude formats ?
	// [Amplitude] HTML5 Support - mp3, aac ...?
	// ref: https://en.wikipedia.org/wiki/HTML5_audio#Supporting_browsers
	// [Howler] mp3, opus, ogg, wav, aac, m4a, mp4, webm
	// +mpeg, oga, caf, weba, webm, dolby, flac
	// [JPlayer] Audio: mp3, m4a - Video: m4v
	// +Audio: webma, oga, wav, fla, rtmpa +Video: webmv, ogv, flv, rtmpv
	// [Media Elements] Audio: mp3, wma, wav +Video: mp4, ogg, webm, wmv

	$formats = array(
		'aac'	=> 'AAC/M4A',   // A/H/J
		'mp3'	=> 'MP3',       // A/H/J
		'ogg'	=> 'OGG',		// H
		'oga'	=> 'OGA',		// H/J
		'webm'	=> 'WebM',		// H/J
		'rtmpa' => 'RTMPA',		// J
		'opus'  => 'OPUS',		// H
	);

	// --- filter and return ---
	$formats = apply_filters( 'stream_player_stream_formats', $formats );
	return $formats;
}

// -----------------
// Get Streaming URL
// -----------------
function stream_player_get_stream_url() {
	$streaming_url = '';
	$stream = stream_player_get_setting( 'streaming_url' );
	if ( STREAM_PLAYER_DEBUG ) {
		echo '<span style="display:none;">Stream URL Setting: ' . esc_html( print_r( $stream, true ) ) . '</span>';
	}
	if ( $stream && ( '' != $stream ) ) {
		$streaming_url = $stream;
	}
	$streaming_url = apply_filters( 'stream_player_stream_url', $streaming_url );

	return $streaming_url;
}

// -----------------
// KSES Allowed HTML
// -----------------
// 2.5.0: added for allowing custom wp_kses output
function stream_player_allowed_html( $type, $context = false ) {
	$allowed = wp_kses_allowed_html( 'post' );
	$allowed = apply_filters( 'stream_player_allowed_html', $allowed, $type, $context );
	return $allowed;
}

// -----------------------
// Anchor Tag Allowed HTML
// -----------------------
// 2.5.6: added for allowing extended anchor tag output for wp_kses
add_filter( 'stream_player_allowed_html', 'stream_player_anchor_tag_allowed_html', 10, 3 );
function stream_player_anchor_tag_allowed_html( $allowed, $type, $context ) {

	// 2.5.6: added docs context for documentation links
	if ( 'docs' != $context ) {
		return $allowed;
	}

	// 2.5.6: added data-href attribute for docs
	$allowed['a']['id'] = true;

	return $allowed;
}
