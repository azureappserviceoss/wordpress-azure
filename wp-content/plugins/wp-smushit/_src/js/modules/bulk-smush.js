/* global WP_Smush */
/* global ajaxurl */

/**
 * Bulk Smush functionality.
 *
 * @since 2.9.0  Moved from admin.js
 */

import Smush from '../smush/smush';

( function( $ ) {
	'use strict';

	WP_Smush.bulk = {
		init: () => {
			/**
			 * Handle the Bulk Smush/Bulk re-Smush button click.
			 */
			$( '.wp-smush-all' ).on( 'click', function( e ) {
				e.preventDefault();

				$( '.sui-notice-top.sui-notice-success' ).remove();

				const bulkWarning = document.getElementById(
					'bulk_smush_warning'
				);
				bulkWarning.classList.add( 'sui-hidden' );

				// Remove limit exceeded styles.
				const progress = $( '.wp-smush-bulk-progress-bar-wrapper' );
				progress.removeClass( 'wp-smush-exceed-limit' );
				progress
					.find( '.sui-progress-block .wp-smush-all' )
					.addClass( 'sui-hidden' );
				progress
					.find( '.sui-progress-block .wp-smush-cancel-bulk' )
					.removeClass( 'sui-hidden' );
				if ( bulkWarning ) {
					document
						.getElementById( 'bulk-smush-resume-button' )
						.classList.add( 'sui-hidden' );
				}

				// Disable re-Smush and scan button.
				// TODO: refine what is disabled.
				$(
					'.wp-resmush.wp-smush-action, .wp-smush-scan, .wp-smush-all:not(.sui-progress-close), a.wp-smush-lossy-enable, button.wp-smush-resize-enable, button#save-settings-button'
				).prop( 'disabled', true );

				// Check for IDs, if there is none (unsmushed or lossless), don't call Smush function.
				/** @param {Array} wp_smushit_data.unsmushed */
				if (
					'undefined' === typeof window.wp_smushit_data ||
					( 0 === window.wp_smushit_data.unsmushed.length &&
						0 === window.wp_smushit_data.resmush.length )
				) {
					return false;
				}

				$( '.wp-smush-remaining' ).addClass( 'sui-hidden' );

				// Show loader.
				progress
					.find( '.sui-progress-block i.sui-icon-info' )
					.removeClass( 'sui-icon-info' )
					.addClass( 'sui-loading' )
					.addClass( 'sui-icon-loader' );

				new Smush( $( this ), true );
			} );

			/**
			 * Ignore file from bulk Smush.
			 *
			 * @since 2.9.0
			 */
			$( 'body' ).on( 'click', '.smush-ignore-image', function( e ) {
				e.preventDefault();

				const self = $( this );

				self.prop( 'disabled', true );
				self.attr( 'data-tooltip' );
				self.removeClass( 'sui-tooltip' );
				$.post( ajaxurl, {
					action: 'ignore_bulk_image',
					id: self.attr( 'data-id' ),
				} ).done( ( response ) => {
					if (
						self.is( 'a' ) &&
						response.success &&
						'undefined' !== typeof response.data.links
					) {
						self.parent()
							.parent()
							.find( '.smush-status' )
							.text( wp_smush_msgs.ignored );
						e.target.closest( '.smush-status-links' ).innerHTML =
							response.data.links;
					}
				} );
			} );

			/**
			 * Show upsell on free version and when there are no images to compress.
			 *
			 * @since 3.7.2
			 */
			const upsellBox = document.getElementById( 'smush-box-bulk-upgrade' );
			if (
				upsellBox &&
				!window.wp_smushit_data.unsmushed.length &&
				!window.wp_smushit_data.resmush.length
			) {
				upsellBox.classList.remove( 'sui-hidden' );
			}
		},
	};

	WP_Smush.bulk.init();
} )( jQuery );
