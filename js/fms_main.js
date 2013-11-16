/////////////////////////////////////////
/////		Environment Setup		/////
/////////////////////////////////////////

/* in our custom log() function, you must specifiy a 'stage', if the stage 
 * does not exist in this array, then the log function does not output
 * the console message. Useful for turning on/off debug messages. */
var debug_stages = [	
	'bootstrap',
	'utility',
	'analytics',
	'skill_module',
	'skill_tags',
	'textExt',
	'adding_skill_from_textext',
	'adding_blank_skill',
	'pre_submit',
    'submit'
];

/* Skill object template, to pass information about a skill around. */
function SkillObj(myid, myname, myicon_path) {
	this.id = (typeof myid != 'undefined') ? myid : -1;
	this.name = (typeof myname != 'undefined') ? myname : "";
	this.icon_path = (typeof myicon_path != 'undefined') ? myicon_path : "";
	this.ranking = -1;
	this.preview_src = '';
	this.setExtra = function(ranking, src_url) {
		this.ranking = ranking;
		this.preview_src = src_url; 
	};
};
/* Variable times for animations and delays */
var TIME_GENERICDELAY = 100;


/////////////////////////////////////////
/////	DOM STATE DEPENDENT CODE	/////
/////////////////////////////////////////

$(function() {
	var urlParams = $.getQuery();
	var campaign = 'campaign name undefined';
	var source = 'campaign source undefined';
	var medium = 'campaign medium undefined';
	// We want to preserve the Google analytics keywords when people click on the home logo
	if (JSON.stringify(urlParams).length > 1 && pageTitle == 'Home') {
		campaign = urlParams.utm_campaign;
		source = urlParams.utm_source;
		medium = urlParams.utm_medium;
		// We do not want to rewrite the link with the anchor tag, so split it off.
		$('.home-link').attr('href', (window.location.href).split("#")[0]);
	}

	mixpanel.track("Page Loaded!", {
		"Page" : pageTitle,
		"utm_campaign" : campaign,
		"utm_source" : source,
		"utm_medium" : medium
	}, function(e) {
		log('analytics', "STARTUP", "Tracking page load success on page [" + pageTitle
				+ "] and campaign: [" + campaign + "] and source: [" + source
				+ "] and medium: [" + medium + "]");
	});

	//////////////////////////////////
	/////		  EVENTS		 /////
	//////////////////////////////////
	
	$('.home-link').on('click', function(e) {
		getMixpanelCallback("Clicked Home Logo", {"referrer": pageTitle}) 
	});
		
	$('.social-links-list-tile > a').each(function(index) {
		$(this).on('click', function(e) {
			getMixpanelCallback("Clicked Share", { "Network" : $(this).attr('class') } )
		});
	});
	
	//////////////////////////////
	/////		AJAX		 /////
	//////////////////////////////
	ajax_getUnreadThreadNum();	

	/** The wrong way to do mixpanel tracking. Too specific, very difficult to build the correct funnels. 
	 * $("button").click(function() { var id = $(this).attr("id");
	 * mixpanel.track("#" + id);
	 * 
	 * //console.log("DEBUG: Button " + id + " was clicked."); });
	 *  // Need to track only specific links, else we will be swimming in link //
	 * related clicks that have no business of being tracked.
	 * $("a").each(function() { var id = $(this).attr("id");
	 * 
	 * //console.log("DEBUG: Link " + id + " bound to mixpanel tracking");
	 * 
	 * mixpanel.track_links("#" + id, '#' + id); /* $(this).bind('click',
	 * function() { console.log("DEBUG: Link " + id + " was clicked."); }); /
	 * });
	 * 
	 */
}); // END OF DOCUMENT READY


/////////////////////////////////////////
/////		DOM INDEPENDENT CODE	/////
/////////////////////////////////////////



/**
 * Many elements are created dynamically and need to have mixpanel bound to
 * them. This function will return the callback function for the event trigger.
 * 
 * @param eventName
 *            The event name, e.g. "Clicked Share"
 * @param obj_eventProperties
 *            The event properties, e.g. "{ "Network" : "Facebook" }
 */
function getMixpanelCallback(eventName, obj_eventProperties) {
	/**log('analytics', "getMixpanelCallback",
			"Function called with these parameters: ["+eventName+"]["+JSON.stringify(obj_eventProperties)+"]");		
	**/
	mixpanel.track(eventName, obj_eventProperties, function(e) {
		log('analytics', "MIXPANELCALLBACK", "[" + eventName + "]" 
				+ " event fired with properties: " 
				+ JSON.stringify(obj_eventProperties));
	});
	
}

