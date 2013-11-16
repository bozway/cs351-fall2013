$(function() {
	
	//////////////////////////////////
	/////		  EVENTS		 /////
	//////////////////////////////////
	 
	
	$('.button').on('click', function(e) {
		getMixpanelCallback(
			"Clicked Button", 
			{"Button Name" : $(this).attr('id') }
		);
	});
	
});
