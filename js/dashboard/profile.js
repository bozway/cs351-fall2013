$(document).ready(function() {

	$('#show_basic_settings').click(function() {
		$('[data-id="profile_basic_settings"]').trigger('click');
	});

	basicSettingInit();

	$("link:eq(3)").attr("href", "");
	$("ul[class=container] li:even").addClass("firstsp");
	$('#project_portfolio_list').sortable({
		out : function(event, ui) {
			$(this).find("li").removeClass("firstsp");
			$(this).find("li:even").addClass("firstsp");
		}
	});

	$('#skill_column').sortable({
		handle : '.dropdownlistimg'
	});

	$(".eye").click(function() {
		var visibility = $(this).attr('id');
		if (visibility == "1") {
			$(this).attr("src", getBaseURL("/img/eye_grey.png"));
			$(this).attr('id', "0");
		} else {
			$(this).attr("src", getBaseURL("/img/eye_blue.png"));
			$(this).attr('id', "1");
		}

	});

	ajax_getUserBiography();

	$('.icon-remove').each(function() {
		$(this).click(function() {
			var row_id = $(this).attr('id');
			$('#' + row_id).parent().parent().detach();
		});
	});

	$('#list-header').parent().hide();

	$('input[name=group1]').click(function() {
		var checkedVal = $(this).attr('id');
		var i = 0;

		$('#list-header').parent().show();
		$('.header').toggleClass('list');
		$('.header_name').toggleClass('list');
		$('.project_name').toggleClass('list');
		$('.project_owner').toggleClass('list');
		$('.project_location').toggleClass('list');
		$('.project_startDate').toggleClass('list');
		$('.project_duration').toggleClass('list');
		$('.project_role').toggleClass('list');

		if (checkedVal === 'list') {
			for ( i = 1; i <= $('.project').length; i++) {
				$('#project' + i).attr('class', 'project list');
				$('.project_image').hide();
			}
		} else {
			$('#list-header').parent().hide();
			for ( i = 1; i <= $('.project').length; i++) {
				$('#project' + i).attr('class', 'project');
				$('.project_image').show();
			}
		}

	});

	$('.view-profile').click(function() {
		window.location = getBaseURL("/users/profile/" + $(this).data('userid'));
	});

	divs = document.getElementsByClassName("project");

	for (var i = 0; i < divs.length; i++) {
		div = divs[i];
		div.addEventListener('dragstart', handleDragStart, false);
		div.addEventListener('dragstart', handleDragStart, false);
		div.addEventListener('dragenter', handleDragEnter, false);
		div.addEventListener('dragover', handleDragOver, false);
		div.addEventListener('dragleave', handleDragLeave, false);
		div.addEventListener('drop', handleDrop, false);
		div.addEventListener('dragend', handleDragEnd, false);
	}
	/**
	 * Check web address availability - Updated
	 */
	$.event.special.inputchange = {
		setup : function() {
			var self = this, val;
			$.data(this, 'timer', window.setInterval(function() {
				val = self.value;
				if ($.data(self, 'cache') != val) {
					$.data(self, 'cache', val);
					$(self).trigger('inputchange');
				}
			}, 20));
		},
		teardown : function() {
			window.clearInterval($.data(this, 'timer'));
		},
		add : function() {
			$.data(this, 'cache', this.value);
		}
	};

	$('input[name=webaddr]').on('inputchange', function() {
		var currWebAddr = this.value;

		$.ajax({
			url : location.protocol + '//' + location.hostname + "/ajax/profile/checkWebAddrAvailability",
			type : "POST",
			data : {
				webAddr : currWebAddr
			}
		}).done(function(responseObj) {
			if (currWebAddr === '') {
				if ($('#webaddrresult').attr('class') === 'fui-cross') {
					$('#webaddrresult').removeClass('fui-cross');
				}
				if ($('#webaddrresult').attr('class') === 'fui-check') {
					$('#webaddrresult').removeClass('fui-check');
				}
			}
			if (responseObj.errorcode === 0 && currWebAddr !== '') {
				if ($('#webaddrresult').attr('class') === 'fui-cross') {
					$('#webaddrresult').removeClass('fui-cross');
				}
				$('#webaddrresult').addClass('fui-check');
			}
			if (responseObj.errorcode === 1) {
				if ($('#webaddrresult').attr('class') === 'fui-check') {
					$('#webaddrresult').removeClass('fui-check');
				}
				$('#webaddrresult').addClass('fui-cross');
			}
			if (responseObj.errorcode === 2) {
				$('#webaddrresult').text('You should login first');
			}
		});
	});

	///////////
	$("#project_list_after").sortable({
		stop : function(event, ui) {
			$('#add_project').appendTo($("#project_list_after"));
		}
	});

	var project_before_count = $("#project_list_before li .project-title").length;
	var project_after_count = $("#project_list_after li .project-title").length;

	//12 projects
	if (project_after_count < 12) {
		$("#project_list_before").sortable({
			connectWith : '#project_list_after',
			stop : function(event, ui) {
				var project_after_count = $("#project_list_after li .project-title").length;
				$('#add_project').appendTo($("#project_list_after"));
				if (project_after_count > 11) {
					$('#project_list_before').sortable();
					$('#add_project').hide();
					alert("You have reached the maximum number of publicly displayed skills!");
				}
			}
		});
	} else {
		$('#add_project').hide();
		$("#project_list_after").sortable();
		$('#project_list_before').sortable();
	}

	$("#project_list_after").sortable({
		connectWith : '#project_list_before',
		stop : function(event, ui) {
			var project_after_count = $("#project_list_after li .project-title").length;
			if (project_after_count < 12) {
				$('#add_project').show();
				$('#add_project').appendTo($("#project_list_after"));
				$('#project_list_before').sortable({
					cancel : ""
				});
			}
		}
	});
	$("#project_list_before").sortable({
		connectWith : '#project_list_after',
		stop : function(event, ui) {
			$('#add_project').appendTo($("#project_list_after"));
			if ($("#project_list_after li .project-title").length == 12 || $("#project_list_after li .project-title").length > 11) {
				$('#project_list_before').sortable({
					cancel : "li"
				});
				// $('#project_list_before').sortable();
				$('#add_project').hide();
				alert("There are 12 project in ranking!");
			}
		}
	});

	$("#project_portfolio .project_play").click(function() {

		var audio = $(this).parent().parent().find('audio')[0];
		var sibling_audio = $(this).parent().parent().siblings().find('audio');
		$(sibling_audio).attr("src", "");
		$(sibling_audio).prev().find(".project_play").attr("class", "project_play fui-play");
		var isload;
		$(audio).attr("src", $(this).data("url"));

		if ($(this).hasClass("fui-pause")) {
			$(audio).attr("src", "");
			$(this).attr("class", "project_play fui-play");
			return false;
		}

		if ($(this).hasClass("fui-play")) {

			if ($(this).data("url").length > 0) {
				$(this).removeClass("fui-play").addClass("fui-radio-unchecked");
			} else {
				alert("There is no audio yet!");
				return;
			}

			if ($(this).hasClass("fui-radio-unchecked")) {
				$(audio).on('canplay canplaythrough', function(e) {
					e.preventDefault();
					$(this).prev().find(".project_play").removeClass("fui-radio-unchecked").addClass("fui-pause");
					isload = true;
					$(audio).trigger('play');
				});
			}
		}

	});
	//sort the project by name or time

	$(".mostrecent .sort-option").click(function() {
		$(".mostrecent .selected-sort").text($(this).find("a").text());
		sort_projects($(this).data('type'));
	});

	updateConnect();
	$('#linkFB').click(function() {

		FB.login(function(res) {
			if (res.status === 'connected') {
				var uid = res.authResponse.userID;
				var accessToken = res.authResponse.accessToken;
				var expire = res.authResponse.expiresIn;
				linkFB(uid, accessToken, expire);
			}
		}, {
			scope : 'email'
		});

		return false;
	});
	$('#unlinkFB').click(function() {
		unlinkFB();
		return false;
	});

	$('#linkTW').click(function() {
		window.h = twitterCallback;
		var url = location.protocol + '//' + location.hostname + '/ajax/profile/twitterSignup/1';
		w = window.open(url, 'Twitter Authorize', 'height=400,width=400');
		if (window.focus) {
			w.focus();
		}
		return false;
	});

	$('#unlinkTW').click(function() {
		unlinkTW();
		return false;
	});
});

