/**
 * @author Waylan Wong <waylan.wong@willrainit.com>
 *
 * Initialization of environment variables
 */

// DEBUG, adding this stage to the main debug output list.
var auth_stages = [ 'signup_skills' ];
debug_stages = debug_stages.concat(auth_stages);

// For debugging, we will fill the array with fake information
var compiledSkillsArray = {
	17 : 1,
	18 : 2,
	19 : 3,
};
var MAX_COMSKILLS_DISPLAY = 15;
// This is a closure, used to keep track of how many skills were selected by the user.
var fms_skillCounter = (function() {
	var MAX_SKILLS_COUNT = 10, 
		MIN_SKILLS_COUNT = 1,
		currentTotal = 0;
	return {
		getMaxLimit		: function() { return MAX_SKILLS_COUNT; },
		getMinLimit		: function() { return MIN_SKILLS_COUNT; },
		maxedSkillCount	: function() { return currentTotal >= MAX_SKILLS_COUNT; },
		incrementCount	: function() { ++currentTotal; log("signup_skills", "skillcounter", "currentTotal: " + currentTotal); return currentTotal; },
		decrementCount 	: function() { --currentTotal; log("signup_skills", "skillcounter", "currentTotal: " + currentTotal);return currentTotal; },
		getCount		: function() { return currentTotal; }
	};
}());

// Skill object template, to pass information about a skill around.
function SkillObj() {
	this.id = -1;
	this.name = "";
	this.icon_path = "";
}

// The CSS ID of the input box
var textBoxID = '#skillsearchbox';
// Keep track of last input, to prevent TextExt from feeding me the same skill twice
var fms_lastUserSkillInput = '';
// Store the skill names for use by the textext plugin
var fms_skillNames = [];
// Store the rest of the skill data in a parallel array
var fms_skillStorage = [];
// Store the special skills that need to be filtered from the common skills pool
var fms_specialSkills = ["Engineer", "Lyricist", "Producer", "Songwriter"];


log('signup_skills', "init", "AJAXing for all the skill data");
fms_getAllSkillData();
/**
 * Initialize the TextExt plugin for this page after we have received the list of skill names
 * from the backend.
 */
$(document).on('fms_getAllSkills_post', function(e) {		
	log('signup_skills', 'init', 'All skills have been downloaded, initializing the autocomplete and filter plugin');
	$(textBoxID).textext({
		plugins : 'autocomplete suggestions filter',	
		suggestions : fms_skillNames,
		filter : fms_skillNames,
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
					var crudeHash = userValue+fms_skillCounter.getCount();
					if (crudeHash != fms_lastUserSkillInput && crudeHash != fms_skillCounter.getCount()) {
						log('textExt', 'onSetFormData', "The hidden field value is: " + self.hiddenInput().val());
						fms_lastUserSkillInput = crudeHash;
						//processUserSelectedSkillName(userValue);
						//alert(userValue);
						processUserEnteredSkillName(userValue);
						// Wait until TextExt finishes processing internal events
						setTimeout(function() {
							// clear out the input boxes and then refresh the filter to show all.
							self.hiddenInput().val('');
							self.input().val('');
							//self.trigger('getSuggestions', {query:''});
						}, TIME_GENERICDELAY, self);						

					}		            
				}			
			}			    
		}			
	});
	//.trigger('getSuggestions', {query:''}); // this trigger would immediately drop down a lit of suggestions.	
	// Fill the common skills area with popular skills from the fms_skillStorage
	//populateCommonSkills(fms_skillStorage, fms_specialSkills);
	// Bind the click listeners for skills that the were pulled from the user's profile
	processExistingSkills();	
	log('signup_skills', "init", "Finished attaching plugin listerners.");
});	

// Initialize the data validator 
signupSkillsFormValidation();

// Initialize the roles and bind the click listeners.
populateSpecialSkills(); 

// Drag and Drop of skills.
$('#skill_selected').sortable(); //jQuery UI initialized


///////////////////////////////////////////////////////////////////////////////
//					End of Initialization
///////////////////////////////////////////////////////////////////////////////


/**
 * Initialize the jQuery form validator.
 * Our only requirement for this stage is that the user adds at least 1 skill. 
 */
