/* global WP_Smush */
/* global ajaxurl */
/* global wp_smushit_data */

let perf = 0;

/**
 * Smush class.
 *
 * @since 2.9.0  Moved from admin.js into a dedicated ES6 class.
 */
class Smush {
	/**
	 * Class constructor.
	 *
	 * @param {Object}  button  Button object that made the call.
	 * @param {boolean} bulk    Bulk smush or not.
	 * @param {string}  type    Accepts: 'nextgen', 'media'.
	 */
	constructor( button, bulk, type = 'media' ) {
		// TODO: errors will reset after bulk smush limit is reached and user clicks continue.
		this.errors = [];
		// Smushed and total we take from the progress bar... I don't like this :-(
		const progressBar = jQuery(
			'.bulk-smush-wrapper .sui-progress-state-text'
		);
		this.smushed = parseInt(
			progressBar.find( 'span:first-child' ).html()
		);
		this.total = parseInt( progressBar.find( 'span:last-child' ).html() );

		//If smush attribute is not defined, Need not skip re-Smush IDs.
		this.skip_resmush = ! (
			'undefined' === typeof button.data( 'smush' ) ||
			! button.data( 'smush' )
		);
		this.button = jQuery( button[ 0 ] );
		this.is_bulk = typeof bulk ? bulk : false;
		this.url = ajaxurl;
		this.log = jQuery( '.smush-final-log' );
		this.deferred = jQuery.Deferred();
		this.deferred.errors = [];

		this.setIds();

		this.is_bulk_resmush =
			0 < wp_smushit_data.resmush.length && ! this.skip_resmush;
		this.status = this.button.parent().prev( '.smush-status' );

		// Added for NextGen support.
		this.smush_type = type;
		this.single_ajax_suffix =
			'nextgen' === this.smush_type
				? 'smush_manual_nextgen'
				: 'wp_smushit_manual';
		this.bulk_ajax_suffix =
			'nextgen' === this.smush_type
				? 'wp_smushit_nextgen_bulk'
				: 'wp_smushit_bulk';
		this.url = this.is_bulk
			? Smush.smushAddParams( this.url, {
					action: this.bulk_ajax_suffix,
			  } )
			: Smush.smushAddParams( this.url, {
					action: this.single_ajax_suffix,
			  } );

		this.start();
		this.run();
		this.bindDeferredEvents();

		// Handle cancel ajax.
		this.cancelAjax();

		return this.deferred;
	}

	/**
	 * Add params to the URL.
	 *
	 * @param {string} url   URL to add the params to.
	 * @param {Object} data  Object with params.
	 * @return {string}  URL with params.
	 */
	static smushAddParams( url, data ) {
		if ( ! jQuery.isEmptyObject( data ) ) {
			url +=
				( url.indexOf( '?' ) >= 0 ? '&' : '?' ) + jQuery.param( data );
		}

		return url;
	}

	/**
	 * Check membership validity.
	 *
	 * @param {Object} data
	 * @param {number} data.show_warning
	 */
	static membershipValidity( data ) {
		const memberValidityNotice = jQuery( '#wp-smush-invalid-member' );

		// Check for membership warning.
		if (
			'undefined' !== typeof data &&
			'undefined' !== typeof data.show_warning &&
			memberValidityNotice.length > 0
		) {
			if ( data.show_warning ) {
				memberValidityNotice.show();
			} else {
				memberValidityNotice.hide();
			}
		}
	}

	/**
	 * Send Ajax request for compressing the image.
	 *
	 * @param {boolean} isBulkResmush
	 * @param {number}  id
	 * @param {string}  sendUrl
	 * @param {string}  nonce
	 * @return {*|jQuery.promise|void}  Compression results.
	 */
	static ajax( isBulkResmush, id, sendUrl, nonce ) {
		const param = jQuery.param( {
			is_bulk_resmush: isBulkResmush,
			attachment_id: id,
			_nonce: nonce,
		} );

		return jQuery.ajax( {
			type: 'GET',
			data: param,
			url: sendUrl,
			/** @param {Array} wp_smushit_data */
			timeout: wp_smushit_data.timeout,
			dataType: 'json',
		} );
	}

	/**
	 * Sets this.ids.
	 */
	setIds() {
		let ids = [];
		if ( 0 < wp_smushit_data.resmush.length && ! this.skip_resmush ) {
			if ( 0 < wp_smushit_data.unsmushed.length ) {
				ids = wp_smushit_data.resmush.concat( wp_smushit_data.unsmushed );
			} else {
				ids = wp_smushit_data.resmush;
			}
		} else {
			ids = wp_smushit_data.unsmushed;
		}

		if ( 'object' === typeof ids ) {
			// If button has re-Smush class, and we do have ids that needs to re-Smushed, put them in the list.
			this.ids = ids.filter( function( itm, i, a ) {
				return i === a.indexOf( itm );
			} );
		} else {
			this.ids = ids;
		}
	}

