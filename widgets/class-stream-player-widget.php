<?php

// ---------------------
// === Player Widget ===
// ---------------------

// -------------
// Player Widget
// -------------
class Radio_Player_Widget extends WP_Widget {

	// --- construct widget ---
	public function __construct() {
		$widget_ops = array(
			'classname'   => 'Radio_Player_Widget',
			'description' => __( 'Stream Player Stream Player.', 'stream-player' ),
		);
		$widget_display_name = __( '(Stream Player) Stream Player', 'stream-player' );
		parent::__construct( 'Radio_Player_Widget', $widget_display_name, $widget_ops );
	}

	// --- widget instance form ---
	public function form( $instance ) {

		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );

		// 2.5.0: fix player image default value to 'default'
		// $media = isset( $instance['media'] ? $instance['media'] : '';
		$url = isset( $instance['url'] ) ? $instance['url'] : '';
		// $format = isset( $instance['format'] ) ? $instance['format'] : '';
		$title = isset( $instance['title'] ) ? $instance['title'] : '';
		$station = isset( $instance['station'] ) ? $instance['station'] : '';
		$image = isset( $instance['image'] ) ? $instance['image'] : 'default';
		$script = isset( $instance['script'] ) ? $instance['script'] : 'default';
		$layout = isset( $instance['layout'] ) ? $instance['layout'] : 'vertical';
		$theme = isset( $instance['theme'] ) ? $instance['theme'] : 'default';
		$buttons = isset( $instance['buttons'] ) ? $instance['buttons'] : 'default';
		$volume = isset( $instance['volume'] ) ? $instance['volume'] : '';
		$default = isset( $instance['default'] ) ? $instance['default'] : 0;

		// 2.5.0: set volume control selection default
		$volumes = isset( $instance['volumes'] ) ? $instance['volumes'] : 'slider,updown,mute,max';
		$volumes = explode( ',', $volumes );
		$volume_slider = in_array( 'slider', $volumes ) ? true : false;
		$volume_updown = in_array( 'updown', $volumes ) ? true : false;
		$volume_mute = in_array( 'mute', $volumes ) ? true : false;
		$volume_max = in_array( 'max', $volumes ) ? true : false;

		// --- additional displays ---
		// $shows = isset( $instance['show_display'] ) ? $instance['show_display'] : false;
		// $hosts = isset( $instance['show_hosts'] ) ? $instance['show_hosts'] : false;
		// $producers = isset( $instance['show_producers'] ) ? $instance['show_producers'] : false;

		// --- get upgrade URLs ---
		$upgrade_url = stream_player_get_upgrade_url();
		$pricing_url = stream_player_get_pricing_url();

		// 2.5.0: set fields array
		$fields = array();

		// === Player Content ===
		$fields['player_styles'] = '<h4>' . esc_html( __( 'Player Content', 'stream-player' ) ) . '</h4>' . "\n";

		// --- Widget Title ---
		$fields['title'] = '<p>
			<label for="' . esc_attr( $this->get_field_id( 'title' ) ) . '">
			' . esc_html( __( 'Widget Title', 'stream-player' ) ) . ':
				<input class="widefat" id="' . esc_attr( $this->get_field_id( 'title' ) ) . '" name="' . esc_attr( $this->get_field_name( 'title' ) ) . '" type="text" value="' . esc_attr( $title ) . '" />
			</label>
		</p>';

		// --- Stream or File URL ---
		$fields['url'] = '<p>
			<label for="' . esc_attr( $this->get_field_id( 'url' ) ) . '">
			' . esc_html( __( 'Stream or File URL', 'stream-player' ) ) . ':
				<input class="widefat" id="' . esc_attr( $this->get_field_id( 'url' ) ) . '" name="' . esc_attr( $this->get_field_name( 'url' ) ) . '" type="text" value="' . esc_attr( $url ) . '" />
			</label><br>
			' . esc_html( __( 'Leave blank to use default stream URL.', 'stream-player' ) ) . '
		</p>';

		// --- Station Text ---
		$fields['station'] = '<p>
			<label for="' . esc_attr( $this->get_field_id( 'station' ) ) . '">
			' . esc_html( __( 'Player Station Text', 'stream-player' ) ) . ':
				<input class="widefat" id="' . esc_attr( $this->get_field_id( 'station' ) ) . '" name="' . esc_attr( $this->get_field_name( 'station' ) ) . '" type="text" value="' . esc_attr( $station ) . '" />
			</label><br>
			(' . esc_html( __( 'Empty for default, 0 for none.', 'stream-player' ) ) . ')
		</p>';

