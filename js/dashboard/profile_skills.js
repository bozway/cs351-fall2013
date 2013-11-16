/**
 * @author Hao.Cai
 */

/////////////////////////////////////////
/////		APPLET VARIABLES 		/////
/////////////////////////////////////////

/* There can be a maximum of 5 genres and 5 influences for each skill. */
var MAX_SKILL_TAGS = 5;

var TAGTYPE_GENRE 		= 1;
var TAGTYPE_INFLUENCE 	= 2;
/* Tag object template, to pass information about a tag around. */
function TagObj(myid, myname, mytype) {	
	this.id = (typeof myid != 'undefined') ? myid : -1;
	this.name = (typeof myname != 'undefined') ? myname : "";
	this.type = (typeof mytype != 'undefined') ? mytype : "";	
}

/* Skill action enums */
var SKILLACTION_SAVE = 1;
var SKILLACTION_EDIT = 2;

/*Track the user skills, genres and all skills are loaded by ajax*/
var bool_user_skills_ready = false;
var bool_genres_ready = false;
var bool_all_skills_ready = false;

/* Prevent spamming of new skills */
var bool_permit_skillclick = true;
var TIME_SKILLSPAMDELAY = 500;

/* store the current skill row index */
var currentRowID = 0;

/* store the list of genres in parallel arrays - one is just names, for use in textext*/
var list_genre_data;
var list_genre_names;
/* store the list of influences in a flat array. */
var list_influence_names;
/* store the parallel lists of skill data and skill names */
var list_skill_data;
var list_skill_names;
/* store user skills */
var list_user_skill;

/* Reference to skills container */
var skills_container = '#skill_column';
/* Reference to the genre search box */
var selector_genreinput_input = '.skillGenreSpecifier';
var selector_genretagblock = '.style_added';
var obj_genreinput_container;
/* Reference to the influence search box */
var selector_influenceinput_input = '.skillInfluenceSpecifier';
var selector_influencetagblock = '.influence_added';
var obj_influenceinput_container;
/* Refernce to the skill search box */
var selector_skillinput_container = '#skillSpecifier-wrapper';
var selector_skillinput_input = '#skillSpecifier';

/* Store the last thing the user entered, to prevent TextExt from feeding us the same input twice. */
var last_userinput = '';
/* Store the icon of the last category selected. */
var last_category_selected_icon = '';

/* This is a closure, used to keep track of how many skills were selected by the user. */
var proj_skillCounter = (function() {
	var MAX_SKILLS_COUNT = 10, 
		MIN_SKILLS_COUNT = 1,
		currentTotal = 0;
	return {
		getMaxLimit		: function() { return MAX_SKILLS_COUNT; },
		getMinLimit		: function() { return MIN_SKILLS_COUNT; },
		hitMaxCount		: function() { return currentTotal >= MAX_SKILLS_COUNT; },
		hitMinCount		: function() { return currentTotal >= MIN_SKILLS_COUNT; },
		incrementCount	: function() { ++currentTotal; log("profile_skills", "skillcounter", "currentTotal: " + currentTotal); return currentTotal; },
		decrementCount 	: function() { --currentTotal; log("profile_skills", "skillcounter", "currentTotal: " + currentTotal);return currentTotal; },
		getCount		: function() { return currentTotal; }
	};
}());

/* Store the ProjectSkills object. */
var user_skills_array = [];