	/**
	 * Show loader in button for single and bulk Smush.
	 */
	start() {
		this.button.prop( 'disabled', true );
		this.button.addClass( 'wp-smush-started' );

		this.bulkStart();
		this.singleStart();
	}

	/**
	 * Start bulk Smush.
	 */
	bulkStart() {
		if ( ! this.is_bulk ) {
			return;
		}

		// Hide the bulk div.
		jQuery( '.wp-smush-bulk-wrapper' ).addClass( 'sui-hidden' );

		// Remove any global notices if there.
		jQuery( '.sui-notice-top' ).remove();

		// Hide the bulk limit message.
		jQuery(
			'.wp-smush-bulk-progress-bar-wrapper .sui-notice-warning:first-of-type'
		).hide();

		// Hide parent wrapper, if there are no other messages.
		if (
			0 >= jQuery( 'div.smush-final-log .smush-bulk-error-row' ).length
		) {
			jQuery( 'div.smush-final-log' ).hide();
		}

		// Show the progress bar.
		jQuery(
			'.bulk-smush-wrapper .wp-smush-bulk-progress-bar-wrapper, #wp-smush-running-notice'
		).removeClass( 'sui-hidden' );
	}

	/**
	 * Start single image Smush.
	 */
	singleStart() {
		if ( this.is_bulk ) {
			return;
		}

		this.button.html(
			'<span class="spinner wp-smush-progress">' +
				window.wp_smush_msgs.smushing +
				'</span>'
		);
		this.status.removeClass( 'error' );
	}

	/**
	 * Enable button.
	 */
	enableButton() {
		this.button.prop( 'disabled', false );
		jQuery('.wp-smush-all').prop('disabled', false);
		// For bulk process, enable other buttons.
		jQuery(
			'button.wp-smush-scan, a.wp-smush-lossy-enable, button.wp-smush-resize-enable, button#save-settings-button'
		).prop('disabled', false);
	}

	/**
	 * Finish single image Smush.
	 */
	singleDone() {
		if ( this.is_bulk ) {
			return;
		}

		const self = this;

		this.button.html( window.wp_smush_msgs.all_done );

		this.request
			.done( function( response ) {
				if ( 'undefined' !== typeof response.data ) {
					// Check if stats div exists.
					const parent = self.status.parent();

					// Check whether to show membership validity notice or not.
					Smush.membershipValidity( response.data );

					if ( ! response.success ) {
						self.status.addClass( 'error' );
						/** @param {string} response.data.error_msg */
						self.status.html( response.data.error_msg );
						self.button.html(
							window.smush_vars.strings.stats_label
						);
					} else {
						// If we've updated status, replace the content.
						parent.html( response.data );
					}

					/**
					 * Update image size in attachment info panel.
					 *
					 * @param {string|number} response.data.new_size
					 */
					Smush.updateImageStats( response.data.new_size );
				}
				self.enableButton();
			} )
			.fail( function( response ) {
				self.status.html( response.data );
				self.status.addClass( 'error' );
				self.enableButton();
			} );
	}

	/**
	 * Set pro savings stats if not premium user.
	 *
	 * For non-premium users, show expected average savings based
	 * on the free version savings.
	 */
	static setProSavings() {
		// Default values.
		let savings =
				wp_smushit_data.savings_percent > 0
					? wp_smushit_data.savings_percent
					: 0,
			savingsBytes =
				wp_smushit_data.savings_bytes > 0
					? wp_smushit_data.savings_bytes
					: 0,
			origDiff = 2.22058824;

		if ( savings > 49 ) {
			origDiff = 1.22054412;
		}

		// Calculate Pro savings.
		if ( savings > 0 ) {
			savings = origDiff * savings;
			savingsBytes = origDiff * savingsBytes;
		}

		wp_smushit_data.pro_savings = {
			percent: WP_Smush.helpers.precise_round( savings, 1 ),
			savings_bytes: WP_Smush.helpers.formatBytes( savingsBytes, 1 ),
		};
	}