		// --- Station Image ---
		// 2.5.0: fix to image field key (script)
		// TODO: maybe add support for custom image?
		$field = '<p>
			<label for="' . esc_attr( $this->get_field_id( 'image' ) ) . '">
			' . esc_html( __( 'Display Station Image', 'stream-player' ) ) . '</label><br>
			<select id="' . esc_attr( $this->get_field_id( 'image' ) ) . '" name="' . esc_attr( $this->get_field_name( 'image' ) ) . '">';
			$options = array(
				'default' => __( 'Plugin Setting', 'stream-player' ),
				'1'       => __( 'Display Station Image', 'stream-player' ),
				'0'       => __( 'Do Not Display Image', 'stream-player' ),
				// 'custom' => __( 'Use Custom Image', 'stream-player' ),
			);
			foreach ( $options as $option => $label ) {
				$field .= '<option value="' . esc_attr( $option ) . '" ' . selected( $image, $option, false ) . '>' . esc_html( $label ) . '</option>';
			}
			$field .= '</select>
		</p>';
		$fields['image'] = $field;

		// === Player Options ===
		$fields['player_options'] = '<h4>' . esc_html( __( 'Player Options', 'stream-player' ) ) . '</h4>' . "\n";

		// --- Player Script ---
		$field = '<p>
			<label for="' . esc_attr( $this->get_field_id( 'script' ) ) . '">
			' . esc_html( __( 'Default Player Script', 'stream-player' ) ) . '</label><br>
			<select id="' . esc_attr( $this->get_field_id( 'script' ) ) . '" name="' . esc_attr( $this->get_field_name( 'script' ) ) . '">';
			$options = array(
				'default'   => __( 'Plugin Setting', 'stream-player' ),
				'amplitude' => __( 'Amplitude', 'stream-player' ),
				'howler'    => __( 'Howler', 'stream-player' ),
				'jplayer'   => __( 'jPlayer', 'stream-player' ),
			);
			foreach ( $options as $option => $label ) {
				$field .= '<option value="' . esc_attr( $option ) . '" ' . selected( $script, $option, false ) . '>' . esc_html( $label ) . '</option>';
			}
			$field .= '</select>
		</p>';
		$fields['script'] = $field;

		// --- Player Volume ---
		// TODO: maybe improve this to a number field control?
		$fields['volume'] = '<p>
			<label for="' . esc_attr( $this->get_field_id( 'volume' ) ) . '">
			' . esc_html( __( 'Player Start Volume', 'stream-player' ) ) . ' (0 to 100, empty for default):
				<input id="' . esc_attr( $this->get_field_id( 'volume' ) ) . '" name="' . esc_attr( $this->get_field_name( 'volume' ) ) . '" type="text" value="' . esc_attr( $volume ) . '" />
			</label>
		</p>';

		// --- Player Volume Controls ---
		// 2.5.0: added for consistency with main plugin settings
		$fields['volumes'] = '<p>
			<label>
			' . esc_html( __( 'Volume Controls', 'stream-player' ) ) . '<br>
				<input name="' . esc_attr( $this->get_field_name( 'volume_slider' ) ) . '" type="checkbox" ' . checked( $volume_slider, true, false ) . '> ' . esc_html( __( 'Slider', 'stream-player' ) ) . ' ' . '
				<input name="' . esc_attr( $this->get_field_name( 'volume_updown' ) ) . '" type="checkbox" ' . checked( $volume_updown, true, false ) . '> ' . esc_html( __( 'Up/Down', 'stream-player' ) ) . ' ' . '
				<input name="' . esc_attr( $this->get_field_name( 'volume_mute' ) ) . '" type="checkbox" ' . checked( $volume_mute, true, false ) . '> ' . esc_html( __( 'Mute', 'stream-player' ) ) . ' ' . '
				<input name="' . esc_attr( $this->get_field_name( 'volume_max' ) ) . '" type="checkbox" ' . checked( $volume_max, true, false ) . '> ' . esc_html( __( 'Max', 'stream-player' ) ) . ' ' . '
			</label>
		</p>';

