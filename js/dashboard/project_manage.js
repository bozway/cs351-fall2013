/**
 * list of all project names
 * {
 * 	"unpublished": ["wei project", "hao project"]
 *  "active": ["wei project", "hao project"]
 *  "completed": ["wei project", "hao project"]
 *  "applied": ["wei project", "hao project"]
 * }
 */
var list_project_name = [];

/**
 *  list of current tabs' project names
 * 	["wei project", "hao project"]
 */
var list_current_project_name = [];

//prevent the duplicate unput of textext
var last_userinput = "";

$(function() {
    $(".btn-delete").click(function() {
        if (confirm("Do you really want to delete this project?")) {
        	var project_obj = $(this).parentsUntil(".project").last().parent();
            $.post(location.protocol + '//' + location.hostname + '/ajax/project/deleteProject/1',
                    {project_id: project_obj.data("id")},
		            function(data) {
		                if (data.errorcode == 0) {
		                	//detach the deleted project
		                    project_obj.detach();
		                    //refresh the textext autocomplete array
							list_project_name['unpublished'] = [];
							$("#projects_unpublished_container").find(".project").each(function(){
								list_project_name['unpublished'].push($(this).find(".project-name").text());
							});
							reconstructArray(list_current_project_name, list_project_name['unpublished']);
		                }
		                else{
		                	alert(data.message);
		                }
		            }, "JSON");
        }
    });
    $(".btn-withdraw").click(function(){
    	if(confirm("Do you really want to withdraw this application?")){
            var project_obj = $(this).parentsUntil(".project").last().parent();
            $.post(location.protocol + '//' + location.hostname + '/ajax/project/deleteAudition',
                    {audition_id: $(this).data('auditionid')},
		            function(data) {
		                if (data.errorcode == 0) {
		                	//detach the deleted application
		                    project_obj.detach();
		                    //refresh the textext autocomplete array
							list_project_name['applied'] = [];
							$("#projects_applied_container").find(".project").each(function(){
								list_project_name['applied'].push($(this).find(".project-name").text());
							});
							reconstructArray(list_current_project_name, list_project_name['applied']);
		                }
		                else{
		                	alert(data.message);
		                }
		            }, "JSON");
    	}
    });
	//sort project by name or time
    $(".tool-bar .sort-option").click(function() {
        $(".tool-bar .selected-sort").text($(this).find("a").text());
        sort_projects($(this).data('type'));
    });
	//filter the project by project role, only active and completed tabs have this filter
    $(".project-filter label").click(function() {
        var filter_value = $(this).find("input").val();
        $("#project_search").val("");
        filter(filter_value);
    });
	//show and hide the project filter since only active and completed tabs have this filter
	//and also change the textext autocomplete value list
    $(".nav-container li").click(function() {
        switch($(this).data('category')){
        	case 0:
        		reconstructArray(list_current_project_name, list_project_name['unpublished']);
	        	$(".project-filter").hide();
	        	break;
        	case 1:
        		reconstructArray(list_current_project_name, list_project_name['active']);
	        	$(".project-filter").show();
	        	break;
        	case 2:
        		reconstructArray(list_current_project_name, list_project_name['completed']);
	        	$(".project-filter").show();
	        	break;
        	case 3:
        		reconstructArray(list_current_project_name, list_project_name['applied']);
	        	$(".project-filter").hide();
	        	break;
        }
    });
    
    //initialize textext for the project search
    $(document).one('extract_projectName_post', function(){
    	reconstructArray(list_current_project_name, list_project_name['active']);
    	$("#project_search").textext({
			plugins : 'autocomplete suggestions filter',	
			suggestions : list_current_project_name,
			filter : list_current_project_name,
			autocomplete : {
				dropdownPosition: 'below',
				dropdownMaxHeight:	'75px',		
				dropdownTop:		'45px'
			},
			ext: {
				core: {
						onSetFormData: function(e, data) {
							// Fixes issue where the hidden input field has double quotation marks
							var self = this;
							self.hiddenInput().val(data);
							var userValue = self.hiddenInput().val();
							// This is to prevent textext from giving me duplicate values - bug - need to keep another variable, preventing some inputs	
							var crudeHash = userValue; 
							if (crudeHash != last_userinput && userValue != "") {
								last_userinput = crudeHash;
								searchProject(userValue);
							}	            
						}			
					}			    
				}			
		});
	});
	//search the project according to search input value when focus out
	$("#project_search").focusout(function(){
		setTimeout(function() {
			searchProject($("#project_search").val());
		}, 500, self);
	});
	$("#project_search_btn").click(function(){
		return false;
	});
	
    //extract the project names for the use of textext
    extractProjectNames();
});
//filter by project role
function filter(value) {
	var list = $("#projects_active_container .project, #projects_completed_container .project");
	var ALL = $(".project-filter").find('input[type="radio"]').first().val();
	list.each(function(){
		if(value == ALL){
			$(this).show();
		}
		else{
			if($(this).data('role') == value){
				$(this).show();
			}
			else{
				$(this).hide();
			}
		}
	});
}
//sort the project by name or time
function sort_projects(value){
	switch(value){
		case 0:
			$(".project").tsort(".project-name", {cases : false});
			break;
		case 1:
			$(".project").tsort({data : 'time', order : 'desc'});
			break;
	}
}
//extract the project names from html for the use of textext
function extractProjectNames(){
	list_project_name = [];
	list_project_name['unpublished'] = [];
	$("#projects_unpublished_container").find(".project").each(function(){
		list_project_name['unpublished'].push($(this).find(".project-name").text());
	});
	list_project_name['active'] = [];
	$("#projects_active_container").find(".project").each(function(){
		list_project_name['active'].push($(this).find(".project-name").text());
	});
	list_project_name['completed'] = [];
	$("#projects_completed_container").find(".project").each(function(){
		list_project_name['completed'].push($(this).find(".project-name").text());
	});
	list_project_name['applied'] = [];
	$("#projects_applied_container").find(".project").each(function(){
		list_project_name['applied'].push($(this).find(".project-name").text());
	});
	$(document).trigger('extract_projectName_post');
}
//search the projects by name and show them
function searchProject(project_name){
	$(".project").each(function(){
		if($(this).find(".project-name").text() == project_name || project_name == ""){
			$(this).show();
		}
		else{
			$(this).hide();
		}
	});
}
/**
 * reconstructArray(originalArray, newArray)
 * this function reconstructs original array to have the same elements as newArray, return an array that has
 * the same reference as originalArray has
 */ 
function reconstructArray(originalArray, newArray){
	originalArray.splice(0, originalArray.length);
	for(i in newArray){
		originalArray.push(newArray[i]);
	}	
}
