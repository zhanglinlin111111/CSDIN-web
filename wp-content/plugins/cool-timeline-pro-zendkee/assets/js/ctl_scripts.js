jQuery('document').ready(function($){
	// applied lightbox in story images
	$(".cool_timeline").find("a[class^='ctl_prettyPhoto']").prettyPhoto({
	 social_tools: false 
	 });
	$(".cool_timeline").find("a[rel^='ctl_prettyPhoto']").prettyPhoto({
		social_tools: false
	});

	// enabled slideshow
	var ele_width=$(".cool_timeline").find('.timeline-content').find(".ctl_info").width();
	ele_width=ele_width-20;
	var value =ele_width
    value *= 1;
    var valueHeight = Math.round((value/4)*3);
	var animation= $(".cool_timeline").find('.ctl_flexslider').attr('data-animation');
	var slideshow_op= $(".cool_timeline").find('.ctl_flexslider').attr('data-slideshow');
	
	if(slideshow_op=="true"){
		slideshow=true;
	}else if(slideshow_op=="false"){
			slideshow=false;
	}else{
		slideshow=true;
	}
	var animationSpeed= $(".cool_timeline").find('.ctl_flexslider').attr('data-animationSpeed');
	 $(".cool_timeline").find('.full-width > iframe').height(valueHeight);
	  $(".cool_timeline").find('.ctl_flexslider').flexslider({
		animation:animation,
		slideshow:slideshow,
		rtl: true,
		slideshowSpeed:animationSpeed,
		smoothHeight:true
});


	// creates year scrolling navigation
	var pagination= $(".cool_timeline").attr('data-pagination');
	var pagination_position= $(".cool_timeline").attr('data-pagination-position');
	var bull_cls='';
	var position='';
	if(pagination_position=="left"){
		 bull_cls='section-bullets-left';
		position='left';
	}else if(pagination_position=="right"){
		 bull_cls='section-bullets-right';
		position='right';
	}else if(pagination_position=="bottom"){
		 bull_cls='section-bullets-bottom';
		position='bottom';
	}
	$('.cool_timeline').each(function(index){
		var id=$(this).attr("id");
	if(id!==undefined){
		if(pagination=="yes"){
		  $('body').sectionScroll({
		  // CSS class for bullet navigation
		  bulletsClass:bull_cls,
		  // CSS class for sectioned content
		  sectionsClass:'scrollable-section',
		  // scroll duration in ms
		  scrollDuration: 1500,
		  // displays titles on hover
		  titles: true,
		  // top offset in pixels
		  topOffset:2,
		  // easing opiton
		  easing: '',
		  id:id,
		  position:position,
		});
		}
	}
	});
	
	if(pagination=="yes"){
    $('.ctl-bullets-container').hide();
     $('.cool_timeline').each(function(){
	if (typeof  $(this).attr("id") !== typeof undefined &&  $(this).attr("id") !== false) {
     	var id="#"+ $(this).attr("id");
     	var nav_id="#"+ $(this).attr("id")+'-navi';
     	$(nav_id).find('li').removeClass('active');
     	var offset = $(id).offset();
  		var t_height =$(id).height();
	
	 $(window).scroll(function () {
      var isElementInView = Utils.isElementInView($(id), false);
		if (isElementInView) {
		  $(nav_id).show();
		} else {
		     $(nav_id).hide(); 
		}
    	});
	}
     });
   }

// enabled animation on page scroll
	$(".cooltimeline_cont").each(function(index ){
			var timeline_id=$(this).attr('id');
			var animations=$("#"+timeline_id).attr("data-animations");
		if(animations!="none") {
			// You can also pass an optional settings object
			// below listed default settings
			AOS.init({
				// Global settings:
				disable:'mobile', // accepts following values: 'phone', 'tablet', 'mobile', boolean, expression or function
				startEvent: 'DOMContentLoaded', // name of the event dispatched on the document, that AOS should initialize on
				offset: 75, // offset (in px) from the original trigger point
				delay: 0, // values from 0 to 3000, with step 50ms
				duration: 750, // values from 0 to 3000, with step 50ms
				easing: 'ease-in-out-sine', // default easing for AOS animations
				mirror: true,
			});
				
				}
	});

 function Utils() {
}

Utils.prototype = {
    constructor: Utils,
    isElementInView: function (element, fullyInView) {
        var pageTop = $(window).scrollTop();
        var pageBottom = pageTop + $(window).height();
        var elementTop = parseInt($(element).offset().top)+200;
        var elementBottom = elementTop + parseInt($(element).height())-500;

        if (fullyInView === true) {
            return ((pageTop < elementTop) && (pageBottom > elementBottom));
        } else {
            return ((elementTop <= pageBottom) && (elementBottom >= pageTop));
        }
    }
};

var Utils = new Utils();


});


jQuery(window).on('load', function($) {
	setTimeout(function(){ AOS.refresh(); }, 500);
});