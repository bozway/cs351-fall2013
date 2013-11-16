/**
 * @author Hao.Cai
 */
//Contacts initialization
 $(function(){
	//Initialize textext for the contact search input
	$(document).one('ajax_getAllContacts_post',function(){
		$(contact_search_input).textext({
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
						if (crudeHash != last_userinput && userValue != "") {
							last_userinput = crudeHash;
							searchContact(userValue);			
						}	            
					}			
				}			    
			}			
		});
	});
	
	$(contact_search_input).focusout(function(){
		setTimeout(function() {
			searchContact($(contact_search_input).val());
		}, 500, self);
	});
	$(contact_search_input).next().click(function(){
		return false;
	});
	
	$(".tool-bar li").click(function(){
		$(".selected-sort").text($(this).children().text());
		switch($(this).data('type')){
			case 0:
				$("#contacts_container > div").tsort('span.last_name', {cases : false});
				break;
			case 1:
				$("#contacts_container > div").tsort('span.first_name', {cases : false});
				break;
			case 2:
				$("#contacts_container > div").tsort('span.last_login_time', {order : 'desc'});
				break;
		}
	});
	
    $('.btn-delete').click(function(){
    	if(confirm("Are you sure to delect this contact?")){
	        var contact_id = $(this).data('contactid');
	        var url = location.protocol + '//' + location.hostname + "/ajax/message/deleteContact";
	        $.post(url, {contactId : contact_id})
	        .done(function(data){
	            if($.trim(data) === 'true'){
	                $('.contact_container[data-id="' + contact_id + '"]').detach();
	            }
	        });
	    }
    });

	$('.more-link').click(function(){
		$(this).parent().find('.more-skills').toggleClass('hidden');
		if($(this).parent().find('.more-skills').hasClass('hidden')){
			$(this).text('more...');
		}
		else{
			$(this).text('less...');
		}
	});
});

function searchContact(contact_name){
	var contact_id = getContactIdByName(contact_name);
	$('.contact_container').each(function(){
		if($(this).data('id') == contact_id || contact_name == ""){
			$(this).show();
		}
		else{
			$(this).hide();
		}
	});
}