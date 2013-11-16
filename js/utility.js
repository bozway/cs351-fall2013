/**
 * This function initialize the image-upload-and-crop object
 * @author Wei Zong
 * @param JQUERY/DOM object
 * 		The image uploader container object
 */
function imgUploaderInit(target, param){
	var target_id = $(target).attr("id");
	var target_photo = $('#'+target_id+" .img-uploaded");
	
	// Initialize attributes of the target image crop object
	$(target).data('cropfilename', target_photo.attr('src'));
	$(target).data('uploadOption', "URL");
	$(target).data('img_crop_index', 0);

	
	// Specify the upload option and filetype
	var url_projectid = 0;
	if(param['option'] == "user_image") {
		var url_option = "user_files";
		var url_filetype = "image";
		$(target).data('target_height', 360);
		$(target).data('target_width', 360);
	}
	if(param['option'] == "user_cover") {
		var url_option = "user_files";
		var url_filetype = "cover";
		$(target).data('target_height', 450);
		$(target).data('target_width', 1280);
	}
	if(param['option'] == "project_image") {
		var url_option = "project_files";
		var url_filetype = "image";
		url_projectid = $('form').data('projectid');
		$(target).data('target_height', 360);
		$(target).data('target_width', 360);
	}
		
	
	// Initialize the default image hover
	// $("#"+target_id + " [data-default-img='1']").hover(
		// function(){$(this).attr("src", "/img/fms_user_portal/demo_photo_hover.png")},
		// function(){$(this).attr("src", "/img/fms_user_portal/demo_photo.png")}
	// );
	
	// Initialize the cropping of existing image
	$("#"+target_id + " [data-default-img='0']").click(function(){
		$(".img-crop-container").hide();
		reloadJCrop($(this).attr("src"));
		$('#'+target_id+"_crop").fadeIn();
	});
	
	// Initialize the upload links
	$("#"+target_id+" .img-uploader-link.url").click(function(){
		$(this).next().toggle();
	});
	$("#"+target_id+" .img-uploader-link.local").click(function(){
		$("#"+target_id+" .img-file").trigger("click");
	});
	
	// Initialize the URL-input box buttons	
	$("#"+target_id+" .img-url-enter a").click(function(){
		$(this).parent().hide();
	});
	$("#"+target_id+" .img-url-enter button").click(function(){
		var uploadURL = $(this).prev();
		if(uploadURL.val() == "") {
			uploadURL.attr("placeholder", "Cannot be empty!");
		}
		else {
			var url = uploadURL.val();
			
			// AJAX submit
			$.ajax({
				url	:	location.protocol + '//' + location.hostname + "/ajax/uploadhandler/uploadFromUrl",
				type:	"POST",
				data:	{
					option:	url_option,
					type:	url_filetype,
					url:	uploadURL.val(),
					project_id: url_projectid,
				}
			}).done(function(responseObj){
				
				if(responseObj.errorcode == 0) {
					// Display the image if success
					$('#'+ target_id+' .img-uploaded-wrap img').data('default-img', 0);
					target_photo.attr("src" , responseObj.url);
					target_photo.attr("style", "");
					
					// Remove the Demo
					target_photo.unbind('mouseenter mouseleave');
					
					// Record file name for uploading
					$(target).data('cropfilename', responseObj.name);
					$(target_photo).data('filename', responseObj.name);
					$(target).data('uploadOption', "LOCAL");
					
					// Reload or Disable cropping
					//target_photo.off('click');
					reloadJCrop(responseObj.url);
					
					// Close the URL input bar
					$("#"+target_id+" .img-url-enter").fadeOut();
				} else {
					alert(responseObj.message);
				}
			});
		}
	});
	
	// Initialize the Local file uplaading
	var projectId = 0;
	if(param['option'] == 'user_image') {
		uploadURL = location.protocol + '//' + location.hostname + "/ajax/uploadhandler/index/"+param['option'];
	} else {
		projectId = $('form').data('projectid');
		uploadURL = location.protocol + '//' + location.hostname + "/ajax/uploadhandler/index/"+param['option']+'?project_id='+projectId;
	}
	$("#"+target_id+" .img-file").fileupload({
		url: uploadURL,
		add: function (e, data) {
			var format = /(?:\.jpg)|(?:\.jpeg)|(?:\.png)|(?:\.gif)$/i;
			if(format.test(data.files[0].name)) {
				if(data.files[0].size > 2000000) {
					alert("File too large. Maximum file size is 2MB.");
				}
				else {
					data.submit();
				}
			}
			else {
				alert("Format not supported:"+data.files[0].name);
			}
		},
		done: function (e, data) {
			// processing JSON: can't figure out why data.result cannot be interpreted as JSON
			if(typeof data.result != 'object') {
				data.result = eval('(' + data.result +')');
			}
			// Check error
			if(data.result.files[0].file_errorcode != 0 ){
				alert('File is not an image!');
				return;
			}
			
			// Display the uploaded image
			$('#'+ target_id+' .img-uploaded-wrap img').data('default-img', 0);
			var url = data.result.files[0].url;
			var image = new Image();
			image.src = url;
			image.onload = function (){
				img_width = this.width;
				img_height = this.height;
			
				if(img_width < 100 || img_height < 100) {
					alert("Image size should be at least 100x100 pixels");
					return;
				}
				target_photo.attr("style", "");
				target_photo.attr("src" ,url);
				
				// Remove the Demo
				target_photo.unbind('mouseenter mouseleave');
				
				// Record file name for uploading
				$(target).data('cropfilename', data.result.files[0].name);
				$(target_photo).data('filename', data.result.files[0].name);
				$(target).data('uploadOption', "LOCAL");
				
				// Enable cropping
				reloadJCrop(url);
			};
		}
	});
	
	// Initialize the Image Crop Close button
	$('#' + target_id + "_crop .close-crop").click(function(){
		$('#' + target_id + "_crop").fadeOut();
	});
	
	// Initialize the Image Crop Button
	$('#' + target_id + "_crop .crop").click(function(){
		cropImage();
	});
	
	/**
	 * This function will initialize JCROP on given image element
	 * 
	 * This function set the styles and callback functions of JCROP.
	 * When the target is selected, the callback function will set the dimensions
	 * of the target by updating the glabal variables
	 * @author:	Wei Zong
	 * @param string url
	 * 			The url of the target image element
	 */
	function reloadJCrop(url) {
		
		var original_image_file_name = $(target_photo).data('filename');
		var matches =  original_image_file_name.match(/\.(\w+)$/);
		var suffix = matches[1];
		
		url = url + '.' + suffix.toLowerCase();
		
		// Enable cropping popup
		target_photo.off('click');
		target_photo.click(function(){
			$('#'+target_id+"_crop").fadeToggle();
		});
		
		// Insert the image to be cropped into DOM
		img_crop_id = target_id+"_no"+$(target).data('img_crop_index');
		$(target).data('img_crop_index', $(target).data('img_crop_index')+1);
		$('#' + target_id + "_crop .img-crop-wrap").html("<img id='"+img_crop_id+"' src='"+ url +"'></img>");
		
		// Get dimension of the image and container
		getImageDimensions(url);
		var img_container_width = $(target_photo).width();
		var img_container_height = $(target_photo).height();
		
		// Enable JCROP on the target image
		$('#'+img_crop_id).load(function(){
			$('#'+img_crop_id).Jcrop({
			    bgColor:     'black',
			    bgOpacity:   .4,
			    setSelect:   [ 100, 100, 50, 50 ],
			    aspectRatio: param['cropratio'],
			    boxWidth:	360,
			    boxHeight:	360,
			    //minSize:	[100, 100],
			    onSelect: function (c){
			    	/*
			    	 * The plugin sometimes return coordinates relative to the actual dimension of the 
			    	 * image, but sometimes, I don't know why it changed to return me coordinates relative
			    	 * to its container size (size of the picture in the DOM, which is customized by CSS)
			    	 * 
			    	 */
			    	
			    	// Cropping with c = position relative to container
			    	// $(target).data('cropx', Math.round(c.x/360*$(target).data('actual_width') ) );
			    	// $(target).data('cropy', Math.round(c.y/360*$(target).data('actual_width') ) );
			    	// $(target).data('croph', Math.round(c.h/360*$(target).data('actual_width') ) );
			    	// $(target).data('cropw', Math.round(c.w/360*$(target).data('actual_width') ) );
			    	
			    	
			    	// Cropping with c = position relative to actual image dimension
			    	// $(target).data('cropx', c.x);
					// $(target).data('cropy', c.y);
					// $(target).data('croph', c.h);
					// $(target).data('cropw', c.w);
					
					// Hacking way of getting the relative position of cropping
			    	var crop_area = $('#'+target_id+' .jcrop-holder');
			    	var cropped_area = $('#'+target_id+' .jcrop-holder').children().first();
			    	var scale_ratio = $(target).data('actual_width') / crop_area.width();
			    	$(target).data('cropw', Math.round(cropped_area.width() * scale_ratio ) );
			    	$(target).data('croph', Math.round(cropped_area.height() * scale_ratio ) );
			    	$(target).data('cropy', Math.round(cropped_area.position().top * scale_ratio ) );
			    	$(target).data('cropx', Math.round(cropped_area.position().left * scale_ratio ) );

			    	
			    	// Shift with c = position relative to image
			    	// $(target).data('display_width', Math.round($(target).data('actual_width') / c.w * img_container_width));
			    	// $(target).data('display_height', Math.round($(target).data('actual_height') / c.h * img_container_height));
			    	// $(target).data('display_x', -1 * Math.round(c.x / c.w * img_container_width));
			    	// $(target).data('display_y', -1 * Math.round(c.y / c.h * img_container_height));
			    	
			    	// Shift with c = position relative to container
			    	// $(target).data('display_width', Math.round(img_container_width * 360 / c.w ));
			    	// $(target).data('display_height', Math.round(img_container_width *360 / c.w * $(target).data('actual_height')/ $(target).data('actual_width')));
			    	// $(target).data('display_x', -1 * Math.round(c.x / c.w * img_container_width));
			    	// $(target).data('display_y', -1 * Math.round(c.y / c.w * img_container_width));
			    	
			    	// No shifting
			    	$(target).data('display_width', img_container_width);
			    	$(target).data('display_height', img_container_height);
			    	$(target).data('display_x', 0);
			    	$(target).data('display_y', 0);
			    	
			    	
			    }
			});
		});
	}
	
	
	/**
	 * This function will get the width and height of an image
	 * 
	 * The width and heigth of the target image will be kept in the global variable
	 * actual_width and actual_height
	 * 
	 * @author Wei Zong
	 * @param string url
	 * 			URL of the target image
	 */
	function getImageDimensions(url) {
		var image = new Image();
		image.src = url;
		image.onload = function (){
			$(target).data('actual_width', this.width);
			$(target).data('actual_height', this.height);
		};
	}
	
	/**
	 * Crop and Display Image
	 * 
	 * This function will ajax backend with the dimensions needed for the cropping
	 * It will also display the cropped image in the webpage
	 * Dimensions of the cropping are global variables set by callback function of the JCROP
	 * @author:	Wei Zong
	 */
	function cropImage () {		
		$.ajax({
			type:	"POST",
			url:	location.protocol + '//' + location.hostname + "/ajax/uploadhandler/imageCrop/"+param['option'],
			data:	{
				//filename:	"Desert.jpg",
				filename:	$(target_photo).data('filename'),
				crop_x:	 	$(target).data('cropx'),
				crop_y:		$(target).data('cropy'),
				crop_h:		$(target).data('croph'),
				crop_w:		$(target).data('cropw'),
				option:		$(target).data('uploadOption'),
				
				target_height:	$(target).data('target_height'),
				target_width:	$(target).data('target_width'),
				//option:		'LOCAL'
				project_id:	 projectId
			}
		}).done(function(responseObj){
			if(responseObj.success == 1) {
				// Approach1: Display cropped image using CSS (by resizing the image and hidding the cropped part)
				// var display_width = $(target).data('display_width');
				// var display_height = $(target).data('display_height');
				// var display_x = $(target).data('display_x');
		    	// var display_y = $(target).data('display_y');
				// $(target_photo).attr("style", "width:"+display_width+"px;height:"+display_height+"px;margin-left:"+display_x+"px;margin-top:"+display_y+"px;");
				$('#'+target_id+"_crop").fadeOut();
				$('#'+target_id+"_crop .img-crop-info").text("Shift Box over to the position desired");
				
				// Approach2: Reload the cropped image
				var d = new Date();
				$(target_photo).attr('src', $(target_photo).attr('src') + '?'+d.getTime() );
			} else {
				$('#'+target_id+"_crop .img-crop-info").text("Fail to crop, please try again.");
			}
		});
	}
}


