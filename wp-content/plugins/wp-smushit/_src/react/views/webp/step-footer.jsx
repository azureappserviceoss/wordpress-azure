/**
 * External dependencies
 */
import React from 'react';

/**
 * WordPress dependencies
 */
const { __ } = wp.i18n;

export default ({
	currentStep,
	setCurrentStep,
	serverType,
	rulesMethod,
	setRulesError,
	makeRequest,
}) => {
	const genericRequestError = __(
		'Something went wrong with the request.',
		'wp-smushit'
	);

	const checkStatus = () => {
		setRulesError(false);

		makeRequest('smush_webp_get_status')
			.then((res) => {
				if (res.success) {
					setCurrentStep(currentStep + 1);
				} else {
					setRulesError(res.data);
				}
			})
			.catch(() => setRulesError(genericRequestError));
	};

	const applyRules = () => {
		setRulesError(false);

		makeRequest('smush_webp_apply_htaccess_rules')
			.then((res) => {
				if (res.success) {
					return checkStatus();
				}

				setRulesError(res.data);
			})
			.catch(() => setRulesError(genericRequestError));
	};

	const hideWizard = (e) => {
		e.currentTarget.classList.add(
			'sui-button-onload',
			'sui-button-onload-text'
		);
		makeRequest('smush_toggle_webp_wizard').then(() => location.reload());
	};

	// Markup stuff.
	let buttonsLeft;

	const quitButton = (
		<button
			type="button"
			className="sui-button sui-button-ghost"
			onClick={hideWizard}
		>
			<span className="sui-loading-text">
				<span className="sui-icon-logout" aria-hidden="true"></span>
				<span className="sui-hidden-xs">
					{__('Quit setup', 'wp-smushit')}
				</span>
				<span className="sui-hidden-sm sui-hidden-md sui-hidden-lg">
					{__('Quit', 'wp-smushit')}
				</span>
			</span>

			<span
				className="sui-icon-loader sui-loading"
				aria-hidden="true"
			></span>
		</button>
	);

	if (1 !== currentStep) {
		buttonsLeft = (
			<button
				type="button"
				className="sui-button sui-button-compound sui-button-ghost"
				onClick={() => setCurrentStep(currentStep - 1)}
			>
				<span className="sui-compound-desktop" aria-hidden="true">
					<span className="sui-icon-arrow-left"></span>
					{__('Previous', 'wp-smushit')}
				</span>

				<span className="sui-compound-mobile" aria-hidden="true">
					<span className="sui-icon-arrow-left"></span>
				</span>

				<span className="sui-screen-reader-text">
					{__('Previous', 'wp-smushit')}
				</span>
			</button>
		);
	}

	const getButtonsRight = () => {
		if (1 === currentStep) {
			return (
				<button
					type="button"
					className="sui-button sui-button-blue sui-button-icon-right"
					onClick={() => setCurrentStep(currentStep + 1)}
				>
					{__('Next', 'wp-smushit')}
					<span
						className="sui-icon-arrow-right"
						aria-hidden="true"
					></span>
				</button>
			);
		}

		if (2 === currentStep) {
			if ('apache' === serverType && 'automatic' === rulesMethod) {
				return (
					<button
						type="button"
						className="sui-button sui-button-blue"
						onClick={applyRules}
					>
						{__('Apply rules', 'wp-smushit')}
					</button>
				);
			}

			return (
				<button
					type="button"
					className="sui-button sui-button-blue"
					onClick={checkStatus}
				>
					{__('Check status', 'wp-smushit')}
				</button>
			);
		}

		return (
			<button
				type="button"
				className="sui-button sui-button-blue"
				onClick={hideWizard}
			>
				<span className="sui-button-text-default">
					{__('Finish', 'wp-smushit')}
				</span>

				<span className="sui-button-text-onload">
					<span
						className="sui-icon-loader sui-loading"
						aria-hidden="true"
					></span>
					{__('Finishing setup…', 'wp-smushit')}
				</span>
			</button>
		);
	};

	return (
		<div className="sui-box-footer">
			<div className="sui-actions-left">
				{quitButton}
				{buttonsLeft}
			</div>
			<div className="sui-actions-right">{getButtonsRight()}</div>
		</div>
	);
};
