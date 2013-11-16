$(document).ready(function(){
	verticalNavInit();
	
	$('#help_myskills').popover();
	$('#alert_myskills').hover(
		function(){
			$('#help_myskills').popover('show');
		},
		function(){
			$('#help_myskills').popover('hide');
		}
	);

	
	$('#help_teamskills').popover();
	$('#alert_teamskills').hover(
		function(){
			$('#help_teamskills').popover('show');
		},
		function(){
			$('#help_teamskills').popover('hide');
		}
	);
});


/////////////////////////////////////////
/////		APPLET VARIABLES 		/////
/////////////////////////////////////////

/* There can be a maximum of 5 genres and 5 influences for each skill. */
var MAX_SKILL_TAGS = 5;
/* There is a maximum number of characters we'll take for the skill descriptions. */
var MAX_SKILL_DESC_CHARS = 255;
/* There is a minimum number of skills that the owner must have. */
var MIN_SKILL_COUNT_OWNER = 1;
/* There is a minimum number of skills that the team must have */
var MIN_SKILL_COUNT_TEAM = 1;

var TAGTYPE_GENRE = 1;
var TAGTYPE_INFLUENCE = 2;
/* Tag object template, to pass information about a tag around. */
function TagObj(myid, myname, mytype) {
    this.id = (typeof myid !== 'undefined') ? myid : -1;
    this.name = (typeof myname !== 'undefined') ? myname : "";
    this.type = (typeof mytype !== 'undefined') ? mytype : "";
}

/* Skill action enums */
var SKILLACTION_SAVE = 1;
var SKILLACTION_EDIT = 2;
var SKILLACTION_VIEW = 3;
var SKILLACTION_CLOSE = 4;

/* Prevent spamming of new skills */
var bool_permit_skillclick = true;
var TIME_SKILLSPAMDELAY = 500;

/* store the current skill row index */
var currentRowID = -1;

/* store the list of genres in parallel arrays - one is just names, for use in textext*/
var list_genre_data;
var list_genre_names;
/* store the list of influences in a flat array. */
var list_influence_names;
/* store the parallel lists of skill data and skill names */
var list_skill_data;
var list_skill_names;
/* flags for when the lists are successfully ajax'ed from the backend */
var bool_data_genre_post = false;
var bool_data_skill_post = false;

/* Reference to the genre search box */
var selector_genreinput_container = '#skillGenreSpecifier-container';
var selector_genreinput_input = '#skillGenreSpecifier';
var selector_genretagblock = '.tag-header[data-type="genre"]';
var obj_genreinput_container;
/* Reference to the influence search box */
var selector_influenceinput_container = '#skillInfluenceSpecifier-container';
var selector_influenceinput_input = '#skillInfluenceSpecifier';
var selector_influencetagblock = '.tag-header[data-type="influence"]';
var obj_influenceinput_container;
/* Refernce to the skill search box */
var selector_skillinput_container = '#skillSpecifier-wrapper';
var selector_skillinput_input = '#skillSpecifier';
/* Reference to the team skills area */
var selector_team_basket = '#selected-skill-list-team';
/* Reference to the owner skills area */
var selector_owner_basket = '#selected-skill-list-owner';
/* Store which skill basket we should put the new skills into 1=myskill, 2=teamskills */
var basket_selector = 1;

/* Store the last thing the user entered, to prevent TextExt from feeding us the same input twice. */
var last_userinput = '';
/* Store the icon of the last category selected. */
var last_category_selected_icon = '';

/* This is a closure, used to keep track of how many skills were selected by the user. */
var proj_skillCounter = (function() {
    var MAX_SKILLS_COUNT = 15,
            MIN_SKILLS_COUNT = 1,
            currentTotal = 0;
    return {
        getMaxLimit: function() { return MAX_SKILLS_COUNT; },
        getMinLimit: function() { return MIN_SKILLS_COUNT; },
        hitMaxCount: function() { return currentTotal >= MAX_SKILLS_COUNT; },
        hitMinCount: function() { return currentTotal >= MIN_SKILLS_COUNT; },
        incrementCount: function() {
            ++currentTotal;            
            log("project_skills", "skillcounter", "currentTotal: " + currentTotal);
            return currentTotal;
        },
        decrementCount: function() {
            --currentTotal;            
            log("project_skills", "skillcounter", "currentTotal: " + currentTotal);
            return currentTotal;
        },
        getCount: function() { return currentTotal; }
    };
}());
/* This closure will act like an enum, storing the action codes we will use to flag each 
 * skill so that the backend can understand what to do with the skill. */
var ACTIONCODES = (function() {
    var code_skill_new = 1,
            code_skill_old = 2,
            code_skill_edit = 3,
            code_skill_delete = 4;
    var currentActionMode = code_skill_new;
    return {
        SKILL_NEW: function() { return code_skill_new; },
        SKILL_OLD: function() { return code_skill_old; },
        SKILL_EDIT: function() { return code_skill_edit; },
        SKILL_DELETE: function() { return code_skill_delete; },
        getCurrentMode: function() { return currentActionMode; },
        setCurrentMode: function(mode) { currentActionMode = mode; }
    };
}());

/* Project Skills object template */
function ProjectSkills() {
    /* Structure of ProjectSkills will be as follows:
     * Integer indexed arrays (this.newskills, this.deletedskills, this.editedskills) 
     * of skill arrays (askill)
     * Each `askill` will be an array of key-value pairs, as follows.
     * `askill` array structure:
     *   skillid => The id of the skill
     *   ownerskill => Boolean TRUE if the project owner is taking this skill
     *   genres => array of integer ID's of genres. max count === 5
     *   influences => array of strings. max count === 5
     *   skilldesc => long string, optional skill description inputted by user
     * Any array may be empty, depending on what the user does,
     * deletedskills and editedskills will only be filled if user is 
     * editing an existing project. 
     */
    this.newskills = [];
    this.deletedskills = [];
    this.editedskills = [];
}
;
/* Store the ProjectSkills object. */
var obj_projectskills = new ProjectSkills();
/* Store the deleted skills for processing during serialization */
var list_deleted_skills = [];
/* Tracking flag, denotes when the import of old skills is complete. */
var bool_skillimport_completed = false;
var skills_left_to_import = -1;

