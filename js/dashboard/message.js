/**
 * Initialization
 * @author Hao Cai
 */

/////////////////////////////////////////
/////		Defined Object 			/////
/////////////////////////////////////////
function MessageObj(msg_id, msg_img, msg_sender, msg_senderid, msg_content, msg_time, msg_date){
	this.id = msg_id;
	this.img = msg_img;
	this.sender = msg_sender;
	this.senderid = msg_senderid;
	this.content = msg_content;
	this.time = msg_time;
	this.date = msg_date;
}
function TheadObj(thr_id, thr_img, thr_title, thr_prev, thr_date, thr_readFlag){
	this.id = thr_id;
	this.img = thr_img;
	this.title = thr_title;
	this.preview = thr_prev;
	this.date = thr_date;
	this.is_read = thr_readFlag;
}

/////////////////////////////////////////
/////		APPLET VARIABLES 		/////
/////////////////////////////////////////
/*array of current message objs*/
var list_message_data;
/*array of thread objs*/
var list_thread_data;
/*arrays of current participants*/
var list_participant_id = [];
var list_participant_name = [];
/*current active thread ID*/
var currentActiveThreadId;
/*current message date, in order to make the date seperator*/
var currentMsgDate;
/*Curent user data*/
var currentUserId;
var currentUserName;
/*array of contacts*/
var list_contact_data;
var list_contact_name;
//prevent duplicate input
var last_userinput = '';

/*Reference to the Elements*/
var messages_container = '.chat-history-container';
var threads_container = '.tab-content';
var conversation_contact_list = '.conv-contact-list';
var conversation_contact_input = '#conv_contact_input';
var new_thread_btn = '#new_thread';
var new_message_btn = '#new_message_btn';
var new_message_input = "#new_message_input";
var thread_title_maxLength = 30;
var thread_left_title_maxLength = 23;
var contact_search_input = '#contact_search';

var message_container_top;
var message_container_min_height = 180;
var message_container_height;
var thread_container_height;
var magic_adjustment = 40;  //because of the message-container-row bottom-margin(20px), absolute lables at the bottom(10px) and unknown 10px
var slimfooter_adjustment = 58; // because we need to have the footer in view so the page doens't scroll.
$(function() {
	message_container_top = $(".message-container > .message-container-row").offset().top;
	setHeight();
	$(threads_container).slimScroll({
		height: thread_container_height + 'px'
	});
	$(messages_container).slimScroll({
		height: message_container_height + 'px',
		start : 'bottom'
	});
	$('.empty-chat-history img').css({'margin-top' : (message_container_height - 105)/2 + 'px'});
	
	$(new_thread_btn).click(function(){
		clearConversation();
		list_participant_id = [currentUserId];
		list_participant_name = [currentUserName]; 
		list_message_data = [];
		$(document).trigger('ajax_getMessagesAndContactsByThread_post');
	});
	$(new_message_input).keypress(function(event){
		if(event.which == 13 && $("#press_enter_checkbox").is(":checked")){
			event.preventDefault();
			sendMessage();
		}
	});
	$(new_message_btn).click(function(){
		sendMessage();
	});
	
	//initialize the tooltip for showing entire conversation contact
	var options = {
		animation : true,
		html : true,
		title : "",
		placement : "bottom"
	};
	$(conversation_contact_list).tooltip(options);
	
	$(conversation_contact_input).next().click(function(){
		return false;
	});
	
	//Initialize textext for the contact search input
	$(document).one('ajax_getAllContacts_post',function(){
		$(conversation_contact_input).textext({
			plugins : 'autocomplete suggestions filter',	
			suggestions : list_contact_name,
			filter : list_contact_name,
			autocomplete : {
				dropdownPosition: 'below',
				dropdownMaxHeight:	'75px',		
				dropdownTop:		'45px'
			},
			ext: {
				core: {
					onSetFormData: function(e, data) {
						// Fixes issue where the hidden input field has double quotation marks
						var self = this;
						self.hiddenInput().val(data);
						var userValue = self.hiddenInput().val();
						// This is to prevent textext from giving me duplicate values - bug - need to keep another variable, preventing some inputs	
						var crudeHash = userValue; 
						if (crudeHash != last_userinput) {
							last_userinput = crudeHash;
							if(typeof currentActiveThreadId == "undefined"){		//check if there is a thread selected
								alert("Please select a thread or create a new thread");
							}
							else if(list_participant_name.indexOf(userValue) != -1){	//check if the person is already in the conversation
								alert("This person is already in the conversation");
							}
							else{
								var user_id = getContactIdByName(userValue);
								if(user_id != 0){
									list_participant_id.push(user_id);
									list_participant_name.push(userValue);
									switchThread();
								}
							}
							
							// Wait until TextExt finishes processing internal events
							setTimeout(function() {
								// clear out the input boxes and then refresh the filter to show all.
								self.hiddenInput().val('');
								self.input().val('');
							}, TIME_GENERICDELAY, self);						
						}	            
					}			
				}			    
			}			
		});
	});
	
	//Process the existed thread once the ajax_getAllThread is done
	$(document).one('ajax_getAllThreads_post',function(){
		for(i in list_thread_data){
			processNewThread(i);
		}
		var all_message_num = $("#all_tab").find(".thread").length;
		if(all_message_num > 99){ all_message_num = 99;}
		$("#all_message_num").text('(' + all_message_num + ')');
		
		var unread_message_num = $("#unread_tab").find(".thread").length;
		if(unread_message_num > 99){ unread_message_num = 99;}
		if(unread_message_num > 0){ $("#unread_message_num").addClass('blue');}
		$("#unread_message_num").text('(' + unread_message_num + ')');
		
		var invitation_num = $("#invitation_tab").find(".thread").length;
		if(invitation_num > 99){ invitation_num = 99;}
		$("#invitation_num").text('(' + invitation_num + ')');
		
		if(all_message_num > 0){
			$('.empty-chat-history p').text('Select a conversation.');
		}
		else{
			$('.empty-chat-history p').text('You have no messages yet!');
		}
	});
	
	//Process the existed messages and contacts once the ajax_getMessagesAndContactsByThread_post is done
	$(document).on('ajax_getMessagesAndContactsByThread_post',function(){
		for(i in list_participant_name){
			processNewConvContact(i);
		}
		for(i in list_message_data){
			processNewMessage(i);
		}
		scrollToBottom();
	});
	
	$(window).resize(function(){
		setHeight();
		//set height of message container
		$(messages_container).css({'height' : message_container_height});
		$(messages_container).parent().css({'height' : message_container_height});
		//set height of thread container
		$(threads_container).css({'height' : thread_container_height});
		$(threads_container).parent().css({'height' : thread_container_height});
		//set the margin-top of empty sign 
		$('.empty-chat-history img').css({'margin-top' : (message_container_height - 105)/2 + 'px'});
	});
	
	//Start ajax to get all threads related to current user
	ajax_getAllThreads();
	//get all the contacts data in order to initialize textext
	ajax_getAllContacts();
});

