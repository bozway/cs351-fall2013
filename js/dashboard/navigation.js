/**
 * @author Hao.Caibhaag milkha bhaag
 */
$(document).ready(function(){			
	$(".navigation-link").click(function(){
		
		//hide the current active panel and remove the active-panel mark
		$(".active-panel").hide();
		$(".active-panel").toggleClass('active-panel');
		//show the selected active panel and add the active-panel mark
		$id = $(this).data('id');
		$("#"+$id).fadeIn();
		$("#"+$id).toggleClass('active-panel');
		
		/**
		 * modified by Hao Cai
		 * 
		 * Navigation bar for Dashboard Profile & Message
		 */
		$('.nav-link.active-nav-link').toggleClass('active-nav-link');
		$(this).parent().addClass('active-nav-link');
		$('#dashboard-profile-default-page').addClass('hide');
	});
});