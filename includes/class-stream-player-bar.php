<?php

/**
 * Stream Player – Free Sitewide Player Bar
 *
 * Renders a fixed-position sitewide player bar (top or bottom) for the free
 * version of Stream Player. Styling (colors), now-playing metadata, and
 * continuous playback are PRO-only features; they are intentionally absent
 * here so the PRO plugin can provide them without conflict.
 *
 * This class is loaded only when Stream Player PRO is NOT active, so that
 * PRO's richer bar implementation takes precedence.
 *
 * @package Stream_Player
 * @since   2.9.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Stream_Player_Bar
 */
class Stream_Player_Bar {

	// -----------------------------------------------------------------------
	// Boot
	// -----------------------------------------------------------------------

	/**
	 * Initialise hooks. Called once from stream-player.php after PRO check.
	 */
	public static function init() {
		add_action( 'wp_footer',          array( __CLASS__, 'output_bar' ), 4 );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_styles' ), 11 );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ), 12 );
	}

	// -----------------------------------------------------------------------
	// Helper
	// -----------------------------------------------------------------------

	/**
	 * Retrieve a single plugin setting.
	 *
	 * Delegates to stream_player_get_setting() (defined in loader.php), which
	 * itself applies the 'stream_player_get_setting' filter and falls back to
	 * option defaults.
	 *
	 * @param  string $key  Option key.
	 * @return mixed
	 */
	private static function get( $key ) {
		if ( function_exists( 'stream_player_get_setting' ) ) {
			return stream_player_get_setting( $key );
		}
		return '';
	}

	/**
	 * Return the active position ('top'|'bottom') or false when the bar is
	 * disabled.
	 *
	 * @return string|false
	 */
	private static function position() {
		$pos = self::get( 'player_bar' );
		if ( '' === $pos || 'off' === $pos || ! in_array( $pos, array( 'top', 'bottom' ), true ) ) {
			return false;
		}
		return $pos;
	}

	// -----------------------------------------------------------------------
	// HTML Output
	// -----------------------------------------------------------------------

