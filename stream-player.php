<?php

/*

Plugin Name: Stream Player
Plugin URI: https://streamplayer.pro
Description: Open-source live streaming audio player by Radio Station by netmix®. Supports Icecast, Shoutcast, Live365, multiple formats & scripts.
Author: Tony Hayes, Tony Zeoli
Version: 2.7.0
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
// - Filter Admin Texts
// - Plugin Admin Options Filter
// - Plugin Options Filter
// - Load Plugin Text Domain
// - Filter Freemius Load Path
// - Filter Freemius Plugin Icon Path
// x Add Pricing Page Path Filter
// x Pricing Page Path Filter
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
// === Sanitization Helpers ===
// - KSES Allowed HTML
// - Filter Anchor Tag Allowed HTML
// - Settings Inputs Allowed HTML
// - Player Widget Allowed HTML


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
	require STREAM_PLAYER_DIR . '/includes/class-stream-player-upgrade.php';
	Stream_Player_Upgrade::init();
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
$plan_options = stream_player_check_plan_options();
require STREAM_PLAYER_DIR . '/options.php';
$options = stream_player_get_plugin_options( false );

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
	'title'        => 'Stream Player',
	'parentmenu'   => STREAM_PLAYER_SLUG,
	'home'         => STREAM_PLAYER_HOME_URL,
	'docs'         => STREAM_PLAYER_DOCS_URL,
	'support'      => 'https://wordpress.org/support/plugin/stream-player/',
	// 'ratetext'     => __( 'Rate on WordPress.org', 'stream-player' ),
	'share'        => STREAM_PLAYER_HOME_URL . '#share',
	// 'sharetext'    => __( 'Share the Plugin Love', 'stream-player' ),
	'donate'       => 'https://patreon.com/radiostation',
	// 'donatetext'   => __( 'Support this Plugin', 'stream-player' ),
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

// ------------------
// Filter Admin Texts
// ------------------
// 2.5.18: added filter for delayed translations
add_filter( 'stream_player_admin_args', 'stream_player_settings_texts' );
function stream_player_settings_texts( $args ) {
	$texts = array(
		'title'        => __( 'Stream Player', 'stream-player' ),
		'sharetext'    => __( 'Share the Plugin Love', 'stream-player' ),
		'ratetext'     => __( 'Rate on WordPress.org', 'stream-player' ),
		'donatetext'   => __( 'Support this Plugin', 'stream-player' ),
	);
	$args = array_merge( $args, $texts );
	return $args;
}

// -----------------------------------
// Plugin Settings Page Header Redesign
// -----------------------------------
// Replace the default square icon with the Stream Player logotype.
add_filter( 'stream_player_settings_page_icon_url', 'stream_player_header_logotype_url' );
function stream_player_header_logotype_url( $icon_url ) {
	return plugins_url( 'images/stream-player-logo.png', STREAM_PLAYER_FILE );
}

// Suppress the plain-text plugin title — the logotype speaks for itself.
add_filter( 'stream_player_settings_page_title', '__return_empty_string' );

// Inject inline CSS to reshape the header into three sections:
//   A (left)   – logotype + byline
//   B (middle) – Home / Docs / Support links, vertical
//   C (right)  – Rate / Share / Support links, vertical
add_action( 'admin_enqueue_scripts', 'stream_player_logotype_css' );
function stream_player_logotype_css() {
	if ( ! wp_style_is( 'plugin-admin-settings', 'enqueued' ) ) {
		return;
	}
	$css = '
/* ---- Logotype: natural wide dimensions, no square crop ---- */
.plugin-settings-page-icon {
	width: auto !important;
	height: 40px !important;
	max-width: 300px !important;
	border-radius: 0 !important;
	display: block;
}

/* ---- Hide the empty title anchor left by __return_empty_string ---- */
.plugin-settings-page-header h3:first-child,
.plugin-settings-page-header h3 a:empty,
.plugin-settings-page-header td h3 {
	display: none !important;
}
/* spacer td between icon and inner-table */
.plugin-settings-page-header > tbody > tr > td:nth-child(2) {
	display: none !important;
}

/* ---- Outer header: flex row, three sections ---- */
.plugin-settings-page-header,
.plugin-settings-page-header > tbody,
.plugin-settings-page-header > tbody > tr {
	display: flex !important;
	width: 100%;
}
.plugin-settings-page-header > tbody > tr {
	align-items: flex-start;
	gap: 32px;
	flex-wrap: nowrap;
}

/* Section A – logotype + byline (first td: the icon cell) */
.plugin-settings-page-header > tbody > tr > td:first-child {
	display: flex !important;
	flex-direction: column;
	align-items: flex-start;
	flex: 0 0 auto;
	min-width: 180px;
}