		// --- Default Player ---
		$fields['default'] = '<p>
			<label for="' . esc_attr( $this->get_field_id( 'default' ) ) . '">
			<input id="' . esc_attr( $this->get_field_id( 'default' ) ) . '" name="' . esc_attr( $this->get_field_name( 'default' ) ) . '" type="checkbox" ' . checked( $default, true, false ) . '>
				' . esc_html( __( 'Use as the default Player instance.', 'stream-player' ) ) . '
			</label>
		</p>';

		// --- [Pro] Popup Player ---
		$fields['popup'] = '<p>
			<label for="dynamic">' . esc_html( __( 'Popup Player button available in Pro.', 'stream-player' ) ) . '</label><br>
			<a href="' . esc_url( $pricing_url ) . '">' . esc_html( __( 'Upgrade to Pro', 'stream-player' ) ) . '</a> |
			<a href="' . esc_url( $upgrade_url ) . '" target="_blank">' . esc_html( __( 'More Details', 'stream-player' ) ) . '</a>
		</p>';

		// === Player Styles ===
		$fields['player_styles'] = '<h4>' . esc_html( __( 'Player Styles', 'stream-player' ) ) . '</h4>' . "\n";

		// --- Player Layout ---
		$field = '<p>
			<label for="' . esc_attr( $this->get_field_id( 'layout' ) ) . '">
			' . esc_html( __( 'Player Widget Layout', 'stream-player' ) ) . '</label><br>
			<select id="' . esc_attr( $this->get_field_id( 'layout' ) ) . '" name="' . esc_attr( $this->get_field_name( 'layout' ) ) . '">';
			$options = array(
				'vertical' => __( 'Vertical (Stacked)', 'stream-player' ),
				'horizontal' => __( 'Horizontal (Inline)', 'stream-player' ),
			);
			foreach ( $options as $option => $label ) {
				$field .= '<option value="' . esc_attr( $option ) . '" ' . selected( $layout, $option, false ) . '>' . esc_html( $label ) . '</option>';
			}
			$field .= '</select>
		</p>';
		$fields['layout'] = $field;

		// --- Player Theme ---
		$field = '<p>
			<label for="' . esc_attr( $this->get_field_id( 'theme' ) ) . '">
			' . esc_html( __( 'Player Theme Style', 'stream-player' ) ) . '</label><br>
			<select id="' . esc_attr( $this->get_field_id( 'theme' ) ) . '" name="' . esc_attr( $this->get_field_name( 'theme' ) ) . '">';
			$options = array(
				'default' => __( 'Plugin Setting', 'stream-player' ),
				'light'   => __( 'Light', 'stream-player' ),
				'dark'    => __( 'Dark', 'stream-player' ),
			);
			$options = apply_filters( 'stream_player_player_theme_options', $options );
			$options = apply_filters( 'radio_player_theme_options', $options );
			foreach ( $options as $option => $label ) {
				$field .= '<option value="' . esc_attr( $option ) . '" ' . selected( $theme, $option, false ) . '>' . esc_html( $label ) . '</option>';
			}

			$field .= '</select>
		</p>';
		$fields['theme'] = $field;

		// --- Player Buttons ---
		$field = '<p>
			<label for="' . esc_attr( $this->get_field_id( 'buttons' ) ) . '">
			' . esc_html( __( 'Buttons Style', 'stream-player' ) ) . '</label><br>
			<select id="' . esc_attr( $this->get_field_id( 'buttons' ) ) . '" name="' . esc_attr( $this->get_field_name( 'buttons' ) ) . '">';
			$options = array(
				'default'  => __( 'Plugin Setting', 'stream-player' ),
				'circular' => __( 'Circular', 'stream-player' ),
				'rounded'  => __( 'Rounded', 'stream-player' ),
				'square'   => __( 'Square', 'stream-player' ),
			);
			$options = apply_filters( 'stream_player_player_button_options', $options );
			$options = apply_filters( 'radio_player_button_options', $options );
			foreach ( $options as $option => $label ) {
				$field .= '<option value="' . esc_attr( $option ) . '" ' . selected( $buttons, $option, false ) . '>' . esc_html( $label ) . '</option>';
			}
			$field .= '</select>
		</p>';
		$fields['buttons'] = $field;

		// --- [Pro] Color Options ---
		// 2.5.0: added Pro color options message
		// 2.5.6: fix to color options title / undefined index warning
		$fields['color_options'] = '<h4>' . esc_html( __( '[Pro] Color Options', 'stream-player' ) ) . '</h4>' . "\n";
		$fields['color_options'] .= '<p>
			<label for="dynamic">' . esc_html( __( 'Color options available in Pro.', 'stream-player' ) ) . '</label><br>
			<a href="' . esc_url( $pricing_url ) . '">' . esc_html( __( 'Upgrade to Pro', 'stream-player' ) ) . '</a> |
			<a href="' . esc_url( $upgrade_url ) . '" target="_blank">' . esc_html( __( 'More Details', 'stream-player' ) ) . '</a>
		</p>';

