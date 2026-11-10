/**
 * === Stream Player Block ===
 */
(() => {

	/* --- Import Modules/Components --- */
	const sp_el = window.wp.element.createElement;
	const sp__ = window.wp.i18n.__;
	const { serverSideRender: ServerSideRender } = window.wp;
	const { registerBlockType, getBlockType } = window.wp.blocks;
	const { InspectorControls, useBlockProps } = window.wp.blockEditor;
	const { Fragment, useEffect, createRef, useRef } = window.wp.element;
	const { BaseControl, TextControl, SelectControl, RadioControl, RangeControl, ToggleControl, ColorPicker, Dropdown, Button, Panel, PanelBody, PanelRow } = window.wp.components;
	
	/* --- Register Block --- */
	if ( !getBlockType('radio-station/player' ) ) {
	 registerBlockType( 'radio-station/player', {

		/* --- Block Settings --- */
		title: sp__( 'Stream Player', 'stream-player' ),
		description: sp__( 'Audio stream player block.', 'stream-player' ),
		icon: 'controls-volumeon',
		category: 'media',
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
			layout: { type: 'string', default: 'vertical' },
			theme: { type: 'string', default: 'default' },
			buttons: { type: 'string', default: 'default' },
			/* --- Hidden Switches --- */
			channel: { type: 'number', default: 0 },
			block: { type: 'boolean', default: true },
			pro: { type: 'boolean', default: false }
		},

		/**
		 * Edit Block Control
		 */
		edit: (props) => {
			const atts = props.attributes;
			let blockRef = createRef(null);
			const blockProps = useBlockProps({ ref: blockRef });

			/* load control colors on render */
			useEffect(() => {
				/* console.log('Player block rendered.', atts, blockProps); */
				radio_player_control_colors(blockProps.id, atts);
			}, [atts.text_color, atts.background_color, atts.playing_color, atts.button_color, atts.track_color, atts.thumb_color]);

			return (
				sp_el( Fragment, {},
					sp_el( ServerSideRender, { block: 'radio-station/player', className: 'radio-player-block', attributes: atts } ),
					sp_el( InspectorControls, {},
						sp_el( Panel, {},
							/* === Player Content === */
							sp_el( PanelBody, { title: sp__( 'Player Content', 'stream-player' ), className: 'stream-block-controls', initialOpen: true },
								/* --- Stream URL --- */
								sp_el( PanelRow, {},
									sp_el( TextControl, {
										label: sp__( 'Stream URL', 'stream-player' ),
										help: sp__( 'Leave blank to use default stream.', 'stream-player' ),
										/* 2.7.1: show the default stream URL from the settings page */
										placeholder: ( ( typeof stream_player_block_defaults !== 'undefined' ) && stream_player_block_defaults.url ) ? stream_player_block_defaults.url : '',
										onChange: ( value ) => {
											props.setAttributes( { url: value } );
										},
										value: atts.url,
									})
								),
								/* --- Player Title Text --- */
								sp_el( PanelRow, {},
									sp_el( TextControl, {
										label: sp__( 'Player Title Text', 'stream-player' ),
										help: sp__( 'Empty for default, 0 for none.', 'stream-player' ),
										onChange: ( value ) => {
											props.setAttributes( { title: value } );
										},
										value: atts.title
									})
								),
								/* --- Image --- */
								sp_el( PanelRow, {},
									sp_el( SelectControl, {
										label: sp__( 'Player Image', 'stream-player' ),
										options : [
											{ label: sp__( 'Plugin Setting', 'stream-player' ), value: 'default' },
											{ label: sp__( 'Display Station Image', 'stream-player' ), value: '1' },
											{ label: sp__( 'Do Not Display Station Image', 'stream-player' ), value: '0' },
											/* { label: sp__( 'Display Custom Image', 'stream-player' ), value: 'custom' }, */
										],
										onChange: ( value ) => {
											props.setAttributes( { image: value } );
										},
										value: atts.image
									})
								)
							),

							/* === Player Options === */
							sp_el( PanelBody, { title: sp__( 'Player Options', 'stream-player' ), className: 'stream-block-controls', initialOpen: true },
								/* --- Script --- */
								sp_el( PanelRow, {},
									sp_el( SelectControl, {
										label: sp__( 'Player Script', 'stream-player' ),
										options : [
											{ label: sp__( 'Plugin Setting', 'stream-player' ), value: 'default' },
											{ label: sp__( 'Amplitude', 'stream-player' ), value: 'amplitude' },
											{ label: sp__( 'Howler', 'stream-player' ), value: 'howler' },
											{ label: sp__( 'jPlayer', 'stream-player' ), value: 'jplayer' },
										],
										onChange: ( value ) => {
											props.setAttributes( { script: value } );
										},
										value: atts.script
									})
								),
								/* --- Volume --- */
								sp_el( PanelRow, {},
									sp_el( RangeControl, {
										label: sp__( 'Initial Volume', 'stream-player' ),
										min: 0,
										max: 100,
										onChange: ( value ) => {
											props.setAttributes( { volume: value } );
										},
										value: atts.volume
									})
								),
								/* --- Volume controls --- */
								sp_el( PanelRow, {},
									sp_el( SelectControl, {
										multiple: true,
										label: sp__( 'Volume Controls', 'stream-player' ),
										help: sp__( 'Ctrl-Click to select multiple controls.', 'stream-player' ),
										options: [
											{ label: sp__( 'Volume Slider', 'stream-player' ), value: 'slider' },
											{ label: sp__( 'Up and Down Buttons', 'stream-player' ), value: 'updown' },
											{ label: sp__( 'Mute Button', 'stream-player' ), value: 'mute' },
											{ label: sp__( 'Maximize Button', 'stream-player' ), value: 'max' },
										],
										onChange: ( value ) => {
											props.setAttributes( { volumes: value } );
										},
										value: atts.volumes
									})
								),
								/* --- Default Player --- */
								sp_el( PanelRow, {},
									sp_el( ToggleControl, {
										label: sp__( 'Use as Default Player', 'stream-player' ),
										help: sp__( 'Make this the default player on this page.', 'stream-player' ),
										onChange: ( value ) => {
											props.setAttributes( { default: value } );
										},
										checked: atts.default,
									})
								),
								/* --- Popup Player Button --- */
								sp_el( PanelRow, {},
									( ( atts.pro ) && 
										sp_el( SelectControl, {
											label: sp__( 'Popup Player', 'stream-player' ),
											help: sp__( 'Enables button to open Player in separate window.', 'stream-player' ),
											options : [
												{ label: sp__( 'Plugin Setting', 'stream-player' ), value: 'default' },
												{ label: sp__( 'On', 'stream-player' ), value: 'on' },
												{ label: sp__( 'Off', 'stream-player' ), value: 'off' },
											],
											onChange: ( value ) => {
												props.setAttributes( { popup: value } );
											},
											value: atts.popup
										})
									), ( ( !atts.pro ) &&
										sp_el( BaseControl, {
											label: sp__( 'Popup Player', 'stream-player' ),
											help: sp__( 'Popup Player Button available in Pro.', 'stream-player' ),
										})
									)
								),
							),

							/* === Player Styles === */
							sp_el( PanelBody, { title: sp__( 'Player Design', 'stream-player' ), className: 'stream-block-controls', initialOpen: true },
								/* --- Player Layout --- */
								/* 2.7.1: layout choice is a Pro option (free is stacked) */
								sp_el( PanelRow, {},
									( ( atts.pro ) &&
									sp_el( RadioControl, {
										label: sp__( 'Player Layout', 'stream-player' ),
										options : [
											{ label: sp__( 'Vertical (Stacked)', 'stream-player' ), value: 'vertical' },
											{ label: sp__( 'Horizontal (Inline)', 'stream-player' ), value: 'horizontal' },
										],
										onChange: ( value ) => {
											props.setAttributes( { layout: value } );
										},
										checked: atts.layout
									})
									), ( ( !atts.pro ) &&
										sp_el( BaseControl, {
											label: sp__( 'Player Layout', 'stream-player' ),
											help: sp__( 'Horizontal (Inline) layout available in Pro.', 'stream-player' ),
										})
									)
								),
								/* --- Player Theme --- */
								( ( !atts.pro ) &&
									sp_el( PanelRow, {},
										sp_el( SelectControl, {
											label: sp__( 'Player Theme', 'stream-player' ),
											options : [
												{ label: sp__( 'Plugin Setting', 'stream-player' ), value: 'default' },
												{ label: sp__( 'Light', 'stream-player' ), value: 'light' },
												{ label: sp__( 'Dark', 'stream-player' ), value: 'dark' },
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
									sp_el( PanelRow, {},
										sp_el( SelectControl, {
											label: sp__( 'Player Theme', 'stream-player' ),
											options : [
												{ label: sp__( 'Plugin Setting', 'stream-player' ), value: 'default' },
												{ label: sp__( 'Light', 'stream-player' ), value: 'light' },
												{ label: sp__( 'Dark', 'stream-player' ), value: 'dark' },
												{ label: sp__( 'Red', 'stream-player' ), value: 'red' },
												{ label: sp__( 'Orange', 'stream-player' ), value: 'orange' },
												{ label: sp__( 'Yellow', 'stream-player' ), value: 'yellow' },
												{ label: sp__( 'Light Green', 'stream-player' ), value: 'light-green' },
												{ label: sp__( 'Green', 'stream-player' ), value: 'green' },
												{ label: sp__( 'Cyan', 'stream-player' ), value: 'cyan' },
												{ label: sp__( 'Light Blue', 'stream-player' ), value: 'light-blue' },
												{ label: sp__( 'Blue', 'stream-player' ), value: 'blue' },
												{ label: sp__( 'Purple', 'stream-player' ), value: 'purple' },
												{ label: sp__( 'Magenta', 'stream-player' ), value: 'magenta' },
											],
											onChange: ( value ) => {
												props.setAttributes( { theme: value } );
											},
											value: atts.theme
										})
									)
								),
								/* --- Player Buttons --- */
								sp_el( PanelRow, {},
									sp_el( SelectControl, {
										label: sp__( 'Player Buttons', 'stream-player' ),
										options : [
											{ label: sp__( 'Plugin Setting', 'stream-player' ), value: 'default' },
											/* 2.7.1: added solid button options */
											{ label: sp__( 'Solid', 'stream-player' ), value: 'solid' },
											{ label: sp__( 'Semi-solid', 'stream-player' ), value: 'semisolid' },
											{ label: sp__( 'Circular', 'stream-player' ), value: 'circular' },
											{ label: sp__( 'Rounded', 'stream-player' ), value: 'rounded' },
											{ label: sp__( 'Square', 'stream-player' ), value: 'square' },
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
								sp_el( PanelBody, { title: sp__( 'Player Colors', 'stream-player' ), className: 'stream-block-controls', initialOpen: true },
								
									/* --- Text Color --- */
									sp_el( PanelRow, {},
										sp_el( BaseControl, {
											label: sp__( 'Text Color', 'stream-player' ),
											className: 'color-dropdown-control'
										},
											sp_el( Dropdown, {
												renderContent: () => (
													sp_el( ColorPicker, {
														disableAlpha: true,
														defaultValue: '',
														onChangeComplete: color => {
															props.setAttributes( {text_color: color.hex} );
														},
														color: atts.text_color
													})
												),
												renderToggle: (args) => (
													sp_el( 'div', {className: 'color-dropdown-buttons'},
														sp_el( Button, {
															className: 'color-dropdown-text_color',
															onClick: args.onToggle,
															variant: 'secondary',
															'aria-expanded': args.isOpen,
															'aria-haspopup': 'true',
															'aria-label': sp__( 'Select Text Color', 'stream-player' )
														},
														( ('' != atts.text_color) ? atts.text_color : sp__( 'Select', 'stream-player' ) )
														),
														sp_el( Button, {
															onClick: () => {
																props.setAttributes( {text_color: ''} );
																args.onClose();
															},
															isSmall: true,
															variant: 'tertiary',
															'aria-label': sp__( 'Clear Text Color Selection', 'stream-player' )
														},
														sp__( 'Clear', 'stream-player' )
														),
														( ( '' != atts.text_color ) &&
															sp_el( 'style', {}, '.components-button.is-secondary.color-dropdown-text_color {background-color:'+atts.text_color+'}' )
														)
													)
												)
											} ) 
										)
									),

									/* --- Background Color --- */
									sp_el( PanelRow, {},
										sp_el( BaseControl, {
											label: sp__( 'Background Color', 'stream-player' ),
											className: 'color-dropdown-control'
										},
											sp_el( Dropdown, {
												renderContent: () => (
													sp_el( ColorPicker, {
														defaultValue: '',
														onChangeComplete: color => {
															props.setAttributes( {background_color: color.hex} );
														},
														color: atts.background_color
													})
												),
												renderToggle: (args) => (
													sp_el( 'div', {className: 'color-dropdown-buttons'},
														sp_el( Button, {
															className: 'color-dropdown-background_color',
															onClick: args.onToggle,
															variant: 'secondary',
															'aria-expanded': args.isOpen,
															'aria-haspopup': 'true',
															'aria-label': sp__( 'Select Background Color', 'stream-player' )
														},
														( ('' != atts.background_color) ? atts.background_color : sp__( 'Select', 'stream-player' ) )
														),
														sp_el( Button, {
															onClick: () => {
																props.setAttributes( {background_color: ''} );
																args.onClose();
															},
															isSmall: true,
															variant: 'tertiary',
															'aria-label': sp__( 'Clear Background Color Selection', 'stream-player' )
														},
														sp__( 'Clear', 'stream-player' )
														),
														( ( '' != atts.background_color ) &&
															sp_el( 'style', {}, '.components-button.is-secondary.color-dropdown-background_color {background-color:'+atts.background_color+'}' )
														)
													)
												)
											} ) 
										)
									),
									
									/* --- Playing Color --- */
									sp_el( PanelRow, {},
										sp_el( BaseControl, {
											label: sp__( 'Playing Highlight', 'stream-player' ),
											className: 'color-dropdown-control'
										},
											sp_el( Dropdown, {
												renderContent: () => (
													sp_el( ColorPicker, {
														defaultValue: '',
														onChangeComplete: color => {
															props.setAttributes( {playing_color: color.hex} );
														},
														color: atts.playing_color
													})
												),
												renderToggle: (args) => (
													sp_el( 'div', {className: 'color-dropdown-buttons'},
														sp_el( Button, {
															className: 'color-dropdown-playing_color',
															onClick: args.onToggle,
															variant: 'secondary',
															'aria-expanded': args.isOpen,
															'aria-haspopup': 'true',
															'aria-label': sp__( 'Select Playing Highlight Color', 'stream-player' )
														},
														( ('' != atts.playing_color) ? atts.playing_color : sp__( 'Select', 'stream-player' ) )
														),
														sp_el( Button, {
															onClick: () => {
																props.setAttributes( {playing_color: ''} );
																args.onClose();
															},
															isSmall: true,
															variant: 'tertiary',
															'aria-label': sp__( 'Clear Playing Color Selection', 'stream-player' )
														},
														sp__( 'Clear', 'stream-player' )
														),
														( ( '' != atts.playing_color ) &&
															sp_el( 'style', {}, '.components-button.is-secondary.color-dropdown-playing_color {background-color:'+atts.playing_color+'}' )
														)
													)
												)
											} ) 
										)
									),
									
									/* --- Buttons Color --- */
									sp_el( PanelRow, {},
										sp_el( BaseControl, {
											label: sp__( 'Buttons Highlight', 'stream-player' ),
											className: 'color-dropdown-control'
										},
											sp_el( Dropdown, {
												renderContent: () => (
													sp_el( ColorPicker, {
														defaultValue: '',
														onChangeComplete: color => {
															props.setAttributes( {buttons_color: color.hex} );
														},
														color: atts.buttons_color
													})
												),
												renderToggle: (args) => (
													sp_el( 'div', {className: 'color-dropdown-buttons'},
														sp_el( Button, {
															className: 'color-dropdown-buttons_color',
															onClick: args.onToggle,
															variant: 'secondary',
															'aria-expanded': args.isOpen,
															'aria-haspopup': 'true',
															'aria-label': sp__( 'Select Button Highlight Color', 'stream-player' )
														},
														( ('' != atts.buttons_color) ? atts.buttons_color : sp__( 'Select', 'stream-player' ) )
														),
														sp_el( Button, {
															onClick: () => {
																props.setAttributes( {buttons_color: ''} );
																args.onClose();
															},
															isSmall: true,
															variant: 'tertiary',
															'aria-label': sp__( 'Clear Button Highlight Color Selection', 'stream-player' )
														},
														sp__( 'Clear', 'stream-player' )
														),
														( ( '' != atts.buttons_color ) &&
															sp_el( 'style', {}, '.components-button.is-secondary.color-dropdown-buttons_color {background-color:'+atts.buttons_color+'}' )
														)
													)
												)
											} ) 
										)
									),
									
									/* --- Track Color --- */
									sp_el( PanelRow, {},
										sp_el( BaseControl, {
											label: sp__( 'Volume Track', 'stream-player' ),
											className: 'color-dropdown-control'
										},
											sp_el( Dropdown, {
												renderContent: () => (
													sp_el( ColorPicker, {
														defaultValue: '',
														onChangeComplete: color => {
															props.setAttributes( {track_color: color.hex} );
														},
														color: atts.track_color
													})
												),
												renderToggle: (args) => (
													sp_el( 'div', {className: 'color-dropdown-buttons'},
														sp_el( Button, {
															className: 'color-dropdown-track_color',
															onClick: args.onToggle,
															variant: 'secondary',
															'aria-expanded': args.isOpen,
															'aria-haspopup': 'true',
															'aria-label': sp__( 'Select Volume Track Color', 'stream-player' )
														},
														( ('' != atts.track_color) ? atts.track_color : sp__( 'Select', 'stream-player' ) )
														),
														sp_el( Button, {
															onClick: () => {
																props.setAttributes( {track_color: ''} );
																args.onClose();
															},
															isSmall: true,
															variant: 'tertiary',
															'aria-label': sp__( 'Clear Volume Track Color Selection', 'stream-player' )
														},
														sp__( 'Clear', 'stream-player' )
														),
														( ( '' != atts.track_color ) &&
															sp_el( 'style', {}, '.components-button.is-secondary.color-dropdown-track_color {background-color:'+atts.track_color+'}' )
														)
													)
												)
											} ) 
										)
									),
									
									/* --- Thumb Color --- */
									sp_el( PanelRow, {},
										sp_el( BaseControl, {
											label: sp__( 'Volume Thumb', 'stream-player' ),
											className: 'color-dropdown-control'
										},
											sp_el( Dropdown, {
												renderContent: () => (
													sp_el( ColorPicker, {
														defaultValue: '',
														onChangeComplete: color => {
															props.setAttributes( {thumb_color: color.hex} );
														},
														color: atts.thumb_color
													})
												),
												renderToggle: (args) => (
													sp_el( 'div', {className: 'color-dropdown-buttons'},
														sp_el( Button, {
															className: 'color-dropdown-thumb_color',
															onClick: args.onToggle,
															variant: 'secondary',
															'aria-expanded': args.isOpen,
															'aria-haspopup': 'true',
															'aria-label': sp__( 'Select Volume Thumb Color', 'stream-player' )
														},
														( ('' != atts.thumb_color) ? atts.thumb_color : sp__( 'Select', 'stream-player' ) )
														),
														sp_el( Button, {
															onClick: () => {
																props.setAttributes( {thumb_color: ''} );
																args.onClose();
															},
															isSmall: true,
															variant: 'tertiary',
															'aria-label': sp__( 'Clear Volume Thumb Color Selection', 'stream-player' )
														},
														sp__( 'Clear', 'stream-player' )
														),
														( ( '' != atts.thumb_color ) &&
															sp_el( 'style', {}, '.components-button.is-secondary.color-dropdown-thumb_color {background-color:'+atts.thumb_color+'}' )
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
								sp_el( PanelBody, { title: sp__( 'Advanced Options', 'stream-player' ), className: 'stream-block-controls', initialOpen: true },
									/* --- Current Show Display --- */
									sp_el( PanelRow, {},
										sp_el( SelectControl, {
											label: sp__( 'Current Show Display', 'stream-player' ),
											options : [
												{ label: sp__( 'Plugin Setting', 'stream-player' ), value: 'default' },
												{ label: sp__( 'On', 'stream-player' ), value: 'on' },
												{ label: sp__( 'Off', 'stream-player' ), value: 'off' },
											],
											onChange: ( value ) => {
												props.setAttributes( { currentshow: value } );
											},
											value: atts.currentshow
										})
									),
									/* ---Now Playing Display --- */
									sp_el( PanelRow, {},
										sp_el( SelectControl, {
											label: sp__( 'Now Playing Track Display', 'stream-player' ),
											options : [
												{ label: sp__( 'Plugin Setting', 'stream-player' ), value: 'default' },
												{ label: sp__( 'On', 'stream-player' ), value: 'on' },
												{ label: sp__( 'Off', 'stream-player' ), value: 'off' },
											],
											onChange: ( value ) => {
												props.setAttributes( { nowplaying: value } );
											},
											value: atts.nowplaying
										})
									),
									/* --- Track Animation --- */
									sp_el( PanelRow, {},
										sp_el( SelectControl, {
											label: sp__( 'Track Animation', 'stream-player' ),
											options : [
												{ label: sp__( 'Plugin Setting', 'stream-player' ), value: 'default' },
												{ label: sp__( 'No Animation', 'stream-player' ), value: 'none' },
												{ label: sp__( 'Left to Right Ticker', 'stream-player' ), value: 'lefttoright' },
												{ label: sp__( 'Right to Left Ticker', 'stream-player' ), value: 'righttoleft' },
												{ label: sp__( 'Back and Forth', 'stream-player' ), value: 'backandforth' },
												{ label: sp__( '', 'stream-player' ), value: 'off' },
											],
											onChange: ( value ) => {
												props.setAttributes( { animation: value } );
											},
											value: atts.animation
										})
									),
									/* --- Metadata URL --- */
									sp_el( PanelRow, {},
										sp_el( TextControl, {
											label: sp__( 'Metadata Source URL', 'stream-player' ),
											help: sp__( 'Defaults to Stream URL.', 'stream-player' ),
											onChange: ( value ) => {
												props.setAttributes( { metadata: value } );
											},
											value: atts.metadata
										})
									),
								)
							),

							/* 2.7.1: Spectrum Analyzer upsell */
							( ( !atts.pro ) &&
								sp_el( PanelBody, { title: sp__( 'Spectrum Analyzer', 'stream-player' ), className: 'stream-block-controls', initialOpen: false },
									sp_el( PanelRow, {},
										sp_el( BaseControl, {
											label: sp__( 'Spectrum Analyzer', 'stream-player' ),
											help: sp__( 'Real-time spectrum analyzer available in Pro.', 'stream-player' ),
										})
									)
								)
							),

							/* === Spectrum Analyzer === */
							/* 2.7.1: per-instance override of the Widget Player Settings
							   section; 'default'/'' means inherit the global setting */
							( ( atts.pro ) &&
								sp_el( PanelBody, { title: sp__( 'Spectrum Analyzer', 'stream-player' ), className: 'stream-block-controls', initialOpen: false },
									sp_el( PanelRow, {},
										sp_el( SelectControl, {
											label: sp__( 'Spectrum Analyzer', 'stream-player' ),
											options : [
												{ label: sp__( 'Plugin Setting', 'stream-player' ), value: 'default' },
												{ label: sp__( 'On', 'stream-player' ), value: 'on' },
												{ label: sp__( 'Off', 'stream-player' ), value: 'off' },
											],
											onChange: ( value ) => {
												props.setAttributes( { analyzer: value } );
											},
											value: atts.analyzer
										})
									),
									sp_el( PanelRow, {},
										sp_el( SelectControl, {
											label: sp__( 'Analyzer Placement', 'stream-player' ),
											options : [
												{ label: sp__( 'Plugin Setting', 'stream-player' ), value: 'default' },
												{ label: sp__( 'Top (Above Widget)', 'stream-player' ), value: 'top' },
												{ label: sp__( 'Background (Behind Widget Content)', 'stream-player' ), value: 'background' },
											],
											onChange: ( value ) => {
												props.setAttributes( { analyzer_placement: value } );
											},
											value: atts.analyzer_placement
										})
									),
									sp_el( PanelRow, {},
										sp_el( SelectControl, {
											label: sp__( 'Analyzer Color Theme', 'stream-player' ),
											options : [
												{ label: sp__( 'Plugin Setting', 'stream-player' ), value: 'default' },
												{ label: sp__( 'Classic', 'stream-player' ), value: 'classic' },
												{ label: sp__( 'Orange Red', 'stream-player' ), value: 'orangered' },
												{ label: sp__( 'Prism', 'stream-player' ), value: 'prism' },
												{ label: sp__( 'Rainbow', 'stream-player' ), value: 'rainbow' },
												{ label: sp__( 'Steel Blue', 'stream-player' ), value: 'steelblue' },
											],
											onChange: ( value ) => {
												props.setAttributes( { analyzer_gradient: value } );
											},
											value: atts.analyzer_gradient
										})
									),
									sp_el( PanelRow, {},
										sp_el( SelectControl, {
											label: sp__( 'Analyzer Style', 'stream-player' ),
											options : [
												{ label: sp__( 'Plugin Setting', 'stream-player' ), value: 'default' },
												{ label: sp__( 'Bars', 'stream-player' ), value: 'bars' },
												{ label: sp__( 'Fine Bars', 'stream-player' ), value: 'finebars' },
												{ label: sp__( 'Line Graph', 'stream-player' ), value: 'graph' },
											],
											onChange: ( value ) => {
												props.setAttributes( { analyzer_style: value } );
											},
											value: atts.analyzer_style
										})
									),
									sp_el( PanelRow, {},
										sp_el( SelectControl, {
											label: sp__( 'Analyzer Sensitivity', 'stream-player' ),
											options : [
												{ label: sp__( 'Plugin Setting', 'stream-player' ), value: 'default' },
												{ label: sp__( 'Low', 'stream-player' ), value: 'low' },
												{ label: sp__( 'Medium', 'stream-player' ), value: 'medium' },
												{ label: sp__( 'High', 'stream-player' ), value: 'high' },
											],
											onChange: ( value ) => {
												props.setAttributes( { analyzer_sensitivity: value } );
											},
											value: atts.analyzer_sensitivity
										})
									),
									sp_el( PanelRow, {},
										sp_el( SelectControl, {
											label: sp__( 'Enable Analyzer on Mobile', 'stream-player' ),
											options : [
												{ label: sp__( 'Plugin Setting', 'stream-player' ), value: 'default' },
												{ label: sp__( 'On', 'stream-player' ), value: 'on' },
												{ label: sp__( 'Off', 'stream-player' ), value: 'off' },
											],
											onChange: ( value ) => {
												props.setAttributes( { analyzer_mobile: value } );
											},
											value: atts.analyzer_mobile
										})
									),
									/* --- Opacity: 0 means "not set" -- RangeControl has no native
									   empty state, so 0 is used as the inherit sentinel and mapped
									   to/from '' on the attribute (opacity's own valid range starts
									   at 10, so 0 is otherwise unused) --- */
									sp_el( PanelRow, {},
										sp_el( RangeControl, {
											label: sp__( 'Analyzer Opacity (%)', 'stream-player' ),
											help: sp__( '0 = Plugin Setting.', 'stream-player' ),
											min: 0,
											max: 100,
											step: 5,
											allowReset: true,
											onChange: ( value ) => {
												props.setAttributes( { analyzer_opacity: ( value ? String( value ) : '' ) } );
											},
											value: atts.analyzer_opacity ? parseInt( atts.analyzer_opacity, 10 ) : 0
										})
									),
									sp_el( PanelRow, {},
										sp_el( RangeControl, {
											label: sp__( 'Analyzer Height (%)', 'stream-player' ),
											help: sp__( '0 = Plugin Setting.', 'stream-player' ),
											min: 0,
											max: 100,
											step: 5,
											allowReset: true,
											onChange: ( value ) => {
												props.setAttributes( { analyzer_height: ( value ? String( value ) : '' ) } );
											},
											value: atts.analyzer_height ? parseInt( atts.analyzer_height, 10 ) : 0
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

/* Load Player Controls Colors */
function radio_player_control_colors(id, atts) {
	container = jQuery('#'+id+' .radio-container'); /* console.log(container); */
	if (!container.length) { setTimeout(function() {radio_player_control_colors(id,atts);}, 1000); return; }
	instance = container.attr('id').replace('radio_container_','');
	url = radio_player.settings.ajaxurl+'?action=player_control_styles&instance='+instance+'&text='+encodeURIComponent(atts.text_color)+'&background='+encodeURIComponent(atts.background_color)+'&playing='+encodeURIComponent(atts.playing_color)+'&buttons='+encodeURIComponent(atts.buttons_color)+'&track='+encodeURIComponent(atts.track_color)+'&thumb='+encodeURIComponent(atts.thumb_color);
	jQuery.ajax({
		type: 'GET',
		url: url,
		data: {'action':'player_control_styles', 'instance':instance, 'text':atts.text_color, 'background':atts.background_color, 'playing':atts.playing_color, 'buttons':atts.buttons_color, 'track':atts.track_color, 'thumb':atts.thumb_color},
		processData: false,
		beforeSend: function(request, settings) {
			request._data = settings.data; 
		},
		success: function(data, success, request) {
			if (data.success) {
				console.log('Load Control Styles Success: '+data.message);
				if (jQuery('#radio-player-control-styles-'+data.instance).length) {jQuery('#radio-player-control-styles-'+data.instance).remove();}
				jQuery('body').append('<style id="radio-player-control-styles-'+data.instance+'">'+data.css+'</style>');
			} else {console.log('Load Control Styles Failed: '+data.message); console.log(request);}
		},
		fail: function(request, textStatus, errorThrown) {
			console.log(request); console.log(textStatus); console.log(errorThrown);
		}
	}).catch(function(error) {
		console.log(error); console.log(jQuery(this));
	});
}

