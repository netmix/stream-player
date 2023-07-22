/**
 * === Stream Player Block ===
 */
(() => {

	/* --- Import Modules/Components --- */
	const rs_el = window.wp.element.createElement;
	const { serverSideRender: ServerSideRender } = window.wp;
	const { registerBlockType } = window.wp.blocks;
	const { getBlockType } = window.wp.blocks;
	const { InspectorControls } = window.wp.blockEditor;
	const { Fragment } = window.wp.element;
	const { BaseControl, TextControl, SelectControl, RadioControl, RangeControl, ToggleControl, ColorPicker, Dropdown, Button, Panel, PanelBody, PanelRow } = window.wp.components;
	const { __ } = window.wp.i18n;
	
	/* --- Register Block --- */
	if ( !getBlockType('radio-station/player' ) ) {
	 registerBlockType( 'radio-station/player', {

		/* --- Block Settings --- */
		title: rs__( 'Stream Player', 'stream-player' ),
		description: rs__( 'Audio stream player block.', 'stream-player' ),
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
				rs_el( Fragment, {},
					rs_el( ServerSideRender, { block: 'radio-station/player', className: 'radio-player-block', attributes: atts } ),
					rs_el( InspectorControls, {},
						rs_el( Panel, {},
							/* === Player Content === */
							rs_el( PanelBody, { title: rs__( 'Player Content', 'stream-player' ), className: 'stream-block-controls', initialOpen: true },
								/* --- Stream URL --- */
								rs_el( PanelRow, {},
									rs_el( TextControl, {
										label: rs__( 'Stream URL', 'stream-player' ),
										help: rs__( 'Leave blank to use default stream.', 'stream-player' ),
										onChange: ( value ) => {
											props.setAttributes( { url: value } );
										},
										value: atts.url,
									})
								),
								/* --- Player Title Text --- */
								rs_el( PanelRow, {},
									rs_el( TextControl, {
										label: rs__( 'Player Title Text', 'stream-player' ),
										help: rs__( 'Empty for default, 0 for none.', 'stream-player' ),
										onChange: ( value ) => {
											props.setAttributes( { title: value } );
										},
										value: atts.title
									})
								),
								/* --- Image --- */
								rs_el( PanelRow, {},
									rs_el( SelectControl, {
										label: rs__( 'Player Image', 'stream-player' ),
										options : [
											{ label: rs__( 'Plugin Setting', 'stream-player' ), value: 'default' },
											{ label: rs__( 'Display Station Image', 'stream-player' ), value: '1' },
											{ label: rs__( 'Do Not Display Station Image', 'stream-player' ), value: '0' },
											/* { label: rs__( 'Display Custom Image', 'stream-player' ), value: 'custom' }, */
										],
										onChange: ( value ) => {
											props.setAttributes( { image: value } );
										},
										value: atts.image
									})
								)
							),

							/* === Player Options === */
							rs_el( PanelBody, { title: rs__( 'Player Options', 'stream-player' ), className: 'stream-block-controls', initialOpen: true },
								/* --- Script --- */
								rs_el( PanelRow, {},
									rs_el( SelectControl, {
										label: rs__( 'Player Script', 'stream-player' ),
										options : [
											{ label: rs__( 'Plugin Setting', 'stream-player' ), value: 'default' },
											{ label: rs__( 'Amplitude', 'stream-player' ), value: 'amplitude' },
											{ label: rs__( 'Howler', 'stream-player' ), value: 'howler' },
											{ label: rs__( 'jPlayer', 'stream-player' ), value: 'jplayer' },
										],
										onChange: ( value ) => {
											props.setAttributes( { script: value } );
										},
										value: atts.script
									})
								),
								/* --- Volume --- */
								rs_el( PanelRow, {},
									rs_el( RangeControl, {
										label: rs__( 'Initial Volume', 'stream-player' ),
										min: 0,
										max: 100,
										onChange: ( value ) => {
											props.setAttributes( { volume: value } );
										},
										value: atts.volume
									})
								),
								/* --- Volume controls --- */
								rs_el( PanelRow, {},
									rs_el( SelectControl, {
										multiple: true,
										label: rs__( 'Volume Controls', 'stream-player' ),
										help: rs__( 'Ctrl-Click to select multiple controls.', 'stream-player' ),
										options: [
											{ label: rs__( 'Volume Slider', 'stream-player' ), value: 'slider' },
											{ label: rs__( 'Up and Down Buttons', 'stream-player' ), value: 'updown' },
											{ label: rs__( 'Mute Button', 'stream-player' ), value: 'mute' },
											{ label: rs__( 'Maximize Button', 'stream-player' ), value: 'max' },
										],
										onChange: ( value ) => {
											props.setAttributes( { volumes: value } );
										},
										value: atts.volumes
									})
								),
								/* --- Default Player --- */
								rs_el( PanelRow, {},
									rs_el( ToggleControl, {
										label: rs__( 'Use as Default Player', 'stream-player' ),
										help: rs__( 'Make this the default player on this page.', 'stream-player' ),
										onChange: ( value ) => {
											props.setAttributes( { default: value } );
										},
										checked: atts.default,
									})
								),
								/* --- Popup Player Button --- */
								rs_el( PanelRow, {},
									( ( atts.pro ) && 
										rs_el( SelectControl, {
											label: rs__( 'Popup Player', 'stream-player' ),
											help: rs__( 'Enables button to open Player in separate window.', 'stream-player' ),
											options : [
												{ label: rs__( 'Plugin Setting', 'stream-player' ), value: 'default' },
												{ label: rs__( 'On', 'stream-player' ), value: 'on' },
												{ label: rs__( 'Off', 'stream-player' ), value: 'off' },
											],
											onChange: ( value ) => {
												props.setAttributes( { popup: value } );
											},
											value: atts.popup
										})
									), ( ( !atts.pro ) &&
										rs_el( BaseControl, {
											label: rs__( 'Popup Player', 'stream-player' ),
											help: rs__( 'Popup Player Button available in Pro.', 'stream-player' ),
										})
									)
								),
							),

							/* === Player Styles === */
							rs_el( PanelBody, { title: rs__( 'Player Design', 'stream-player' ), className: 'stream-block-controls', initialOpen: true },
								/* --- Player Layout --- */
								rs_el( PanelRow, {},
									rs_el( RadioControl, {
										label: rs__( 'Player Layout', 'stream-player' ),
										options : [
											{ label: rs__( 'Vertical (Stacked)', 'stream-player' ), value: 'vertical' },
											{ label: rs__( 'Horizontal (Inline)', 'stream-player' ), value: 'horizontal' },
										],
										onChange: ( value ) => {
											props.setAttributes( { layout: value } );
										},
										checked: atts.layout
									})
								),
								/* --- Player Theme --- */
								( ( !atts.pro ) &&
									rs_el( PanelRow, {},
										rs_el( SelectControl, {
											label: rs__( 'Player Theme', 'stream-player' ),
											options : [
												{ label: rs__( 'Plugin Setting', 'stream-player' ), value: 'default' },
												{ label: rs__( 'Light', 'stream-player' ), value: 'light' },
												{ label: rs__( 'Dark', 'stream-player' ), value: 'dark' },
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
									rs_el( PanelRow, {},
										rs_el( SelectControl, {
											label: rs__( 'Player Theme', 'stream-player' ),
											options : [
												{ label: rs__( 'Plugin Setting', 'stream-player' ), value: 'default' },
												{ label: rs__( 'Light', 'stream-player' ), value: 'light' },
												{ label: rs__( 'Dark', 'stream-player' ), value: 'dark' },
												{ label: rs__( 'Red', 'stream-player' ), value: 'red' },
												{ label: rs__( 'Orange', 'stream-player' ), value: 'orange' },
												{ label: rs__( 'Yellow', 'stream-player' ), value: 'yellow' },
												{ label: rs__( 'Light Green', 'stream-player' ), value: 'light-green' },
												{ label: rs__( 'Green', 'stream-player' ), value: 'green' },
												{ label: rs__( 'Cyan', 'stream-player' ), value: 'cyan' },
												{ label: rs__( 'Light Blue', 'stream-player' ), value: 'light-blue' },
												{ label: rs__( 'Blue', 'stream-player' ), value: 'blue' },
												{ label: rs__( 'Purple', 'stream-player' ), value: 'purple' },
												{ label: rs__( 'Magenta', 'stream-player' ), value: 'magenta' },
											],
											onChange: ( value ) => {
												props.setAttributes( { theme: value } );
											},
											value: atts.theme
										})
									)
								),
								/* --- Player Buttons --- */
								rs_el( PanelRow, {},
									rs_el( SelectControl, {
										label: rs__( 'Player Buttons', 'stream-player' ),
										options : [
											{ label: rs__( 'Plugin Setting', 'stream-player' ), value: 'default' },
											{ label: rs__( 'Circular', 'stream-player' ), value: 'circular' },
											{ label: rs__( 'Rounded', 'stream-player' ), value: 'rounded' },
											{ label: rs__( 'Square', 'stream-player' ), value: 'square' },
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
								rs_el( PanelBody, { title: rs__( 'Player Colors', 'stream-player' ), className: 'stream-block-controls', initialOpen: true },
								
									/* --- Text Color --- */
									rs_el( PanelRow, {},
										rs_el( BaseControl, {
											label: rs__( 'Text Color', 'stream-player' ),
											className: 'color-dropdown-control'
										},
											rs_el( Dropdown, {
												renderContent: () => (
													rs_el( ColorPicker, {
														disableAlpha: true,
														defaultValue: '',
														onChangeComplete: color => {
															props.setAttributes( {text_color: color.hex} );
														},
														color: atts.text_color
													})
												),
												renderToggle: (args) => (
													rs_el( 'div', {className: 'color-dropdown-buttons'},
														el ( Button, {
															className: 'color-dropdown-text_color',
															onClick: args.onToggle,
															variant: 'secondary',
															'aria-expanded': args.isOpen,
															'aria-haspopup': 'true',
															'aria-label': rs__( 'Select Text Color', 'stream-player' )
														},
														( ('' != atts.text_color) ? atts.text_color : rs__( 'Select', 'stream-player' ) )
														),
														rs_el( Button, {
															onClick: () => {
																props.setAttributes( {text_color: ''} );
																args.onClose();
															},
															isSmall: true,
															variant: 'tertiary',
															'aria-label': rs__( 'Clear Text Color Selection', 'stream-player' )
														},
														rs__( 'Clear', 'stream-player' )
														),
														( ( '' != atts.text_color ) &&
															rs_el( 'style', {}, '.components-button.is-secondary.color-dropdown-text_color {background-color:'+atts.text_color+'}' )
														)
													)
												)
											} ) 
										)
									),

									/* --- Background Color --- */
									rs_el( PanelRow, {},
										rs_el( BaseControl, {
											label: rs__( 'Background Color', 'stream-player' ),
											className: 'color-dropdown-control'
										},
											rs_el( Dropdown, {
												renderContent: () => (
													rs_el( ColorPicker, {
														defaultValue: '',
														onChangeComplete: color => {
															props.setAttributes( {background_color: color.hex} );
														},
														color: atts.background_color
													})
												),
												renderToggle: (args) => (
													rs_el( 'div', {className: 'color-dropdown-buttons'},
														el ( Button, {
															className: 'color-dropdown-background_color',
															onClick: args.onToggle,
															variant: 'secondary',
															'aria-expanded': args.isOpen,
															'aria-haspopup': 'true',
															'aria-label': rs__( 'Select Background Color', 'stream-player' )
														},
														( ('' != atts.background_color) ? atts.background_color : rs__( 'Select', 'stream-player' ) )
														),
														rs_el( Button, {
															onClick: () => {
																props.setAttributes( {background_color: ''} );
																args.onClose();
															},
															isSmall: true,
															variant: 'tertiary',
															'aria-label': rs__( 'Clear Background Color Selection', 'stream-player' )
														},
														rs__( 'Clear', 'stream-player' )
														),
														( ( '' != atts.background_color ) &&
															rs_el( 'style', {}, '.components-button.is-secondary.color-dropdown-background_color {background-color:'+atts.background_color+'}' )
														)
													)
												)
											} ) 
										)
									),
									
									/* --- Playing Color --- */
									rs_el( PanelRow, {},
										rs_el( BaseControl, {
											label: rs__( 'Playing Highlight', 'stream-player' ),
											className: 'color-dropdown-control'
										},
											rs_el( Dropdown, {
												renderContent: () => (
													rs_el( ColorPicker, {
														defaultValue: '',
														onChangeComplete: color => {
															props.setAttributes( {playing_color: color.hex} );
														},
														color: atts.playing_color
													})
												),
												renderToggle: (args) => (
													rs_el( 'div', {className: 'color-dropdown-buttons'},
														el ( Button, {
															className: 'color-dropdown-playing_color',
															onClick: args.onToggle,
															variant: 'secondary',
															'aria-expanded': args.isOpen,
															'aria-haspopup': 'true',
															'aria-label': rs__( 'Select Playing Highlight Color', 'stream-player' )
														},
														( ('' != atts.playing_color) ? atts.playing_color : rs__( 'Select', 'stream-player' ) )
														),
														rs_el( Button, {
															onClick: () => {
																props.setAttributes( {playing_color: ''} );
																args.onClose();
															},
															isSmall: true,
															variant: 'tertiary',
															'aria-label': rs__( 'Clear Playing Color Selection', 'stream-player' )
														},
														rs__( 'Clear', 'stream-player' )
														),
														( ( '' != atts.playing_color ) &&
															rs_el( 'style', {}, '.components-button.is-secondary.color-dropdown-playing_color {background-color:'+atts.playing_color+'}' )
														)
													)
												)
											} ) 
										)
									),
									
									/* --- Buttons Color --- */
									rs_el( PanelRow, {},
										rs_el( BaseControl, {
											label: rs__( 'Buttons Highlight', 'stream-player' ),
											className: 'color-dropdown-control'
										},
											rs_el( Dropdown, {
												renderContent: () => (
													rs_el( ColorPicker, {
														defaultValue: '',
														onChangeComplete: color => {
															props.setAttributes( {buttons_color: color.hex} );
														},
														color: atts.buttons_color
													})
												),
												renderToggle: (args) => (
													rs_el( 'div', {className: 'color-dropdown-buttons'},
														el ( Button, {
															className: 'color-dropdown-buttons_color',
															onClick: args.onToggle,
															variant: 'secondary',
															'aria-expanded': args.isOpen,
															'aria-haspopup': 'true',
															'aria-label': rs__( 'Select Button Highlight Color', 'stream-player' )
														},
														( ('' != atts.buttons_color) ? atts.buttons_color : rs__( 'Select', 'stream-player' ) )
														),
														rs_el( Button, {
															onClick: () => {
																props.setAttributes( {buttons_color: ''} );
																args.onClose();
															},
															isSmall: true,
															variant: 'tertiary',
															'aria-label': rs__( 'Clear Button Highlight Color Selection', 'stream-player' )
														},
														rs__( 'Clear', 'stream-player' )
														),
														( ( '' != atts.buttons_color ) &&
															rs_el( 'style', {}, '.components-button.is-secondary.color-dropdown-buttons_color {background-color:'+atts.buttons_color+'}' )
														)
													)
												)
											} ) 
										)
									),
									
									/* --- Track Color --- */
									rs_el( PanelRow, {},
										rs_el( BaseControl, {
											label: rs__( 'Volume Track', 'stream-player' ),
											className: 'color-dropdown-control'
										},
											rs_el( Dropdown, {
												renderContent: () => (
													rs_el( ColorPicker, {
														defaultValue: '',
														onChangeComplete: color => {
															props.setAttributes( {track_color: color.hex} );
														},
														color: atts.track_color
													})
												),
												renderToggle: (args) => (
													rs_el( 'div', {className: 'color-dropdown-buttons'},
														el ( Button, {
															className: 'color-dropdown-track_color',
															onClick: args.onToggle,
															variant: 'secondary',
															'aria-expanded': args.isOpen,
															'aria-haspopup': 'true',
															'aria-label': rs__( 'Select Volume Track Color', 'stream-player' )
														},
														( ('' != atts.track_color) ? atts.track_color : rs__( 'Select', 'stream-player' ) )
														),
														rs_el( Button, {
															onClick: () => {
																props.setAttributes( {track_color: ''} );
																args.onClose();
															},
															isSmall: true,
															variant: 'tertiary',
															'aria-label': rs__( 'Clear Volume Track Color Selection', 'stream-player' )
														},
														rs__( 'Clear', 'stream-player' )
														),
														( ( '' != atts.track_color ) &&
															rs_el( 'style', {}, '.components-button.is-secondary.color-dropdown-track_color {background-color:'+atts.track_color+'}' )
														)
													)
												)
											} ) 
										)
									),
									
									/* --- Thumb Color --- */
									rs_el( PanelRow, {},
										rs_el( BaseControl, {
											label: rs__( 'Volume Thumb', 'stream-player' ),
											className: 'color-dropdown-control'
										},
											rs_el( Dropdown, {
												renderContent: () => (
													rs_el( ColorPicker, {
														defaultValue: '',
														onChangeComplete: color => {
															props.setAttributes( {thumb_color: color.hex} );
														},
														color: atts.thumb_color
													})
												),
												renderToggle: (args) => (
													rs_el( 'div', {className: 'color-dropdown-buttons'},
														el ( Button, {
															className: 'color-dropdown-thumb_color',
															onClick: args.onToggle,
															variant: 'secondary',
															'aria-expanded': args.isOpen,
															'aria-haspopup': 'true',
															'aria-label': rs__( 'Select Volume Thumb Color', 'stream-player' )
														},
														( ('' != atts.thumb_color) ? atts.thumb_color : rs__( 'Select', 'stream-player' ) )
														),
														rs_el( Button, {
															onClick: () => {
																props.setAttributes( {thumb_color: ''} );
																args.onClose();
															},
															isSmall: true,
															variant: 'tertiary',
															'aria-label': rs__( 'Clear Volume Thumb Color Selection', 'stream-player' )
														},
														rs__( 'Clear', 'stream-player' )
														),
														( ( '' != atts.thumb_color ) &&
															rs_el( 'style', {}, '.components-button.is-secondary.color-dropdown-thumb_color {background-color:'+atts.thumb_color+'}' )
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
								rs_el( PanelBody, { title: rs__( 'Advanced Options', 'stream-player' ), className: 'stream-block-controls', initialOpen: true },
									/* --- Current Show Display --- */
									rs_el( PanelRow, {},
										rs_el( SelectControl, {
											label: rs__( 'Current Show Display', 'stream-player' ),
											options : [
												{ label: rs__( 'Plugin Setting', 'stream-player' ), value: 'default' },
												{ label: rs__( 'On', 'stream-player' ), value: 'on' },
												{ label: rs__( 'Off', 'stream-player' ), value: 'off' },
											],
											onChange: ( value ) => {
												props.setAttributes( { currentshow: value } );
											},
											value: atts.currentshow
										})
									),
									/* ---Now Playing Display --- */
									rs_el( PanelRow, {},
										rs_el( SelectControl, {
											label: rs__( 'Now Playing Track Display', 'stream-player' ),
											options : [
												{ label: rs__( 'Plugin Setting', 'stream-player' ), value: 'default' },
												{ label: rs__( 'On', 'stream-player' ), value: 'on' },
												{ label: rs__( 'Off', 'stream-player' ), value: 'off' },
											],
											onChange: ( value ) => {
												props.setAttributes( { nowplaying: value } );
											},
											value: atts.nowplaying
										})
									),
									/* --- Track Animation --- */
									rs_el( PanelRow, {},
										rs_el( SelectControl, {
											label: rs__( 'Track Animation', 'stream-player' ),
											options : [
												{ label: rs__( 'Plugin Setting', 'stream-player' ), value: 'default' },
												{ label: rs__( 'No Animation', 'stream-player' ), value: 'none' },
												{ label: rs__( 'Left to Right Ticker', 'stream-player' ), value: 'lefttoright' },
												{ label: rs__( 'Right to Left Ticker', 'stream-player' ), value: 'righttoleft' },
												{ label: rs__( 'Back and Forth', 'stream-player' ), value: 'backandforth' },
												{ label: rs__( '', 'stream-player' ), value: 'off' },
											],
											onChange: ( value ) => {
												props.setAttributes( { animation: value } );
											},
											value: atts.animation
										})
									),
									/* --- Metadata URL --- */
									rs_el( PanelRow, {},
										rs_el( TextControl, {
											label: rs__( 'Metadata Source URL', 'stream-player' ),
											help: rs__( 'Defaults to Stream URL.', 'stream-player' ),
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