/////////////////////////////////////////
/////	DOM STATE DEPENDENT CODE	/////
/////////////////////////////////////////
$(function() {
	// initialize the debug logger
	var loggingStage = ["profile_skills"];
	debug_stages = debug_stages.concat(loggingStage);
	
	$("#save_skills").click(function(){
		serializeProjectSkills();
	});
		
	$(document).one('ajax_getAllGenres_post',function(){
		//try to process the existed user skills
		if(bool_genres_ready && bool_user_skills_ready && bool_all_skills_ready){
			processExistingSkills();
		}
	});
	
	$(document).one('ajax_getUserSkills_post',function(){
		//try to process the existed user skills
		if(bool_genres_ready && bool_user_skills_ready && bool_all_skills_ready){
			processExistingSkills();
		}
	});
	
	$(document).one('ajax_getAllSkills_post', function() {		
		//try to process the existed user skills
		if(bool_genres_ready && bool_user_skills_ready && bool_all_skills_ready){
			processExistingSkills();
		}
		//initialize the textext for search skill
		var list_skill_names_copy = [];
		for(var iter=0; iter < list_skill_names.length;iter++) list_skill_names_copy[iter] = list_skill_names[iter];
		$(selector_skillinput_input).textext({
			plugins : 'autocomplete suggestions filter',	
			suggestions : list_skill_names_copy,
			filter : list_skill_names_copy,
			autocomplete : {
				dropdownPosition: 'below',
			},
			ext: {
				core: {
					onSetFormData: function(e, data) {
						// Fixes issue where the hidden input field has double quotation marks
						var self = this;
						self.hiddenInput().val(data);
						var userValue = self.hiddenInput().val();
						// This is to prevent textext from giving me duplicate values - bug - need to keep another variable, preventing some inputs
						var crudeHash = userValue+proj_skillCounter.getCount(); 
						//if (crudeHash != last_userinput && crudeHash != proj_skillCounter.getCount()) {
						if(userValue != 0 && userValue != last_userinput) {
							log('profile_skills', 'onSetFormData', "The hidden field value is: " + self.hiddenInput().val());
							//last_userinput = crudeHash;
							last_userinput = userValue
							processNewSkill(userValue, proj_skillCounter.getCount(), "");
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
	
	// start AJAX'ing for the user's skills
	ajax_getUserSkills();

	// start AJAX'ing for the skills and tag information
	ajax_getAllGenres();

	// DEBUG Get the skill data, maybe we will switch to ajax'ing the backend for skill names instead.
	ajax_getAllSkills();
});

/////////////////////////////////////////
/////		DOM INDEPENDENT CODE	/////
/////////////////////////////////////////
function processExistingSkills(){
	for(var i in list_user_skill){
		processNewSkill(list_user_skill[i].name, list_user_skill[i].ranking, list_user_skill[i].videoPreview);
		currentRowID = i;
		for(var x in list_user_skill[i].genres){
			processNewGenreTag(list_user_skill[i].genres[x]);
		}
		for(var x in list_user_skill[i].influences){
			processNewInfluenceTag(list_user_skill[i].influences[x]);
		}
	}
}

function initializeGenreTextext(genreInput) {		
	$obj = $(genreInput).textext({
		plugins : 'autocomplete suggestions filter',	
		suggestions : list_genre_names,
		filter : list_genre_names,
		autocomplete : {
			dropdownPosition: 'below',
		},
		ext: {
			core: {
				onSetFormData: function(e, data) {
					// Fixes issue where the hidden input field has double quotation marks
					var self = this;
					currentRowID = $(self.hiddenInput()).parent().find('.skillGenreSpecifier').data('rowid');
					self.hiddenInput().val(data);
					var userValue = self.hiddenInput().val();
					// This is to prevent textext from giving me duplicate values - bug - need to keep another variable, preventing some inputs
					var crudeHash = userValue+proj_skillCounter.getCount(); 
					if (crudeHash != last_userinput && crudeHash != proj_skillCounter.getCount()) {
						log('profile_skills', 'onSetFormData', "The hidden field value is: " + self.hiddenInput().val());
						last_userinput = crudeHash;
						processNewGenreTag(userValue);
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
}

function initializeInfluenceTextext(influenceInput) {		
	$(influenceInput).textext({
		plugins : 'ajax autocomplete suggestions filter',
		ajax : {
			url : '/dashboard/project/getInfluenceSuggestions',
			dataType : "JSON",
			"dataCallback" : function(query) { 
				log("profile_skills", "AJAX", "The partial artist name is: " + query); 
				return { 'partial' : query }; 
			},
			loadingDelay : 1.0,
			loadingMessage : "Loading suggestions...",
			typeDelay : 0.50,			
		},
		autocomplete : {
			dropdownPosition: 'below',
		},
		ext: {
			core: {
				onSetFormData: function(e, data) {
					// Fixes issue where the hidden input field has double quotation marks
					var self = this;
					currentRowID = $(self.hiddenInput()).parent().find('.skillInfluenceSpecifier').data('rowid');
					self.hiddenInput().val(data);
					var userValue = self.hiddenInput().val();
					//log('profile_skills', 'onSetFormData', "Data-rowid of the parent row: " + self.hiddenInput().parent().parent().parent().parent().data('rowid'));
					// This is to prevent textext from giving me duplicate values - bug - need to keep another variable, preventing some inputs
					var crudeHash = userValue+proj_skillCounter.getCount(); 
					if (crudeHash != last_userinput && crudeHash != proj_skillCounter.getCount()) {
						log('profile_skills', 'onSetFormData', "The hidden field value is: " + self.hiddenInput().val());
						last_userinput = crudeHash;
						if (userValue != "Who's the inspiration?" && userValue != "Is that a new artist?") {
							processNewInfluenceTag(userValue);
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
}
	
/**
 * @author Waylan Wong <waylan.wong@willrainit.com>
 * 
 * This function will generate and then append the HTML element 
 * for a new skill to the appropriate location.
 * 
 * @pre The maximum number of skills has not been reached yet.
 * @post The maximum number of skills will not be exceeded.
 * @param skillObj			The SkillObj holding information about the desired skill
 * @param destinationobj 	The HTML element where we should append this new skill.
 */
function appendNewSkill(skillObj) {
	var htmlarray = [];
	proj_skillCounter.incrementCount();
	var newRowID = proj_skillCounter.getCount()-1;
	var base_url=location.protocol + '//' + location.hostname +"/";
	htmlarray.push('<li class="skill_list" data-name="'+skillObj.name+'" data-rowid="'+ newRowID +'" data-skillid="'+skillObj.id+'" data-ranking="'+skillObj.ranking+'">');
//	htmlarray.push('');
	htmlarray.push('<p class="skillname" data-rowid="'+ newRowID +'"><img class="directingimg" src="'+base_url+'img/trigon_white_right.png">'+ skillObj.name);
	htmlarray.push('<span class="cancel_skill" >Delete Skill</span></p> <img class="dropdownlistimg" src="'+base_url+'img/skill_menu.png">');
	

	htmlarray.push('<div class="container dropdown-menu"><div class=" firstsp span4">');
	htmlarray.push('<div class="video_space">');
	if(typeof skillObj.preview_src != 'undefined' && skillObj.preview_src != null && skillObj.preview_src != ''){
		htmlarray.push('<iframe title="YouTube video player" width="280" height="191" src="' + skillObj.preview_src + '" frameborder="0" allowfullscreen></iframe>');
	}else{
		htmlarray.push('<span class="fui-video video_space_span"></span>');
	}
	htmlarray.push('</div>');
   // htmlarray.push(' <p>Do you have a video to show your skills?<input type="text" class="video_url" value="' + skillObj.preview_src + '"></p>');
    htmlarray.push('</div>');	
	
	htmlarray.push('<div class="firstsp span4"> <div class="genres_influences_input">');
 //   htmlarray.push('<input type="text" id="search-query"  class ="skillGenreSpecifier" placeholder="Search" data-rowid="' + newRowID + '"/>');
 	htmlarray.push('<form class="form-search"><div class="input-append">');
	htmlarray.push('<input type="text" data-rowid="' + newRowID + '" class="span2 search-query skillGenreSpecifier" placeholder="Search">');
	htmlarray.push('<button type="submit" class="btn"><span class="fui-search"></span></button></div></form>');
	htmlarray.push('<span  class="Genresname">Genres</span></div> <div class="style_added"></div></div>');	
	
	htmlarray.push('<div class="span4"> <div class="genres_influences_input">');
  //  htmlarray.push('<input type="text" id="search-query" placeholder="Search" class="skillInfluenceSpecifier" data-rowid="' + newRowID + '">');
   	htmlarray.push('<form class="form-search"><div class="input-append">');
	htmlarray.push('<input type="text" data-rowid="' + newRowID + '" class="span2 search-query skillInfluenceSpecifier" placeholder="Search">');
	htmlarray.push('<button type="submit" class="btn"><span class="fui-search"></span></button></div></form>');
  
   	htmlarray.push('<span class="Genresname">Influences</span></div> <div class="influence_added"></div></div>');
	htmlarray.push(' <p class="video-url-area">Do you have a video to show your skills?<input type="text" class="video_url" ');
	htmlarray.push( (skillObj.preview_src != null) ? 'value="' + skillObj.preview_src + '">': 'value="">');	
	htmlarray.push('</p></div> </li>');
	
	
	var htmlstring = htmlarray.join('');
	log("profile_skills", "appendNewSkill", "This is the generated HTML for the new skill: " + htmlstring);
	// append the HTML code for the new skill row
	$(skills_container).append(htmlstring);	
	
	// bind click listeners on the buttons, wait a bit for the DOM to initialize
	setTimeout(function() {
		/* Timeout is used to make sure the buttons are in the DOM before we try to bind
		 * the click events to them. Since the row is not active yet, must select based 
		 * on the data-rowid instead of on the .active class. */		
		var newActive = $('.skill_list[data-rowid="' + newRowID + '"]');
		var prevInput = $(newActive).find('.video_url');
		$(prevInput).change(function(){
			attachPrev(newActive);
		});
		var deletebutton = $(newActive).find('.cancel_skill');
		$(deletebutton).click(function(e){
			if (e.stopPropagation) {
		        e.stopPropagation();
		    }
			var skill_list_num = $('.skill_list').length;
			if(skill_list_num > 1){
				if (window.confirm("Are you sure you wish to delete skill?")) { 
					deleteSkill(newActive);
				}
			} else {
				alert('You must have at least 1 skill.');
			}
		});
		var genreInput = $(newActive).find('.skillGenreSpecifier');
		initializeGenreTextext(genreInput);
		var influenceInput = $(newActive).find('.skillInfluenceSpecifier');
		initializeInfluenceTextext(influenceInput);
		
		var dropdownlistimg=$(newActive).find('.skillname'); 
		$(dropdownlistimg).click(function() {
			var dropdown_menu = $(this).parent().find('.dropdown-menu');
			var is_hidden = dropdown_menu.is(":hidden");
			if (is_hidden) {
				dropdown_menu.show();
				$(this).parent().find("img:first").attr("src", base_url + "/img/trigon_white_bottom.png");
			} else {
				dropdown_menu.hide();
				$(this).parent().find("img:first").attr("src", base_url + "/img/trigon_white_right.png");
			}
		});
		// Waylan: Fix for Flat UI not activating the blue highlight for the entire search box, we will 
		// force activation of the "focus" class on the <form class="form-search"> element.
		$(newActive).find('form.form-search').each(function() {
			/* For each form-search element (there are two, one for genre, one for influence
			 * we will find the search-query input box, and then when user clicks inside, or 
			 * outside, we will toggle the "focus" class on the <form> element, which we pass
			 * as $(this) to the event.data for the $.on() function. */
			$(this).find('.search-query').on('focus blur',null, $(this),function(event){
				event.data.toggleClass('focus');
			});
		});
		
	}, TIME_GENERICDELAY);	
	// WAYLAN :: ah shit, toggleActivation is called right after this,  we may need to put off activation with a event bind
	return newRowID;  
}




/**
 * @author Hao Cai <Hao.Cai@willrainit.com>
 * 
 * This function will attach a video from the url in preview video input
 * 
 * @param target	the row element being selected
 */
function attachPrev(row){
	var prev_src = row.find('.video_url').val();
	if(prev_src == ""){												//remove the video when the pre_url is empty
		row.find('iframe').remove();
		return;
	}
	var re = /([a-zA-Z0-9_\-])/;
	var video_page_head = "?v=";
	var share_head = 'http://youtu.be/';
	var embed_head = '//www.youtube.com/embed/';
	var vIdLength = 11;
	var vId="";
	
	if(prev_src.indexOf(video_page_head) != -1){					//check if this is the video page address
		vId = prev_src.substr(prev_src.indexOf(video_page_head) + video_page_head.length, vIdLength);
	}
	else if(prev_src.indexOf(share_head) != -1){					//check if this is the share address
		vId = prev_src.substr(prev_src.indexOf(share_head) + share_head.length, vIdLength);
	}
	else if(prev_src.indexOf(embed_head) != -1){					//check if this is the embedded address
		vId = prev_src.substr(prev_src.indexOf(embed_head) + embed_head.length, vIdLength);
	}
	else if(re.test(prev_src) && prev_src.length == vIdLength){		//check if this is the vId already
		vId = prev_src;
	}
	
	if(vId == ""){
		alert('the url is invalid');
		row.find('.video_url').val("");
		return;
	}
	
	//transfer into embed url
	prev_src = '//www.youtube.com/embed/' + vId;
	row.find('.video_url').val(prev_src);
	
	if(row.find('iframe').length == 0){
		htmlstring = '<iframe title="YouTube video player" width="280" height="191" src="' + prev_src + '" frameborder="0" allowfullscreen></iframe>';
		row.find('.video_space').append(htmlstring);
	}
	else{
		row.find('iframe').attr('src', prev_src);
	}
}

/**
 * @author Waylan Wong <waylan.wong@willrainit.com>
 * 
 * This function will detach a skill row from the DOM. It should be 
 * called either by an onClick= or a .click(function(e){});
 * 
 * @param target	the row element being deleted
 */
function deleteSkill(row) {
	log("profile_skills", "deleteSkill", "Clicked the delete skill button for: " + row.data('rowid'));
	// decrement the skill counter
	proj_skillCounter.decrementCount();
	// detach the skill row.
	row.detach();
}


/**
 * @author Waylan Wong <waylan.wong@willrainit.com>
 * 
 * This function will check if we are saving changes or editing the skill. 
 * It should be called either by an onClick= or a .click(function(e){});
 * 
 * @param self		This is the DOM element that triggered the click event. 
 */
function editToggle(self) {
	log("profile_skills", "editToggle", "Clicked the toggle skill button for: " + $(self.currentTarget).data('rowid'));
	// call toggleActiveRow
	toggleActiveRow($(self.currentTarget).data('rowid'));
}

/**
 * @author Waylan Wong <waylan.wong@willrainit.com>
 * 
 * This function will generate the HTML code for each tag element, 
 * then append the element to the specified tag block and skill row.
 * The tagObj will contain information about the tag, including 
 * tag type.
 * 
 * @param tagObj			Data about the tag to be added.
 * @param skillRowID		Integer identifier
 */
function appendNewTag(tagObj, skillRowID) {
	// check if max tag count has been reached.
	var currentActive = $(skills_container).find('li[class="skill_list"][data-rowid="' + skillRowID + '"]');
	var tagblocklist;
	if (tagObj.type == TAGTYPE_GENRE) {
		tagblocklist = currentActive.find(selector_genretagblock);
	} else if (tagObj.type == TAGTYPE_INFLUENCE) {
		tagblocklist = currentActive.find(selector_influencetagblock);
	}
	
	if (tagblocklist.find('.tag').length < MAX_SKILL_TAGS) {
		// Only add the tag if it doesn't exist in the list
		if (tagblocklist.find('[data-name="'+tagObj.name+'"]').length == 0) {
			var htmlarray = [];
			htmlarray.push('<div class="tag" data-id="'+tagObj.id+'">');
			htmlarray.push('<label class="skill-tag-content" data-name="'+tagObj.name+'">'+tagObj.name+'</label>');
			htmlarray.push('<label class="skill-tag-delete"></label>');
			htmlarray.push('</div>');
			var htmlstring = htmlarray.join('');
			tagblocklist.append(htmlstring);
			// may need a timeout here before binding the action.
			tagblocklist.find('[data-name="'+tagObj.name+'"]').next().click(function(e) {
				deleteTag(e);
			});
		}
	} else {
		alert("You have reached the maximum of " + MAX_SKILL_TAGS + " skill tags!"); // DEBUG 
	}
	 
	// build the HTML code for the new tag
	// increment the tag count in the tag block
	// append the tag element to the tag block	
}

/**
 * @author Waylan Wong <waylan.wong@willrainit.com>
 * 
 * This function will detach one tag from the tag block 
 * when called by the onClick event.
 * 
 * @param self		The DOM element from the onClick event.
 */
function deleteTag(self) {
	// remove the tag from the DOM.
	$(self.currentTarget).parent().detach();
}

/////////////////////////////////////////
/////		TextExt Processors		/////
/////////////////////////////////////////

/**
 * @author Waylan Wong <waylan.wong@willrainit.com>
 * 
 * This will take the plain text input from textext, and figure out the 
 * corresponding genre tag data from the parallel array, then feeding the 
 * information to the function that will actually append the HTML element.
 * 
 * @param userInput		String value from TextExt. A valid genre name.
 */
function processNewGenreTag(userInput) {
	var tagdata = list_genre_data[list_genre_names.indexOf(userInput)];
	if (tagdata) {
		var newtag = new TagObj(tagdata.id, tagdata.name, TAGTYPE_GENRE);
		appendNewTag(newtag, currentRowID);
	}
}

/**
 * @author Waylan Wong <waylan.wong@willrainit.com>
 * 
 * 7/12/2013 As of now, we have no plans on storing anything other than the 
 * artist name because the database we are pulling from is not our own.
 * Since we will be pulling from Last.fm, we will not need any ID's. 
 * 
 * @param userInput		String value from TextExt.
 */
function processNewInfluenceTag(userInput) {
	// call the append function directly.
	var newtag = new TagObj(-1, userInput, TAGTYPE_INFLUENCE);
	appendNewTag(newtag, currentRowID);
}

/**
 * @author Waylan Wong <waylan.wong@willrainit.com>
 * 
 * This will take the plain text input from textext, and figure out the 
 * corresponding skill data from the parallel array, then feeding the 
 * information to the function that will actually append the HTML element.
 * 
 * @param userInput		String value from TextExt
 */
function processNewSkill(name, ranking, videoPreview) {
	// Check if they have reached the maximum number of skills per project
	if (!proj_skillCounter.hitMaxCount()) {
		
		// figure out which skill they want to add.
		var skilldata = list_skill_data[list_skill_names.indexOf(name)];
		if (skilldata) {
			// Add the new skill to the destination
			var video_src = videoPreview;
			var clickedSkill = new SkillObj(skilldata.skillid, skilldata.skill_name, '');
			clickedSkill.setExtra(ranking, videoPreview);
			appendNewSkill(clickedSkill);
		}					
	} else {
		alert("You have reached the maximum of " + proj_skillCounter.getMaxLimit() + " skills!");
	}
}


/**
 * @author Waylan Wong <waylan.wong@willrainit.com>
 * 
 * This will generate a ProjectSkills object with all the data the user entered.
 * For object data structure details, please see the comment describing the 
 * ProjectSkills variable at the top of the this file. 
 */
function serializeProjectSkills() {
	var tempskills = [];
	var skills = $(skills_container + ' .skill_list');
	
	$(skills).each(function(index) {
		var tempgenres = [];
		var tempinfluences = [];		
		var theskillid = $(this).data('skillid');
		var theskillranking = index;
		var theskillPrev = $(this).find('.video_url').val(); 
		if(theskillPrev.indexOf('//www.youtube.com/embed/') != 0 && theskillPrev.indexOf('http://youtu.be/') != 0  && theskillPrev != ""){
			alert('the url is invalid');
			row.find('.video_url').val("");
			return;
		}
		$(this).find(selector_genretagblock).find('div').each(function() {
			tempgenres.push($(this).data('id'));
		});
		$(this).find(selector_influencetagblock).find('.skill-tag-content').each(function() {
			tempinfluences.push($(this).data('name'));
		});
		tempskills.push({
			skillid : theskillid,
			ranking : theskillranking,
			genres : tempgenres,
			influences : tempinfluences,
			skillPrev : theskillPrev
		}); 
	});
	
	user_skills_array = tempskills;
	ajax_postUserSkills();
}


/////////////////////////////////////////
/////			AJAX CODE			/////
/////////////////////////////////////////

/**
 * @author Waylan Wong <waylan.wong@willrainit.com>
 * 
 * AJAX function to grab the list of genre's from the backend.
 * This will fire the 'ajax_getAllGenres_post' event when it 
 * completes.  
 */
function ajax_getAllGenres() {
	$.get(location.protocol + "//" + location.hostname
			+ "/dashboard/project/getGenreTags", {}, function(data) {
		if (data.length > 0) {
			list_genre_data = data[0];
			list_genre_names = data[1];
			bool_genres_ready = true;
			$(document).trigger('ajax_getAllGenres_post');
		} else {
			log('profile_skills', "ajax_getAllGenres",
					"Did not get any genres.");
		}
	}, "JSON").fail(function(){
		setTimeout(function(){
			ajax_getAllGenres();
		}, 500, self);
	});
}

/**
 * @author Waylan Wong <waylan.wong@willrainit.com>
 * 
 * DEVELOPMENT - AJAX function to grab the list of skill data This will fire the
 * 'ajax_getAllSkills_post' event when it completes.
 */
function ajax_getAllSkills() {
	$.get(location.protocol + "//" + location.hostname
			+ "/dashboard/project/getAllSkillData", {}, function(data) {
		if (data.length > 0) {
			list_skill_data = data[0];
			list_skill_names = data[1];
			bool_all_skills_ready = true;
			$(document).trigger('ajax_getAllSkills_post');
		} else {
			log('profile_skills', "ajax_getAllSkills_post",
					"Did not get any skill data.");
		}
	}, "JSON").fail(function(){
		setTimeout(function(){
			ajax_getAllSkills();
		}, 500, self);
	});
}

/**
 * @author Hao Cai
 * 
 * DEVELOPMENT - AJAX function to retrieve the user's skills. This will fire the
 * 'ajax_getUserSkills_post' event when it completes.
 */
function ajax_getUserSkills(){
	list_user_skill = [];
	$.get(location.protocol + "//" + location.hostname
			+ "/ajax/profile/getUserSkills", {}, function(data) {
		if (data.length > 0) {
			for(var i in data){
				list_user_skill[parseInt(data[i].ranking)] = data[i];
			}
			bool_user_skills_ready = true;
			$(document).trigger('ajax_getUserSkills_post');
		} else {
			log('profile_skills', "ajax_getUserSkills",
					"Did not get any user skill.");
		}
	}, "JSON").fail(function(){
		setTimeout(function(){
			ajax_getUserSkills();
		}, 500, self);
	});
}

/**
 * @author Hao Cai
 * 
 * DEVELOPMENT - AJAX function to post all the data of skills to backend
 */
function ajax_postUserSkills(){
	$.post(location.protocol + "//" + location.hostname
			+ "/ajax/profile/updateUserSkill", {userSkills : user_skills_array}, function(data){
				if(data.errorcode == 0){
					alert(data.message);
				}
				else{
					alert(data.message);
				}
			});
}
