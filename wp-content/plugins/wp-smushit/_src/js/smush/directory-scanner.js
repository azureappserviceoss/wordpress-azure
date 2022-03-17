/* global WP_Smush */
/* global ajaxurl */

/**
 * Directory scanner module that will Smush images in the Directory Smush modal.
 *
 * @since 2.8.1
 *
 * @param {string|number} totalSteps
 * @param {string|number} currentStep
 * @return {Object}  Scan object.
 * @class
 */
const DirectoryScanner = ( totalSteps, currentStep ) => {
	totalSteps = parseInt( totalSteps );
	currentStep = parseInt( currentStep );

	let cancelling = false,
		failedItems = 0,
		skippedItems = 0;

	const obj = {
		scan() {
			const remainingSteps = totalSteps - currentStep;
			if ( currentStep !== 0 ) {
				// Scan started on a previous page load.
				step(remainingSteps).fail(this.showScanError);
			} else {
				jQuery
					.post(ajaxurl, { action: 'directory_smush_start' }, () =>
						step(remainingSteps).fail(this.showScanError)
					)
					.fail(this.showScanError);
			}
		},

		cancel() {
			cancelling = true;
			return jQuery.post( ajaxurl, { action: 'directory_smush_cancel' } );
		},

		getProgress() {
			if ( cancelling ) {
				return 0;
			}
			// O M G ... Logic at it's finest!
			const remainingSteps = totalSteps - currentStep;
			return Math.min(
				Math.round(
					( parseInt( totalSteps - remainingSteps ) * 100 ) /
						totalSteps
				),
				99
			);
		},

		onFinishStep( progress ) {
			jQuery( '.wp-smush-progress-dialog .sui-progress-state-text' ).html(
				currentStep -
					failedItems +
					'/' +
					totalSteps +
					' ' +
					window.wp_smush_msgs.progress_smushed
			);
			WP_Smush.directory.updateProgressBar( progress );
		},

		onFinish() {
			WP_Smush.directory.updateProgressBar( 100 );
			window.location.href =
				window.wp_smush_msgs.directory_url + '&scan=done';
		},

		/**
		 * Displays an error when the scan request fails.
		 *
		 * @param {Object} res XHR object.
		 */
		showScanError(res) {
			const dialog = jQuery('#wp-smush-progress-dialog');

			// Add the error class to show/hide elements in the dialog.
			dialog
				.removeClass('wp-smush-exceed-limit')
				.addClass('wp-smush-scan-error');

			// Add the error status and description to the error message.
			dialog
				.find('#smush-scan-error')
				.text(`${res.status} ${res.statusText}`);

			// Show/hide the 403 error specific instructions.
			const forbiddenMessage = dialog.find('.smush-403-error-message');
			if (403 !== res.status) {
				forbiddenMessage.addClass('sui-hidden');
			} else {
				forbiddenMessage.removeClass('sui-hidden');
			}
		},

		limitReached() {
			const dialog = jQuery( '#wp-smush-progress-dialog' );

			dialog.addClass( 'wp-smush-exceed-limit' );
			dialog
				.find( '#cancel-directory-smush' )
				.attr( 'data-tooltip', window.wp_smush_msgs.bulk_resume );
			dialog
				.find( '.sui-box-body .sui-icon-close' )
				.removeClass( 'sui-icon-close' )
				.addClass( 'sui-icon-play' );
			dialog
				.find( '#cancel-directory-smush' )
				.attr( 'id', 'cancel-directory-smush-disabled' );
		},

		resume() {
			const dialog = jQuery( '#wp-smush-progress-dialog' );
			const resume = dialog.find( '#cancel-directory-smush-disabled' );

			dialog.removeClass( 'wp-smush-exceed-limit' );
			dialog
				.find( '.sui-box-body .sui-icon-play' )
				.removeClass( 'sui-icon-play' )
				.addClass( 'sui-icon-close' );
			resume.attr( 'data-tooltip', 'Cancel' );
			resume.attr( 'id', 'cancel-directory-smush' );

			obj.scan();
		},
	};

	/**
	 * Execute a scan step recursively
	 *
	 * Private to avoid overriding
	 *
	 * @param {number} remainingSteps
	 */
	const step = function( remainingSteps ) {
		if ( remainingSteps >= 0 ) {
			currentStep = totalSteps - remainingSteps;
			return jQuery.post(
				ajaxurl,
				{
					action: 'directory_smush_check_step',
					step: currentStep,
				},
				( response ) => {
					// We're good - continue on.
					if (
						'undefined' !== typeof response.success &&
						response.success
					) {
						if (
							'undefined' !== typeof response.data &&
							'undefined' !== typeof response.data.skipped &&
							true === response.data.skipped
						) {
							skippedItems++;
						}

						currentStep++;
						remainingSteps = remainingSteps - 1;
						obj.onFinishStep( obj.getProgress() );
						step(remainingSteps).fail(obj.showScanError);
					} else if (
						'undefined' !== typeof response.data.error &&
						'dir_smush_limit_exceeded' === response.data.error
					) {
						// Limit reached. Stop.
						obj.limitReached();
					} else {
						// Error? never mind, continue, but count them.
						failedItems++;
						currentStep++;
						remainingSteps = remainingSteps - 1;
						obj.onFinishStep( obj.getProgress() );
						step(remainingSteps).fail(obj.showScanError);
					}
				}
			);
		}
		return jQuery.post(
			ajaxurl,
			{
				action: 'directory_smush_finish',
				items: totalSteps - ( failedItems + skippedItems ),
				failed: failedItems,
				skipped: skippedItems,
			},
			( response ) => obj.onFinish( response )
		);
	};

	return obj;
};

export default DirectoryScanner;
