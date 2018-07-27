function test_video_thumbnail( test_type ) {
	var data = {
		action: 'video_thumbnail_' + test_type + '_test'
	};
	document.getElementById( test_type + '-test' ).innerHTML = 'Working...';
	jQuery.post(ajaxurl, data, function(response){
		document.getElementById( test_type + '-test' ).innerHTML = response;
	});
};

function test_video_thumbnail_markup_detection() {
	var data = {
		action: 'video_thumbnail_markup_detection_test',
		markup: jQuery('#markup-input').val()
	};
	document.getElementById( 'markup-test-result' ).innerHTML = '<p>Working...</p>';
	jQuery.post(ajaxurl, data, function(response){
		document.getElementById( 'markup-test-result' ).innerHTML = response;
	});
}