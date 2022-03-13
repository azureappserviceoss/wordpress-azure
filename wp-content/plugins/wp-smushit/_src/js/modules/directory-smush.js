/* global WP_Smush */
/* global ajaxurl */

/**
 * Directory Smush module JavaScript code.
 *
 * @since 2.8.1  Separated from admin.js into dedicated file.
 */

import { createTree } from 'jquery.fancytree';
import Scanner from '../smush/directory-scanner';

( function( $ ) {
	'use strict';

	WP_Smush.directory = {
		selected: [],
		tree: [],
		wp_smush_msgs: [],
		triggered: false,

		init() {
			const self = this,
				progressDialog = $( '#wp-smush-progress-dialog' );

			let totalSteps = 0,
				currentScanStep = 0;

			// Make sure directory smush vars are set.
			if ( typeof window.wp_smushit_data.dir_smush !== 'undefined' ) {
				totalSteps = window.wp_smushit_data.dir_smush.totalSteps;
				currentScanStep =
					window.wp_smushit_data.dir_smush.currentScanStep;
			}

			// Init image scanner.
			this.scanner = new Scanner( totalSteps, currentScanStep );

			/**
			 * Smush translation strings.
			 *
			 * @param {Array} wp_smush_msgs
			 */
			this.wp_smush_msgs = window.wp_smush_msgs || {};

			/**
			 * Open the "Select Smush directory" modal.
			 */
			$('button.wp-smush-browse, a#smush-directory-open-modal').on(
				'click',
				function (e) {
					e.preventDefault();

					if ( $( e.currentTarget ).hasClass( 'wp-smush-browse' ) ) {
						// Hide all the notices.
						$( 'div.wp-smush-scan-result div.wp-smush-notice' ).hide();

						// Remove notice.
						$( 'div.wp-smush-info' ).remove();
					}

					window.SUI.openModal(
						'wp-smush-list-dialog',
						e.currentTarget,
						$(
							'#wp-smush-list-dialog .sui-box-header [data-modal-close]'
						)[0],
						true
					);
					//Display File tree for Directory Smush
					self.initFileTree();
				}
			);

			/**
			 * Smush images: Smush in Choose Directory modal clicked
			 */
			$( '#wp-smush-select-dir' ).on( 'click', function( e ) {
				e.preventDefault();

				// If disabled, do not process
				if ( $( this ).prop( 'disabled' ) ) {
					return;
				}

				const button = $( this );

				$( 'div.wp-smush-list-dialog div.sui-box-body' ).css( {
					opacity: '0.8',
				} );
				$( 'div.wp-smush-list-dialog div.sui-box-body a' ).off(
					'click'
				);

				// Disable button
				button.prop( 'disabled', true );

				// Display the spinner.
				button.addClass('sui-button-onload');

				const selectedFolders = self.tree.getSelectedNodes();

				const paths = [];
				selectedFolders.forEach( function( folder ) {
					paths.push( folder.key );
				} );

				// Send a ajax request to get a list of all the image files
				const param = {
					action: 'image_list',
					smush_path: paths,
					image_list_nonce: $(
						'input[name="image_list_nonce"]'
					).val(),
				};

				$.post( ajaxurl, param, function( response ) {
					window.SUI.closeModal();

					if ( response.success ) {
						self.scanner = new Scanner( response.data, 0 );
						self.showProgressDialog( response.data );
						self.scanner.scan();
					} else {
						window.SUI.openNotice(
							'wp-smush-ajax-notice',
							response.data.message,
							{ type: 'warning' }
						);
					}
				} );
			} );

			/**
			 * Cancel scan.
			 */
			progressDialog.on(
				'click',
				'#cancel-directory-smush, #dialog-close-div, .wp-smush-cancel-dir',
				function (e) {
					e.preventDefault();
					// Display the spinner
					$('.wp-smush-cancel-dir').addClass('sui-button-onload');
					self.scanner
						.cancel()
						.done(
							() =>
								( window.location.href =
									self.wp_smush_msgs.directory_url )
						);
				}
			);

			/**
			 * Continue scan.
			 */
			progressDialog.on(
				'click',
				'.sui-icon-play, .wp-smush-resume-scan',
				function( e ) {
					e.preventDefault();
					self.scanner.resume();
				}
			);

			/**
			 * Check to see if we should open the directory module.
			 * Used to redirect from dashboard page.
			 *
			 * @since 3.8.6
			 */
			const queryString = window.location.search;
			const urlParams = new URLSearchParams(queryString);
			if (urlParams.has('start') && !this.triggered) {
				this.triggered = true;
				$('button.wp-smush-browse').trigger('click');
			}
		},

		/**
		 * Init fileTree.
		 */
		initFileTree() {
			const self = this,
				smushButton = $( 'button#wp-smush-select-dir' ),
				ajaxSettings = {
					type: 'GET',
					url: ajaxurl,
					data: {
						action: 'smush_get_directory_list',
						list_nonce: $( 'input[name="list_nonce"]' ).val(),
					},
					cache: false,
				};

			// Object already defined.
			if ( Object.entries( self.tree ).length > 0 ) {
				return;
			}

			self.tree = createTree( '.wp-smush-list-dialog .content', {
				autoCollapse: true, // Automatically collapse all siblings, when a node is expanded
				clickFolderMode: 3, // 1:activate, 2:expand, 3:activate and expand, 4:activate (dblclick expands)
				checkbox: true, // Show checkboxes
				debugLevel: 0, // 0:quiet, 1:errors, 2:warnings, 3:infos, 4:debug
				selectMode: 3, // 1:single, 2:multi, 3:multi-hier
				tabindex: '0', // Whole tree behaves as one single control
				keyboard: true, // Support keyboard navigation
				quicksearch: true, // Navigate to next node by typing the first letters
				source: ajaxSettings,
				lazyLoad: ( event, data ) => {
					data.result = new Promise( function( resolve, reject ) {
						ajaxSettings.data.dir = data.node.key;
						$.ajax( ajaxSettings )
							.done( ( response ) => resolve( response ) )
							.fail( reject );
					} );
				},
				loadChildren: ( event, data ) =>
					data.node.fixSelection3AfterClick(), // Apply parent's state to new child nodes:
				select: () =>
					smushButton.prop(
						'disabled',
						! +self.tree.getSelectedNodes().length
					),
				init: () => smushButton.prop( 'disabled', true ),
			} );
		},

		/**
		 * Show progress dialog.
		 *
		 * @param {number} items  Number of items in the scan.
		 */
		showProgressDialog( items ) {
			// Update items status and show the progress dialog..
			$( '.wp-smush-progress-dialog .sui-progress-state-text' ).html(
				'0/' + items + ' ' + self.wp_smush_msgs.progress_smushed
			);

			window.SUI.openModal(
				'wp-smush-progress-dialog',
				'dialog-close-div',
				undefined,
				false
			);
		},

		/**
		 * Update progress bar during directory smush.
		 *
		 * @param {number}  progress  Current progress in percent.
		 * @param {boolean} cancel    Cancel status.
		 */
		updateProgressBar( progress, cancel = false ) {
			if ( progress > 100 ) {
				progress = 100;
			}

			// Update progress bar
			$( '.sui-progress-block .sui-progress-text span' ).text(
				progress + '%'
			);
			$( '.sui-progress-block .sui-progress-bar span' ).width(
				progress + '%'
			);

			if ( progress >= 90 ) {
				$( '.sui-progress-state .sui-progress-state-text' ).text(
					'Finalizing...'
				);
			}

			if ( cancel ) {
				$( '.sui-progress-state .sui-progress-state-text' ).text(
					'Cancelling...'
				);
			}
		},
	};

	WP_Smush.directory.init();
} )( jQuery );