function sort_projects(value) {
	switch(value) {
		case 0:
			$("#project_list_before .span3").tsort({
				data : 'name'
			}, {
				cases : false
			});
			break;
		case 1:
			$("#project_list_before .span3").tsort({
				order : 'desc',
				data : 'times'
			});
			break;
	}
}

/**
 * This function is added because
 */
function basicSettingInit() {

	// Init city zipcode checking
	googleLocationInit($('input[name="city"]'));

	// Init language dropdown
	var dropdown = $(".fms_dropdown_container");
	for (var i = 0; i < dropdown.length; i++) {
		if ($(dropdown[i]).data("select-inactive") !== 1)
			dropdownMenuInit_v2(dropdown[i]);
	}

	// Init image uploading module
	var param1 = [];
	param1['option'] = 'user_image';
	param1['cropratio'] = 1 / 1;
	imgUploaderInit($('#profile_picture_module'), param1);

	var param2 = [];
	param2['option'] = 'user_cover';
	param2['cropratio'] = 700 / 250;
	imgUploaderInit($('#cover_photo_module'), param2);

	// Init audio uploading module
	audioUploaderInit($('#user_spotlight_uploader'), {
		'option' : 'user'
	});

	// Form validation and AJAX submit
	jQuery.validator.addMethod("alpha", function(value, element, param) {
		if (value == '')
			return true;
		var reg = new RegExp("^[a-zA-Z]+$");
		if (reg.exec(value) != null) {
			return true;
		} else {
			return false;
		}
	}, "Please enter only letters");
	jQuery.validator.addMethod("legalChar", function(value, element, param) {
		if (value == '')
			return true;
		var reg = new RegExp("^[a-zA-Z ]+$");
		if (reg.exec(value) != null) {
			return true;
		} else {
			return false;
		}
	}, "Please enter valid city name");

	$('#dashboard_profile_basic_settings').validate({
		focusInvalid : false,
		ignoreTitle : true,
		submitHandler : function(form) {

			if ($('#dashboard_profile_basic_settings').valid()) {

				$.ajax({
					//POST the form data to the backend
					url : getBaseURL("/ajax/profile/updateUserProfile"),
					type : "POST",
					data : {
						namefirst : $('#dashboard_profile_basic_settings [name="namefirst"]').val(),
						namelast : $('#dashboard_profile_basic_settings [name="namelast"]').val(),
						language : $('#language').text(),
						//country: 	$('#dashboard_profile_basic_settings #country_dropdown .filter-option.pull-left').text(),
						country : "UNITED STATES",
						state : $('#usstate').text(),
						city : $('#dashboard_profile_basic_settings [name="city"]').val(),
						webaddr : $('#dashboard_profile_basic_settings [name="webaddr"]').val(),
						audioranking : getAudioRanking($('#user_spotlight_uploader'))
					}
				}).done(function(responseObj) {
					if (responseObj.errorcode == 0) {
						alert('Succcessfully saved your changes!');
					} else {
						alert('There was some sort of error when saving your changes. Please try refreshing the page.');
					}
				});
			}
		},
		rules : {
			namefirst : {
				required : true,
				alpha : true,
				maxlength : 16,
			},
			namelast : {
				required : true,
				alpha : true,
				maxlength : 16,
			},
			city : {
				legalChar : true,
				maxlength : 20,
			},

		},
		message : {
			namefirst : {
				required : 'Please enter your first name',
				maxlength : 'Your name cannot be longer than 20 characters.'
			},
			namelast : {
				required : 'Please enter your last name',
				maxlength : 'Your name cannot be longer than 20 characters.'
			},
			city : {
				legalChar : 'You can only enter letters and space',
				maxlength : 'City name cannot exceed 20 characters',
			},
		}
	});

}

