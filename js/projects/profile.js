/**
 * @author Hao.Cai
 */

var obj_demo_buffer_bar;
var maxduration = 0;

$(document).ready(function() {

	$(".member_cover").hover(function() {
		$(this).parent().addClass('hovered');
	}, function() {
		$(this).parent().removeClass('hovered');		
	});

	$('#hotlink .skill_name').click(function() {

		var details = $('#hotlink div');
		for (var i = 0; i < details.length; i++) {
			if ($(details[i])[0] == $(this).next()[0]) {
				$(this).next().slideToggle();
			} else {
				$(details[i]).slideUp();
			};
		}
		var $class_name = $(this).children('span').attr('class');
		if ($class_name == 'fui-arrow-right down_arrow') {
			$('#hotlink .skill_name').children('span').attr('class', 'fui-arrow-right');
			$(this).removeClass('selected');
		} else {
			$('#hotlink .skill_name').children('span').attr('class', 'fui-arrow-right');
			$(this).children('span').attr('class', 'fui-arrow-right down_arrow');
			$('#hotlink .skill_name.selected').removeClass('selected');
			$(this).addClass('selected');
		}
	});

	var audio = $('.preview_audio')[0];
	var playButton = $('.demo-play-button')[0];
	var progBar = $('.demo-progress')[0];
	var stopButton = $('.demo-stop-button')[0];
	obj_demo_buffer_bar = $($('.demo-buffer-bar'));
	demoPlayInit(audio, playButton);
	progressBarInit(audio, progBar);
	stopButtonInit(audio, playButton, stopButton);
	bufferBarInit(audio);

});

function demoPlayInit(audioElem, btnElem) {
	$(btnElem).click(function() {
		if ($(audioElem)[0].paused) {
			$(audioElem).trigger('play');
			$(btnElem).attr('class', 'demo-play-button pause');
		} else {
			$(audioElem).trigger('pause');
			$(btnElem).attr('class', 'demo-play-button');
		}
	});
}

function progressBarInit(audioElem, progBarElem) {
	$(audioElem).bind('timeupdate', function() {
		var prog = (audioElem.currentTime / audioElem.duration) * 220;
		if (prog > 215) {
			$(progBarElem).css({
				'border-bottom-right-radius' : '5px',
				'border-top-right-radius' : '5px'
			});
		} else {
			$(progBarElem).css({
				'border-bottom-right-radius' : '0px',
				'border-top-right-radius' : '0px'
			});
		}
		if ($(audioElem)[0].ended) {
			$('.demo-play-button').removeClass('pause');
		}
		$(progBarElem).css({
			'width' : prog + 'px'
		});
	});
}

function bufferBarInit(audioElem) {
	if (audioElem.buffered !== "undefined" && audioElem.buffered.length !== 0) {
		if (maxduration === 0) {
			maxduration = audioElem.duration;
			console.log("maxduration is: " + maxduration);
		}
		$(audioElem).on("progress", function() {
			currentBuffer = audioElem.buffered.end(0);
			console.log("currentBuffer: " + currentBuffer);
			var percentage = 100 * currentBuffer / maxduration;
			//console.log(percentage);
			if (percentage > 97 && obj_demo_buffer_bar.css('border-bottom-right-radius') !== '5px') {
				obj_demo_buffer_bar.css({
					'border-bottom-right-radius' : '5px',
					'border-top-right-radius' : '5px'
				});
			}
			obj_demo_buffer_bar.css({
				'width' : percentage + '%'
			});
		});
	} else {
		console.log("Buffering isn't supported!");
	}
}

function stopButtonInit(audioElem, btnElem, stopBtnElem) {
	$(stopBtnElem).click(function() {
		$(audioElem).trigger('pause');
		$(btnElem).attr('class', 'demo-play-button');
		$(audioElem)[0].currentTime = 0;
	});
}
