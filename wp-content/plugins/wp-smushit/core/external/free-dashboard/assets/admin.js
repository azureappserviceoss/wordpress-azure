jQuery( function () {
	const notice = document.querySelector( '.frash-notice' );
	const type = notice.querySelector( 'input[name=type]' ).value;
	const emailInput = notice.querySelector( 'input[name=EMAIL]' );
	const btnAct = notice.querySelector( '.frash-notice-act' );
	const btnDismiss = notice.querySelector( '.frash-notice-dismiss' );

	/**
	 * Display the notice after the page was loaded.
	 */
	function initialize() {
		fadeIn( notice, 500 );
		initEmail();
	}

	/**
	 * Initialize the email field.
	 */
	function initEmail() {
		if ( ! emailInput ) {
			return;
		}

		// Adjust the size of the email field to its contents.
		function adjustEmailSize() {
			const el = document.createElement( 'span' );

			el.setAttribute( 'class', 'input-field' );
			el.innerHTML = emailInput.value;
			document.querySelector( 'body' ).appendChild( el );
			const width = Math.ceil( el.getBoundingClientRect().width );
			el.remove();

			emailInput.style.width = width + 34 + 'px';
		}

		emailInput.addEventListener( 'keypress', function ( e ) {
			if ( e.defaultPrevented ) {
				return;
			}

			let handled = false;
			if ( undefined !== e.key && 'Enter' === e.key ) {
				btnAct.click();
				handled = true;
			} else if ( undefined !== e.keyCode && 13 === e.keyCode ) {
				btnAct.click();
				handled = true;
			}

			if ( handled ) {
				// Suppress "double action" if event handled.
				e.preventDefault();
			} else {
				adjustEmailSize();
			}
		} );

		adjustEmailSize();
	}

	/**
	 * Open a tab to rate the plugin.
	 */
	function actRate() {
		const urlWP = notice.querySelector( 'input[name=url_wp]' ).value;
		const url =
			urlWP.replace( /\/plugins\//, '/support/plugin/' ) +
			'/reviews/?rate=5#new-post';
		const link = document.createElement( 'a' );
		link.setAttribute( 'href', url );
		link.setAttribute( 'target', '_blank' );
		link.innerHTML = 'Rate';

		document.querySelector( 'body' ).appendChild( link );
		link.click();
		link.remove();
	}

	/**
	 * Submit the user to our email list.
	 */
	function actEmail() {
		const form = emailInput.closest( 'form' );

		const query = [];
		for ( const el of form.querySelectorAll( 'input' ) ) {
			query.push(
				encodeURIComponent( el.name ) +
					'=' +
					encodeURIComponent( el.value )
			);
		}

		// TODO: refactor jQuery to JavaScript.
		jQuery.ajax( {
			type: form.getAttribute( 'method' ),
			url: form.getAttribute( 'action' ),
			data: query.join( '&' ),
			cache: false,
			dataType: 'json',
			contentType: 'application/json; charset=utf-8',
			success( data ) {
				window.console.log( data.msg );
			},
		} );
	}

	/**
	 * Notify WordPress about the users choice and close the message.
	 *
	 * @param {string} action
	 * @param {string} message
	 */
	function notifyWordPress( action, message ) {
		notice.dataset.message = message;
		notice.classList.add( 'loading' );

		const ajaxData = {
			action,
			plugin_id: notice.querySelector( 'input[name=plugin_id]' ).value,
			type,
		};

		console.log( ajaxData );

		// TODO: refactor jQuery to JavaScript
		jQuery.post( window.ajaxurl, ajaxData, hideNotice );
	}

	/**
	 * Handle click on the primary CTA button.
	 * Either open the wp.org page or submit the email address.
	 */
	if ( btnAct ) {
		btnAct.addEventListener( 'click', function ( e ) {
			e.preventDefault();

			// Do not submit form if the value is not set.
			const input = notice.querySelector( 'input[type="email"]' );
			if ( ( ! input || ! input.value ) && type === 'email' ) {
				return;
			}

			switch ( type ) {
				case 'rate':
					actRate();
					break;
				case 'email':
					actEmail();
					break;
			}

			notifyWordPress( 'frash_act', btnAct.dataset.msg );
		} );
	}

	/**
	 * Dismiss the notice without any action.
	 */
	if ( btnDismiss ) {
		btnDismiss.addEventListener( 'click', function ( e ) {
			e.preventDefault();
			notifyWordPress( 'frash_dismiss', btnDismiss.dataset.msg );
		} );
	}

	/**
	 * JavaScript implementation similar to a jQuery fadeIn().
	 *
	 * @param {Object} el
	 * @param {number} time
	 */
	function fadeIn( el, time ) {
		el.style.opacity = 0;
		el.style.display = 'table';

		let last = +new Date();
		const tick = function () {
			el.style.opacity = +el.style.opacity + ( new Date() - last ) / time;
			last = +new Date();

			if ( +el.style.opacity < 1 ) {
				( window.requestAnimationFrame &&
					requestAnimationFrame( tick ) ) ||
					setTimeout( tick, 10 );
			}
		};

		tick();
	}

	/**
	 * Hide the notice after a CTA button was clicked.
	 */
	function hideNotice() {
		const tick = function () {
			notice.style.opacity = notice.style.opacity - 0.05;

			if ( +notice.style.opacity > 0 ) {
				( window.requestAnimationFrame &&
					requestAnimationFrame( tick ) ) ||
					setTimeout( tick, 10 );
			} else {
				notice.remove();
			}
		};

		tick();
	}

	window.setTimeout( initialize, 500 );
} );
