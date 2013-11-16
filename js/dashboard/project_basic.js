var audio_index = 0;

$(document).ready(function() {
	
	
	verticalNavInit();
	
    updateProject();
    //countrySelectionInit($('[name="country"]'));
    
    // Youtube video
    $('[name="video_preview"]').change(function(){
    	attachPrev($(this), $('.video-preview'));
    });
    
    googleLocationInit($('input[name="city"]'));
    
    dropdownMenuInit_v2($('#language_dropdown'));
    dropdownMenuInit_v2($('#usstate_dropdown'));

    /*
     * the following statement is to execute all the elements for
     * WYSIWYG
     */
    bkLib.onDomLoaded(function() {
        //nicEditors.allTextAreas();
        
        var projectDescription = new nicEditor({
	    	buttonList : ['bold', 'italic', 'underline', 'left', 'center', 'right', 'ol', 'li', 'image', 'link', 'fontFormat']
	    }).panelInstance('project-description');
	    
	    
	    $('.nicEdit-panel').append("<label id=\"click-to-plain-text\">Convert To Plain Text</label>");
	    $('#click-to-plain-text').css("float","right");
	    $('#click-to-plain-text').css("margin-right","10px");
	    $('#click-to-plain-text').css("margin-bottom", "0px");
	    $('#click-to-plain-text').css("margin-top","3px");
	    $('#click-to-plain-text').css("text-decoration","underline");
	    
	    $('#click-to-plain-text').click(function(){
	    	var t = confirm("Are you sure? This will strip ALL formatting from your text!");
	    	var plainTxt = $('.nicEdit-main').text();
	    	if(t==true){
	 			$('.nicEdit-main').html(plainTxt);
	    	}
	    });
    });

    var dropdowns = $(".fms-select-container");
    for (var i = 0; i < dropdowns.length; i++) {
        dropdownMenuInit(dropdowns[i]);
    }

    $(".visibility-toggle").click(function() {
        $(this).toggleClass('not-show');
        var dataShow = $(this).data('show');
        $(this).data('show', dataShow ? 0 : 1)
    });

	var param = [];
    param['option'] = 'project_image';
    param['cropratio'] = 1/1;
    imgUploaderInit($("#profile_picture_module"), param);
    //googleLocationInit($('[name="city"]'), $('#country'));
    
    var param = [];
    param['option'] = 'project';
    param['project_id'] = $('#projectBasicForm').data('projectid');
    audioUploaderInit($('#audio_preview'), param);
});