	/**
	 * Update all stats sections based on the response.
	 *
	 * @param {string} scanType Current scan type.
	 */
	static updateStats( scanType ) {
		const isNextgen =
			'undefined' !== typeof scanType && 'nextgen' === scanType;
		let superSavings = 0;

		// Calculate updated savings in bytes.
		wp_smushit_data.savings_bytes =
			parseInt( wp_smushit_data.size_before ) -
			parseInt( wp_smushit_data.size_after );

		const formattedSize = WP_Smush.helpers.formatBytes(
			wp_smushit_data.savings_bytes,
			1
		);
		const statsHuman = jQuery( '.wp-smush-savings .wp-smush-stats-human' );

		if ( isNextgen ) {
			statsHuman.html( formattedSize );
		} else {
			statsHuman.html(
				WP_Smush.helpers.getFormatFromString( formattedSize )
			);
			jQuery( '.sui-summary-large.wp-smush-stats-human' ).html(
				WP_Smush.helpers.getSizeFromString( formattedSize )
			);
		}

		// Update the savings percent.
		wp_smushit_data.savings_percent = WP_Smush.helpers.precise_round(
			( parseInt( wp_smushit_data.savings_bytes ) /
				parseInt( wp_smushit_data.size_before ) ) *
				100,
			1
		);
		if ( ! isNaN( wp_smushit_data.savings_percent ) ) {
			jQuery( '.wp-smush-savings .wp-smush-stats-percent' ).html(
				wp_smushit_data.savings_percent
			);
		}

		// Super-Smush savings.
		if (
			'undefined' !== typeof wp_smushit_data.savings_bytes &&
			'undefined' !== typeof wp_smushit_data.savings_resize
		) {
			superSavings =
				parseInt( wp_smushit_data.savings_bytes ) -
				parseInt( wp_smushit_data.savings_resize );
			if ( superSavings > 0 ) {
				jQuery(
					'li.super-smush-attachments span.smushed-savings'
				).html( WP_Smush.helpers.formatBytes( superSavings, 1 ) );
			}
		}

		// Update image count.
		if ( isNextgen ) {
			jQuery( '.sui-summary-details span.wp-smush-total-optimised' ).html(
				wp_smushit_data.count_images
			);
		} else {
			jQuery(
				'span.smushed-items-count span.wp-smush-count-total span.wp-smush-total-optimised'
			).html( wp_smushit_data.count_images );
		}

		// Update resize image count.
		jQuery(
			'span.smushed-items-count span.wp-smush-count-resize-total span.wp-smush-total-optimised'
		).html( wp_smushit_data.count_resize );

		// Update super-Smushed image count.
		const smushedCountDiv = jQuery(
			'li.super-smush-attachments .smushed-count'
		);
		if (
			smushedCountDiv.length &&
			'undefined' !== typeof wp_smushit_data.count_supersmushed
		) {
			smushedCountDiv.html( wp_smushit_data.count_supersmushed );
		}

		// Update conversion savings.
		const smushConversionSavings = jQuery( '.smush-conversion-savings' );
		if (
			smushConversionSavings.length > 0 &&
			'undefined' !== typeof wp_smushit_data.savings_conversion &&
			wp_smushit_data.savings_conversion !== ''
		) {
			const conversionSavings = smushConversionSavings.find(
				'.wp-smush-stats'
			);
			if ( conversionSavings.length > 0 ) {
				conversionSavings.html(
					WP_Smush.helpers.formatBytes(
						wp_smushit_data.savings_conversion,
						1
					)
				);
			}
		}

		// Update resize savings.
		const smushResizeSavings = jQuery( '.smush-resize-savings' );
		if (
			smushResizeSavings.length > 0 &&
			'undefined' !== typeof wp_smushit_data.savings_resize &&
			wp_smushit_data.savings_resize !== ''
		) {
			// Get the resize savings in number.
			const savingsValue = parseInt( wp_smushit_data.savings_resize );
			const resizeSavings = smushResizeSavings.find( '.wp-smush-stats' );
			const resizeMessage = smushResizeSavings.find(
				'.wp-smush-stats-label-message'
			);
			// Replace only if value is grater than 0.
			if ( savingsValue > 0 && resizeSavings.length > 0 ) {
				// Hide message.
				if ( resizeMessage.length > 0 ) {
					resizeMessage.hide();
				}
				resizeSavings.html(
					WP_Smush.helpers.formatBytes(
						wp_smushit_data.savings_resize,
						1
					)
				);
			}
		}

		//Update pro Savings
		Smush.setProSavings();

		// Updating pro savings stats.
		if ( 'undefined' !== typeof wp_smushit_data.pro_savings ) {
			// Pro stats section.
			const smushProSavings = jQuery( '.smush-avg-pro-savings' );
			if ( smushProSavings.length > 0 ) {
				const proSavingsPercent = smushProSavings.find(
					'.wp-smush-stats-percent'
				);
				const proSavingsBytes = smushProSavings.find(
					'.wp-smush-stats-human'
				);
				if (
					proSavingsPercent.length > 0 &&
					'undefined' !==
						typeof wp_smushit_data.pro_savings.percent &&
					wp_smushit_data.pro_savings.percent !== ''
				) {
					proSavingsPercent.html(
						wp_smushit_data.pro_savings.percent
					);
				}
				if (
					proSavingsBytes.length > 0 &&
					'undefined' !==
						typeof wp_smushit_data.pro_savings.savings_bytes &&
					wp_smushit_data.pro_savings.savings_bytes !== ''
				) {
					proSavingsBytes.html(
						wp_smushit_data.pro_savings.savings_bytes
					);
				}
			}
		}

		// Update remaining count.
		// Update sidebar count.
		const sidenavCountDiv = jQuery(
			'.smush-sidenav .wp-smush-remaining-count'
		);
		if ( sidenavCountDiv.length ) {
			let count = 0;

			// Unsmushed
			if (
				'undefined' !== typeof wp_smushit_data.unsmushed &&
				wp_smushit_data.unsmushed.length > 0
			) {
				count += wp_smushit_data.unsmushed.length;
			}

			// Re-smush
			if (
				'undefined' !== typeof wp_smushit_data.resmush &&
				wp_smushit_data.resmush.length > 0
			) {
				count += wp_smushit_data.resmush.length;
			}

			Smush.updateRemainingCount( count );
		}
	}

