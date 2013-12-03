/**
 * @author Hao.Cai
 */
$(document).ready(function() {
	//Initially show biography in Biography Part
	$('#biography_bio').show();

	// Added: play music
	$('.project-img-hover').click(function() {
		var target = $(this).next().next();
		if (target[0].paused === false)
			$(target).trigger('pause');
		else
			$(target).trigger('play');
	});

	// sort project portfolio list
	$('.project-listing').append($('.project-listing li').sort(function(a, b) {
		return parseInt(a.getAttribute('data-id'), 10) - parseInt(b.getAttribute('data-id'), 10);
	}));
	

	$('.project_play, .spotlight-play').click(function() {
		// Get spotlight URL
		var spotlight_url = $(this).data('url');
		if (!spotlight_url) {
			return;

		}

		// Pause other audio
		$('audio').trigger('pause');
		$('audio').parent().removeClass('fui-pause');
		$('audio').parent().addClass('fui-play');

		// Append audio tag and INIT
		var spotlight_play_btn = $(this);
		var target = $(this).children();
		if (target.length <= 0) {
			$(this).html('<audio><source src="' + spotlight_url + '"></source></audio>');
			target = $(this).children();
			// In the future may add loading icon here
			$(this).off('click');
			$(target).on('canplay', function() {
				// In the future may remove loading icon here
				spotlight_play_btn.removeClass('fui-play');
				spotlight_play_btn.addClass('fui-pause');
				$(target).trigger('play');
				$(spotlight_play_btn).click(function() {
					// Play or pause
					if (target[0].paused) {
						spotlight_play_btn.removeClass('fui-play');
						spotlight_play_btn.addClass('fui-pause');
						$(target).trigger('play');
					} else {
						spotlight_play_btn.removeClass('fui-pause');
						spotlight_play_btn.addClass('fui-play');
						$(target).trigger('pause');
					}
				});
			});
			return;
		}
	});

	//Initialize video play event in Biography part
	$(".biography-video-container").click(function() {
		play_youtube_video(this, 220, 200);
	});

	//Initialize video play event in Skills Part
	$(".skill-video-container").click(function() {
		play_youtube_video(this, 220, 160);
	});
	//Initialize project portfolio
	var project_length = $('.project-listing li').length;
	for ( $i = 1; $i <= project_length / 3; $i++) {
		var li_index = 3 * $i - 1;
		$('.project-listing li:eq(' + li_index + ')').addClass('no-margin-right');
	}
	$('.project-img-hover').mouseover(function() {
		$(this).addClass('project-img-onmouseover');
	});
	$('.project-img-hover').mouseout(function() {
		$(this).removeClass('project-img-onmouseover');
	});
	// $('.project_play').click(function(){
	// var url = $(this).data('url');
	// $(this).parent().next().children().attr('src',url);
	// });

	//Initialize hot link event in profile page
	$('#hotlink p').click(function() {
		var details = $('#hotlink div');
		for (var i = 0; i < details.length; i++) {
			if ($(details[i])[0] === $(this).next()[0]) {
				$(this).next().slideToggle();
			} else
				$(details[i]).slideUp();
		}
		var class_name = $(this).children('span').attr('class');
		if (class_name == 'fui-arrow-right down_arrow') {
			$('#hotlink p').children('span').attr('class', 'fui-arrow-right');
		} else {
			$('#hotlink p').children('span').attr('class', 'fui-arrow-right');
			$(this).children('span').attr('class', 'fui-arrow-right down_arrow');
		}
	});

	//Initialize navigation event in profile frontpage

	$('#biography').click(function() {
		if ($('#biography_container').length <= 0) {
			$.get('../../ajax/profile/getuserbiography', {
				id : $('#id').val()
			}, function(data) {
				$('#detail_container').append(data);
				$("#biography_container").fadeIn();
				$('.sending_message').click(function() {
					$('#message').trigger('click');
				});
			});
		}
	});

    // adding mobile bio
    if ($('#biography_container').length <= 0) {
        $.get('../../ajax/profile/getuserbiography', {
            id : $('#id').val()
        }, function(data) {
            $('#mobile_bio_container').append(data);
        });
    }

	$('#workedwith').click(function() {
		if ($('#workedwith_container').length <= 0) {
			$.get('../../ajax/profile/getworked_with', {
				id : $('#id').val(),
			}, function(data) {
				$('#detail_container').append(data);
				$("#workedwith_container").fadeIn();
				workedwith_removeborder();
				$('.invite_workwith').click(function() {
					$('#Invite').trigger('click');
				});
			});
		}
	});

    //working with mobile

    if ($('#workedwith_container').length <= 0) {
        $.get('../../ajax/profile/getworked_with', {
            id : $('#id').val(),
        }, function(data) {
            $('#mobile_working_with_container').append(data);
        });
    }

	$('#skills').click(function() {
		if ($('#skills_container').length <= 0) {
			$.get('../../ajax/profile/getskill', {
				id : $('#id').val(),
			}, function(data) {
				$('#detail_container').append(data);
				$("#skills_container").fadeIn();
			});
		}
	});

    //skills mobile

	$('#navigation li').click(function() {
		var tabs = $('#navigation li');
		for (var i = 0; i < tabs.length; i++) {
			if ($(tabs[i])[0] !== $(this)[0]) {
				$(tabs[i]).attr("class", "nav-tab");
				$("#" + tabs[i].id + "_container").hide();
			}
		}
		$(this).attr("class", "nav-tab active");
		$("#" + this.id + "_container").fadeIn();
	});

	$('.invite_project').click(function() {
		$('#Invite').trigger('click');
	});

});

/**
 *This function show the selected biography part (Biography, Photos and Videos) and hide the other unselected parts.
 *
 * @param  jQUery DOM $target		this dom object to be shown
 */
function showBiographyContent($target) {
	$('.biography-content').hide();
	$target.show();
}

function workedwith_removeborder() {
	var $usermember_length = $('.user_profile li').length;
	for ( $i = 1; $i <= $usermember_length / 3; $i++) {
		var $user_li_index = 3 * $i - 1;
		$('.user_profile li:eq(' + $user_li_index + ')').addClass('no-margin-right');
	}
}

function checkhot_linkarrow() {

}