function switchThread(){
	//see if this group chat exsited
	for(var i in list_thread_data){
		if(compareArray(list_thread_data[i].participants, list_participant_id)){
			fetchThreadById(list_thread_data[i].id);
			return;
		}
	}
	//it looks like there is not such a group chat
	clearConversation();
	for(i in list_participant_name){
		processNewConvContact(i);
	}
	updateConvContactList();
}

function compareArray(a, b){
	if(a.length != b.length){
		return false;
	}	
	for(var i=0; i<a.length; i++){
		if(a.indexOf(b[i]) == -1 || b.indexOf(a[i]) == -1){
			return false;
		}
	}
	return true;
}

function sendMessage(){
	if($(new_message_btn).hasClass('disabled')){
		return;
	}
	if(typeof currentActiveThreadId == "undefined"){
		alert("Please select a thread or create a new thread");
		return;
	}
	if(list_participant_id.length < 2){
		alert("Please add at least one of your friends");
		return;
	}
	$("#new_message").validate({
		rules:{
			new_message_input : {
				required : true,
				maxlength : 800
			}
		},
		messages:{
			new_message_input : {
				required : "Message can not be empty.",
				maxlength : "Message should not be greater than 800 characters."
			}	
		}
	});
	if(!$("#new_message").valid()){
		return;
	}
	var content = $(new_message_input).val();
	ajax_createMessage(content);
}

/**
 * This function will handle the process of posting a new thread
 * 
 * @param {integer} index
 */
function processNewThread(index){
	var threadObj = new TheadObj(list_thread_data[index].id, list_thread_data[index].img, list_thread_data[index].title, list_thread_data[index].preview, list_thread_data[index].date, list_thread_data[index].is_read);
	appendNewThread(threadObj);
}

/**
 * This function will handle the process of posting a new message
 * 
 * @param {integer} index
 */
function processNewMessage(index){
	var messageObj = new MessageObj(list_message_data[index].id, list_message_data[index].img, list_message_data[index].sender, list_message_data[index].senderid, list_message_data[index].content, list_message_data[index].time, list_message_data[index].date);
	appendNewMessage(messageObj);
}

/**
 *This function will handle the process of posting a new conversation contact 
 *
 * @param {string} name
 */
function processNewConvContact(index){
	appendNewConvContact(list_participant_name[index]);
}