/////////////////////////////////////////
/////	DOM STATE DEPENDENT CODE	/////
/////////////////////////////////////////

$(function() {

    // initialize the debug logger
    var loggingStage = ["project_skills"];
    debug_stages = debug_stages.concat(loggingStage);

    basket_selector = $('.skill-basket-toggle.active').data('mode');
    // Detach the input boxes for later use, must wait for the textext to initialize before detaching them.
    $(document).one('textext_genre_post', function() {
        obj_genreinput_container = $(selector_genreinput_container).detach();
    });
    $(document).one('textext_influence_post', function() {
        obj_influenceinput_container = $(selector_influenceinput_container).detach();
    });

    // Get the existing project skills from the backend, wait until skill and genre data is loaded.
    $(document).on('ajax_getProjectSkills_pre', function() {
        if (bool_data_genre_post && bool_data_skill_post) {
            $(document).off('ajax_getProjectSkills_pre');
            ajax_getProjectSkills();
        }
    });

    // setup the genre TextExt to wait for some AJAX data
    $(document).one('ajax_getAllGenres_post', function() {
        $(selector_genreinput_input).textext({
            plugins: 'autocomplete suggestions filter',
            suggestions: list_genre_names,
            filter: list_genre_names,
            autocomplete: {
                dropdownPosition: 'below',
                dropdownMaxHeight:	'125px'
            },
            ext: {
                core: {
                    onSetFormData: function(e, data) {
                        // Fixes issue where the hidden input field has double quotation marks
                        var self = this;
                        self.hiddenInput().val(data);
                        var userValue = self.hiddenInput().val();
                        // This is to prevent textext from giving me duplicate values - bug - need to keep another variable, preventing some inputs
                        var crudeHash = userValue + proj_skillCounter.getCount();
                        if (crudeHash !== last_userinput && crudeHash !== proj_skillCounter.getCount() && userValue != '') {
                            log('project_skills', 'onSetFormData', "The hidden field value is: " + self.hiddenInput().val());
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
        $(document).trigger('textext_genre_post');
    });
    try {
        $(selector_influenceinput_input).textext({
            plugins: 'ajax autocomplete suggestions filter',
            ajax: {
                url: getBaseURL('/dashboard/project/getInfluenceSuggestions'),
                dataType: "JSON",
                "dataCallback": function(query) {
                    log("project_skills", "AJAX", "The partial artist name is: " + query);
                    return {'partial': query};
                },
                loadingDelay: 1.0,
                loadingMessage: "Loading suggestions...",
                typeDelay: 0.50
            },
            autocomplete: {
                dropdownPosition: 'below',
                dropdownMaxHeight:	'110px'
            },
            ext: {
                core: {
                    onSetFormData: function(e, data) {
                        // Fixes issue where the hidden input field has double quotation marks
                        var self = this;
                        self.hiddenInput().val(data);
                        var userValue = self.hiddenInput().val();
                        // This is to prevent textext from giving me duplicate values - bug - need to keep another variable, preventing some inputs
                        var crudeHash = userValue + proj_skillCounter.getCount();
                        if (crudeHash !== last_userinput && crudeHash !== proj_skillCounter.getCount() && userValue != '') {
                            log('project_skills', 'onSetFormData', "The hidden field value is: " + self.hiddenInput().val());
                            last_userinput = crudeHash;
                            if (userValue !== "Who's the inspiration?" && userValue !== "Is that a new artist?") {
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
        $(document).trigger('textext_influence_post');
    } catch (e) {
    }
    $(document).one('ajax_getAllSkills_post', function() {
        var list_skill_names_copy = [];
        for (var iter = 0; iter < list_skill_names.length; iter++)
            list_skill_names_copy[iter] = list_skill_names[iter];
        $(selector_skillinput_input).textext({
            plugins: 'autocomplete suggestions filter',
            suggestions: list_skill_names_copy,
            filter: list_skill_names_copy,
            autocomplete: {
                dropdownPosition: 'below'
            },
            ext: {
                core: {
                    onSetFormData: function(e, data) {
                        // Fixes issue where the hidden input field has double quotation marks
                        var self = this;
                        self.hiddenInput().val(data);
                        var userValue = self.hiddenInput().val();
                        // This is to prevent textext from giving me duplicate values
                        var crudeHash = userValue + proj_skillCounter.getCount();
                        //if (crudeHash != last_userinput && crudeHash != proj_skillCounter.getCount()) {
                        if (userValue !== 0 && userValue !== last_userinput) {
                            log('project_skills', 'onSetFormData', "The hidden field value is: " + self.hiddenInput().val());
                            //last_userinput = crudeHash;
                            last_userinput = userValue;
                            ACTIONCODES.setCurrentMode(ACTIONCODES.SKILL_NEW());
                            processNewSkill(userValue);
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

    // start AJAX'ing for the skills and tag information
    ajax_getAllGenres();
    // DEBUG Get the skill data, maybe we will switch to ajax'ing the backend for skill names instead.
    ajax_getAllSkills();
    
    // Start up the listener to validate skill count.
    init_skillCountValidator();

    /////////////////////////////////////
    //////			Events			/////
    /////////////////////////////////////
    
    
    // Add another way to activate the My Saved Skills dropdown
    $('#saved-skills-toggler').click(function(event){
    	event.stopPropagation();
    	$('#saved-skills-toggle-btn').trigger('click');
    });

    // Toggling between My Skills and Team Skills
    $('.skill-basket-toggle').click(function() {
        var checkselector = $(this).data('mode');
        if (basket_selector !== checkselector) {
            // swap the active tab
            $('.skill-basket-toggle').each(function() {
                $(this).toggleClass('active');
            });
            // swap differentiating characteristics
            $('#skill-sidebar').toggleClass('bg-owner');
            $('#selected-skill-container').toggleClass('bg-owner');
            $('#skill-sidebar').toggleClass('bg-team');
            $('#selected-skill-container').toggleClass('bg-team');
            // Reorder the my team and team skills sections.
            var firstlist = $('.selected-skill-list-wrapper').first().detach();
            $('#selected-skill-container').append(firstlist);
            basket_selector = checkselector;
            log("project_skills", "skill-basket-toggle", "The basket-selector is: [" + basket_selector + "]");
        }
    });

    // Binding click action to open the list of skills for that specific skill category
    $('[data-type="skillcat"]').click(function() {
        last_category_selected_icon = $(this).children('img').attr('src');
        var categoryid = '#' + $(this).data('categoryid');
        $(categoryid).css({'visibility': 'visible'});
        // a timeout is necessary in order for the bubbled click event 
        // to expire before the one() bind takes effect.
        setTimeout(function() {
            $(document).one('click', function() {
                $(categoryid).css({'visibility': 'hidden'});
            });
        }, TIME_GENERICDELAY);
    });
    // Clicked on a specific skill from the skill list 
    $('[data-type="skilltile"]').click(function() {
        // Time gate the skill clicks so we don't get flooded with clicks.
        if (bool_permit_skillclick) {
            bool_permit_skillclick = false;
            setTimeout(function() {
                bool_permit_skillclick = true;
            }, TIME_SKILLSPAMDELAY);
            // Check if they have reached the maximum number of skills per project
            if (!proj_skillCounter.hitMaxCount()) {
                var clickedSkill = new SkillObj(
                        $(this).data('skillid'),
                        $(this).data('name'),
                        (last_category_selected_icon.length > 0) ? last_category_selected_icon : $(this).children('img').attr('src'));
                // reset the stored icon.
                last_category_selected_icon = '';
                // Process differently if we are adding a skill from the My Saved Skills dropdown.
                if ($(this).data('internal')) {
                	var datapackage = [];
                	datapackage.push({
                		genre : $(this).data('storedgenres'),
                		influences : $(this).data('storedinfluences'),
                		isOpen : false,
                		ownerskill : true,
                		skillid : clickedSkill.id,
                		projectSkillId : -1                		
                	});
                	toggleVisibilityOfOwnerSavedSkill(datapackage[0].skillid);
                	importExistingProjectSkills(datapackage, { internalImport : true });
                } else {
                	var destination = (basket_selector === 1) ? $(selector_owner_basket) : $(selector_team_basket);
                    // We need to bind the row activation to an event because we need to 
                    // wait for the row DOM element to initialize before calling further 
                    // actions on it.
                    $(document).one('appendNewSkill_post', function() {                    
                        log("project_skills", "skilltileClicked", "The new row id: " + currentRowID);
                        // Toggle activation state on the skill
                        if (currentRowID >= 0) {
                            toggleActiveRow(currentRowID);
                        }
                    });
                    ACTIONCODES.setCurrentMode(ACTIONCODES.SKILL_NEW());
                    appendNewSkill(clickedSkill, destination);
                }                
            } else {
                alert("You have reached the maximum of " + proj_skillCounter.getMaxLimit() + " skills!");
            }
        }
    });

    $("#publishProject").click(function() {
        serializeProjectSkills();
        var pid = $("#project-skills").data("projectid");
        var publishProjectUrl = getBaseURL("/ajax/project/createProjectSkills");
        var postData = {projectId: pid, publish: "1", projectSkills: obj_projectskills.newskills};
        if (isValidSkillCount()) {
        	$.post(publishProjectUrl, postData).done(function(responseObj) {
            	// Need check status of the update skill step
            	if(responseObj.errorcode == 0) {
            		window.location = getBaseURL("/dashboard/project/create_finish/"+pid);
            	} else {
            		alert(responseObj.message);
            	}
            });
        } else {
        	$(document).trigger('validate_skillcount');
        }
    });

    $("#editProjectSkill").click(function() {
        serializeProjectSkills();
        var pid = $("#project-skills").data("projectid");
        var publishProjectUrl = getBaseURL("/ajax/project/updateProjectSkills");
        var postData = {projectId: pid, newSkills: obj_projectskills.newskills, deletedSkills: obj_projectskills.deletedskills, editedSkills: obj_projectskills.editedskills};
        //alert(postData);
        if (isValidSkillCount()) {
        	$.post(publishProjectUrl, postData).done(function(response) {
            	if (parseInt(response.errorcode) === 0) {            		
            		alert("Successfully saved project information!");
            		// force page reload, true == reload from server to refresh our app state.
            		location.reload(true);
            	} else if (parseInt(response.errorcode) === 3) {
            		alert(response.message);
            	}            
                //window.location = location.protocol + "//" + location.host +"/dashboard/project/create_finish";
            });
        } else {
        	$(document).trigger('validate_skillcount');
        }
    });

});

/////////////////////////////////////////
/////		DOM INDEPENDENT CODE	/////
/////////////////////////////////////////

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
 * @param overrideEventName	If you want to override the default 'appendNewSkill_post' event trigger, specifiy it here.
 * @param bool_disableEdit 	If you want to disable editing of the skill, set this to TRUE.
 */
function appendNewSkill(skillObj, destinationObj, overrideEventName, bool_disableEdit, performerName) {
    var custom_eventname = (typeof overrideEventName !== 'undefined') ? overrideEventName : 'appendNewSkill_post';
    var bool_editdisabled = (typeof bool_disableEdit !== 'undefined') ? bool_disableEdit : false;
    var old_performerName = (typeof performerName !== 'undefined') ? performerName : '';
    var bool_ownerskill = false;
    if (old_performerName === 'da0wn3r') {
        old_performerName = '';
        bool_ownerskill = true;
    }
    var htmlarray = [];
    proj_skillCounter.incrementCount();
    var newRowID = ++currentRowID; //proj_skillCounter.getCount()-1;	
    // build the HTML code for the new skill row
    htmlarray.push('<li class="selected-skill-item" ');
    htmlarray.push('data-rowid="' + newRowID + '" data-skillid="' + skillObj.id + '" ');
    htmlarray.push('data-action="' + ACTIONCODES.getCurrentMode() + '" data-membername="' + old_performerName + '">');
    htmlarray.push('<div class="selected-skill-header">');
    htmlarray.push('<label class="skill-category-icon">');
    htmlarray.push('<img src="');
    //var imglink = location.protocol + "//" + location.hostname + "/" + skillObj.icon_path;
    var imglink = skillObj.icon_path;
    htmlarray.push(imglink + '"></label>');
    htmlarray.push('<span class="skill-name">' + skillObj.name + '</span>');
    if (old_performerName.length > 0) {
        htmlarray.push('<span class="performer-name">' + old_performerName + '</span>');
    }
    htmlarray.push('<label class="remove-skill-btn" data-rowid="' + newRowID + '" title="Remove">');
    htmlarray.push('<img src="');
    imglink = getBaseURL('/img/skill_icons/delete_img_grey.png');
    htmlarray.push(imglink + '"></label>');
    htmlarray.push('<label class="skill-action" data-rowid="' + newRowID);
    if (bool_editdisabled) {
        htmlarray.push('" data-mode="3">View</label></div>');
    } else {
        htmlarray.push('" data-mode="2">Edit</label></div>');
    }
    htmlarray.push('<div class="tag-block-wrap">');
    var collapse_or_not = (bool_editdisabled)? 'collapsed': '';
    htmlarray.push('<div class="tag-block"><div class="tag-header '+collapse_or_not +'" data-type="genre"></div>');
    htmlarray.push('<ul class="skill-tag-list"></ul></div>');
    htmlarray.push('<div class="tag-block"><div class="tag-header '+ collapse_or_not+ '" data-type="influence"></div>');
    htmlarray.push('<ul class="skill-tag-list"></ul></div><div class="cf"></div>');
    htmlarray.push('</div>');	// End of tag-block-wrap
    htmlarray.push('<div class="skill-description"><div class="skill-description-arrow"></div><textarea class="skill-description-input" rows="" cols="" placeholder="Need to be more specific? You can describe your needs here."');
    if (bool_editdisabled) {
        htmlarray.push('disabled="disabled"></textarea>');
    } else {
        htmlarray.push('></textarea>');
    }
    htmlarray.push('</div><div class="cf"></div></li>');
    var htmlstring = htmlarray.join('');
    log("project_skills", "appendNewSkill", "This is the generated HTML for the new skill: " + htmlstring);
    // append the HTML code for the new skill row
    $(destinationObj).prepend(htmlstring);
    // bind click listeners on the buttons, wait a bit for the DOM to initialize
    setTimeout(function() {
        /* Timeout is used to make sure the buttons are in the DOM before we try to bind
         * the click events to them. Since the row is not active yet, must select based 
         * on the data-rowid instead of on the .active class. */
        var newActive = $('.selected-skill-item[data-rowid="' + newRowID + '"]');
        $(newActive).data('inputstate', bool_editdisabled);
        var editbutton = $(newActive).find('.skill-action');
        var deletebutton = $(newActive).find('.remove-skill-btn');
        $(editbutton).click(function(e) {
            editToggle(e);
        });
        $(deletebutton).click(function(e) {
            // Do not allow deletion if this is a filled skill, mininum qty of skill check done in deleteSkill()
            if (bool_editdisabled && !bool_ownerskill) {
                alert("You cannot delete this skill until you remove the project member!");
            } else {
                deleteSkill(e);
            }
        });
        if (!$(newActive).find('textarea').attr('disabled')) {
        	validator_textarea({
    			jquery_target_obj : $(newActive).find('textarea'),
    			limit_min : 0,
    			limit_max : MAX_SKILL_DESC_CHARS,
    		});
    	}
        // trigger the appendNewSkill_post event to tell us to continue with DOM manipulation.
        $(document).trigger(custom_eventname);
        $(document).trigger('validate_skillcount');
    }, TIME_GENERICDELAY);
    // WAYLAN :: ah shit, toggleActivation is called right after this,  we may need to put off activation with a event bind
    return newRowID;
}

/**
 * @author Waylan Wong <waylan.wong@willrainit.com>
 * 
 * This function will detach a skill row from the DOM. It should be 
 * called either by an onClick= or a .click(function(e){});
 * 
 * @param self		This is the DOM element that triggered the click event. 
 */
function deleteSkill(self) {
    log("project_skills", "deleteSkill", "Clicked the delete skill button for: " + $(self.currentTarget).data('rowid'));
    if (confirm("Are you sure you want to remove this skill from your project?")) {
        // call toggleActiveRow to deactivate row and detach the tag input boxes
        toggleActiveRow($(self.currentTarget).data('rowid'));
        // decrement the skill counter
        proj_skillCounter.decrementCount();
        // detach the skill row.
        var deleteMe = $('.selected-skill-item[data-rowid="' + $(self.currentTarget).data('rowid') + '"]');
        var bool_isOwnerSkill = ($(deleteMe).parent().attr('id') == 'selected-skill-list-owner');
        /* Store the deleted skill because we need to tell the backend which ones are deleted.
         * Only store the old skills or old skills that were edited because the backend doesn't care 
         * about the new skills that are deleted. */
        var detached_deleteMe = $(deleteMe).detach();    
        if ($(detached_deleteMe).data('action') === ACTIONCODES.SKILL_OLD() || $(detached_deleteMe).data('action') === ACTIONCODES.SKILL_EDIT()) {
        	list_deleted_skills.push($(detached_deleteMe).data('pskillid'));
        }
        // Check if this is one of the user's saved skills, if so, show() the option so they can select it again
    	if (bool_isOwnerSkill) {
    		if ($('#savedskills').find('[data-skillid="'+$(detached_deleteMe).data('skillid')+'"][data-internal="1"]').css('display') === "none") {
    			toggleVisibilityOfOwnerSavedSkill($(detached_deleteMe).data('skillid'));
    		}		
    	}
        $(document).trigger('validate_skillcount');
    }
}

/**
 * @author Waylan Wong <waylan.wong@willrainit.com>
 * 
 * This function will toggle the visibility of one Owner Saved Skill
 * 
 * @param skillID The skill ID of the owner saved skill we wish to toggle.
 */
function toggleVisibilityOfOwnerSavedSkill(skillID) {
	var myskillid = (typeof skillID !== "undefined") ? skillID : -1;
	$('#savedskills').find('[data-skillid="'+myskillid+'"][data-internal="1"]').toggle();
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
    log("project_skills", "editToggle", "Clicked the toggle skill button for: " + $(self.currentTarget).data('rowid'));
    // call toggleActiveRow
    toggleActiveRow($(self.currentTarget).data('rowid'));
}

/**
 * @author Waylan Wong <waylan.wong@willrainit.com>
 * 
 * This function will toggle the activation state of the selected skill row.
 * When activated, the row will increase in height to reveal the tag blocks
 * as well as the search fields, and the skill comments.
 * When deactivated, the search fields will be detached and put into storage
 * in the #inputContainer, and the skill row will decrease in height to hide
 * the tag blocks and skill comments.
 * 
 * @param skillRowID	This is the skill row ID, accessed as an HTML5 data attribute
 * @param bool_disableInput		If true, then the genre and influence input boxes will not be availble.
 */
function toggleActiveRow(skillRowID, bool_disableInput) {
    var newActive = $('.selected-skill-item[data-rowid="' + skillRowID + '"]');
    // check if there is a currently active row
    var currentActive = $('.selected-skill-item.active');
    if (currentActive.length > 0) {
        // if an active row exists, we need to detach the tag input boxes.		
        if ($(selector_genreinput_container).length > 0) {
            obj_genreinput_container = $(selector_genreinput_container).detach();
        }
        if ($(selector_influenceinput_container).length > 0) {
            obj_influenceinput_container = $(selector_influenceinput_container).detach();
        }
        // if there is another active row, deactivate it
        if ($(currentActive).data('rowid') !== skillRowID) {
            var oldskillAction = $(currentActive).find('.skill-action');

            switch ($(oldskillAction).data('mode')) {
                case SKILLACTION_SAVE :
                    {
                        $(oldskillAction).data('mode', SKILLACTION_EDIT);
                        $(oldskillAction).html('Edit');
                        break;
                    }
                case SKILLACTION_EDIT :
                    {
                        $(oldskillAction).data('mode', SKILLACTION_SAVE);
                        $(oldskillAction).html('Save Changes');
                        break;
                    }
                case SKILLACTION_VIEW :
                    {
                        $(oldskillAction).data('mode', SKILLACTION_CLOSE);
                        $(oldskillAction).html('Close');
                        break;
                    }
                case SKILLACTION_CLOSE :
                    {
                        $(oldskillAction).data('mode', SKILLACTION_VIEW);
                        $(oldskillAction).html('View');
                        break;
                    }
            }
            $(currentActive).toggleClass('active');
        }
    }
    // check if this is a row can edit the tags.
    bool_disableInput = ($(newActive).data('inputstate') === true) ? true : false;

    // attach the tag input boxes to the right places, only if input is allowed.
    if (!bool_disableInput) {
        $(newActive).find(selector_genretagblock).append(obj_genreinput_container);
        $(newActive).find(selector_influencetagblock).append(obj_influenceinput_container);
    }

    // Change the displayed action text and mode depending on which mode it is in.	
    var skillAction = $(newActive).find('.skill-action');

    switch ($(skillAction).data('mode')) {
        case SKILLACTION_SAVE :
            {
                $(skillAction).data('mode', SKILLACTION_EDIT);
                $(skillAction).html('Edit');
                break;
            }
        case SKILLACTION_EDIT :
            {
                $(skillAction).data('mode', SKILLACTION_SAVE);
                $(skillAction).html('Save Changes');
                break;
            }
        case SKILLACTION_VIEW :
            {
                $(skillAction).data('mode', SKILLACTION_CLOSE);
                $(skillAction).html('Close');
                break;
            }
        case SKILLACTION_CLOSE :
            {
                $(skillAction).data('mode', SKILLACTION_VIEW);
                $(skillAction).html('View');
                break;
            }
    }
    $(newActive).toggleClass('active');
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
 * @param bool_disableDEL	If true, tag cannot be deleted.
 */
function appendNewTag(tagObj, skillRowID, bool_disableDEL) {
    var currentActive = $('.selected-skill-item.active');
    var tagblocklist;
    if (tagObj.type === TAGTYPE_GENRE) {
        tagblocklist = $(currentActive).find(selector_genretagblock).next();
    } else if (tagObj.type === TAGTYPE_INFLUENCE) {
        tagblocklist = $(currentActive).find(selector_influencetagblock).next();
    }
    // check if max tag count has been reached.
    if ($(tagblocklist).find('.skill-tag').length < MAX_SKILL_TAGS) {
        // Only add the tag if it doesn't exist in the list
        if ($(tagblocklist).find('[data-name="' + tagObj.name + '"]').length === 0) {
            var htmlarray = [];
            htmlarray.push('<li class="skill-tag" data-id="' + tagObj.id + '">');
            htmlarray.push('<label class="skill-tag-content" data-name="' + tagObj.name + '">' + tagObj.name + '</label>');
            if (!bool_disableDEL) {
                htmlarray.push('<label class="skill-tag-delete"></label>');
            }
            htmlarray.push('</li>');
            var htmlstring = htmlarray.join('');
            $(tagblocklist).append(htmlstring);
            // may need a timeout here before binding the action.
            $(tagblocklist).find('[data-name="' + tagObj.name + '"]').next().click(currentActive, function(e) {
            	// mark the skill as edited so we can separate it from the rest during serialization,
            	// but only if it already have a project skill id.
            	if (typeof $(e.data).data('pskillid') != "undefined") {            		
            		$(e.data).data('edited', 1);
            	}
                deleteTag(e);
            });
        }
        // mark the active skill edited, but only if this is not during old skill import, 
        // and only if it already has a project skill ID
        if ((ACTIONCODES.getCurrentMode() != ACTIONCODES.SKILL_OLD() || bool_skillimport_completed) 
        		&& typeof currentActive.data('pskillid') != "undefined") {
        	currentActive.data('edited', 1);
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
function processNewSkill(userInput) {
    // Check if they have reached the maximum number of skills per project
    if (!proj_skillCounter.hitMaxCount()) {

        // figure out which skill they want to add.
        var skilldata = list_skill_data[list_skill_names.indexOf(userInput)];
        if (skilldata) {
            // Add the new skill to the destination
            var image_src = $("label[data-categoryid='" + skilldata.categoryid + "'] img").attr('src');
            var clickedSkill = new SkillObj(skilldata.skillid, skilldata.skill_name, image_src);
            var destination = (basket_selector === 1) ? $(selector_owner_basket) : $(selector_team_basket);
            $(document).one('appendNewSkill_post', function() {
                // We need to bind the row activation to an event because we need to 
                // wait for the row DOM element to initialize before calling further 
                // actions on it.
                log("project_skills", "processNewSkill", "The new row id: " + currentRowID);
                // Toggle activation state on the skill
                if (currentRowID >= 0) {
                    toggleActiveRow(currentRowID);
                }
            });
            currentRowID = appendNewSkill(clickedSkill, destination);
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
    var myskills = [];
    var teamskills = [];
    var tempskills = [];
    var editedskills = [];
    var theskillid, theskilldesc, theactioncode, theprojskillid;
    var OWNERSKILL = true, TEAMSKILL = false;
    var DONE_MYSKILLS = false, DONE_TEAMSKILLS = false;

    myskills = $(selector_owner_basket + ' .selected-skill-item');
    teamskills = $(selector_team_basket + ' .selected-skill-item');

    $(myskills).each(function() {
        log('project_skills', "serializeProjectSkills", "mySkills actioncode: " + $(this).data('action'));
        if ($(this).data('action') === ACTIONCODES.SKILL_NEW()) {
            var tempgenres = [];
            var tempinfluences = [];
            theskillid = $(this).data('skillid');
            theactioncode = $(this).data('action');
            theskilldesc = $(this).find('.skill-description-input').val();
            $(this).find('.tag-header[data-type="genre"]').next().find('.skill-tag').each(function() {
                tempgenres.push($(this).data('id'));
            });
            $(this).find('.tag-header[data-type="influence"]').next().find('.skill-tag-content').each(function() {
                tempinfluences.push($(this).data('name'));
            });
            tempskills.push([{
                    ownerskill: OWNERSKILL,
                    skillid: theskillid,
                    actioncode: theactioncode,
                    genre: tempgenres,
                    influences: tempinfluences,
                    skilldesc: theskilldesc
                }]);
        }
        /* My Skills doesn't need to process "edited" skills because the user 
         * cannot edit any of the owner skills, they can only make new ones, 
         * or delete the old ones.
         */ 
    });

    $(teamskills).each(function() {
        log('project_skills', "serializeProjectSkills", "mySkills actioncode: " + $(this).data('action'));
        log('project_skills', "serializeProjectSkills", "mySkills membername: " + $(this).data('membername'));
        if (($(this).data('action') === ACTIONCODES.SKILL_NEW() || $(this).data('membername').length === 0)
        		&& typeof $(this).data('pskillid') === "undefined") {
            var tempgenres = [];
            var tempinfluences = [];
            theskillid = $(this).data('skillid');
            theactioncode = $(this).data('action');
            theskilldesc = $(this).find('.skill-description-input').val();
            $(this).find('.tag-header[data-type="genre"]').next().find('.skill-tag').each(function() {
                tempgenres.push($(this).data('id'));
            });
            $(this).find('.tag-header[data-type="influence"]').next().find('.skill-tag-content').each(function() {
                tempinfluences.push($(this).data('name'));
            });
            tempskills.push([{
                    ownerskill: TEAMSKILL,
                    skillid: theskillid,
                    actioncode: theactioncode,
                    genre: tempgenres,
                    influences: tempinfluences,
                    skilldesc: theskilldesc
                }]);
        } else if ($(this).data('edited') == 1) {
        	var tempgenres = [];
            var tempinfluences = [];
            theskillid = $(this).data('skillid');
            theactioncode = $(this).data('action');
            theskilldesc = $(this).find('.skill-description-input').val();
            $(this).find('.tag-header[data-type="genre"]').next().find('.skill-tag').each(function() {
                tempgenres.push($(this).data('id'));
            });
            $(this).find('.tag-header[data-type="influence"]').next().find('.skill-tag-content').each(function() {
                tempinfluences.push($(this).data('name'));
            });
            theprojskillid = $(this).data('pskillid');
        	editedskills.push({
        		ownerskill: TEAMSKILL,
                skillid: theskillid,
                actioncode: theactioncode,
                genre: tempgenres,
                influences: tempinfluences,
                skilldesc: theskilldesc,
                projectskillid: theprojskillid
        	});
        }
    });

    obj_projectskills.newskills = tempskills;
    obj_projectskills.editedskills = editedskills;
    obj_projectskills.deletedskills = list_deleted_skills;
}


/**
 * @author Waylan Wong <waylan.wong@willrainit.com>
 * 
 * We must be able to rebuild the page and rebind all the actions
 * for skills that have already been entered into the project 
 * and mark them as such. The data structure we expect is 
 * the same as ProjectSkills except, consolidated into one array.
 * 
 * @pre		The genre data must have been populated from the backend before we can proceed.
 * @param ajaxdata		The data from the backend in the same structure as ProjectSkills
 * @param advancedOptions	An object containing boolean switches such as "internalImport" 
 */
function importExistingProjectSkills(ajaxdata, advancedOptions) {
	
	var importedSkillCounter = 0, destination;
	skills_left_to_import = ajaxdata.length;
    // Ensure all the skills being added are marked as old/existing skills
    ACTIONCODES.setCurrentMode(ACTIONCODES.SKILL_OLD());
	
	if (typeof advancedOptions !== "undefined") {
		// if we are adding a skill from the My Saved Skills dropdown, we must start 
		// the skill counter, which controls the active row toggle, after the last 
		// row ID being used. We also need to set the action code to NEW because it 
		// is a new addition to the project skills, in order for the serializeProjectSkills()
		// to recognize that it should be saved.
		if (advancedOptions.internalImport) {
			importedSkillCounter = currentRowID + 1;
			ACTIONCODES.setCurrentMode(ACTIONCODES.SKILL_NEW());
		}
	}

    // Make a genre ID indexed mirror array so we can access the other genre data.
    var list_genre_data_by_id = [];
    $(list_genre_data).each(function() {
        list_genre_data_by_id.push(this.id);
    });

    var image_src, clickedSkill, skill_name, skill_obj, category_id;
    var aSkill;
    $(ajaxdata).each(function() {
        aSkill = this;
        skill_obj = $('[data-skillid="' + aSkill.skillid + '"][data-type="skilltile"]');
        skill_name = $(skill_obj).data('name');
        if ($(skill_obj).data('internal')) {
            // This is one of the "special" skills
            image_src = $(skill_obj).find('img').attr('src');
        } else {
            category_id = $(skill_obj).parent().parent().attr('id');
            image_src = $("label[data-categoryid='" + category_id + "'] img").attr('src');
        }
        clickedSkill = new SkillObj(aSkill.skillid, skill_name, image_src);
        destination = (aSkill.ownerskill) ? $(selector_owner_basket) : $(selector_team_basket);
        aSkill.operatingRowID = importedSkillCounter;
        aSkill.bool_disableDetails = true; // controlls the tags and skill description
        aSkill.bool_disableDelete = true; // controlls deletion of entire skill
        /* Determine which sections we need to disable editing on. */
        if (aSkill.ownerskill) {
            aSkill.performerName = 'da0wn3r'; // yay, nonsense names.			
        } else if (!aSkill.ownerskill && aSkill.isOpen) {
            aSkill.bool_disableDetails = false; // controlls the tags and skill description
            aSkill.bool_disableDelete = false; // controlls deletion of entire skill
        }

        $(document).one('appendNewSkill_post' + importedSkillCounter, aSkill, function(e) {
            aSkill = e.data;

            // We need to bind the row activation to an event because we need to 
            // wait for the row DOM element to initialize before calling further 
            // actions on it.
            log("project_skills", "importExistingProjectSkills", "The new row id: " + aSkill.operatingRowID);
            if (aSkill.operatingRowID >= 0) {
                toggleActiveRow(aSkill.operatingRowID, aSkill.bool_disableDetails);
            }
            // now process the tags - tags can only be added to the currently active row, thus the reason for activating it
            $(aSkill.genre).each(function() {
                tagdata = list_genre_data[list_genre_data_by_id.indexOf(parseInt(this))];
                if (tagdata) {
                    var newtag = new TagObj(tagdata.id, tagdata.name, TAGTYPE_GENRE);
                    appendNewTag(newtag, aSkill.operatingRowID, aSkill.bool_disableDetails);
                }
            });
            $(aSkill.influences).each(function(index) {
                var newtag = new TagObj(-1, aSkill.influences[index], TAGTYPE_INFLUENCE);
                appendNewTag(newtag, aSkill.operatingRowID, aSkill.bool_disableDetails);
            });
            var currentOperatingRow = $('[data-rowid="' + aSkill.operatingRowID + '"]');
            // restore the textarea.
            var mytextarea = currentOperatingRow.find('textarea');
            mytextarea.val(aSkill.skilldesc);
            /* make sure to mark the skill as edited if the textarea was changed.
             * The simplest solution is to mark it as edited if the textarea was clicked
             * inside. The other solution is to somehow track the contents of the textarea
             * and then check if the contents were changed. Too much cost for too little benefit. */
            mytextarea.focus(currentOperatingRow, function(event) {
            	currentOperatingRow.data('edited', 1);
            });
            // append the projectSkillId if it exists, so that later the backend can find the right skill
            currentOperatingRow.data('pskillid', aSkill.projectSkillId);
                                    
            // adjust the "edited" tag in case we are not allowing tag edits.
            if (aSkill.bool_disableDetails) {
            	currentOperatingRow.data('edited', -1);
            }
            
            // check if we have finished importing skills:
            if (--skills_left_to_import <= 0) {
            	skills_left_to_import = 0;
            	bool_skillimport_completed = true;
            }

        });        
        appendNewSkill(clickedSkill, destination, 'appendNewSkill_post' + importedSkillCounter,
                aSkill.bool_disableDelete, aSkill.performerName);
        ++importedSkillCounter;
    });
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
    $.get(getBaseURL("/dashboard/project/getGenreTags"), {}, function(data) {
        if (data.length > 0) {
            list_genre_data = data[0];
            list_genre_names = data[1];
            bool_data_genre_post = true;
            $(document).trigger('ajax_getProjectSkills_pre');
            $(document).trigger('ajax_getAllGenres_post');
        } else {
            log('project_skills', "ajax_getAllGenres",
                    "Did not get any genres.");
        }
    }, "JSON");
}


/**
 * @author Waylan Wong <waylan.wong@willrainit.com>
 * 
 * DEVELOPMENT - AJAX function to grab the list of skill data This will fire the
 * 'ajax_getAllSkills_post' event when it completes.
 */
function ajax_getAllSkills() {
    $.get(getBaseURL("/dashboard/project/getAllSkillData"), {}, function(data) {
        if (data.length > 0) {
            list_skill_data = data[0];
            list_skill_names = data[1];
            bool_data_skill_post = true;
            $(document).trigger('ajax_getProjectSkills_pre');
            $(document).trigger('ajax_getAllSkills_post');
        } else {
            log('project_skills', "ajax_getAllSkills_post",
                    "Did not get any skill data.");
        }
    }, "JSON");
}

function ajax_getProjectSkills() {
    $.post(getBaseURL("/ajax/project/getProjectSkills"), {projectId: $("#project-skills").data("projectid")}, function(data) {
        if (data.length > 0) {
            importExistingProjectSkills(data);
        } else {
            log('project_skills', "ajax_getProjectSkills", "Did not get any project skill data.");
        }
    }, "JSON");
}

/////////////////////////////////////////////
/////		Validation Processors		/////
/////////////////////////////////////////////


/**
 * @author Waylan Wong <waylan.wong@willrainit.com>
 * 
 * This function will bind a listener to the specified textarea to track 
 * how many characters are in the textarea. Deviation above the max and 
 * below the min will trigger an error message to be displayed
 * 
 * Advanced options to be specified in JSON format in advOptions
 * @param jquery_target_obj	The jQuery object of the textarea
 * @param limit_min	Minimum number of characters
 * @param limit_max Maximum number of characters
 * @param error_message A custom error message.
 * 
 */
function validator_textarea(advOptions) {
	if (typeof advOptions === "undefined") {
		return false;
	}
	var target;
	if (typeof advOptions.jquery_target_obj === "undefined") {
		return false;
	} else if (!advOptions.jquery_target_obj.is('textarea')) {
		return false;
	} else {
		target = advOptions.jquery_target_obj;
	}	
	var min = (typeof advOptions.limit_min === "undefined") ? 0 : advOptions.limit_min;
	var max = (typeof advOptions.limit_max === "undefined") ? 255 : advOptions.limit_max;	
	if (min >= max) { return false; }
	var error_msg = (typeof advOptions.error_message === "undefined") ? "Please keep your input between " + min + " and " + max + " characters." : advOptions.error_message; 
		
	// Setup the closure that will be kept alive and keeping track of the data per textarea.
	var validityCheck = (function(){
		var mytarget, mymin, mymax, myerror_message, myerror_label;
		
		return {
			setData : function(_target, _min, _max, _error_msg) {
				mytarget = _target,		
				mymin = _min;
				mymax = _max;	
				myerror_message = _error_msg;
				myerror_label = '<label class="error error-message">' + myerror_message + '</label>';
			},
			isValid : function() { 
				if ($(mytarget).val().length < mymin) {
					if (!$(mytarget).hasClass('error')) {
						$(mytarget).addClass('error');
						$(mytarget).after(myerror_label);
					}					
					return false;
				} else if ($(mytarget).val().length > mymax) {
					if (!$(mytarget).hasClass('error')) {
						$(mytarget).addClass('error');
						$(mytarget).after(myerror_label);
					}
					return false;
				} else {
					if ($(mytarget).hasClass('error')) {
						$(mytarget).removeClass('error');
						$(mytarget).next('.error-message').remove();
					}
					return true;
				}
			}
		};
	}());	
	validityCheck.setData(target, min, max, error_msg);
	
	$(target).keyup(validityCheck, function() {
		validityCheck.isValid();
	});
	
	return true;
	
}

/**
 * @author Waylan Wong <waylan.wong@willrainit.com>
 * 
 * This function will bind listener to the document level that will 
 * check for the proper amount of skills in the team and owner sections 
 * whenever it is triggered. It should be triggered whenever a skill is 
 * added or removed. The event name to trigger is 'validate_skillcount'
 */
function init_skillCountValidator() {
	$(document).on('validate_skillcount', function() {
		
		// Check the number of owner skills
		if ($(selector_owner_basket).find('.selected-skill-item').length < MIN_SKILL_COUNT_OWNER) {
			$('#alert_myskills').css({visibility : 'visible'});
			$('#help_myskills').popover('show');
			$(selector_owner_basket).parent().css({"margin-bottom": '150px'});
			$(selector_owner_basket).prev().show();
		} else {
			$('#alert_myskills').css({visibility : 'hidden'});
			$('#help_myskills').popover('hide');
			$(selector_owner_basket).parent().css({"margin-bottom": '75px'});
			$(selector_owner_basket).prev().hide();
		}
		// Check the number of team skills
		if ($(selector_team_basket).find('.selected-skill-item').length < MIN_SKILL_COUNT_TEAM) {
			$('#alert_teamskills').css({visibility : 'visible'});
			$('#help_teamskills').popover('show');
			$(selector_team_basket).parent().css({"margin-bottom": '150px'});
			$(selector_team_basket).prev().show();
		} else {
			$('#alert_teamskills').css({visibility : 'hidden'});
			$('#help_teamskills').popover('hide');
			$(selector_team_basket).parent().css({"margin-bottom": '75px'});
			$(selector_team_basket).prev().hide();
		}		
	});	
	$(document).trigger('validate_skillcount');
}

/**
 * @author Waylan Wong <waylan.wong@willrainit.com>
 * 
 * This function will check if we are within the prescribed skill count limits 
 * will will return a boolean result.
 * 
 * @returns {Boolean}
 */
function isValidSkillCount() {
	var bool_sufficient_owner_skills = false;
	var bool_sufficient_team_skills = false;
	var bool_within_max_skills = false;
	if ($('.selected-skill-item').length <= proj_skillCounter.getMaxLimit()) {
		bool_within_max_skills = true;
	}
	if ($(selector_owner_basket).find('.selected-skill-item').length >= MIN_SKILL_COUNT_OWNER) {
		bool_sufficient_owner_skills  = true;
	}
	if ($(selector_team_basket).find('.selected-skill-item').length >= MIN_SKILL_COUNT_TEAM) {
		bool_sufficient_team_skills = true;
	}		
	return (bool_within_max_skills && bool_sufficient_owner_skills && bool_sufficient_team_skills);
}




