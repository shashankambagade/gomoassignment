jQuery(document).ready(function(){

	jQuery("#video-background").show().delay(1940).fadeOut();
	jQuery(document).ready(function () {	
        setTimeout(function() {
			jQuery(".home-section").css('opacity', 1);
		}, 1940);
	}); 
	
    /*jQuery('.avia_video').on('ended', function(){
        jQuery('#video-background').fadeOut('1000', function(){
            jQuery('#av-layout-grid-1').css('opacity', 1);
        });
    });*/

    jQuery('.hide-video').click(function(e){
        jQuery('#disappear-block').fadeOut('1000');
        jQuery('#casestudy-scroll-section').css('display', 'block');
        jQuery('#casestudy-mobile').css('display','block');
        jQuery('.bigHeading-fixed').css('opacity', 1);
    });


    jQuery('.avia_video').on('ended', function(){
        jQuery('#disappear-block').fadeOut('1000', function(){
            jQuery('#casestudy-scroll-section').css('display', 'block');
            jQuery('#casestudy-mobile').css('display','block');
            jQuery('.bigHeading-fixed').css('opacity', 1);
        });
    });

    var menuHeight = jQuery('#header').outerHeight();
    var windowHeight = jQuery(window).height();
    var remainingHeight = windowHeight - 170;
    jQuery('#disappear-block .avia_video').height(remainingHeight);

});

function isScrolledIntoView(elem) {
    var docViewTop = jQuery(window).scrollTop();
    var docViewBottom = docViewTop + jQuery(window).height();

    var elemTop = jQuery(elem).offset().top;
    var elemBottom = elemTop + jQuery(elem).height();

    return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
}

    //jQuery('.banner-client-name').text(jQuery('.casestudy-image-slider .image-slide:first-child').find('.client-display:first-child').text());
    jQuery(window).scroll(function (event) {
        jQuery('.image-slide').each(function () {
            if (isScrolledIntoView(this) === true) {
                var _this = jQuery(this);
                _this.addClass('visible');
                jQuery('.banner-client-name').text(_this.find('.client-display').text());
            }else{
                jQuery(this).removeClass('visible');
            }
        });
    });

    jQuery('.banner-client-name').text(jQuery('.casestudy-image-slider .image-slide:first').find('.client-display:first').text());

    /**jQuery('.banner-client-name').text(jQuery('.mobile-slide:first').find('.client-display:first').text());
    function mobile_client_name(data){
    //  console.log(data.realIndex);
        jQuery('.banner-client-name').text(jQuery('.swiper-slide-active').find('.client-display').text());
    } **/

    /** Swiper slider ***/
    var swiper = new Swiper(".mobile-image-slider", {
        slidesPerView: 1.2,
        spaceBetween: 0,
        centeredSlides: true,
        loop: true,  
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
          },
    });

    swiper.on('slideChangeTransitionEnd', function () {
        mobile_client_name(this);

    });


    var swiper = new Swiper(".case-type-a-slider", {
        slidesPerView: 1,
        spaceBetween: 0,
        loop: true,  
        pagination: {
        el: ".swiper-pagination",
        clickable: true,
      },
    });


    var swiper = new Swiper(".team_slider", {
        slidesPerView: 1,
        spaceBetween: 0,
        loop: true,  
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
          },
    });


    var swiper = new Swiper(".case-listing-slider", {
        slidesPerView: 1,
        spaceBetween: 0,
        loop: true,  
        pagination: {
        el: ".swiper-pagination.case",
        clickable: true,
      },
    });


    jQuery(".readmore-link").click( function(e) {
        // record if our text is expanded
        var isExpanded =  jQuery(e.target).hasClass("expand");
        
        //close all open paragraphs
        jQuery(".readmore.expand").removeClass("expand");
        jQuery(".readmore-link.expand").removeClass("expand");
        
        // if target wasn't expand, then expand it
        if (!isExpanded){
            jQuery( e.target ).parent( ".readmore" ).addClass( "expand" );
            jQuery(e.target).addClass("expand");  
        }  
    });
    
    jQuery(function(){
        jQuery.stellar({
            horizontalScrolling: 0,
            verticalOffset: -40
        });		
    });
	
	jQuery(function() {
		jQuery("#archive-browser select").change(function() 
		{
			jQuery("#archive-pot")
				.empty()
				.html("<div style='text-align:center;padding:30px;color:red;font-size:20px;'>Please wait...</div>");
			var fun_id = jQuery("#fun").val();
			var pro_id = jQuery("#pro").val();
			jQuery.ajax
			({
				url: ajaxurl,
				dataType: "html",
				type: "POST",
				data: 
				{
					"itfun_id" : fun_id,
					"itpro_id" : pro_id,
					"action" : "get_posts_from_cat"
				},
				success: function(data) 
				{
					jQuery("#archive-pot").html(data);
				}
			});
		});
	});


    jQuery("select").change(function() {
    // remove "selected" class from previously selected option
    jQuery(this).find("option.selected").removeClass("selected");
    // add "selected" class to newly selected option
    jQuery(this).find("option:selected").addClass("selected");
    });