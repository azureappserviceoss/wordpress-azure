import Smush from '../smush/smush';

const remove_element = function (el, timeout) {
	if (typeof timeout === 'undefined') {
		timeout = 100;
	}
	el.fadeTo(timeout, 0, function () {
		el.slideUp(timeout, function () {
			el.remove();
		});
	});
};

jQuery(function ($) {
	'use strict';

	/**
	 * Disable the action links *
	 *
	 * @param c_element
	 */
	const disable_links = function (c_element) {
		const parent = c_element.parent();
		//reduce parent opacity
		parent.css({ opacity: '0.5' });
		//Disable Links
		parent.find('a').prop('disabled', true);
	};

	/**
	 * Enable the Action Links *
	 *
	 * @param c_element
	 */
	const enable_links = function (c_element) {
		const parent = c_element.parent();

		//reduce parent opacity
		parent.css({ opacity: '1' });
		//Disable Links
		parent.find('a').prop('disabled', false);
	};

	/**
	 * Restore image request with a specified action for Media Library / NextGen Gallery
	 *
	 * @param {Object} e
	 * @param {string} currentButton
	 * @param {string} smushAction
	 * @param {string} action
	 */
	const process_smush_action = function (
		e,
		currentButton,
		smushAction,
		action
	) {
		// If disabled.
		if (currentButton.prop('disabled')) {
			return;
		}

		e.preventDefault();

		// Remove Error.
		$('.wp-smush-error').remove();

		// Hide stats.
		$('.smush-stats-wrapper').hide();

		let mode = 'grid';
		if ('smush_restore_image' === smushAction) {
			if ($(document).find('div.media-modal.wp-core-ui').length > 0) {
				mode = 'grid';
			} else {
				mode =
					window.location.search.indexOf('item') > -1
						? 'grid'
						: 'list';
			}
		}

		// Get the image ID and nonce.
		const params = {
			action: smushAction,
			attachment_id: currentButton.data('id'),
			mode,
			_nonce: currentButton.data('nonce'),
		};

		// Reduce the opacity of stats and disable the click.
		disable_links(currentButton);

		currentButton.html(
			'<span class="spinner wp-smush-progress">' +
				wp_smush_msgs[action] +
				'</span>'
		);

		// Restore the image.
		$.post(ajaxurl, params, function (r) {
			// Reset all functionality.
			enable_links(currentButton);

			if (r.success && 'undefined' !== typeof r.data) {
				// Replace in immediate parent for NextGEN.
				if (
					'undefined' !== typeof this.data &&
					this.data.indexOf('nextgen') > -1
				) {
					// Show the smush button, and remove stats and restore option.
					currentButton.parents().eq(1).html(r.data.stats);
				} else if ('restore' === action) {
					// Show the smush button, and remove stats and restore option.
					currentButton.parents().eq(1).html(r.data.stats);
				} else {
					currentButton.parents().eq(1).html(r.data);
				}

				if ('undefined' !== typeof r.data && 'restore' === action) {
					Smush.updateImageStats(r.data.new_size);
				}
			} else if (r.data && r.data.error_msg) {
				if (
					-1 === this.data.indexOf('nextgen') &&
					'restore' === action
				) {
					$('.smush-status').addClass('error').html(r.data.error_msg);
				} else {
					// Show error.
					currentButton.parent().append(r.data.error_msg);
				}
			}
		});
	};

	/**
	 * Validates the Resize Width and Height against the Largest Thumbnail Width and Height
	 *
	 * @param wrapper_div jQuery object for the whole setting row wrapper div
	 * @param width_only Whether to validate only width
	 * @param height_only Validate only Height
	 * @return {boolean} All Good or not
	 */
	const validate_resize_settings = function (
		wrapper_div,
		width_only,
		height_only
	) {
		const resize_checkbox = wrapper_div.find('#resize');

		if (!height_only) {
			var width_input = wrapper_div.find('#wp-smush-resize_width');
			var width_error_note = wrapper_div.find(
				'.sui-notice-info.wp-smush-update-width'
			);
		}
		if (!width_only) {
			var height_input = wrapper_div.find('#wp-smush-resize_height');
			var height_error_note = wrapper_div.find(
				'.sui-notice-info.wp-smush-update-height'
			);
		}

		let width_error = false;
		let height_error = false;

		//If resize settings is not enabled, return true
		if (!resize_checkbox.is(':checked')) {
			return true;
		}

		//Check if we have localised width and height
		if (
			'undefined' === typeof wp_smushit_data.resize_sizes ||
			'undefined' === typeof wp_smushit_data.resize_sizes.width
		) {
			//Rely on server validation
			return true;
		}

		//Check for width
		if (
			!height_only &&
			'undefined' !== typeof width_input &&
			parseInt(wp_smushit_data.resize_sizes.width) >
				parseInt(width_input.val())
		) {
			width_input.parent().addClass('sui-form-field-error');
			width_error_note.show('slow');
			width_error = true;
		} else {
			//Remove error class
			width_input.parent().removeClass('sui-form-field-error');
			width_error_note.hide();
			if (height_input.hasClass('error')) {
				height_error_note.show('slow');
			}
		}

		//Check for height
		if (
			!width_only &&
			'undefined' !== typeof height_input &&
			parseInt(wp_smushit_data.resize_sizes.height) >
				parseInt(height_input.val())
		) {
			height_input.parent().addClass('sui-form-field-error');
			//If we are not showing the width error already
			if (!width_error) {
				height_error_note.show('slow');
			}
			height_error = true;
		} else {
			//Remove error class
			height_input.parent().removeClass('sui-form-field-error');
			height_error_note.hide();
			if (width_input.hasClass('error')) {
				width_error_note.show('slow');
			}
		}

		if (width_error || height_error) {
			return false;
		}
		return true;
	};

	/**
	 * Update the progress bar width if we have images that needs to be resmushed
	 *
	 * @param unsmushed_count
	 * @return {boolean}
	 */
	const update_progress_bar_resmush = function (unsmushed_count) {
		if ('undefined' === typeof unsmushed_count) {
			return false;
		}

		const smushed_count = wp_smushit_data.count_total - unsmushed_count;

		//Update the Progress Bar Width
		// get the progress bar
		const $progress_bar = jQuery(
			'.bulk-smush-wrapper .wp-smush-progress-inner'
		);
		if ($progress_bar.length < 1) {
			return;
		}

		const width = (smushed_count / wp_smushit_data.count_total) * 100;

		// increase progress
		$progress_bar.css('width', width + '%');
	};

	const runRecheck = function (process_settings) {
		const button = $('.wp-smush-scan');

		// Add a "loading" state to the button.
		button.addClass('sui-button-onload');

		// Check if type is set in data attributes.
		let scan_type = button.data('type');
		scan_type = 'undefined' === typeof scan_type ? 'media' : scan_type;

		// Remove the Skip resmush attribute from button.
		$('.wp-smush-all').removeAttr('data-smush');

		// Remove notices.
		const notices = $('.sui-notice-top.sui-notice-success');
		notices.slideUp(100, function () {
			notices.remove();
		});

		// Disable Bulk smush button and itself.
		$('.wp-smush-all').prop('disabled', true);

		// Hide Settings changed Notice.
		$('.wp-smush-settings-changed').hide();

		// Ajax params.
		const params = {
			action: 'scan_for_resmush',
			type: scan_type,
			get_ui: true,
			process_settings,
			wp_smush_options_nonce: jQuery('#wp_smush_options_nonce').val(),
		};

		// Send ajax request and get ids if any.
		$.get(ajaxurl, params, function (r) {
			// Check if we have the ids,  initialize the local variable.
			if ('undefined' !== typeof r.data) {
				// Update Resmush id list.
				if ('undefined' !== typeof r.data.resmush_ids) {
					wp_smushit_data.resmush = r.data.resmush_ids;

					// Update wp_smushit_data ( Smushed count, Smushed Percent, Image count, Super smush count, resize savings, conversion savings ).
					if ('undefined' !== typeof wp_smushit_data) {
						wp_smushit_data.count_smushed =
							'undefined' !== typeof r.data.count_smushed
								? r.data.count_smushed
								: wp_smushit_data.count_smushed;
						wp_smushit_data.count_supersmushed =
							'undefined' !== typeof r.data.count_supersmushed
								? r.data.count_supersmushed
								: wp_smushit_data.count_supersmushed;
						wp_smushit_data.count_images =
							'undefined' !== typeof r.data.count_image
								? r.data.count_image
								: wp_smushit_data.count_images;
						wp_smushit_data.size_before =
							'undefined' !== typeof r.data.size_before
								? r.data.size_before
								: wp_smushit_data.size_before;
						wp_smushit_data.size_after =
							'undefined' !== typeof r.data.size_after
								? r.data.size_after
								: wp_smushit_data.size_after;
						wp_smushit_data.savings_resize =
							'undefined' !== typeof r.data.savings_resize
								? r.data.savings_resize
								: wp_smushit_data.savings_resize;
						wp_smushit_data.savings_conversion =
							'undefined' !== typeof r.data.savings_conversion
								? r.data.savings_conversion
								: wp_smushit_data.savings_conversion;
						wp_smushit_data.count_resize =
							'undefined' !== typeof r.data.count_resize
								? r.data.count_resize
								: wp_smushit_data.count_resize;
						wp_smushit_data.unsmushed =
							'undefined' !== typeof r.data.unsmushed
								? r.data.unsmushed
								: wp_smushit_data.unsmushed;
					}

					if ('nextgen' === scan_type) {
						wp_smushit_data.bytes =
							parseInt(wp_smushit_data.size_before) -
							parseInt(wp_smushit_data.size_after);
					}

					// Hide the Existing wrapper.
					const notices = $(
						'.bulk-smush-wrapper .sui-notice:not(.smush-upsell-notice)'
					);
					if (notices.length > 0) {
						notices.addClass('sui-hidden');
						$('.wp-smush-pagespeed-recommendation').addClass(
							'sui-hidden'
						);
					}
					// Remove existing Re-Smush notices.
					$('.wp-smush-resmush-notice').remove();

					// Show Bulk wrapper.
					$('.wp-smush-bulk-wrapper').removeClass('sui-hidden');
				}
				// If content is received, Prepend it.
				if ('undefined' !== typeof r.data.content) {
					$('#wp-smush-bulk-content').html(r.data.content);
				}
				// If we have any notice to show.
				if ('undefined' !== typeof r.data.notice) {
					let type = 'success';
					if ('undefined' !== typeof r.data.noticeType) {
						type = r.data.noticeType;
					}
					window.SUI.openNotice(
						'wp-smush-ajax-notice',
						'<p>' + r.data.notice + '</p>',
						{ type, icon: 'check-tick' }
					);
				}
				// Hide errors.
				$('div.smush-final-log').hide();

				// Hide Super Smush notice if it's enabled in media settings.
				if (
					'undefined' !== typeof r.data.super_smush &&
					r.data.super_smush &&
					'undefined' !== r.data.super_smush_stat
				) {
					$('.super-smush-attachments .wp-smush-stats').html(
						r.data.super_smush_stats
					);
				}
				Smush.updateStats(scan_type);

				const remainingCount = r.data.count || 0;
				Smush.updateRemainingCount(remainingCount);
				updateDisplayedContentAfterReCheck(remainingCount);
			}
		}).always(function () {
			// Hide the progress bar.
			jQuery(
				'.bulk-smush-wrapper .wp-smush-bulk-progress-bar-wrapper'
			).addClass('sui-hidden');

			// Add check complete status to button.
			button
				.removeClass('sui-button-onload')
				.addClass('smush-button-check-success');

			const $defaultText = button.find('.wp-smush-default-text'),
				$completedText = button.find('.wp-smush-completed-text');

			$defaultText.addClass('sui-hidden-important');
			$completedText.removeClass('sui-hidden');

			// Remove success message from button.
			setTimeout(function () {
				button.removeClass('smush-button-check-success');

				$defaultText.removeClass('sui-hidden-important');
				$completedText.addClass('sui-hidden');
			}, 2000);

			$('.wp-smush-all').prop('disabled', false);
		});
	};

	const updateDisplayedContentAfterReCheck = function (count) {
		const $pendingImagesWrappers = jQuery(
			'.bulk-smush-wrapper .wp-smush-bulk-wrapper, #wp-smush-pending-to-smush-text'
		);
		const $allDoneWrappers = jQuery(
			'.bulk-smush-wrapper .wp-smush-all-done, .bulk-smush-wrapper .wp-smush-pagespeed-recommendation, #smush-box-bulk-upgrade, #wp-smush-all-smushed-text'
		);

		if ($pendingImagesWrappers.length && $allDoneWrappers.length) {
			if (count === 0) {
				$pendingImagesWrappers.addClass('sui-hidden');
				$allDoneWrappers.removeClass('sui-hidden');
			} else {
				$pendingImagesWrappers.removeClass('sui-hidden');
				$allDoneWrappers.addClass('sui-hidden');

				// Update texts mentioning the amount of unsmushed imagesin the summary icon tooltip.
				const $unsmushedTooltip = jQuery(
					'.sui-summary-smush .sui-summary-details .sui-tooltip'
				);

				// The tooltip doesn't exist in the NextGen page.
				if ($unsmushedTooltip.length) {
					const textForm = 1 === count ? 'singular' : 'plural',
						tooltipText = $unsmushedTooltip
							.data(textForm)
							.replace('{count}', count);
					$unsmushedTooltip.attr('data-tooltip', tooltipText);
				}
			}
		}

		// Total count in the progress bar.
		jQuery('.wp-smush-total-count').text(count);
	};

	// Scroll the element to top of the page.
	const goToByScroll = function (selector) {
		// Scroll if element found.
		if ($(selector).length > 0) {
			$('html, body').animate(
				{
					scrollTop: $(selector).offset().top - 100,
				},
				'slow'
			);
		}
	};

	const update_cummulative_stats = function (stats) {
		//Update Directory Smush Stats
		if ('undefined' !== typeof stats.dir_smush) {
			const stats_human = $(
				'li.smush-dir-savings span.wp-smush-stats span.wp-smush-stats-human'
			);
			const stats_percent = $(
				'li.smush-dir-savings span.wp-smush-stats span.wp-smush-stats-percent'
			);

			// Do not replace if 0 savings.
			if (stats.dir_smush.bytes > 0) {
				$('.wp-smush-dir-link').addClass('sui-hidden');

				// Hide selector.
				$('li.smush-dir-savings .wp-smush-stats-label-message').hide();
				//Update Savings in bytes
				if (stats_human.length > 0) {
					stats_human.html(stats.dir_smush.human);
				} else {
					var span =
						'<span class="wp-smush-stats-human">' +
						stats.dir_smush.bytes +
						'</span>';
				}

				//Percentage section
				if (stats.dir_smush.percent > 0) {
					// Show size and percentage separator.
					$(
						'li.smush-dir-savings span.wp-smush-stats span.wp-smush-stats-sep'
					).removeClass('sui-hidden');
					//Update Optimisation percentage
					if (stats_percent.length > 0) {
						stats_percent.html(stats.dir_smush.percent + '%');
					} else {
						var span =
							'<span class="wp-smush-stats-percent">' +
							stats.dir_smush.percent +
							'%' +
							'</span>';
					}
				}
			} else {
				$('.wp-smush-dir-link').removeClass('sui-hidden');
			}
		}

		//Update Combined stats
		if (
			'undefined' !== typeof stats.combined_stats &&
			stats.combined_stats.length > 0
		) {
			const c_stats = stats.combined_stats;

			let smush_percent = (c_stats.smushed / c_stats.total_count) * 100;
			smush_percent = WP_Smush.helpers.precise_round(smush_percent, 1);

			//Smushed Percent
			if (smush_percent) {
				$('div.wp-smush-count-total span.wp-smush-images-percent').html(
					smush_percent
				);
			}
			//Update Total Attachment Count
			if (c_stats.total_count) {
				$(
					'span.wp-smush-count-total span.wp-smush-total-optimised'
				).html(c_stats.total_count);
			}
			//Update Savings and Percent
			if (c_stats.savings) {
				$('span.wp-smush-savings span.wp-smush-stats-human').html(
					c_stats.savings
				);
			}
			if (c_stats.percent) {
				$('span.wp-smush-savings span.wp-smush-stats-percent').html(
					c_stats.percent
				);
			}
		}
	};

	/**
	 * When 'All' is selected for the Image Sizes setting, select all available sizes.
	 *
	 * @since 3.2.1
	 */
	$('#all-image-sizes').on('change', function () {
		$('input[name^="wp-smush-image_sizes"]').prop('checked', true);
	});

	/**
	 * Handles the tabs navigation on mobile.
	 *
	 * @since 3.8.4
	 */
	$('.sui-mobile-nav').on('change', (e) => {
		window.location.assign($(e.currentTarget).val());
	});

	/**
	 * Handle re-check api status button click (Settings)
	 *
	 * @since 3.2.0.2
	 */
	$('#update-api-status').on('click', function (e) {
		e.preventDefault();

		//$(this).prop('disabled', true);
		$(this).addClass('sui-button-onload');

		$.post(ajaxurl, { action: 'recheck_api_status' }, function () {
			location.reload();
		});
	});

	/** Handle smush button click **/
	$('body').on(
		'click',
		'.wp-smush-send:not(.wp-smush-resmush)',
		function (e) {
			// prevent the default action
			e.preventDefault();
			new Smush($(this), false);
		}
	);

	/** Handle show in bulk smush button click **/
	$('body').on('click', '.wp-smush-remove-skipped', function (e) {
		e.preventDefault();

		const self = $(this);

		// Send Ajax request to remove the image from the skip list.
		$.post(ajaxurl, {
			action: 'remove_from_skip_list',
			id: self.attr('data-id'),
		}).done((response) => {
			if (
				response.success &&
				'undefined' !== typeof response.data.links
			) {
				self.parent()
					.parent()
					.find('.smush-status')
					.text(wp_smush_msgs.not_processed);
				e.target.closest('.smush-status-links').innerHTML =
					response.data.links;
			}
		});
	});

	/** Handle NextGen Gallery smush button click **/
	$('body').on('click', '.wp-smush-nextgen-send', function (e) {
		// prevent the default action
		e.preventDefault();
		new Smush($(this), false, 'nextgen');
	});

	/** Handle NextGen Gallery Bulk smush button click **/
	$('body').on('click', '.wp-smush-nextgen-bulk', function (e) {
		// prevent the default action
		e.preventDefault();

		// Remove existing Re-Smush notices.
		$('.wp-smush-resmush-notice').remove();

		//Check for ids, if there is none (Unsmushed or lossless), don't call smush function
		if (
			'undefined' === typeof wp_smushit_data ||
			(wp_smushit_data.unsmushed.length === 0 &&
				wp_smushit_data.resmush.length === 0)
		) {
			return false;
		}

		jQuery('.wp-smush-all, .wp-smush-scan').prop('disabled', true);
		$('.wp-smush-notice.wp-smush-remaining').hide();
		new Smush($(this), true, 'nextgen');
	});

	/** Restore: Media Library **/
	$('body').on('click', '.wp-smush-action.wp-smush-restore', function (e) {
		const current_button = $(this);
		process_smush_action(
			e,
			current_button,
			'smush_restore_image',
			'restore'
		);
	});

	/** Resmush: Media Library **/
	$('body').on('click', '.wp-smush-action.wp-smush-resmush', function (e) {
		process_smush_action(e, $(this), 'smush_resmush_image', 'smushing');
	});

	/** Restore: NextGen Gallery **/
	$('body').on(
		'click',
		'.wp-smush-action.wp-smush-nextgen-restore',
		function (e) {
			process_smush_action(
				e,
				$(this),
				'smush_restore_nextgen_image',
				'restore'
			);
		}
	);

	/** Resmush: NextGen Gallery **/
	$('body').on(
		'click',
		'.wp-smush-action.wp-smush-nextgen-resmush',
		function (e) {
			process_smush_action(
				e,
				$(this),
				'smush_resmush_nextgen_image',
				'smushing'
			);
		}
	);

	//Scan For resmushing images
	$('.wp-smush-scan').on('click', function (e) {
		e.preventDefault();
		runRecheck(false);
	});

	//Dismiss Welcome notice
	//@todo: Use it for popup
	$('#wp-smush-welcome-box .smush-dismiss-welcome').on('click', function (e) {
		e.preventDefault();
		const $el = $(this).parents().eq(1);
		remove_element($el);

		//Send a ajax request to save the dismissed notice option
		const param = {
			action: 'dismiss_welcome_notice',
		};
		$.post(ajaxurl, param);
	});

	//Remove Notice
	$('body').on('click', '.wp-smush-notice .icon-fi-close', function (e) {
		e.preventDefault();
		const $el = $(this).parent();
		remove_element($el);
	});

	// On re-Smush click.
	$('body').on('click', '.wp-smush-skip-resmush', function (e) {
		e.preventDefault();

		const self = jQuery(this),
			container = self.parents().eq(1),
			el = self.parent();

		// Remove Parent div.
		remove_element(el);

		// Remove Settings Notice.
		$('.sui-notice-top.sui-notice-success').remove();

		// Set button attribute to skip re-smush ids.
		container.find('.wp-smush-all').attr('data-smush', 'skip_resmush');

		// Update Smushed count.
		wp_smushit_data.count_smushed =
			parseInt(wp_smushit_data.count_smushed) +
			wp_smushit_data.resmush.length;
		wp_smushit_data.count_supersmushed =
			parseInt(wp_smushit_data.count_supersmushed) +
			wp_smushit_data.resmush.length;

		// Update stats.
		if (wp_smushit_data.count_smushed === wp_smushit_data.count_total) {
			// Show all done notice.
			$(
				'.wp-smush-notice.wp-smush-all-done, .wp-smush-pagespeed-recommendation'
			).removeClass('sui-hidden');

			// Hide Smush button.
			$('.wp-smush-bulk-wrapper ').addClass('sui-hidden');
		}

		// Remove re-Smush notice.
		$('.wp-smush-resmush-notice').remove();

		let type = $('.wp-smush-scan').data('type');
		type = 'undefined' === typeof type ? 'media' : type;

		const smushed_count =
			'undefined' !== typeof wp_smushit_data.count_smushed
				? wp_smushit_data.count_smushed
				: 0;

		let smush_percent = (smushed_count / wp_smushit_data.count_total) * 100;
		smush_percent = WP_Smush.helpers.precise_round(smush_percent, 1);

		$('.wp-smush-images-percent').html(smush_percent);

		// Update the progress bar width. Get the progress bar.
		const progress_bar = jQuery(
			'.bulk-smush-wrapper .wp-smush-progress-inner'
		);
		if (progress_bar.length < 1) {
			return;
		}

		// Increase progress.
		progress_bar.css('width', smush_percent + '%');

		// Show the default bulk smush notice.
		$('.wp-smush-bulk-wrapper').removeClass('sui-hidden');
		$('.wp-smush-bulk-wrapper .sui-notice').removeClass('sui-hidden');

		const params = {
			action: 'delete_resmush_list',
			type,
		};

		//Delete resmush list, @todo: update stats from the ajax response
		$.post(ajaxurl, params, function (res) {
			// Remove the whole li element on success
			if (res.success && 'undefined' !== typeof res.data.stats) {
				const stats = res.data.stats;
				// Update wp_smushit_data ( Smushed count, Smushed Percent, Image count, Super smush count, resize savings, conversion savings )
				if ('undefined' !== typeof wp_smushit_data) {
					wp_smushit_data.count_images =
						'undefined' !== typeof stats.count_images
							? parseInt(wp_smushit_data.count_images) +
							  stats.count_images
							: wp_smushit_data.count_images;
					wp_smushit_data.size_before =
						'undefined' !== typeof stats.size_before
							? parseInt(wp_smushit_data.size_before) +
							  stats.size_before
							: wp_smushit_data.size_before;
					wp_smushit_data.size_after =
						'undefined' !== typeof stats.size_after
							? parseInt(wp_smushit_data.size_after) +
							  stats.size_after
							: wp_smushit_data.size_after;
					wp_smushit_data.savings_resize =
						'undefined' !== typeof stats.savings_resize
							? parseInt(wp_smushit_data.savings_resize) +
							  stats.savings_resize
							: wp_smushit_data.savings_resize;
					wp_smushit_data.savings_conversion =
						'undefined' !== typeof stats.savings_conversion
							? parseInt(wp_smushit_data.savings_conversion) +
							  stats.savings_conversion
							: wp_smushit_data.savings_conversion;

					// Add directory smush stats.
					if (
						'undefined' !==
							typeof wp_smushit_data.savings_dir_smush &&
						'undefined' !==
							typeof wp_smushit_data.savings_dir_smush.orig_size
					) {
						wp_smushit_data.size_before =
							'undefined' !==
							typeof wp_smushit_data.savings_dir_smush
								? parseInt(wp_smushit_data.size_before) +
								  parseInt(
										wp_smushit_data.savings_dir_smush
											.orig_size
								  )
								: wp_smushit_data.size_before;
						wp_smushit_data.size_after =
							'undefined' !==
							typeof wp_smushit_data.savings_dir_smush
								? parseInt(wp_smushit_data.size_after) +
								  parseInt(
										wp_smushit_data.savings_dir_smush
											.image_size
								  )
								: wp_smushit_data.size_after;
					}

					wp_smushit_data.count_resize =
						'undefined' !== typeof stats.count_resize
							? parseInt(wp_smushit_data.count_resize) +
							  stats.count_resize
							: wp_smushit_data.count_resize;
				}
				// Smush notice.
				const remainingCountDiv = $(
					'.bulk-smush-wrapper .wp-smush-remaining-count'
				);
				if (
					remainingCountDiv.length &&
					'undefined' !== typeof wp_smushit_data.unsmushed
				) {
					remainingCountDiv.html(wp_smushit_data.unsmushed.length);
				}

				// If no images left, hide the notice, show all success notice.
				if (
					'undefined' !== typeof wp_smushit_data.unsmushed ||
					wp_smushit_data.unsmushed.length === 0
				) {
					$('.wp-smush-bulk-wrapper .sui-notice').removeClass(
						'sui-hidden'
					);
					$('.sui-notice-success.wp-smush-all-done').addClass(
						'sui-hidden'
					);
				}

				Smush.updateStats();
			}
		});
	});

	// Enable super smush on clicking link from stats area.
	$('a.wp-smush-lossy-enable').on('click', function (e) {
		e.preventDefault();
		// Scroll down to settings area.
		goToByScroll('#column-lossy');
	});

	// Enable resize on clicking link from stats area.
	$('.wp-smush-resize-enable').on('click', function (e) {
		e.preventDefault();
		// Scroll down to settings area.
		goToByScroll('#column-resize');
	});

	// If settings string is found in url, enable and scroll.
	if ( window.location.hash ) {
		const setting_hash = window.location.hash.substring( 1 );
		let scrollTo = '';

		switch ( setting_hash ) {
			case 'enable-resize':
				scrollTo = '#column-resize';
				break;

			case 'backup-label':
				scrollTo = '#backup';
				break;

			case 'original-label':
				scrollTo = '#original';
				break;

			case 'enable-lossy':
				scrollTo = '#column-lossy';
				break;
		}

		if ( '' !== scrollTo ) {
			goToByScroll( scrollTo );
			document.getElementById( scrollTo.replace( '#', '' ) ).focus();
		}
	}

	//Trigger Bulk
	$('body').on('click', '.wp-smush-trigger-bulk', function (e) {
		e.preventDefault();

		//Induce Setting button save click
		if (
			'undefined' !== typeof e.target.dataset.type &&
			'nextgen' === e.target.dataset.type
		) {
			$('.wp-smush-nextgen-bulk').trigger('click');
		} else {
			$('.wp-smush-all').trigger('click');
		}

		$('span.sui-notice-dismiss').trigger('click');
	});

	//Trigger Bulk
	$('body').on('click', '#bulk-smush-top-notice-close', function (e) {
		e.preventDefault();
		$(this).parent().parent().slideUp('slow');
	});

	//Allow the checkboxes to be Keyboard Accessible
	$('.wp-smush-setting-row .toggle-checkbox').on('focus', function () {
		//If Space is pressed
		$(this).keypress(function (e) {
			if (e.keyCode == 32) {
				e.preventDefault();
				$(this).find('.toggle-checkbox').trigger('click');
			}
		});
	});

	// Re-Validate Resize Width And Height.
	$('body').on('blur', '.wp-smush-resize-input', function () {
		const self = $(this);

		const wrapper_div = self.parents().eq(4);

		// Initiate the check.
		validate_resize_settings(wrapper_div, false, false); // run the validation.
	});

	// Handle Resize Checkbox toggle, to show/hide width, height settings.
	$('body').on('click', '#resize', function () {
		const self = $(this);
		const settings_wrap = $('#smush-resize-settings-wrap');

		if (self.is(':checked')) {
			settings_wrap.show();
		} else {
			settings_wrap.hide();
		}
	});

	//Handle Re-check button functionality
	$('#wp-smush-revalidate-member').on('click', function (e) {
		e.preventDefault();
		//Ajax Params
		const params = {
			action: 'smush_show_warning',
		};
		const link = $(this);
		const parent = link.parents().eq(1);
		parent.addClass('loading-notice');
		$.get(ajaxurl, params, function (r) {
			//remove the warning
			parent.removeClass('loading-notice').addClass('loaded-notice');
			if (0 == r) {
				parent.attr('data-message', wp_smush_msgs.membership_valid);
				remove_element(parent, 1000);
			} else {
				parent.attr('data-message', wp_smush_msgs.membership_invalid);
				setTimeout(function remove_loader() {
					parent.removeClass('loaded-notice');
				}, 1000);
			}
		});
	});

	if ($('li.smush-dir-savings').length > 0) {
		// Update Directory Smush, as soon as the page loads.
		const stats_param = {
			action: 'get_dir_smush_stats',
		};
		$.get(ajaxurl, stats_param, function (r) {
			//Hide the spinner
			$('li.smush-dir-savings .sui-icon-loader').hide();

			//If there are no errors, and we have a message to display
			if (!r.success && 'undefined' !== typeof r.data.message) {
				$('div.wp-smush-scan-result div.content').prepend(
					r.data.message
				);
				return;
			}

			//If there is no value in r
			if (
				'undefined' === typeof r.data ||
				'undefined' === typeof r.data.dir_smush
			) {
				//Append the text
				$('li.smush-dir-savings span.wp-smush-stats').append(
					wp_smush_msgs.ajax_error
				);
				$('li.smush-dir-savings span.wp-smush-stats span').hide();
			} else {
				//Update the stats
				update_cummulative_stats(r.data);
			}
		});
	}

	//Dismiss Smush recommendation
	$('span.dismiss-recommendation').on('click', function (e) {
		e.preventDefault();
		const parent = $(this).parent();
		//remove div and save preference in db
		parent.hide('slow', function () {
			parent.remove();
		});
		$.ajax({
			type: 'POST',
			url: ajaxurl,
			data: {
				action: 'hide_pagespeed_suggestion',
			},
		});
	});

	// Display dialogs that show up with no user action.
	if ($('#smush-updated-dialog').length) {
		// Displays the modal with the release's higlights if it exists.
		window.SUI.openModal(
			'smush-updated-dialog',
			'wpbody-content',
			undefined,
			false
		);
	}

	/**
	 * Toggle backup notice based on "Compress uploaded images" setting.
	 * @since 3.9.1
	 */
	$( 'input#original' ).on( 'change', function() {
		$( '#backup-notice' ).toggleClass( 'sui-hidden', $( this ).is(':checked') );
	} );
});
