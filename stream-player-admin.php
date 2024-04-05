<?php
/*
 * Stream Player Plugin Admin Functions
 */

if ( !defined( 'ABSPATH' ) ) exit;

// === Admin Setup ===
// - Enqueue Admin Scripts
// - Admin Style Fixes
// - Filter Plugin Page Links
// === Admin Menu ===
// - Setting Page Capability Check
// - Add Admin Menu and Submenu Items
// - Fix to Redirect Plugin Settings Menu Link
// - Display Plugin Docs Page
// - Parse Markdown Doc File
// === Update Notices ===
// - Get Plugin Upgrade Notice
// - Parse Plugin Update Notice
// - Plugin Page Update Message
// - Display Admin Update Notice
// - Display Plugin Notice
// - Get Plugin Notices
// - AJAX Mark Notice Read
// === Admin Notices ===
// x Admin Notice Dismiss Iframe
// - Admin Notice Dismiss Javascript
// - Plugin Settings Page Top
// - Plugin Settings Page Bottom
// - Launch Offer Notice
// - Launch Offer Content
// - Dismiss Launch Offer
// - MailChimp Subscriber Form
// - AJAX Record Subscriber
// - AJAX Clear Subscriber


// -------------------
// === Admin Setup ===
// -------------------

// ---------------------
// Enqueue Admin Scripts
// ---------------------
add_action( 'admin_enqueue_scripts', 'stream_player_enqueue_admin_scripts' );
function stream_player_enqueue_admin_scripts() {

	// --- enqueue admin js file ---
	$script_url = plugins_url( 'js/stream-player-admin.js', STREAM_PLAYER_FILE );
	$script_path = STREAM_PLAYER_DIR . '/js/stream-player-admin.js';
	$version = filemtime( $script_path );
	$deps = array( 'jquery' );
	wp_enqueue_script( 'stream-player-admin', $script_url, $deps, $version, true );

	// 2.5.0: maybe enqueue pricing page styles
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( isset( $_REQUEST['page'] ) && ( 'stream-player-pricing' == sanitize_text_field( $_REQUEST['page'] ) ) ) {
		$style_url = plugins_url( 'freemius-pricing/freemius-pricing.css', STREAM_PLAYER_FILE );
		$style_path = STREAM_PLAYER_DIR . '/freemius-pricing/freemius-pricing.css';
		$version = filemtime( $style_path );
		wp_enqueue_style( 'freemius-pricing', $style_url, array(), $version, 'all' );
	}

}

// -----------------
// Admin Style Fixes
// -----------------
// add_action( 'admin_print_styles', 'stream_player_admin_styles' );
function stream_player_admin_styles() {

	// --- hide first admin submenu item to prevent duplicate of main menu item ---
	$css = '#toplevel_page_stream-player .wp-first-item {display: none;}' . "\n";
	$css .= '#toplevel_page_stream-player-pro .wp-first-item {display: none;}' . "\n";

	// --- filter admin styles ---
	$css = apply_filters( 'stream_player_admin_styles', $css );

	// --- output admin styles ---
	// 2.5.6: use wp_kses_post instead of wp_strip_all_tags
	echo '<style>' . wp_kses_post( $css ) . '</style>' . "\n";

}

// ------------------------
// Filter Plugin Page Links
// ------------------------
add_filter( 'plugin_action_links_' . STREAM_PLAYER_BASENAME, 'stream_player_plugin_page_links', 20, 2 );
function stream_player_plugin_page_links( $links, $file ) {

	global $stream_player_data;

	foreach ( $links as $key => $link ) {

		if ( strstr( $link, '-addons' ) ) {
			if ( !$stream_player_data['settings']['hasaddons'] ) {
				unset( $links[$key] );
			}
		}
		// --- remove upgrade link if Pro is already installed ---
		if ( defined( 'STREAM_PLAYER_PRO_FILE' ) && strstr( $key, 'upgrade' ) ) {
			unset( $links[$key] );
		}
	}

	return $links;
}


// ------------------
// === Admin Menu ===
// ------------------

// ------------------------------
// Settings Page Capability Check
// ------------------------------
// (recheck permissions for main menu item click)
add_action( 'admin_init', 'stream_player_settings_cap_check' );
function stream_player_settings_cap_check() {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( isset( $_REQUEST['page'] ) && ( STREAM_PLAYER_SLUG == sanitize_text_field( $_REQUEST['page'] ) ) ) {
		$settingscap = apply_filters( 'stream_player_settings_capability', 'manage_options' );
		if ( !current_user_can( $settingscap ) ) {
			wp_die( esc_html( __( 'You do not have permissions to access that page.', 'stream-player' ) ) );
		}
	}
}

// --------------------------------
// Add Admin Menu and Submenu Items
// --------------------------------
add_action( 'admin_menu', 'stream_player_add_admin_menus' );
function stream_player_add_admin_menus() {

	// TODO: create stream player icon
	$icon = plugins_url( 'images/stream-player-icon.png', STREAM_PLAYER_FILE );
	$position = apply_filters( 'stream_player_menu_position', 5 );
	$settingscap = apply_filters( 'stream_player_manage_options_capability', 'manage_options' );
	$sp = __( 'Stream Player', 'stream-player' );

	// ---- main menu item ----
	// (added with publish_posts capability so that other submenu items remain accessible)
	add_menu_page( $sp . ' ' . __( 'Settings', 'stream-player' ), $sp, 'publish_posts', STREAM_PLAYER_SLUG, 'stream_player_settings_page', $icon, $position );

	// --- settings submenu item ---
	add_options_page( $sp . ' ' . __( 'Settings', 'stream-player' ), $sp, $settingscap, STREAM_PLAYER_SLUG, 'stream_player_settings_page' );

	// --- documentation submenu item
	add_submenu_page( STREAM_PLAYER_SLUG, $sp . ' ' . __( 'Documentation', 'stream-player' ), __( 'Help', 'stream-player' ), 'publish_posts', 'stream-player-docs', 'stream_player_plugin_docs_page' );

	do_action( 'stream_player_admin_submenu_bottom' );

}

