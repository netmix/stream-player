
/* === Stream Player Block Editor Scripts === */

const { Icon } = wp.components;

/* --- Subscribe to Block State --- */
( () => {
    let blocksState = wp.data.select( 'core/block-editor' ).getBlocks();
    wp.data.subscribe( _.debounce( ()=> {
        newBlocksState = wp.data.select( 'core/block-editor' ).getBlocks();
        if ( blocksState.length !== newBlocksState.length ) {

            /* --- recheck for needed scripts --- */
			player = false;
			for ( i = 0; i < newBlocksState.length; i++ ) {
				block = newBlocksState[i];
				if ( block.name == 'radio-station/player' ) {
					stream_player_load_block_script('player');
				}
			}
        }
        blocksState = newBlocksState;
    }, 300 ) );
} )();

/* --- Load Block Script --- */
function stream_player_load_block_script(handle) {
	id = 'stream-'+handle+'-js';
	if (!document.getElementById(id)) {
		jQuery('html head').append('<script id="'+id+'" src="'+stream_player_script+'"></script>');
	}
	if (typeof stream_player_pro_load_block_script == 'function') {
		stream_player_pro_load_block_script(handle);
	}
}
