/**
 * @author Waylan Wong <waylan.wong@willrainit.com 
 */
var bool_loaded_basecss = false;
var destinationURL = "";
var bool_remainOnSamePage = false;


$(function() {

	///////////////////////////////////////////////////////////////////////////
	/////					Initialization									 //
	///////////////////////////////////////////////////////////////////////////

	init();

});

/**
 * Initialize all triggers and environmental settings.
 */
function init() {

	// add this script's debug stages to the main array for debugging purposes.
	var auth_stages = [ 'authentication' ];
	debug_stages = debug_stages.concat(auth_stages);

	log('authentication', 'init', 'Firing up the initializer.');
	insertCSSintoDOM('css/fms_signup_elements.css');	
	
	/* Reinitialize the auth flag on page load, to deal with users hitting the
	 * back button and accessing the cached version of the authenticated page.
	 */
	document.auth = false;	
	$.get(getBaseURL('/auth_gateway'), function(response) {
		if (response != null) {
			if (response.loggedin && response.completedsignup) {
				document.auth = true;									
			} else if (parseInt($('#modal_container').data('authservice'))===1){
				window.location = getBaseURL("/login");
			}
		}
	}, "JSON");
	

	$('[data-gated="1"]').on('click', function(event) {
		// set the data variable 'login' so that fms_user_signup_portal.js can pick up which modal to show
		$(document).data('login', ($(event.currentTarget).data('linktype')) ? 1 : 0);
		event.preventDefault();
		log("authentication", "init", "Calling auth_processor.");			
		if (!bool_loaded_basecss) {
			$(document).on('css_loaded', function(){
				$(document).off('css_loaded');
				auth_processor();
			});
			insertCSSintoDOM('css/fms_signup_elements.css');
			bool_loaded_basecss = !bool_loaded_basecss;
		} else {
			auth_processor();
		}						
	});

}

/**
 * This function will be called whenever functionality that is accessible only 
 * to registered users is accessed. It will check the backend for a login 
 * response, and then process the response object that is returned.
 * 
 * AJAX GET to backend auth_gateway() function, and then processes the 
 * returned JSON object. 
 * 
 * @see auth_gateway() for the returned data structure.
 * @param options	object containing advanced options for authentication, see 
 * 		function body for the possible options.
 */
function auth_processor(options) {		
	// set the default values for the options
	// by default we will redirect to the profile dashboard
	var myoptions = new Object;
	myoptions.remainOnSamePage = false;
	myoptions.redirectToProfile = true;
	// by default we will conduct a full signup process check.
	myoptions.quickAuthCheck = false;	
	if (typeof options !== "undefined") {		 	
		myoptions.redirectToProfile = (typeof options.redirectToProfile !== "undefined") ? options.redirectToProfile : myoptions.redirectToProfile;
		myoptions.quickAuthCheck = (typeof options.quickAuthCheck !== "undefined") ? options.quickAuthCheck : myoptions.quickAuthCheck;
		bool_remainOnSamePage = (typeof options.remainOnSamePage !== "undefined") ? options.remainOnSamePage : bool_remainOnSamePage;
	}
	
	if (myoptions.quickAuthCheck && document.auth){		
		return true;
	} else {
		var rtp = myoptions.redirectToProfile;
		$.get(getBaseURL('/auth_gateway'), rtp, function(response) {
			if (response != null) {
				if (response.redirecturl) {
					destinationURL = response.redirecturl;
					processSuccessfulAuthentication();
				}
				//log("authentication", "auth_processor", "response: "+ JSON.stringify(response));
				if (!response.completedsignup && !response.auth_error) {
					// user may or may not be logged in, or hasn't completed the signup process.				
					$(document).on('css_loaded', function(){
						$(document).off('css_loaded'); // disable the event
						insertModal(response.modalcontent, response.modalid);
						insertJSintoDOM(response.modaljs);
					});				
					insertCSSintoDOM(response.modalcss);
				} else if (response.loggedin && response.completedsignup) {
					// user has logged in and has completed the signup process.
					document.auth = true;
					log("authentication", "auth_processor", "Well, looks like you have completed the signup procees, welcome to Find My Song!");
					
					if (bool_remainOnSamePage) {
						// after logging in successfully, reload the page and force refetching of
						// content from the server to get all the content only authenticated users can see
						location.reload(true);
					} else if (rtp) {
						window.location = getBaseURL("/hot");
					}
				} else if (response.auth_error) {
					alert("You are not authorized to access this portion of the site!");
				}
			}
		}, "JSON");
		return false;
	}
}

/**
 * This function will append the specified modal to our page, only 
 * if the modal doesn't exist in the DOM. This function will also
 * hide all other modals before showing the specified modal.
 * 
 * @param modalcontent		The HTML markup for the modal
 * @param modalid			The CSS ID selector of the modal to be activated 
 */
function insertModal(modalcontent, modalid) {
	$('.modal').each(function() {
		// hide all the modals that exist, in preparation for the new modal.
		// don't change the display state of the current modalid.
		if ("#"+$(this).attr('id') != modalid){
			$(this).modal('hide');			
		}		
	});
	if ($(modalid).length == 0 ) {				
		$('#modal_container').append(modalcontent);
		log('authentication', 'insertModal', modalid + " Modal content inserted");
	} else {
		log('authentication', 'insertModal', "Duplicate ["+modalid+"] modal detected, aborting modal insert.");
	}
	$(modalid).modal('show');
	/** Waylan :: This code was to deal with the case where the modal would appear on the top of 
	 * the page when the user actually clicked something on the bottom, but right now it seems 
	 * that bootstrap is automatically scrolling all the way to the top for us. This behaviour
	 * is quite abrupt and may need to be changed later.
	 * .on('shown', function() {
		// make sure the modal is visible on the screen, move it down if user has scrolled down.
		var delta_modal_windowTop = $(this).offset().top - $(window).scrollTop();
		if ( delta_modal_windowTop < 0) {
			$(this).css({top: -1 * delta_modal_window + 'px'});
		}
	});
	**/
}


/**
 * This function will append an <a> link that the user can click on to redirect 
 * themself to their original destination. The button text will be changed to
 * mention that they can continue to edit their profile.
 */
function processSuccessfulAuthentication() {
	var htmlstring = '<a id="destURL" href="'+destinationURL+'">Click here to be redirected to your destination.</a>';
	$('#loginhere').parent().append(htmlstring);
	$('#destURL').click(autoRedirect);
	$('#loginhere').text("Click here to continue editing your profile!");	
}

/**
 * This function will immediately redirect the user to the destinationURL.
 */
function autoRedirect() {
	if (destinationURL) {
		window.location = destinationURL;
	}
}