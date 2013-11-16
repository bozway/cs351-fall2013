/**
 * @author Wei
 * Initialization steps for signup profile page
 * This steps initialize the google API, jquery file upload plugin
 * 
 * Things to do next: popovers
 */
// form validation
profileFormValidation();

// File Uploading Module
var param = [];
param['option'] = 'user_image';
param['cropratio'] = 1/1;
imgUploaderInit($('#signup_image_uploader'), param);
audioUploaderInit($('#signup_audio_uploader'), {'option': 'user','project_id':'undefined'});

$('#country_options li').click(function(){
	$('#country').text($(this).children().children().text());
});
$('#gender_options li').click(function(){
	$('#gender').text($(this).children().children().text());
});

googleLocationInit($('input[name="city"]'));

// Dropdowns
dropdownMenuInit_v2($('#usstate_dropdown'));
dropdownMenuInit_v2($('#gender_dropdown'));

// Popovers
$('#help_privacy').popover();
$('#help_upload').popover();
$('#help_privacy_button').click(function () {
	document.getElementById('help_privacy').click();
});	
$('#help_upload_button').click(function () {
	document.getElementById('help_upload').click();
});

// Privacy
var privacy = "public";
$('#public_profile').click(function () {
	$('#public_profile').attr("class", "radio checked");
	$('#private_profile').attr("class", "radio");
	privacy = "public";
});
$('#private_profile').click(function () {
	$('#public_profile').attr("class", "radio");
	$('#private_profile').attr("class", "radio checked");
	privacy = "private";
});

// Back and Skip buttons
$('#skip').click(function() {
	$.post(
		getBaseURL("/skipProfileStep"),
		function(data) {
			log("signup", "skipstep", JSON.stringify(data));
			auth_processor();
		}
	);
});

$('#back').click(function() {
	$.post(
		getBaseURL("/returnToPreviousStep"),
		function(data) {
			auth_processor();
		}
	);
});



/**
 * Signup Form Validation
 * 
 * The function should be called on page load to initialize the
 * JQUERY form validation plugin. The submit handler will Ajax the backend with user profile.
 * And it will make a separate Ajax to the backend with the ranking of the spotligth.
 */
function profileFormValidation() {
	jQuery.validator.addMethod("legalChar", function(value, element,param) {
		var reg = new RegExp("^[0-9a-zA-Z ]+$");
		if(reg.exec(value) != null) {
			  return true;
		  } else {
			  return false;
		  }
	}, "Illegal characters");
	
	
	jQuery.validator.addMethod("validCity", function(value, element,param) {
    	if(value == '') return true;
		var reg = new RegExp("^[a-zA-Z ]+$");
		if(reg.exec(value) != null) {
			  return true;
		  } else {			  
			  return false;
		  }
	}, "Please enter valid city name");	
	
	$("#fms_profile_form").validate({
        focusInvalid: false,
        ignoreTitle: true,
		submitHandler : function(form) {
			if ($('#fms_profile_form').valid()) {
				// Ajax to submit the form
				$.ajax({
        			url		:	getBaseURL("/ajax/profile/updateUserProfile"),
        			type	:	"POST",
        			data	: 	{
        				city:	$('#city').val(),
        				//country:$('.signup_country .filter-option').text(),
        				country: "UNITED STATES",
        				state:	$('#usstate').text(),
        				gender: $('#gender').text(),
        				audioranking: getAudioRanking($('#signup_audio_uploader')),
        			},        			
        		}).done(function(responseObj) {
        			/*
        			 * !!! WHERE IS THE ERROR CHECKING IN THIS AJAX HANDLER !!!
        			 */
        			if(responseObj.errorcode == 0) {
        				window.location = getBaseURL("/hot");
        			} else {
        				alert('There was an error in processing your submission, please reload the page and try again.');
        			}
        		});        		
        	}
        },
		rules:{
			city: {
				validCity:	true,
				maxlength:	20,
			}
		},
		message: {},
	});
}

