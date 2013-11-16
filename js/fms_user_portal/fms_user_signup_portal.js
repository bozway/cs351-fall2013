/**
 * @author Wei
 * Initialization Scripts for singup page
 * This part initialized all the popovers, click button, and form validation
 */

// add this script's debug stages to the main array for debugging purposes.
var auth_stages = [ 'signup' ];
debug_stages = debug_stages.concat(auth_stages);

$("#help_email").popover();
$("#help_name").popover();
$('#help_psw').popover();
$('#help_login').popover({
	html:	true,
	content:	"<div class='login_error_sign'></div><div class='login_error_message'><p class='login_error_title'>Incorrect Email or Password</p><p class='login_error_body'>Please try again</p></div>"
});
var termslink = getBaseURL("/terms");
$("#help_term").popover({
	html:	true,
	content:	'By checking the box and clicking "Join", I understand that I am joining FindMySong, and I have read and accepted the <a href="'+termslink+'">Terms of Use</a> and consent to the <a href="'+getBaseURL('privacy')+'">Privacy Policy</a>'
});
$("#help_term").popover('show');

signupFormValidation();	
$("#checkbox").click(function () {
	$(this).toggleClass("checked");
	if($("#term_agree").attr('checked'))
		$("#term_agree").attr('checked',false);
	else
		$("#term_agree").attr('checked',true);
});

/**
 * @authro Wei
 * Initialization Scripts for signin page
 * This part initialize all the click button, and form validation
 */
$('#anchor_login').click(function (){
	$('#signupModal').addClass("login");
	$('#signup_modal_body').hide();
	$('#signin_modal_body').show();
	$('#anchor_login').addClass("active");
	$('#anchor_signup').removeClass("active");	
});

$('#anchor_signup').click(function (){
	$('#signupModal').removeClass("login");
	$('#signin_modal_body').hide();
	$('#signup_modal_body').show();
	$('#anchor_login').removeClass("active");
	$('#anchor_signup').addClass("active");	
});

signinFormValidation();

log('fms_user_signup', 'init', 'Binding the modal show behaviour');
$('#signupModal').on('show', function(){
	// the data variable is set in fms_auth when a data-gated link is clicked.
	var modaltype = $(document).data('login');
	if (modaltype === 1) {
		console.log("firing the #anchor_login click event");
		$('#anchor_login').click();
	} else if (modaltype === 0){
		console.log("firing the #anchor_signup click event");	
		$('#anchor_signup').click();
	}
	$(document).data('login', -1);
});

$('#signupModal').trigger('show');


/**
 * Signup Form Validation
 * The function should be binded to the click event of form submit button
 * to varify the format of the input are legal
 */
function signinFormValidation() {	
	$("#fms_signin_form").validate({
        focusInvalid: false,
        ignoreTitle: true,
		submitHandler : function(form) {       	
        	
        	if ($('#fms_signin_form').valid()) {
        		
        		$.ajax({
        			//POST the form data to the backend
        			url		:	getBaseURL("/processCredentials"),
        			type	:	"POST",
        			data	: 	{
        				password:	$('#user_password_login').val(),
        				email:		$('#user_email_login').val(),
        			},        			
        		}).done(function(responseObj) {
        			// check if backend reported any errors.
        			// NOTE: 
        			if (parseInt(responseObj.status) == 1) {
        				// no errors, call auth_processor to move to next stage of signup.
        				auth_processor(); 
        			} else {
        				//log("signup", "submitHandler", "There was an error in the submission, error code is: "+ responseObj.error);
        				$('#help_login').popover('show');
        			}
        		});
        	}
        },
		rules:{
			user_email_login:	{
				required:	true,
				email:		true
			},
			user_password_login:	{
				required:	true,
			}
		},
		message: {
			user_email:	{
				required:	"Please enter a valid email",
				email:		"Email not valid",
			},
			user_password: {
				required:	"Please enter your password",
				rangelength:"Password should be 6-20 characters",
			}
		}
	});
}

/**
 * Signup Form Validation
 * The function should be binded to the click event of form submit button
 * to varify the format of the input are legal
 */