function handleDragStart(e) {
	this.style.opacity = '0.5';
	dragSrcEl = this;

	e.dataTransfer.effectAllowed = 'move';
	e.dataTransfer.setData('text/html', this.innerHTML);
}

function handleDragOver(e) {
	if (e.preventDefault) {
		e.preventDefault();
		// Necessary. Allows us to drop.
	}

	e.dataTransfer.dropEffect = 'move';
	// See the section on the DataTransfer object.
	return false;
}

function handleDragEnter(e) {
	// this / e.target is the current hover target.
	this.classList.add('over');
}

function handleDragLeave(e) {
	this.classList.remove('over');
	// this / e.target is previous target element.
}

function handleDrop(e) {
	// this / e.target is current target element.

	if (e.stopPropagation) {
		e.stopPropagation();
		// stops the browser from redirecting.
	}
	dragSrcEl.style.opacity = '1.0';
	// See the section on the DataTransfer object.
	// Don't do anything if dropping the same column we're dragging.
	if (dragSrcEl !== this) {
		// Set the source column's HTML to the HTML of the column we dropped on.
		dragSrcEl.innerHTML = this.innerHTML;
		this.innerHTML = e.dataTransfer.getData('text/html');
		$(".visibility_toggle").click(function() {
			if (this.style.opacity !== 0.5) {
				this.style.opacity = "0.5";
			} else {
				this.style.opacity = "1.0";
			}

			alert("Visibility_toggle clicked");
		});
	}

	return false;
}


