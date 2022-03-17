/**
 * External dependencies
 */
import React from 'react';
import ReactDOM from 'react-dom';

/**
 * WordPress dependencies
 */
import domReady from '@wordpress/dom-ready';
const { __, sprintf } = wp.i18n;

/**
 * SUI dependencies
 */
import { Presets } from '@wpmudev/shared-presets';

export const Configs = ({ isWidget }) => {
	// TODO: Handle the html interpolation and translation better.
	const proDescription = (
		<>
			{__(
				'You can easily apply configs to multiple sites at once via ',
				'wp-smushit'
			)}
			<a
				href={window.smushReact.links.hubConfigs}
				target="_blank"
				rel="noreferrer"
			>
				{__('the Hub.')}
			</a>
		</>
	);

	const closeIcon = __('Close this dialog window', 'wp-smushit'),
		cancelButton = __('Cancel', 'wp-smushit');

	const lang = {
		title: __('Preset Configs', 'wp-smushit'),
		upload: __('Upload', 'wp-smushit'),
		save: __('Save config', 'wp-smushit'),
		loading: __('Updating the config list…', 'wp-smushit'),
		emptyNotice: __(
			'You don’t have any available config. Save preset configurations of Smush’s settings, then upload and apply them to your other sites in just a few clicks!',
			'wp-smushit'
		),
		baseDescription: __(
			'Use configs to save preset configurations of Smush’s settings, then upload and apply them to your other sites in just a few clicks!',
			'wp-smushit'
		),
		proDescription,
		syncWithHubText: __(
			'Created or updated configs via the Hub?',
			'wp-smushit'
		),
		syncWithHubButton: __('Check again', 'wp-smushit'),
		apply: __('Apply', 'wp-smushit'),
		download: __('Download', 'wp-smushit'),
		edit: __('Name and Description', 'wp-smushit'),
		delete: __('Delete', 'wp-smushit'),
		notificationDismiss: __('Dismiss notice', 'wp-smushit'),
		freeButtonLabel: __('Try The Hub', 'wp-smushit'),
		defaultRequestError: sprintf(
			/* translators: %s request status */
			__(
				'Request failed. Status: %s. Please reload the page and try again.',
				'wp-smushit'
			),
			'{status}'
		),
		uploadActionSuccessMessage: sprintf(
			/* translators: %s request status */
			__(
				'%s config has been uploaded successfully – you can now apply it to this site.',
				'wp-smushit'
			),
			'{configName}'
		),
		uploadWrongPluginErrorMessage: sprintf(
			/* translators: %s {pluginName} */
			__(
				'The uploaded file is not a %s Config. Please make sure the uploaded file is correct.',
				'wp-smushit'
			),
			'{pluginName}'
		),
		applyAction: {
			closeIcon,
			cancelButton,
			title: __('Apply Config', 'wp-smushit'),
			description: sprintf(
				/* translators: %s config name */
				__(
					'Are you sure you want to apply the %s config to this site? We recommend you have a backup available as your existing settings configuration will be overridden.',
					'wp-smushit'
				),
				'{configName}'
			),
			actionButton: __('Apply', 'wp-smushit'),
			successMessage: sprintf(
				/* translators: %s. config name */
				__('%s config has been applied successfully.', 'wp-smushit'),
				'{configName}'
			),
		},
		deleteAction: {
			closeIcon,
			cancelButton,
			title: __('Delete Configuration File', 'wp-smushit'),
			description: sprintf(
				/* translators: %s config name */
				__(
					'Are you sure you want to delete %s? You will no longer be able to apply it to this or other connected sites.',
					'wp-smushit'
				),
				'{configName}'
			),
			actionButton: __('Delete', 'wp-smushit'),
		},
		editAction: {
			closeIcon,
			cancelButton,
			nameInput: __('Config name', 'wp-smushit'),
			descriptionInput: __('Description', 'wp-smushit'),
			emptyNameError: __('The config name is required', 'wp-smushit'),
			actionButton: __('Save', 'wp-smushit'),
			editTitle: __('Rename Config', 'wp-smushit'),
			editDescription: __(
				'Change your config name to something recognizable.',
				'wp-smushit'
			),
			createTitle: __('Save Config', 'wp-smushit'),
			createDescription: __(
				'Save your current settings configuration. You’ll be able to then download and apply it to your other sites.',
				'wp-smushit'
			),
			successMessage: sprintf(
				/* translators: %s. config name */
				__('%s config created successfully.', 'wp-smushit'),
				'{configName}'
			),
		},
		settingsLabels: {
			bulk_smush: __('Bulk Smush', 'wp-smushit'),
			integrations: __('Integrations', 'wp-smushit'),
			lazy_load: __('Lazy Load', 'wp-smushit'),
			cdn: __('CDN', 'wp-smushit'),
			webp_mod: __('Local WebP', 'wp-smushit'),
			tools: __('Tools', 'wp-smushit'),
			settings: __('Settings', 'wp-smushit'),
			networkwide: __('Subsite Controls', 'wp-smushit'),
		},
	};

	return (
		<Presets
			isWidget={isWidget}
			isPro={window.smushReact.isPro}
			isWhitelabel={window.smushReact.hideBranding}
			sourceLang={lang}
			sourceUrls={window.smushReact.links}
			requestsData={window.smushReact.requestsData}
		/>
	);
};

domReady(function () {
	const configsPageBox = document.getElementById('smush-box-configs');
	if (configsPageBox) {
		ReactDOM.render(<Configs isWidget={false} />, configsPageBox);
	}
	const configsWidgetBox = document.getElementById('smush-widget-configs');
	if (configsWidgetBox) {
		ReactDOM.render(<Configs isWidget={true} />, configsWidgetBox);
	}
});