function signupFormValidation() {
	jQuery.validator.addMethod("alpha", function(value, element,param) {
		var reg = new RegExp("^[a-zA-Z]+$");
		if(reg.exec(value) != null) {
			  return true;
		  } else {
			  return false;
		  }		
	}, "Please enter only letters");
	
	jQuery.validator.addMethod("hasDigit", function(value, element,param) {
		var reg = new RegExp("[0-9]");
		if(reg.exec(value) != null) {
			  return true;
		  } else {
			  return false;
		  }		
	}, "Must have a digit");
		
	jQuery.validator.addMethod("hasLower", function(value, element,param) {
		var reg = new RegExp("[a-z]");
		if(reg.exec(value) != null) {
			  return true;
		  } else {
			  return false;
		  }		
	}, "Must have a lower case letter");
	
	jQuery.validator.addMethod("hasUpper", function(value, element,param) {
		var reg = new RegExp("[A-Z]");
		if(reg.exec(value) != null) {
			  return true;
		  } else {
			  return false;
		  }		
	}, "Must have a upper case letter");
	
	jQuery.validator.addMethod("legalChar", function(value, element,param) {
		var reg = new RegExp("^[0-9a-zA-Z]+$");
		if(reg.exec(value) != null) {
			  return true;
		  } else {			  
			  return false;
		  }
	}, "Illegal characters");	
	
	$("#fms_signup_form").validate({
        focusInvalid: false,
        ignoreTitle: true,
		submitHandler : function(form) {       	

			var classname = $("#checkbox").attr("class");
			if(classname === "fms_checkbox unchecked") {
				alert("You must agree to the terms and conditions.");
			  	return false;
			} else {				
	        	if ($('#fms_signup_form').valid()) {
	        		
	        		$.ajax({
	        			//POST the form data to the backend
	        			url		:	getBaseURL("/signup"),
	        			type	:	"POST",
	        			data	: 	{
	        				name_first:	$('#user_namefirst').val(),
	        				name_last:	$('#user_namelast').val(),
	        				email:		$('#user_email').val(),
	        				password:	$('#user_password').val(),
	        				invitation_code:	$('[name="invitation_code"]').val(),
	        			},        			
	        		}).done(function(responseObj) {
	        			// check if backend reported any errors.
	        			// NOTE: 
	        			if (parseInt(responseObj.signup_success) == 1) {
	        				// no errors, call auth_processor to move to next stage of signup.
	        				auth_processor(); 
	        			} else {
	        				if(parseInt(responseObj.signup_success) == -1) {
	        					alert(responseObj.error);
	        					return;
	        				}	        				
	        				
	        				log("signup", "submitHandler", "There was an error in the submission, error code is: "+ responseObj.error);
	        				if(responseObj.error === "Email taken") {
	        					$("#signup_email_error").text("Account exists with this email!");
	        					$("#signup_email_error").show();
	        				}
	        			}
	        		});        		
	        	}
			}
        },
		rules:{
			user_namefirst: {
				required: 	true,
				alpha:		true,
				maxlength:	16
			},
			user_namelast:	{
				required:	true,
				alpha:		true,
				maxlength:	16
			},
			user_email:	{
				required:	true,
				email:		true
			},
			user_email_repeat:	{
				equalTo:	"#user_email"
			},
			user_password:	{
				required:	true,
				maxlength:	20,
				minlength:	8,
				hasDigit:	true,
				hasLower:	true,
				hasUpper:	true,
				legalChar:	true
			},
			user_password_repeat: {
				required:	true,
				equalTo:	"#user_password"
			},
			term_agree: {
				agreed:		true,
				required:	true
			},
			invitation_code: {
				//required:	true,
			}
		},
		messages : {
			user_email :	{
				required :	"Please enter a valid email",
				email :		"Please enter a valid email"
			}
		},
		showErrors: function(errorMap, errorList){
			curlist = this.currentElements;
			for(var i = 0; i < curlist.length; i++) {
				var cur = curlist[i];
				var popover;
				
				// Locate the popover
				skip = 0;
				name = 0;
				switch(cur.name) {
					case "user_password_repeat":
						popover = $("#help_psw");
						break;
					case "user_namefirst":
					case "user_namelast":
						name = 1;
						popover = $("#help_name");
						break;
					case "user_email_repeat":
						popover = $("#help_email");
						break;
					default:
						skip = 1;
						this.defaultShowErrors();
				};
				if(skip == 0) {
				
					// Search for errors
					var error = 0;
					for(var j=0; j < errorList.length; j++) {
						if(errorList[j].element.name === cur.name) {
							error = 1;
							break;
						}
						if(name == 1) {
							if(errorList[j].element.name === "user_namefirst" || errorList[j].element.name === "user_namelast") {
								error = 1;
								break;
							}
						}
					}
					
					// Display or hide errors
					if(error == 0)
						popover.popover('hide');
					else
						popover.popover('show');
				}
			}
		}
	});
}

function signup(id,accessToken,expireIn) {
    var baseurl = "http://graph.facebook.com/";
    var endurl  = "/picture?type=large";
    console.log('Welcome!  Fetching your Facebook information.... ');
    FB.api('/me', function(res) {
        var lastName  = res.last_name;
        var firstName = res.first_name;
        var gender    = 0;
        var email     = res.email;
        switch (res.gender){
            case "female":
            gender = 2;
            break;
            case "male":
            gender = 1;
            break;            
        }
    
    $.post(
        getBaseURL("/facebookSignup"),
       {
           facebookUserid :  id,
           token          :  accessToken,
           name_last      :  lastName,
           name_first     :  firstName,
           email          :  email,
           password       :  "1",
           expire         :  expireIn            
       }
    )
    .done(function(data) {
    	setTimeout(function(){
    		auth_processor();
    	},1000);
        location.reload(true);
        //alert("facebook signup success!");
    });    
    
    
    });
 };

$('#login_facebook').click(function(event){

    event.preventDefault();
    //var invitation_code = $('input[name="invitation_code"]').val();
    var invitation_code = "USC2013";
    if(invitation_code == ""){
        alert("Invitation code needed!");
        return;
    }
    ajax_checkInvitationCode(invitation_code);
});
$('#signup_facebook').click(function(event){

	event.preventDefault();

	//var invitation_code = $('input[name="invitation_code"]').val();
	var invitation_code = "USC2013";
	if(invitation_code == ""){
		alert("Invitation code needed!");
		return;
	}
 	ajax_checkInvitationCode(invitation_code);
});

$('#signup_twitter').click(function(){
   window.h=auth_processor;
    var url = getBaseURL('/twitterSignup/0');    
    w=window.open(url,'Twitter Authorize','height=400,width=400'); 
    if (window.focus) {w.focus()}    
   return false; 
});

function signUp_facebook(){
	FB.login(function(res){
	    if(res.status==='connected'){
	        var uid = res.authResponse.userID;
	        var accessToken = res.authResponse.accessToken;
	        var expire = res.authResponse.expiresIn;
	        signup(uid,accessToken,expire);
	    }
	},{scope: 'email'});
}

function ajax_checkInvitationCode(invitation_code){
	$.get(getBaseURL('/checkInvitationCode'), {code : invitation_code}, function(data){
		if(data == 1){
		    //alert("Your invitation code is incorrect!");
			signUp_facebook();
		}
		else{
			alert("Your invitation code is incorrect!");
		}
	});
}

