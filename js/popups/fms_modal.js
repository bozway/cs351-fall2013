//the user being messaged or invited
var targetUser_obj;
//the active or recruiting project of current user
var list_myProject;
//flags to make sure the ajax-responsed data is ready
var userdata_ready = false;
var myprojects_ready = false;
//indicator of which modal is under use 0=>none, 1=>audition, 2=>invitation, 3=>message
var currentModal = 0;

$(function() {
	$('[data-btn="aud"]').click(auditionPopup);
	$('[data-btn="inv"]').click(invitePopup);
	$('[data-btn="msg"]').click(msgPopup);
	$('[data-btn="con"]').click(conPopup);

	$('#sendMessage').click(ajax_sendMsg);
	$('#sendAudition').click(ajax_sendAudition);
	$('#sendInvitation').click(ajax_sendInvitation);

	$("#inviteModal").on('hidden', function() {
		userdata_ready = false;
		myprojects_ready = false;
	});
});

function hideAllModals() {
	$(".modal").each(function() {
		$(this).modal('hide');
	});
}

//Audition Popup
function auditionPopup() {
	// $(this) is in the context of the button that was just clicked.
	if (auth_processor({quickAuthCheck : true, remainOnSamePage : true})) {	
		var projectid = $(this).data('projectid');
		if ( typeof projectid == 'undefined') {
			alert('data-projectid is undefined!');
			return;
		}
		currentModal = 1;
		$("#auditionModal").data('projectid', projectid);
		ajax_getAuditionModalData(projectid);
	}
}

function showAuditionPopup(data) {
	hideAllModals();
	var modal_header = $("#auditionModal .modal-header span").text(data.project_name);
	var skill_select = $("#auditionModal").find('select[id="skill_audition"]').first();
	skill_select.empty();
	for (i in data.project_skill_data) {
		var htmlstring = '<option value="' + data.project_skill_data[i].projectskillid + '">' + data.project_skill_data[i].projectskillname + '</option>';
		skill_select.append(htmlstring);
	}
	
	// Wei: Added onclick listener for each of the FLAT-UI dropdown option
	$('#audition_skill_selection li').click(function(){
		var optionIndex = $(this).attr('rel');
		var selectedSkillId = $($(skill_select).children()[optionIndex]).val();
		$('#audition_skill_selection').data('skillid', selectedSkillId); 
	});
	
	$("#auditionModal").modal('show');
}

//Invitation Popup
function invitePopup() {
	if (auth_processor({ quickAuthCheck : true, remainOnSamePage : true})) {
		// $(this) is in the context of the button that was just clicked.
		var userid = $(this).data('userid');
		if ( typeof userid == "undefined") {
			alert('data-userid is undefined!');
			return;
		}
		currentModal = 2;
		$("#inviteModal").data('userid', userid);
		ajax_getInvitationModalData(userid);
	}
}

function showInvitePopup(data) {
	hideAllModals();
	//set invited user data
	$("#inviteModal").find("#musicianName").text(data.username);
	var content = "Dear " + data.username + ", I would like to personally invite you to my project! I think you will be a good fit let me know if you have any questions at all!";
	$("#inviteModal").find("#message textarea").val(content);
	$("#inviteModal").find("#profilePicture").attr('src', data.userimg);
	//set my projects
	var project_select = $("#inviteModal").find('select[id="project_invitation"]').first();
	project_select.empty();
	for (i in data.project_data) {
		var htmlstring = '<option value="' + data.project_data[i].projectid + '">' + data.project_data[i].projectname + '</option>';
		project_select.append(htmlstring);
	}
	$("#inviteModal").modal('show');
}

//Message Popup
function msgPopup() {
	if (auth_processor({quickAuthCheck : true,remainOnSamePage : true})) {
		// $(this) is in the context of the button that was just clicked.
		var userid = $(this).data('userid');
		if ( typeof userid === 'undefined') {
			alert('data-userid is undefined!');
			return;
		}
		currentModal = 3;
		$("#messageModal").data('userid', userid);
		ajax_getMessageModalData(userid);
	}
}

