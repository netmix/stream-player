# Stream Player Plugin Options

***

Plugin Settings are stored in an array under the `stream_player` key in the WordPress options table.

Below is a list of plugin options available via the Plugin Settings Screen.


### Plugin Setting Value Filters

Note for custom flexibility, all Plugin Settings can also be filtered programmatically via their respective option key. Use `add_filter` to add a filter to `stream_player_{settings_key}`, then check your desired conditions to modify the value before returning it. eg: 

```
add_filter( 'stream_player_station_title', 'my_custom_station_title' );
function my_custom_station_phone( $title ) {
	$current_hour = (int) date( 'G', time() );
	if ( ( $current_hour > 22 ) && ( $current_hour < 6 ) ) {
		$title .= ' [Offline until 6am]';
	}
	return $title;
}
```

The above example will change the display of the Channel Title between 10pm and 6am (server time).


## General 

### Broadcast

#### Streaming URL
Default: None. Key: streaming_format
Enter the Streaming URL for your Stream Player.

#### Stream Format
Default: AAC/M4A. Key: streaming_format
Select the format for your stream.

#### Fallback URL
Default: None. Key: fallback_url
Enter the fallback Streaming URL for your Stream Player.

#### Streaming URL
Default: OGG. Key: fallback_format
Select the format for your fallback stream. 

### Channel

#### Channel Title
Default: None. Key: station_title

#### [Pro] Display Stream Title
Default: On. Key: player_title
Display your Stream Title in the Player by default. In the free version players show no stream title.

#### Channel Image
Default: None. Key: station_image

#### [Pro] Display Stream Image
Default: On. Key: player_image
Display your Stream Image in the Player by default. In the free version players show no stream image.

#### [Pro] Player Meta Display
Default: tagline. Key: player_meta
Display your Stream Tagline, Location and/or Timezone in the Player by default. In the free version players show no stream meta.


## Player

### Basic Defaults

#### Player Script
Default: amplitude. Key: player_script
Default audio script to use for Radio Streaming Player. Ampliture, Howler and Jplayer.

#### Player Theme
Default: light. Key: player_theme
Default Player Controls theme style. Light or dark to match your theme.

#### Player Buttons
Default: rounded. Key: player_buttons
Default Player Buttons shape style. Circular, rounded or square.

#### Player Volume Controls
Default: all. Key: player_volumes
Which volume controls to display in the Player by default.

#### Player Debug Mode
Default: off. Key: player_debug
Output player debug information in browser javascript console.

### Player Colors

#### [Pro] Playing Highlight Color
Default: #70E070. Key: player_playing_color
Default highlight color to use for Play button icon when playing.

#### [Pro] Controls Highlight Color
Default: #00A0E0. Key: player_buttons_color
Default highlight color to use for player Control button icons when active.

#### [Pro] Volume Knob Color
Default: #80C080. Key: player_thumb_color
Default Knob Color for Player Volume Slider.

#### [Pro] Volume Track Color
Default: #80C080. Key: player_range_color
Default Track Color for Player Volume Slider.

### Advanced Defaults

#### Player Start Volume
Default: 77. Key: player_volume
Initial volume for when the Player starts playback. 0-100

#### Single Player at Once
Default: on. Key: player_single
Stop any existing Player instances on the page or in other windows or tabs when a Player is started. Players on the same page always switch with one click (see [How Players Work Together](./Player.md#how-players-work-together).)

#### [Pro] Player Autoresume
Default: off. Key: player_autoresume
On return to the site or page reload, ask the visitor in a small dialog whether to resume stream playback if they were playing the stream before.

#### [Pro] Player Popup
Default: off. Key: player_popup
Add button to open Popup Player in separate window. (See [Popup Player Window](./Player.md#pro-popup-player-window).)


## Player Bar

#### Enable Player Bar
Default: off. Key: player_bar_active
Show a basic fixed Player Bar at the bottom of every page that plays your Stream URL, with play and volume controls only. (See [Basic Player Bar](./Player.md#basic-player-bar).) Hidden when Stream Player Pro is active, as the Pro Sitewide Player Bar replaces it.

> **🔒 The settings below are Pro features.**
> They are available with [Stream Player Pro](https://radiostation.pro/stream-player/). On the free version, these options are visible in the settings screen but grayed out. Upgrade to Pro to unlock the Sitewide Bar Player, continuous playback, metadata display, and all color and transition options.

### [Pro] Bar Display

#### [Pro] Sitewide Player Bar
Default: off. Key: player_bar
Add a fixed position Player Bar which displays Sitewide. Fixed top or bottom position.

#### [Pro] Player Bar Height
Default: 80. Key: player_bar_height
Height in pixels for the Player Bar. 80px is the recommended minimum.

#### [Pro] Fade In Player Bar
Default: 2500. Key: player_bar_fadein
Number of milliseconds after Page load over which to fade in Player Bar. Use 0 for instant display

#### [Pro] Bar Player Text Color
Default: #FFFFFF. Key: player_bar_text
Text color for the fixed position Sitewide Bar Player.

#### [Pro] Bar Player Background Color
Default: black. Key: player_bar_background
Background color for the fixed position Sitewide Bar Player.

### Persistent Player

#### [Pro] Continuous Playback
Default: on. Key: player_bar_continuous
Uninterrupted Sitewide Bar playback while user is navigating between pages! Pages are loaded in background and faded in while Player Bar persists.

#### [Pro] Player Page Fade
Default: 2000. Key: player_bar_pagefade
Number of milliseconds over which to fade in new Pages when continuous playback is enabled. Use 0 for instant display.

#### [Pro] Page Load Timeout
Default: 7000. Key: player_bar_timeout
Number of milliseconds to wait for new Page to load before fading in anyway when continuous playback is enabled.

### Metadata Display

#### [Pro] Bar Player Now Playing
Default: on. Key: player_bar_nowplaying
Display the Now Playing Track metadata in the Player Bar.

#### [Pro] Bar Player Track Animation
Default: backandforth. Key: player_bar_track_animation
How to animate the currently playing track display.

#### [Pro] Bar Player Metadata Source
Default: none (use Stream URL.) Key: player_bar_metadata
Alternative metadata source URL for Now Playing Track metadata.

