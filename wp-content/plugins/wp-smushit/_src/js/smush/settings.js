/* global ajaxurl */
/* global wp_smush_msgs */
/* global WP_Smush */
/* global SUI */

(function ($) {
	'use strict';

	/**
	 * Bulk compress page.
	 */
	$('form#smush-bulk-form').on('submit', function (e) {
		e.preventDefault();
		$('#save-settings-button').addClass('sui-button-onload');
		// TODO: this might be a problem, if we can't recalculate on larger installs.
		saveSettings($(this).serialize(), 'bulk');
		runReCheck();
	});

	/**
	 * Lazy load page.
	 */
	$('form#smush-lazy-load-form').on('submit', function (e) {
		e.preventDefault();
		$('#save-settings-button').addClass('sui-button-onload-text');
		saveSettings($(this).serialize(), 'lazy-load');
	});

	/**
	 * CDN page.
	 */
	$('form#smush-cdn-form').on('submit', function (e) {
		e.preventDefault();
		$('#save-settings-button').addClass('sui-button-onload-text');
		saveSettings($(this).serialize(), 'cdn');
	});

	/**
	 * Integrations page.
	 */
	$('form#smush-integrations-form').on('submit', function (e) {
		e.preventDefault();
		$('#save-settings-button').addClass('sui-button-onload-text');
		saveSettings($(this).serialize(), 'integrations');
	});

	/**
	 * Tools page.
	 */
	$('form#smush-tools-form').on('submit', function (e) {
		e.preventDefault();
		$('#save-settings-button').addClass('sui-button-onload-text');
		saveSettings($(this).serialize(), 'tools');
	});

	/**
	 * Settings page.
	 */
	$('form#smush-settings-form').on('submit', function (e) {
		e.preventDefault();
		$('#save-settings-button').addClass('sui-button-onload-text');
		saveSettings($(this).serialize(), 'settings');
	});

	/**
	 * Save settings.
	 *
	 * @param {string} settings  JSON string of settings.
	 * @param {string} page      Settings page.
	 */
	function saveSettings(settings, page) {
		const xhr = new XMLHttpRequest();

		xhr.open('POST', ajaxurl + '?action=smush_save_settings', true);
		xhr.setRequestHeader(
			'Content-type',
			'application/x-www-form-urlencoded'
		);

		xhr.onload = () => {
			$('#save-settings-button').removeClass(
				'sui-button-onload-text sui-button-onload'
			);

			if (200 === xhr.status) {
				const res = JSON.parse(xhr.response);
				if ('undefined' !== typeof res.success && res.success) {
					showSuccessNotice(wp_smush_msgs.settingsUpdated);
				} else if (res.data && res.data.message) {
					WP_Smush.helpers.showErrorNotice(res.data.message);
				} else {
					WP_Smush.helpers.showErrorNotice('Request failed.');
				}
			} else {
				WP_Smush.helpers.showErrorNotice(
					'Request failed. Returned status of ' + xhr.status
				);
			}
		};

		xhr.send(
			'page=' +
				page +
				'&' +
				settings +
				'&_ajax_nonce=' +
				wp_smush_msgs.nonce
		);
	}

	/**
	 * Show successful update notice.
	 *
	 * @param {string} msg  Notice message.
	 */
	function showSuccessNotice(msg) {
		const noticeMessage = `<p>${msg}</p>`,
			noticeOptions = {
				type: 'success',
				icon: 'check',
			};

		SUI.openNotice('wp-smush-ajax-notice', noticeMessage, noticeOptions);

		const loadingButton = document.querySelector('.sui-button-onload');
		if (loadingButton) {
			loadingButton.classList.remove('sui-button-onload');
		}
	}

	/**
	 * Re-check images from bulk smush and integrations pages.
	 */
	function runReCheck() {
		$('#save-settings-button').addClass('sui-button-onload');

		const param = {
			action: 'scan_for_resmush',
			wp_smush_options_nonce: $('#wp_smush_options_nonce').val(),
			type: 'media',
		};

		// Send ajax, Update Settings, And Check For resmush.
		$.post(ajaxurl, $.param(param)).done(function () {
			//showSuccessNotice(r.data.notice);
			$('#save-settings-button').removeClass('sui-button-onload');
		});
	}

	/**
	 * Parse remove data change.
	 */
	$('input[name=keep_data]').on('change', function (e) {
		const otherClass =
			'keep_data-true' === e.target.id
				? 'keep_data-false'
				: 'keep_data-true';
		e.target.parentNode.classList.add('active');
		document
			.getElementById(otherClass)
			.parentNode.classList.remove('active');
	});

	/**
	 * Handle auto detect checkbox toggle, to show/hide highlighting notice.
	 */
	$('input#detection').on('click', function () {
		const noticeDiv = $('.smush-highlighting-notice');
		const warningDiv = $('.smush-highlighting-warning');

		// Setting enabled.
		if ($(this).is(':checked')) {
			// Highlighting is already active and setting not saved.
			if (noticeDiv.length > 0) {
				noticeDiv.show();
			} else {
				warningDiv.show();
			}
		} else {
			noticeDiv.hide();
			warningDiv.hide();
		}
	});
})(jQuery);