function updateProject() {
	jQuery.validator.addMethod("alpha", function(value, element,param) {
		if(value == '') return true;
		var reg = new RegExp("^[a-zA-Z]+$");
		if(reg.exec(value) != null) {
			  return true;
		  } else {
			  return false;
		  }		
	}, "Please enter only letters");
    jQuery.validator.addMethod("legalChar", function(value, element,param) {
    	if(value == '') return true;
		var reg = new RegExp("^[a-zA-Z ]+$");
		if(reg.exec(value) != null) {
			  return true;
		  } else {			  
			  return false;
		  }
	}, "Please enter valid city name");	
	
	jQuery.validator.addMethod("projectName", function(value, element,param) {
		var reg = new RegExp("^[a-zA-Z0-9!()-\? : \'\"]+$");
		var projActualName = value.replace(/"/g, '&quot;')
    						.replace(/'/g, '&apos;');
		
		if(reg.exec(value) != null && projActualName.length <200) {
			  return true;
		  } else {			  
			  return false;
		  }
	}, "Allowed characters are numbers, letters, parentheses, apostrophes, and dashes.");		
	
    $("#projectBasicForm").validate({
        focusInvalid: false,
        ignoreTitle: true,
        submitHandler: function(form) {
        	
            var flag = $("[data-create]").data('create');
            var projectId = $(form).data("projectid");
            var tags = $('#projectTags .tag');
            var tagArray = [];
            for(var i = 0; i < tags.length; i++) {
            	tagArray[i] = $(tags[i]).children().text();
            }
            var description = $.trim($('.nicEdit-main').html());
            
            var descriptionStr = description
        						.replace(/"/g, '\\x11')
        						.replace(/=/g, '\\x22')
        						.replace(/:/g, '\\x33');
        						// .replace(/;/g, '\\x44');
                    
            if($('#datepicker')) {
            	var start_date = $('#datepicker-01').val();
            } else {
            	var start_date = "";
            }
            
            // Must upload a profile picture
        	if($('#profile_picture_module .img-uploaded-wrap img').data('default-img') == 1) {
        		alert('Please upload a photo!');
        		return;
        	}
        	
        	// Must have at least 2 tags
        	if(tags.length < 2) {
        		alert("Please have two or more project tags!");
        		return;
        	}
            
            var postdata = {
                projectId: 		$(form).data("projectid"),
                name: 			$('[name="name"]').val(),
                //country: 		$('#country .filter-option').text(),
                country:		"UNITED STATES",
                state:			$('#usstate').text(),
                language: 		$('#language').text(),
                city: 			$('[name="city"]').val(),
                videoPreview: 	$('[name="video_preview"]').val(),
                duration: 		$('#estimatedDuration .filter-option').text(),
                description: 	descriptionStr, 
                tags: 			tagArray,
                startDate:		start_date,
                //startDate: 	$('[name="startDate"]').val(),
                //listLength: 	$("#listLength").data("selected"),
                
                audioranking: 		getAudioRanking($('#audio_preview')),
                showAudioPreview: 	$("#showAudioPreview").data("show"),
                showVideoPreview: 	$("#showVideoPreview").data("show"),
                showDescription: 	$("#showDescription").data("show"),
                showCountry: 		$("#showLocation").data("show"),
                showCity: 			$("#showLocation").data("show"),
                showDuration: 		$("#showDuration").data("show"),
                //showTags: $("#showTags").data("show"),
                //showStartDate: $("#showStartDate").data("show"),
                //showListLength: $("#showListLength").data("show"),
                //showLanguage: $("#showLanguage").data("show"),
            };
            
            //Allowable # of characters (with spaces and HTML tags) in description is 2000. 
        	//Pankaj K., Sept 05, 2013
        	if(description.length > 2000){
	        	alert("You have exceeded 2000 characters, including HTML formatting. " 
	        	+ "Please reduce your content or formatting, or use our \"Convert to Plain Text\" button.");
	        }else{
	        	$.ajax({
	                //POST the form data to the backend
	                url: getBaseURL("/ajax/project/updateProjectDetail"),
	                type: "POST",
	                data: postdata
	            }).done(function(responseObj) {
	                // Error
	                if(responseObj.errorcode === 2 || responseObj.errorcode === 3 || responseObj.errorcode === 4){
	                	alert(responseObj.message);
	                }
	                
	                // Success
	                if (responseObj.errorcode === 0 && !$('#projectBasicForm').data('confirmation')) {
	                	// For create basic page
	                	if(flag === 1)
	                    	window.location = location.protocol + "//" + location.host + "/dashboard/project/create_skills/" + projectId;
	                    // For edit basic page
	                    else 
	                    	alert('Your changes has been saved');
	                }
	                
	                // For confirmation page
	                if (responseObj.errorcode === 0 && $('#projectBasicForm').data('confirmation')) {
	                	// update status from ACTIVE to COMPLETED
	                	$.ajax({
	                		url:	getBaseURL("/ajax/project/updateProjectStatus"),
	                		type:	"POST",
	                		data: {
	                			project_id:		$('#projectBasicForm').data('projectid'),
	                			project_status:	'ACTIVE',
	                		}
	                	}).done(function(responseObj){
	                		if(responseObj.errorcode) {
	                			alert(responseObj.message);
	                		} else {
	                			window.location = getBaseURL("/dashboard/project/overview/" + projectId);
	                		}
	                	});
	                }
	            });	
	        }
        },
        rules: {
        	name: {
        		required:	true,
        		projectName:true,
        		minlength:	2,
        		maxlength:	200
        	},
        	city: {
        		legalChar:	true,
        		maxlength:	20
        	}
        },
        message: {
        	name: {
        		minlength:	"Please come up with a project name greater than 2 characters.",
        		maxlength:	"Please come up project name that is less than 200 characters."
        	}
        }
    });
}



function attachPrev(urlInput, iframeContainer){
	//var prev_src = $(urlInput).val();
	var prev_src = $('[name="video_preview"]').val();
	var iframeContainer = $('.video-preview');
	if(prev_src == ""){												//remove the video when the pre_url is empty
		$(iframeContainer).html('<span class="fui-video"></span>');
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
		return;
	}
	
	//transfer into embed url
	prev_src = '//www.youtube.com/embed/' + vId;
	$(urlInput).val(prev_src);
	
	if($(iframeContainer).find('iframe').length == 0){
		htmlstring = '<iframe title="YouTube video player" width="460" height="220" src="' + prev_src + '" frameborder="0" allowfullscreen></iframe>';
		$(iframeContainer).html(htmlstring);
	}
	else{
		$(iframeContainer).find('iframe').attr('src', prev_src);
		$(iframeContainer).html('<span class="fui-video"></span>');
	}
}