// -----------------------------------------
// Fix to Redirect Plugin Settings Menu Link
// -----------------------------------------
add_action( 'admin_init', 'stream_player_settings_page_redirect' );
function stream_player_settings_page_redirect() {

	// --- bug out if not plugin settings page ---
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( !isset( $_REQUEST['page'] ) || ( STREAM_PLAYER_SLUG != sanitize_text_field( $_REQUEST['page'] ) ) ) {
		return;
	}

	// --- check if link is for options-general.php ---
	if ( strstr( sanitize_text_field( $_SERVER['REQUEST_URI'] ), '/options-general.php' ) ) {

		// --- redirect to plugin settings page (admin.php) ---
		$url = add_query_arg( 'page', STREAM_PLAYER_SLUG, admin_url( 'admin.php' ) );
		// TODO: maybe use wp_safe_redirect here ?
		wp_redirect( $url );
		exit;
	}
}

// ------------------------
// Display Plugin Docs Page
// ------------------------
function stream_player_plugin_docs_page() {

	// --- show MailChimp signup form ---
	// echo "<p>&nbsp;</p>";
	// stream_player_mailchimp_form();

	// --- include markdown reader ---
	include_once STREAM_PLAYER_DIR . '/reader.php';

	$docs = scandir( STREAM_PLAYER_DIR . '/docs/' );
	// $docs[] = 'CHANGELOG.md'; // temporarily removed
	foreach ( $docs as $doc ) {
		if ( !in_array( $doc, array( '.', '..' ) ) ) {
			$id = str_replace( '.md', '', $doc );
			echo '<div id="doc-page-' . esc_attr( strtolower( $id ) ) . '" class="doc-page"';
			if ( 'index' != $id ) {
				echo ' style="display:none;"';
			}
			echo '>';
				// 2.5.6: use wp_kses with allowed HTML
				$allowed = stream_player_allowed_html( 'content', 'docs' );
				echo wp_kses( stream_player_parse_doc( $id ), $allowed );
			echo '</div>' . "\n";
		}
	}

	// 2.5.6: added jquery onclick functions to replace onclick attributes
	echo "<script>jQuery('.doc-link').on('click',function(){ref = jQuery(this).attr('id').replace('-doc-link',''); stream_load_doc(ref);});
	function stream_load_doc(id) {
		pages = document.getElementsByClassName('doc-page');
		for (i = 0; i < pages.length; i++) {pages[i].style.display = 'none';}
		hash = '';
		if (id.indexOf('#') > -1) {
			parts = id.split('#');
			id = parts[0]; hash = parts[1];
		} else if (id == 'index') {hash = 'index-top';}
		document.getElementById('doc-page-'+id).style.display = 'block';
		if (hash != '') {
			anchor = document.getElementById(hash);
			atop = anchor.offsetTop; /* do not use 'top'! */
			window.scrollTo(0, (atop-20));
		}
	}</script>";

	echo '<style>.doc-page {padding: 20px 40px 20px 10px;}
	.doc-page, .doc-page p {font-size: 14px;}
	.doc-page table {padding: 10px; background-color: #F9F9F9; border: 1px solid #CCC; border-radius: 10px;}
	.doc-page th {text-align: left; padding: 7px 14px;}
	.doc-page td {font-size:16px; padding: 7px 14px;}
	.doc-page td a {text-decoration: none; font-weight: bold;}
	.doc-page td a:hover {text-decoration: underline;}
	h1.docs-heading {font-size: 1.65em; margin-bottom: 1.65em;}
	h2.docs-heading {font-size: 1.5em; margin-top: 1.5em;}
	h3.docs-heading {font-size: 1.3em;}
	h4.docs-heading {font-size: 1.1em;}
	</style>';

	// --- output announcement content ---
	// stream_player_announcement_content( false );

}

// -----------------------
// Parse Markdown Doc File
// -----------------------
function stream_player_parse_doc( $id ) {

	// --- get docs page contents ---
	if ( 'CHANGELOG' == $id ) {
		$path = STREAM_PLAYER_DIR . '/CHANGELOG.md';
	} else {
		$path = STREAM_PLAYER_DIR . '/docs/' . $id . '.md';
	}
	$contents = file_get_contents( $path );

	// --- strip top level heading to prevent duplicate title ---
	$sep = '***';
	$backlink = '';
	if ( 'index' != $id ) {
		// 2.5.6: remove onclick attribute to survive sanitization
		// $backlink = '<a class="doc-index-link" href="javascript:void(0);" onclick="stream_load_doc(\'index\');">&larr; ';
		$backlink = '<a class="doc-link" id="index-doc-link">&larr; ';
		$backlink .= esc_html( __( 'Back to Documentation Index', 'stream-player' ) );
		$backlink .= '</a><br>';
	}
	$contents = str_replace( $sep, $backlink, $contents );

	// --- replace relative links ---
	$contents = str_replace( '(#', '(./' . $id . '#', $contents );
	$contents = str_replace( '.md)', ')', $contents );
	$contents = str_replace( '.md#', '#', $contents );

	// --- process markdown formatting ---
	$formatted = Markdown( $contents );

	// --- a # name links to headings ---
	for ( $i = 1; $i < 7; $i++ ) {
		$tag_start = '<h' . $i . '>';
		$tag_end = '</h' . $i . '>';
		if ( stristr( $formatted, $tag_start ) ) {
			while ( stristr( $formatted, $tag_start ) ) {
				$pos = stripos( $formatted, $tag_start );
				$pos2 = $pos + strlen( $tag_start );
				$before = substr( $formatted, 0, $pos );
				$after = substr( $formatted, $pos2, strlen( $formatted ) );
				$pos3 = stripos( $after, $tag_end );
				$anchor = sanitize_title( substr( $after, 0, $pos3 ) );
				$alink = '<a id="' . esc_attr( $anchor ) . '" name="' . esc_attr( $anchor ) . '"></a>';
				$newheading = $alink . '<h' . $i . ' class="docs-heading">';
				$formatted = $before . $newheading . $after;
			}
		}
	}

	// --- replace links with javascript ---
	$tag_start = '<a href="./';
	$tag_end = '"';
	$placeholder = '<alink ';
	if ( stristr( $formatted, $tag_start ) ) {
		while ( stristr( $formatted, $tag_start ) ) {
			$pos = strpos( $formatted, $tag_start );
			$before = substr( $formatted, 0, $pos );
			$pos = strpos( $formatted, $tag_start ) + strlen( $tag_start );
			$after = substr( $formatted, $pos, strlen( $formatted ) );
			$pos2 = strpos( $after, $tag_end );
			$url = substr( $after, 0, $pos2 );
			$url = strtolower( $url );
			// 2.5.6: replace onclick with class and id
			// $onclick = ' onclick="stream_load_doc(\'' . esc_js( $url ) . '\');';
			$class = ' class="doc-link" id="' . esc_attr( $url ) . '-doc-link"';
			$after = substr( $after, ( $pos2 + 1), strlen( $after ) );
			$formatted = $before . $placeholder . $class . $after;
		}
	}

	$formatted = str_replace( '<a href="', '<a target="_blank" href="', $formatted );
	if ( 'index' != $id ) {
		$formatted .= $backlink . '<br>';
	} else {
		$formatted = '<a id="index-top" name="index-top"></a>' . $formatted;
	}
	$formatted = str_replace( '<alink ', '<a ', $formatted );

	return $formatted;
}


// ----------------------
// === Update Notices ===
// ----------------------

// -------------------------
// Get Plugin Upgrade Notice
// -------------------------
function stream_player_get_upgrade_notice() {

	// --- check updates transient for upgrade notices ---
	$notice = false;
	$pluginslug = STREAM_PLAYER_SLUG;
	$pluginupdates = get_site_transient( 'update_plugins' );

	if ( $pluginupdates && is_object( $pluginupdates ) && property_exists( $pluginupdates, 'response' ) ) {
		foreach ( $pluginupdates->response as $file => $update ) {
			if ( is_object( $update ) && property_exists( $update, 'slug' ) ) {
				if ( $update->slug == $pluginslug ) {
					if ( property_exists( $update, 'upgrade_notice' ) ) {

						// 2.3.3.9: compare new version with installed version
						$new_version = $update->new_version;
						$version = stream_player_plugin_version();
						if ( version_compare( $version, $new_version, '<' ) ) {

							// --- parse upgrade notice ---
							$notice = $update->upgrade_notice;
							$notice = stream_player_parse_upgrade_notice( $notice );
							$notice['update_id'] = str_replace( '.', '', $new_version );
							if ( property_exists( $update, 'icons' ) && isset( $update->icons['1x'] ) ) {
								$notice['icon_url'] = $update->icons['1x'];
							}
							$notice['plugin_file'] = $file;
							break;
						}
					}
				}
			}
		}
	}
	return $notice;
}

// --------------------
// Parse Upgrade Notice
// --------------------
function stream_player_parse_upgrade_notice( $notice ) {

	$lines = $content_lines = array();
	$notice_url = '';
	if ( strstr( $notice, "\n" ) ) {
		$contents = explode( "\n", $notice );
	} else {
		$contents = array( $notice );
	}

	foreach ( $contents as $content ) {
		if ( trim( $content ) != '' ) {
			// --- extract link from line ---
			if ( strstr( $content, 'http' ) ) {
				$pos = strpos( $content, 'http' );
				$chunks = str_split( $content, $pos );
				unset( $chunks[0] );
				$remainder = implode( '', $chunks );
				$breaks = array( ' ', '<', "\n", "\r" );
				$pos = array();
				foreach ( $breaks as $i => $urlbreak ) {
					if ( strstr( $remainder, $urlbreak ) ) {
						$pos[$i] = strpos( $remainder, $urlbreak );
					}
				}
				if ( count( $pos ) > 0 ) {
					$pos = min( $pos );
					$chunks = str_split( $remainder, $pos );
					$notice_url = $chunks[0];
				} else {
					$notice_url = $remainder;
				}
				$content = str_replace( $notice_url, '', $content );
				$content = str_replace( '<li>', '', $content );
				$content = str_replace( '</li>', '', $content );
			}
		}
		if ( '' != trim( $content ) ) {
			$content_lines[] = $content;
			if ( !in_array( $content, array( '<ul>', '</ul>' ) ) ) {
				$line = str_replace( array( '<li>', '</li>' ), array( '', '' ), $content );
				$lines[] = $line;
			}
		}
	}

	// --- recombine lines and return ---
	$content = implode( "\n", $content_lines );
	$notice = array(
		'content' => $content,
		'lines'   => $lines,
		'url'     => $notice_url,
	);

	return $notice;
}

// --------------------------
// Plugin Page Update Message
// --------------------------
add_action( 'in_plugin_update_message-' . STREAM_PLAYER_BASENAME, 'stream_player_plugin_update_message', 10, 2 );
function stream_player_plugin_update_message( $plugin_data, $response ) {

	// --- bug out if no update plugins capability ---
	if ( !current_user_can( 'update_plugins' ) ) {
		return;
	}

	// --- get upgrade notice ---
	$notice = stream_player_get_upgrade_notice();

	// --- bug out if no upgrade notice ---
	if ( !$notice ) {
		return;
	}

	// --- output update available message ---
	echo '<br><b>' . esc_html( __( 'Take a moment to Update for a better experience. In this update', 'stream-player' ) ) . ":</b><br>";
	foreach ( $notice['lines'] as $i => $line ) {
		// 2.5.0: maybe output link to notice URL
		if ( ( '' != $notice['url'] ) && ( 0 == $i ) ) {
			// 2.5.6: fix to incorrect variable notice_url
			echo '&bull; <a href="' . esc_url( $notice['url'] ) . '" target="_blank" title="' . esc_attr( __( 'Read full update details.', 'stream-player' ) ) . '">' . esc_html( $line ) . '</a><br>';
		} else {
			echo '&bull; ' . esc_html( $line ) . '<br>';
		}
	}

}

// ---------------------------
// Display Admin Update Notice
// ---------------------------
add_action( 'admin_notices', 'stream_player_admin_update_notice' );
function stream_player_admin_update_notice() {
	// --- do not display on settings page ---
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( isset( $_GET['page'] ) && ( STREAM_PLAYER_SLUG === sanitize_text_field( $_GET['page'] ) ) ) {
		return;
	}
	stream_player_update_notice();
}

// ---------------------------
// Display Admin Update Notice
// ---------------------------
function stream_player_update_notice() {

	// --- bug out if no update plugins capability ---
	if ( !current_user_can( 'update_plugins' ) ) {
		return;
	}

	// --- get upgrade notice ---
	$notice = stream_player_get_upgrade_notice();

	// --- bug out if no upgrade notice ---
	if ( !$notice ) {
		return;
	}

	// --- ignore if updating now ---
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( isset( $_GET['action'] ) && ( 'upgrade-plugin' === sanitize_text_field( $_GET['action'] ) ) && isset( $_GET['plugin'] ) && ( $notice['plugin_file'] === sanitize_text_field( $_GET['plugin'] ) ) ) {
		return;
	}

	// --- bug out if already read ---
	$read = get_option( 'stream_player_read_upgrades' );
	if ( $read && is_array( $read ) && isset( $read[$notice['update_id']] ) && ( '1' == $read[$notice['update_id']] ) ) {
		return;
	}

	// --- set plugin update URL ---
	$update_url = admin_url( 'update.php' ) . '?action=upgrade-plugin&plugin=' . $notice['plugin_file'];
	$update_url = wp_nonce_url( $update_url, 'upgrade-plugin_' . $notice['plugin_file'] );

	// --- output update available notice ---
	echo '<div id="stream-player-update-' . esc_attr( $notice['update_id'] ) . '" class="notice update-nag" style="position:relative;">' . "\n";

		echo '<ul style="list-style:none;">' . "\n";

			if ( isset( $notice['icon_url'] ) ) {
				echo '<li style="display:inline-block; vertical-align:top; margin-right:40px;">' . "\n";
				echo '<img src="' . esc_url( $notice['icon_url'] ) . '" style="width:75px; height: 75px;">' . "\n";
				echo '</li>' . "\n";
			}

			echo '<li style="display:inline-block; text-align:center; vertical-align:top; margin-right:40px; line-height:1.8em;">' . "\n";
			echo esc_html( __( 'A new version of', 'stream-player' ) ) . '<br>' . "\n";
			echo '<b><span style="font-size:1.2em;">' . esc_html( __( 'Stream Player', 'stream-player' ) ) . '</span></b><br>' . "\n";
			echo esc_html( __( 'is available.', 'stream-player' ) ) . "\n";
			echo '</li>' . "\n";

			echo '<li style="display:inline-block; vertical-align:top; margin-right:40px; max-width:600px;">' . "\n";
			echo '<b>' . esc_html( __( 'Take a moment to Update for a better experience. In this update', 'stream-player' ) ) . ":</b><br>" . "\n";
				echo '<ul style="padding:0; list-style:disc;">' . "\n";
				foreach ( $notice['lines'] as $i => $line ) {
					if ( ( '' != $notice['url'] ) && ( 0 == $i ) ) {
						echo '<li style="text-indent:20px;"><a href="' . esc_url( $notice['url'] ) . '" target="_blank" title="' . esc_attr( __( 'Full update details.', 'stream-player' ) ) . '">' . esc_html( $line ) . '</li>' . "\n";
					} else {
						echo '<li style="text-indent:20px;">' . esc_html( $line ) . '</li>' . "\n";
					}
				}
				echo '</ul>' . "\n";
			echo '</li>' . "\n";

			echo '<li style="display:inline-block; text-align:center; vertical-align:top;">' . "\n";
			echo '<a class="button button-primary" href="' . esc_url( $update_url ) . '">' . esc_html( __( 'Update Now', 'stream-player' ) ) . '</a>' . "\n";
			if ( '' != $notice['url'] ) {
				echo '<br><br>' . "\n";
				echo '<a class="button" href="' . esc_url( $notice['url'] ) . '" target="_blank">' . esc_html( __( 'Full Update Details', 'stream-player' ) ) . ' &rarr;</a>' . "\n";
			}
			echo '</li>' . "\n";

		echo '</ul>' . "\n";

		// --- dismiss notice link ---
		echo '<div style="position:absolute; top:20px; right: 20px;">' . "\n";
			// 2.5.7: deprecated admin notice iframe
			// $dismiss_url = add_query_arg( 'action', 'stream_player_notice_dismiss', admin_url( 'admin-ajax.php' ) );
			// $dismiss_url = add_query_arg( 'upgrade', $notice['update_id'], $dismiss_url );
			// echo '<a href="' . esc_url( $dismiss_url ) . '" target="stream-player-notice-iframe" style="text-decoration:none;">' . "\n";
			echo '<a href="#" onclick="stream_player_dismiss_notice(\'upgrade\',\'' . esc_attr( $notice['update_id'] ) . '\');" style="text-decoration:none;">' . "\n";
			echo '<span class="dashicons dashicons-dismiss" title="' . esc_attr( __( 'Dismiss this Notice', 'stream-player' ) ) . '"></span></a>' . "\n";
		echo '</div>' . "\n";

	echo '</div>' . "\n";

	// --- notice dismissal ---
	// 2.5.7: replace admin notice iframe with javascript
	// stream_player_admin_notice_iframe();
	stream_player_dismiss_notice_javascript();
}

// ---------------------
// Display Plugin Notice
// ---------------------
add_action( 'admin_notices', 'stream_player_notice' );
function stream_player_notice() {

	// 2.5.0: check for user capability
	if ( !current_user_can( 'update_plugins' ) ) {
		return;
	}

	// --- get latest notice ---
	$notices = stream_player_get_notices();
	if ( count( $notices ) < 1 ) {
		return;
	}
	$notice_ids = array_keys( $notices );
	$notice_id = max( $notice_ids );
	$notice = $notices[$notice_id];

	// --- bug out if already read ---
	$read = get_option( 'stream_player_read_notices' );
	if ( $read && isset( $read[$notice_id] ) && ( '1' == $read[$notice_id] ) ) {
		return;
	}

	// --- display plugin notice ---
	echo '<div id="stream-player-notice-' . esc_attr( $notice['id'] ) . '" class="notice notice-info" style="position:relative;">' . "\n";

		// --- output plugin notice text ---
		echo '<ul style="list-style:none;">' . "\n";

			// --- plugin icon ---
			$icon_url = plugins_url( 'images/stream-player.png', STREAM_PLAYER_FILE );
			echo '<li style="display:inline-block; text-align:center; vertical-align:top; margin-right:40px; line-height:1.8em;">' . "\n";
				echo '<img src="' . esc_url( $icon_url ) . '" style="width:75px; height:75px;">' . "\n";
			echo '</li>' . "\n";

			// --- notice title ---
			echo '<li style="display:inline-block; text-align:center; vertical-align:top; margin-right:40px; line-height:1.8em;">' . "\n";
				echo '<b><span style="font-size:1.2em;">' . esc_html( __( 'Stream Player', 'stream-player' ) ) . '</span></b><br>' . "\n";
				echo '<b>' . esc_html( __( 'Update Notice', 'stream-player' ) ) . '</b>' . "\n";
			echo '</li>' . "\n";

			// --- notice details ---
			echo '<li style="display:inline-block; vertical-align:top; margin-right:40px; font-size:16px; line-height:22px; max-width:600px;">' . "\n";
				echo '<div style="margin-bottom:10px;">' . "\n";
					echo '<b>' . esc_html( __( 'Thanks for Updating! You can enjoy these improvements now', 'stream-player' ) ) . '</b>:' . "\n";
				echo '</div>' . "\n";
				echo '<ul style="padding:0; list-style:disc;">' . "\n";
				foreach ( $notice['lines'] as $i => $line ) {
					if ( ( '' != $notice['url'] ) && ( 0 == $i ) ) {
						echo '<li style="text-indent:20px;">' . esc_html( $line ) . '</li>' . "\n";
					} else {
						echo '<li style="text-indent:20px;">' . esc_html( $line ) . '</li>' . "\n";
					}
				}
				echo '</ul>' . "\n";
			echo '</li>' . "\n";

			echo '<li style="display:inline-block; text-align:center; vertical-align:top;">' . "\n";

				// --- link to update blog post ---
				if ( isset( $notice['url'] ) && ( '' != $notice['url'] ) ) {
					echo '<a class="button" href="' . esc_url( $notice['url'] ) . '">' . esc_html( __( 'Full Update Details', 'stream-player' ) ) . ' &rarr;</a>' . "\n";
					echo '<br><br>' . "\n";
				}

				// --- link to settings page ---
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				if ( !isset( $_REQUEST['page'] ) || ( STREAM_PLAYER_SLUG !== sanitize_text_field( $_REQUEST['page'] ) ) ) {
					$settings_url = add_query_arg( 'page', STREAM_PLAYER_SLUG, admin_url( 'admin.php' ) );
					echo '<a class="button button-primary" href="' . esc_url( $settings_url ) . '">' . esc_html( __( 'Plugin Settings', 'stream-player' ) ) . '</a>' . "\n";
				}

			echo '</li>' . "\n";

		echo '</ul>' . "\n";

		// --- notice dismissal button ---
		echo '<div style="position:absolute; top:20px; right: 20px;">' . "\n";
			// $dismiss_url = add_query_arg( 'action', 'stream_player_notice_dismiss', admin_url( 'admin-ajax.php' ) );
			// $dismiss_url = add_query_arg( 'notice', $notice['id'], $dismiss_url );
			// echo '<a href="' . esc_url( $dismiss_url ) . '" target="stream-player-notice-iframe" id+style="text-decoration:none;">' . "\n";
			echo '<a href="#" onclick="stream_player_notice_dismiss(\'notice\',\'' . esc_attr( $notice['id'] ) . '\');" id+style="text-decoration:none;">' . "\n";
			echo '<span class="dashicons dashicons-dismiss" title="' . esc_attr( __( 'Dismiss this Notice', 'stream-player' ) ) . '"></span></a>' . "\n";
		echo '</div>' . "\n";

	// --- close notice wrap ---
	echo '</div>' . "\n";

	// --- notice dismissal ---
	// 2.5.7: replace admin notice iframe with javascript
	// stream_player_admin_notice_iframe();
	stream_player_dismiss_notice_javascript();

}

// ------------------
// Get Plugin Notices
// ------------------
function stream_player_get_notices() {

	// --- check for needed files ---
	$readme = STREAM_PLAYER_DIR . '/readme.txt';
	$parser = STREAM_PLAYER_DIR . '/reader.php';

	// --- get upgrade notices ---
	$notices = array();
	if ( file_exists( $parser ) && file_exists( $readme ) ) {

		// --- get readme contents ---
		$contents = file_get_contents( $readme );

		// --- fix to parser failing on license lines ---
		$contents = str_replace( 'License: GPLv2 or later', '', $contents );
		$contents = str_replace( 'License URI: http://www.gnu.org/licenses/gpl-2.0.html', '', $contents );

		// --- include Markdown Readme Parser ---
		include $parser;
		$readme = new WordPress_Readme_Parser();
		$parsed = $readme->parse_readme_contents( $contents );

		// --- parse all the notices to get notice info ---
		if ( isset( $parsed['upgrade_notice'] ) ) {
			$notices = array();
			foreach ( $parsed['upgrade_notice'] as $version => $notice ) {
				if ( trim( $notice ) != '' ) {
					$id = str_replace( '.', '', $version );
					$notice = stream_player_parse_upgrade_notice( $notice );
					$notices[$id] = array(
						'id'      => $id,
						'version' => $version,
						'url'     => $notice['url'],
						'content' => $notice['content'],
						'lines'   => $notice['lines'],
					);
				}
			}
		}
	}

	return $notices;
}

// ---------------------
// AJAX Mark Notice Read
// ---------------------
add_action( 'wp_ajax_stream_player_notice_dismiss', 'stream_player_notice_dismiss' );
function stream_player_notice_dismiss() {
	if ( current_user_can( 'manage_options' ) || current_user_can( 'update_plugins' ) ) {
		if ( wp_verify_nonce( sanitize_text_field( $_REQUEST['nonce'] ), 'stream_player_notice' ) ) {
		
			if ( isset( $_GET['notice'] ) ) {

				$notice = absint( $_GET['notice'] );
				if ( $notice < 0 ) {
					return;
				}
				$notices = get_option( 'stream_player_read_notices' );
				if ( ! $notices ) {
					$notices = array();
				}
				$notices[$notice] = '1';
				update_option( 'stream_player_read_notices', $notices );
				// echo "<script>parent.document.getElementById('stream-player-notice-" . esc_js( $notice ) . "').style.display = 'none';</script>" . "\n";

			} elseif ( isset( $_GET['upgrade'] ) ) {

				$upgrade = absint( $_GET['upgrade'] );
				if ( $upgrade < 0 ) {
					return;
				}
				$upgrades = get_option( 'stream_player_read_upgrades' );
				if ( ! $upgrades ) {
					$upgrades = array();
				}
				$upgrades[$upgrade] = '1';
				update_option( 'stream_player_read_upgrades', $upgrades );
				// echo "<script>parent.document.getElementById('stream-player-update-" . esc_js( $upgrade ) . "').style.display = 'none';</script>" . "\n";

			}
		}
	}

	// --- send success data ---
	$success = array( 'success' => '1' );
	wp_send_json( $success , 200 );
}


// ---------------------
// === Admin Notices ===
// ---------------------

// --------------------------
// Admin Notice Action Iframe
// --------------------------
// 2.5.7: deprecated notice action iframe
/* function stream_player_admin_notice_iframe() {
	global $stream_player_notice_iframe;
	if ( !isset( $stream_player_notice_iframe ) || !$stream_player_notice_iframe ) {
		echo '<iframe src="javascript:void(0);" name="stream-player-notice-iframe" id="stream-player-notice-iframe" style="display:none;"></iframe>' . PHP_EOL;
		$stream_player_notice_iframe = true;
	}
} */

// -------------------------------
// Admin Notice Dismiss Javascript
// -------------------------------
function stream_player_dismiss_notice_javascript() {

	// --- once only output ---
	global $stream_player_notice_js;
	if ( isset( $stream_player_notice_iframe ) || $stream_player_notice_iframe ) {
		return;
	}
	$stream_player_notice_js = true;

	// --- dismiss notices javascript function ---
	$ajax_url = admin_url( 'admin-ajax.php' );
	$nonce = wp_create_nonce( 'stream_player_notice' );
	echo "<script>var stream_player_notice_nonce = '" . esc_js( $nonce ) . "';
	function stream_display_notice_dismiss(context,id) {
		if (context == 'offer') {
			url = '" . esc_url( $ajax_url ) . "?action=stream_player_launch_offer_dismiss&accept='+id+'&nonce='+stream_player_notice_nonce;
			jQuery.get(url, function(data) {
				document.getElementById('stream-player-launch-offer-notice').style.display = 'none';
			}
		} else if (context == 'notice') {
			url = '" . esc_url( $ajax_url ) . "?action=stream_player_notice_dismiss&notice='+id+'&nonce='+stream_player_notice_nonce;
			jQuery.get(url, function(data) {
				console.log(data); result = JSON.parse(data); console.log(id);
				document.getElementById('stream-player-notice-'+data.id+').style.display = 'none';
			}
		} else if (context == 'upgrade') {
			url = '" . esc_url( $ajax_url ) . "?action=stream_player_notice_dismiss&upgrade='+id+'&nonce='+stream_player_notice_nonce;
			jQuery.get(url, function(data) {
				console.log(data); result = JSON.parse(data); console.log(id);
				document.getElementById('stream-player-update-'+result.id+').style.display = 'none';
			}
		}
	}</script>" . "\n";
}

// ------------------------
// Plugin Settings Page Top
// ------------------------
add_action( 'stream_player_admin_page_top', 'stream_player_settings_page_top' );
function stream_player_settings_page_top() {

	// --- pro launch discount notice ---
	/* $now = time();
	$offer_start = strtotime( '2021-07-20 00:01' );
	$offer_end = strtotime( '2021-07-26 00:01' );
	if ( $now < $offer_end ) {
		$user_id = get_current_user_id();
		$user_ids = get_option( 'stream_player_launch_offer_accepted' );
		if ( !$user_ids || !is_array( $user_ids ) || !in_array( $user_id, $user_ids ) ) {
			$prelaunch = ( $now < $offer_start ) ? true : false;
			if ( isset( $_GET['offertest'] ) ) {
				$offertest = sanitize_title( $_GET['offertest'] );
				if ( '1' == $offertest ) {
					$prelaunch = false;
				} elseif ( '2' == $offertest ) {
					$prelaunch = true;
				}
			}
			stream_player_launch_offer_content( false, $prelaunch );
		}
	} */

	// --- plugin update notice ---
	stream_player_update_notice();
	echo '<br>' . "\n";
}

// ---------------------------
// Plugin Settings Page Bottom
// ---------------------------
add_action( 'stream_player_admin_page_bottom', 'stream_player_settings_page_bottom' );
function stream_player_settings_page_bottom() {
	// stream_player_mailchimp_form();
}

// -------------------
// Launch Offer Notice
// -------------------
// add_action( 'admin_notices', 'stream_player_launch_offer_notice' );
function stream_player_launch_offer_notice( $sppage = false ) {

	// --- bug out on certain plugin pages ---
	$pages = array( STREAM_PLAYER_SLUG, STREAM_PLAYER_SLUG . '-docs' );
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( isset( $_REQUEST['page'] ) && in_array( sanitize_text_field( $_REQUEST['page'] ), $pages ) ) {
		return;
	}

	// --- bug out if not admin ---
	if ( !current_user_can( 'manage_options' ) && !current_user_can( 'update_plugins' ) ) {
		return;
	}

	// --- check offer time window ---
	$now = time();
	$offer_start = strtotime( '2023-01-01 00:01' );
	$offer_end = strtotime( '2023-01-31 00:01' );
	if ( $now > $offer_end ) {
		return;
	}

	// --- bug out if already dismissed (by user) ---
	$user_id = get_current_user_id();
	$user_ids = get_option( 'stream_player_launch_offer_dismissed' );
	if ( $user_ids && is_array( $user_ids ) && in_array( $user_id, $user_ids ) ) {
		return;
	}

	// --- display plugin announcement ---
	echo '<div id="stream-player-launch-offer-notice" class="notice notice-success" style="position:relative;">' . "\n";
		$prelaunch = ( $now < $offer_start ) ? true : false;
		stream_player_launch_offer_content( true, $prelaunch );
	echo '</div>' . "\n";

	// --- notice dismissal frame (once) ---
	// 2.5.7: replace notice iframe with javascript
	// stream_player_admin_notice_iframe();
	stream_player_dismiss_notice_javascript();
	
}

// --------------------
// Launch Offer Content
// --------------------
function stream_player_launch_offer_content( $dismissable = true, $prelaunch = false ) {

	echo '<ul style="list-style:none;">' . "\n";

		// --- directory logo image ---
		$launch_image = plugins_url( 'images/pro-launch.gif', STREAM_PLAYER_FILE );
		echo '<li style="display:inline-block; vertical-align:middle;">' . "\n";
			echo '<img src="' . esc_url( $launch_image ) . '" style="width:128px; height:128px">' . "\n";
		echo '</li>' . "\n";

		// --- free listing offer text ---
		echo '<li style="display:inline-block; vertical-align:middle; margin-left:40px; font-size:16px; line-height:24px;">' . "\n";

		if ( $prelaunch ) {
			echo '<center><b style="font-size:18px;">' . esc_html( __( 'STream Player Pro Launch Discount!', 'stream-player' ) ) . '</b></center>' . "\n";
			echo '<p style="font-size: 16px; line-height: 24px; margin-top: 0;">' . "\n";
				echo esc_html( __( 'We are thrilled to announce the upcoming launch of Radio Station PRO', 'stream-player' ) ) . ' !!!<br>' . "\n";
				echo esc_html( __( 'Jam-packed with new features to "level up" your Station\'s online presence.', 'stream-player' ) ) . '<br>' . "\n";
				echo esc_html( __( 'During the launch,' ) ) . ' <b>' . esc_html( __( 'we are offering 30% discount to existing Stream Player users!', 'stream-player' ) ) . '</b><br>' . "\n";
				echo esc_html( __( 'Sign up to the exclusive launch list to receive your discount code when we go LIVE.', 'stream-player' ) ) . "\n";
			echo '</p>' . "\n";
		} else {
			echo '<center><b style="font-size:18px;">' . esc_html( __( 'Stream Player PRO Launch is LIVE!', 'stream-player' ) ) . '</b></center>' . "\n";
			echo '<p style="font-size: 16px; line-height: 24px; margin-top: 0;">' . "\n";
				echo esc_html( __( 'The long anticipated moment has arrived. The doors are open to get PRO', 'stream-player' ) ) . ' !!!<br>' . "\n";
				echo esc_html( __( 'Jam-packed with new features to "level up" your Station\'s online presence.', 'stream-player' ) ) . '<br>' . "\n";
				echo esc_html( __( 'Remember,' ) ) . ' <b>' . esc_html( __( 'we are offering 30% discount to existing Stream Player users!', 'stream-player' ) ) . '</b><br>' . "\n";
				echo '<a href="' . esc_url( STREAM_PLAYER_PRO_URL ) . 'plugin-launch-discount/" target="_blank">' . "\n";
					echo esc_html( __( 'Sign up here to receive your exclusive launch discount code.', 'stream-player' ) ) . "\n";
				echo '</a>' . "\n";
			echo '</p>' . "\n";
		}

		echo '</li>' . "\n";

		// --- accept / decline offer button links ---
		echo '<li style="display:inline-block; vertical-align:middle; margin-left:40px; font-size:16px; line-height:24px;">' . "\n";
		echo '<center>' . "\n";
		echo '<div id="launch-offer-accept-button" style="display:inline-block; margin-right:10px;">' . "\n";
		if ( $prelaunch ) {
			echo '<a href="' . esc_url( STREAM_PLAYER_PRO_URL ) . 'plugin-launch-discount/" style="font-size: 16px;" target="_blank" class="button-primary"';
			if ( $dismissable ) {
				echo ' onclick="stream_player_dismiss_notice(\'launch\',1);"';
			}
			echo '>' . esc_html( __( "Yes, I'm in!", 'stream-player' ) ) . '</a>' . "\n";
		} else {
			echo '<a href="' . esc_url( STREAM_PLAYER_PRO_URL ) . 'pricing/" style="font-size: 16px;" target="_blank" class="button-primary"';
			if ( $dismissable ) {
				echo ' onclick="stream_player_dismiss_notice(\'launch\',1);"';
			}
			echo '>' . esc_html( __( 'Go PRO', 'stream-player' ) ) . '</a>' . "\n";
		}
		echo '</div>' . "\n";

		echo '<div id="launch-offer-dismiss-link" style="display:none;">' . "\n";
			// $accept_dismiss_url = add_query_arg( 'accepted', '1', $dismiss_url );
			// echo '<a href="' . esc_url( $accept_dismiss_url ) . '" style="font-size: 12px;" target="stream-player-notice-iframe">' . esc_html( __( 'Thanks, already done.', 'stream-player' ) ) . '</a>' . "\n";
			echo '<a href="#" style="font-size: 12px;" onclick="stream_player_dismiss_notice(\'launch\',1);">' . esc_html( __( 'Thanks, already done.', 'stream-player' ) ) . '</a>' . "\n";
		echo '</div>' . "\n";
		echo '</center><br>' . "\n";

		echo '</li>' . "\n";

	echo '</ul>' . "\n";

	// --- dismiss notice icon ---
	if ( $dismissable ) {
		echo '<div style="position:absolute; top:20px; right: 20px;">' . "\n";
			// $dismiss_url = admin_url( 'admin-ajax.php?action=stream_player_launch_offer_dismiss' );
			// echo '<a href="' . esc_url( $dismiss_url ) . '" target="stream-player-notice-iframe" style="text-decoration:none;">' . "\n";
			echo '<a href="#" onclick="stream_player_dismiss_notice(\'launch\',0);" style="text-decoration:none;">' . "\n";
				echo '<span class="dashicons dashicons-dismiss" title="' . esc_html( __( 'Dismiss this Notice', 'stream-player' ) ) . '"></span>' . "\n";
			echo '</a>' . "\n";
		echo '</div>' . "\n";
	}

}

// --------------------
// Dismiss Launch Offer
// --------------------
add_action( 'wp_ajax_stream_player_launch_offer_dismiss', 'stream_player_launch_offer_dismiss' );
function stream_player_launch_offer_dismiss() {

	// --- bug out if no permissions ---
	if ( !current_user_can( 'manage_options' ) && !current_user_can( 'update_plugins' ) ) {
		exit;
	}

	if ( !wp_verify_nonce( sanitize_text_field( $_REQUEST['nonce'] ), 'stream_player_notice' ) ) {
		exit;
	}

	// --- get current user ID ---
	$user_id = get_current_user_id();

	// --- set option to dismissed ---
	$user_ids = get_option( 'stream_player_launch_offer_dismissed' );
	if ( !$user_ids || !is_array( $user_ids ) ) {
		$user_ids = array( $user_id );
	} elseif ( !in_array( $user_id, $user_ids ) ) {
		$user_ids[] = $user_id;
	}
	update_option( 'stream_player_launch_offer_dismissed', $user_ids );

	// --- maybe set option for accepted ---
	$accepted = 0;
	if ( isset( $_REQUEST['accept'] ) && ( '1' == sanitize_text_field( $_REQUEST['accepted'] ) ) ) {
		$accepted = 1;
		$user_ids = get_option( 'stream_player_launch_offer_accepted' );
		if ( !$user_ids || !is_array( $user_ids ) ) {
			$user_ids = array( $user_id );
		} elseif ( !in_array( $user_id, $user_ids ) ) {
			$user_ids[] = $user_id;
		}
		update_option( 'stream_player_launch_offer_accepted', $user_ids );
	}

	// --- hide the announcement in parent frame ---
	// echo "<script>parent.document.getElementById('stream-player-launch-offer-notice').style.display = 'none';</script>" . "\n";
	// exit;

	// --- send success data ---
	$success = array( 'success' => '1', 'accepted' => $accepted );
	wp_send_json( $success , 200 );
}

// -------------------------
// MailChimp Subscriber Form
// -------------------------
function stream_player_mailchimp_form() {

	// --- get current user email ---
	$current_user = wp_get_current_user();
	$user_email = $current_user->user_email;

	// --- bug out if already subscribed ---
	$subscribed = get_option( 'stream_player_subscribed' );
	if ( $subscribed && is_array( $subscribed ) && in_array( $user_email, $subscribed ) ) {
		return;
	}

	// --- enqueue MailChimp form styles ---
	// TODO: update mailchimp styles
	// $version = filemtime( STREAM_PLAYER_DIR . '/css/rs-mailchimp.css' );
	// $url = plugins_url( 'css/rs-mailchimp.css', RADIO_STATION_FILE );
	// wp_enqueue_style( 'rs-mailchimp', $url, array(), $version, 'all' );

	// --- set plugin icon URL ---
	$icon = plugins_url( 'images/stream-player-icon.png', STREAM_PLAYER_FILE );

	// --- output MailChimp signup form ---
	// TODO: update list action for stream player mailchimp list
	// 2.5.7: added translation wrapper to your email address placeholder
	?>

	<div id="mc_embed_signup">
		<form action="" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
			<div style="position: absolute; left: -5000px;" aria-hidden="true">
				<input type="text" name="b_c53a6feec82d81974edd00a95_7130454f20" tabindex="-1" value="">
			</div>
			<div id="mc_embed_signup_scroll">
				<div id="plugin-icon">
					<img src="<?php echo esc_url( $icon ); ?>" alt='<?php echo esc_html( __( 'Stream Player', 'stream-player' ) ); ?>'>
				</div>
				<label id="signup-label" for="mce-EMAIL"><?php echo esc_html( __( "Stay tuned! Subscribe to Stream Player's", 'stream-player' ) ); ?>
					<br>
					<?php echo esc_html( __( 'Plugin Updates and Announcements List', 'stream-player' ) ); ?></label>
				<input type="email" name="EMAIL" class="email" id="mce-EMAIL" value="<?php echo esc_html( $user_email ); ?>" placeholder="<?php echo esc_attr( __( 'Your email address', 'stream-player' ) ); ?>" required>
				<div class="subscribe">
					<input type="button" value="Subscribe" name="subscribe" id="mc-embedded-button" class="button">
				</div>
			</div>
		</form>
	</div>

	<?php

	// 2.6.7: removed iframe (no longer used)
	// echo '<iframe id="mc-subscribe-record" src="javascript:void(0);" style="display:none;"></iframe>' . "\n";

	// --- AJAX subscription call ---
	// 2.3.0: added to record subscribers
	// 2.5.7: modified to use AJAX via jquery instead of iframe
	// 2.5.10: added nonce field to submit URL
	echo "<script>
	jQuery(document).ready(function() {
		var sp_sub_nonce = '" . esc_js( wp_create_nonce( 'stream_player_subscribe' ) ) . "';
		jQuery('#mc-embedded-button').on('click', function(e) {
			email = document.getElementById('mce-EMAIL').value;
			url = '" . esc_url( admin_url( 'admin-ajax.php' ) ) . "&action=stream_player_record_subscribe&nonce='+sp_sub_nonce+'&email='+encodeURIComponent(email);
			jQuery.get(url, function(data) {
				console.log('Subscribe Response:'); console.log(data);
				if (data.success) {jQuery('#mc-embedded-subscribe-form').submit();}
			}
		});
	});</script>" . "\n";

}

// ---------------------
// AJAX Record Subcriber
// ---------------------
add_action( 'wp_ajax_stream_player_record_subscribe', 'stream_player_record_subscribe' );
function stream_player_record_subscribe() {

	if ( !wp_verify_nonce( $_REQUEST['nonce'], 'stream_player_subscribe' ) ) {
		$response = array( 'success' => '0' );
	} else {

		$email = sanitize_email( $_GET['email'] );
		$subscribed = get_option( 'stream_player_subscribed' );
		if ( !$subscribed || !is_array( $subscribed ) ) {
			add_option( 'stream_player_subscribed', array( $email ) );
		} else {
			$subscribed[] = $email;
			update_option( 'stream_player_subscribed', $subscribed );
		}

		// --- submit form in parent window ---
		// echo "<script>console.log('Subscription Recorded');";
		// echo "parent.jQuery('#mc-embedded-subscribe-form').submit();</script>" . "\n";
		// exit;
		// 2.6.7: just return success JSON data
		$response = array( 'success' => '1' );
	}

	wp_send_json( $response , 200 );
}

// ------------------
// AJAX Clear Notices
// ------------------
// (for manual use in development testing)
add_action( 'wp_ajax_stream_player_clear_option', 'stream_player_clear_plugin_options' );
function stream_player_clear_plugin_options() {

	if ( !current_user_can( 'manage_options' ) ) {
		return;
	}

	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( isset( $_GET['option'] ) ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$option = sanitize_text_field( $_GET['option'] );
		if ( 'subscribed' == $option ) {
			delete_option( 'stream_player_subscribed' );
		} elseif ( 'notices' == $option ) {
			delete_option( 'stream_player_read_notices' );
		} elseif ( 'upgrades' == $option ) {
			delete_option( 'stream_player_read_upgrades' );
		}
	}

	exit;
}