/* Section B – inner nav table (second real td, which becomes the third td in DOM) */
.plugin-settings-page-header > tbody > tr > td:nth-child(3) {
	display: flex !important;
	flex: 1 1 auto;
	align-items: flex-start;
}

/* Section C – rate/share/support links (last td) */
.plugin-settings-page-header > tbody > tr > td:last-child {
	display: flex !important;
	flex: 0 0 auto;
	align-items: flex-start;
}

/* ---- Inner table (Home|Readme|Docs|Support links) → vertical list ---- */
.plugin-settings-page-header td > table {
	display: flex !important;
	flex-direction: column;
	align-items: flex-start;
}
.plugin-settings-page-header td > table > tbody,
.plugin-settings-page-header td > table > tbody > tr {
	display: flex !important;
	flex-direction: column;
	align-items: flex-start;
}
.plugin-settings-page-header td > table > tbody > tr > td {
	display: block !important;
	padding: 2px 0;
}
/* hide loader-rendered author name + icon — we replace with JS byline in section A */
.plugin-settings-page-header td > table font {
	display: none !important;
}
/* hide the author icon TD (last td in the inner table row) */
.plugin-settings-page-header td > table tr > td:last-child:not(:first-child) {
	display: none !important;
}
/* hide author name link (the <a> directly inside the inner table td, before the links) */
.plugin-settings-page-header td > table td > a:not(.pluginlink):not(.smalllink) {
	display: none !important;
}

/* ---- Version: push into the right-side td, style it small ---- */
.plugin-setttings-page-version {
	font-size: 12px !important;
	font-weight: 400 !important;
	color: #646970 !important;
	white-space: nowrap;
	margin: 0 !important;
}

/* ---- Link styling: match the right-column links ---- */
.plugin-settings-page-header .pluginlink,
.plugin-settings-page-header .smalllink {
	display: block !important;
	font-size: 13px !important;
	font-weight: normal !important;
	text-decoration: none;
	padding: 2px 0 !important;
	line-height: 1.6 !important;
}
.plugin-settings-page-header .pluginlink {
	display: flex !important;
	align-items: center;
}
/* Section C: remove extra spacing/breaks */
.plugin-settings-page-links {
	display: flex;
	flex-direction: column;
	padding-top: 0 !important;
}
.plugin-settings-page-links br {
	display: none !important;
}
/* ---- Byline ---- */
.sp-header-byline {
	display: block;
	font-size: 12px;
	color: #646970;
	margin-top: 6px;
	line-height: 1.4;
}
.sp-header-byline a {
	color: #646970;
	text-decoration: none;
}
.sp-header-byline a:hover {
	color: #2271b1;
	text-decoration: underline;
}
';
	wp_add_inline_style( 'plugin-admin-settings', $css );
}

// Inject the byline HTML after the logotype via admin_footer JS.
add_action( 'admin_footer', 'stream_player_header_byline_js' );
function stream_player_header_byline_js() {
	if ( ! wp_style_is( 'plugin-admin-settings', 'enqueued' ) ) {
		return;
	}
	?>
	<script>
	(function() {
		// Byline: insert under the logotype
		var icon = document.querySelector( '.plugin-settings-page-header .plugin-settings-page-icon' );
		if ( icon && ! document.querySelector( '.sp-header-byline' ) ) {
			var byline = document.createElement( 'span' );
			byline.className = 'sp-header-byline';
			byline.innerHTML = 'by <a href="https://profiles.wordpress.org/tonyzeoli/" target="_blank" rel="noopener noreferrer">Tony Zeoli</a> &amp; <a href="https://profiles.wordpress.org/majick777/" target="_blank" rel="noopener noreferrer">Tony Hayes</a>';
			icon.parentNode.insertBefore( byline, icon.nextSibling );
		}
		// Strip the ' | ' text-node separators between the nav links
		var linksCell = document.querySelector( '.plugin-settings-page-header td > table td[align="center"]' );
		if ( linksCell ) {
			linksCell.childNodes.forEach( function( node ) {
				if ( node.nodeType === 3 ) { node.textContent = ''; }
			});
		}
	})();
	</script>
	<?php
}

// ---------------------------
// Plugin Admin Options Filter
// ---------------------------
// 2.5.18: added filter for delay translated admin options
add_filter( 'stream_player_options', 'stream_player_admin_options' );
function stream_player_admin_options( $options ) {
	$admin = is_admin();
	$options = stream_player_get_plugin_options( $admin );
	return $options;
}

