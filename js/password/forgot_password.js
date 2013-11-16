/**
 * @author Hao.Cai
 */
$(document).ready(function(){
	$('#retrieve_password').click(function(){
		var email = $('#email').val();
		if(email == ''){
			$('#error_message').text('Please enter your registered email');
			return;
		}
		ajax_retrievePassword(email);
	});
	$('#reset_password').click(function(){
		if($('#password_reset_form').valid()){
			ajax_resetPassword($("#userid").val(), $("#token").val(), $("#password").val());
		}
	});
	
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
			password: {
				required:	true,
				maxlength:	20,
				minlength:	8,
				hasDigit:	true,
				hasLower:	true,
				hasUpper:	true,
				legalChar:	true
			},
			repassword: {
				required:	true,
				equalTo:	"#password"
			}
		}
	});
});

function ajax_retrievePassword(myemail){
	$.post(getBaseURL("ajax/account/retrievePassword"), {email : myemail}, function(data){
		if(data.errorcode == 0){
			alert(data.message);
			location.assign(getBaseURL());
		}else{
			$('#error_message').text(data.message);
		}
	}, "JSON");
}


function ajax_resetPassword(myid, mytoken, mypassword){
	$.post(getBaseURL("ajax/account/resetPassword"), {id : myid, token : mytoken, password : mypassword}, function(data){
		if(data.errorcode == 0){
			alert(data.message);
			location.assign(getBaseURL());
		}else{
			alert(data.message);
		}
	}, "JSON");
}