	/**
	 * Update image size in attachment info panel.
	 *
	 * @since 2.8
	 *
	 * @param {number} newSize
	 */
	static updateImageStats( newSize ) {
		if ( 0 === newSize ) {
			return;
		}

		const attachmentSize = jQuery( '.attachment-info .file-size' );
		const currentSize = attachmentSize
			.contents()
			.filter( function() {
				return this.nodeType === 3;
			} )
			.text();

		// There is a space before the size.
		if ( currentSize !== ' ' + newSize ) {
			const sizeStrongEl = attachmentSize
				.contents()
				.filter( function() {
					return this.nodeType === 1;
				} )
				.text();
			attachmentSize.html(
				'<strong>' + sizeStrongEl + '</strong> ' + newSize
			);
		}
	}

	/**
	 * Sync stats.
	 */
	syncStats() {
		const messageHolder = jQuery(
			'div.wp-smush-bulk-progress-bar-wrapper div.wp-smush-count.tc'
		);
		// Store the existing content in a variable.
		const progressMessage = messageHolder.html();
		/** @param {string} wp_smush_msgs.sync_stats */
		messageHolder.html( window.wp_smush_msgs.sync_stats );

		// Send ajax.
		jQuery
			.ajax( {
				type: 'GET',
				url: this.url,
				data: {
					action: 'get_stats',
				},
				success( response ) {
					if ( response && 'undefined' !== typeof response ) {
						response = response.data;
						jQuery.extend( wp_smushit_data, {
							count_images: response.count_images,
							count_smushed: response.count_smushed,
							count_total: response.count_total,
							count_resize: response.count_resize,
							count_supersmushed: response.count_supersmushed,
							savings_bytes: response.savings_bytes,
							savings_conversion: response.savings_conversion,
							savings_resize: response.savings_resize,
							size_before: response.size_before,
							size_after: response.size_after,
						} );
						// Got the stats, update it.
						Smush.updateStats( this.smush_type );
					}
				},
			} )
			.always( () => messageHolder.html( progressMessage ) );
	}

	/**
	 * After the bulk optimization has been finished.
	 */
	bulkDone() {
		if ( ! this.is_bulk ) {
			return;
		}

		// Enable the button.
		this.enableButton();

		const statusIcon = jQuery( '.sui-summary-smush .smush-stats-icon' );

		// Show notice.
		if ( 0 === this.ids.length ) {
			statusIcon.addClass( 'sui-hidden' );
			jQuery(
				'.bulk-smush-wrapper .wp-smush-all-done, .wp-smush-pagespeed-recommendation'
			).removeClass( 'sui-hidden' );
			jQuery( '.wp-smush-bulk-wrapper' ).addClass( 'sui-hidden' );
			// Hide the progress bar if scan is finished.
			jQuery( '.wp-smush-bulk-progress-bar-wrapper' ).addClass( 'sui-hidden' );

			// Display the upsell metabox.
			if (document.getElementById('smush-box-bulk-upgrade')) {
				document
					.getElementById('smush-box-bulk-upgrade')
					.classList.remove('sui-hidden');

				document
					.getElementById('wp-smush-all-smushed-text')
					.classList.remove('sui-hidden');

				document
					.getElementById('wp-smush-pending-to-smush-text')
					.classList.add('sui-hidden');
			}

			// Reset the progress when we finish so the next smushing starts from zero.
			this._updateProgress(0, 0);
		} else {
			// Show loader.
			statusIcon
				.removeClass( 'sui-icon-loader sui-loading sui-hidden' )
				.addClass( 'sui-icon-info sui-warning' );

			const notice = jQuery(
				'.bulk-smush-wrapper .wp-smush-resmush-notice'
			);

			if ( notice.length > 0 ) {
				notice.show();
			} else {
				jQuery( '.bulk-smush-wrapper .wp-smush-remaining' ).removeClass( 'sui-hidden' );
			}
		}

		// Enable re-Smush and scan button.
		jQuery( '.wp-resmush.wp-smush-action, .wp-smush-scan' ).removeProp(
			'disabled'
		);
	}

