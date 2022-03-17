/**
 * External dependencies
 */
import React from 'react';

/**
 * WordPress dependencies
 */
const { __ } = wp.i18n;

export default ({ smushData }) => {
	return (
		<div className="sui-box-body">
			<div className="sui-message">
				<img
					className="sui-image"
					src={smushData.urls.freeImg}
					alt={__('Smush WebP', 'wp-smushit')}
				/>

				<div className="sui-message-content">
					<p>
						{__(
							'Fix the "Serve images in next-gen format" Google PageSpeed recommendation by setting up this feature. Serve WebP versions of your images to supported browsers, and gracefully fall back on JPEGs and PNGs for browsers that don\'t support WebP.',
							'wp-smushit'
						)}
					</p>

					<ol className="sui-upsell-list">
						<li>
							<span
								className="sui-icon-check sui-sm"
								aria-hidden="true"
							/>
							{__(
								'Add or automatically apply the rules to enable Local WebP feature.',
								'wp-smushit'
							)}
						</li>
						<li>
							<span
								className="sui-icon-check sui-sm"
								aria-hidden="true"
							/>
							{__(
								'Fix “Serve images in next-gen format" Google PageSpeed recommendation.',
								'wp-smushit'
							)}
						</li>
						<li>
							<span
								className="sui-icon-check sui-sm"
								aria-hidden="true"
							/>
							{__(
								'Serve WebP version of images in the browsers that support it and fall back to JPEGs and PNGs for non supported browsers.',
								'wp-smushit'
							)}
						</li>
					</ol>

					<p className="sui-margin-top">
						<a
							href={smushData.urls.upsell}
							className="sui-button sui-button-purple"
							style={{ marginRight: '30px' }}
							target="_blank"
							rel="noreferrer"
						>
							{__('Try WebP for free', 'wp-smushit')}
						</a>
						<a
							href={smushData.urls.webpDoc}
							style={{ color: '#8D00B1' }}
							target="_blank"
							rel="noreferrer"
						>
							{__('Learn more', 'wp-smushit')}
						</a>
					</p>
				</div>
			</div>
		</div>
	);
};
