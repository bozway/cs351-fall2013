/**
 * ajax_getAllContacts()
 * 
 * This function retrieve all the contact of current user and get the current user data by the way 
 */
function ajax_getAllContacts(){
	$.get(location.protocol + "//" + location.hostname
			+ "/ajax/message/getMyContacts", {}, function(data) {
		list_contact_data = [];
		list_contact_name = [];
		if (data.length > 0) {
			list_contact_name = data[0];
			list_contact_data = data[1];
			currentUserId = data[2].id;
			currentUserName = data[2].name;
			$(document).trigger('ajax_getAllContacts_post');
		}
	}, "JSON").fail(function(){
		setTimeout(function(){
			ajax_getAllContacts();
		}, 500, self);
	});;
}

/**
 * ajax_getAllThreads() 
 *
 * This function retrieve all the threads belonging to current user.
 */
function ajax_getAllThreads(){
	$.get(location.protocol + "//" + location.hostname
			+ "/ajax/message/getMyThreads", {}, function(data) {
		list_thread_data = [];
		if (data.errorcode == 0) {
			list_thread_data = data.threads;
			$(document).trigger('ajax_getAllThreads_post');
		}
	}, "JSON").fail(function(){
		setTimeout(function(){
			ajax_getAllThreads();
		}, 500, self);
	});
}

/**
 * ajax_getMessagesAndContactsByThreadId(thr_id) 
 *
 */
function ajax_getMessagesAndContactsByThreadId(thr_id){	
	$.get(getBaseURL("/ajax/message/getMessagesAndContactsByThread"), {thread_id:thr_id}, function(data) {
		list_message_data = [];
		list_participant_name = [];
		if (data.length > 0) {
			if(thr_id == currentActiveThreadId){
				list_message_data = data[0];
				list_participant_name = data[1].names;
				list_participant_id = data[1].ids;
				$(document).trigger('ajax_getMessagesAndContactsByThread_post');
			}
		}
	}, "JSON");
}

function ajax_createMessage(content){
	$(new_message_btn).addClass('disabled');
	$.post(location.protocol + "//" + location.hostname
			+ "/ajax/message/addMessageToThread", {thread_id: currentActiveThreadId, participants: list_participant_id, content: content}, function(data) {
		if(data.errorcode == 0){
			if(data.is_new){
				list_thread_data.push(data.thread);
				processNewThread(list_thread_data.length-1);
				//mark it selected
				var currentActive = $('.thread[data-id="' + data.thread.id + '"]');
				currentActive.toggleClass("selected");
			}
			else{
				if(data.thread.id != currentActiveThreadId){
					alert("No in the correct conversation");
					return;
				}
			}
			list_message_data.push(data.message);
			processNewMessage(list_message_data.length-1);
			$(new_message_input).val('');
			scrollToBottom();
		}
		else{
			alert(data.message);
		}

	}, "JSON")
	.always(function(){
		$(new_message_btn).removeClass('disabled');
	});
}
  