$(".visibility_toggle").click(function() {
	$(this).toggleClass('not');
});

function handleDragEnd(e) {
}

/* following functions and codes are for social media connections*/

var skills_num = 0;
$(".skill-order").each(function() {
	skills_num++;
	$(this).spinner({
		change : function(event, ui) {
		}
	});
	$(this).spinner("value", skills_num);
});

function ajax_getUserBiography() {
	$.ajax({
		url : location.protocol + "//" + location.host + "/ajax/profile/getuserbiography",
		type : "GET",
		data : {},
		dataType : "json",
	}).done(function(responseObj) {
		if (responseObj.errorcode == 0) {
			$("#profile_biography").html(responseObj.data);
			biographyInit();
		}
	}).fail(function() {
		setTimeout(function() {
			ajax_getUserBiography();
		}, 500, self);
	});
}

/**
 * the following statement is to execute all the elements for
 * WYSIWYG
 */
function biographyInit() {
	var bioarea = new nicEditor({
		buttonList : ['bold', 'italic', 'underline', 'left', 'center', 'right', 'ol', 'li', 'image', 'link', 'fontFormat']
	}).panelInstance('user-biography');

	$('.nicEdit-panel').append("<label id=\"click-to-plain-text\">Convert To Plain Text</label>");
	$('#click-to-plain-text').css("float", "right");
	$('#click-to-plain-text').css("margin-right", "10px");
	$('#click-to-plain-text').css("margin-bottom", "0px");
	$('#click-to-plain-text').css("margin-top", "3px");
	$('#click-to-plain-text').css("text-decoration", "underline");

	$('#click-to-plain-text').click(function() {
		var t = confirm("Are you sure? This will strip ALL formatting from your text!");
		var plainTxt = $('.nicEdit-main').text();
		if (t == true) {
			$('.nicEdit-main').html(plainTxt);
		}
	});

	$('#save_biography').click(function() {
		var user_biography = $.trim($('.nicEdit-main').html());
		var biographyStr = user_biography.replace(/"/g, '\\x11').replace(/=/g, '\\x22').replace(/:/g, '\\x33');//.replace(/;/g, '\\x44');

		//Allowable # of characters (with spaces and HTML tags) in biography is 2000.
		//Pankaj K., Sept 05, 2013
		if (user_biography.length > 2000) {
			alert("You have exceeded 2000 characters, including HTML formatting. " + "Please reduce your content or formatting, or use our \"Convert to Plain Text\" button.");
		} else {
			$.ajax({
				url : getBaseURL("/ajax/profile/updateUserBiography"),
				type : "POST",
				data : {
					biography : biographyStr
				}
			}).done(function(responseObj) {
				alert(responseObj.message);
			});
		}
	});
}

/**
 * This section initializes Music Uploader and spotlight
 *
 * This section initializes the JQUERY file upload plugin on page load.
 * When a music file is uploaded successfully, a styled music player will be inserted
 * into the DOM, and the controlers of the player will be initialized
 */
var audio_index = 0;
function musicPlayerInit() {
	// Existing spotlight
	var user_spotlights = $("#user_spotlights").children();
	for (var i = 0; i < user_spotlights.length; i++) {
		var jsonObj = {
			id : $(user_spotlights[i]).data('id'),
			name : $(user_spotlights[i]).data('name'),
			url : location.protocol + '//' + location.hostname + '/' + $(user_spotlights[i]).data('url')
		};
		audioPlayerInit(jsonObj);
	}
}

/**
 *This section belonging to profile portfolio
 */

$(document).ready(function() {
	$("#save-project-portfolio").click(function() {
		var project_visible = [];
		var url = getBaseURL("/ajax/profile/updateUserProjectRankingVisibility");
		var projectList = $('#project_list_after li');
		var restProjectList = $('#project_list_before li');
		var visibleProject = [];
		var invisibleProject = [];

		for (var i = 0; i < projectList.length - 1; i++) {
			visibleProject.push({
				'id' : $(projectList[i]).data('project-id'),
				'visibility' : '1'
			});
		}
		var k = 0;
		for (var j = i; j < restProjectList.length + projectList.length - 1; j++, k++) {
			visibleProject.push({
				'id' : $(restProjectList[k]).data('project-id'),
				'visibility' : '0'
			});
		}
		$.post(url, {
			'visibility' : visibleProject
		}).done(function(responseObj) {
			if (responseObj.errorcode == 0) {
				alert("Your changes have been saved successfully!");
				window.location = getBaseURL('dashboard/profile/portfolio');
			} else {
				alert(responseObj.message);
			}
		});
	});
});

/***********end profile portfolio****************/

function linkFB(id, accessToken, expireIn) {
	var baseurl = "http://graph.facebook.com/";
	var endurl = "/picture?type=large";
	//console.log('Welcome!  Fetching your information.... ');
	FB.api('/me', function(res) {
		var lastName = res.last_name;
		var firstName = res.first_name;
		var gender = 0;
		var email = res.email;
		switch (res.gender) {
			case "female":
				gender = 2;
				break;
			case "male":
				gender = 1;
				break;
		}
		$.post(getBaseURL("/ajax/profile/linkFB"), {
			facebookUserid : id,
			token : accessToken,
			name_last : lastName,
			name_first : firstName,
			email : email,
			password : "1",
			expire : expireIn
		}).done(function(data) {
			if (data.status == 0) {
				alert("Somebody is already logged in with a Facebook accound,  please log out of Facebook.");
				w = window.open('http://www.facebook.com', 'facebook', 'height=400,width=400');
			}
		});

	});
};

function unlinkFB() {

	$.post(getBaseURL("/ajax/profile/unlinkFB")).done(function(data) {
		alert("Your Facebook account has been disconnected!");
		window.location = getBaseURL("dashboard/profile/connect");
	});
};

function unlinkTW() {

	$.post(location.protocol + '//' + location.hostname + "/ajax/profile/unlinkTW").done(function(data) {
		alert("Your Twitter account has been disconnected!");
		window.location = getBaseURL("dashboard/profile/connect");
	});
};

function updateConnect() {
	$("#connect_form").validate({
		focusInvalid : false,
		ignoreTitle : true,
		submitHandler : function(form) {
			var data = $(form).serialize();

			$.ajax({
				//POST the form data to the backend
				url : getBaseURL("/ajax/profile/updateUserConnect"),
				type : "POST",
				data : data

			}).done(function(responseObj) {
				alert("Your changes have been saved!");
				window.location = getBaseURL("dashboard/profile/connect");
				return false;
			});
		}
	});
}

function twitterCallback(status) {
	if (status == 1) {
		alert("Your Twitter account has been connected!");
		window.location = getBaseURL("dashboard/profile/connect");
	}
	if (status == 2) {
		alert("A Twitter account has already been logged in, please log out of that account first.");
		nw = window.open('http://www.twitter.com', 'Twitter', 'height=1000,width=1500');
		nw.focus();
	}
}