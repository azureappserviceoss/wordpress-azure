/* global ajaxurl */

/**
 * External dependencies
 */
import React from 'react';
import ReactDOM from 'react-dom';

/**
 * WordPress dependencies
 */
import domReady from '@wordpress/dom-ready';

/**
 * Internal dependencies
 */
import StepsBar from '../views/webp/steps-bar';
import StepContent from '../views/webp/step-content';
import FreeContent from '../views/webp/free-content';
import StepFooter from '../views/webp/step-footer';

export const WebpPage = ({ smushData }) => {
	const [currentStep, setCurrentStep] = React.useState(
		parseInt(smushData.startStep)
	);

	React.useEffect(() => {
		if (2 === currentStep) {
			window.SUI.suiCodeSnippet();
		}
	}, [currentStep]);

	const [serverType, setServerType] = React.useState(
		smushData.detectedServer
	);
	const [rulesMethod, setRulesMethod] = React.useState('automatic');
	const [rulesError, setRulesError] = React.useState(false);

	const makeRequest = (action, verb = 'GET') => {
		return new Promise((resolve, reject) => {
			const xhr = new XMLHttpRequest();
			xhr.open(
				verb,
				`${ajaxurl}?action=${action}&_ajax_nonce=${smushData.nonce}`,
				true
			);

			xhr.setRequestHeader(
				'Content-type',
				'application/x-www-form-urlencoded'
			);

			xhr.onload = () => {
				if (xhr.status >= 200 && xhr.status < 300) {
					resolve(JSON.parse(xhr.response));
				} else {
					reject(xhr);
				}
			};
			xhr.onerror = () => reject(xhr);
			xhr.send();
		});
	};

	const stepContent = smushData.isPro ? (
		<StepContent
			currentStep={currentStep}
			serverType={serverType}
			rulesMethod={rulesMethod}
			setRulesMethod={setRulesMethod}
			rulesError={rulesError}
			setServerType={setServerType}
			smushData={smushData}
			makeRequest={makeRequest}
		/>
	) : (
		<FreeContent smushData={smushData} />
	);

	return (
		<React.Fragment>
			<div className="sui-box-body sui-no-padding">
				<div className="sui-row-with-sidenav">
					<StepsBar smushData={smushData} currentStep={currentStep} />
					{stepContent}
				</div>
			</div>
			{smushData.isPro && (
				<StepFooter
					currentStep={currentStep}
					setCurrentStep={setCurrentStep}
					serverType={serverType}
					rulesMethod={rulesMethod}
					setRulesError={setRulesError}
					makeRequest={makeRequest}
				/>
			)}
		</React.Fragment>
	);
};

domReady(function () {
	const webpPageBox = document.getElementById('smush-box-webp-wizard');
	if (webpPageBox) {
		ReactDOM.render(
			<WebpPage smushData={window.smushReact} />,
			webpPageBox
		);
	}
});