	/**
	 * Free Smush limit exceeded.
	 */
	freeExceeded() {
		const progress = jQuery( '.wp-smush-bulk-progress-bar-wrapper' );
		progress.addClass( 'wp-smush-exceed-limit' );
		progress
			.find( '.sui-progress-block .wp-smush-cancel-bulk' )
			.addClass( 'sui-hidden' );
		progress
			.find( '.sui-progress-block .wp-smush-all' )
			.removeClass( 'sui-hidden' );

		progress
			.find( 'i.sui-icon-loader' )
			.addClass( 'sui-icon-info' )
			.removeClass( 'sui-icon-loader' )
			.removeClass( 'sui-loading' );

		document
			.getElementById( 'bulk-smush-resume-button' )
			.classList.remove( 'sui-hidden' );

		if (document.getElementById('smush-box-bulk-upgrade')) {
			document
				.getElementById('smush-box-bulk-upgrade')
				.classList.remove('sui-hidden');
		}
	}

	/**
	 * Update remaining count.
	 */
	static updateRemainingCount( count ) {
		const remainingCountContainers = jQuery( '.wp-smush-remaining-count' );
		if ( remainingCountContainers.length ) {
			remainingCountContainers.html( count );
		}

		// Update sidebar count.
		const sidenavCountDiv = jQuery( '.smush-sidenav .wp-smush-remaining-count' ),
			sidenavCheckTag = jQuery( '.smush-sidenav .smush-bulk .sui-icon-check-tick' );
		if ( sidenavCountDiv.length && sidenavCheckTag.length ) {
			if ( count > 0 ) {
				sidenavCountDiv.removeClass( 'sui-hidden' );
				sidenavCheckTag.addClass( 'sui-hidden' );
			} else {
				jQuery( '.sui-summary-smush .smush-stats-icon' ).addClass( 'sui-hidden' );
				sidenavCheckTag.removeClass( 'sui-hidden' );
				sidenavCountDiv.addClass( 'sui-hidden' );
			}
		}
	}

	/**
	 * Adds the stats for the current image to existing stats.
	 *
	 * @param {Array}   imageStats
	 * @param {string}  imageStats.count
	 * @param {boolean} imageStats.is_lossy
	 * @param {Array}   imageStats.savings_resize
	 * @param {Array}   imageStats.savings_conversion
	 * @param {string}  imageStats.size_before
	 * @param {string}  imageStats.size_after
	 * @param {string}  type
	 */
	static updateLocalizedStats( imageStats, type ) {
		// Increase the Smush count.
		if ( 'undefined' === typeof window.wp_smushit_data ) {
			return;
		}

		// No need to increase attachment count, resize, conversion savings for directory Smush.
		if ( 'media' === type ) {
			// Increase Smushed image count.
			wp_smushit_data.count_images =
				parseInt( wp_smushit_data.count_images ) +
				parseInt( imageStats.count );

			// Increase super Smush count, if applicable.
			if ( imageStats.is_lossy ) {
				wp_smushit_data.count_supersmushed =
					parseInt( wp_smushit_data.count_supersmushed ) + 1;
			}

			// Add to resize savings.
			wp_smushit_data.savings_resize =
				'undefined' !== typeof imageStats.savings_resize.bytes
					? parseInt( wp_smushit_data.savings_resize ) +
					  parseInt( imageStats.savings_resize.bytes )
					: parseInt( wp_smushit_data.savings_resize );

			// Update resize count.
			wp_smushit_data.count_resize =
				'undefined' !== typeof imageStats.savings_resize.bytes
					? parseInt( wp_smushit_data.count_resize ) + 1
					: wp_smushit_data.count_resize;

			// Add to conversion savings.
			wp_smushit_data.savings_conversion =
				'undefined' !== typeof imageStats.savings_conversion &&
				'undefined' !== typeof imageStats.savings_conversion.bytes
					? parseInt( wp_smushit_data.savings_conversion ) +
					  parseInt( imageStats.savings_conversion.bytes )
					: parseInt( wp_smushit_data.savings_conversion );
		} else if ( 'directory_smush' === type ) {
			//Increase smushed image count
			wp_smushit_data.count_images =
				parseInt( wp_smushit_data.count_images ) + 1;
		} else if ( 'nextgen' === type ) {
			wp_smushit_data.count_supersmushed =
				parseInt( wp_smushit_data.count_supersmushed ) + 1;

			// Increase Smushed image count.
			wp_smushit_data.count_images =
				parseInt( wp_smushit_data.count_images ) +
				parseInt( imageStats.count );
		}

		// If we have savings. Update savings.
		if ( imageStats.size_before > imageStats.size_after ) {
			wp_smushit_data.size_before =
				'undefined' !== typeof imageStats.size_before
					? parseInt( wp_smushit_data.size_before ) +
					  parseInt( imageStats.size_before )
					: parseInt( wp_smushit_data.size_before );
			wp_smushit_data.size_after =
				'undefined' !== typeof imageStats.size_after
					? parseInt( wp_smushit_data.size_after ) +
					  parseInt( imageStats.size_after )
					: parseInt( wp_smushit_data.size_after );
		}

		// Add stats for resizing. Update savings.
		if ( 'undefined' !== typeof imageStats.savings_resize ) {
			wp_smushit_data.size_before =
				'undefined' !== typeof imageStats.savings_resize.size_before
					? parseInt( wp_smushit_data.size_before ) +
					  parseInt( imageStats.savings_resize.size_before )
					: parseInt( wp_smushit_data.size_before );
			wp_smushit_data.size_after =
				'undefined' !== typeof imageStats.savings_resize.size_after
					? parseInt( wp_smushit_data.size_after ) +
					  parseInt( imageStats.savings_resize.size_after )
					: parseInt( wp_smushit_data.size_after );
		}

		// Add stats for conversion. Update savings.
		if ( 'undefined' !== typeof imageStats.savings_conversion ) {
			wp_smushit_data.size_before =
				'undefined' !== typeof imageStats.savings_conversion.size_before
					? parseInt( wp_smushit_data.size_before ) +
					  parseInt( imageStats.savings_conversion.size_before )
					: parseInt( wp_smushit_data.size_before );
			wp_smushit_data.size_after =
				'undefined' !== typeof imageStats.savings_conversion.size_after
					? parseInt( wp_smushit_data.size_after ) +
					  parseInt( imageStats.savings_conversion.size_after )
					: parseInt( wp_smushit_data.size_after );
		}
	}

