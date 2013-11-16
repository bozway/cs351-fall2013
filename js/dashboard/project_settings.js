$(document).ready(function() {
	
	verticalNavInit();
	
    //var status = $.trim($('#dashboard-project-settings-status').text());
    var currentStatus = $('#project-status').text(); 
    var project_name = $('.dashboard-project-settings-container').data('project-name');
    var project_id = $('.dashboard-project-settings-container').attr('id');

    if (currentStatus === 'COMPLETED') {
        window.location = location.protocol + "//" + location.host + "/dashboard/project/manage";
    }

    $('#dashboard-project-action').click(function() {
    	// Change from ACTIVE to COMPLETED
    	if (currentStatus === 'ACTIVE') {
	    	window.location = location.protocol + "//" + location.host + "/dashboard/project/confirm_completion/" + project_id;
	    } else {
    	
	    	// Change from UNPUBLISHED TO ACTIVE
	        var url = location.protocol + "//" + location.host + "/ajax/project/updateProjectStatus";  
	
	        if (currentStatus === 'UNPUBLISHED') {
	            var r = confirm("Are you sure about changing the status of the project from \"Unpublished\" to \"Active\" ?");
	        } else if (currentStatus === 'ACTIVE') {
	            var r = confirm("Are you sure about changing the status of the project from \"Active\" to \"Completed\" ?");
	        }
	
	        if (r === true && currentStatus === 'UNPUBLISHED') {
	            $.post(url,
	                    {'project_id': project_id,
	                        'project_status': currentStatus
	                    })
	                    .done(function(data) {
	                if ($.trim(data) === '2' || $.trim(data) === '3') {
	                    $('#project-status').text('ACTIVE');
	                    $('#dashboard-project-action').text('Complete Project');
	                } 
	                // else if ($.trim(data) === '4') {
	                    // $('#project-status').text('COMPLETED');
	                    // window.location = location.protocol + "//" + location.host + "/dashboard/project/manage";
	                // }
	            });
	        }
		}
    });



    $('#dashboard-project-delete').click(function() {
        var url = location.protocol + "//" + location.host + "/ajax/project/deleteProject";
        var r = confirm("\"" + project_name + "\" is going to be deleted permanently!!!");

        if (r === true) {
            $.post(url, {'project_id': project_id})
                    .done(function(data) {
                if ($.trim(data) === 'true') {
                    alert("\"" + project_name + "\" has been successfully deleted!!!");
                    window.location = location.protocol + "//" + location.host + "/dashboard/project/manage";
                }
            });
        }
    });
    
    $('.demo-col').hover(function(){
    	$(this).find("a").toggleClass('hover');
    });

});