		// --- [Pro] Track Animation ---
		// 2.5.0: added Pro track animation message
		$fields['advanced_options'] = '<h4>' . esc_html( __( '[Pro] Advanced Options', 'stream-player' ) ) . '</h4>' . "\n";
		$fields['advanced_options'] .= '<p>
			<label for="dynamic">' . esc_html( __( 'Advanced options available in Pro.', 'stream-player' ) ) . '</label><br>
			<ul>
			<a href="' . esc_url( $pricing_url ) . '">' . esc_html( __( 'Upgrade to Pro', 'stream-player' ) ) . '</a> |
			<a href="' . esc_url( $upgrade_url ) . '" target="_blank">' . esc_html( __( 'More Details', 'stream-player' ) ) . '</a>
		</p>';

		// --- filter and output ---
		// 2.5.0: added filter for array fields for ease of adding fields
		$fields = apply_filters( 'stream_player_player_widget_fields_list', $fields, $this, $instance );
		$fields_html = implode( "\n", $fields );
		$fields_html = apply_filters( 'stream_player_player_widget_fields', $fields_html, $this, $instance );
		// 2.5.0: use wp_kses on field settings output
		$allowed = stream_player_allowed_html( 'content', 'settings' );
		echo wp_kses( $fields_html, $allowed );
	}

	// --- update widget instance values ---
	public function update( $new_instance, $old_instance ) {

		// --- get new widget options ---
		$instance = $old_instance;
		// $instance['media'] = isset( $new_instance['media'] ) : 'stream';
		$instance['url'] = isset( $new_instance['url'] ) ? $new_instance['url'] : '';
		$instance['title'] = isset( $new_instance['title'] ) ? $new_instance['title'] : '';
		$instance['station'] = isset( $new_instance['station'] ) ? $new_instance['station'] : '';
		$instance['image'] = isset( $new_instance['image'] ) ? $new_instance['image'] : $old_instance['image'];
		$instance['script'] = isset( $new_instance['script'] ) ? $new_instance['script'] : $old_instance['script'];
		$instance['layout'] = isset( $new_instance['layout'] ) ? $new_instance['layout'] : $old_instance['layout'];
		$instance['theme'] = isset( $new_instance['theme'] ) ? $new_instance['theme'] : $old_instance['theme'];
		$instance['buttons'] = isset( $new_instance['buttons'] ) ? $new_instance['buttons'] : $old_instance['buttons'];
		$instance['volume'] = isset( $new_instance['volume'] ) ? $new_instance['volume'] : $old_instance['volume'];
		if ( '' != $instance['volume'] ) {
			$instance['volume'] = absint( $instance['volume'] );
			if ( $instance['volume'] > 100 ) {
				$instance['volume'] = 100;
			} elseif ( $instance['volume'] < 0 ) {
				$instance['volume'] = 0;
			}
		}
		$instance['default'] = isset( $new_instance['default'] ) ? 1 : 0;

		// 2.5.0: save volume control selections
		$volumes = array();
		if ( isset( $new_instance['volume_slider'] ) ) {
			$volumes[] = 'slider';
		}
		if ( isset( $new_instance['volume_updown'] ) ) {
			$volumes[] = 'updown';
		}
		if ( isset( $new_instance['volume_mute'] ) ) {
			$volumes[] = 'mute';
		}
		if ( isset( $new_instance['volume_max'] ) ) {
			$volumes[] = 'max';
		}
		$instance['volumes'] = implode( ',', $volumes );

		// --- additional displays ---
		// $instance['show_display'] = isset( $new_instance['show_display'] ) ? 1 : 0;
		// $instance['show_hosts'] = isset( $new_instance['show_hosts'] ) ? 1 : 0;
		// $instance['show_producers'] = isset( $new_instance['show_producers'] ) ? 1 : 0;

		// --- filter and return ---
		$instance = apply_filters( 'stream_player_player_widget_update', $instance, $new_instance, $old_instance );
		return $instance;
	}

	// --- widget output ---
	public function widget( $args, $instance ) {

		global $stream_player_data;

		// --- set widget id ---
		// 2.5.0: simplify widget id setting
		if ( !isset( $stream_player_data['widgets']['player'] ) ) {
			$stream_player_data['widgets']['player'] = 0;
		}
		$stream_player_data['widgets']['player']++;
		$id = $stream_player_data['widgets']['player'];

		// --- get widget options ---
		$media = isset( $instance['media'] ) ? $instance['media'] : 'stream';
		$url = $instance['url'];
		// $fallback = $instance['fallback'];
		$title = empty( $instance['title'] ) ? '' : $instance['title'];
		$title = apply_filters( 'widget_title', $title );
		$station = $instance['station'];
		$image = $instance['image'];
		if ( 'on' == $image ) {
			$image = 1;
		} elseif ( 'off' == $image ) {
			$image = 0;
		}
		$script = $instance['script'];
		$layout = $instance['layout'];
		$theme = $instance['theme'];
		$buttons = $instance['buttons'];
		$volume = $instance['volume'];
		if ( !$volume ) {
			$volume = 'default';
		}
		$default = $instance['default'];
		// 2.5.0: added volume controls
		$volumes = isset( $instance['volumes'] ) ? explode( ',', $instance['volumes'] ) : array( 'slider', 'updown', 'mute', 'max' );

		// --- additional displays ---
		// $instance['show_display'] = $instance['show_display'] ) ? 1 : 0;
		// $instance['show_hosts'] = $instance['show_hosts'];
		// $instance['show_producers'] = $instance['show_producers'];

		// --- set shortcode attributes ---
		// note: station text mapped to shortcode title attribute
		// 2.5.0: added media and volumes attributes
		$atts = array(
			// --- main player settings ---
			'media'          => $media,
			'url'            => $url,
			// 'fallback'    => $fallback
			'title'          => $station,
			'image'          => $image,
			'script'         => $script,
			'layout'         => $layout,
			'theme'          => $theme,
			'buttons'        => $buttons,
			'volume'         => $volume,
			'volumes'        => $volumes,
			'default'        => $default,
			// --- additional displays ---
			// 'shows'          => $shows,
			// 'hosts'          => $hosts,
			// 'producers'      => $producers,
			// --- widget data ---
			// 2.4.0.1: prefix widget player ID
			'widget'         => 1,
			'id'             => $id,
		);

		// 2.5.0: add filter for default widget attributes
		$atts = apply_filters( 'stream_player_player_widget_atts', $atts, $instance );

		// --- maybe debug widget attributes --
		// 2.5.0: added for debugging widget attributes
		// 2.5.6: added sanitize_text_field wrapper
		if ( isset( $_REQUEST['player-debug'] ) && ( '1' === sanitize_text_field( $_REQUEST['player-debug'] ) ) ) {
			echo '<span style="display:none;">Radio Player Widget Attributes: ';
			echo esc_html( print_r( $atts, true ) ) . '</span>';
		}

		// 2.5.0: get context filtered allowed HTML
		$allowed = stream_player_allowed_html( 'widget', 'radio-player' );

		// --- before widget ---
		// 2.5.0: use wp_kses on output
		echo wp_kses( $args['before_widget'], $allowed );

		// --- open widget container ---
		// 2.5.0: added class radio-player-widget
		echo '<div id="radio-player-widget-' . esc_attr( $id ) . '" class="radio-player-widget widget">' . "\n";

			// --- widget title ---
			// 2.5.0: use wp_kses on output
			echo wp_kses( $args['before_title'], $allowed );
			if ( !empty( $title ) ) {
				echo wp_kses( $title, $allowed );
			}
			// 2.5.0: use wp_kses on output
			echo wp_kses( $args['after_title'], $allowed );

			// --- get default display output ---
			$output = radio_player_shortcode( $atts );

			// --- check for widget output override ---
			$output = apply_filters( 'stream_player_player_widget_override', $output, $args, $atts );

			// --- output widget display ---
			// TODO: test wp_kses on widget output ?
			// wp_kses( $output, $allowed );
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $output;

		echo '</div>' . "\n";

		// --- after widget ---
		// 2.5.0: use wp_kses on output
		echo wp_kses( $args['after_widget'], $allowed );

	}
}

// ----------------------
// Register Player Widget
// ----------------------
add_action( 'widgets_init', 'stream_player_register_player_widget' );
function stream_player_register_player_widget() {
	register_widget( 'Radio_Player_Widget' );
}
