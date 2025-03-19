<?php

/*

Plugin Name: Stream Player
Plugin URI: https://streamplayer.pro
Description: Free, open source streaming audio player plugin by netmix®. Works with Icecast, Shoutcast, and Live 365 streams.
Author: Tony Hayes, Tony Zeoli
Version: 2.5.9.11
License: GPLv2 or later
Requires at least: 4.0
Text Domain: stream-player
Domain Path: /languages
Author URI: https://netmix.com

*/

if ( !defined( 'ABSPATH' ) ) exit;

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
// - Get Now Timestamp
// - Add Inline Script
// - Print Footer Scripts
// - Add inline Style
// - Print Footer Styles
// - Enqueue Widget Color Picker
// - Stream Player Shortcode
// - Check Plan Options
// - Get Upgrade URL
// - Get Pricing Page URL
// - Get Stream Formats
// - Get Streaming URL
// - Get Fallback URL
// - Get Station Image URL
// - Filter for Streaming Data
// - Get Broadcast Data
// - Get Allowed HTML
// - Filter Anchor Tag Allowed HTML
// - Settings Inputs Allowed HTML


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
define( 'STREAM_PLAYER_HOME_URL', 'https://streamplayer.pro/stream-player/' );
define( 'STREAM_PLAYER_DOCS_URL', 'https://streamplayer.pro/docs/' );
define( 'STREAM_PLAYER_PRO_URL', 'https://streamplayer.pro/' );

