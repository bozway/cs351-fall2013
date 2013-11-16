/* 
 * @author Pankaj K.
 */

$(document).ready(function() {
	jQuery.validator.addMethod("hasDigit", function(value, element,param) {
		var reg = new RegExp("[0-9]");
		if(reg.exec(value) != null) {
			  return true;
		  } else {
			  return false;
		  }		
	}, "Must have at least one digit");
		
	jQuery.validator.addMethod("hasLower", function(value, element,param) {
		var reg = new RegExp("[a-z]");
		if(reg.exec(value) != null) {
			  return true;
		  } else {
			  return false;
		  }		
	}, "Must have at least one lower case letter");
	
	jQuery.validator.addMethod("hasUpper", function(value, element,param) {
		var reg = new RegExp("[A-Z]");
		if(reg.exec(value) != null) {
			  return true;
		  } else {
			  return false;
		  }		
	}, "Must have at least one upper case letter");
	
	jQuery.validator.addMethod("legalChar", function(value, element,param) {
		var reg = new RegExp("^[0-9a-zA-Z]+$");
		if(reg.exec(value) != null) {
			  return true;
		  } else {			  
			  return false;
		  }
	}, "Illegal characters. Only letters and numbers please.");  
	
	$('#password_reset_form').validate({
		rules:{
			new_password: {
				required:	true,
				maxlength:	20,
				minlength:	8,
				hasDigit:	true,
				hasLower:	true,
				hasUpper:	true,
				legalChar:	true
			},
			confirm_new_password: {
				required:	true,
				equalTo:	"#new_password"
			}
		}
	});
	
	$('#email_reset_form').validate({
		rules: {
			new_email: {
				required:	true,
				email:		true
			},
			confirm_new_email: {
				required: true,
				equalTo:	"#new_email"
			}
		}
	});
	
	$('#deactive').click(function() {
	    var r = confirm("Are you sure about deactivating your account permanently ?");
	    if (r === true) {
	        var url = getBaseURL("/ajax/profile/deactivateUserAccount");
	
	        $.post(url)
	                .done(function(data) {
	            if ($.trim(data) === 'true') {
	                alert("Your account has been deactivated permanently!!!");
	                window.location = location.protocol + "//" + location.host;
	            }
	        });
	    }
	});






});

$(document).ready(function(){
	$('#save_email_btn').click(updateEmail);
	$('#save_psw_btn').click(updatePassword);
});

function updateEmail() {
	if($('#email_reset_form').valid()){
		$.ajax({
			url:	location.protocol + "//" + location.host + "/ajax/profile/updateUserLoginCredentials",
			type:	'POST',
			data:	{
				password:	$('#password').val(),
				newEmail:	$('#new_email').val(),
			}
		}).done(function(responseObj){
			if(responseObj.errorcode == 0) {
				$('#original_email').html(responseObj.message);
				alert('Successfully updated your email!');
				location.reload(true);
			} else {
				alert(responseObj.message);
			}
		});
	}
}

function updatePassword() {
	if($('#password_reset_form').valid()){
		$.ajax({
			url:	location.protocol + "//" + location.host + "/ajax/profile/updateUserLoginCredentials",
			type:	'POST',
			data:	{
				password:		$('#confirm_password').val(),
				newPassword:	$('#new_password').val(),
			}
		}).done(function(responseObj){
			if(responseObj.errorcode == 0) {
				alert('Successfully updated your password!');
				location.reload(true);
			} else {
				alert(responseObj.message);
			}
		});
	}
}
