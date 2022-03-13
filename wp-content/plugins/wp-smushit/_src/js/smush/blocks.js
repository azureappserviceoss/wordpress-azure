/**
 * BLOCK: extend image block
 */
const { createHigherOrderComponent } = wp.compose,
	{ Fragment } = wp.element,
	{ InspectorControls } = wp.blockEditor,
	{ PanelBody } = wp.components;

/**
 * Transform bytes to human readable format.
 *
 * @param {number} bytes
 * @return {string}  Readable size string.
 */
function humanFileSize( bytes ) {
	const thresh = 1024,
		units = [ 'kB', 'MB', 'GB', 'TB' ];

	if ( Math.abs( bytes ) < thresh ) {
		return bytes + ' B';
	}

	let u = -1;
	do {
		bytes /= thresh;
		++u;
	} while ( Math.abs( bytes ) >= thresh && u < units.length - 1 );

	return bytes.toFixed( 1 ) + ' ' + units[ u ];
}

/**
 * Generate Smush stats table.
 *
 * @param {number} id
 * @param {Object} stats
 * @return {*}  Smush stats.
 */
export function smushStats( id, stats ) {
	if ( 'undefined' === typeof stats ) {
		return window.smush_vars.strings.gb.select_image;
	} else if ( 'string' === typeof stats ) {
		return stats;
	}

	return (
		<div
			id="smush-stats"
			className="sui-smush-media smush-stats-wrapper hidden"
			style={ { display: 'block' } }
		>
			<table className="wp-smush-stats-holder">
				<thead>
					<tr>
						<th className="smush-stats-header">
							{ window.smush_vars.strings.gb.size }
						</th>
						<th className="smush-stats-header">
							{ window.smush_vars.strings.gb.savings }
						</th>
					</tr>
				</thead>
				<tbody>
					{ Object.keys( stats.sizes )
						.filter( ( item ) => 0 < stats.sizes[ item ].percent )
						.map( ( item, i ) => (
							<tr key={ i }>
								<td>{ item.toUpperCase() }</td>
								<td>
									{ humanFileSize(
										stats.sizes[ item ].bytes
									) }{ ' ' }
									( { stats.sizes[ item ].percent }% )
								</td>
							</tr>
						) ) }
				</tbody>
			</table>
		</div>
	);
}

/**
 * Fetch image data. If image is Smushing, update in 3 seconds.
 *
 * TODO: this could be optimized not to query so much.
 *
 * @param {Object} props
 */
export function fetchProps( props ) {
	const image = new wp.api.models.Media( { id: props.attributes.id } ),
		smushData = props.attributes.smush;

	image.fetch( { attribute: 'smush' } ).done( function( img ) {
		if ( 'string' === typeof img.smush ) {
			props.setAttributes( { smush: img.smush } );
			//setTimeout( () => fetch( props ), 3000 );
		} else if (
			'undefined' !== typeof img.smush &&
			( 'undefined' === typeof smushData ||
				JSON.stringify( smushData ) !== JSON.stringify( img.smush ) )
		) {
			props.setAttributes( { smush: img.smush } );
		}
	} );
}

/**
 * Modify the block’s edit component.
 * Receives the original block BlockEdit component and returns a new wrapped component.
 */
const smushStatsControl = createHigherOrderComponent( ( BlockEdit ) => {
	return ( props ) => {
		// If not image block or not selected, return unmodified block.
		if (
			'core/image' !== props.name ||
			! props.isSelected ||
			'undefined' === typeof props.attributes.id
		) {
			return (
				<Fragment>
					<BlockEdit { ...props } />
				</Fragment>
			);
		}

		const smushData = props.attributes.smush;
		fetchProps( props );

		return (
			<Fragment>
				<BlockEdit { ...props } />
				<InspectorControls>
					<PanelBody title={ window.smush_vars.strings.gb.stats }>
						{ smushStats( props.attributes.id, smushData ) }
					</PanelBody>
				</InspectorControls>
			</Fragment>
		);
	};
}, 'withInspectorControl' );

wp.hooks.addFilter(
	'editor.BlockEdit',
	'wp-smush/smush-data-control',
	smushStatsControl
);
