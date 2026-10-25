<?php

/**
 * Stream Player – Upgrade Cards
 *
 * Renders two upgrade-prompt cards on the "Upgrade" admin settings tab:
 *
 *   1. Stream Player PRO  – lists every PRO-only feature locked in the free
 *      version and links to the Freemius pricing / upgrade page.
 *
 *   2. Radio Station PRO  – promotes the full Radio Station ecosystem:
 *      - Radio Station (free) for station management
 *      - Radio Station PRO (includes Stream Player PRO) for the full platform
 *
 * This class is loaded only on the admin side (stream-player.php already
 * guards it with is_admin()), so no frontend overhead is added.
 *
 * @package Stream_Player
 * @since   2.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Stream_Player_Upgrade
 */
class Stream_Player_Upgrade {

	// -----------------------------------------------------------------------
	// Boot
	// -----------------------------------------------------------------------

	/**
	 * Register hooks. Called once from stream-player.php inside is_admin().
	 */
	public static function init() {
		// Render cards at the top of the upgrade tab (before the empty table).
		add_action( 'stream_player_admin_page_tab_upgrade_top', array( __CLASS__, 'render_cards' ) );

		// Hide the Reset / Save buttons on the upgrade tab – there are no fields.
		add_filter( 'stream_player_admin_save_buttons', array( __CLASS__, 'hide_save_buttons' ), 10, 2 );

		// Enqueue the inline CSS once per admin page load.
		add_action( 'admin_head', array( __CLASS__, 'enqueue_styles' ) );

		// This class is only instantiated when PRO is NOT active (stream-player.php
		// guard), so we never need to hide the tab here. The tab key exists in
		// options.php only when PRO is absent, by virtue of this class not loading.
		// However, if another plugin or mu-plugin loads PRO after our options array
		// is built, filter it out defensively.
		add_filter( 'stream_player_options', array( __CLASS__, 'maybe_remove_upgrade_tab' ) );
	}

	/**
	 * If PRO activates mid-request (rare but possible), strip the upgrade tab
	 * placeholder option so the tab button never appears.
	 *
	 * @param  array $options
	 * @return array
	 */
	public static function maybe_remove_upgrade_tab( $options ) {
		if ( defined( 'STREAM_PLAYER_PRO_FILE' ) || defined( 'RADIO_STATION_PRO_FILE' ) ) {
			unset( $options['upgrade_tab_placeholder'] );
		}
		return $options;
	}

	// -----------------------------------------------------------------------
	// Styles
	// -----------------------------------------------------------------------

	/**
	 * Output upgrade-card CSS in <head> (admin only, minimal footprint).
	 */
	public static function enqueue_styles() {

		// Only output on the Stream Player settings page.
		$screen = get_current_screen();
		if ( ! $screen || false === strpos( $screen->id, 'stream-player' ) ) {
			return;
		}
		?>
		<style id="sp-upgrade-cards-css">
		/* ---- Upgrade Tab wrapper ---- */
		#upgrade-tab .sp-upgrade-wrap {
			display: flex;
			flex-wrap: wrap;
			gap: 24px;
			padding: 24px 0 8px;
			align-items: flex-start;
		}

		/* ---- Individual card ---- */
		.sp-upgrade-card {
			background: #fff;
			border: 1px solid #c3c4c7;
			border-radius: 6px;
			box-shadow: 0 1px 3px rgba(0,0,0,.07);
			flex: 1 1 340px;
			max-width: 520px;
			padding: 28px 28px 24px;
			box-sizing: border-box;
		}

		/* ---- PRO card accent ---- */
		.sp-upgrade-card.sp-card-pro {
			border-top: 4px solid #2271b1;
		}

		/* ---- RSP card accent ---- */
		.sp-upgrade-card.sp-card-rsp {
			border-top: 4px solid #e8832a;
		}

