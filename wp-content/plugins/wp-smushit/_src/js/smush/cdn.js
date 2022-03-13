/* global WP_Smush */
/* global ajaxurl */

/**
 * CDN functionality.
 *
 * @since 3.0
 */
( function() {
	'use strict';

	WP_Smush.CDN = {
		cdnEnableButton: document.getElementById( 'smush-enable-cdn' ),
		cdnDisableButton: document.getElementById( 'smush-cancel-cdn' ),
		cdnStatsBox: document.querySelector( '.smush-cdn-stats' ),

		init() {
			/**
			 * Handle "Get Started" button click on disabled CDN page.
			 */
			if ( this.cdnEnableButton ) {
				this.cdnEnableButton.addEventListener( 'click', ( e ) => {
					e.preventDefault();
					e.currentTarget.classList.add( 'sui-button-onload' );
					this.toggle_cdn( true );
				} );
			}

			/**
			 * Handle "Deactivate' button click on CDN page.
			 */
			if ( this.cdnDisableButton ) {
				this.cdnDisableButton.addEventListener( 'click', ( e ) => {
					e.preventDefault();
					e.currentTarget.classList.add( 'sui-button-onload' );

					this.toggle_cdn( false );
				} );
			}

			this.updateStatsBox();
		},

		/**
		 * Toggle CDN.
		 *
		 * @since 3.0
		 *
		 * @param {boolean} enable
		 */
		toggle_cdn( enable ) {
			const nonceField = document.getElementsByName(
				'wp_smush_options_nonce'
			);

			const xhr = new XMLHttpRequest();
			xhr.open( 'POST', ajaxurl + '?action=smush_toggle_cdn', true );
			xhr.setRequestHeader(
				'Content-type',
				'application/x-www-form-urlencoded'
			);
			xhr.onload = () => {
				if ( 200 === xhr.status ) {
					const res = JSON.parse( xhr.response );
					if ( 'undefined' !== typeof res.success && res.success ) {
						window.location.search = 'page=smush-cdn';
					} else if ( 'undefined' !== typeof res.data.message ) {
						WP_Smush.helpers.showErrorNotice( res.data.message );
					}
				} else {
					WP_Smush.helpers.showErrorNotice( 'Request failed.  Returned status of ' + xhr.status );
				}
			};
			xhr.send(
				'param=' + enable + '&_ajax_nonce=' + nonceField[ 0 ].value
			);
		},

		/**
		 * Update the CDN stats box in summary meta box. Only fetch new data when on CDN page.
		 *
		 * @since 3.0
		 */
		updateStatsBox() {
			if (
				'undefined' === typeof this.cdnStatsBox ||
				! this.cdnStatsBox
			) {
				return;
			}

			// Only fetch the new stats, when user is on CDN page.
			if ( ! window.location.search.includes( 'view=cdn' ) ) {
				return;
			}

			this.toggleElements();

			const xhr = new XMLHttpRequest();
			xhr.open( 'POST', ajaxurl + '?action=get_cdn_stats', true );
			xhr.onload = () => {
				if ( 200 === xhr.status ) {
					const res = JSON.parse( xhr.response );
					if ( 'undefined' !== typeof res.success && res.success ) {
						this.toggleElements();
					} else if ( 'undefined' !== typeof res.data.message ) {
						WP_Smush.helpers.showErrorNotice( res.data.message );
					}
				} else {
					WP_Smush.helpers.showErrorNotice( 'Request failed.  Returned status of ' + xhr.status );
				}
			};
			xhr.send();
		},

		/**
		 * Show/hide elements during status update in the updateStatsBox()
		 *
		 * @since 3.1  Moved out from updateStatsBox()
		 */
		toggleElements() {
			const spinner = this.cdnStatsBox.querySelector(
				'.sui-icon-loader'
			);
			const elements = this.cdnStatsBox.querySelectorAll(
				'.wp-smush-stats > :not(.sui-icon-loader)'
			);

			for ( let i = 0; i < elements.length; i++ ) {
				elements[ i ].classList.toggle( 'sui-hidden' );
			}

			spinner.classList.toggle( 'sui-hidden' );
		},
	};

	WP_Smush.CDN.init();
} )();
