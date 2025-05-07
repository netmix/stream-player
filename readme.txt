=== Stream Player by netmix® - Streaming audio for WordPress! ===
Contributors: tonyzeoli, majick
Donate link: https://netmix.org
Tags: player, stream, audio, radio, broadcast
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Requires at least: 4.0
Tested up to: 6.7
Stable tag: 2.5.12

Free, open source streaming audio player plugin by netmix®. Works with Icecast, Shoutcast, and Live 365 streams. For additional features, upgrade to Stream Player PRO.

== Description ==

#### STREAM PLAYER by netmix®† - the best streaming audio plugin for WordPress

[Live Demo](https://demo.streamplayer.pro/) |
[Documentation](https://radiostation.pro/docs/) | [Support](https://wordpress.org/support/plugin/stream-player/) | [**Upgrade to Stream Player PRO**!](https://radiostation.pro/pricing/)

This plugin can be downloaded for free without any paid subscription from the [official WordPress repository](https://wordpress.org/plugins/stream-player/).

**Stream Player** *by netmix®* is a simple, powerful livestream audio player plugin that can be used by radio stations, broadcasters, social audio moderators, news organizations, or anyone who wants to broadcast their live audio stream on a WordPress website.  [Upgrade to Stream Player Pro](https://streamplayer.pro/pricing/)

Stream Player by netmix® is included in our free, open-source [WordPress plugin, Radio Station](https://wordpress.org/plugins/radio-station/).

#### STREAM PLAYER BY netmix® FEATURES

- Works with virtually any Stream source
- Compatible with Shoutcast, Icecast, Live365, and Radio.co
- 3 In-built Audio Scripts: Howler, Amplitude, jPlayer
- 7 Streaming Formats Support: MP3, AAC/M4A, OGG, OGA, WebM, RTMPA, OPUS
- Source and Fallback Streaming URLs
- Volume Controls: Volume Slider, Volume +/-, Mute Volume Toggle, Maximize Volume, Player Start Volume
- Single Player at Once: Stop any existing Players on the page or in other windows or tabs when a Player is started.
- Stream Title and Image
- Added via Shortcode, Widget or Block

#### Upgrade to STREAM PLAYER PRO BY netmix®

**Stream Player PRO** *by netmix* is our persistent ("sticky") audio player bar, which gives you uninterrupted streaming like you see on Spotify, Soundcloud, Mixcloud, and other streaming services and radio networks. The player bar can be affixed to the header or footer of your website and listeners can enjoy uninterrupted streaming.

We broke out Stream Player PRO by netmix® from its parent plugin, **Radio Station PRO** *by netmix®*, our flagship WordPress plugin to manage and publish your streaming station's show schedule to a WordPress website. Stream Player PRO by netmix® is included in Radio Station PRO to give stations and streamers a full suite of tools to both broadcast and manage content related to Shows, Episodes, Hosts and Producers, Guests, and Playlists. You can also associate blog posts with specific Shows.

#### STREAM PLAYER PRO BY netmix® FEATURES

- Persistent Player Bar
- Smoother Page Transitions
- Supports Shoutcast, Icecast, Live 365, and more
- Animated Track Meta Data
- Color Themes and Styling Options
- Single Broadcast Point Source
- Wide audio format support, including MP3, M4A, OGG, FLAC, WAV, and more
- Stream Title and Image
- Added via Shortcode, Widget or Block

[Upgrade to Stream Player Pro](https://radiostation.pro/pricing/)

== Installation ==

1. Upload plugin .zip file to the `/wp-content/plugins/` directory and unzip.
2. Activate the plugin through the 'Plugins' menu in the WordPress Admin
3. Alternatively search for Stream Player via the WordPress admin Add New plugin interface and install and activate it there.
4. Visit the plugin settings panel to set your default Stream and player settings.

== Frequently Asked Questions ==

= Where can I find the full Stream Player documentation (Free or PRO)? =

The latest documentation [can be found online here](https://streamplayer.pro/docs/). Documentation is also included with the currently installed version via the Stream Player Help menu item located under the Stream Player admin menu. You can find the Markdown-formatted files in the `/docs` folder of the [GitHub Repository](https://github.com/netmix/stream-player/docs/) and in the `/docs` folder of the plugin directory. 

= How do I get support for Stream Player (Free or PRO)? =

For Stream Player customers using the free, open-source version of our plugin, you can contact us via [our support channel in the WordPress support forums here](https://wordpress.org/plugins/support/stream-player). If you have any bug reports or feature suggestions please [open an issue on our Github repository](https://github.com/netmix/stream-player/) For Stream Player PRO subscribers, you can email us at support@radiostation.pro and someone will respond to your inquiry within 12 to 24 hours. All support inquiries will be handled in the order they are received. Before contacting support or opening an issue, make sure you check for conflicts by disabling all of your plugins and re-enabling them one at a time to ascertain which plugin is conflicting with Stream Player. Note that Stream Player PRO works as an addon to Stream Player, so deactivating it will disable the PRO features until you reactivate it.

= Does this plugin use any 3rd party services? =

Stream Player uses the Amplitude and jPlayer scripts to play audio. In the that case you are connecting the player to a Soundcloud stream URL, Amplitude may load the latest Soundcloud SDK automatically via connect.soundcloud.com ... Soundcloud's terms of service are available at [here](https://soundcloud.com/terms-of-use).


== Screenshots ==

1. Settings: Stream
2. Settings: Player
3. Settings: Bar
4. Player Block/Widget

== Changelog ==

= 2.5.12 =
* Fixed: Player Block rendering function

= 2.5.11 =
* Fixed: duplicate function conflict stream_player_add_inline_script

= 2.5.9.10 =
* Removed: load_plugin_textdomain function call
* Removed: radio_player_validate_boolean function
* Removed: standalone player script/style tag output

= 2.5.9.9 =
* Fixed: settings page script enqueueing

= 2.5.9.8 =
* Updated: Freemius SDK (2.11.0)
* Updated: plugin review update changes

= 2.5.9.7 =
* Updated: Plugin Panel (1.3.4)

= 2.5.9.6 = 
* Updated: Freemius SDK (2.9.0)
* Fixed: Freemius optin image URL path

= 2.5.9.3 =
* Updated: Freemius SDK (2.8.1)
* Updated: reader.php with prefixed reader functions
* Updated: Plugin Panel (1.3.1) with new reader function
* Updated: Color Picker Alpha Library (3.0.4)
* Updated: Howler Library (2.2.4)
* Improved: use wp_kses on player widget output

= 2.5.9.2 =
* Updated: Freemius SDK (2.8.0)
* Updated: reader.php with prefixed reader functions
* Updated: Plugin Panel (1.3.1) with new reader function
* Updated: Color Picker Alpha Library (3.0.4)
* Updated: Howler Library (2.2.4)
* Improved: use wp_kses on player widget output

= 2.5.9 =
* Updated: Freemius SDK (2.6.2)

= 2.5.7 =
* Updated: Freemius SDK (2.6.0)
* Disabled: Howler Player Script (browser compatibility issues)

= 2.5.6 =
* Updated: Freemius SDK (2.5.11)
* Updated: Plugin Panel (1.3.0)
* Improved: more consistent sanitization and escaping
* Fixed: automatic use of default Stream settings

= 2.5.5 =
* Updated: Freemius SDK (2.5.10)
* Fixed: Prefix Block element JS constant to prevent conflicts

= 2.5.4 =
* Updated: Freemius SDK (2.5.9)

= 2.5.1 =
* Fixed: Pro Player Backwards Compatibility

= 2.5.0 =
* Added: Radio Station Blocks! (converted Widgets)
* Updated: Freemius SDK (2.5.7)
* Updated: Plugin Panel (1.2.9)
* Updated: AmplitudeJS (5.3.2)
* Updated: Howler (2.2.3)
* Improved: Redesigned higher resolution player buttons
* Improved: Standardized Widget Input Fields
* Improved: WordPress Coding Standards
* Improved: Sanitization using KSES
* Improved: Translation Implementation
* Added: Freemius Pricing Page v2
* Added: Volume Control options to Player widget
* Fixed: Radio Player iOS no volume control detection
* Fixed: Mobile detection (via any pointer type)
* Fixed: Workaround Amplitude pause event not firing
* Security Fix: Escape all debug output content

= 2.4.0.9 =
* Update: Sysend (1.11.1) for Radio Player

= 2.4.0.4 =
* Fixed: Fallback scripts and fallback stream URLs

= 2.4.0.3 =
* Update: Plugin Panel (1.2.1) with zero value save and tab fixes
* Added: option to disable player audio fallback scripts
* Added: option to hide various volume controls
* Improved: lazy load player audio fallback scripts
* Fixed: player volume slider background position (cross-browser)
* Fixed: Fallback scripts and fallback stream URLs

= 2.4.0.2 =
* Fixed: Multiple Player instance IDs
* Fixed: Player loading button glow animation
* Added: Enabled Pro Pricing plans page
* Added: Alternative text positions in Player
* Added: Pause button graphics to Player

= 2.4.0.1 =
* Fixed: Rounded player play button background corner style

= 2.4.0 =
* Added: Radio Stream Player!