function showMsgPopup(data) {
	hideAllModals();
	$("#messageModal").find("#musicianName").text(data.username);
	$("#messageModal").find("#message textarea").val("Dear " + data.username + ":");
	$("#messageModal").find("#profilePicture").attr('src', data.userimg);
	$("#messageModal").modal('show');
}

function conPopup() {
	if (auth_processor({quickAuthCheck : true,remainOnSamePage : true})) {
		// $(this) is in the context of the button that was just clicked.
		var contact = $(this).data('userid');
		var add_url = location.protocol + '//' + location.hostname + "/ajax/profile/addContact";
		var delete_url = location.protocol + '//' + location.hostname + "/ajax/message/deleteContact";

		if ($('[data-btn="con"][data-userid="' + contact + '"]').attr('data-toggle') === '1') {
			$.post(add_url, {
				'contactId' : contact
			}).done(function(data) {
				if (data === 'true') {
					$('[data-btn="con"][data-userid="' + contact + '"]').attr('data-toggle', '0');
					$('[data-btn="con"][data-userid="' + contact + '"]').text('Unsave Contact');
					$('[data-btn="con"][data-userid="' + contact + '"]').removeClass('btn-success');
				}
			});
		} else if ($('[data-btn="con"][data-userid="' + contact + '"]').attr('data-toggle') === '0') {
			$.post(delete_url, {
				'contactId' : contact
			}).done(function(data) {
				if (data === 'true') {
					$('[data-btn="con"][data-userid="' + contact + '"]').attr('data-toggle', '1');
					$('[data-btn="con"][data-userid="' + contact + '"]').text('Save Contact');
					$('[data-btn="con"][data-userid="' + contact + '"]').addClass('btn-success');
				}
			});
		}
	}
}

function ajax_getAuditionModalData(id) {
	$.get(getBaseURL("/ajax/message/getAuditionModalData"), {
		project_id 	: id
	}, function(data) {
		showAuditionPopup(data);
	}, "JSON");
}

function ajax_getInvitationModalData(id) {
	$.get(getBaseURL("/ajax/message/getInvitationModalData"), {
		user_id : id
	}, function(data) {
		showInvitePopup(data);
	}, "JSON");
}

function ajax_getMessageModalData(id){
	$.get(getBaseURL("/ajax/message/getMessageModalData"), {
		user_id : id
	}, function(data) {
		showMsgPopup(data);
	}, "JSON");
}

function ajax_sendMsg() {
	var user_id = $("#messageModal").data('userid');
	var content = $("#messageModal").find("#message textarea").val();
	if(content == ""){
		alert("Message can not be empty!");
		return;	
	}
	$.post(getBaseURL("/ajax/message/messageUser"), {
		user_id : user_id,
		content : content
	}, function(data) {
		if (data.errorcode != 0) {
			alert(data.message);
		}
		hideAllModals();
	}, "JSON");
}

function ajax_sendAudition() {
	var projectskill_id = $("#skill_audition").val();
	var project_id = $("#auditionModal").data('projectid');
	if(!projectskill_id){
		alert("Please select a project skill to audition");
		return;
	}
	$.post(getBaseURL("/ajax/message/auditionProject"), {
		projectskill_id : projectskill_id
	}, function(data) {
		if (data.errorcode != 0) {
			alert(data.message);
		}
		else{
			$('[data-btn="aud"][data-projectid=' + project_id + ']').unbind('click')
																	.removeAttr('data-btn')
																	.removeClass('btn-success')
																	.addClass('disabled');	
		}
		hideAllModals();
	}, "JSON");
}

function ajax_sendInvitation() {
	var user_id = $("#inviteModal").data('userid');
	var project_id = $("#project_invitation").val();
	if(!project_id){
		alert("Please select a project");
		return;
	}
	var content = $("#inviteModal").find("#message textarea").val();
	if(content == ""){
		alert("Invitation message can not be empty!");
		return;
	}
	var url = getBaseURL("/projects/profile/" + project_id);
	content += '<br>Click <a href="' + url + '">here</a> to access the project';
	$.post(getBaseURL("/ajax/message/inviteUser"), {
		user_id : user_id,
		content : content
	}, function(data) {
		if (data.errorcode != 0) {
			alert(data.message);
		}
		hideAllModals();
	}, "JSON");
}
