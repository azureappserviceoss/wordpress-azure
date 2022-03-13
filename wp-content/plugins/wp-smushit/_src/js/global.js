import '../scss/common.scss';

/* global ajaxurl */

document.addEventListener( 'DOMContentLoaded', function() {
	const dismissNoticeBtn = document.getElementById(
		'smush-dismiss-conflict-notice'
	);
	if ( dismissNoticeBtn ) {
		dismissNoticeBtn.addEventListener( 'click', dismissNotice );
	}

	const dismissNoticeTop = document.querySelector(
		'#smush-conflict-notice > .notice-dismiss'
	);
	if ( dismissNoticeTop ) {
		dismissNoticeTop.addEventListener( 'click', dismissNotice );
	}

	function dismissNotice() {
		const xhr = new XMLHttpRequest();
		xhr.open(
			'POST',
			ajaxurl + '?action=dismiss_check_for_conflicts',
			true
		);
		xhr.onload = () => {
			const btn = document.querySelector(
				'#smush-conflict-notice > button.notice-dismiss'
			);
			if ( btn ) {
				btn.trigger('click');
			}
		};
		xhr.send();
	}
} );
