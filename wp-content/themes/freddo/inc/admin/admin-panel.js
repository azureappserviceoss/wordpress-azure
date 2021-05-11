(function($) {
	"use strict";
	$(document).ready(function() {
		$('.toggleTitle').click(function(){
			$(this).next().slideToggle(200).parent().toggleClass('active');
			return false;
		});
		if(document.location.hash) {
			$('#' + document.location.hash.substring(1)).children().children('.toggleText').slideToggle(200).parent().toggleClass('active');
		}
	});
})(jQuery);