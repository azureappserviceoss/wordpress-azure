function clear_all_video_thumbnails( nonce ) {
	var confimation_result = confirm("Are you sure you want to clear all video thumbnails? This cannot be undone.");
	if (confimation_result){
		var data = {
			action: 'clear_all_video_thumbnails',
			nonce: nonce
		};
		document.getElementById( 'clear-all-video-thumbnails-result' ).innerHTML = '<p>Working...</p>';
		jQuery.post(ajaxurl, data, function(response){
			document.getElementById( 'clear-all-video-thumbnails-result' ).innerHTML = response;
		});
	}
	else{
		//
	}
};