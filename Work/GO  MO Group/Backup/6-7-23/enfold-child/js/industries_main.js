(function($) {
	$( document ).ready(function() {
		$('.listing-section').on('click', '#load-more', function (e) {
		  var _this = $(this),
			  posts_per_page = _this.attr('data-posts-per-page'),
			  total = _this.attr('data-total'),
			  offset = _this.attr('data-offset'),
			  current_lang = _this.attr('data-lang');
		  _this.addClass( "disabled-link" );
		  $('.industries-loadmore').addClass('loading');
		  jQuery.ajax({
			type: 'POST',
			dataType: 'JSON',
			//url: ajaxurl,
			url: themeurl+'/get_industries_ajax.php',
			data: {
			  'posts_per_page'  : posts_per_page,
			  'offset'          : offset,
			  'current_lang'    : current_lang,
			},
			success: function(data){
			  //Get new updated offset
			  $('.industries-loadmore').removeClass('loading');
			  if(!data.error){
	  			_this.removeClass( "disabled-link" );
				$(".industries-posts-wrap").append(jQuery(data.html));
				setTimeout(function(){
					$(".industries-posts-wrap").find('.half-width-posts').matchHeight();
				}, 500);
				//console.log('error');
				var pcount = $(".listing-section").find('.posts').length;
				offset = parseInt(pcount);
				//replace offset with new offset for future ajax
				$('.industries-loadmore #load-more').attr('data-offset', offset);
				//if all posts are loaded hide loadmore
				if(pcount < data.found_posts){
				  $('.industries-loadmore').removeClass('hidden');
				}else{
				  $('.industries-loadmore').addClass('hidden');
				}
			  }else{
				$('.industries-loadmore').hide();
			  }
			},
		  });
		  e.preventDefault();
		});
	});//Ready end
})(jQuery); // Fully reference jQuery after this point.
