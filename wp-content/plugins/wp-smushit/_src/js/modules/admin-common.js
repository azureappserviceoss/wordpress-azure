jQuery(function ($) {
	'use strict';

    /**
	 * Handle the Smush Stats link click
	 */
    $('body').on('click', 'a.smush-stats-details', function (e) {
        //If disabled
        if ($(this).prop('disabled')) {
            return false;
        }

        // prevent the default action
        e.preventDefault();
        //Replace the `+` with a `-`
        const slide_symbol = $(this).find('.stats-toggle');
        $(this).parents().eq(1).find('.smush-stats-wrapper').slideToggle();
        slide_symbol.text(slide_symbol.text() == '+' ? '-' : '+');
    });
});