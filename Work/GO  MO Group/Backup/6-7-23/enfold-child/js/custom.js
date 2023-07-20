function set_height(){
	var winht = jQuery(window).height();
	var winwd = jQuery(window).width();
	
	var header = jQuery('#header').height();
	
	jQuery('.home .hem-banner-section > .container').css('height', winht + 'px' );
}

// Code to block email id: no-replyzew@gmail.com on contact forms
jQuery(document).ready(function(){
	jQuery('.wpcf7-form .btn').click(function(e){
		if(document.querySelectorAll('.wpcf7-form .wpcf7-email')[0].value == "no-replyzew@gmail.com"){
			e.preventDefault();
			throw new Error("There is some error while submitting the form!");
		}
		return true;
	});

	//Avoid multiple form submissions in cf7
	var disableSubmit = false;
	var glang = jQuery('html').attr('lang');
	var submitTextDefault = "Submit";
	var submitTextBefore = "Sending..";
	var submitTextAfter = "Sent!";
	
	if(glang == 'sv-SE'){
		submitTextDefault = 'Skicka';
		submitTextBefore = 'Skickar..';
		submitTextAfter = 'Skicka';
	}
	jQuery('input.wpcf7-submit[type="submit"]').click(function() {
	    jQuery(':input[type="submit"]').attr('value',submitTextBefore);
	    if (disableSubmit == true) {
	        return false;
	    }
	    disableSubmit = true;
	    return true;
	})
	document.addEventListener('wpcf7mailsent', function(event) {
	    jQuery(':input[type="submit"]').attr('value',submitTextAfter);
	    disableSubmit = false;
		console.log('mail sent');
	}, false );

	document.addEventListener( 'wpcf7invalid', function( event ) {
	    jQuery(':input[type="submit"]').attr('value',submitTextDefault)
	    disableSubmit = false;
		console.log('mail error');
	}, false );

	//
	jQuery('.letsdiscuss textarea[name="your-message"]').on('input', function () {
		this.style.height = '30px';
			
		this.style.height = 
				(this.scrollHeight) + 'px';
	});

	if(jQuery('body').hasClass('home')){
		set_height();
	}

	var swiper1 = new Swiper('.swiper-container.article-slider', {
		fadeEffect: { crossFade: true },
		virtualTranslate: true,
		effect: "fade",
		speed: 600,
		slidesPerView: 1,
		spaceBetween: 20,
		centeredSlides: false,
		loop: true,
		allowTouchMove: true,
		navigation: {
			nextEl: '.swiper-button-next',
			prevEl: '.swiper-button-prev',
		},
		breakpoints:{
			1024: {
				slidesPerView: 1,
			},
			768: {
				slidesPerView: 1,
			},
		}
	});
	
	var swiper2 = new Swiper('.swiper-container.press-slider', {
		speed: 1000,
		slidesPerView: 2.5,
		spaceBetween: 20,
		allowTouchMove: true,
		mousewheel: true,
        pagination: {
          el: ".swiper-pagination",
          type: "progressbar",
        }, 
		navigation: {
			nextEl: '.swiper-button-next-press',
			prevEl: '.swiper-button-prev-press',
		},
		breakpoints:{
			1600: {
				slidesPerView: 2.5,
			},
			1400: {
				slidesPerView: 2.5,
			},
			1024: {
				slidesPerView: 2,
			},
			768: {
				slidesPerView: 1,
			},
			320: {
				slidesPerView: 1,
			},
		}
	});

	var swiper3 = new Swiper('.swiper-container.press-slider.news-slider', {
		speed: 1000,
		slidesPerView: 2.5,
		spaceBetween: 20,
		allowTouchMove: true,
		pagination: {
			el: ".swiper-pagination",
			type: "progressbar",
		}, 
		navigation: {
			nextEl: '.swiper-button-next.new',
			prevEl: '.swiper-button-prev.new',
		},
		breakpoints:{
			1600: {
				slidesPerView: 2.5,
			},
			1400: {
				slidesPerView: 2.5,
			},
			1024: {
				slidesPerView: 2,
			},
			768: {
				slidesPerView: 1,
			},
			320: {
				slidesPerView: 1,
			},
		}
	});

	var swiper4 = new Swiper('.swiper-container.faq-slider', {
		slidesPerView: 1,
		spaceBetween: 100,
		allowTouchMove: true,
		pagination: {
		  el: ".swiper-pagination",
		  type: "progressbar",
		clickable: true,
		}, 
		navigation: {
			nextEl: '.swiper-button-next.next-faq',
			prevEl: '.swiper-button-prev.prev-faq',
		},
	});
	
	jQuery('.press-slider .swiper-pagination-number span').click(function(){
		var _this = jQuery(this);
		_this.siblings().removeClass('active')
		_this.addClass('active');
		swiper2.slideTo(_this.attr('data-slide'));
		swiper2.update();
		//swiper2.pagination.update();
	});
	swiper2.on('slideChange', function() {
	    swiper2.pagination.render();
	    swiper2.pagination.update();
	});

	/*jQuery(document).on('click', '.wpcf7-submit', function(e){
	    if( jQuery('.wpcf7-form').hasClass('submitting') ) {
			e.preventDefault();
			jQuery('.wpcf7-submit').prop('disabled', true);
			return false;
	    }
	});*/

	jQuery('.news-slider .swiper-pagination-number span').click(function(){
		var _this = jQuery(this);
		_this.siblings().removeClass('active')
		_this.addClass('active');
		swiper3.slideTo(_this.attr('data-slide'));
		swiper3.update();
		//swiper2.pagination.update();
	});
	swiper3.on('slideChange', function() {
	    swiper3.pagination.render();
	    swiper3.pagination.update();
	});

	jQuery(document).on('click', '.social-share .print-btn', function(e) {
		e.preventDefault();
		window.print();
	});

	
	jQuery(".back-to-insights").click(function(event) {
		event.preventDefault();
		window.history.back();
	});
	
	
	jQuery('img[title]').each(function(){
		jQuery(this).removeAttr('title');
	});
	
	// jQuery('.avia-search-tooltip').remove();
	// jQuery("#menu-item-search a").attr("data-avia-search-tooltip","");
	// jQuery("#menu-item-search").click(function(e){
	// 	document.getElementById("myOverlay").style.display = "block";
	// });
	// jQuery("#myOverlay #searchsubmit").click(function(e){
	// 	jQuery("#myOverlay #searchform").submit();
	// });
	// jQuery(".closebtn").click(function(e){
	// 	document.getElementById("myOverlay").style.display = "none";
	// });
	// jQuery('.overlay').click(function(e) {
 //      var elm = jQuery(this);
 //    	y = e.pageY - elm.offset().top;
	// 		if(y>70){
	// 			document.getElementById("myOverlay").style.display = "none";
	// 		}
 //  });
		
	jQuery.noConflict()(function($){
		$(document).ready(function(){
			$(' .swiper-pagination-number span:first').addClass('active');
		});
	});
	
	jQuery('.wpcf7submit').click(function(){
		jQuery(this).attr('disabled', true);
	});
	
	document.addEventListener( 'wpcf7submit', function( event ) {
		jQuery(this).prop('disabled', true);
		var status = event.detail.status;
		//console.log(status);
		var _this = jQuery(this);
		_this.attr("disabled", true);

		if(status == 'mail_sent'){
			_this.attr("disabled", true);
		}else{
			_this.attr("disabled", false);
		}
	}, false );

	/*jQuery('.wpcf7-submit').on('click',function(){
    var v = jQuery(this).val();
    jQuery(this).val(v + ' ...');
	});*/

	jQuery('.faq-slider .swiper-pagination-number span').click(function(){
		var _this = jQuery(this);
		_this.siblings().removeClass('active')
		_this.addClass('active');
		swiper4.slideTo(_this.attr('data-slide'));
		swiper4.update();
		//swiper3.pagination.update();
  	});

  swiper4.on('slideChange', function() {
	    swiper4.pagination.render();
	    swiper4.pagination.update();
	});

  //readmore
  jQuery(".read-more-btn").click(function(e){
		e.preventDefault();
		jQuery(this).addClass("hide");
		jQuery(".read-more-section").addClass("show");
	});

	jQuery(".read-less-btn").click(function(e){
		e.preventDefault();
		jQuery(".read-more-btn").removeClass("hide");
		jQuery(".read-more-section").removeClass("show");
	});
	
	// jQuery("#footer-form-msg.wpcf7-textarea").focus(function(){
	// 	jQuery(this).css("width","80%");
	// 	jQuery(this).css("min-height","60px");
	// 	jQuery(this).css("text-align","left");
	// });
	
	// jQuery("#footer-form-msg.wpcf7-textarea").blur(function(){
	// 	if(jQuery(this).val() == ''){
	// 		jQuery(this).css("width","300px");
	// 		jQuery(this).css("min-height","20px");
	// 		jQuery(this).css("text-align","center");
	// 	}
	// });

//contact form text area extend as per content

	
/*jQuery("#footer-form-msg.wpcf7-textarea").on("input",function(){
   jQuery(this).height("auto").height(jQuery(this)[0].scrollHeight);
});*/
	
/*jQuery('#footer-form-msg.wpcf7-textarea').keydown(function(){  
	var contents = jQuery(this).val();      
	var charlength = contents.length;  
	newwidth =  charlength*7;
	jQuery(this).css(
		{
			//'width':newwidth,
			//'height': 'auto',
			'overflow-y': 'auto'
		}
	);
});*/ 
/*jQuery('#footer-form-msg.wpcf7-textarea').keydown(function(){  
	if(jQuery('#footer-form-msg').val().length>55){
		jQuery('#footer-form-msg').css('width','700px');
		jQuery('#footer-form-msg').css('max-width','700px');
	}else if(jQuery('#footer-form-msg').val().length>35){
		jQuery('#footer-form-msg').css('width','450px');
		jQuery('#footer-form-msg').css('max-width','450px');
	}
});*/

	var winwd = jQuery(window).width();
	if(winwd < 991){
		jQuery('.footer-wrap #text-2.widget').insertAfter('#text-10');
		jQuery('.footer-wrap #text-8.widget').insertAfter('#text-11');

		jQuery('.footer-wrap .widget > .widgettitle').click(function(){
	        var _this = jQuery(this);
			var wd = _this.next();
			var wd_parent = _this.closest('.widget');
	        if(wd_parent.is('#text-10') || wd_parent.is('#text-11')){
				//console.log('#text-3');
				return;
			}else{
				if(wd_parent.hasClass('open')){
					wd.slideUp();
					wd_parent.removeClass('open');
				}else{
					wd.slideDown();
					wd_parent.addClass('open');
				}
			}
		});
	}
});