/////////////////////////////////////////
/////		 HELPER FUNCTIONS		/////
/////////////////////////////////////////

/**
 * This function will convert the array represented as a string, into a
 * javascript array object.
 * 
 * @param userInput
 *            The array as string, in the following format: "["text1","text2"]"
 * @return a javascript array of the strings inside the array representation.
 */
function explodeArrayString(userInput) {
	if (userInput.length > 0) {
		// first remove the [] brackets
		var convertedString = userInput.substr(1, userInput.length - 2);
		// next remove the "" marks around the strings.
		convertedString = convertedString.split('"').join('');
		// now break it up into an array
		return convertedString.split(",");
	} else
		return;
}

jQuery.extend(jQuery.easing, {
	/**
	 * Custom easing function, similar to easeInBack at the beginning, and then
	 * easeOutCubic at the end. Made in:
	 * http://www.timotheegroleau.com/Flash/experiments/easing_function_generator.htm
	 * with Point locations at: P0: 0 P1: -0.55 P2: 0.2 P3: 1 P4: 1 P5: 1
	 */
	easeInBackOutCubic : function(t, b, c, d) {
		var ts = (t /= d) * t;
		var tc = ts * t;
		return b
				+ c
				* (1.25 * tc * ts + 2 * ts * ts + -12.5 * tc + 13 * ts + -2.75
						* t);
	},
});

/**
 * Custom debug logging function. Takes in function name and message. Does log
 * filtering as well, if debug owner does not match then the message is not
 * printed.
 * 
 * @param stage
 *            String that must exist in the list of enabled log messages
 * @param funcName
 *            The function name
 * @param message
 *            The debug message.
 */
function log(stage, funcName, message) {
	if (debug_stages.indexOf(stage) > -1) {
		console.log(stage + "::" + funcName + "() " + message);
	}
}




/**
 * This function will asynchronously load a JS file, specified by 
 * the relative file path, and insert it into the DOM for use by later.
 * An event will be triggered on the document after the file is successfully 
 * loaded, which can be caught as 'done', providing a ".done()" experience.
 * 
 * If the script already exists in the DOM, do not insert.
 * 
 * This script was styled after the google+ async script download.
 *  
 * @param jspath		Relative file path to the javascript file, may be an array of js paths
 */
function insertJSintoDOM(jspath) {	
	var targetsrc = '';
	var jspatharray = [];
	if (typeof jspath === 'string') {
		jspatharray.push(jspath);
	} else {
		jspatharray = jspath;
	}
	$.each(jspatharray, function() {
		targetsrc = location.protocol + "//" +  location.hostname + "/" + this;
		if ($('script[src="'+targetsrc+'"]').length == 0 ) {
			var po = document.createElement('script');
			po.type = 'text/javascript';
			po.async = false;
			po.src = targetsrc;
			var s = document.getElementsByTagName('script')[0];
			s.parentNode.insertBefore(po, s);
			log("utility", 'insertJSintoDOM', "There were no duplicate scripts detected, inserted script ["+this+"].");
		} else {
			log("utility", 'insertJSintoDOM', "Duplicate scripts detected, did not insert script ["+this+"].");
		}
	});	
	$(document).trigger('done');
}

/**
 * This function will asynchronously load a CSS file, specified by 
 * the relative file path, and insert it into the DOM for use.
 * An event 'css_loaded' will be triggered on the document after the file is 
 * successfully loaded, which can be caught, providing a ".done()" experience.
 * 
 * If the css already exists in the DOM, do not insert.
 * 
 * This css was styled after the google+ async script download.
 *  
 * @param csspath		Relative file path to the css file, may be an array of css paths
 */
function insertCSSintoDOM(csspath) {
	var targetsrc = '';
	var csspatharray = [];
	if (typeof csspath === 'string') {
		csspatharray.push(csspath);
	} else {
		csspatharray = csspath;
	}
	$.each(csspatharray, function(){
		targetsrc = location.protocol + "//" +  location.hostname + "/" + this;
		if ($('link[href="'+targetsrc+'"]').length == 0 ) {
			var po = document.createElement('link');
			po.type = 'text/css';
			po.rel = 'stylesheet';
			po.async = true;
			po.href = targetsrc;		
			var s = document.getElementsByTagName('head')[0];
			s.appendChild(po); // force insertion of CSS to the end, for maximum priority
			log("utility", 'insertCSSintoDOM', "There were no duplicate CSS detected, inserted CSS ["+this+"].");
		} else {
			log("utility", 'insertCSSintoDOM', "Duplicate CSS detected, did not insert CSS script ["+this+"].");
		}
	});	
	$(document).trigger('css_loaded');
}