		/* ---- Card header ---- */
		.sp-upgrade-card .sp-card-header {
			text-align: center;
			display: flex;
			align-items: center;
			gap: 12px;
			margin-bottom: 10px;
		}
		.sp-upgrade-card .sp-card-badge {
			display: inline-block;
			font-size: 11px;
			font-weight: 700;
			text-transform: uppercase;
			letter-spacing: .06em;
			padding: 3px 8px;
			border-radius: 3px;
			color: #fff;
		}
		.sp-card-pro .sp-card-badge  { background: #2271b1; }
		.sp-card-rsp .sp-card-badge  { background: #e8832a; }

		.sp-upgrade-card h2 {
			font-size: 18px;
			font-weight: 700;
			margin: 0;
			padding: 0;
			line-height: 1.3;
			color: #1d2327;
			border: none;
		}

		/* ---- Tagline ---- */
		.sp-upgrade-card .sp-card-tagline {
			font-size: 13px;
			color: #50575e;
			margin: 0 0 16px;
			line-height: 1.5;
		}

		/* ---- Feature list ---- */
		.sp-upgrade-card ul.sp-feature-list {
			margin: 0 0 20px 0;
			padding: 0;
			list-style: none;
		}
		.sp-upgrade-card ul.sp-feature-list li {
			font-size: 13px;
			color: #3c434a;
			padding: 4px 0 4px 22px;
			position: relative;
			line-height: 1.5;
			border-bottom: 1px solid #f0f0f1;
		}
		.sp-upgrade-card ul.sp-feature-list li:last-child {
			border-bottom: none;
		}
		.sp-upgrade-card ul.sp-feature-list li::before {
			content: "✓";
			position: absolute;
			left: 0;
			color: #00a32a;
			font-weight: 700;
		}


		/* ---- Coupon banner ---- */
		.sp-upgrade-card .sp-card-coupon {
			display: flex;
			align-items: center;
			gap: 10px;
			background: #fff8e5;
			border: 1px dashed #f0b429;
			border-radius: 4px;
			padding: 10px 14px;
			margin-bottom: 16px;
			font-size: 13px;
			color: #3c434a;
			line-height: 1.4;
		}
		.sp-upgrade-card .sp-card-coupon .sp-coupon-code {
			display: inline-block;
			font-family: monospace;
			font-size: 14px;
			font-weight: 700;
			letter-spacing: .08em;
			background: #f0b429;
			color: #1d2327;
			padding: 2px 8px;
			border-radius: 3px;
			white-space: nowrap;
		}

		/* ---- CTA buttons ---- */
		.sp-upgrade-card .sp-card-actions {
			display: flex;
			flex-wrap: wrap;
			gap: 10px;
			margin-top: 4px;
		}
		.sp-upgrade-card .sp-btn-primary {
			display: inline-block;
			background: #2271b1;
			color: #fff !important;
			text-decoration: none !important;
			font-size: 13px;
			font-weight: 600;
			padding: 8px 18px;
			border-radius: 4px;
			border: none;
			cursor: pointer;
			line-height: 1.4;
		}
		.sp-upgrade-card .sp-btn-primary:hover {
			background: #135e96;
		}
		.sp-card-rsp .sp-btn-primary {
			background: #e8832a;
		}
		.sp-card-rsp .sp-btn-primary:hover {
			background: #c7621a;
		}
		.sp-upgrade-card .sp-btn-secondary {
			display: inline-block;
			background: #f6f7f7;
			color: #2271b1 !important;
			text-decoration: none !important;
			font-size: 13px;
			font-weight: 600;
			padding: 8px 18px;
			border-radius: 4px;
			border: 1px solid #c3c4c7;
			cursor: pointer;
			line-height: 1.4;
		}
		.sp-upgrade-card .sp-btn-secondary:hover {
			background: #e5e5e5;
		}

		/* ---- Already-active notice ---- */
		.sp-upgrade-card .sp-card-active-notice {
			background: #edfaef;
			border: 1px solid #68de7c;
			border-radius: 4px;
			color: #1e4620;
			font-size: 13px;
			padding: 8px 12px;
			margin-top: 12px;
		}
		</style>
		<?php
	}

	// -----------------------------------------------------------------------
	// Render
	// -----------------------------------------------------------------------

	/**
	 * Output both upgrade cards.
	 * Hooked on stream_player_admin_page_tab_upgrade_top.
	 */
	public static function render_cards() {

		$pro_active = defined( 'STREAM_PLAYER_PRO_FILE' );
		$rsp_active = defined( 'RADIO_STATION_PRO_FILE' );

		// Internal Freemius pricing page URL (opens the in-dashboard upgrade flow).
		$sp_upgrade_url = add_query_arg( 'page', 'stream-player-pricing', admin_url( 'admin.php' ) );

		// External product / marketing URLs.
		$sp_pro_url  = 'https://streamplayer.pro/pricing/';
		$rsp_pro_url = 'https://radiostation.pro/pricing/';
		$rs_free_url = 'https://wordpress.org/plugins/radio-station/';

		echo '<div class="sp-upgrade-wrap">' . "\n";

		// ===================================================================
		// Card 1 – Stream Player PRO
		// ===================================================================
		self::render_sp_pro_card( $pro_active, $sp_upgrade_url, $sp_pro_url );

		// ===================================================================
		// Card 2 – Radio Station PRO
		// ===================================================================
		self::render_rsp_card( $rsp_active, $rsp_pro_url, $rs_free_url );

		echo '</div>' . "\n";
	}

	// -----------------------------------------------------------------------
	// Card 1 – Stream Player PRO
	// -----------------------------------------------------------------------

	/**
	 * @param bool   $pro_active    Whether Stream Player PRO is currently active.
	 * @param string $upgrade_url   In-dashboard Freemius upgrade URL.
	 * @param string $sp_pro_url    External pricing page URL.
	 */
	private static function render_sp_pro_card( $pro_active, $upgrade_url, $sp_pro_url ) {

		$features = array(
			__( 'Sitewide Bar – Responsive Mode (horizontal arrows or vertical expander)', 'stream-player' ),
			__( 'Sitewide Bar – Fade In animation on page load (via Teleporter)', 'stream-player' ),
			__( 'Sitewide Bar – Custom Text Color (free bar uses white)', 'stream-player' ),
			__( 'Sitewide Bar – Custom Background Color with transparency (free bar uses black)', 'stream-player' ),
			__( 'Continuous Playback – uninterrupted audio while navigating between pages', 'stream-player' ),
			__( 'Page Fade Transition – smooth page-load animation (via Teleporter)', 'stream-player' ),
			__( 'Page Load Timeout – fallback display when pages load slowly', 'stream-player' ),
			__( 'Now Playing – live stream metadata (song/artist) in the bar', 'stream-player' ),
			__( 'Metadata URL – custom endpoint for now-playing data', 'stream-player' ),
			__( 'Track Animation – scrolling ticker for long track titles', 'stream-player' ),
		);

		?>
		<div class="sp-upgrade-card sp-card-pro">

			<div class="sp-card-header">
				<img src="<?php echo esc_url( plugins_url( 'images/stream-player.png', STREAM_PLAYER_FILE ) ); ?>" alt="<?php esc_attr_e( 'Stream Player PRO', 'stream-player' ); ?>" class="sp-card-logo" />
				<span class="sp-card-badge"><?php esc_html_e( 'PRO', 'stream-player' ); ?></span>
				<h2><?php esc_html_e( 'Stream Player PRO', 'stream-player' ); ?></h2>
			</div>

			<p class="sp-card-tagline">
				<?php esc_html_e( 'Unlock the full sitewide persistent player bar experience — custom styling, live now-playing metadata, smooth page transitions, and continuous playback across every page of your site. Keep your listeners engaged and on-air branded without interruption.', 'stream-player' ); ?>
			</p>

			<ul class="sp-feature-list">
				<?php foreach ( $features as $feature ) : ?>
					<li><?php echo esc_html( $feature ); ?></li>
				<?php endforeach; ?>
			</ul>

			<?php if ( ! $pro_active ) : ?>
				<div class="sp-card-coupon">
					<?php esc_html_e( 'Save 30% — use code', 'stream-player' ); ?>
					<span class="sp-coupon-code">30OFF</span>
					<?php esc_html_e( 'at checkout.', 'stream-player' ); ?>
				</div>
			<?php endif; ?>

			<?php if ( $pro_active ) : ?>
				<div class="sp-card-active-notice">
					<?php esc_html_e( '✓ Stream Player PRO is active on this site.', 'stream-player' ); ?>
				</div>
			<?php else : ?>
				<div class="sp-card-actions">
					<a href="<?php echo esc_url( $upgrade_url ); ?>" class="sp-btn-primary">
						<?php esc_html_e( 'Upgrade to PRO', 'stream-player' ); ?>
					</a>
					<a href="<?php echo esc_url( $sp_pro_url ); ?>" class="sp-btn-secondary" target="_blank" rel="noopener noreferrer">
						<?php esc_html_e( 'View Pricing Details', 'stream-player' ); ?>
					</a>
				</div>
			<?php endif; ?>

		</div>
		<?php
	}

	// -----------------------------------------------------------------------
	// Card 2 – Radio Station PRO
	// -----------------------------------------------------------------------

	/**
	 * @param bool   $rsp_active   Whether Radio Station PRO is currently active.
	 * @param string $rsp_pro_url  Radio Station PRO pricing page URL.
	 * @param string $rs_free_url  Radio Station free plugin on WordPress.org.
	 */
	private static function render_rsp_card( $rsp_active, $rsp_pro_url, $rs_free_url ) {

		$features = array(
			__( 'Full radio station website management – schedules, shows, DJs, playlists', 'stream-player' ),
			__( 'Show Schedules with automated On-Air / Next Show display', 'stream-player' ),
			__( 'DJ & Host profile pages with bio, image, and social links', 'stream-player' ),
			__( 'Episode archive with audio playback per show', 'stream-player' ),
			__( 'Playlist management with track listings displayed in the player bar', 'stream-player' ),
			__( 'Current Show display in the persistent sitewide player bar', 'stream-player' ),
			__( 'Current Show Track Listings in the persistent sitewide player bar', 'stream-player' ),
			__( 'Includes Stream Player PRO – all PRO bar features included', 'stream-player' ),
		);

		?>
		<div class="sp-upgrade-card sp-card-rsp">

			<div class="sp-card-header">
				<img src="<?php echo esc_url( plugins_url( 'images/radio-station-pro.png', STREAM_PLAYER_FILE ) ); ?>" alt="<?php esc_attr_e( 'Radio Station PRO', 'stream-player' ); ?>" class="sp-card-logo" />
				<span class="sp-card-badge"><?php esc_html_e( 'PRO', 'stream-player' ); ?></span>
				<h2><?php esc_html_e( 'Radio Station PRO', 'stream-player' ); ?></h2>
			</div>

			<p class="sp-card-tagline">
				<?php esc_html_e( 'Build a complete radio station website. Radio Station PRO adds full station management tools — schedules, shows, DJs, playlists, and episode archives — and includes Stream Player PRO as part of the package. One subscription powers your entire on-air brand online.', 'stream-player' ); ?>
			</p>

			<ul class="sp-feature-list">
				<?php foreach ( $features as $feature ) : ?>
					<li><?php echo esc_html( $feature ); ?></li>
				<?php endforeach; ?>
			</ul>

			<?php if ( $rsp_active ) : ?>
				<div class="sp-card-active-notice">
					<?php esc_html_e( '✓ Radio Station PRO is active on this site.', 'stream-player' ); ?>
				</div>
			<?php else : ?>

				<div class="sp-card-coupon">
					<?php esc_html_e( 'Save 30% — use code', 'stream-player' ); ?>
					<span class="sp-coupon-code">30OFF</span>
					<?php esc_html_e( 'at checkout.', 'stream-player' ); ?>
				</div>

				<div class="sp-card-actions">
					<a href="<?php echo esc_url( add_query_arg( 'page', 'stream-player-pricing', admin_url( 'admin.php' ) ) ); ?>" class="sp-btn-primary">
						<?php esc_html_e( 'Upgrade to Radio Station PRO', 'stream-player' ); ?>
					</a>
					<a href="<?php echo esc_url( $rs_free_url ); ?>" class="sp-btn-secondary" target="_blank" rel="noopener noreferrer">
						<?php esc_html_e( 'Get Radio Station Free', 'stream-player' ); ?>
					</a>
				</div>

				<p style="font-size:12px;color:#646970;margin:12px 0 0;">
					<?php esc_html_e( 'Already managing your station with Radio Station (free)? Upgrading to Radio Station PRO unlocks all PRO features including Stream Player PRO — no separate license needed.', 'stream-player' ); ?>
				</p>

				<p style="font-size:12px;color:#646970;margin:8px 0 0;">
					<?php printf(
						wp_kses(
							/* translators: %s: Radio Station free download URL */
							__( 'New to Radio Station? <a href="%s" target="_blank" rel="noopener noreferrer">Download Radio Station free</a> to explore the platform — then upgrade to PRO with a <strong>14-day free trial</strong>, no commitment required.', 'stream-player' ),
							array( 'a' => array( 'href' => array(), 'target' => array(), 'rel' => array() ), 'strong' => array() )
						),
						esc_url( $rs_free_url )
					); ?>
				</p>

			<?php endif; ?>

		</div>
		<?php
	}

	// -----------------------------------------------------------------------
	// Suppress save buttons on upgrade tab
	// -----------------------------------------------------------------------

	/**
	 * Remove the Reset / Save buttons row on the upgrade tab.
	 * The tab has no form fields so the buttons serve no purpose.
	 *
	 * @param  string $buttons  Default buttons HTML.
	 * @param  string $tab      Current tab slug.
	 * @return string|false     Empty string to suppress output.
	 */
	public static function hide_save_buttons( $buttons, $tab ) {
		if ( 'upgrade' === $tab ) {
			return '';
		}
		return $buttons;
	}
}
