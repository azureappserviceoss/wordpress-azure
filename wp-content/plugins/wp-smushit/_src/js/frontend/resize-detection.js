import '../../scss/resize-detection.scss';

/**
 * Image resize detection (IRD).
 *
 * Show all wrongly scaled images with a highlighted border and resize box.
 *
 * Made in pure JS.
 * DO NOT ADD JQUERY SUPPORT!!!
 *
 * @since 2.9
 */
( function() {
	'use strict';

	const SmushIRS = {
		bar: document.getElementById( 'smush-image-bar' ),
		toggle: document.getElementById( 'smush-image-bar-toggle' ),
		images: {
			bigger: [],
			smaller: [],
		},
		strings: window.wp_smush_resize_vars,

		/**
		 * Init scripts.
		 */
		init() {
			/**
			 * Make sure these are set, before we proceed.
			 */
			if ( ! this.bar ) {
				this.bar = document.getElementById( 'smush-image-bar' );
			}
			if ( ! this.toggle ) {
				this.toggle = document.getElementById(
					'smush-image-bar-toggle'
				);
			}

			this.process();

			// Register the event handler after everything is done.
			this.toggle.addEventListener(
				'click',
				this.handleToggleClick.bind( this )
			);
		},

		/**
		 * Do image processing.
		 */
		process() {
			const icon = this.toggle.querySelector( 'i' );

			icon.classList.add( 'sui-icon-loader' );
			icon.classList.remove( 'sui-icon-info' );

			this.detectImages();

			if ( ! this.images.bigger.length && ! this.images.smaller.length ) {
				this.toggle.classList.add( 'smush-toggle-success' );
				document.getElementById(
					'smush-image-bar-notice'
				).style.display = 'block';
				document.getElementById(
					'smush-image-bar-notice-desc'
				).style.display = 'none';
			} else {
				this.toggle.classList.remove( 'smush-toggle-success' );
				document.getElementById(
					'smush-image-bar-notice'
				).style.display = 'none';
				document.getElementById(
					'smush-image-bar-notice-desc'
				).style.display = 'block';
				this.generateMarkup( 'bigger' );
				this.generateMarkup( 'smaller' );
			}

			this.toggleDivs();

			icon.classList.remove( 'sui-icon-loader' );
			icon.classList.add( 'sui-icon-info' );
		},

		/**
		 * Various checks to see if the image should be processed.
		 *
		 * @param {Object} image
		 * @return {boolean}  Should skip image or not.
		 */
		shouldSkipImage( image ) {
			// Skip avatars.
			if ( image.classList.contains( 'avatar' ) ) {
				return true;
			}

			// Skip images from Smush CDN with auto-resize feature.
			if (
				'string' === typeof image.getAttribute( 'no-resize-detection' )
			) {
				return true;
			}

			// Skip 1x1px images.
			if (
				image.clientWidth === image.clientHeight &&
				1 === image.clientWidth
			) {
				return true;
			}

			// Skip 1x1px placeholders.
			if (
				image.naturalWidth === image.naturalHeight &&
				1 === image.naturalWidth
			) {
				return true;
			}

			// If width attribute is not set, do not continue.
			return null === image.clientWidth || null === image.clientHeight;
		},

		/**
		 * Get tooltip text.
		 *
		 * @param {Object} props
		 * @return {string}  Tooltip.
		 */
		getTooltipText( props ) {
			let tooltipText = '';

			if ( props.bigger_width || props.bigger_height ) {
				/** @param {string} strings.large_image */
				tooltipText = this.strings.large_image;
			} else if ( props.smaller_width || props.smaller_height ) {
				/** @param {string} strings.small_image */
				tooltipText = this.strings.small_image;
			}

			return tooltipText
				.replace( 'width', props.real_width )
				.replace( 'height', props.real_height );
		},

		/**
		 * Generate markup.
		 *
		 * @param {string} type  Accepts: 'bigger' or 'smaller'.
		 */
		generateMarkup( type ) {
			this.images[ type ].forEach( ( image, index ) => {
				const item = document.createElement( 'div' ),
					tooltip = this.getTooltipText( image.props );

				item.setAttribute(
					'class',
					'smush-resize-box smush-tooltip smush-tooltip-constrained'
				);
				item.setAttribute( 'data-tooltip', tooltip );
				item.setAttribute( 'data-image', image.class );
				item.addEventListener( 'click', ( e ) =>
					this.highlightImage( e )
				);

				item.innerHTML = `
					<div class="smush-image-info">
						<span>${ index + 1 }</span>
						<span class="smush-tag">${ image.props.computed_width } x ${
					image.props.computed_height
				}px</span>
						<i class="smush-front-icons smush-front-icon-arrows-in" aria-hidden="true">&nbsp;</i>
						<span class="smush-tag smush-tag-success">${ image.props.real_width } × ${
					image.props.real_height
				}px</span>					
					</div>
					<div class="smush-image-description">${ tooltip }</div>
				`;

				document
					.getElementById( 'smush-image-bar-items-' + type )
					.appendChild( item );
			} );
		},

		/**
		 * Show/hide sections based on images.
		 */
		toggleDivs() {
			const types = [ 'bigger', 'smaller' ];
			types.forEach( ( type ) => {
				const div = document.getElementById(
					'smush-image-bar-items-' + type
				);
				if ( 0 === this.images[ type ].length ) {
					div.style.display = 'none';
				} else {
					div.style.display = 'block';
				}
			} );
		},

		/**
		 * Scroll the selected image into view and highlight it.
		 *
		 * @param {Object} e
		 */
		highlightImage( e ) {
			this.removeSelection();

			const el = document.getElementsByClassName(
				e.currentTarget.dataset.image
			);
			if ( 'undefined' !== typeof el[ 0 ] ) {
				// Display description box.
				e.currentTarget.classList.toggle( 'show-description' );

				// Scroll and flash image.
				el[ 0 ].scrollIntoView( {
					behavior: 'smooth',
					block: 'center',
					inline: 'nearest',
				} );
				el[ 0 ].style.opacity = '0.5';
				setTimeout( () => {
					el[ 0 ].style.opacity = '1';
				}, 1000 );
			}
		},

		/**
		 * Handle click on the toggle item.
		 */
		handleToggleClick() {
			this.bar.classList.toggle( 'closed' );
			this.toggle.classList.toggle( 'closed' );
			this.removeSelection();
		},

		/**
		 * Remove selected items.
		 */
		removeSelection() {
			const items = document.getElementsByClassName( 'show-description' );
			if ( items.length > 0 ) {
				Array.from( items ).forEach( ( div ) =>
					div.classList.remove( 'show-description' )
				);
			}
		},

		/**
		 * Function to highlight all scaled images.
		 *
		 * Add yellow border and then show one small box to
		 * resize the images as per the required size, on fly.
		 */
		detectImages() {
			const images = document.getElementsByTagName( 'img' );
			for ( const image of images ) {
				if ( this.shouldSkipImage( image ) ) {
					continue;
				}

				// Get defined width and height.
				const props = {
					real_width: image.clientWidth,
					real_height: image.clientHeight,
					computed_width: image.naturalWidth,
					computed_height: image.naturalHeight,
					bigger_width: image.clientWidth * 1.5 < image.naturalWidth,
					bigger_height:
						image.clientHeight * 1.5 < image.naturalHeight,
					smaller_width: image.clientWidth > image.naturalWidth,
					smaller_height: image.clientHeight > image.naturalHeight,
				};

				// In case image is in correct size, do not continue.
				if (
					! props.bigger_width &&
					! props.bigger_height &&
					! props.smaller_width &&
					! props.smaller_height
				) {
					continue;
				}

				const imgType =
						props.bigger_width || props.bigger_height
							? 'bigger'
							: 'smaller',
					imageClass =
						'smush-image-' + ( this.images[ imgType ].length + 1 );

				// Fill the images arrays.
				this.images[ imgType ].push( {
					src: image,
					props,
					class: imageClass,
				} );

				/**
				 * Add class to original image.
				 * Can't add two classes in single add(), because no support in IE11.
				 * image.classList.add('smush-detected-img', imageClass);
				 */
				image.classList.add( 'smush-detected-img' );
				image.classList.add( imageClass );
			}
		}, // End detectImages()

		/**
		 * Allows refreshing the list. A good way is to refresh on lazyload actions.
		 *
		 * @since 3.6.0
		 */
		refresh() {
			// Clear out classes on DOM.
			for ( let id in this.images.bigger ) {
				if ( this.images.bigger.hasOwnProperty( id ) ) {
					this.images.bigger[ id ].src.classList.remove(
						'smush-detected-img'
					);
					this.images.bigger[ id ].src.classList.remove(
						'smush-image-' + ++id
					);
				}
			}

			for ( let id in this.images.smaller ) {
				if ( this.images.smaller.hasOwnProperty( id ) ) {
					this.images.smaller[ id ].src.classList.remove(
						'smush-detected-img'
					);
					this.images.smaller[ id ].src.classList.remove(
						'smush-image-' + ++id
					);
				}
			}

			this.images = {
				bigger: [],
				smaller: [],
			};

			// This might be overkill - there will probably never be a situation when there are less images than on
			// initial page load.
			const elements = document.getElementsByClassName(
				'smush-resize-box'
			);
			while ( elements.length > 0 ) {
				elements[ 0 ].remove();
			}

			this.process();
		},
	}; // End WP_Smush_IRS

	/**
	 * After page load, initialize toggle event.
	 */
	window.addEventListener( 'DOMContentLoaded', () => SmushIRS.init() );
	window.addEventListener( 'lazyloaded', () => SmushIRS.refresh() );
} )();