/**
 * @author Waylan Wong <waylan.wong@willrainit.com>
 * 
 * This function mirrors the Code Igniter base_url() function.
 * 
 * @param urlSlug Optional, pass in a partial URL you want appended to the base URL.
 * @return String The rebuilt URL.
 */
function getBaseURL(urlSlug) {
	var appendedPiece = (typeof urlSlug !== "undefined") ? urlSlug : "/";
	appendedPiece = (appendedPiece[0] === "/") ? appendedPiece : "/" + appendedPiece;
	return location.protocol + "//" + location.hostname + appendedPiece; 
}





/////////////////////////////////////////////////////
/////		Common site functionality			/////






/**
 *This function plays youtube video.
 * 
 * At the beginning, the container has a img inside, which is the video cover, and this function replace the img with
 * an iframe, which is the video
 *
 * @param	video_container		DOM Object that contains the video (with attribute 'data-src' storing the video src)
 * @param	width				width of the video
 * @param	height				height of the video
 */
function play_youtube_video(video_container, width, height){
	$(video_container).find('img').detach();
	$(video_container).find('div').detach();
	var video = '<iframe width=' + width + ' height=' + height + ' src="' + $(video_container).attr('data-src') + '" frameborder="0" allowfullscreen></iframe>';
	$(video_container).append(video);
}

/**
 * dropdownMenuInit()
 * 
 * This functions initialize the dropdown menu. The dropdown menu should have a container
 * of any element. And it must have a "p" element and a "ul" element which is a list of options.
 * 
 * @author Wei
 * @param target DOM-element Dropdown menu container
 */




/**
 * ratingInit()
 * 
 * This function initialize the rating bar. On mouse over, the hover-rating will be displayed
 * and on mouse out the current-selected-rating will be restored.
 * 
 * @authro Wei
 * @param target DOM-Element The ratings' container
 */
function ratingInit(target) {
	var ratings = $(target).children();
	for(var k = 0; k < ratings.length; k++) {
		ratings[k].onmouseover = function(){
			var hover = $(this).attr("rating");
			//alert(hover);
			for(var i=0; i < ratings.length; i++) {
				if(i >= hover) $(ratings[i]).attr("class", "unselected");
				else $(ratings[i]).attr("class", "");
			}
		};
		
		ratings[k].onmouseout = function(){
			var cur = $(target).attr("rating");
			for(var i=0; i < ratings.length; i++) {
				if(i >= cur) $(ratings[i]).attr("class", "unselected");
				else $(ratings[i]).attr("class", "");
			}
		};
		
		ratings[k].onclick = function() {
			$(target).attr("rating", $(this).attr('rating'));
		};
	}
}


/**
 * countrySelectionInit(target)
 * 
 * This function initialize the country selection dropdown menu.
 * It includes the Textext input suggestion, and an on blur validation that
 * convert country name into country isoCode
 * 
 * @param JQUERY DOM element or HTML DOM element
 * @author Wei Zong
 */
function countrySelectionInit(target) {
	var country_names = [];
	var country_codes = [];
	var country_names_check = [];
	
	// Get country names and codes array
	$.ajax({
		url		:	location.protocol + '//' + location.hostname + "/ajax/profile/getCountryCodeName",
		type	: 	"GET"
	}).done(function(responseObj){		
		// Get country codes and names
		for(var i = 0; i < responseObj.length; i++) {
			country_codes[i] = responseObj[i].isoCode;
			country_names[i] = responseObj[i].countryName;
			country_names_check[i] = responseObj[i].countryName;
		}
		
		// Store the country codes and names into DOM object
		$(target).data('country-codes', country_codes);
		$(target).data('country-names', country_names);
		$(target).data('country-names-copy', country_names_check);
				
		// Initialize Textext Plugin
		$(target).textext({
			plugins : 'autocomplete suggestions filter',	
			suggestions : $(target).data('country-names-copy'),
			filter : $(target).data('country-names-copy'),
			autocomplete : {
				dropdownPosition: 'below',
				dropdownMaxHeight:	'500px',		
				dropdownTop:		'45px'
			},
		});
		
		// Initialize the country code
		convertCountryCode();
	});
	
	// Initialize onclick event
	$("#"+$(target).attr('id')).on('blur', function(){
		var value = $(target).val();
		var index = $(target).data('country-names').indexOf(value);
		
		console.log("country suggestion", "record", index+"/"+$(target).data('country-names')[index]+"/"+$(target).data('country-codes')[index]);
		convertCountryCode();
	});
	
	// Function that can convert Country Name into Country Code
	function convertCountryCode() {
		var value = $(target).val();
		var index = $(target).data('country-names').indexOf(value);
		if(index >= 0) {
			$(target).next().next().val($(target).data('country-codes')[index] );
		} else {
			if(value == '') {
				$(target).next().next().val('');
			} else {
				alert("Please enter correct name and select from dropdown list.");
			}
		}
	}
}