	/**
	 * Update progress.
	 *
	 * @param {Object} _res
	 */
	updateProgress( _res ) {
		if ( ! this.is_bulk_resmush && ! this.is_bulk ) {
			return;
		}

		let progress = 0;

		// Update localized stats.
		if (
			_res &&
			'undefined' !== typeof _res.data &&
			'undefined' !== typeof _res.data.stats
		) {
			Smush.updateLocalizedStats( _res.data.stats, this.smush_type );
		}

		if ( ! this.is_bulk_resmush ) {
			// Handle progress for normal bulk smush.
			progress =
				( ( this.smushed + this.errors.length ) / this.total ) * 100;
		} else {
			// If the request was successful, update the progress bar.
			if ( _res.success ) {
				// Handle progress for super Smush progress bar.
				if ( wp_smushit_data.resmush.length > 0 ) {
					// Update the count.
					jQuery( '.wp-smush-images-remaining' ).html(
						wp_smushit_data.resmush.length
					);
				} else if (
					0 === wp_smushit_data.resmush.length &&
					0 === this.ids.length
				) {
					// If all images are re-Smushed, show the All Smushed message.
					jQuery(
						'.bulk-resmush-wrapper .wp-smush-all-done, .wp-smush-pagespeed-recommendation'
					).removeClass( 'sui-hidden' );

					// Hide everything else.
					jQuery(
						'.wp-smush-resmush-wrap, .wp-smush-bulk-progress-bar-wrapper'
					).addClass( 'sui-hidden' );
				}
			}

			// Handle progress for normal bulk Smush. Set progress bar width.
			if (
				'undefined' !== typeof this.ids &&
				'undefined' !== typeof this.total &&
				this.total > 0
			) {
				progress =
					( ( this.smushed + this.errors.length ) / this.total ) *
					100;
			}
		}

		// Reset the lossless images count in case of pending images for resmush ( Nextgen only ).
		if (
			'nextgen' === this.smush_type  &&
			wp_smushit_data.resmush.length > 0 && 
			(this.smushed + this.errors.length <= 1)
		) {
			wp_smushit_data.count_images -= (wp_smushit_data.resmush.length + 1);
		}

		// No more images left. Show bulk wrapper and Smush notice.
		if ( 0 === this.ids.length ) {
			// Sync stats for bulk Smush media library ( skip for Nextgen ).
			if ( 'nextgen' !== this.smush_type ) {
				this.syncStats();
			}
			jQuery(
				'.bulk-smush-wrapper .wp-smush-all-done, .wp-smush-pagespeed-recommendation'
			).removeClass( 'sui-hidden' );
			jQuery( '.wp-smush-bulk-wrapper' ).addClass( 'sui-hidden' );
		}

		// Update remaining count.
		if ( 'undefined' !== typeof this.ids ) {
			Smush.updateRemainingCount( this.ids.length );
		}

		// Increase the progress bar and counter.
		this._updateProgress(
			this.smushed + this.errors.length,
			WP_Smush.helpers.precise_round( progress, 1 )
		);

		// Avoid updating the stats twice when the bulk smush ends on Smush's page.
		if (0 !== this.ids.length || 'nextgen' === this.smush_type) {
			// Update stats and counts.
			Smush.updateStats(this.smush_type);
		}
	}