	/**
	 * Hooked on wp_footer (priority 4) – renders the bar wrapper + player.
	 */
	public static function output_bar() {

		$position = self::position();
		if ( ! $position ) {
			return;
		}

		$classes = implode( ' ', array( 'player-bar', $position, 'horizontal', 'sp-free-bar' ) );

		echo '<div id="radio-station-player-bar" class="' . esc_attr( $classes ) . '">' . "\n";

			// Close / hide button (persists via localStorage).
			echo '<button class="sp-bar-close" aria-label="' . esc_attr__( 'Hide player bar', 'stream-player' ) . '" title="' . esc_attr__( 'Hide player bar', 'stream-player' ) . '">';
				echo '<span aria-hidden="true">&times;</span>';
			echo '</button>' . "\n";

			// Build shortcode attributes matching PRO's bar behaviour.
			$title  = self::get( 'player_title' );
			if ( 'yes' === $title ) {
				$title = self::get( 'station_title' );
			} else {
				$title = '0';
			}

			$image = self::get( 'player_image' );
			if ( 'yes' === $image ) {
				$image_id  = self::get( 'station_image' );
				$attachment = wp_get_attachment_image_src( $image_id );
				$image = ( $attachment && is_array( $attachment ) ) ? $attachment[0] : 0;
			} else {
				$image = 0;
			}

			$atts = array(
				'title'    => $title,
				'image'    => $image,
				'script'   => self::get( 'player_script' ),
				'layout'   => 'horizontal',
				'theme'    => self::get( 'player_theme' ),
				'buttons'  => self::get( 'player_buttons' ),
				'volume'   => self::get( 'player_volume' ),
				'default'  => true,
				'sitewide' => true,
			);

			/**
			 * Filter the player attributes used for the free sitewide bar.
			 *
			 * @param array $atts Player shortcode attributes.
			 */
			$atts = apply_filters( 'stream_player_free_bar_atts', $atts );

			if ( function_exists( 'stream_player_shortcode' ) ) {
				echo stream_player_shortcode( $atts ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}

		echo '</div>' . "\n";
	}

	// -----------------------------------------------------------------------
	// Styles
	// -----------------------------------------------------------------------

	/**
	 * Enqueue / output bar CSS.  Runs at wp_enqueue_scripts priority 11 so it
	 * fires after the core player stylesheet is registered.
	 */
	public static function enqueue_styles() {

		$position = self::position();

		// Always enqueue the core player styles (needed for the player inside
		// the bar even if the bar itself is disabled on this page load).
		if ( function_exists( 'stream_player_enqueue_styles' ) ) {
			stream_player_enqueue_styles();
		}

		if ( ! $position ) {
			return;
		}

		$bar_height = (int) self::get( 'player_bar_height' );
		if ( $bar_height < 40 ) {
			$bar_height = 80;
		}

		$css = self::build_css( $position, $bar_height );

		// Attach as inline style to the already-enqueued stream-player handle.
		if ( wp_style_is( 'stream-player', 'enqueued' ) || wp_style_is( 'stream-player', 'registered' ) ) {
			wp_add_inline_style( 'stream-player', $css );
		} else {
			// Fallback: register a thin handle just to carry the inline CSS.
			wp_register_style( 'sp-bar', false, array(), false ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
			wp_enqueue_style( 'sp-bar' );
			wp_add_inline_style( 'sp-bar', $css );
		}
	}

	/**
	 * Build the full CSS string for the bar.
	 *
	 * @param  string $position   'top' or 'bottom'.
	 * @param  int    $bar_height Bar height in pixels.
	 * @return string
	 */
	private static function build_css( $position, $bar_height ) {

		$css = '';

		// --- Core bar layout ---
		// Default black background. Color customisation is a PRO-only feature;
		// free installs always get this base style.
		$css .= '#radio-station-player-bar {';
			$css .= 'position: fixed; left: 0; right: 0; text-align: center; z-index: 9999;';
			$css .= 'background-color: #000000; color: #ffffff;';
			// Prevent subpixel repaints on scroll.
			$css .= 'will-change: transform; transform: translateZ(0); backface-visibility: hidden;';
		$css .= '}' . "\n";

		$css .= '#radio-station-player-bar .rp-interface {overflow: hidden;}' . "\n";

		// Stable viewport height unit (avoids jumping on mobile when address
		// bar appears / disappears).
		$css .= ':root {--sp-bar-vh: 1vh;}' . "\n";
		$css .= 'body {min-height: calc(var(--sp-bar-vh) * 100);}' . "\n";

		// --- Body padding so page content is not hidden behind the bar ---
		if ( 'top' === $position ) {
			$css .= 'body {padding-top: ' . $bar_height . 'px;}' . "\n";
		} else {
			$css .= 'body {padding-bottom: ' . $bar_height . 'px;}' . "\n";
		}

		// --- Positional CSS ---
		$css .= '#radio-station-player-bar.top {top: 0; width: 100%; height: ' . $bar_height . 'px;}' . "\n";
		$css .= 'body.admin-bar #radio-station-player-bar.top {top: 32px;}' . "\n";
		$css .= '@media screen and (max-width: 782px) {body.admin-bar #radio-station-player-bar.top {top: 46px;}}' . "\n";

		// Bottom bar respects iOS safe-area-inset.
		$css .= '#radio-station-player-bar.bottom {width: 100%; height: ' . $bar_height . 'px; bottom: 0;';
			$css .= 'bottom: calc(env(safe-area-inset-bottom, 0px) + 0px);';
		$css .= '}' . "\n";

		// --- Station logo ---
		$station_image_url = function_exists( 'stream_player_get_station_image_url' )
			? stream_player_get_station_image_url()
			: '';
		if ( '' !== $station_image_url ) {
			$css .= '#radio-station-player-bar .rp-station-image {background-image: url(' . esc_url( $station_image_url ) . ');}' . "\n";
		} else {
			$css .= '#radio-station-player-bar .rp-station-image {display: none;}' . "\n";
		}

		// --- Full-width player ---
		$css .= '#radio-station-player-bar .radio-player-shortcode,';
		$css .= '#radio-station-player-bar .radio-container,';
		$css .= '#radio-station-player-bar .rp-single {width: 100%;}' . "\n";

		// --- Section width constraints ---
		$css .= '#radio-station-player-bar .rp-station-info,';
		$css .= '#radio-station-player-bar .rp-show-info {min-width: 240px; max-width: 320px;}' . "\n";
		$css .= '#radio-station-player-bar .rp-interface {min-width: 320px; max-width: 400px;}' . "\n";
		$css .= '#radio-station-player-bar .radio-container.no-volume-controls .rp-interface {min-width: 160px;}' . "\n";
		$css .= '#radio-station-player-bar .radio-container.no-volume-controls .rp-play-pause-button-bg {margin-right: 0;}' . "\n";

		// Now-playing hidden (PRO feature).
		$css .= '#radio-station-player-bar .radio-container .rp-now-playing,';
		$css .= '#radio-station-player-bar .rp-container .rp-now-playing {display: none;}' . "\n";

		// --- Close button ---
		$css .= '#radio-station-player-bar .sp-bar-close {';
			$css .= 'position: absolute; top: 50%; right: 8px; transform: translateY(-50%);';
			$css .= 'background: none; border: none; cursor: pointer;';
			$css .= 'font-size: 20px; line-height: 1; padding: 4px 6px;';
			$css .= 'color: inherit; opacity: 0.6; z-index: 10000;';
			$css .= 'min-width: 44px; min-height: 44px;'; // WCAG touch target.
			$css .= 'display: flex; align-items: center; justify-content: center;';
		$css .= '}' . "\n";
		$css .= '#radio-station-player-bar .sp-bar-close:hover {opacity: 1;}' . "\n";

		// Shift player content left so close button does not overlap.
		$css .= '#radio-station-player-bar .radio-player-shortcode {padding-right: 52px;}' . "\n";

		// --- Mobile tweaks ---
		$css .= self::build_media_queries();

		/**
		 * Filter the complete CSS for the free sitewide bar.
		 *
		 * @param string $css      Generated CSS.
		 * @param string $position 'top' or 'bottom'.
		 * @param int    $bar_height Bar height in px.
		 */
		$css = apply_filters( 'stream_player_free_bar_styles', $css, $position, $bar_height );

		return $css;
	}

	/**
	 * Media-query rules for mobile responsiveness.
	 *
	 * @return string
	 */
	private static function build_media_queries() {

		$css = '';

		// Small phones (≤ 415 px).
		$css .= '@media (max-width: 415px) {' . "\n";
			$css .= '#radio-station-player-bar .rp-interface {min-width: 240px; max-width: 280px; text-align: center;}' . "\n";
			$css .= '#radio-station-player-bar .horizontal .rp-play-pause-button-bg {margin-left: 7px; margin-right: 7px;}' . "\n";
			$css .= '#radio-station-player-bar .rp-station-text, #radio-station-player-bar .rp-show-text {max-width: 180px;}' . "\n";
			$css .= '#radio-station-player-bar .rp-station-image {margin-right: 5px;}' . "\n";
			$css .= '#radio-station-player-bar .rp-show-image {margin-left: 5px;}' . "\n";
		$css .= '}' . "\n";

		// Tiny phones (≤ 300 px).
		$css .= '@media (max-width: 300px) {' . "\n";
			$css .= '#radio-station-player-bar .rp-interface {min-width: 200px; max-width: 200px;}' . "\n";
			$css .= '#radio-station-player-bar .rp-mute, #radio-station-player-bar .rp-volume-max,';
			$css .= '#radio-station-player-bar .rp-volume-up, #radio-station-player-bar .rp-volume-down {display: none;}' . "\n";
			$css .= '#radio-station-player-bar .rp-station-text, #radio-station-player-bar .rp-show-text {max-width: 150px;}' . "\n";
		$css .= '}' . "\n";

		/**
		 * Filter media-query CSS for the free bar.
		 *
		 * @param string $css
		 */
		$css = apply_filters( 'stream_player_free_bar_media_queries', $css );

		return $css;
	}

	// -----------------------------------------------------------------------
	// Scripts
	// -----------------------------------------------------------------------

	/**
	 * Enqueue sp-bar.js – handles fade-in, close/restore, and single-player
	 * coordination.
	 */
	public static function enqueue_scripts() {

		$position = self::position();
		if ( ! $position ) {
			return;
		}

		$version  = function_exists( 'stream_player_plugin_version' ) ? stream_player_plugin_version() : filemtime( STREAM_PLAYER_DIR . '/js/sp-bar.js' );
		$js_url   = plugins_url( 'js/sp-bar.js', STREAM_PLAYER_FILE );

		wp_enqueue_script(
			'sp-bar',
			$js_url,
			array( 'stream-player' ), // depends on core player script being loaded first
			$version,
			true // load in footer
		);
	}
}
