/**
 * External dependencies
 */
import React from 'react';

/**
 * WordPress dependencies
 */
const { __ } = wp.i18n;

export default ({ currentStep, smushData }) => {
	const getStepClass = (step) => {
		const stepClass = 'smush-wizard-bar-step';

		if (!smushData.isPro) {
			return stepClass + ' disabled';
		}

		if (step > currentStep) {
			return stepClass;
		}

		return (
			stepClass +
			(step === currentStep ? ' current' : ' sui-tooltip done')
		);
	};

	const getStepNumber = (step) => {
		return currentStep > step ? (
			<span className="sui-icon-check" aria-hidden="true"></span>
		) : (
			step
		);
	};

	const steps = [
		{ number: 1, title: __('Server Type', 'wp-smushit') },
		{ number: 2, title: __('Add Rules', 'wp-smushit') },
		{ number: 3, title: __('Finish Setup', 'wp-smushit') },
	];

	return (
		<div className="sui-sidenav">
			<span className="smush-wizard-bar-subtitle">
				{__('Setup', 'wp-smushit')}
			</span>
			<div className="smush-sidenav-title">
				<h4>{__('Local WebP', 'wp-smushit')}</h4>
				{!smushData.isPro && (
					<span className="sui-tag sui-tag-pro">
						{__('Pro', 'wp-smushit')}
					</span>
				)}
			</div>

			<div className="smush-wizard-steps-container">
				<svg
					className="smush-svg-mobile"
					focusable="false"
					aria-hidden="true"
				>
					<line
						x1="0"
						x2="50%"
						stroke={1 !== currentStep ? '#1ABC9C' : '#E6E6E6'}
					/>
					<line
						x1="50%"
						x2="100%"
						stroke={3 === currentStep ? '#1ABC9C' : '#E6E6E6'}
					/>
				</svg>
				<ul>
					{steps.map((step) => (
						<React.Fragment key={step.number}>
							<li
								className={getStepClass(step.number)}
								data-tooltip={__(
									'This stage is already completed.',
									'wp-smushit'
								)}
							>
								<div className="smush-wizard-bar-step-number">
									{getStepNumber(step.number)}
								</div>
								{step.title}
							</li>
							{3 !== step.number && (
								<svg
									data={step.number}
									data2={currentStep}
									className="smush-svg-desktop"
									focusable="false"
									aria-hidden="true"
								>
									<line
										y1="0"
										y2="40px"
										stroke={
											step.number < currentStep
												? '#1ABC9C'
												: '#E6E6E6'
										}
									/>
								</svg>
							)}
						</React.Fragment>
					))}
				</ul>
			</div>
		</div>
	);
};
