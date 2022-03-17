/**
 * External dependencies
 */
import React from 'react';

/**
 * WordPress dependencies
 */
const { __, sprintf } = wp.i18n;

export default ({
	currentStep,
	serverType,
	rulesMethod,
	setRulesMethod,
	setServerType,
	rulesError,
	smushData,
	makeRequest,
}) => {
	const stepsHeading = {
		1: {
			title: __('Choose Server Type', 'wp-smushit'),
			description: __(
				'Choose your server type. If you don’t know this, please contact your hosting provider.',
				'wp-smushit'
			),
		},
		2: {
			title: __('Add Rules', 'wp-smushit'),
			description:
				'apache' === serverType
					? __(
							'Smush can automatically apply WebP conversion rules for Apache servers by writing to your .htaccess file. Alternatively, switch to Manual to apply these rules yourself.',
							'wp-smushit'
					  )
					: __(
							'The following configurations are for NGINX servers. If you do not have access to your NGINX config files you will need to contact your hosting provider to make these changes.',
							'wp-smushit'
					  ),
		},
		3: {
			title: __('Finish Setup', 'wp-smushit'),
			description: __(
				'The rules have been applied successfully.',
				'wp-smushit'
			),
		},
	};

	const getTopNotice = () => {
		if (1 === currentStep && smushData.isS3Enabled) {
			return (
				<div className="sui-notice sui-notice-warning">
					<div className="sui-notice-content">
						<div className="sui-notice-message">
							<span
								className="sui-notice-icon sui-icon-info sui-md"
								aria-hidden="true"
							></span>
							<p>
								{__(
									'We noticed the Amazon S3 Integration is enabled. Offloaded images will not be served in WebP format, but Smush will create local WebP copies of all images. If this is undesirable, you can quit the setup.',
									'wp-smushit'
								)}
							</p>
						</div>
					</div>
				</div>
			);
		}

		if (2 === currentStep) {
			return (
				<div
					role="alert"
					className="sui-notice sui-notice-warning"
					aria-live="assertive"
					style={rulesError ? { display: 'block' } : {}}
				>
					{rulesError && (
						<div className="sui-notice-content">
							<div className="sui-notice-message">
								<span
									className="sui-notice-icon sui-icon-info sui-md"
									aria-hidden="true"
								></span>
								<p
									dangerouslySetInnerHTML={{
										__html: rulesError,
									}}
								/>
							</div>
						</div>
					)}
				</div>
			);
		}

		if (smushData.isWpmudevHost) {
			const message = !smushData.isWhitelabel
				? __(
						'Since your site is hosted with WPMU DEV, we already have done the configurations steps for you. The only step for you would be to create WebP images below.',
						'wp-smushit'
				  )
				: __(
						'WebP conversion is active and working well. Your hosting has automatically pre-configured the conversion for you. The only step for you would be to create WebP images below.',
						'wp-smushit'
				  );

			return (
				<div className="sui-notice sui-notice-info">
					<div className="sui-notice-content">
						<div className="sui-notice-message">
							<span
								className="sui-notice-icon sui-icon-info sui-md"
								aria-hidden="true"
							></span>
							<p>{message}</p>
						</div>
					</div>
				</div>
			);
		}
	};

	const getStepContent = () => {
		if (1 === currentStep) {
			return (
				<React.Fragment>
					<div className="sui-box-selectors">
						<ul>
							<li>
								<label
									htmlFor="smush-wizard-server-type-apache"
									className="sui-box-selector"
								>
									<input
										id="smush-wizard-server-type-apache"
										type="radio"
										value="apache"
										checked={'apache' === serverType}
										onChange={(e) =>
											setServerType(e.currentTarget.value)
										}
									/>
									<span>{__('Apache', 'wp-smushit')}</span>
								</label>
							</li>

							<li>
								<label
									htmlFor="smush-wizard-server-type-nginx"
									className="sui-box-selector"
								>
									<input
										id="smush-wizard-server-type-nginx"
										type="radio"
										value="nginx"
										checked={'nginx' === serverType}
										onChange={(e) =>
											setServerType(e.currentTarget.value)
										}
									/>
									<span>{__('NGINX', 'wp-smushit')}</span>
								</label>
							</li>
						</ul>
					</div>

					<div className="sui-notice" style={{ textAlign: 'left' }}>
						<div className="sui-notice-content">
							<div className="sui-notice-message">
								<span
									className="sui-notice-icon sui-icon-info sui-md"
									aria-hidden="true"
								></span>
								<p>
									{sprintf(
										/* translators: server type */
										__(
											"We've automatically detected your server type is %s. If this is incorrect, manually select your server type to generate the relevant rules and instructions.",
											'wp-smushit'
										),
										'nginx' === smushData.detectedServer
											? 'NGINX'
											: 'Apache / LiteSpeed'
									)}
								</p>
							</div>
						</div>
					</div>
				</React.Fragment>
			);
		}

		if (2 === currentStep) {
			if ('nginx' === serverType) {
				return (
					<div className="smush-wizard-rules-wrapper">
						<ol className="sui-description">
							<li>
								{__(
									'Insert the following in the server context of your configuration file (usually found in /etc/nginx/sites-available). “The server context” refers to the part of the configuration that starts with “server {” and ends with the matching “}”.',
									'wp-smushit'
								)}
							</li>
							<li>
								{__(
									'Copy the generated code found below and paste it inside your http or server blocks.',
									'wp-smushit'
								)}
							</li>
						</ol>

						<pre
							className="sui-code-snippet"
							style={{ marginLeft: '12px' }}
						>
							{smushData.nginxRules}
						</pre>
						<ol className="sui-description" start="3">
							<li>{__('Reload NGINX.', 'wp-smushit')}</li>
						</ol>

						<p className="sui-description">
							{__('Still having trouble?', 'wp_smushit')}{' '}
							<a
								href={smushData.urls.support}
								target="_blank"
								rel="noreferrer"
							>
								{__('Get Support.', 'wp_smushit')}
							</a>
						</p>
					</div>
				);
			}

			// TODO: The non-selected button isn't focusable this way. Why arrows don't workkkkkkk?
			return (
				<div className="sui-side-tabs sui-tabs">
					<div role="tablist" className="sui-tabs-menu">
						<button
							type="button"
							role="tab"
							id="smush-tab-automatic"
							className={
								'sui-tab-item' +
								('automatic' === rulesMethod ? ' active' : '')
							}
							aria-controls="smush-tab-content-automatic"
							aria-selected={'automatic' === rulesMethod}
							onClick={() => setRulesMethod('automatic')}
							tabIndex={'automatic' === rulesMethod ? '0' : '-1'}
						>
							{__('Automatic', 'wp-smushit')}
						</button>

						<button
							type="button"
							role="tab"
							id="smush-tab-manual"
							className={
								'sui-tab-item' +
								('manual' === rulesMethod ? ' active' : '')
							}
							aria-controls="smush-tab-content-manual"
							aria-selected={'manual' === rulesMethod}
							onClick={() => setRulesMethod('manual')}
							tabIndex={'manual' === rulesMethod ? '0' : '-1'}
						>
							{__('Manual', 'wp-smushit')}
						</button>
					</div>

					<div className="sui-tabs-content">
						<div
							role="tabpanel"
							tabIndex="0"
							id="smush-tab-content-automatic"
							className={
								'sui-tab-content' +
								('automatic' === rulesMethod ? ' active' : '')
							}
							aria-labelledby="smush-tab-automatic"
							hidden={'automatic' !== rulesMethod}
						>
							<p
								className="sui-description"
								style={{ marginTop: '30px' }}
							>
								{__(
									'Please note: Some servers have both Apache and NGINX software which may not begin serving WebP images after applying the .htaccess rules. If errors occur after applying the rules, we recommend adding NGINX rules manually.',
									'wp-smushit'
								)}
							</p>
						</div>

						<div
							role="tabpanel"
							tabIndex="0"
							id="smush-tab-content-manual"
							className={
								'sui-tab-content' +
								('manual' === rulesMethod ? ' active' : '')
							}
							aria-labelledby="smush-tab-manual"
							hidden={'manual' !== rulesMethod}
						>
							<p className="sui-description">
								{__(
									'If you are unable to get the automated method working, follow these steps:',
									'wp-smushit'
								)}
							</p>

							<div className="smush-wizard-rules-wrapper">
								<ol className="sui-description">
									<li>
										{__(
											'Copy the generated code below and paste it at the top of your .htaccess file (before any existing code) in the root directory.',
											'wp-smushit'
										)}
									</li>
								</ol>

								<pre
									className="sui-code-snippet"
									style={{ marginLeft: '12px' }}
								>
									{smushData.apacheRules}
								</pre>
								<ol className="sui-description" start="2">
									<li>
										{__(
											"Next, click Check Status button below to see if it's working.",
											'wp-smushit'
										)}
									</li>
								</ol>

								<h5
									className="sui-settings-label"
									style={{
										marginTop: '30px',
										fontSize: '13px',
										color: '#333333',
									}}
								>
									{__('Troubleshooting', 'wp-smushit')}
								</h5>

								<p className="sui-description">
									{__(
										'If .htaccess does not work, and you have access to vhosts.conf or httpd.conf, try this:',
										'wp-smushit'
									)}
								</p>

								<ol className="sui-description">
									<li>
										{__(
											'Look for your site in the file and find the line that starts with <Directory> - add the code above that line and into that section and save the file.',
											'wp-smushit'
										)}
									</li>
									<li>
										{__('Reload Apache.', 'wp-smushit')}
									</li>
									<li>
										{__(
											"If you don't know where those files are, or you aren't able to reload Apache, you would need to consult with your hosting provider or a system administrator who has access to change the configuration of your server.",
											'wp-smushit'
										)}
									</li>
								</ol>

								<p className="sui-description">
									{__('Still having trouble?', 'wp_smushit')}{' '}
									<a
										href={smushData.urls.support}
										target="_blank"
										rel="noreferrer"
									>
										{__('Get Support.', 'wp_smushit')}
									</a>
								</p>
							</div>
						</div>
					</div>
				</div>
			);
		}

		const hideWizard = (e) => {
			e.preventDefault();
			makeRequest('smush_toggle_webp_wizard').then(() => {
				location.href = smushData.urls.bulkPage;
			});
		};

		return (
			<React.Fragment>
				<p style={{ marginBottom: 0 }}><b>{__('Convert Images to WebP', 'wp-smushit')}</b></p>
				<p className="sui-description" dangerouslySetInnerHTML={ { __html: smushData.thirdStepMsg } } />
				{!smushData.isMultisite && (
					<p>
						<a href={smushData.urls.bulkPage} onClick={hideWizard}>
							{__('Convert now', 'wp-smushit')}
						</a>
					</p>
				)}
			</React.Fragment>
		);
	};

	const stepIndicatorText = sprintf(
		/* translators: currentStep/totalSteps indicator */
		__('Step %s', 'wp-smushit'),
		currentStep + '/3'
	);

	return (
		<div
			className={`smush-wizard-steps-content-wrapper smush-wizard-step-${currentStep}`}
		>
			{getTopNotice()}
			<div className="smush-wizard-steps-content">
				<span className="smush-step-indicator">
					{stepIndicatorText}
				</span>
				<h2>{stepsHeading[currentStep].title}</h2>
				<p className="sui-description">
					{stepsHeading[currentStep].description}
				</p>
				{getStepContent()}
			</div>
		</div>
	);
};