/**
 *  ajax_getUnreadThreadNum()
 * 
 *  Get the unread thread num. Ajax the backend and get a number, if the  number is bigger than 0, show it and
 *  add the class for that, otherwise, show nothing and remove the class.
 * 
 * @author Hao Cai  
 */
function ajax_getUnreadThreadNum(){
	$.get(location.protocol + "//" + location.hostname
			+ "/ajax/message/getUnreadThreadNum", {}, function(data) {
			var num = data.num;
			if(num > 0){
				$("#unread_thread_num").text(num);
				if(!$("#unread_thread_num").hasClass("header-unread-num")){
					$("#unread_thread_num").addClass("header-unread-num");
				}
			}
			else{
				$("#unread_thread_num").text("");
				if($("#unread_thread_num").hasClass("header-unread-num")){
					$("#unread_thread_num").removeClass("header-unread-num");
				}
			}
	}, "JSON");
}

/**
 * googleLocationInit()
 * 
 * Get city and country by zip code. When User finished entering zip code, Ajax Google 
 * API to get country and city, and update the country name, country code, and city name.
 * 
 * @param JQUERY DOM or HTML DOM element of City and Country input
 * @author Wei Zong
 */
function googleLocationInit(cityTarget, countryTarget) {
	
	$(cityTarget).blur(checkLocationByZipcode);
	
	function checkLocationByZipcode() {
		$.ajax({
			url: "http://maps.googleapis.com/maps/api/geocode/json?address="+$(cityTarget).val()+"&sensor=true",
			context: document.body
		}).done(function(responseObj) {
			result = responseObj.results[0].address_components;
			for(var i=0; i < result.length; i++) {
				if(result[i].types[0] == "locality" || result[i].types[0] == "administrative_area_level_2") {
					var resCity = result[i].long_name;
					$(cityTarget).val(resCity);
				}
				// Country has been changed to states, so commented out following lines
				// if(result[i].types[0] == "country") {
					// var resCountry = result[i].long_name;
					// $(countryTarget).val(resCountry.toUpperCase());
					// $(countryTarget).next().next().val(result[i].short_name);
				// }
			}
		});
	}
}

/**
 * verticalNavInit()
 * 
 * This function will initialize vertical navigation bar of the dashboard
 * project page.
 */
function verticalNavInit () {
	$('[data-group="1"]').click(function() {
		$(this).parent().next().slideToggle();
		$(this).toggleClass('active');
		$(this).children().toggleClass('unfolded');
	});
}


/**
 * 
 * This function is used to search musicians or projects. 
 * 
 * @author Pankaj K.
 */

$('.fms-search.member').mouseenter(function(){
	if($('#fms-user-settings-menu').attr('class') === 'tooltip fade bottom in tooltip-light show'){
		$('#fms-user-settings-menu').toggleClass('show');
	}
	$(this).addClass('hover');
	$('#init-search').addClass('show');
	
	$('#init-search').mouseleave(function(){
		$('#init-search').removeClass('show');
		$('.fms-search.member.hover').removeClass('hover');
	});
});


$('.show-fms-search-header').mouseleave(function(){
	if($('#init-search').attr('class') === 'tooltip fade bottom in tooltip-light show'){
		$('#init-search').toggleClass('show');
		$('.fms-search.member.hover').removeClass('hover');
	}
});

$('.show-fms-user-menu-header').mouseleave(function(){
	if($('#fms-user-settings-menu').attr('class') === 'tooltip fade bottom in tooltip-light show'){
		$('#fms-user-settings-menu').toggleClass('show');
	}
});