function appendNewMessage(messageObj){
	if(messageObj.date != currentMsgDate){
		var message_date_dom = $("#message_date_template").clone(true);
		message_date_dom.removeAttr('id');
		message_date_dom.find('.message-date').text(messageObj.date);
		message_date_dom.css('display','block');
		$(messages_container).append(message_date_dom);
	}
	var message_dom = $("#message_template").clone(true);
	message_dom.css('display','block');
	message_dom.removeAttr('id');
	message_dom.attr('data-id', messageObj.id);
	message_dom.find('img').attr('src', messageObj.img);
	message_dom.find('.message-sender').text(messageObj.sender);
	message_dom.find('.message-content').html(htmlDecode(messageObj.content));
	message_dom.find('.message-time').text(messageObj.time);
	$(messages_container).append(message_dom);
	currentMsgDate = messageObj.date;
	message_dom.find('img').click(function(){
		location.assign(location.protocol+"//" + location.host + "/users/profile/" + messageObj.senderid);	
	});
}

function appendNewThread(threadObj){
	var thread_dom = $("#thread_template").clone(true);
	thread_dom.css('display','block');
	thread_dom.removeAttr('id');
	thread_dom.attr('data-id', threadObj.id);
	thread_dom.find('img').attr('src', threadObj.img);
	thread_dom.find('.thread-title').text(threadObj.title);
	thread_dom.find('.thread-time').text(threadObj.date);
	thread_dom.find('.thread-preview').text(htmlDecode(threadObj.preview));
	if(threadObj.is_read == 0 || threadObj.is_read == 2){
		thread_dom.addClass('unread');
	}
	switch(threadObj.is_read){
		case 0:
			$("#unread_tab").prepend(thread_dom.clone(true));
			break;
		case 2:
			$("#unread_tab").prepend(thread_dom.clone(true));
			$("#invitation_tab").prepend(thread_dom.clone(true));
			break;
		case 3:
			$("#invitation_tab").prepend(thread_dom.clone(true));
			break;
	}
	$("#all_tab").prepend(thread_dom);
	$(".thread[data-id=" + threadObj.id + "]").click(function(){
		fetchThreadById($(this).data('id'));
	});
}

function fetchThreadById(thread_id){
	var currentActive = $('.thread[data-id="' + thread_id + '"]');
	clearConversation();
	
	currentActiveThreadId = thread_id;
	currentMsgDate = currentActive.find('.thread-time').first().text();
	//mark it selected
	currentActive.toggleClass("selected");

	ajax_getMessagesAndContactsByThreadId(thread_id);
}

function appendNewConvContact(name){
	updateConvContactList();
	if($(conversation_contact_list).text().length > thread_title_maxLength){
		return;
	}
	$(conversation_contact_list).append(name + ", ");
	if($(conversation_contact_list).text().length > thread_title_maxLength){
		var content = $(conversation_contact_list).text();
		$(conversation_contact_list).text(content.substring(0,thread_title_maxLength) + "...");
	}
}

//build the tooltip(popover) for entire conversation contact list
function updateConvContactList(){
	var html_array = [];
	html_array.push('<ul>');
	for(i in list_participant_name){
		html_array.push('<li>' + list_participant_name[i] + '</li>');
	}
	html_array.push('</ul>');
	var contact_list = html_array.join('');
	$(conversation_contact_list).attr('data-original-title', contact_list);
}

//clear the conversation contacts list and the messages list 
function clearConversation(){
	currentActiveThreadId = 0;
	currentMsgDate = "";
	$(".thread.selected").toggleClass("selected");
	$(messages_container).empty();
	$(conversation_contact_list).empty();
}

//This function just works at the frontend, which is to get the corresponding id b given name in list_contact_data
function getContactIdByName(name){
	for(i in list_contact_data){
		if(list_contact_data[i].name == name){
			return list_contact_data[i].id;
		}
	}
	return 0;
}

//encode html
function htmlEncode(value){
    if (value) {
        return jQuery('<div />').text(value).html();
    } else {
        return '';
    }
}
//decode html
function htmlDecode(value) {
    if (value) {
        return $('<div />').html(value).text();
    } else {
        return '';
    }
}
function setHeight(){
	var new_message_container_height = $(".new_message_container").height();
	var window_bottom_edge = $(window).scrollTop() + $(window).height();
	thread_container_height = window_bottom_edge - message_container_top - magic_adjustment/2 - slimfooter_adjustment;
	message_container_height = window_bottom_edge - new_message_container_height - message_container_top - magic_adjustment - slimfooter_adjustment;
	if(message_container_height < message_container_min_height){
		message_container_height = message_container_min_height;
		thread_container_height = message_container_min_height + new_message_container_height + magic_adjustment/2;
	}
}
function scrollToBottom(){
	if($(messages_container).find('.message').length > 0){
		var last_message = $(messages_container).find('.message').last();
		var scrollbar_posiiton = last_message.offset().top - message_container_top;
		$(messages_container).slimScroll({scrollTo: scrollbar_posiiton + 'px'});
	}
}
