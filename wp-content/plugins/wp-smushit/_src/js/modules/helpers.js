/* global WP_Smush */
/* global ajaxurl */
/* global wp_smush_msgs */

/**
 * Helpers functions.
 *
 * @since 2.9.0  Moved from admin.js
 */
( function() {
	'use strict';

	WP_Smush.helpers = {
		init: () => {},

		/**
		 * Convert bytes to human readable form.
		 *
		 * @param {number} a  Bytes
		 * @param {number} b  Number of digits
		 * @return {*} Formatted Bytes
		 */
		formatBytes: ( a, b ) => {
			const thresh = 1024,
				units = [ 'KB', 'MB', 'GB', 'TB', 'PB' ];

			if ( Math.abs( a ) < thresh ) {
				return a + ' B';
			}

			let u = -1;

			do {
				a /= thresh;
				++u;
			} while ( Math.abs( a ) >= thresh && u < units.length - 1 );

			return a.toFixed( b ) + ' ' + units[ u ];
		},

		/**
		 * Get size from a string.
		 *
		 * @param {string} formattedSize  Formatter string
		 * @return {*} Formatted Bytes
		 */
		getSizeFromString: ( formattedSize ) => {
			return formattedSize.replace( /[a-zA-Z]/g, '' ).trim();
		},

		/**
		 * Get type from formatted string.
		 *
		 * @param {string} formattedSize  Formatted string
		 * @return {*} Formatted Bytes
		 */
		getFormatFromString: ( formattedSize ) => {
			return formattedSize.replace( /[0-9.]/g, '' ).trim();
		},

		/**
		 * Stackoverflow: http://stackoverflow.com/questions/1726630/formatting-a-number-with-exactly-two-decimals-in-javascript
		 *
		 * @param {number} num
		 * @param {number} decimals
		 * @return {number}  Number
		 */
		precise_round: ( num, decimals ) => {
			const sign = num >= 0 ? 1 : -1;
			// Keep the percentage below 100.
			num = num > 100 ? 100 : num;
			return (
				Math.round( num * Math.pow( 10, decimals ) + sign * 0.001 ) /
				Math.pow( 10, decimals )
			);
		},

		/**
		 * Displays a floating error message using the #wp-smush-ajax-notice container.
		 *
		 * @since 3.8.0
		 *
		 * @param {string} message
		 */
		showErrorNotice: ( message ) => {
			if ( 'undefined' === typeof message ) {
				return;
			}

			const noticeMessage = `<p>${ message }</p>`,
				noticeOptions = {
					type: 'error',
					icon: 'info',
				};

			SUI.openNotice( 'wp-smush-ajax-notice', noticeMessage, noticeOptions );

			const loadingButton = document.querySelector( '.sui-button-onload' );
			if ( loadingButton ) {
				loadingButton.classList.remove( 'sui-button-onload' );
			}
		},

		/**
		 * Reset settings.
		 *
		 * @since 3.2.0
		 */
		resetSettings: () => {
			const _nonce = document.getElementById( 'wp_smush_reset' );
			const xhr = new XMLHttpRequest();
			xhr.open( 'POST', ajaxurl + '?action=reset_settings', true );
			xhr.setRequestHeader(
				'Content-type',
				'application/x-www-form-urlencoded'
			);
			xhr.onload = () => {
				if ( 200 === xhr.status ) {
					const res = JSON.parse( xhr.response );
					if ( 'undefined' !== typeof res.success && res.success ) {
						window.location.href = wp_smush_msgs.smush_url;
					}
				} else {
					window.console.log(
						'Request failed.  Returned status of ' + xhr.status
					);
				}
			};
			xhr.send( '_ajax_nonce=' + _nonce.value );
		},
	};

	WP_Smush.helpers.init();
} )();
