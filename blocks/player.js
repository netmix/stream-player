/**
 * === Stream Player Block ===
 */
(() => {

	/* --- Import Modules/Components --- */
	const el = window.wp.element.createElement;
	const { serverSideRender: ServerSideRender } = window.wp;
	const { registerBlockType } = window.wp.blocks;
	const { getBlockType } = window.wp.blocks;
	const { InspectorControls } = window.wp.blockEditor;
	const { Fragment } = window.wp.element;
	const { BaseControl, TextControl, SelectControl, RadioControl, RangeControl, ToggleControl, ColorPicker, Dropdown, Button, Panel, PanelBody, PanelRow } = window.wp.components;
	const { __, _e } = window.wp.i18n;
	
	/* --- Register Block --- */
	if ( !getBlockType('radio-station/player' ) ) {
	 registerBlockType( 'radio-station/player', {

		/* --- Block Settings --- */
		title: __( 'Stream Player', 'stream-player' ),
		description: __( 'Audio stream player block.', 'stream-player' ),
		icon: 'controls-volumeon',
		category: 'stream-player',
		example: {},
		attributes: {
			/* --- Player Content --- */
			url: { type: 'string', default: '' },
			title: { type: 'string', default: '' },
			image: { type: 'string', default: 'default' },
			/* --- Player Options --- */
			script: { type: 'string', default: 'default' },
			volume: { type: 'number', default: 77 },
			volumes: { type: 'array', default: ['slider'] },
			default: { type: 'boolean', default: false },
			/* --- Player Styles --- */
			layout: { type: 'string', default: 'horizontal' },
			theme: { type: 'string', default: 'default' },
			buttons: { type: 'string', default: 'default' },
			/* --- Hidden Switches --- */
			block: { type: 'boolean', default: true },
			pro: { type: 'boolean', default: false }
		},

		/**
		 * Edit Block Control
		 */
		edit: (props) => {
			const atts = props.attributes;
			return (
				el( Fragment, {},
					el( ServerSideRender, { block: 'radio-station/player', className: 'radio-player-block', attributes: atts } ),
					el( InspectorControls, {},
						el( Panel, {},
							/* === Player Content === */
							el( PanelBody, { title: __( 'Player Content', 'stream-player' ), className: 'stream-block-controls', initialOpen: true },
								/* --- Stream URL --- */
								el( PanelRow, {},
									el( TextControl, {
										label: __( 'Stream URL', 'stream-player' ),
										help: __( 'Leave blank to use default stream.', 'stream-player' ),
										onChange: ( value ) => {
											props.setAttributes( { url: value } );
										},
										value: atts.url,
									})
								),
								/* --- Player Title Text --- */
								el( PanelRow, {},
									el( TextControl, {
										label: __( 'Player Title Text', 'stream-player' ),
										help: __( 'Empty for default, 0 for none.', 'stream-player' ),
										onChange: ( value ) => {
											props.setAttributes( { title: value } );
										},
										value: atts.title
									})
								),
								/* --- Image --- */
								el( PanelRow, {},
									el( SelectControl, {
										label: __( 'Player Image', 'stream-player' ),
										options : [
											{ label: __( 'Plugin Setting', 'stream-player' ), value: 'default' },
											{ label: __( 'Display Station Image', 'stream-player' ), value: '1' },
											{ label: __( 'Do Not Display Station Image', 'stream-player' ), value: '0' },
											/* { label: __( 'Display Custom Image', 'stream-player' ), value: 'custom' }, */
										],
										onChange: ( value ) => {
											props.setAttributes( { image: value } );
										},
										value: atts.image
									})
								)
							),

							/* === Player Options === */
							el( PanelBody, { title: __( 'Player Options', 'stream-player' ), className: 'stream-block-controls', initialOpen: true },
								/* --- Script --- */
								el( PanelRow, {},
									el( SelectControl, {
										label: __( 'Player Script', 'stream-player' ),
										options : [
											{ label: __( 'Plugin Setting', 'stream-player' ), value: 'default' },
											{ label: __( 'Amplitude', 'stream-player' ), value: 'amplitude' },
											{ label: __( 'Howler', 'stream-player' ), value: 'howler' },
											{ label: __( 'jPlayer', 'stream-player' ), value: 'jplayer' },
										],
										onChange: ( value ) => {
											props.setAttributes( { script: value } );
										},
										value: atts.script
									})
								),
								/* --- Volume --- */
								el( PanelRow, {},
									el( RangeControl, {
										label: __( 'Initial Volume', 'stream-player' ),
										min: 0,
										max: 100,
										onChange: ( value ) => {
											props.setAttributes( { volume: value } );
										},
										value: atts.volume
									})
								),
								/* --- Volume controls --- */
								el( PanelRow, {},
									el( SelectControl, {
										multiple: true,
										label: __( 'Volume Controls', 'stream-player' ),
										help: __( 'Ctrl-Click to select multiple controls.', 'stream-player' ),
										options: [
											{ label: __( 'Volume Slider', 'stream-player' ), value: 'slider' },
											{ label: __( 'Up and Down Buttons', 'stream-player' ), value: 'updown' },
											{ label: __( 'Mute Button', 'stream-player' ), value: 'mute' },
											{ label: __( 'Maximize Button', 'stream-player' ), value: 'max' },
										],
										onChange: ( value ) => {
											props.setAttributes( { volumes: value } );
										},
										value: atts.volumes
									})
								),
								/* --- Default Player --- */
								el( PanelRow, {},
									el( ToggleControl, {
										label: __( 'Use as Default Player', 'stream-player' ),
										help: __( 'Make this the default player on this page.', 'stream-player' ),
										onChange: ( value ) => {
											props.setAttributes( { default: value } );
										},
										checked: atts.default,
									})
								),
								/* --- Popup Player Button --- */
								el( PanelRow, {},
									( ( atts.pro ) && 
										el( SelectControl, {
											label: __( 'Popup Player', 'stream-player' ),
											help: __( 'Enables button to open Player in separate window.', 'stream-player' ),
											options : [
												{ label: __( 'Plugin Setting', 'stream-player' ), value: 'default' },
												{ label: __( 'On', 'stream-player' ), value: 'on' },
												{ label: __( 'Off', 'stream-player' ), value: 'off' },
											],
											onChange: ( value ) => {
												props.setAttributes( { popup: value } );
											},
											value: atts.popup
										})
									), ( ( !atts.pro ) &&
										el( BaseControl, {
											label: __( 'Popup Player', 'stream-player' ),
											help: __( 'Popup Player Button available in Pro.', 'stream-player' ),
										})
									)
								),
							),

							/* === Player Styles === */
							el( PanelBody, { title: __( 'Player Design', 'stream-player' ), className: 'stream-block-controls', initialOpen: true },
								/* --- Player Layout --- */
								el( PanelRow, {},
									el( RadioControl, {
										label: __( 'Player Layout', 'stream-player' ),
										options : [
											{ label: __( 'Vertical (Stacked)', 'stream-player' ), value: 'vertical' },
											{ label: __( 'Horizontal (Inline)', 'stream-player' ), value: 'horizontal' },
										],
										onChange: ( value ) => {
											props.setAttributes( { layout: value } );
										},
										checked: atts.layout
									})
								),
								/* --- Player Theme --- */
								( ( !atts.pro ) &&
									el( PanelRow, {},
										el( SelectControl, {
											label: __( 'Player Theme', 'stream-player' ),
											options : [
												{ label: __( 'Plugin Setting', 'stream-player' ), value: 'default' },
												{ label: __( 'Light', 'stream-player' ), value: 'light' },
												{ label: __( 'Dark', 'stream-player' ), value: 'dark' },
											],
											onChange: ( value ) => {
												props.setAttributes( { theme: value } );
											},
											value: atts.theme
										})
									)
								),
								/* [Pro] Extra Theme Color Options */
								( ( atts.pro ) &&
									el( PanelRow, {},
										el( SelectControl, {
											label: __( 'Player Theme', 'stream-player' ),
											options : [
												{ label: __( 'Plugin Setting', 'stream-player' ), value: 'default' },
												{ label: __( 'Light', 'stream-player' ), value: 'light' },
												{ label: __( 'Dark', 'stream-player' ), value: 'dark' },
												{ label: __( 'Red', 'stream-player' ), value: 'red' },
												{ label: __( 'Orange', 'stream-player' ), value: 'orange' },
												{ label: __( 'Yellow', 'stream-player' ), value: 'yellow' },
												{ label: __( 'Light Green', 'stream-player' ), value: 'light-green' },
												{ label: __( 'Green', 'stream-player' ), value: 'green' },
												{ label: __( 'Cyan', 'stream-player' ), value: 'cyan' },
												{ label: __( 'Light Blue', 'stream-player' ), value: 'light-blue' },
												{ label: __( 'Blue', 'stream-player' ), value: 'blue' },
												{ label: __( 'Purple', 'stream-player' ), value: 'purple' },
												{ label: __( 'Magenta', 'stream-player' ), value: 'magenta' },
											],
											onChange: ( value ) => {
												props.setAttributes( { theme: value } );
											},
											value: atts.theme
										})
									)
								),
								/* --- Player Buttons --- */
								el( PanelRow, {},
									el( SelectControl, {
										label: __( 'Player Buttons', 'stream-player' ),
										options : [
											{ label: __( 'Plugin Setting', 'stream-player' ), value: 'default' },
											{ label: __( 'Circular', 'stream-player' ), value: 'circular' },
											{ label: __( 'Rounded', 'stream-player' ), value: 'rounded' },
											{ label: __( 'Square', 'stream-player' ), value: 'square' },
										],
										onChange: ( value ) => {
											props.setAttributes( { buttons: value } );
										},
										value: atts.buttons
									})
								)					
							),
							
							/* === [Pro] Player Colors === */
							( ( atts.pro ) &&
								el( PanelBody, { title: __( 'Player Colors', 'stream-player' ), className: 'stream-block-controls', initialOpen: true },
								
									/* --- Text Color --- */
									el( PanelRow, {},
										el( BaseControl, {
											label: __( 'Text Color', 'stream-player' ),
											className: 'color-dropdown-control'
										},
											el( Dropdown, {
												renderContent: () => (
													el( ColorPicker, {
														disableAlpha: true,
														defaultValue: '',
														onChangeComplete: color => {
															props.setAttributes( {text_color: color.hex} );
														},
														color: atts.text_color
													})
												),
												renderToggle: (args) => (
													el( 'div', {className: 'color-dropdown-buttons'},
														el ( Button, {
															className: 'color-dropdown-text_color',
															onClick: args.onToggle,
															variant: 'secondary',
															'aria-expanded': args.isOpen,
															'aria-haspopup': 'true',
															'aria-label': __( 'Select Text Color', 'stream-player' )
														},
														( ('' != atts.text_color) ? atts.text_color : __( 'Select', 'stream-player' ) )
														),
														el( Button, {
															onClick: () => {
																props.setAttributes( {text_color: ''} );
																args.onClose();
															},
															isSmall: true,
															variant: 'tertiary',
															'aria-label': __( 'Clear Text Color Selection', 'stream-player' )
														},
														__( 'Clear', 'stream-player' )
														),
														( ( '' != atts.text_color ) &&
															el( 'style', {}, '.components-button.is-secondary.color-dropdown-text_color {background-color:'+atts.text_color+'}' )
														)
													)
												)
											} ) 
										)
									),

									/* --- Background Color --- */
									el( PanelRow, {},
										el( BaseControl, {
											label: __( 'Background Color', 'stream-player' ),
											className: 'color-dropdown-control'
										},
											el( Dropdown, {
												renderContent: () => (
													el( ColorPicker, {
														defaultValue: '',
														onChangeComplete: color => {
															props.setAttributes( {background_color: color.hex} );
														},
														color: atts.background_color
													})
												),
												renderToggle: (args) => (
													el( 'div', {className: 'color-dropdown-buttons'},
														el ( Button, {
															className: 'color-dropdown-background_color',
															onClick: args.onToggle,
															variant: 'secondary',
															'aria-expanded': args.isOpen,
															'aria-haspopup': 'true',
															'aria-label': __( 'Select Background Color', 'stream-player' )
														},
														( ('' != atts.background_color) ? atts.background_color : __( 'Select', 'stream-player' ) )
														),
														el( Button, {
															onClick: () => {
																props.setAttributes( {background_color: ''} );
																args.onClose();
															},
															isSmall: true,
															variant: 'tertiary',
															'aria-label': __( 'Clear Background Color Selection', 'stream-player' )
														},
														__( 'Clear', 'stream-player' )
														),
														( ( '' != atts.background_color ) &&
															el( 'style', {}, '.components-button.is-secondary.color-dropdown-background_color {background-color:'+atts.background_color+'}' )
														)
													)
												)
											} ) 
										)
									),
									
									/* --- Playing Color --- */
									el( PanelRow, {},
										el( BaseControl, {
											label: __( 'Playing Highlight', 'stream-player' ),
											className: 'color-dropdown-control'
										},
											el( Dropdown, {
												renderContent: () => (
													el( ColorPicker, {
														defaultValue: '',
														onChangeComplete: color => {
															props.setAttributes( {playing_color: color.hex} );
														},
														color: atts.playing_color
													})
												),
												renderToggle: (args) => (
													el( 'div', {className: 'color-dropdown-buttons'},
														el ( Button, {
															className: 'color-dropdown-playing_color',
															onClick: args.onToggle,
															variant: 'secondary',
															'aria-expanded': args.isOpen,
															'aria-haspopup': 'true',
															'aria-label': __( 'Select Playing Highlight Color', 'stream-player' )
														},
														( ('' != atts.playing_color) ? atts.playing_color : __( 'Select', 'stream-player' ) )
														),
														el( Button, {
															onClick: () => {
																props.setAttributes( {playing_color: ''} );
																args.onClose();
															},
															isSmall: true,
															variant: 'tertiary',
															'aria-label': __( 'Clear Playing Color Selection', 'stream-player' )
														},
														__( 'Clear', 'stream-player' )
														),
														( ( '' != atts.playing_color ) &&
															el( 'style', {}, '.components-button.is-secondary.color-dropdown-playing_color {background-color:'+atts.playing_color+'}' )
														)
													)
												)
											} ) 
										)
									),
									
									/* --- Buttons Color --- */
									el( PanelRow, {},
										el( BaseControl, {
											label: __( 'Buttons Highlight', 'stream-player' ),
											className: 'color-dropdown-control'
										},
											el( Dropdown, {
												renderContent: () => (
													el( ColorPicker, {
														defaultValue: '',
														onChangeComplete: color => {
															props.setAttributes( {buttons_color: color.hex} );
														},
														color: atts.buttons_color
													})
												),
												renderToggle: (args) => (
													el( 'div', {className: 'color-dropdown-buttons'},
														el ( Button, {
															className: 'color-dropdown-buttons_color',
															onClick: args.onToggle,
															variant: 'secondary',
															'aria-expanded': args.isOpen,
															'aria-haspopup': 'true',
															'aria-label': __( 'Select Button Highlight Color', 'stream-player' )
														},
														( ('' != atts.buttons_color) ? atts.buttons_color : __( 'Select', 'stream-player' ) )
														),
														el( Button, {
															onClick: () => {
																props.setAttributes( {buttons_color: ''} );
																args.onClose();
															},
															isSmall: true,
															variant: 'tertiary',
															'aria-label': __( 'Clear Button Highlight Color Selection', 'stream-player' )
														},
														__( 'Clear', 'stream-player' )
														),
														( ( '' != atts.buttons_color ) &&
															el( 'style', {}, '.components-button.is-secondary.color-dropdown-buttons_color {background-color:'+atts.buttons_color+'}' )
														)
													)
												)
											} ) 
										)
									),
									
									/* --- Track Color --- */
									el( PanelRow, {},
										el( BaseControl, {
											label: __( 'Volume Track', 'stream-player' ),
											className: 'color-dropdown-control'
										},
											el( Dropdown, {
												renderContent: () => (
													el( ColorPicker, {
														defaultValue: '',
														onChangeComplete: color => {
															props.setAttributes( {track_color: color.hex} );
														},
														color: atts.track_color
													})
												),
												renderToggle: (args) => (
													el( 'div', {className: 'color-dropdown-buttons'},
														el ( Button, {
															className: 'color-dropdown-track_color',
															onClick: args.onToggle,
															variant: 'secondary',
															'aria-expanded': args.isOpen,
															'aria-haspopup': 'true',
															'aria-label': __( 'Select Volume Track Color', 'stream-player' )
														},
														( ('' != atts.track_color) ? atts.track_color : __( 'Select', 'stream-player' ) )
														),
														el( Button, {
															onClick: () => {
																props.setAttributes( {track_color: ''} );
																args.onClose();
															},
															isSmall: true,
															variant: 'tertiary',
															'aria-label': __( 'Clear Volume Track Color Selection', 'stream-player' )
														},
														__( 'Clear', 'stream-player' )
														),
														( ( '' != atts.track_color ) &&
															el( 'style', {}, '.components-button.is-secondary.color-dropdown-track_color {background-color:'+atts.track_color+'}' )
														)
													)
												)
											} ) 
										)
									),
									
									/* --- Thumb Color --- */
									el( PanelRow, {},
										el( BaseControl, {
											label: __( 'Volume Thumb', 'stream-player' ),
											className: 'color-dropdown-control'
										},
											el( Dropdown, {
												renderContent: () => (
													el( ColorPicker, {
														defaultValue: '',
														onChangeComplete: color => {
															props.setAttributes( {thumb_color: color.hex} );
														},
														color: atts.thumb_color
													})
												),
												renderToggle: (args) => (
													el( 'div', {className: 'color-dropdown-buttons'},
														el ( Button, {
															className: 'color-dropdown-thumb_color',
															onClick: args.onToggle,
															variant: 'secondary',
															'aria-expanded': args.isOpen,
															'aria-haspopup': 'true',
															'aria-label': __( 'Select Volume Thumb Color', 'stream-player' )
														},
														( ('' != atts.thumb_color) ? atts.thumb_color : __( 'Select', 'stream-player' ) )
														),
														el( Button, {
															onClick: () => {
																props.setAttributes( {thumb_color: ''} );
																args.onClose();
															},
															isSmall: true,
															variant: 'tertiary',
															'aria-label': __( 'Clear Volume Thumb Color Selection', 'stream-player' )
														},
														__( 'Clear', 'stream-player' )
														),
														( ( '' != atts.thumb_color ) &&
															el( 'style', {}, '.components-button.is-secondary.color-dropdown-thumb_color {background-color:'+atts.thumb_color+'}' )
														)
													)
												)
											} ) 
										)
									),
									/* --- end color options --- */
								)
							),

							/* === Advanced Options === */
							( ( atts.pro ) &&
								el( PanelBody, { title: __( 'Advanced Options', 'stream-player' ), className: 'stream-block-controls', initialOpen: true },
									/* --- Current Show Display --- */
									el( PanelRow, {},
										el( SelectControl, {
											label: __( 'Current Show Display', 'stream-player' ),
											options : [
												{ label: __( 'Plugin Setting', 'stream-player' ), value: 'default' },
												{ label: __( 'On', 'stream-player' ), value: 'on' },
												{ label: __( 'Off', 'stream-player' ), value: 'off' },
											],
											onChange: ( value ) => {
												props.setAttributes( { currentshow: value } );
											},
											value: atts.currentshow
										})
									),
									/* ---Now Playing Display --- */
									el( PanelRow, {},
										el( SelectControl, {
											label: __( 'Now Playing Track Display', 'stream-player' ),
											options : [
												{ label: __( 'Plugin Setting', 'stream-player' ), value: 'default' },
												{ label: __( 'On', 'stream-player' ), value: 'on' },
												{ label: __( 'Off', 'stream-player' ), value: 'off' },
											],
											onChange: ( value ) => {
												props.setAttributes( { nowplaying: value } );
											},
											value: atts.nowplaying
										})
									),
									/* --- Track Animation --- */
									el( PanelRow, {},
										el( SelectControl, {
											label: __( 'Track Animation', 'stream-player' ),
											options : [
												{ label: __( 'Plugin Setting', 'stream-player' ), value: 'default' },
												{ label: __( 'No Animation', 'stream-player' ), value: 'none' },
												{ label: __( 'Left to Right Ticker', 'stream-player' ), value: 'lefttoright' },
												{ label: __( 'Right to Left Ticker', 'stream-player' ), value: 'righttoleft' },
												{ label: __( 'Back and Forth', 'stream-player' ), value: 'backandforth' },
												{ label: __( '', 'stream-player' ), value: 'off' },
											],
											onChange: ( value ) => {
												props.setAttributes( { animation: value } );
											},
											value: atts.animation
										})
									),
									/* --- Metadata URL --- */
									el( PanelRow, {},
										el( TextControl, {
											label: __( 'Metadata Source URL', 'stream-player' ),
											help: __( 'Defaults to Stream URL.', 'stream-player' ),
											onChange: ( value ) => {
												props.setAttributes( { metadata: value } );
											},
											value: atts.metadata
										})
									),
								)
							)
							/* end panels */
						)
					)
				)
			);
		},

		/**
		 * Returns nothing because this is a dynamic block rendered via PHP
		 */
		save: () => null,
	 });
	}
})();