	/**
	 * Update progress.
	 *
	 * @param {number} count  Number of images optimized.
	 * @param {string} width  Percentage complete.
	 * @private
	 */
	_updateProgress( count, width ) {
		if ( ! this.is_bulk && ! this.is_bulk_resmush ) {
			return;
		}

		// Progress bar label.
		jQuery( 'span.wp-smush-images-percent' ).html( width + '%' );
		// Progress bar.
		jQuery( '.bulk-smush-wrapper .wp-smush-progress-inner' ).css(
			'width',
			width + '%'
		);

		// Progress bar status.
		jQuery( '.bulk-smush-wrapper .sui-progress-state-text' )
			.find( 'span:first-child' )
			.html( count )
			.find( 'span:last-child' )
			.html( this.total );
	}

	/**
	 * Whether to send the ajax requests further or not.
	 *
	 * @return {*|boolean}  Should continue or not.
	 */
	continue() {
		let continueSmush = this.button.attr( 'continue_smush' );

		if ( 'undefined' === typeof continueSmush ) {
			continueSmush = true;
		}

		if ( 'false' === continueSmush || ! continueSmush ) {
			continueSmush = false;
		}

		return continueSmush && this.ids.length > 0 && this.is_bulk;
	}

	/**
	 * Send ajax request for optimizing single and bulk, call update_progress on ajax response.
	 *
	 * @return {*}  Ajax call response.
	 */
	callAjax() {
		/**
		 * This here little piece of code allows to track auto continue clicks and halts bulk Smush until the page
		 * is reloaded.
		 *
		 * @since 3.5.0
		 * @see https://wordpress.org/plugins/wp-nonstop-smushit/
		 */
		if (
			0 !== perf &&
			'undefined' !== typeof perf &&
			10 > performance.now() - perf
		) {
			this.freeExceeded();
			return this.deferred;
		}

		let nonceValue = '';
		// Remove from array while processing so we can continue where left off.
		this.current_id = this.is_bulk
			? this.ids.shift()
			: this.button.data( 'id' );

		// Remove the ID from respective variable as well.
		Smush.updateSmushIds( this.current_id );

		const nonceField = this.button.parent().find( '#_wp_smush_nonce' );
		if ( nonceField ) {
			nonceValue = nonceField.val();
		}

		const self = this;

		this.request = Smush.ajax(
			this.is_bulk_resmush,
			this.current_id,
			this.url,
			nonceValue
		)
			.done( function( res ) {
				// If no response or success is false, do not process further. Increase the error count except if bulk request limit exceeded.
				if (
					'undefined' === typeof res.success ||
					( 'undefined' !== typeof res.success &&
						false === res.success &&
						'undefined' !== typeof res.data &&
						'limit_exceeded' !== res.data.error )
				) {
					self.errors.push( self.current_id );

					/** @param {string} res.data.file_name */
					const errorMsg = Smush.prepareErrorRow(
						res.data.error_message,
						res.data.file_name,
						res.data.thumbnail,
						self.current_id,
						self.smush_type
					);

					self.log.show();

					if ( self.errors.length > 5 ) {
						jQuery( '.smush-bulk-errors-actions' ).removeClass(
							'sui-hidden'
						);
					} else {
						// Print the error on screen.
						self.log
							.find( '.smush-bulk-errors' )
							.append( errorMsg );
					}
				} else if (
					'undefined' !== typeof res.success &&
					res.success
				) {
					// Increment the smushed count if image smushed without errors.
					self.smushed++;
				}

				// Check whether to show the warning notice or not.
				Smush.membershipValidity( res.data );

				/**
				 * Bulk Smush limit exceeded: Stop ajax requests, remove progress bar, append the last image ID
				 * back to Smush variable, and reset variables to allow the user to continue bulk Smush.
				 */
				if (
					'undefined' !== typeof res.data &&
					'limit_exceeded' === res.data.error &&
					'resolved' !== self.deferred.state()
				) {
					// Show error message.
					const bulkWarning = document.getElementById(
						'bulk_smush_warning'
					);
					bulkWarning.classList.remove( 'sui-hidden' );

					// Add a data attribute to the Smush button, to stop sending ajax.
					self.button.attr( 'continue_smush', false );

					// Reinsert the current ID.
					wp_smushit_data.unsmushed.unshift( self.current_id );
					self.ids.unshift( self.current_id );

					perf = performance.now();
					self.freeExceeded();
				} else if ( self.is_bulk ) {
					self.updateProgress( res );
				} else if ( 0 === self.ids.length ) {
					// Sync stats anyway.
					self.syncStats();
				}

				self.singleDone();
			} )
			.always( function() {
				if ( ! self.continue() || ! self.is_bulk ) {
					// Calls deferred.done()
					self.deferred.resolve();
				} else {
					self.callAjax();
				}
			} );

		this.deferred.errors = this.errors;
		return this.deferred;
	}

