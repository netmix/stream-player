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

#### Display Station Title
Default: On. key: player_title

#### Channel Image
Default: None. Key: station_image

#### Display Channel Image
Default: On. key: player_image


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

#### Single Player
Default: on. Key: player_single
Stop any existing Player instances on the page or in other windows or tabs when a Player is started.

#### [Pro] Player Autoresume
Default: on. Key: player_autoresume
Attempt to resume playback if visitor was playing. Only triggered when the user first interacts with the page.

#### [Pro] Player Popup
Default: off. Key: player_popup
Add button to open Popup Player in separate window.


## Player Bar

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

