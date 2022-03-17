/* global smush_vars */
/* global _ */

/**
 * Adds a Smush Now button and displays stats in Media Attachment Details Screen
 */
(function ($, _) {
	'use strict';

	// Local reference to the WordPress media namespace.
	const smushMedia = wp.media,
		sharedTemplate =
			"<span class='setting smush-stats' data-setting='smush'>" +
			"<span class='name'><%= label %></span>" +
			"<span class='value'><%= value %></span>" +
			'</span>',
		template = _.template(sharedTemplate);

	/**
	 * Create the template.
	 *
	 * @param {string} smushHTML
	 * @return {Object} Template object
	 */
	const prepareTemplate = function (smushHTML) {
		/**
		 * @param {Array}  smush_vars.strings  Localization strings.
		 * @param {Object} smush_vars          Object from wp_localize_script()
		 */
		return template({
			label: smush_vars.strings.stats_label,
			value: smushHTML,
		});
	};

	if (
		'undefined' !== typeof smushMedia.view &&
		'undefined' !== typeof smushMedia.view.Attachment.Details.TwoColumn
	) {
		// Local instance of the Attachment Details TwoColumn used in the edit attachment modal view
		const smushMediaTwoColumn =
			smushMedia.view.Attachment.Details.TwoColumn;

		/**
		 * Add Smush details to attachment.
		 *
		 * A similar view to media.view.Attachment.Details
		 * for use in the Edit Attachment modal.
		 *
		 * @see wp-includes/js/media-grid.js
		 */
		smushMedia.view.Attachment.Details.TwoColumn = smushMediaTwoColumn.extend(
			{
				initialize() {
					smushMediaTwoColumn.prototype.initialize.apply(this, arguments);
					this.listenTo(this.model, 'change:smush', this.render);
				},

				render() {
					// Ensure that the main attachment fields are rendered.
					smushMedia.view.Attachment.prototype.render.apply(
						this,
						arguments
					);

					const smushHTML = this.model.get('smush');
					if (typeof smushHTML === 'undefined') {
						return this;
					}

					this.model.fetch();

					/**
					 * Detach the views, append our custom fields, make sure that our data is fully updated
					 * and re-render the updated view.
					 */
					this.views.detach();
					this.$el
						.find('.settings')
						.append(prepareTemplate(smushHTML));
					this.views.render();

					return this;
				},
			}
		);
	}

	// Local instance of the Attachment Details TwoColumn used in the edit attachment modal view
	const smushAttachmentDetails = smushMedia.view.Attachment.Details;

	/**
	 * Add Smush details to attachment.
	 */
	smushMedia.view.Attachment.Details = smushAttachmentDetails.extend({
		initialize() {
			smushAttachmentDetails.prototype.initialize.apply(this, arguments);
			this.listenTo(this.model, 'change:smush', this.render);
		},

		render() {
			// Ensure that the main attachment fields are rendered.
			smushMedia.view.Attachment.prototype.render.apply(this, arguments);

			const smushHTML = this.model.get('smush');
			if (typeof smushHTML === 'undefined') {
				return this;
			}

			this.model.fetch();

			/**
			 * Detach the views, append our custom fields, make sure that our data is fully updated
			 * and re-render the updated view.
			 */
			this.views.detach();
			this.$el.append(prepareTemplate(smushHTML));

			return this;
		},
	});

	/**
	 * Create a new MediaLibraryTaxonomyFilter we later will instantiate
	 *
	 * @since 3.0
	 */
	const MediaLibraryTaxonomyFilter = wp.media.view.AttachmentFilters.extend({
		id: 'media-attachment-smush-filter',

		createFilters() {
			this.filters = {
				all: {
					text: smush_vars.strings.filter_all,
					props: { stats: 'all' },
					priority: 10,
				},

				unsmushed: {
					text: smush_vars.strings.filter_not_processed,
					props: { stats: 'unsmushed' },
					priority: 20,
				},

				excluded: {
					text: smush_vars.strings.filter_excl,
					props: { stats: 'excluded' },
					priority: 30,
				},
			};
		},
	});

	/**
	 * Extend and override wp.media.view.AttachmentsBrowser to include our new filter.
	 *
	 * @since 3.0
	 */
	const AttachmentsBrowser = wp.media.view.AttachmentsBrowser;
	wp.media.view.AttachmentsBrowser = wp.media.view.AttachmentsBrowser.extend({
		createToolbar() {
			// Make sure to load the original toolbar
			AttachmentsBrowser.prototype.createToolbar.call(this);
			this.toolbar.set(
				'MediaLibraryTaxonomyFilter',
				new MediaLibraryTaxonomyFilter({
					controller: this.controller,
					model: this.collection.props,
					priority: -75,
				}).render()
			);
		},
	});
})(jQuery, _);