// -----------------------
// Set Debug Mode Constant
// -----------------------
if ( !defined( 'STREAM_PLAYER_DEBUG' ) ) {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$sp_debug = ( isset( $_REQUEST['sp-debug'] ) && ( '1' == sanitize_text_field( wp_unslash( $_REQUEST['sp-debug'] ) ) ) ) ? true : false;
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
	// 2.5.6: added affiliation key
	'hasplans'     => $plan_options['has_plans'],
	'upgrade_link' => add_query_arg( 'page', STREAM_PLAYER_SLUG . '-pricing', admin_url( 'admin.php' ) ),
	'pro_link'     => STREAM_PLAYER_PRO_URL . 'pricing/',
	'hasaddons'    => $plan_options['has_addons'],
	'addons_link'  => add_query_arg( 'page', STREAM_PLAYER_SLUG . '-addons', admin_url( 'admin.php' ) ),
	'plan'         => $plan_options['plan_type'],
	// 'affiliation'  => 'selected', // temporarily disabled

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


// -------------------------
// Filter Freemius Load Path
// -------------------------
// 2.5.9.3: added for move of Freemius directory
add_filter( 'freemius_load_path', 'stream_player_freemius_load_path', 10, 3 );
function stream_player_freemius_load_path( $freemius_path, $namespace, $args ) {
	if ( 'stream_player' == $namespace ) {
		$freemius_path = dirname( __FILE__ ) . '/vendor/freemius/start.php';
	}
	return $freemius_path;
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
	// 2.5.10: fix to mismatched variable default_path
	$icon_path = STREAM_PLAYER_DIR . '/images/' . STREAM_PLAYER_SLUG . '.png';
	if ( file_exists( $icon_path ) ) {
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

// ---------------------------------
// Filter Freemius Pricing Page Path
// ---------------------------------
// 2.5.0: added for Freemius Pricing Page v2
// 2.5.9.3: added vendor subdirectory to Freemius pricing path
function stream_player_pricing_page_path( $default_pricing_js_path ) {
	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) || STREAM_PLAYER_DEBUG ? '' : '.min';
	return STREAM_PLAYER_DIR . '/vendor/freemius-pricing/freemius-pricing' . $suffix . '.js';
}


// ------------------------
// === Helper Functions ===
// ------------------------

// -----------------
// Get Now Timestamp
// -----------------
function stream_player_get_now() {

	$datetime = new DateTime( 'now', new DateTimeZone( 'UTC' ) );
	$now = $datetime->format( 'U' );

	// 2.5.6: allow explicit override of now time
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( isset( $_REQUEST['for_time'] ) ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$now = absint( $_REQUEST['for_time'] );
	}
	$now = apply_filters( 'stream_player_now_time', $now );

	return $now;
}

// -----------------
// Add Inline Script
// -----------------
// 2.5.0: added for missed inline scripts (via shortcodes)
function stream_player_add_inline_script( $handle, $js, $position = 'after' ) {

	// --- add check if script is already done ---
	if ( wp_script_is( $handle, 'registered' ) && !wp_script_is( $handle, 'done' ) ) {

		// --- handle javascript normally ---
		wp_add_inline_script( $handle, $js, $position );

	} else {

		// 2.5.7: enqueue dummy javascript file to output in footer
		if ( !wp_script_is( 'stream-player-footer', 'registered' ) ) {
			$version = functIon_exists( 'stream_player_plugin_version' ) ? stream_player_plugin_version() : '2.5.0';
			wp_register_script( 'stream-player-footer', null, array( 'jquery' ), $version, true );
			wp_enqueue_script( 'stream-player-footer' );
		}
		wp_add_inline_script( 'stream-player-footer', $js, $position );
	}
}

// -----------------
// Add Inline Styles
// -----------------
// 2.5.0: added for missed inline styles (via shortcodes)
// 2.5.10: deprecated: duplicated function in radio player
/* function stream_player_add_inline_style( $handle, $css ) {

	// --- add check if style is already done ---
	if ( wp_style_is( $handle, 'registered' ) && !wp_style_is( $handle, 'done' ) ) {

		// --- handle style normally ---
		wp_add_inline_style( $handle, $css );

	} else {
		
		if ( !wp_style_is( 'stream-player-footer', 'registered' ) ) {
			$version = function_exists( 'stream_player_plugin_version' ) ? stream_player_plugin_version() : '2.5.0';
			wp_register_style( 'stream-player-footer', null, array(), $version, 'all' );
			wp_enqueue_style( 'stream-player-footer' );
		}
		wp_add_inline_style( 'stream-player-footer', $css );

	}
} */


// ---------------------------
// Enqueue Widget Color Picker
// ---------------------------
// 2.5.0: added for widget color options
function stream_player_enqueue_color_picker() {

	// --- enqueue color picker ---
	$suffix = SCRIPT_DEBUG ? '' : '.min';
	$url = plugins_url( '/js/wp-color-picker-alpha' . $suffix . '.js', STREAM_PLAYER_FILE );
	wp_enqueue_script( 'wp-color-picker-a', $url, array( 'wp-color-picker' ), '3.0.0', true );

	// --- init color picker fields ---
	$js = "jQuery(document).ready(function() {";
		$js .= "if (jQuery('.color-picker').length) {jQuery('.color-picker').wpColorPicker();}";
	$js .= "});" . "\n";
	stream_player_add_inline_script( 'wp-color-picker-a', $js );
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
		if ( !function_exists( 'get_plugins' ) ) {
			$admin_plugins = ABSPATH . 'wp-admin/includes/plugin.php';
			if ( file_exists( $admin_plugins ) ) {
				include_once $admin_plugins;
			}
		}
		$plugins = get_plugins();
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

// ---------------
// Get Upgrade URL
// ---------------
// 2.3.0: added to get Upgrade to Pro link
function stream_player_get_upgrade_url() {
	$upgrade_url = add_query_arg( 'page', 'stream-player-pricing', admin_url( 'admin.php' ) );
	return $upgrade_url;
}

// ---------------
// Get Pricing URL
// ---------------
// 2.5.0: added to get link to Pricing page
function stream_player_get_pricing_url() {
	$pricing_url = STREAM_PLAYER_PRO_URL . 'pricing/';
	return $pricing_url;
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

// ----------------
// Get Fallback URL
// ----------------
// 2.3.3.9: added get fallback URL helper
function stream_player_get_fallback_url() {
	$fallback_url = '';
	$fallback = stream_player_get_setting( 'fallback_url' );
	if ( $fallback && ( '' != $fallback ) ) {
		$fallback_url = $fallback;
	}
	if ( STREAM_PLAYER_DEBUG ) {
		echo '<span style="display:none;">Fallback URL Setting: ' . esc_html( $fallback_url ) . '</span>';
	}
	$fallback_url = apply_filters( 'stream_player_fallback_url', $fallback_url );

	return $fallback_url;
}

// ---------------------
// Get Station Image URL
// ---------------------
// 2.3.3.8: added get station logo image URL
function stream_player_get_station_image_url() {
	$station_image = '';
	$attachment_id = stream_player_get_setting( 'station_image' );
	$image = wp_get_attachment_image_src( $attachment_id, 'full' );
	if ( is_array( $image ) ) {
		$station_image = $image[0];
	}
	$station_image = apply_filters( 'stream_player_station_image_url', $station_image );

	return $station_image;
}

// -------------------------
// Filter for Streaming Data
// -------------------------
// 2.3.3.7: added streaming data filter for player integration
add_filter( 'radio_player_data', 'stream_player_streaming_data', 10, 2 );
function stream_player_streaming_data( $data, $channel = false ) {
	$data = array(
		'script'   => stream_player_get_setting( 'player_script' ),
		'instance' => 0,
		'url'      => stream_player_get_stream_url(),
		'format'   => stream_player_get_setting( 'streaming_format' ),
		'fallback' => stream_player_get_fallback_url(),
		'fformat'  => stream_player_get_setting( 'fallback_format' ),
	);
	if ( STREAM_PLAYER_DEBUG ) {
		echo '<span style="display:none;">Player Stream Data: ' . esc_html( print_r( $data, true ) ) . '</span>' . "\n";
	}
	$data = apply_filters( 'stream_player_streaming_data', $data, $channel );
	return $data;
}

// ------------------
// Get Broadcast Data
// ------------------
// 2.5.6: added for now playing metadata endpoint 
function stream_player_get_broadcast_data() {

	// --- filter and return ---
	$broadcast = apply_filters( 'stream_player_broadcast_data', array() );
	return $broadcast;
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

// ----------------------------
// Settings Inputs Allowed HTML
// ----------------------------
// 2.5.0: added for admin settings inputs
add_filter( 'stream_player_allowed_html', 'stream_player_settings_allowed_html', 10, 3 );
function stream_player_settings_allowed_html( $allowed, $type, $context ) {

	if ( ( 'content' != $type ) || ( 'settings' != $context ) ) {
		return $allowed;
	}

	// --- input ---
	$allowed['input'] = array(
		'id'          => array(),
		'class'       => array(),
		'name'        => array(),
		'value'       => array(),
		'type'        => array(),
		'data'        => array(),
		'placeholder' => array(),
		'style'       => array(),
		'checked'     => array(),
		'onclick'     => array(),
	);

	// --- textarea ---
	$allowed['textarea'] = array(
		'id'          => array(),
		'class'       => array(),
		'name'        => array(),
		'value'       => array(),
		'type'        => array(),
		'placeholder' => array(),
		'style'       => array(),
	);

	// --- select ---
	$allowed['select'] = array(
		'id'          => array(),
		'class'       => array(),
		'name'        => array(),
		'value'       => array(),
		'type'        => array(),
		'multiselect' => array(),
		'style'       => array(),
		'onchange'    => array(),
	);

	// --- select option ---
	$allowed['option'] = array(
		'selected' => array(),
		'value'    => array(),
	);

	// --- option group ---
	$allowed['optgroup'] = array(
		'label' => array(),
	);

	// --- allow onclick on spans and divs ---
	$allowed['span']['onclick'] = array();
	$allowed['div']['onclick'] = array();

	return $allowed;
}

// --------------------------
// Player Widget Allowed HTML
// --------------------------
// 2.5.10: added allowed HTML for player widget
add_filter( 'stream_player_allowed_html', 'stream_player_widget_player_allowed_html', 10, 3 );
function stream_player_widget_player_allowed_html( $allowed, $type, $context ) {

	if ( ( 'widget' != $type ) || ( 'player' != $context ) ) {
		return $allowed;
	}

	// --- link ---
	$allowed['link'] = array(
		'rel'         => array(),
		'href'        => array(),
	);

	// --- button ---
	$allowed['button'] = array(
		'id'          => array(),
		'class'       => array(),
		'role'        => array(),
		'onclick'     => array(),
		'tabindex'    => array(),
		'aria-label'  => array(),
		'style'       => array(),
	);

	// --- input ---
	$allowed['input'] = array(
		'id'          => array(),
		'class'       => array(),
		'name'        => array(),
		'value'       => array(),
		'type'        => array(),
		'data'        => array(),
		'placeholder' => array(),
		'style'       => array(),
		'checked'     => array(),
		'onclick'     => array(),
		'max'         => array(),
		'min'         => array(),
		'aria-label'  => array(),
	);

	// --- styles ---
	$allowed['style'] = array();

	return $allowed;
}

