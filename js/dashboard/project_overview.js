$(document).ready(function() {
	
	verticalNavInit();
	
	$("#show-past-member").click( function() {
		if($(this).data('toggle')) {
			$('#past-member').slideToggle();
			$('#show-past-member p a').text('Show Past Member');
			$(this).data('toggle', 0);
		} else {
			$('#past-member').slideToggle();
			$('#show-past-member p a').text('Hide Past Member');
			$(this).data('toggle', 1);
		}
	});
    $('#dashboard-action-project').click(function() {
        
        var r = confirm("Are you sure about changing the status of the project to \"Complete\" ?");
        if (r === true) {
            $.ajax({
                url: location.protocol + "//" + location.host + "/ajax/project/updateProjectStatus",
                type: "POST",
                data: {
                    project_id: $('#project-id').data('project-id')
                }
            }).done(function(responseObj) {
                if (responseObj.errorcode === 0) {
                	window.location = location.protocol + '//' + location.hostname + '/dashboard/project/overview'+$('#project-id').data('project-id');
                } else {
                    alert(responseObj.message);
                }
            });
        }
    });


    $('.dashboard-project-member-kickout').click(function() {
        var target = this;
        if(confirm("By clicking OK, the member will be removed from your project.")) {
	        $.ajax({
	            url: getBaseURL("/ajax/project/deleteProjectMember"),
	            type: "POST",
	            data: {
	                project_id: $('#project_id').data('project-id'),
	                member_id: $(target).data('member-id')
	            }
	        }).done(function(responseObj) {
	            if (responseObj.errorcode === 0) {
	            	var target_item = $(target).parent().parent().parent().parent();
	            	$(target).remove();
	            	$(target_item).detach();
	            	$(target_item).appendTo('#past-member');
	            } else {
	                alert(responseObj.message);
	            }
	        });
        }
    });

    var projectId = $('#project_id').data('project-id');

    $('#dashboard-leave-project').click(function() {

        var userId = $('#dashboard-leave-project').data('user-id');
        var url = location.protocol + '//' + location.hostname + '/ajax/project/deleteProjectMember';

        if (confirm("Do you really want to leave this project?")) {
            $.post(url,
                    {'project_id': projectId,
                        'member_id': userId}
            ).done(function(data) {
                if (data.errorcode === 0) {
                    window.location = location.protocol + '//' + location.hostname + '/dashboard/project/manage';
                }
                else {
                    alert(data.message);
                }
            }, "JSON");
        }
    });

    $('#dashboard-view-profile').click(function() {
        window.location = location.protocol + "//" + location.host + "/projects/profile/" + projectId;
    });

});


