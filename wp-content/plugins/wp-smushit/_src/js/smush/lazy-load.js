/* global WP_Smush */
/* global ajaxurl */

/**
 * Lazy loading functionality.
 *
 * @since 3.0
 */
( function() {
	'use strict';

	WP_Smush.Lazyload = {
		lazyloadEnableButton: document.getElementById(
			'smush-enable-lazyload'
		),
		lazyloadDisableButton: document.getElementById(
			'smush-cancel-lazyload'
		),

		init() {
			const self = this;

			/**
			 * Handle "Activate" button click on disabled Lazy load page.
			 */
			if ( this.lazyloadEnableButton ) {
				this.lazyloadEnableButton.addEventListener( 'click', ( e ) => {
					e.preventDefault();
					e.currentTarget.classList.add( 'sui-button-onload' );

					this.toggle_lazy_load( true );
				} );
			}

			/**
			 * Handle "Deactivate' button click on Lazy load page.
			 */
			if ( this.lazyloadDisableButton ) {
				this.lazyloadDisableButton.addEventListener( 'click', ( e ) => {
					e.preventDefault();
					e.currentTarget.classList.add( 'sui-button-onload' );

					this.toggle_lazy_load( false );
				} );
			}

			/**
			 * Handle "Remove icon" button click on Lazy load page.
			 *
			 * This removes the image from the upload placeholder.
			 *
			 * @since 3.2.2
			 */
			const removeSpinner = document.getElementById(
				'smush-remove-spinner'
			);
			if ( removeSpinner ) {
				removeSpinner.addEventListener( 'click', ( e ) => {
					e.preventDefault();
					this.removeLoaderIcon();
				} );
			}
			const removePlaceholder = document.getElementById(
				'smush-remove-placeholder'
			);
			if ( removePlaceholder ) {
				removePlaceholder.addEventListener( 'click', ( e ) => {
					e.preventDefault();
					this.removeLoaderIcon( 'placeholder' );
				} );
			}

			/**
			 * Handle "Remove" icon click.
			 *
			 * This removes the select icon from the list (not same as above functions).
			 *
			 * @since 3.2.2
			 */
			const items = document.querySelectorAll( '.smush-ll-remove' );
			if ( items && 0 < items.length ) {
				items.forEach( function( el ) {
					el.addEventListener( 'click', ( e ) => {
						e.preventDefault();
						e.target.closest( 'li' ).style.display = 'none';
						self.remove(
							e.target.dataset.id,
							e.target.dataset.type
						);
					} );
				} );
			}

			this.handlePredefinedPlaceholders();
		},

		/**
		 * Handle background color changes for the two predefined placeholders.
		 *
		 * @since 3.7.1
		 */
		handlePredefinedPlaceholders() {
			const pl1 = document.getElementById( 'placeholder-icon-1' );
			if ( pl1 ) {
				pl1.addEventListener( 'click', () => this.changeColor( '#F3F3F3' ) );
			}

			const pl2 = document.getElementById( 'placeholder-icon-2' );
			if ( pl2 ) {
				pl2.addEventListener( 'click', () => this.changeColor( '#333333' ) );
			}
		},

		/**
		 * Set color.
		 *
		 * @since 3.7.1
		 * @param {string} color
		 */
		changeColor( color ) {
			document.getElementById( 'smush-color-picker' ).value = color;
			document.querySelector( '.sui-colorpicker-hex .sui-colorpicker-value > span > span' ).style.backgroundColor = color;
			document.querySelector( '.sui-colorpicker-hex .sui-colorpicker-value > input' ).value = color;
		},

		/**
		 * Toggle lazy loading.
		 *
		 * @since 3.2.0
		 *
		 * @param {string} enable
		 */
		toggle_lazy_load( enable ) {
			const nonceField = document.getElementsByName(
				'wp_smush_options_nonce'
			);

			const xhr = new XMLHttpRequest();
			xhr.open(
				'POST',
				ajaxurl + '?action=smush_toggle_lazy_load',
				true
			);
			xhr.setRequestHeader(
				'Content-type',
				'application/x-www-form-urlencoded'
			);
			xhr.onload = () => {
				if ( 200 === xhr.status ) {
					const res = JSON.parse( xhr.response );
					if ( 'undefined' !== typeof res.success && res.success ) {
						window.location.search = 'page=smush-lazy-load';
					} else if ( 'undefined' !== typeof res.data.message ) {
						WP_Smush.helpers.showErrorNotice( res.data.message );
						document.querySelector( '.sui-button-onload' ).classList.remove( 'sui-button-onload' );
					}
				} else {
					WP_Smush.helpers.showErrorNotice( 'Request failed.  Returned status of ' + xhr.status );
					document.querySelector( '.sui-button-onload' ).classList.remove( 'sui-button-onload' );
				}
			};
			xhr.send(
				'param=' + enable + '&_ajax_nonce=' + nonceField[ 0 ].value
			);
		},

		/**
		 * Add lazy load spinner icon.
		 *
		 * @since 3.2.2
		 * @param {string} type  Accepts: spinner, placeholder.
		 */
		addLoaderIcon( type = 'spinner' ) {
			let frame;

			// If the media frame already exists, reopen it.
			if ( frame ) {
				frame.open();
				return;
			}

			// Create a new media frame
			frame = wp.media( {
				title: 'Select or upload an icon',
				button: {
					text: 'Select icon',
				},
				multiple: false, // Set to true to allow multiple files to be selected
			} );

			// When an image is selected in the media frame...
			frame.on( 'select', function() {
				// Get media attachment details from the frame state
				const attachment = frame
					.state()
					.get( 'selection' )
					.first()
					.toJSON();

				// Send the attachment URL to our custom image input field.
				const imageIcon = document.getElementById(
					'smush-' + type + '-icon-preview'
				);
				imageIcon.style.backgroundImage =
					'url("' + attachment.url + '")';
				imageIcon.style.display = 'block';

				// Send the attachment id to our hidden input
				document
					.getElementById( 'smush-' + type + '-icon-file' )
					.setAttribute( 'value', attachment.id );

				// Hide the add image link
				document.getElementById(
					'smush-upload-' + type
				).style.display = 'none';

				// Unhide the remove image link
				const removeDiv = document.getElementById(
					'smush-remove-' + type
				);
				removeDiv.querySelector( 'span' ).innerHTML =
					attachment.filename;
				removeDiv.style.display = 'block';
			} );

			// Finally, open the modal on click
			frame.open();
		},

		/**
		 * Remove lazy load spinner icon.
		 *
		 * @since 3.2.2
		 * @param {string} type  Accepts: spinner, placeholder.
		 */
		removeLoaderIcon: ( type = 'spinner' ) => {
			// Clear out the preview image
			const imageIcon = document.getElementById(
				'smush-' + type + '-icon-preview'
			);
			imageIcon.style.backgroundImage = '';
			imageIcon.style.display = 'none';

			// Un-hide the add image link
			document.getElementById( 'smush-upload-' + type ).style.display =
				'block';

			// Hide the delete image link
			document.getElementById( 'smush-remove-' + type ).style.display =
				'none';

			// Delete the image id from the hidden input
			document
				.getElementById( 'smush-' + type + '-icon-file' )
				.setAttribute( 'value', '' );
		},

		/**
		 * Remove item.
		 *
		 * @param {number} id    Image ID.
		 * @param {string} type  Accepts: spinner, placeholder.
		 */
		remove: ( id, type = 'spinner' ) => {
			const nonceField = document.getElementsByName(
				'wp_smush_options_nonce'
			);
			const xhr = new XMLHttpRequest();
			xhr.open( 'POST', ajaxurl + '?action=smush_remove_icon', true );
			xhr.setRequestHeader(
				'Content-type',
				'application/x-www-form-urlencoded'
			);
			xhr.send(
				'id=' +
					id +
					'&type=' +
					type +
					'&_ajax_nonce=' +
					nonceField[ 0 ].value
			);
		},
	};

	WP_Smush.Lazyload.init();
} )();
