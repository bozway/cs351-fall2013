/**
 *
 * @author Pankaj K. 
 */

$('.helpful-articles-container p a').hover(function(){
	$(this).toggleClass('hover');
});


$('.demo-col').hover(function(){
	$(this).find("a").toggleClass('hover');
});
