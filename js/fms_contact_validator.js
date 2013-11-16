$(document).ready(function() {
	$('.dropdown-menu span').click(function(event) {
		checkIfSelectorOptionIsValid(event);
	});
	
	$("#contact-fms").validate({
		submitHandler : function(form) {
			if ($('#contact-fms').valid() && checkIfSelectorOptionIsValid()) {
				var placeholdervar = $.ajax({
					url : "send_email",
					type : "POST",
					data : {
						customer_name : $('input[name="customer_name"]', '#contact-fms').val(),
						customer_email : $('input[name="customer_email"]', '#contact-fms').val(),
						customer_subject : $('#customer_subject').val(),
						customer_message : $('#customer_message').val(),
					},
				}).done(function() {
					$('#contact-fms').empty();
					$('#contact-fms').html("<span class=\"thank-customer\">Thank you for contacting us." + 
					" We will get back to you as soon as possible !!!</span>");
				});
			}
		},
		rules : {
			customer_name : {
				required : ($('input[name="customer_name"]', '#contact-fms').length > 0),
				minlength : 5,
				maxlength : 64
			}, 
			customer_email : {
				required : ($('input[name="customer_email"]', '#contact-fms').length > 0),
				email : true
			},
			customer_message : {
				required : true,
				minlength : 20,
				maxlength : 1000
			}, 
			customer_subject : {
				required : true,
			}			
		},
		messages : {
			customer_name : {
				required : "Please enter your name.",
				minlength : "Please enter your full name.",
				maxlength : "Please use less than 64 characters."
			},
			customer_email : {
				required : "Please enter your email address.",
				email : "Please enter a valid email address."
			},
			customer_message : {
				required : "Please tell us how we can help you.",
				minlength : "Please make your message greater than 20 characters.",
				maxlength : "Please make your message smaller than 1000 characters."
			},
			customer_subject : {
				required : "Please choose a subject.",
			}
		}
	});

	$('#contact-fms').submit(function() {
		if (checkIfSelectorOptionIsValid() && $('#contact-fms').valid()) {
			$('.contact-fms').html('<h3 class="successful_contact_submission">Thanks for contacting us! We\'ll get back to you as soon as we can!</h3>');
		} else {
			alert("Please correct the issues in red before submitting.");
			return false;
		}
	});

});

/**
 * @author Waylan Wong <waylan.wong@willrainit.com>
 * 
 * @param bool_show True if you want to add the error class to the selector dropdown. 
 */
function showSelectorError(bool_show) {
	if (bool_show) {
		if (!$('#subject-selector-wrapper').hasClass('error')) {
			$('#subject-selector-wrapper').addClass('error');
		} 
	} else {
		$('#subject-selector-wrapper').removeClass('error');
	}	
}
/**
 * @author Waylan Wong <waylan.wong@willrainit.com>
 * 
 * This function will return a boolean telling us if the user has selected a non-default option.
 * @param eventobj The event object 
 */
function checkIfSelectorOptionIsValid(eventobj) {
	var optionText = (typeof eventobj !== "undefined") ? $(eventobj.target).html() : $('#customer_subject').val();  
	
	if (optionText != "What are you inquiring about?") {
		showSelectorError(false);
	} else {
		showSelectorError(true);
	}
	return ($('#customer_subject').val() != "What are you inquiring about?");
}