function signupSkillsFormValidation() {
	
	jQuery.validator.addMethod('minUserSkillsCount', function(field_value, element){
		return fms_skillCounter.getCount() >= fms_skillCounter.getMinLimit();
	}, "Please add at least one skill.");
	
	jQuery.validator.addMethod('maxUserSkillsCount', function(field_value, element){
		return fms_skillCounter.maxedSkillCount();
	}, "Please reduce your skills to ten or less.");
	
	$("#fms_signup_skill_form").validate({
        focusInvalid: false,
        ignoreTitle: true,
		submitHandler : function(form) {
        	if ($(form).valid() && fms_skillCounter.getCount() >= fms_skillCounter.getMinLimit()) {        		
        		$.ajax({
        			//POST the form data to the backend
        			url		:	getBaseURL("/addUserSkill"),
        			type	:	"POST",
        			data	: 	{
        				skillsArray : processSkillsForExport() 
        			},        			
        		}).done(function(responseObj) {
        			// check if backend reported any errors.
        			if (responseObj.signup_success == 1) {
        				// no errors, call auth_processor to move to next stage of signup.
        				auth_processor(); 
        			} else {
        				log("signup", "submitHandler", "There was an error in the submission, error code is: "+ responseObj.error);
        				alert("There is some sort of error, please try reloading the page.");
        			}
        		});
        		        		        		        		
        	} else {
        		// not enough skills
        		alert("Please select at least "+fms_skillCounter.getMinLimit()+" skills!");
        	}
        },
		rules:{},
	});
}

/**
 * This function will create a list of skills for selection by the user.
 * The array must be in the following structure:
 * [
 * 		"id" 	: int,
 * 		"name"	: string,
 * 		"icon_path" : string
 * ]
 * Each list item will have a click listener bound to it, to create a 
 * list item on the right when clicked.
 * 
 * @param skillArray The array of skills we to show in the common skills pool.
 * @param exceptionSkills The array of skills we DON'T want to show in the common skills pool.
 */
function populateCommonSkills(skillArray, exceptionSkills) {
	log("signup_skills", "populateCommonSkills", "The skills we will show: " + JSON.stringify(skillArray));
	var tempstore = [];
	var tempstr = '';
	var offset = 0; // used to offset the max skills count in case we need to filter special skills out
	$.each(skillArray, function(index) {
		if (exceptionSkills.indexOf(this.name) >= 0) {
			// the current skill is a special skill, do not add to pool, increment the offset
			++offset;
		} else {
			tempstr = '<li><p data-id="'+this.id+'" data-name="'+this.name+'">'+this.name+'</p></li>\r\n';
			tempstore.push(tempstr);
			if (index >= MAX_COMSKILLS_DISPLAY + offset) { return false; }
		}
	});
	tempstore = tempstore.join('');
	log("signup_skills", "populateCommonSkills", "The finalized HTML of the common skills: " + tempstore);
	$('#common_skills').append(tempstore);
	
	// now bind click listeners on each one.
	$('#common_skills p').each(function() {
		$(this).click(function(e){			
			var clickedSkill = new SkillObj();
			clickedSkill.id = $(this).data('id');
			clickedSkill.name = $(this).data('name');
			if ($(this).hasClass('selected')) {
				removeSkillFromSelection(clickedSkill);	
			} else {
				if (addSkillToSelection(clickedSkill)){
					$(this).toggleClass('selected');
				}				
			}			
		});
	});
}

/**
 * This function will bind click listeners to the special skill buttons. 
 */
function populateSpecialSkills() {
	log('signup_skills', "populateSpecialSkills", "Binding click events to the special skills.");
	$('#extra_skills p').each(function() {
		$(this).click(function(e){	                        
                    
			var clickedSkill = new SkillObj();
			clickedSkill.id = $(this).data('id');
			clickedSkill.name = $(this).data('name');
			if ($(this).hasClass('selected')) {
				removeSkillFromSelection(clickedSkill);	
			} else {
				if (addSkillToSelection(clickedSkill)){
					$(this).toggleClass('selected');
				}								
			}
		});
	});
}

/**
 * This function is called by the TextExt plugin when user picks a value from 
 * the autocomplete/suggested list of skill names. Since it only gives us the 
 * skill name, we need to check it against our parallel array of complete 
 * skill data to find the rest of the skill data.
 * 
 * @param userValue The string value of the user selection
 */
function processUserEnteredSkillName(userInput) {
	var skillData = fms_skillStorage[fms_skillNames.indexOf(userInput)];
	if (skillData) {
		var clickedSkill = new SkillObj();
		clickedSkill.id = skillData.skillid;
		clickedSkill.name = skillData.skill_name;		
		// check if the skill is already selected.
		if ($('#skill_selected p[data-id="'+skillData.skillid+'"]').length == 0) {
			// check if the skill is un-selected in the visible pool
			// do this check BEFORE you add the new skill to the DOM, or else 
			// you will have a false positive (it will find the one in the selected list)
			var skill_in_pool = $('p[data-id="'+skillData.skillid+'"]'); 
			var bool_addedSkill = addSkillToSelection(clickedSkill);
			if (skill_in_pool.length > 0 && !$(skill_in_pool).hasClass('selected') && bool_addedSkill) {
				$(skill_in_pool).toggleClass('selected');
			}									
		} 		
		
	}
}

/**
 * This function will bind click listeners to the skills that were pulled from 
 * the backend. This make it possible for the user to resume editing their 
 * skills if they clicked the 'back' button from stage 3.
 */