/**
 * getAudioRanking()
 * 
 * This function is used to get audio file ranking. The parameter it takes is 
 * the spotlight container which is an unordered list.
 */
function getAudioRanking(target) {
	var target_id = $(target).attr('id');
	var spotlights = $('#'+target_id+' .audio-player-container');
	var ranking = [];
	if(spotlights.length == 0) {
		return ranking;
	}
	for(var i = 0; i < spotlights.length; i++) {
		ranking[i] = $(spotlights[i]).attr('id');
	}
	return ranking;
}


/**
 * This function initialize the audio uploading module and player
 * @author Wei Zong
 * @param JQUERY/DOM object
 * 		The image uploader container object
 */
function audioUploaderInit(target, param) {
	var target_id = $(target).attr('id');
	$(target).data('audio_index', 0);

	// Existing spotlight
	var user_spotlights = $('#'+target_id+' .audio-files-preload').children();
	for(var i = 0; i < user_spotlights.length; i++) {
		var jsonObj = {
			id:		$(user_spotlights[i]).data('id'),
			name:	$(user_spotlights[i]).data('name'),
			url:	$(user_spotlights[i]).data('url'),
		};
		audioPlayerInit(jsonObj);
	}

	// Music progress
//	song_progress();

	// Drag and drop of spotlight
	$('#'+target_id+' .audio-preview-container').sortable(); 

	// File uploader
	if(param['option'] == 'project') {
		upload_url = location.protocol + '//' + location.hostname + "/ajax/uploadhandler/index/project_audio?project_id="+ param['project_id'];
	} else {
		upload_url = location.protocol + '//' + location.hostname + "/ajax/uploadhandler/index/user_audio";
	}
	$('#'+target_id+' .audio-file-input').fileupload({
		url: upload_url,
		add: function (e, data) {
			// Check if exceeds maximum of 3 spotlights
			var num = $('#'+target_id+' .audio-preview-container').children().length;
			if(num >= 4) {
				$('#'+target_id+' .audio-upload-error').text("You can upload at most 3 spotlights.");
				return;
			}
			
			// Check file format and size
			if(/\.(mp3|wav)$/i.test(data.files[0].name)) {
				if(data.files[0].size > 10000000) {
					alert("File too large. Maximum file size is 10MB.");
				}
				else {
					// Display status
					$('#'+target_id+' .audio-upload-error').text('Uploading ... ');
					
					// submit
					data.submit();
				}
			}
			else
				alert("Format not supported:"+data.files[0].name);
		},
		done: function (e, data) {
			// Clear the upload status
			$('#'+target_id+' .audio-upload-error').text('');
			
			// Check error
			if(data.result.files[0].file_errorcode == 1) {
				alert('File is not an accepted audio file format!');
				return;
			}
			if(data.result.files[0].file_errorcode == 2) {
				alert('You can upload at most 3 spotlights.');
				return;
			}
			
			// processing JSON: can't figure out why data.result cannot be interpreted as JSON
			if(typeof data.result != 'object') {
				data.result = eval('(' + data.result +')');
			}
			
			// Initialize the audio player
			audioPlayerInit(data.result.files[0]);
		}
	});
	
	// Upload button
	$('#'+target_id+' button').click(function(){
		$('#'+target_id+' .audio-file-input').trigger('click');
	});
	
	/**
	 * This function Initialize a new audio player
	 * @author:	Wei
	 * @param file
	 */
	function audioPlayerInit(file) {
		// Delete a placeholder
		// ...
		
		// Clone and place the audio player
		var audio_player = $("#"+target_id+" .audio-player-template").clone();
		$(audio_player).attr('class', 'audio-player-container loading');
		$(audio_player).attr("id", file.id);
		$(audio_player).appendTo("#"+target_id+" .audio-preview-container");
		
		// Get the element we need to initialize
		var button		= $(audio_player).children()[0];
		var info_name	= $(audio_player).children()[1];
		var cancel 		= $(audio_player).children()[2];
		var info_time	= $(audio_player).children()[3];
		var audio 		= $(audio_player).children()[4];
		
		// Initialze the information
		$(audio).on('canplay', function () {
			$(info_name).html('<span>'+file.name+'</span>');
			var minute = Math.floor(audio.duration/60);
			var second = Math.floor(audio.duration) % 60;
			if(second < 10) {
				second_str = '0' + second;
			} else {
				second_str = second;
			}
			$(info_time).html('<span>'+minute+":"+second_str+'</span>');
			$(audio_player).removeClass("loading");
		});
		
		// Initialize the source of audio
		$(audio).children(":last-child").attr("src", file.url);
		$(audio).load();
		
		// Initialize the play button
		$(button).click(function(e) {
		    e.preventDefault();
		    var song = $(this).parent().find('audio')[0];
		    if (song.paused) {
		    	$(song).trigger('play');
		    	$(this).html('<span class="fui-pause"></span>');
		    }
		    	
		    else {
		    	$(song).trigger('pause');
		    	$(this).html('<span class="fui-play"></span>');
		    }
		});
		
		// Initialize the volume bar
//		$(volume).children().last().slider({
//			value:	100,
//			step:	1,
//			min:	0,
//			max:	100,
//			change:	function() {
//				var value = $(this).slider("value");
//				var song = $(this).parent().parent().find('audio')[0];
//				song.volume = (value/100);
//			}
//		});
		
		// Initialize the cancel button
		$(cancel).click(function (){
			var cur_audio_player = $(this).parent()[0]; 
			var delete_type = (param['option'] == 'project') ? 'project':'user';
			$.ajax({
				//POST the form data to the backend
				//url		:	"http://www.findmysong.dev/audiohandler/delete",
				url		:	location.protocol + '//' + location.hostname + "/ajax/uploadhandler/delete",
				type	:	"POST",
				data	:	{
					filename:	 file.name,
					type:		 delete_type,
					fileid:		 $(audio_player).attr('id'),

				}
			}).done(function(responseObj) {
				if(responseObj.success) {
					$(cur_audio_player).remove();
					//$("#spotlight-placeholder").append('<p class="audio-player-placeholder"></p>');
				}
				else alert("Fail to delete the file.");
			});        	
		});
	}
}