jQuery.noConflict()(function($){

  $(window).scroll(function(){
      if ($(this).scrollTop() > 50) {
         $('.stretched').addClass('all-sticky');
         $('.rmp_menu_trigger').addClass('box-sticky');
      } else {
         $('.stretched').removeClass('all-sticky');
         $('.rmp_menu_trigger').removeClass('box-sticky');
      }
  });
  
  // $(window).scroll(function(){
		// if ($(this).scrollTop() > 150) {
  //        $('.avia-team-members ').addClass('side-sticky');
		// }
		// if ($(this).scrollTop() > 1000) { 
  //        $('.avia-team-members ').removeClass('side-sticky');  
		// }
  // });

});




jQuery(window).load(function() {
	if(jQuery('body').hasClass('home')){
		set_height();
	}
});
jQuery(window).resize(function() {
	if(jQuery('body').hasClass('home')){
		set_height();
	}
});

jQuery(".readmore-link").click(function(e) {
    // record if our text is expanded
    var isExpanded =  jQuery(e.target).hasClass("expand");
    
    //close all open paragraphs
    jQuery(".readmore.expand").removeClass("expand");
    jQuery(".readmore-link.expand").removeClass("expand");
    
    // if target wasn't expand, then expand it
    if (!isExpanded){
        jQuery(e.target ).parent(".readmore").addClass("expand");
        jQuery(e.target).addClass("expand");  
    }  
});
