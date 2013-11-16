$(function() {

	verticalNavInit();

	$(".applicant-accept").click("accept", processApplicant);

	$(".applicant-hide").click("reject", processApplicant);

	$(".applicant-re-shown").click("reshow", processApplicant);

	$('.sortby-list li').click(function() {
		$(".selected-sort").text($(this).children().text());
		switch ($(this).data('type')) {
		case 0:
			$("#applicant-list > div").tsort('p.applicant-name', {
				cases : false
			});
			break;
		case 1:
			$("#applicant-list > div").tsort('p.applicant-skill', {
				cases : false
			});
			break;
		}
	});
});

/**
 * @author Yongbin Wei
 * @author Waylan Wong
 * @param statusTextObj The event object containing the final status of the 
 * 		applicant if the action is confirmed by the user.
 */
function processApplicant(statusTextObj) {

	if (typeof statusTextObj.data === "undefined") {
		return false;
	}
	var confirmationQuestion = '';
	switch (statusTextObj.data) {
	case "accept":
		confirmationQuestion = "By clicking OK, you will accept this applicant.";
		break;
	case "reject":
		confirmationQuestion = "By clicking OK, you will reject this applicant.";
		break;
	case "reshow":
		confirmationQuestion = "By clicking OK, you can reconsider this applicant.";
	}

	if (confirm(confirmationQuestion)) {
		var pid = $('#project_id').data('id');
		var uid = $(this).data('user-id');
		var sid = $(this).data('skill-id');
		var url = getBaseURL("/ajax/project/updateAuditionStatus");
		var target = $(this).parent();
		$.post(url, {
			'project_id' : pid,
			'user_id' : uid,
			'skill_id' : sid,
			'status' : statusTextObj.data
		}).done(function(responseObj) {
			if (responseObj.errorcode == 0) {
				$(target).remove();
			} else {
				alert(responseObj.message);
			}
		});
	}
}
