(function($) {
	//Generic functions
	function set_height(){
		var winht = $(window).height();
		var winwd = $(window).width();
		
		var header = $('#header').height();
		
		$('.home .hem-banner-section > .container').css('height', winht + 'px' );
	}

	$(document).ready(function(){
    	set_height();
	});
	
	$( window ).load(function() {
    	set_height();
    });

	$(window).resize(function() {
    	set_height();
	});

})(jQuery); // Fully reference jQuery after this point.