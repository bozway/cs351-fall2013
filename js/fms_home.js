var imageActive = 1;
var trackTimer = -1;
var SLIDER_INTERVAL = 8000;

$(function() {
	
	/** Silly way of removing the left margin from all the featured projects and artists **/
	var musicians_num = $('.featured_musicians').length;
	var div_index='';
	for($i=1;$i<=musicians_num/3;$i++){
		div_index=3*$i-1;
		$('.featured_musicians:eq('+div_index+')').addClass('no-margin-right');
	}
	
	var projects_num = $('.featured_projects').length;
	var project_div_index='';
	for($i=1;$i<=projects_num/2;$i++){
		project_div_index=2*$i-1;
		$('.featured_projects:eq('+project_div_index+')').addClass('no-margin-right');
	}
	
	$('.slider-link').click(function() {
		changeBgImage($(this).data('id'));
		imageActive = $(this).data('id');
		clearInterval(trackTimer);
		trackTimer = setInterval(changeBgImage, SLIDER_INTERVAL);
	});
	
	
	
	// Initialize the first slider image.
	$('#home_header_top [data-id="1"]').toggleClass('show');
	$('.home_bg_images_bg').css("background","#255073");
	// Start up the slide show.
	trackTimer = setInterval(changeBgImage, SLIDER_INTERVAL);
});



/**
 * @author Pankaj Kumar 
 * @author Waylan Wong
 * This function will change the slider image as well as teh 
 * @param manualChange ID of the slider image to show.
 */
function changeBgImage(manualChange){
	 
	++imageActive;
	if(imageActive === 5){
		imageActive = 1;		
	}
	var nextimage = (typeof manualChange !== "undefined") ? manualChange : imageActive;
	
	switch(nextimage){
		case 1:
			$('.home_bg_images_bg').css("background","#255073");
			break;
		case 2:
			$('.home_bg_images_bg').css("background","#69583e");
			break;
		case 3:
			$('.home_bg_images_bg').css("background","#23758d");
			break;
		case 4:
			$('.home_bg_images_bg').css("background","#72c7a8");
			break;
	}
	
	$('#home_header_top .show').toggleClass('show');
	$('#home_header_top [data-id="'+nextimage+'"]').toggleClass('show');
}



/*********************
 * related to youtube video - disabled for now
 *  *****/
 	
// var player;
// var VIDEO_ID			=	'_OBlgSz8sSM';	// unique youtube video id
// var RELATED_VIDEOS		=	0;				// no related videos
// var VIDEO_ANNOTATIONS	=	3; 				// no video annotations 
// var CONTROLS			=	0;				// no progress bar & other youtube settings
// var AUTOPLAY			=	0;				// no autoplay
// var HD					=	1; 				// by deafault HD quality video will be shown, if it available
// 
// 
// function onYouTubeIframeAPIReady() {
    // player = new YT.Player('fms-home-video-container', {
      // height: '500',
      // width: '770',
      // playerVars: {
      	// 'rel'				:  RELATED_VIDEOS,
      	// 'iv_load_policy'	:  VIDEO_ANNOTATIONS,
      	// 'controls'			:  CONTROLS,
      	// 'autoplay'			:  AUTOPLAY,
      	// 'hd'				:  HD	
  	  // },
  	  // videoId	:   VIDEO_ID,
      // events: {
        // 'onStateChange'		:  onPlayerStateChange
      // }
	// });
// }
// 
// function onPlayerStateChange(event){ 
	// if(player.getPlayerState() !== YT.PlayerState.PAUSED){
		// document.onclick=pauseVideo;
	// }
// }
// 
// function pauseVideo(){
	// player.pauseVideo();	
// }