	/**
	 * Prepare error row. Will only allow to hide errors for WP media attachments (not nextgen).
	 *
	 * @since 1.9.0
	 *
	 * @param {string} errorMsg   Error message.
	 * @param {string} fileName   File name.
	 * @param {string} thumbnail  Thumbnail for image (if available).
	 * @param {number} id         Image ID.
	 * @param {string} type       Smush type: media or netxgen.
	 *
	 * @return {string}  Row with error.
	 */
	static prepareErrorRow( errorMsg, fileName, thumbnail, id, type ) {
		const thumbDiv =
			'undefined' === typeof thumbnail
				? '<i class="sui-icon-photo-picture" aria-hidden="true"></i>'
				: thumbnail;
		const fileLink =
			'undefined' === fileName || 'undefined' === typeof fileName
				? 'undefined'
				: fileName;

		let tableDiv =
			'<div class="smush-bulk-error-row">' +
			'<div class="smush-bulk-image-data">' +
			thumbDiv +
			'<span class="smush-image-name">' +
			fileLink +
			'</span>' +
			'<span class="smush-image-error">' +
			errorMsg +
			'</span>' +
			'</div>';

		if ( 'media' === type ) {
			tableDiv =
				tableDiv +
				'<div class="smush-bulk-image-actions">' +
				'<button type="button" class="sui-button-icon sui-tooltip sui-tooltip-constrained sui-tooltip-top-right smush-ignore-image" data-tooltip="' +
				window.wp_smush_msgs.error_ignore +
				'" data-id="' +
				id +
				'">' +
				'<i class="sui-icon-eye-hide" aria-hidden="true"></i>' +
				'</button>' +
				'</div>';
		}

		tableDiv = tableDiv + '</div>';

		return tableDiv;
	}

	/**
	 * Send ajax request for single and bulk Smushing.
	 */
	run() {
		// If bulk and we have a definite number of IDs.
		if ( this.is_bulk && this.ids.length > 0 ) {
			this.callAjax();
		}

		if ( ! this.is_bulk ) {
			this.callAjax();
		}
	}

	/**
	 * Show bulk Smush errors, and disable bulk Smush button on completion.
	 */
	bindDeferredEvents() {
		const self = this;

		this.deferred.done( function() {
			self.button.removeAttr( 'continue_smush' );

			if ( self.errors.length ) {
				/** @param {string} wp_smush_msgs.error_in_bulk */
				const msg = window.wp_smush_msgs.error_in_bulk
					.replace( '{{errors}}', self.errors.length )
					.replace( '{{total}}', self.total )
					.replace( '{{smushed}}', self.smushed );

				jQuery( '.wp-smush-all-done' )
					.addClass( 'sui-notice-warning' )
					.removeClass( 'sui-notice-success' )
					.find( 'p' )
					.html( msg );
			}

			self.bulkDone();

			// Re-enable the buttons.
			jQuery(
				'.wp-smush-all:not(.wp-smush-finished), .wp-smush-scan'
			).prop('disabled', false);
		} );
	}

	/**
	 * Handles the cancel button click.
	 * Update the UI, and enable the bulk Smush button.
	 */
	cancelAjax() {
		const self = this;

		jQuery( '.wp-smush-cancel-bulk' ).on( 'click', function() {
			// Add a data attribute to the Smush button, to stop sending ajax.
			self.button.attr( 'continue_smush', false );
			// Sync and update stats.
			self.syncStats();

			self.request.abort();
			self.enableButton();
			self.button.removeClass( 'wp-smush-started' );
			wp_smushit_data.unsmushed.unshift( self.current_id );
			jQuery( '.wp-smush-bulk-wrapper' ).removeClass( 'sui-hidden' );

			// Hide the progress bar.
			jQuery( '.wp-smush-bulk-progress-bar-wrapper' ).addClass( 'sui-hidden' );
		} );
	}

	/**
	 * Remove the current ID from the unSmushed/re-Smush variable.
	 *
	 * @param {number} currentId
	 */
	static updateSmushIds( currentId ) {
		if (
			'undefined' !== typeof wp_smushit_data.unsmushed &&
			wp_smushit_data.unsmushed.length > 0
		) {
			const uIndex = wp_smushit_data.unsmushed.indexOf( currentId );
			if ( uIndex > -1 ) {
				wp_smushit_data.unsmushed.splice( uIndex, 1 );
			}
		}

		// Remove from the re-Smush list.
		if (
			'undefined' !== typeof wp_smushit_data.resmush &&
			wp_smushit_data.resmush.length > 0
		) {
			const index = wp_smushit_data.resmush.indexOf( currentId );
			if ( index > -1 ) {
				wp_smushit_data.resmush.splice( index, 1 );
			}
		}
	}
}

export default Smush;