function processExistingSkills() {
	// Have to bind the click to delete listeners, and toggle the selected 
	// class on the listed skills.
	var existingSkill = new SkillObj();
	$('#skill_selected p').each(function() {		
		fms_skillCounter.incrementCount();
		existingSkill.id = $(this).data('id');
		existingSkill.name = $(this).data('name');
		// Toggle corresponding skill in the common pool
		var skill_in_pool = $('p[data-id="'+existingSkill.id+'"]'); 
		if (skill_in_pool.length > 0 && !$(skill_in_pool).hasClass('selected')) {
			$(skill_in_pool).toggleClass('selected');
		}		
		// bind delete listener to the selected skill 
		$(this).click(function() {
			var clickedSkill = new SkillObj();
			clickedSkill.id = $(this).data('id');
			clickedSkill.name = $(this).data('name');
			removeSkillFromSelection(clickedSkill);
		});
	});
}


/**
 * This function will take one skill in the form of a SkillObj and add it to the 
 * list of user selected skills.
 * 
 * @param mySkillObj Information about the user selected skill, stored in a SkillObj object 
 * @returns 		True if skill was added successfully, else false. 
 */
function addSkillToSelection(mySkillObj) {
	log('signup_skills', "addSkillToSelection", "The desired skill is: " + JSON.stringify(mySkillObj));
	if (!fms_skillCounter.maxedSkillCount()) {
		fms_skillCounter.incrementCount();
		var tempstr = '<li><p data-id="'+mySkillObj.id+'" data-name="'+mySkillObj.name+'">'+mySkillObj.name+'</p><img src="/img/fms_user_portal/icon_handle.png"></li>\r\n';
		$('#skill_selected').append(tempstr);
		
		// Bind the delete skill action to the newly create skill tile
		$('#skill_selected p[data-id="'+mySkillObj.id+'"]').click(function(e){
			var clickedSkill = new SkillObj();
			clickedSkill.id = $(this).data('id');
			clickedSkill.name = $(this).data('name');
			removeSkillFromSelection(clickedSkill);
		});
		return true;
	} else {
		setTimeout(function(){
			$('#error_message').fadeOut();
		}, 1500);
		$('#error_message').show();
		return false;
	}	
}

/**
 * This function will take one skill off the list of user selected skills, and 
 * disable the selected highlight on the skill in either the common skills area 
 * or the special skills.
 * 
 * @param mySkillObj Information about the user selected skill, stored in a SkillObj object
 * @returns			True if the skill was removed successfully, else false.
 */
function removeSkillFromSelection(mySkillObj) {
	log('signup_skills', "removeSkillFromSelection", "The desired skill is: " + JSON.stringify(mySkillObj));
	var selected_skill_item = $('#skill_selected p[data-id="'+mySkillObj.id+'"]').parent();
		
	if (selected_skill_item) {
		fms_skillCounter.decrementCount();
		$(selected_skill_item).detach(); // remove from the selected list.
		var skill_item = $('p[data-id="'+mySkillObj.id+'"]');
		if (skill_item) {
			$(skill_item).toggleClass('selected');
		}
		return true;
	}
	return false;
}


/**
 * This function will process the user selected skills into an associative array 
 * to be AJAX'ed to the backend for insertion into the database.
 * 
 * @returns 		An associative array of skill ID's => skill ranking 
 */
function processSkillsForExport() {
	log('signup_skills', "processSkillsForExport", "Beginning processing of the selected skills for export.");
	var tempstore = new Object(); // we will be creating a JSON object to send back.
	$('#skill_selected p').each(function(index) {
		tempstore[$(this).data('id')] = index;
	});
	log('signup_skills', "processSkillsForExport", "Finished processing, here is result: " + JSON.stringify(tempstore));
	return tempstore;
}

/////////////////////////////////////////////////
////		AJAX code
/////////////////////////////////////////////////


/**
 * This function will go to the backend to grab all the skill data, as well as 
 * an auxilliary array that only contains the skill names.
 */
function fms_getAllSkillData() {
	$.get( 
		location.protocol + '//' + location.hostname + '/dashboard/project/getAllSkillData',
		{},
		function(db_output) {
			if (db_output.length > 0) {				
				fms_skillStorage = db_output[0];
				fms_skillNames = db_output[1];
				$(document).trigger('fms_getAllSkills_post'); // tell the textExt plugin that we are ready to initialize
				log('signup_skills', 'fms_getAllSkillData', 'This is skill data dump: ' + JSON.stringify(fms_skillStorage));
				log('signup_skills', 'fms_getAllSkillData', 'This is skill name dump: ' + JSON.stringify(fms_skillNames));
			} else {
				log('signup_skills', 'fms_getAllSkillData', 'Nothing came back from the backend.');
			}
		}, "JSON"
	);
}

// Popovers
$('#help_skill_selection').hover(function(){
	document.getElementById("help_skill_popover_trgger").click();
});
$('#help_skill_popover_trgger').popover();
