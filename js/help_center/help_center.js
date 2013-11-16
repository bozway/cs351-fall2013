// JavaScript Document
$(function() {
	var scrollDuration = 1000;
	var scrollEasing = "easeInOutExpo";
	$('.faq_content_right a').click(function(e) {
		e.preventDefault();
		$.scrollTo($(this).attr('href'), scrollDuration, {
			easing : scrollEasing
		});
	});
	$('.faq_a').click(function(e) {
		e.preventDefault();
		$.scrollTo($(this).attr('href'), scrollDuration, {
			easing : scrollEasing
		});
	});
	var sidebarAnchor = $('.top');
	var sidebarAnchorParentElementPosition = $('.faq_content').offset().top;
	//var sidebarAnchorVerticalPosition = sidebarAnchor.offset().top;
	var faq_link_list_bottom_edge = $('.faq_content_right').offset().top + $('.faq_content_right').height();
	var faq_bottom_edge = $('.faq_content_left').offset().top + $('.faq_content_left').height();
	var window_bottom_edge = $(window).scrollTop() + $(window).height(); 
	
	
	
	var header_height = $('.faq_content').offset().top;
	var window_height = $(window).height();
	var scroll_top = 0;
	var max_scroll_top = $('body').height() - $('footer').height() - $('.help_center_footer').height() - header_height;

	$(window).scroll(function() {
		// calculate how far down the page we can see.
		window_bottom_edge = $(window).scrollTop() + $(window).height();
		// check if there is space at the bottom of the list of links to show the "go to top" link 
		if (window_bottom_edge > (faq_link_list_bottom_edge + 40) ) {
			// stop the "go to top" link above the page footer.
			// I have no godamn idea why it doesn't work without -60px. :: waylan
			sidebarAnchor.stop().animate({				
				top : Math.min(window_bottom_edge, faq_bottom_edge) - sidebarAnchorParentElementPosition - 60
			}, 100);
		} else {
			// There is no space, shove the "go to top" link out of sight
			sidebarAnchor.stop().animate({
				top : $('.faq_content_left').height() - sidebarAnchorParentElementPosition
			}, 100);
		}
		
		/**
		if ($(window).scrollTop() > (sidebarAnchorVerticalPosition + 20 - window_height)) {
			scroll_top = ($(window).scrollTop() + window_height	- header_height - 80);
			if (scroll_top > max_scroll_top) {
				scroll_top = max_scroll_top;
			}
			sidebarAnchor.stop().animate({
				top : scroll_top
			}, 100);
		} else {
			sidebarAnchor.stop().animate({
				top : $('.faq_content_left').height()
			}, 100);
		}
		**/
	});

	sidebarAnchor.click(function(e) {
		e.preventDefault();
		$.scrollTo($(this).attr('href'), scrollDuration, {
			easing : scrollEasing
		});
	});
	// ---------------sidebar scroll---------------//
	/*
	 * var sidebar=$('.faq_content_right'); var offset = sidebar.offset(); var
	 * sidebar_height=sidebar.height(); var window_height=$(window).height();
	 * var max_top=$('body').height() - $('footer').height() -
	 * $('.help_center_footer').height() - 70 - sidebar_height - offset.top;
	 * 
	 * var top = 0;
	 * 
	 * $(window).scroll(function() { window_height=$(window).height();
	 * if(sidebar_height >= window_height){ if ($(window).scrollTop() >
	 * (offset.top + sidebar_height + 20 - window_height)) { top =
	 * ($(window).scrollTop() - offset.top - sidebar_height - 20 +
	 * window_height); if(top > max_top){ top = max_top; }
	 * sidebar.stop().animate({top:top},"slow"); }else{
	 * sidebar.stop().animate({top:"2px"},"slow"); } }else{ if
	 * ($(window).scrollTop() > (offset.top - 20)) { top =
	 * ($(window).scrollTop() - offset.top); if(top > (max_top-20)){ top =
	 * max_top - 20; } sidebar.stop().animate({top:top},"slow"); }else{
	 * sidebar.stop().animate({top:"2px"},"slow"); } } });
	 */
});