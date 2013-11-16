/**
 * This part initialize the customized dropdown menu
 * It activate the next option when selected
 */

var texttext_init_text;
var last_userinput;
var textextinput;
var normalinput;
var textflag = 1;
var genderFilter = 0;
var minExpFilter = 0;
var maxExpFilter = 45;

var list_genre_data;
var list_genre_names;
/* store the list of influences in a flat array. */
var list_influence_names;
var list_influence_data;
/* store the parallel lists of skill data and skill names */
var list_skill_data;
var list_skill_names;
/* flags for when the lists are successfully ajax'ed from the backend */
var bool_data_genre_post = false;
var bool_data_skill_post = false;

var searchFlag = 0;
var initFlag = 0;
var showMatchFlag = 0;
function createSearchObj(sid, cid) {
	this.skillId = sid;
	this.categoryId = cid;
	this.genres = [];
	this.influences = [];
};

var searchOBJ = new createSearchObj(-1, -1);

$(function() {

	// Google location on the zipcode input box
	googleLocationInit($('#zipcode'));

	normalinput = $("#normalinput").detach();

	hiddenSkills = $("#hiddenSkills").detach();
	hiddenGenres = $("#hiddenGenres").detach();
	hiddenInfluences = $("#hiddenInfluences").detach();
	$('.back_to_top').hide();
	var dropdown = $(".fms_dropdown_container");
	for (var i = 0; i < dropdown.length; i++) {
		if ($(dropdown[i]).data("select-inactive") !== 1)
			dropdownMenuInit(dropdown[i]);
	}

	$("#select_project li").click(function() {
		var projectId = $(this).data('projectid');
		getProjectSkills(projectId);
	});

	$(".project-photo-hover").click(function() {
		$(this).next().trigger('play');
	});

	$("#searchByProjectNeed").click(function() {
		if (searchFlag === 0) {
			return false;
		} else {
			searchByProjectNeed();
		}
	});

	$("#clear_genre").click(clearGenre);
	$("#clear_influence").click(clearInfluence);

	$("#slimScroll").slimScroll({
		height : '250px'
	});

	$("#generalSearch").click(function() {
		generalSearch($("#searchBar input").val());
	});

	$('#genderdiv label').click(function() {
		genderFilter = $(this).data('value');
	});

	$("#searchBy li").click(function() {
		var selected = $(this).text();
		switch (selected) {
			case "Skills":
				if (textflag == 0) {
					$("#normalinput").remove();
					textextinput.insertAfter("#searchBy");
				}
				reconstructArray(texttext_init_text, list_skill_names);
				textflag = 1;
				break;
			case "Genres":
				if (textflag == 0) {
					$("#normalinput").remove();
					textextinput.insertAfter("#searchBy");
				}
				reconstructArray(texttext_init_text, list_genre_names);
				textflag = 1;
				break;
			case "Influences":
				if (textflag == 0) {
					$("#normalinput").remove();
					textextinput.insertAfter("#searchBy");
				}
				reconstructArray(texttext_init_text, list_influence_names);
				textflag = 1;
				break;
			case "Name":
				if (textflag == 1) {
					$("#textextinput").val("");
					textextinput = $("#textextinput").detach();
					normalinput.insertAfter("#searchBy");
				}
				textflag = 0;
				showMatchFlag = 0;
				$("#normalinput").keyup(function(event) {
					if (event.keyCode == 13) {
						generalSearch($("#normalinput").val());
					}
				});
				break;
			case "Project Tags":
				if (textflag == 1) {
					$("#textextinput").val("");
					textextinput = $("#textextinput").detach();
					normalinput.insertAfter("#searchBy");
				}
				textflag = 0;
				$("#normalinput").keyup(function(event) {
					if (event.keyCode == 13) {
						generalSearch($("#normalinput").val());
					}
				});
				break;
		}

	});
	$(".view_profile").click(function() {
		window.open($(this).data('url'));
	});

	//=====================================================================textext init section=========================================================
	$(document).one('ajax_getAllSkills_post', function() {
		texttext_init_text = [];
		for (var iter = 0; iter < list_skill_names.length; iter++)
			texttext_init_text[iter] = list_skill_names[iter];
		$("#textextinput").textext({
			plugins : 'autocomplete suggestions filter',
			suggestions : texttext_init_text,
			filter : texttext_init_text,
			autocomplete : {
				dropdownPosition : 'below'
			},
			ext : {
				core : {
					onSetFormData : function(e, data) {
						// Fixes issue where the hidden input field has double quotation marks
						var self = this;
						self.hiddenInput().val(data);
						var userValue = self.hiddenInput().val();
						//if (crudeHash != last_userinput && crudeHash != proj_skillCounter.getCount()) {
						if (userValue !== 0 && userValue !== last_userinput) {
							log('project_skills', 'onSetFormData', "The hidden field value is: " + self.hiddenInput().val());
							//last_userinput = crudeHash;
							last_userinput = userValue;
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
		textextinput = $("#textextinput").detach();
		textextinput.insertAfter("#searchBy");
	});

	ajax_getAllSkills();
	ajax_getAllGenres();
	ajax_getAllInfluences();

	$('#apply_filter').click(filterResults);

	// Initialize the experience slider bar
	$('#project_experience').data('expmin', 0);
	$('#project_experience').data('expmax', 45);
	$('#project_experience').slider({
		min : 0,
		max : 45,
		values : [0, 45],
		orientation : "horizontal",
		range : true,
		slide : function(event, ui) {
			$('#project_experience').find(".ui-slider-value:first").text(ui.values[0]).end().find(".ui-slider-value:last").text(ui.values[1]);

			// Change the data value in the slider
			var expmin = $('#project_experience .ui-slider-value.first').text();
			var expmax = $('#project_experience .ui-slider-value.last').text();
			minExpFilter = expmin;
			maxExpFilter = expmax;
			$('#project_experience').data('expmin', expmin);
			$('#project_experience').data('expmax', expmax);
		}
	});

	$('#sortBy li').click(function() {
		sortResults($(this).data('search-id'));
	});

	getRandomUser();

});

function getRandomUser() {
	$.get(getBaseURL("/users/search/getRandomUser"), {}, function(response) {
		if (response.length > 0) {
			displayResult(response);
		} else {
			// log('project_skills', "ajax_getAllSkills_post",
			// "Did not get any skill data.");
		}
	}, "JSON");

}

function ajax_getAllSkills() {
	$.get(getBaseURL("/dashboard/project/getAllSkillData"), {}, function(data) {
		if (data.length > 0) {
			list_skill_data = data[0];
			list_skill_names = data[1];
			bool_data_skill_post = true;
			//$(document).trigger('ajax_getProjectSkills_pre');
			$(document).trigger('ajax_getAllSkills_post');
		} else {
			// log('project_skills', "ajax_getAllSkills_post",
			// "Did not get any skill data.");
		}
	}, "JSON");
}

function ajax_getAllGenres() {
	$.get(getBaseURL("/dashboard/project/getGenreTags"), {}, function(data) {
		if (data.length > 0) {
			list_genre_data = data[0];
			list_genre_names = data[1];
			bool_data_genre_post = true;
			//$(document).trigger('ajax_getProjectSkills_pre');
			//$(document).trigger('ajax_getAllGenres_post');
		} else {
			// log('project_skills', "ajax_getAllGenres",
			// "Did not get any genres.");
		}
	}, "JSON");
}

function ajax_getAllInfluences() {
	$.get(getBaseURL("/dashboard/project/getAllInflunces"), {}, function(data) {
		if (data.length > 0) {
			list_influence_data = data[0];
			list_influence_names = data[1];
			//$(document).trigger('ajax_getProjectSkills_pre');
			//$(document).trigger('ajax_getAllGenres_post');
		} else {
			// log('project_skills', "ajax_getAllGenres",
			// "Did not get any genres.");
		}
	}, "JSON");
}

function processNewSkill(userValue) {
	//alert(userValue);
	if (userValue == "") {
		return;
	}
	generalSearch(userValue);
}

/**
 * Get Skills of a specified project
 * This function get skills of the project, update the skill dropdown
 * menu, and enable it.
 *
 * @param string projName
 */
function getProjectSkills(projectId) {
	// AJAX and update the select_skill dropdown list

	// Enable the dropdown list
	$("#select_skill").removeClass("inactive");
	$("#select_skill p span:first-child").text("Select Skill");
	var pidStr = "#projectId" + projectId;
	var projectSkills = hiddenSkills.find(pidStr);
	$('#skillSelect').append(projectSkills);
	if (initFlag == 0) {
		initFlag = 1;
		dropdownMenuInit($("#select_skill"));
	}

	$("#select_skill li").click(function() {
		var choice = $(this).text();
		var projectSkillId = $(this).data('projectskillid');
		var skillId = $(this).data('skillid');
		var categoryId = $(this).data('categoryid');
		searchOBJ = new createSearchObj(skillId, categoryId);
		searchFlag = 1;
		getSkillOptions(projectSkillId);
		$('#skill_style').fadeIn();
	});

}

/**
 * Get options of selected skill
 * This function get options of the selected skill, update the tags
 * and enable onclick event on them.
 *
 * @param integer projectSkillId    id of the project skill
 */
function getSkillOptions(projectSkillId) {
	// Remove all the tags
	$('#genreUL').children().remove();
	$('#influenceUL').children().remove();

	var str1 = "#influenceSkillId" + projectSkillId;
	var str2 = "#genreSkillId" + projectSkillId;
	hiddenInfluences.find(str1).clone().appendTo('#influenceUL');
	hiddenGenres.find(str2).clone().appendTo("#genreUL");

	// AJAX and update the genres and influence list
	// Enable the on click event of new tags

	$("#skill_style .first-icon.fui-checkbox-checked").click(function() {
		var genreId = $(this).data('genreid');
		var influenceId = $(this).data('influenceid');
		$(this).toggleClass('fui-checkbox-checked');
		$(this).toggleClass('fui-checkbox-unchecked');
		$(this).toggleClass('selected');
		if ($(this).hasClass('fui-checkbox-unchecked')) {
			if (genreId) {
				var index = searchOBJ.genres.indexOf(genreId);
				searchOBJ.genres.splice(index, 1);
			} else {
				var index = searchOBJ.influences.indexOf(influenceId);
				searchOBJ.influences.splice(index, 1);
			}
		} else {
			if (genreId) {
				searchOBJ.genres.push(genreId);
			} else {
				searchOBJ.influences.push(influenceId);
			}
		}

	});
}

function clearGenre() {
	$('#filter_genre .first-icon.selected').removeClass('fui-checkbox-checked');
	$('#filter_genre .first-icon.selected').addClass('fui-checkbox-unchecked');
	$('#filter_genre .first-icon.selected').toggleClass('selected');
	searchOBJ.genres.length = 0;
}

function clearInfluence() {
	$('#filter_influence .first-icon.selected').removeClass('fui-checkbox-checked');
	$('#filter_influence .first-icon.selected').removeClass('fui-checkbox-unchecked');
	$('#filter_influence .first-icon.selected').toggleClass('selected');
	searchOBJ.infuences.length = 0;
}

/**
 * This function is used to search by Project Need
 * @returns {undefined}
 */
function searchByProjectNeed() {
	$.ajax({
		type : 'POST',
		url : location.protocol + '//' + location.hostname + '/users/search/searchByProjectSkill',
		data : {
			skillId : searchOBJ.skillId,
			categoryId : searchOBJ.categoryId,
			genres : searchOBJ.genres,
			influences : searchOBJ.influences
		}
	}).done(function(responseObj) {
		// Update and display the header
		updateResultCount(responseObj);

		// Display the results
		displayResult(responseObj);
	});
}

/**
 * This function is used for basic / general search
 * @returns {undefined}
 */
function generalSearch(searchTerm) {
	//var searchTerm = $("#searchInput").val().trim();
	//alert(searchTerm+"   "+$("#searchBy").data('selected'));
	if (searchTerm == "") {
		return;
	}
	$.ajax({
		type : 'POST',
		url : location.protocol + '//' + location.hostname + '/users/search/generalSearch',
		data : {
			searchBy : $("#searchBy").data('selected'),
			keywords : searchTerm
		}
	}).done(function(responseObj) {
		// Update and display the header
		updateResultCount(responseObj);

		// Display results
		displayResult(responseObj);
	});
}

function updateResultCount(responseObj) {
	$('#result_count').empty();
	if ( typeof (responseObj) == 'object') {
		$('#result_count').append(responseObj.length + ' Results Found');
		$('.back_to_top').show();
		$('#sortBy').show();
	}
	if (responseObj.length == 0) {
		$('#result_count').html('0 Results Found');
		$('.back_to_top').hide();
		$('#sortBy').hide();
	}
	$('#result_header_placeholder').hide();
	$('#result_header').fadeIn();
}

/**
 * This function is responsible for the actual musician search results.
 *
 * @param {type} responseObj
 * @returns {undefined} */

function displayResult(responseObj) {
	$('#musician_search_result').children().remove();
	for (var i = 0; i < responseObj.length; i++) {
		var lastLoginTime = new Date(parseInt(responseObj[i].lastActive) * 1000);
		var lastLoginDate = (lastLoginTime.getUTCMonth() + 1) + '/' + lastLoginTime.getUTCDate() + '/' + lastLoginTime.getUTCFullYear();
		var location = [];
		if (responseObj[i].city) {
			location[location.length] = responseObj[i].city;
		}
		if (responseObj[i].country) {
			location[location.length] = responseObj[i].country;
		}
		var result = '';
		result += '<li ' + 'data-name="' + responseObj[i].firstName + responseObj[i].lastName + '" ' + 'data-experience="' + responseObj[i].numOfProjects + '" ' + 'data-match="' + responseObj[i].match + '" ' + 'data-lastactive="' + responseObj[i].lastActiveSort + '" ' + 'data-gender="' + responseObj[i].gender + '" ' + 'data-language="' + responseObj[i].language + '" ' + 'data-city="' + responseObj[i].city + '" ' + 'data-state="' + responseObj[i].state + '" ' + ' >';
		result += '<div class="project_info"><div class="project_content"><label class="project_play"><img src="';
		result += responseObj[i].profilePic;
		result += '"/><div class="project_photo_hover"></div><audio id="';
		result += 'audio' + i;
		result += '" height="100" width="100"><source data-src="';
		result += responseObj[i].audioUrl;
		result += '" type="audio/mp3"></audio></label><div class="project_detail_info"><div id="';
		result += responseObj[i].userId;
		result += '" class="project_title"><a href="' + responseObj[i].profileUrl + '">';
		result += responseObj[i].name;
		result += '</a></div><div class="project_location">';

		result += location.join(', ');
		result += '</div><div class="project_msg_audition"><button class="message" data-btn="msg" data-userid="';
		result += responseObj[i].userId;
		result += '" ><i class="fui-mail"></i></button></div>';
		result += '<div class="user_intro">“' + responseObj[i].biography + ' ” ';
		if (responseObj[i].moreFlag == 1) {
			result += '<a href="' + responseObj[i].profileUrl + '">more</a>';
		}
		result += '</div>';

		result += '<div class="save_message">';
		result += '<p>Last Active ';
		result += lastLoginDate;
		result += '</p><p>Worked on ';
		result += responseObj[i].numOfProjects;
		result += ' Projects</p></div></div></div>';
		if (showMatchFlag) {
			result += '<div class="project_details"><div class="match_rating">match';
			result += '<div class="rating_value">';
			result += responseObj[i].match;
			result += '<span>%</span></div></div>';
		}
		//result += responseObj[i].profileUrl;
		result += '</div></div><hr/></li>';

		$('#musician_search_result').append(result);
	}

	$('[data-btn="msg"]').click(msgPopup);
	$('.project_photo_hover').each(function() {
		var audio_src = $(this).parent().find('source').data("src");
		if (audio_src != 0) {
			$(this).hover(function() {
				$(this).css({
					'opacity' : 1
				});
			}, function() {
				$(this).css({
					'opacity' : 0
				});
			});

			$(this).click(function() {
				// Close other audio
				var otherAudio = $('audio');
				for (var i = 0; i < otherAudio.length; i++) {
					if ($(otherAudio[i]).attr('id') != $(this).next().attr('id') && !otherAudio[i].paused) {
						$(otherAudio[i]).prev().removeClass('pause');
						otherAudio[i].pause();
					}
				}

				// Play or pause the audio
				var audioObj = $(this).parent().find('audio');
				var paused = audioObj[0].paused;
				var audioState = audioObj[0].readyState;
				if (!paused) {
					$(this).removeClass("pause");
					audioObj[0].pause();
					return;
				}
				var srcObj = $(this).parent().find('source');				

				// If the audio is not loaded yet
				if (audioState == 0) {
					srcObj.attr('src', srcObj.data('src'));
					audioObj[0].load();
					audioObj.on('canplay canplaythrough', function() {
						$(this).parent().find('.project_photo_hover').addClass("pause");
						audioObj[0].play();
					});
				}

				// If the audio is loaded
				if (audioState > 1) {
					$(this).parent().find('.project_photo_hover').addClass("pause");
					audioObj[0].play();
				}
			});
		} else {
			// There is no audio source, we must remove the cursor:pointer so the user doesn't think it's clickable
			$(this).parent().css({
				"cursor" : 'default'
			});
		}
	});
	// end of the $.each() loop processing each search result for audio player
}

/**
 * This function applies the filters to the Advanced Musician Search.
 *
 * @returns {undefined}
 */
function filterResults() {
	var filter = [];

	// Project experiences
	//filter['min_projects'] = $('#slider_start_hint').text();
	//filter['max_projects'] = $('#slider_end_hint').text();

	// filter['min_projects'] = 0;
	// filter['max_projects'] = 10000;

	// City
	var city = $('#zipcode').val();
	if (city) {
		filter['city'] = city.toUpperCase();
	} else {
		filter['city'] = false;
	}

	// Language
	var language = $('#dropdown_language').data('selected');
	if (language) {
		filter['language'] = language.toUpperCase();
	} else {
		filter['language'] = false;
	}

	// State
	var state = $('#dropdown_state').data('selectedid');
	if (state) {
		filter['state'] = state.toUpperCase();
	} else {
		filter['state'] = false;
	}

	// Check for each item
	var results = $('#musician_search_result li');
	for (var i = 0; i < results.length; i++) {
		var display = 1;

		//Check project experience
		var projects = $(results[i]).data('experience');
		if (projects < minExpFilter || projects > maxExpFilter) {
			display = 0;
		}

		// Check country
		var result_state = $(results[i]).data('state');
		if (filter['state'] && result_state.toUpperCase() != filter['state']) {
			display = 0;
		}

		// Check city
		var result_city = $(results[i]).data('city');
		if (result_city) {
			if (filter['city'] && result_city.toUpperCase() !== filter['city']) {
				//alert('city1');
				display = 0;
			}
		} else {
			if (filter['city']) {
				//alert('city1');
				display = 0;
			}
		}

		// Check language
		var result_language = $(results[i]).data('language');
		if (result_language) {
			if (filter['language'] && result_language.toUpperCase() !== filter['language']) {
				//alert('language1');
				display = 0;
			}
		} else {
			if (filter['language']) {
				//alert('language2');
				display = 0;
			}
		}

		//Check gender
		if (genderFilter != 0 && $(results[i]).data('gender') != genderFilter) {
			display = 0;
		}

		// Show or hide each result
		if (display === 1) {
			$(results[i]).fadeIn();
		} else {
			$(results[i]).hide();
		}
	}
}

function reconstructArray(originalArray, newArray) {
	originalArray.splice(0, originalArray.length);
	for (i in newArray) {
		originalArray.push(newArray[i]);
	}
}

function sortResults(type) {
	switch(type) {
		case 1:
			$("#musician_search_result > li").tsort({
				data : 'lastactive',
				order : 'desc'
			});
			break;
		case 2:
			$("#musician_search_result > li").tsort({
				data : 'name',
				cases : false
			});
			break;
		case 3:
			$("#musician_search_result > li").tsort({
				data : 'experience',
				order : 'desc'
			});
			break;
	}
}