// ---------------------
// Plugin Options Filter
// ---------------------
// 2.5.18: added to bypass REST null options bug
add_filter( 'stream_player_plugin_options', 'stream_player_plugin_options' );
function stream_player_plugin_options( $options ) {
	$options = stream_player_get_plugin_options( false );
	return $options;
}

// -----------------------
// Load Plugin Text Domain
// -----------------------
// add_action( 'init', 'stream_player_init' );
function stream_player_init() {
	load_plugin_textdomain( 'stream-player', false, STREAM_PLAYER_DIR . '/languages' );
}

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
// 2.5.18: removed pricing page as merged in Freemius
// add_action( 'stream_player_loaded', 'stream_player_add_pricing_path_filter' );
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

// ------------
// Get Template
// ------------
// 2.5.18: added for future usage
function stream_player_get_template( $type, $template, $paths = false ) {

	$dirs = array();
	$styledir = get_stylesheet_directory();
	$styledirurl = get_stylesheet_directory_uri();
	$templatedir = get_template_directory();
	$templatedirurl = get_template_directory_uri();

	// --- maybe generate default hierarchies ---
	foreach ( $paths as $path ) {
		$dirs[] = array(
			'path'    => $styledir . '/' . $path,
			'urlpath' => $styledirurl . '/' . $path,
		);
	}
	if ( $styledir != $templatedir ) {
		foreach ( $paths as $path ) {
			$dirs[] = array(
				'path'    => $templatedir . '/' . $path,
				'urlpath' => $templatedirurl . '/' . $path,
			);
		}
	}
	if ( defined( 'STREAM_PLAYER_PRO_DIR' ) ) {
		foreach ( $paths as $path ) {
			$dirs[] = array(
				'path'    => STREAM_PLAYER_PRO_DIR . '/' . $path,
				'urlpath' => plugins_url( $path, STREAM_PLAYER_PRO_FILE ),
			);
		}
	}
	foreach ( $paths as $path ) {
		$dirs[] = array(
			'path'    => STREAM_PLAYER_DIR . '/' . $path,
			'urlpath' => plugins_url( $path, STREAM_PLAYER_FILE ),
		);
	}
	$dirs = apply_filters( 'stream_player_template_dir_hierarchy', $dirs, $template, $paths );

	// --- loop directory hierarchy to find first template ---
	foreach ( $dirs as $dir ) {

		// 2.3.4: use trailingslashit to account for empty paths
		$template_path = trailingslashit( $dir['path'] ) . $template;
		$template_url = trailingslashit( $dir['urlpath'] ) . $template;

		if ( file_exists( $template_path ) ) {
			if ( 'file' == (string) $type ) {
				return $template_path;
			} elseif ( 'url' === (string) $type ) {
				return $template_url;
			} else {
				return array( 'file' => $template_path, 'url' => $template_url );
			}
		}
	}

	return false;
}

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
// 2.5.18: deprecated: duplicated function in radio player
/* function stream_player_add_inline_script( $handle, $js, $position = 'after' ) {

	// --- add check if script is already done ---
	if ( wp_script_is( $handle, 'registered' ) && !wp_script_is( $handle, 'done' ) ) {

		// --- handle javascript normally ---
		wp_add_inline_script( $handle, $js, $position );

	} else {

		// 2.5.7: enqueue dummy javascript file to output in footer
		// 2.5.18: register but do not enqueue
		if ( !wp_script_is( 'stream-player-footer', 'registered' ) ) {
			$version = function_exists( 'stream_player_plugin_version' ) ? stream_player_plugin_version() : '2.5.0';
			wp_register_script( 'stream-player-footer', null, array( 'jquery' ), $version, true );
			// wp_enqueue_script( 'stream-player-footer' );
		}
		wp_add_inline_script( 'stream-player-footer', $js, $position );
		
		// 2.5.18: enqueue scripts in footer
		if ( !has_action( 'wp_footer', 'stream_player_enqueue_footer_scripts', 9 ) ) {
			add_action( 'wp_footer', 'stream_player_enqueue_footer_scripts', 9 );
		}
	}
} */

// ----------------------
// Enqueue Footer Scripts
// ----------------------
/* function stream_player_enqueue_footer_scripts() {
	wp_enqueue_script( 'stream-player-footer' );	
} */

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

	// 2.5.18: let list and div items have an onclick and style
	$allowed['li']['onclick'] = array();
	$allowed['li']['style'] = array();
	$allowed['div']['onclick'] = array();
	$allowed['div']['style'] = array();

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

	// --- select ---
	// 2.5.13: add select tag
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

	// --- styles ---
	$allowed['style'] = array();

	return $allowed;
}

