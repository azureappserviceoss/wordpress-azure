/* global WP_Smush */
/* global ajaxurl */

/**
 * WebP functionality.
 *
 * @since 3.8.0
 */

(function () {
	'use strict';

	WP_Smush.WebP = {
		nonceField: document.getElementsByName('wp_smush_options_nonce'),
		toggleModuleButton: document.getElementById('smush-toggle-webp-button'),
		recheckStatusButton: document.getElementById('smush-webp-recheck'),
		recheckStatusLink: document.getElementById('smush-webp-recheck-link'),
		showWizardButton: document.getElementById('smush-webp-toggle-wizard'),

		init() {
			this.maybeShowDeleteAllSuccessNotice();

			/**
			 * Handles the "Deactivate" and "Get Started" buttons on the WebP page.
			 */
			if (this.toggleModuleButton) {
				this.toggleModuleButton.addEventListener('click', (e) =>
					this.toggleWebp(e)
				);
			}

			/**
			 * Handle "RE-CHECK STATUS' button click on WebP page.
			 */
			if (this.recheckStatusButton) {
				this.recheckStatusButton.addEventListener('click', (e) => {
					e.preventDefault();
					this.recheckStatus();
				});
			}

			/**
			 * Handle "RE-CHECK STATUS' link click on WebP page.
			 */
			if (this.recheckStatusLink) {
				this.recheckStatusLink.addEventListener('click', (e) => {
					e.preventDefault();
					this.recheckStatus();
				});
			}

			/**
			 * Handles the "Delete WebP images" button.
			 */
			if (document.getElementById('wp-smush-webp-delete-all')) {
				document
					.getElementById('wp-smush-webp-delete-all')
					.addEventListener('click', (e) => this.deleteAll(e));
			}

			if (this.showWizardButton) {
				this.showWizardButton.addEventListener(
					'click',
					this.toggleWizard
				);
			}
		},

		/**
		 * Toggle WebP module.
		 *
		 * @param {Event} e
		 */
		toggleWebp(e) {
			e.preventDefault();

			const button = e.currentTarget,
				doEnable = 'enable' === button.dataset.action;

			button.classList.add('sui-button-onload');

			const xhr = new XMLHttpRequest();
			xhr.open('POST', ajaxurl + '?action=smush_webp_toggle', true);
			xhr.setRequestHeader(
				'Content-type',
				'application/x-www-form-urlencoded'
			);

			xhr.onload = () => {
				const res = JSON.parse(xhr.response);

				if (200 === xhr.status) {
					if ('undefined' !== typeof res.success && res.success) {
						const scanPromise = this.runScan();
						scanPromise.onload = () => {
							window.location.href =
								window.wp_smush_msgs.localWebpURL;
						};
					} else if ('undefined' !== typeof res.data.message) {
						this.showNotice(res.data.message);
						button.classList.remove('sui-button-onload');
					}
				} else {
					let message = window.wp_smush_msgs.generic_ajax_error;
					if (res && 'undefined' !== typeof res.data.message) {
						message = res.data.message;
					}
					this.showNotice(message);
					button.classList.remove('sui-button-onload');
				}
			};

			xhr.send(
				'param=' + doEnable + '&_ajax_nonce=' + this.nonceField[0].value
			);
		},

		/**
		 * re-check server configuration for WebP.
		 */
		recheckStatus() {
			this.recheckStatusButton.classList.add('sui-button-onload');

			const xhr = new XMLHttpRequest();
			xhr.open('POST', ajaxurl + '?action=smush_webp_get_status', true);
			xhr.setRequestHeader(
				'Content-type',
				'application/x-www-form-urlencoded'
			);
			xhr.onload = () => {
				this.recheckStatusButton.classList.remove('sui-button-onload');
				let message = false;
				const res = JSON.parse(xhr.response);
				if (200 === xhr.status) {
					const isConfigured = res.success ? '1' : '0';
					if (
						isConfigured !==
						this.recheckStatusButton.dataset.isConfigured
					) {
						// Reload the page when the configuration status changed.
						location.reload();
					}
				} else {
					message = window.wp_smush_msgs.generic_ajax_error;
				}

				if (res && res.data) {
					message = res.data;
				}

				if (message) {
					this.showNotice(message);
				}
			};
			xhr.send('_ajax_nonce=' + window.wp_smush_msgs.webp_nonce);
		},

		deleteAll(e) {
			const button = e.currentTarget;
			button.classList.add('sui-button-onload');

			let message = false;
			const xhr = new XMLHttpRequest();
			xhr.open('POST', ajaxurl + '?action=smush_webp_delete_all', true);
			xhr.setRequestHeader(
				'Content-type',
				'application/x-www-form-urlencoded'
			);

			xhr.onload = () => {
				const res = JSON.parse(xhr.response);
				if (200 === xhr.status) {
					if ('undefined' !== typeof res.success && res.success) {
						const scanPromise = this.runScan();
						scanPromise.onload = () => {
							location.search =
								location.search + '&notice=webp-deleted';
						};
					} else {
						message = window.wp_smush_msgs.generic_ajax_error;
					}
				} else {
					message = window.wp_smush_msgs.generic_ajax_error;
				}

				if (res && res.data && res.data.message) {
					message = res.data.message;
				}

				if (message) {
					button.classList.remove('sui-button-onload');

					const noticeMessage = `<p style="text-align: left;">${message}</p>`;
					const noticeOptions = {
						type: 'error',
						icon: 'info',
						autoclose: {
							show: false,
						},
					};

					window.SUI.openNotice(
						'wp-smush-webp-delete-all-error-notice',
						noticeMessage,
						noticeOptions
					);
				}
			};

			xhr.send('_ajax_nonce=' + this.nonceField[0].value);
		},

		toggleWizard(e) {
			e.currentTarget.classList.add('sui-button-onload');

			const xhr = new XMLHttpRequest();
			xhr.open(
				'GET',
				ajaxurl +
					'?action=smush_toggle_webp_wizard&_ajax_nonce=' +
					window.wp_smush_msgs.webp_nonce,
				true
			);
			xhr.onload = () => location.reload();
			xhr.send();
		},

		/**
		 * Triggers the scanning of images for updating the images to re-smush.
		 *
		 * @since 3.8.0
		 */
		runScan() {
			const xhr = new XMLHttpRequest(),
				nonceField = document.getElementsByName(
					'wp_smush_options_nonce'
				);

			xhr.open('POST', ajaxurl + '?action=scan_for_resmush', true);
			xhr.setRequestHeader(
				'Content-type',
				'application/x-www-form-urlencoded'
			);

			xhr.send('_ajax_nonce=' + nonceField[0].value);

			return xhr;
		},

		/**
		 * Show message (notice).
		 *
		 * @param {string} message
		 * @param {string} type
		 */
		showNotice(message, type) {
			if ('undefined' === typeof message) {
				return;
			}

			const noticeMessage = `<p>${message}</p>`;
			const noticeOptions = {
				type: type || 'error',
				icon: 'info',
				dismiss: {
					show: true,
					label: window.wp_smush_msgs.noticeDismiss,
					tooltip: window.wp_smush_msgs.noticeDismissTooltip,
				},
				autoclose: {
					show: false,
				},
			};

			window.SUI.openNotice(
				'wp-smush-ajax-notice',
				noticeMessage,
				noticeOptions
			);
		},

		/**
		 * Show delete all webp success notice.
		 */
		maybeShowDeleteAllSuccessNotice() {
			if (!document.getElementById('wp-smush-webp-delete-all-notice')) {
				return;
			}
			const noticeMessage = `<p>${
				document.getElementById('wp-smush-webp-delete-all-notice')
					.dataset.message
			}</p>`;

			const noticeOptions = {
				type: 'success',
				icon: 'check-tick',
				dismiss: {
					show: true,
				},
			};

			window.SUI.openNotice(
				'wp-smush-webp-delete-all-notice',
				noticeMessage,
				noticeOptions
			);
		},
	};

	WP_Smush.WebP.init();
})();
