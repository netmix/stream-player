# Stream Player Plugin Filters

***

## Settings Filters

To programmatically override any of the Plugin Settings available from the Settings Page, see [Options Documentation](./Options.md) 

## Data Filters

There are filters throughout the plugin that allow you to override data values and plugin output. We employ the practice of adding as many of these as possible to allow users of the plugin to customize it's behaviour without needing to modify the plugin's code - as these kind of modifications are overwritten with plugin updates.

You can add your own custom filters via a Code Snippets plugin (which has the advantage of checking syntax for you), or in your Child Theme's `functions.php`, or in any file with a PHP extension in your `/wp-content/mu-plugins/` directory. 

## Finding Filters

You can find these filters by searching any of the PHP plugin files for: `apply_filters(`

## Filter Values and Arguments

Note the first argument passed to `apply_filters` is the name of the filter, the second argument is the value to be filtered. Additional arguments may also be provided to the filter so that you can match changes to specific contexts.

## Filter Examples

You can find many examples and tutorials of how to use WordPress filters online. Here is a generic filter example to help you get started with filters. This one will add custom HTML to the bottom of the Player Widget:

```
add_filter( 'stream_player_???', 'my_custom_function_name' );
function my_custom_function_name( $html ) {
    $html .= "<div>Now taking phone requests!</div>";
    return $html;
}
```

Note if a filter has additional arguments, and you wish to check them, you need to specify the number of arguments. To do this you must also include a filter priority. Here `10` is the (default) priority of when to run the filter and `3` is the number of arguments passed to the filter function. This example will add custom HTML to the bottom of the Current Show widget only if the Show ID is 20:

```
add_filter( 'stream_player_???', 'my_custom_function_name', 10, 3 );
function my_custom_function_name( $html, $args, $atts ) {
    if ( 20 == $show_id ) {
        $html .= "<div>Now taking phone requests!</div>";
    }
    return $html;
}
```

## Filter List

Here is a full list of available filters within the plugin, grouped by file and function for ease of reference. 

| File / *Function* | Filter | Value | Extra Args |
| - | - |
|**player/stream-player.php**||||
|*stream_player_output*|`stream_player_output_args` | ` $args` | `$instance`|
| |`stream_player_station_image_tag` | ` $image` | `$args['image']`, `$args`, `$instance`|
|*stream_player_shortcode*|`stream_player_section_order` | ` $section_order` | `$args`|
| |`stream_player_control_order` | ` $control_order` | `$args`, `$instance`|
| |`stream_player_station_text_alt` | ` $station_text_alt` | `$args`, `$instance`|
| |`stream_player_show_text_alt` | ` $show_text_alt` | `$args`, `$instance`|
| |`stream_player_html` | ` $player` | `$args`, `$instance`|
| |`stream_player_default_title_display` | ` $title` | |
| |`stream_player_default_image_display` | ` $image` | |
| |`stream_player_default_script` | ` $script` | |
| |`stream_player_default_layout` | ` $layout` | |
| |`stream_player_default_volume` | ` $volume` | |
| |`stream_player_default_theme` | ` $theme` | |
| |`stream_player_default_buttons` | ` $buttons` | |
| |`stream_player_shortcode_attributes` | ` $atts` | |
| |`stream_player_default_title` | ` ''` | |
| |`stream_player_default_image` | ` ''` | |
|*stream_player_ajax*|`stream_player_output` | ` $override` | `$atts`|
| |`stream_player_atts` | ` $atts` | |
| |`stream_player_mediaelements_interface` | ` $html` | `$atts`, `$post_id`|
|*stream_player_enqueue_script*|`stream_player_pageload_script` | ` ''` | |
| |`stream_player_scripts` | ` $js` | |
| |`stream_player_fallbacks` | ` $fallbacks` | |
|*stream_player_enqueue_mediaelements*|`stream_player_mediaelement_settings` | ` $player_settings` | |
|*stream_player_script*|`stream_player_save_interval` | ` $save_interval` | |
| |`stream_player_jplayer_swf_path` | ` ''` | |
| |`stream_player_title` | ` $player_title` | |
| |`stream_player_image` | ` $player_image` | |
| |`stream_player_volume` | ` $player_volume ) )` | |
| |`stream_player_single` | ` $player_single` | |
| |`stream_player_fallbacks` | ` $fallbacks` | |
| |`stream_player_debug` | ` $debug` | |
|*stream_player_iframe*|`stream_player_data` | ` false` | `$station`|
|*stream_player_script_howler*|`stream_player_script_amplitude` | ` $js` | |
| |`stream_player_script_howler` | ` $js` | |
| |`stream_player_script_jplayer` | ` $js` | |


## [Pro] Pro Filter List

Below is a list of filters that are available within [Radio Station Pro](https://radiostation.pro).

| File / *Function* | Filter | Value | Extra Args |
| - | - |

