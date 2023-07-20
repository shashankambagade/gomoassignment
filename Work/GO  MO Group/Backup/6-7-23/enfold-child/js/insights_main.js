(function($) {
	$( document ).ready(function() {
		jQuery('.half-width-posts').matchHeight();
		jQuery('.insight-filter .selectpicker').change(function () {
			//console.log('loaded');
		  //Display loading... text
		  jQuery(".insights-posts-wrap").addClass('loading').append('<span class="loader"><i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
		  //Send all filters to ajax and fetch result
		  var _this = jQuery(this),
			  offset = 0,
			  group = _this.attr('data-filter-group'),
			  selected_val = _this.val(),
			  optionText = _this.find('option:selected').text();
			  service_filter = jQuery('.selectpicker.service-filter').val(),
			  //current_lang = jQuery('#load-more').attr('data-lang'),
			  //industry_filter = jQuery('.selectpicker.industry-filter').val(),
			  format_filter = jQuery('.selectpicker.format-filter').val(),
			  posts_per_page = jQuery('#load-more').attr('data-initial-offset');
		  
		  //check if any filter is selected
		  count = $('.selectpicker').filter(function () {
			return $(this).val() != '*';
		  });
		  if (count.length == 0) {
			$('.current-filters-section').hide();
		  } else if (count.length > 0) {
			$('.current-filters-section').show();
		  }
		  if(selected_val == '*'){
			_this.closest('.insight-filter').removeClass('active');
			//Remove filter if all option is selected
			$('.current-filters-section .current-filters .filter-in-bucket[data-filter="'+group+'-filter"]').remove();
		  }else{
			//console.log(selected_val);
			_this.closest('.insight-filter').addClass('active');
			if($('.current-filters-section .filter-in-bucket[data-filter="'+group+'-filter"]').length){
			  //If same group filter is exist then update that
			  $('.current-filters-section .filter-in-bucket[data-filter="'+group+'-filter"] span').text(optionText);
			}else{
			  //Add filter in the list with cross button
			  $('.current-filters-section .current-filters').append('<div class="filter-in-bucket" data-filter="'+group+'-filter"><span>'+optionText+'</span><i class="close">X</i></div>');
			}
		  }
		  //var str = "service="+service_filter+"&industry="+industry_filter+"&subject="+subject_filter+"&format="+format_filter;
		  jQuery.ajax({
			type: 'POST',
			dataType: 'JSON',
			url: myAjax.ajaxurl,
			//url: themeurl+'/get_insights_ajax.php',
			data: {
				'action': 'get_insights',
				'posts_per_page'  : posts_per_page,
				'service_filter'  : service_filter,
				'format_filter'   : format_filter,
				//'current_lang'	: current_lang
			},
			success: function(data){
			  jQuery(".insights-posts-wrap .loader").remove();
			  jQuery(".insights-posts-wrap").removeClass('loading');
			  if(!data.error){
				
				$('.insights-loadmore #load-more').attr('data-total', data.found_posts);
				jQuery(".insights-posts-wrap").empty().append(jQuery(data.html));
				setTimeout(function(){
					$(".insights-posts-wrap").find('.half-width-posts').matchHeight();
				}, 500);
				jQuery("#no-posts-msg").hide();
				var pcount = $(".insights-posts-wrap").find('.posts').length;
				offset = parseInt(pcount);
				//console.log('new offset '+offset);
				//replace offset with new offset for future ajax
				$('.insights-loadmore #load-more').attr('data-offset', offset);
				//if all posts are loaded hide loadmore
				if(pcount < data.found_posts){
				  $('.insights-loadmore').removeClass('hidden');
				}else{
				  //console.log(offset + ' ' + data.found_posts);
				  $('.insights-loadmore').addClass('hidden');
				}
			  }else{
				jQuery(".insights-posts-wrap").empty();
				$('.insights-loadmore').addClass('hidden');
				jQuery("#no-posts-msg").show();
			  }
			  /*if (history.pushState) {
				var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?' + str;
				window.history.pushState({path:newurl},'',newurl);
			  }*/
				/*setTimeout(function(){
					$(".insights-posts-wrap").find('.services-item-outer .service-item').matchHeight();
				}, 500);*/
			},
		  });
		});
		//Remove filter
		$('.current-filters-section').on('click','.filter-in-bucket .close', function() {
		  var _this = $(this),
			  filter = _this.closest('.filter-in-bucket').data('filter');
		  //Remove current element from filters list
		  // $('.selectpicker.'+filter).selectpicker('val', '*');
		  // $('.selectpicker.'+filter).selectpicker('refresh');
		  // $('.selectpicker.'+filter).selectpicker('render');
			$('.selectpicker.'+filter).val('*');

		  $('.selectpicker.'+filter).change();
		  _this.fadeOut().remove();
		});
		$('.listing-section').on('click', '#load-more', function (e) {
			//console.log('load more');
		  var _this = $(this),
			  service_filter = $('.selectpicker.service-filter').val(),
			  format_filter = $('.selectpicker.format-filter').val(),
			  posts_per_page = _this.attr('data-posts-per-page'),
			  total = _this.attr('data-total'),
			  offset = _this.attr('data-offset');
			  //current_lang = _this.attr('data-lang');
		  	_this.addClass( "disabled-link" );
		  jQuery.ajax({
			type: 'POST',
			dataType: 'JSON',
			url: myAjax.ajaxurl,
			//url: themeurl+'/get_insights_ajax.php',
			data: {
				'action': 'get_insights',
				'posts_per_page'  : posts_per_page,
				'offset'          : offset,
				//'current_lang'    : current_lang,
				'service_filter'  : service_filter,
				'format_filter'   : format_filter,
			},
			success: function(data){
				//console.log('success');
			  //Get new updated offset
			  $('.insights-loadmore').removeClass('loading');
			  if(!data.error){
		  		_this.removeClass( "disabled-link" );
				$(".insights-posts-wrap").append(jQuery(data.html));
				setTimeout(function(){
					$(".insights-posts-wrap").find('.half-width-posts').matchHeight();
				}, 500);
				//console.log('error');
				//var pcount = $(".insights-posts-wrap").find('.posts').length;	
				var pcount = $(".insights-posts-wrap").find('.posts').length;
				offset = parseInt(pcount);
				//replace offset with new offset for future ajax
				$('.insights-loadmore #load-more').attr('data-offset', offset);
				//if all posts are loaded hide loadmore
				if(pcount < data.found_posts){
				  $('.insights-loadmore').removeClass('hidden');
				}else{
				  //console.log(offset + ' ' + data.found_posts);
				  $('.insights-loadmore').addClass('hidden');
				}
			  }else{
				//console.log('error');
				$('.insights-loadmore').addClass('hidden');
			  }
			},
		  });
		  e.preventDefault();
		});
	});//Ready end

	$(window).load(function(){

	});
})(jQuery); // Fully reference jQuery after this point.
