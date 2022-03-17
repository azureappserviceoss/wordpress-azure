/* global WP_Smush */
/* global ajaxurl */
/* global _ */

/**
 * Bulk restore JavaScript code.
 *
 * @since 3.2.2
 */
(function () {
	'use strict';

	/**
	 * Bulk restore modal.
	 *
	 * @since 3.2.2
	 */
	WP_Smush.restore = {
		modal: document.getElementById('smush-restore-images-dialog'),
		contentContainer: document.getElementById('smush-bulk-restore-content'),
		settings: {
			slide: 'start', // start, progress or finish.
			success: 0,
			errors: [],
		},
		items: [], // total items, 1 item = 1 step.
		success: [], // successful items restored.
		errors: [], // failed items.
		currentStep: 0,
		totalSteps: 0,

		/**
		 * Init module.
		 */
		init() {
			if (!this.modal) {
				return;
			}

			this.settings = {
				slide: 'start',
				success: 0,
				errors: [],
			};

			this.resetModalWidth();
			this.renderTemplate();

			// Show the modal.

			window.SUI.openModal(
				'smush-restore-images-dialog',
				'wpbody-content',
				undefined,
				false
			);
		},

		/**
		 * Update the template, register new listeners.
		 */
		renderTemplate() {
			const template = WP_Smush.onboarding.template('smush-bulk-restore');
			const content = template(this.settings);

			if (content) {
				this.contentContainer.innerHTML = content;
			}

			this.bindSubmit();
		},

		/**
		 * Reset modal width.
		 *
		 * @since 3.6.0
		 */
		resetModalWidth() {
			this.modal.style.maxWidth = '460px';
			this.modal.querySelector('.sui-box').style.maxWidth = '460px';
		},

		/**
		 * Catch "Finish setup wizard" button click.
		 */
		bindSubmit() {
			const confirmButton = this.modal.querySelector(
				'button[id="smush-bulk-restore-button"]'
			);
			const self = this;

			if (confirmButton) {
				confirmButton.addEventListener('click', function (e) {
					e.preventDefault();
					self.resetModalWidth();

					self.settings = { slide: 'progress' };
					self.errors = [];

					self.renderTemplate();
					self.initScan();
				});
			}
		},

		/**
		 * Cancel the bulk restore.
		 */
		cancel() {
			if (
				'start' === this.settings.slide ||
				'finish' === this.settings.slide
			) {
				// Hide the modal.
				window.SUI.closeModal();
			} else {
				this.updateProgressBar(true);
				window.location.reload();
			}
		},

		/**
		 * Update progress bar during directory smush.
		 *
		 * @param {boolean} cancel Cancel status.
		 */
		updateProgressBar(cancel = false) {
			let progress = 0;
			if (0 < this.currentStep) {
				progress = Math.min(
					Math.round((this.currentStep * 100) / this.totalSteps),
					99
				);
			}

			if (progress > 100) {
				progress = 100;
			}

			// Update progress bar
			this.modal.querySelector('.sui-progress-text span').innerHTML =
				progress + '%';
			this.modal.querySelector('.sui-progress-bar span').style.width =
				progress + '%';

			const statusDiv = this.modal.querySelector(
				'.sui-progress-state-text'
			);
			if (progress >= 90) {
				statusDiv.innerHTML = 'Finalizing...';
			} else if (cancel) {
				statusDiv.innerHTML = 'Cancelling...';
			} else {
				statusDiv.innerHTML =
					this.currentStep +
					'/' +
					this.totalSteps +
					' ' +
					'images restored';
			}
		},

		/**
		 * First step in bulk restore - get the bulk attachment count.
		 */
		initScan() {
			const self = this;
			const _nonce = document.getElementById('_wpnonce');

			const xhr = new XMLHttpRequest();
			xhr.open('POST', ajaxurl + '?action=get_image_count', true);
			xhr.setRequestHeader(
				'Content-type',
				'application/x-www-form-urlencoded'
			);
			xhr.onload = () => {
				if (200 === xhr.status) {
					const res = JSON.parse(xhr.response);
					if ('undefined' !== typeof res.data.items) {
						self.items = res.data.items;
						self.totalSteps = res.data.items.length;
						self.step();
					}
				} else {
					window.console.log(
						'Request failed.  Returned status of ' + xhr.status
					);
				}
			};
			xhr.send('_ajax_nonce=' + _nonce.value);
		},

		/**
		 * Execute a scan step recursively
		 */
		step() {
			const self = this;
			const _nonce = document.getElementById('_wpnonce');

			if (0 < this.items.length) {
				const item = this.items.pop();
				const xhr = new XMLHttpRequest();
				xhr.open('POST', ajaxurl + '?action=restore_step', true);
				xhr.setRequestHeader(
					'Content-type',
					'application/x-www-form-urlencoded'
				);
				xhr.onload = () => {
					this.currentStep++;

					if (200 === xhr.status) {
						const res = JSON.parse(xhr.response);
						if (
							'undefined' !== typeof res.data.success &&
							res.data.success
						) {
							self.success.push(item);
						} else {
							self.errors.push({
								id: item,
								src: res.data.src,
								thumb: res.data.thumb,
								link: res.data.link,
							});
						}
					}

					self.updateProgressBar();
					self.step();
				};
				xhr.send('item=' + item + '&_ajax_nonce=' + _nonce.value);
			} else {
				// Finish.
				this.settings = {
					slide: 'finish',
					success: this.success.length,
					errors: this.errors,
					total: this.totalSteps,
				};

				self.renderTemplate();
				if (0 < this.errors.length) {
					this.modal.style.maxWidth = '660px';
					this.modal.querySelector('.sui-box').style.maxWidth =
						'660px';
				}
			}
		},
	};

	/**
	 * Template function (underscores based).
	 *
	 * @type {Function}
	 */
	WP_Smush.restore.template = _.memoize((id) => {
		let compiled;
		const options = {
			evaluate: /<#([\s\S]+?)#>/g,
			interpolate: /{{{([\s\S]+?)}}}/g,
			escape: /{{([^}]+?)}}(?!})/g,
			variable: 'data',
		};

		return (data) => {
			_.templateSettings = options;
			compiled =
				compiled || _.template(document.getElementById(id).innerHTML);
			return compiled(data);
		};
	});
})();