/**
 *
 * following function is used to handle the user-action of viewing the settings 
 */

$('.fms-user-settings').mouseenter(function(){
	if($('#init-search').attr('class') === 'tooltip fade bottom in tooltip-light show'){
		$('#init-search').toggleClass('show');
	}
	$(this).addClass('hover');
	$('#fms-user-settings-menu').addClass('show');
	
	$('#fms-user-settings-menu').mouseleave(function(){
		$('#fms-user-settings-menu').removeClass('show');
	});
});


function dropdownMenuInit(target) {
    var select = $(target).children().first();
    var menu = $(target).children().last();
    var textspan = $(select).children().first();
    $(select).click(function(e){ 
        e.stopPropagation();           
        $(".fms_dropdown_menu").each(function(){
            if($(this).is(":visible") && $(this).parent().attr('id') != $(menu).parent().attr('id')){
                $(this).fadeOut();
                // Close dropdown without slimscroll
                if($(this).parent().attr('class') == 'fms_dropdown_container'){
                	$(this).parent().toggleClass('active');
                }
                // Close dropdown with slimscroll
           		if($(this).parent().parent().attr('class') == 'fms_dropdown_container'){
                	$(this).parent().toggleClass('active');
                }
                return true;
            }
        });     
        $(menu).fadeToggle();
        $(target).toggleClass('active');
        $(document).one('click', function() {
            $(menu).fadeOut();
            $(target).toggleClass('active');
        });
        
    });
    $(menu).find('li').click(function(e){
        e.stopPropagation();
        $(textspan).text($(this).text());
        $(target).data('selected', $(this).text());
        $(target).data('selectedid', $(this).data('id'));        
        $(menu).fadeOut();
        $(target).toggleClass('active');
    });
}


function dropdownMenuInit_v2(target) {
	var target_id = $(target).attr('id');
    var select = $(target).children().first();
    var textspan = $(select).children().first();
    var menu = $('#'+target_id+' .fms_dropdown_menu_container');
    
    // Init slimscroll
    if($(target).data('slimscroll') == 1) {
	    var dropdown_size = $(target).data('slimscrollsize');
	    $('#'+target_id+' .fms_dropdown_menu').slimScroll({
	        height: 200+'px'
	    });
	    $('#'+target_id+' .fms_dropdown_menu').css('height', (dropdown_size-6)+'px');
	    
	    // Keypress scrollto
    	scrollToListItemInit($('#'+target_id+' .fms_dropdown_menu'));
	}
	    
    // Init clicking on the dropdown button
    $(select).click(function(e){ 
        e.stopPropagation();
        
        // Close the reset of dropdowns     
        $(".fms_dropdown_container").each(function(){
        	var dropdown_id = $(this).attr('id');
            if($('#'+dropdown_id+' .fms_dropdown_menu_container').is(":visible") && dropdown_id != target_id){
                $('#'+dropdown_id+' .fms_dropdown_menu_container').fadeOut();
                $(this).removeClass('active');
                return true;
            }
        });
        
        // Show the current dropdown menu
        $(menu).fadeToggle();
        $(target).toggleClass('active');
        
        // Init on blur
        $(document).one('click', function() {
            $(menu).fadeOut();
            $(target).removeClass('active');
        });
        
    });
    
    // Init clicking to select
    $(menu).find('li').click(function(e){
        e.stopPropagation();
        $(textspan).text($(this).text());
        $(target).data('selected', $(this).text());
        $(target).data('selectedid', $(this).data('id'));        
        $(menu).fadeOut();
        $(target).removeClass('active');
    });
    
    function scrollToListItemInit(target) {
		var target_id = $(target).attr('id');
		$(document).keypress(function(e){
			e.stopPropagation();
			// If it is not displayed, then stop
			if($(target).parent().css('display') == 'none') {
				return;
			}
			
			// Get the key that is pressed
			var key = String.fromCharCode(e.which);
			
			// Covert to upper case
			if(e.which >= 97 && e.which <= 122) {
				key = key.toUpperCase();
			}
			
			// Get the list item
			var target_item = $('#'+ target_id + ' [data-search-index="'+key+'"]');
			if(target_item) {
				var target_position = $(target_item[0]).data('scrollto-index');
				$(target).slimScroll({scrollTo: target_position*32 + 'px'});
			}
		})
	}
}
