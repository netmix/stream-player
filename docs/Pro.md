# Stream Player Pro

***

[Stream Player Pro](https://radiostation.pro/stream-player/) unlocks a full set of advanced features for broadcasters who want a professional listener experience. Free version settings that are grayed out in the Plugin Settings screen are Pro-only — they are shown so you can see what's available, but they will not activate until you upgrade.

[**Upgrade to Stream Player Pro →**](https://radiostation.pro/stream-player/)

---

## What's Included in Pro

### Sitewide Bar Player

A fixed Player Bar displayed across every page of your site — pinned to the top or bottom of the browser window, unaffected by scrolling. Configured via **Plugin Settings → Player → Player Bar**.

Key Bar Player settings (all Pro):

| Setting | Description |
|---|---|
| Sitewide Player Bar | Enable/disable the bar. Fixed top or bottom position. |
| Bar Height | Height in pixels. 80px recommended minimum. |
| Fade In | Milliseconds to fade in the bar after page load. |
| Text Color | Bar player text color. |
| Background Color | Bar player background color. |
| Responsive Display | Adapts to mobile screen sizes automatically. |

→ See [Player Bar options](./Options.md#player-bar) for the full list.

---

### Continuous Playback

Keeps audio playing without interruption while visitors navigate between pages on your site. Pages are loaded silently in the background and faded in while the Player Bar persists. Powered by the integrated [Teleporter](./Player.md#pro-continous-player-integration) page transition plugin.

Key settings:

| Setting | Description |
|---|---|
| Continuous Playback | Enable uninterrupted playback between pages. |
| Player Page Fade | Milliseconds to fade between pages (0 = instant). |
| Page Load Timeout | Milliseconds to wait for a page before fading in anyway. |

> **Note:** Continuous playback uses click-event listeners on `<a>` tags. Dynamically generated links (e.g. mobile menus, AJAX content) need extra configuration. See [Continuous Player Integration](./Player.md#pro-continous-player-integration) for the `teleporter_dynamic_link_classes` filter.
>
> Elementor page transition animations must be disabled (set both before and after animations to None) to avoid conflicts.

---

### Now Playing / Track Metadata

Displays the currently playing track title in the Player Bar, pulled from your stream's Icecast/Shoutcast metadata. Supports scrolling text animation.

| Setting | Description |
|---|---|
| Bar Player Now Playing | Show/hide the Now Playing display. |
| Bar Player Track Animation | Animation style: none, lefttoright, righttoleft, backandforth. |
| Bar Player Metadata Source | Optional alternative URL for metadata retrieval. |

---

### Playback Autoresume

Attempts to automatically resume playback for returning visitors who were previously listening. Triggered only after the visitor first interacts with the page, in compliance with browser autoplay policies.

---

### Popup Player Window

Adds a button to open the player in a small, separate browser window — letting listeners keep audio going while they navigate away from your site entirely.

---

### Color Themes & Styling

Pro unlocks a full range of color controls for both the inline player and the Player Bar:

**Inline Player Colors** (Plugin Settings → Player → Player Colors):

- Playing Highlight Color — Play button icon color when playing
- Controls Highlight Color — Volume and control button active color
- Volume Knob Color — Slider thumb color
- Volume Track Color — Slider track color

**Button Themes** — In addition to Light and Dark, choose from: Red, Orange, Yellow, Light Green, Green, Cyan, Light Blue, Blue, Purple, or Magenta. Matching play/pause and volume control images are applied automatically.

**Bar Player Colors** (Plugin Settings → Player → Player Bar):

- Bar Player Text Color
- Bar Player Background Color

---

### Extra Widget, Shortcode & Block Options

Pro adds color and behavior options to every individual Player instance (widget, shortcode, Gutenberg block):

```
[radio-player text_color="#ffffff" background_color="#000000" nowplaying="1" animation="backandforth"]
```

See [Extra Widget and Shortcode Options](./Player.md#pro-extra-widget-and-shortcode-options) for the full attribute list.

---

### Elementor Widget & Beaver Builder Module

Pro includes native drag-and-drop Player widgets for:

- **Elementor** — found in the Stream Player category of the Elements panel
- **Beaver Builder** — found in the Stream Player category of the Modules panel

Both include color and typography options in their Style tabs, with live preview on change.

---

## How to Upgrade

1. Visit [radiostation.pro/stream-player](https://radiostation.pro/stream-player/)
2. Purchase a Stream Player Pro license
3. Download the Pro plugin zip from your account
4. In WordPress Admin, go to **Plugins → Add New → Upload Plugin** and upload the Pro zip
5. Activate — your existing settings are preserved and all Pro options unlock immediately

Once active, the grayed-out settings in Plugin Settings will become fully functional.

---

## Pro Feature Index

For inline documentation within the free version docs, Pro features are marked with `[Pro]`:

### Player Settings

- [Pro Player Colors](./Options.md#player-colors)
- [Pro Player Autoresume](./Options.md#advanced-defaults)
- [Pro Player Popup](./Options.md#advanced-defaults)

### Player Bar Settings (all Pro)

- [Bar Display options](./Options.md#player-bar)
- [Persistent Player / Continuous Playback](./Options.md#persistent-player)
- [Metadata Display](./Options.md#metadata-display)

### Player Features

- [Sitewide Bar Player](./Player.md#pro-sitewide-bar-player)
- [Responsive Bar Display](./Player.md#pro-responsive-bar-display)
- [Continuous Player Integration](./Player.md#pro-continous-player-integration)
- [Smooth Page Transitions](./Player.md#pro-smooth-page-transitions)
- [Extra Bar Color Options](./Player.md#pro-extra-bar-color-options)
- [Playback Autoresume](./Player.md#pro-playback-autoresume)
- [Popup Player Window](./Player.md#pro-popup-player-window)
- [Track Metadata](./Player.md#pro-track-metadata)

### Widgets & Blocks

- [Extra Widget and Shortcode Options](./Player.md#pro-extra-widget-and-shortcode-options)
- [Extra Block Options](./Player.md#pro-extra-block-options)
- [Elementor Widget](./Player.md#pro-elementor-widget)
- [Beaver Builder Module](./Player.md#pro-beaver-builder-module)
