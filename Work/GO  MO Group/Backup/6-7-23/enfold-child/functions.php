<?php
/*
* Add your own functions here. You can also copy some of the theme functions into this file.
* Wordpress will use those functions instead of the original functions then.
*/
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles', 99 );
function theme_enqueue_styles() {
	//Stylesheets
	wp_enqueue_style('parent-style', get_template_directory_uri().'/style.css');
	wp_enqueue_style('custom-style', get_stylesheet_directory_uri().'/css/custom-style.css');
	wp_enqueue_style('swiper-css', get_stylesheet_directory_uri().'/css/swiper-bundle.min.css');
	wp_enqueue_style('print-css', get_stylesheet_directory_uri().'/css/print.css', array(), '0.1', 'print');
	wp_enqueue_style('child-style', get_stylesheet_directory_uri().'/style.css');

	//Javascripts
	wp_enqueue_script('jquery');
	wp_enqueue_script('custom-js-gdpr', get_stylesheet_directory_uri() .'/js/custom-gdpr.js', array( 'jquery' ),'1.0.0', true);
	wp_enqueue_script('swiper-js', get_stylesheet_directory_uri().'/js/swiper-bundle.min.js', array('jquery'), '2.0.0', true);
	//wp_enqueue_script('exclude-from-nitropack', get_stylesheet_directory_uri() .'/js/exclude-from-nitropack.js', array( 'jquery' ),'1.0.0', true);	
	wp_enqueue_script('jQuery-validate', 'https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js');
	wp_enqueue_script('jQuery-additional-methods', 'https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js');
	if( is_page(353) || is_page(15116) || is_page(12519) || is_page(15196) ){
		//only for listing pages 
		wp_enqueue_script( 'matchheight_js', get_stylesheet_directory_uri() . '/js/jquery.matchHeight-min.js', array('jquery'), false, true );
	}
	//Dont change the sequence as insights_js and industries_js depends on it
	wp_enqueue_script('custom-js', get_stylesheet_directory_uri().'/js/custom.js');
	
	if( is_page(353) || is_page(15116) ){
		wp_enqueue_script( 'insights_js', get_stylesheet_directory_uri() . '/js/insights_main.js', array('jquery', 'custom-js'), false, true );
		wp_localize_script( 'insights_js', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));	
	}
	if( is_page(12519) || is_page(15196) ){
		wp_enqueue_script( 'industries_js', get_stylesheet_directory_uri() . '/js/industries_main.js', array('jquery', 'custom-js'), false, true );
	}

}
/*****************************************************
******* Ubermenu integration with Enfold theme ******
*****************************************************
add_filter( 'avf_main_menu_nav' , 'um_enfold_menu' , 100 , 1 );
function um_enfold_menu( $menu ){
	if( function_exists( 'ubermenu' ) ){
		$menu = ubermenu( 'main' , array( 'theme_location' => 'avia' , 'echo' => false ) );
	}
	return $menu;
}
*/
/*************************************
** Slider code
**************************************/

// Excerpt count
add_filter( 'excerpt_length', 'custom_excerpt_length', 9999 );
function custom_excerpt_length( $length ) {
	return 20;
}


function get_adjacent_posts($args) {
	global $post;

	$all_posts = get_posts($args);
	$len = count($all_posts);
	$np = null;
	$cp = $post;
	$pp = null;

	if ($len > 1) {
		for ($i=0; $i < $len; $i++) {
			if ($all_posts[$i]->ID === $cp->ID) {
				if (array_key_exists($i-1, $all_posts)) {
					$pp = $all_posts[$i-1];
				} else {
					$new_key = $len-1;
					$pp = $all_posts[$new_key];

					while ($pp->ID === $cp->ID) {
						$new_key -= 1;
						$pp = $all_posts[$new_key];
					}
				}

				if (array_key_exists($i+1, $all_posts)) {
					$np = $all_posts[$i+1];
				} else {
					$new_key = 0;
					$np = $all_posts[$new_key];

					while ($pp->ID === $cp->ID) {
						$new_key += 1;
						$np = $all_posts[$new_key];
					}
				}

				break;
			}
		}
	}

	return array('next' => $np, 'prev' => $pp);
}

// Home Featured carousel
function home_article_carousel_function(){
	$args = array(
		'post_type' => 'insights',
		'posts_per_page' => 6,
		'post_status' => 'publish',
		'orderby'   => 'menu_order',
		'order'=>'ASC',
		'meta_key' => 'use_as_home_featured',
		'meta_value' => '1',
		'suppress_filters' => true,
	);
	/*if(ICL_LANGUAGE_CODE == "en"){
		$args['tax_query'][] = array(
			'taxonomy' => 'insight_language',
			'field'    => 'slug',
			'terms'    => 'english',
		);
	}
	if(ICL_LANGUAGE_CODE == "sv"){
		$args['tax_query'][] = array(
			'taxonomy' => 'insight_language',
			'field'    => 'slug',
			'terms'    => 'swedish',
		);
	}*/
	$query = new WP_Query($args);
	$html = "";
	if($query->have_posts()){
		$html .= '<div class="swiper-container article-slider">';
			$html .= '<div class="swiper-wrapper">';
				while($query->have_posts()){
					$query->the_post();

					$html .= '<div class="swiper-slide">
									<div class="slider-top">
										<div class="col-md-2 article-content">
											<div class="content">
											<p><a href="'.get_the_permalink().'">
												<span class="slider-title">'.get_the_title().'</span>
											</a></p>
											<img class="show-only-for-mob" src="'.get_the_post_thumbnail_url(get_the_ID(), 'full').'" alt="" />
											<div class="show-only-for-desk">
												<p class="excerpt-text">'.get_the_excerpt().'</p>
												<p><a href="'.get_post_permalink().'" class="btn">';
													if(ICL_LANGUAGE_CODE == "sv"){
														$html .= 'Läs mer';
													}else{
														$html .= 'READ MORE';
													}
												$html .= '</a></p></div>';
									$html .= '</div></div>
										<div class="col-md-2">
											<img class="show-only-for-desk" src="'.get_the_post_thumbnail_url(get_the_ID(), 'full').'" alt="" />
										</div>
									</div>';
									$html .= '<div class="row"><div class="col-md-3"></div>';
									$html .= '<div class="next-article-slider col-md-6"  style="position: relative;">';
									$adjacent = get_adjacent_posts($args);

									$next_title = $adjacent['next']->post_title;
									$next_image = get_the_post_thumbnail_url($adjacent['next']->ID, 'thumbnail');
									$next_url = get_permalink($adjacent['next']);

									$prev_title = $adjacent['prev']->post_title;
									$ev_image = get_the_post_thumbnail_url($adjacent['next']->ID, 'thumbnail');
									$prev_url = get_permalink($adjacent['prev']);
									
									$html .= '<div class="next-article-wrap">
										<div class="next-article-img"><img src="'.$next_image.'" alt="" /></div>
										<div class="next-article-text"><div class="">'; 
											if(ICL_LANGUAGE_CODE == "sv"){
												$html .= 'NÄSTA ARTIKEL';
											}else{
												$html .= 'NEXT ARTICLE';
											}
									$html .=  '</div>'.$next_title.'</div><div class="slider-btn-wrap"><div class="swiper-button-next"></div><div class="swiper-button-prev"></div></div>
									</div>';
									
									$html .= '</div>';
									$html .= '<div class="col-md-3"></div></div>
									<div class="show-only-for-mob">
									<p class="excerpt-text">'.get_the_excerpt().'</p>
											<p><a href="'.get_post_permalink().'" class="btn">';
												if(ICL_LANGUAGE_CODE == "sv"){
													$html .= 'Läs mer';
												}else{
													$html .= 'READ MORE';
												}
											$html .= '</a></p></div>';
					$html .= '</div>';
					}
			wp_reset_postdata();
			$html .= '</div>';
		$html .= '</div>';
		return $html;
	}
}
add_shortcode( 'home_article_carousel', 'home_article_carousel_function' );

//
function article_carousel_function(){
	$args = array(
		'post_type' => 'insights',
		'posts_per_page' => 6,
		'post_status' => 'publish',
		'orderby'   => 'menu_order',
		'order'=>'DESC',
		'meta_key' => 'use_as_featured',
		'meta_value' => '1',
		'suppress_filters' => true,
	);
	/*if(ICL_LANGUAGE_CODE == "en"){
		$args['tax_query'][] = array(
			'taxonomy' => 'insight_language',
			'field'    => 'slug',
			'terms'    => 'english',
		);
	}
	if(ICL_LANGUAGE_CODE == "sv"){
		$args['tax_query'][] = array(
			'taxonomy' => 'insight_language',
			'field'    => 'slug',
			'terms'    => 'swedish',
		);
	}*/
	$query = new WP_Query($args);
	$html = "";
	if($query->have_posts()){
		$html .= '<div class="swiper-container article-slider">';
			$html .= '<div class="swiper-wrapper">';
				while($query->have_posts()){
					$query->the_post();

					$html .= '<div class="swiper-slide">
									<div class="slider-top">
										<div class="col-md-2 article-content">
											<div class="content">
											<p><a href="'.get_the_permalink().'">
												<span class="slider-title">'.get_the_title().'</span>
											</a></p>
											<img class="show-only-for-mob" src="'.get_the_post_thumbnail_url(get_the_ID(), 'full').'" alt="" />
											<p class="excerpt-text">'.get_the_excerpt().'</p>
											<p><a href="'.get_post_permalink().'" class="btn">';
												if(ICL_LANGUAGE_CODE == "sv"){
													$html .= 'Läs mer';
												}else{
													$html .= 'READ MORE';
												}
											$html .= '</a></p>';
									$html .= '</div></div>
										<div class="col-md-2">
											<img class="show-only-for-desk" src="'.get_the_post_thumbnail_url(get_the_ID(), 'full').'" alt="" />
										</div>
									</div>';
									$html .= '<div class="row"><div class="col-md-3"></div>';
									$html .= '<div class="next-article-slider col-md-6"  style="position: relative;">';
									$adjacent = get_adjacent_posts($args);

									$next_title = $adjacent['next']->post_title;
									$next_image = get_the_post_thumbnail_url($adjacent['next']->ID, 'thumbnail');
									$next_url = get_permalink($adjacent['next']);

									$prev_title = $adjacent['prev']->post_title;
									$ev_image = get_the_post_thumbnail_url($adjacent['next']->ID, 'thumbnail');
									$prev_url = get_permalink($adjacent['prev']);
									
									$html .= '<div class="next-article-wrap">
										<div class="next-article-img"><a href="'.$next_url.'"><img src="'.$next_image.'" alt="" /></a></div>
										<a href="'.$next_url.'" style="text-decoration: inherit; color: inherit;"><div class="next-article-text"><div class="">'; 
											if(ICL_LANGUAGE_CODE == "sv"){
												$html .= 'NÄSTA ARTIKEL';
											}else{
												$html .= 'NEXT ARTICLE';
											}
									$html .=  '</div>'.$next_title.'</div></a><div class="slider-btn-wrap"><div class="swiper-button-next"></div><div class="swiper-button-prev"></div></div>
									</div>';
									
									$html .= '</div>';
									$html .= '<div class="col-md-3"></div></div>';
					$html .= '</div>';
					}
			wp_reset_postdata();
			$html .= '</div>';
		$html .= '</div>';
		return $html;
	}
}
add_shortcode( 'article_carousel', 'article_carousel_function' );

/*** Press Slider ***/
function press_carousel_function(){
ob_start();
	$args = array(
		'post_type' => 'insights',
		'posts_per_page' => 6,
		'post__not_in' => array(get_the_ID()),
		'orderby' => 'publish_date', 
		'order' => 'DESC',
		'meta_key' => 'use_as_featured',
		'meta_value' => '1',  
		'suppress_filters' => true,
	);
	/*if(ICL_LANGUAGE_CODE == "en"){
		$args['tax_query'][] = array(
			'taxonomy' => 'insight_language',
			'field'    => 'slug',
			'terms'    => 'english',
		);
	}
	if(ICL_LANGUAGE_CODE == "sv"){
		$args['tax_query'][] = array(
			'taxonomy' => 'insight_language',
			'field'    => 'slug',
			'terms'    => 'swedish',
		);
	}*/
	$query = new WP_Query($args);
	if($query->have_posts()){
		$html = "";
		$pagination = '';
		$i = 1;
		$html .= '<div class="swiper-container press-slider">';
			$html .= '<div class="swiper-wrapper">';
				while($query->have_posts()){
					$query->the_post();
					$image = get_field('carousel_image');
					$html .= '<div class="swiper-slide">
								<div class="outer-wrap"><a href="'.get_post_permalink().'" class="full-box-click">';
								if( !empty( $image ) ){
									$html .= '<img src="'.esc_url($image['url']).'" alt="'.esc_attr($image['alt']).'" />';
								}else{
									$html .= '<img src="'.get_the_post_thumbnail_url(get_the_ID(), 'medium').'" alt="" />';
								}
									$html .= '</a><div class="overlay-box">
										<p class="slider-title"><a href="'.get_the_permalink().'">'.get_the_title().'
										</a></p>
										<a href="'.get_post_permalink().'" class="btn">';
										if(ICL_LANGUAGE_CODE == "sv"){
											$html .= 'Läs mer';
										}else{
											$html .= 'READ MORE';
										}
									  $html .= '</a>
									</div>
								</div>';
					$html .= '</div>';
					$pagination .= '<span class="" data-slide="'.$i.'">'.sprintf("%02d", $i).'</span>';
					$i++;
				}
				wp_reset_postdata();
			$html .= '</div>';
			$html .= '<div class="swiper-pagination"></div>
					  <div class="pagination-number">
							<div class="smalltext">';
							if(ICL_LANGUAGE_CODE == "sv"){
								$html .= 'POPULÄRASTE INSIKTERNA';
							}else{
								$html .= 'Popular Insights';
							}
			$html .= '		</div>
						<div class="swiper-pagination-number">'.$pagination.'</div>
						</div>';
		$html .= '</div>';
		echo $html;
		return ob_get_clean();
	}
}
add_shortcode( 'press_carousel', 'press_carousel_function' );

/*** GOMO News Slider ***/
function news_carousel_function(){
ob_start();
	$args = array(
		'post_type' => 'press',
		'posts_per_page' => -1,
		'orderby' => 'date',
		'order'   => 'DESC',
		'meta_key' => 'use_as_featured',
		'meta_value' => '1',
		'suppress_filters' => true,
	);
	$query = new WP_Query($args);
	if($query->have_posts()){
		$html = "";
		$pagination = '';
		$i = 1;
		$html .= '<div class="swiper-container press-slider news-slider">';
			$html .= '<div class="swiper-wrapper">';
				while($query->have_posts()){
					$query->the_post();
					$image = get_field('carousel_image');
					$newslink = get_field('news_link');
					$html .= '<div class="swiper-slide">
								<div class="outer-wrap">';
								if( !empty( $image ) ){
									$html .= '<img src="'.esc_url($image['url']).'" alt="'.esc_attr($image['alt']).'" />';
								}else{
									$html .= '<img src="'.get_the_post_thumbnail_url(get_the_ID(), 'medium').'" alt="" />';
								}
									$html .= '<div class="overlay-box">
										<p class="slider-title"><a href="'.esc_url( $newslink ).'" target="_blank">'.get_the_title().'
										</a></p>
										<a href="'.esc_url( $newslink ).'" target="_blank" class="btn">';
										if(ICL_LANGUAGE_CODE == "sv"){
											$html .= 'Läs mer';
										}else{
											$html .= 'READ MORE';
										}
									  $html .= '</a>
									</div>
								</div>';
					$html .= '</div>';
					$pagination .= '<span class="" data-slide="'.$i.'">'.sprintf("%02d", $i).'</span>';
					$i++;
				}
				wp_reset_postdata();
			$html .= '</div>';
			$html .= '<div class="swiper-pagination"></div>
					 		<div class="pagination-number">
							<div class="smalltext">';
							if(ICL_LANGUAGE_CODE == "sv"){
								$html .= 'UTVALDA ARTIKLAR';
							}else{
								$html .= 'FEATURED ARTICLES';
							}
			$html .= '		</div>
						<div class="swiper-pagination-number">'.$pagination.'</div>
						</div>';
		$html .= '</div>';
		echo $html;
		return ob_get_clean();
	}
}
add_shortcode( 'news_carousel', 'news_carousel_function' );

function faq_carousel_function(){
	ob_start();
	$pagination = '';
	$i = 1;
	$html = $question = $answer = "";
	$slides = array();
	if( have_rows('faqs') ){ 
		while ( have_rows('faqs') ){
			the_row();
			$question = get_sub_field('question');
			$answer = get_sub_field('answer');
			$slides[] = array('question' => $question, 'answer' => $answer );
		}
	}
	$final_arr = array_chunk($slides, 6);
	if($final_arr){
		$html .= '<div class="swiper-container faq-slider">';
			$html .= '<div class="swiper-wrapper">';
				$count = 0;
				foreach($final_arr as $slide){
					$html .= '<div class="swiper-slide"><div class="togglecontainer av-elegant-toggle enable_toggles">';
						foreach($slide as $acc){
							$html .= '<section class="av_toggle_section">
									<div role="tablist" class="single_toggle">
										<p data-fake-id="#toggle-id-'.$count.'" class="toggler" itemprop="name" role="tab" tabindex="0" aria-controls="toggle-id-'.$count.'">'.$acc['question'].'<span class="toggle_icon"><span class="vert_icon"></span><span class="hor_icon"></span></span></p>
										<div id="toggle-id-'.$count.'" class="toggle_wrap" itemscope="itemscope" itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"><div class="toggle_content invers-color" itemprop="text">'.$acc['answer'].'</div></div>
									</div>
								</section>';
								$count++;
						}
					$html .= '</div></div>';
					$pagination .= '<span class="" data-slide="'.$i.'">'.sprintf("%02d", $i).'</span>';
					$i++;
				}
		$html .= '</div>';
		$html .= '<div class="swiper-pagination"></div>
					<div class="pagination-number">
							<div class="smalltext">';
							if(ICL_LANGUAGE_CODE == "sv"){
								$html .= 'Vanliga frågor';
							}else{
								$html .= 'Popular FAQ ';
							}
			$html .= '		</div>
						<div class="swiper-pagination-number">'.$pagination.'</div>
						</div>';
		$html .= '</div>';
	}
	echo $html;
	
	//FAQ Schama
	?>
	<script type="application/ld+json">
	{
	    "@context": "https://schema.org",
	    "@type": "FAQPage",
	    "mainEntity": [
	    <?php if( have_rows('faqs') ): ?>
	        <?php while ( have_rows('faqs') ) : the_row(); ?>
	        {
	            "@type": "Question",
	            "name": "<?php echo wp_strip_all_tags(get_sub_field('question')); ?>",
	            "acceptedAnswer": {
	                "@type": "Answer",
	                "text": "<?php echo preg_replace( '/(^|[^\n\r])[\r\n](?![\n\r])/', '$1 ', wp_strip_all_tags(get_sub_field('answer')) ); ?>"
	            }
	        },
	        <?php endwhile; ?>
	    <?php endif; ?>
	    <?php if( have_rows('faqs') ): ?>
	        <?php $rows_cnt = count(get_field('faqs')); ?>
	        <?php while ( have_rows('faqs') ) : the_row(); ?>
	        {
	            "@type": "Question",
	            "name": "<?php echo wp_strip_all_tags(get_sub_field('question')); ?>",
	            "acceptedAnswer": {
	                "@type": "Answer",
	                "text": "<?php echo preg_replace( '/(^|[^\n\r])[\r\n](?![\n\r])/', '$1 ', wp_strip_all_tags(get_sub_field('answer')) ); ?>"
	            }
	        <?php $index = get_row_index(); ?>
	        <?php if($index == $rows_cnt){ ?>
	        }
	        <?php }else{ ?>
	        },
	        <?php } ?>
	        <?php endwhile; ?>
	    <?php endif; ?>
	  ]
	}
	</script>
	<?php

	
	return ob_get_clean();
}
add_shortcode( 'faq_carousel', 'faq_carousel_function' );



/*************************************
** Register Custom Post Types
**************************************/
add_action( 'init', 'register_post_type_init' );
function register_post_type_init() {
// Blog Authors
	$labels = array(
		'name'               => _x( 'Blog Author', 'Blog Author', 'gomo' ),
		'singular_name'      => _x( 'Blog Author', 'Blog Author', 'gomo' ),
		'menu_name'          => _x( 'Blog Author', 'admin menu', 'gomo' ),
		'name_admin_bar'     => _x( 'Blog Author', 'add new on admin bar', 'gomo' ),
		'add_new'            => _x( 'Add New', 'Blog Author', 'gomo' ),
		'add_new_item'       => __( 'Add New Blog Author', 'gomo' ),
		'new_item'           => __( 'New Blog Author', 'gomo' ),
		'edit_item'          => __( 'Edit Blog Author', 'gomo' ),
		'view_item'          => __( 'View Blog Author', 'gomo' ),
		'all_items'          => __( 'All Blog Author', 'gomo' ),
		'search_items'       => __( 'Search Blog Author', 'gomo' ),
		'parent_item_colon'  => __( 'Parent Blog Author:', 'gomo' ),
		'not_found'          => __( 'No Blog Author found.', 'gomo' ),
		'not_found_in_trash' => __( 'No Blog Author found in Trash.', 'gomo' )
	);
	$args = array(
		'labels'             => $labels,
		'description'        => __( 'Description.', 'gomo' ),
		'menu_icon'			 => 'dashicons-businessperson',
		'public'             => false,
		'publicly_queryable' => false,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'blog-author' ),
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array('title', 'editor', 'excerpt',  'thumbnail')
	);
	register_post_type( 'blog-author', $args );

// Team
	$labels = array(
		'name'               => _x( 'Team Members', 'Team Members', 'gomo' ),
		'singular_name'      => _x( 'Team Member', 'Team Member', 'gomo' ),
		'menu_name'          => _x( 'Team Members', 'admin menu', 'gomo' ),
		'name_admin_bar'     => _x( 'Team Member', 'add new on admin bar', 'gomo' ),
		'add_new'            => _x( 'Add New', 'Team Members', 'gomo' ),
		'add_new_item'       => __( 'Add New Team Members', 'gomo' ),
		'new_item'           => __( 'New Team Member', 'gomo' ),
		'edit_item'          => __( 'Edit Team Member', 'gomo' ),
		'view_item'          => __( 'View Team Member', 'gomo' ),
		'all_items'          => __( 'All Team Members', 'gomo' ),
		'search_items'       => __( 'Search Team Member', 'gomo' ),
		'parent_item_colon'  => __( 'Parent Team Member:', 'gomo' ),
		'not_found'          => __( 'No Team Member found.', 'gomo' ),
		'not_found_in_trash' => __( 'No Team Member found in Trash.', 'gomo' )
	);
	$args = array(
		'labels'             => $labels,
		'description'        => __( 'Description.', 'gomo' ),
		'menu_icon'			 => 'dashicons-businessperson',
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'medarbetare' ),
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array('title', 'editor', 'excerpt', 'custom-fields', 'revisions', 'thumbnail', 'page-attributes')
	);
	register_post_type( 'team', $args );
	
	//team categoery taxonomy
	$labels = array(
		'name'              => _x( 'Team Categories', 'taxonomy general name' ),
		'singular_name'     => _x( 'Team Category', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Categories' ),
		'all_items'         => __( 'All Categories' ),
		'parent_item'       => __( 'Parent Category' ),
		'parent_item_colon' => __( 'Parent Category:' ),
		'edit_item'         => __( 'Edit Category' ),
		'update_item'       => __( 'Update Category' ),
		'add_new_item'      => __( 'Add New Category' ),
		'new_item_name'     => __( 'New Category Name' ),
		'menu_name'         => __( 'Team Categories' ),
	);
	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'team-categories' ),
	);
	register_taxonomy( 'team_category', array( 'team' ), $args );

//Insights
	$labels = array(
		'name'               => _x( 'Insights', 'post type general name', 'gomo' ),
		'singular_name'      => _x( 'Insight', 'post type singular name', 'gomo' ),
		'menu_name'          => _x( 'Insights', 'admin menu', 'gomo' ),
		'name_admin_bar'     => _x( 'Insight', 'add new on admin bar', 'gomo' ),
		'add_new'            => _x( 'Add New', 'new casestudy', 'gomo' ),
		'add_new_item'       => __( 'Add New Insight', 'gomo' ),
		'new_item'           => __( 'New Insight', 'gomo' ),
		'edit_item'          => __( 'Edit Insight', 'gomo' ),
		'view_item'          => __( 'View Insight', 'gomo' ),
		'all_items'          => __( 'All Insights', 'gomo' ),
		'search_items'       => __( 'Search Insights', 'gomo' ),
		'parent_item_colon'  => __( 'Parent Insights:', 'gomo' ),
		'not_found'          => __( 'No Insights found.', 'gomo' ),
		'not_found_in_trash' => __( 'No Insights found in Trash.', 'gomo' ),
	);
	$args = array(
		'labels'             => $labels,
		'description'        => __( 'Description.', 'gomo' ),
		'menu_icon'			 => 'dashicons-analytics',
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'insikter' ),
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => true,
		'menu_position'      => null,
		'supports'           => array('title', 'editor', 'excerpt', 'custom-fields', 'revisions', 'thumbnail', 'page-attributes')
	);
	register_post_type( 'insights', $args );
	
// Industry
	$labels = array(
		'name'               => _x( 'Industry', 'post type general name', 'gomo' ),
		'singular_name'      => _x( 'Industry', 'post type singular name', 'gomo' ),
		'menu_name'          => _x( 'Industries', 'admin menu', 'gomo' ),
		'name_admin_bar'     => _x( 'Industry', 'add new on admin bar', 'gomo' ),
		'add_new'            => _x( 'Add New', 'new industry', 'gomo' ),
		'add_new_item'       => __( 'Add New industry', 'gomo' ),
		'new_item'           => __( 'New Industry', 'gomo' ),
		'edit_item'          => __( 'Edit Industry', 'gomo' ),
		'view_item'          => __( 'View Industry', 'gomo' ),
		'all_items'          => __( 'All Industries', 'gomo' ),
		'search_items'       => __( 'Search Industries', 'gomo' ),
		'parent_item_colon'  => __( 'Parent Industries:', 'gomo' ),
		'not_found'          => __( 'No Industries found.', 'gomo' ),
		'not_found_in_trash' => __( 'No Industries found in Trash.', 'gomo' ),
	);
	$args = array(
		'labels'             => $labels,
		'description'        => __( 'Description.', 'gomo' ),
		'menu_icon'			 => 'dashicons-store',
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'case' ),
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => true,
		'menu_position'      => null,
		'supports'           => array('title', 'editor', 'excerpt', 'custom-fields', 'revisions', 'thumbnail', 'page-attributes')
	);
	register_post_type( 'industries', $args );
	
	//Industry category taxonomy
	$labels = array(
		'name'              => _x( 'Industries Categories', 'taxonomy general name' ),
		'singular_name'     => _x( 'Industries Category', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Categories' ),
		'all_items'         => __( 'All Categories' ),
		'parent_item'       => __( 'Parent Category' ),
		'parent_item_colon' => __( 'Parent Category:' ),
		'edit_item'         => __( 'Edit Category' ),
		'update_item'       => __( 'Update Category' ),
		'add_new_item'      => __( 'Add New Category' ),
		'new_item_name'     => __( 'New Category Name' ),
		'menu_name'         => __( 'industries Categories' ),
	);
	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'industries-categories' ),
	);
	register_taxonomy( 'industries_category', array( 'industries' ), $args );
	
// Job Posts
	$labels = array(
		'name'               => _x( 'Job Posts', 'post type general name', 'gomo' ),
		'singular_name'      => _x( 'Job Post', 'post type singular name', 'gomo' ),
		'menu_name'          => _x( 'Job Posts', 'admin menu', 'gomo' ),
		'name_admin_bar'     => _x( 'Job Posts', 'add new on admin bar', 'gomo' ),
		'add_new'            => _x( 'Add New', 'new job post', 'gomo' ),
		'add_new_item'       => __( 'Add New Job Post', 'gomo' ),
		'new_item'           => __( 'New Job Post', 'gomo' ),
		'edit_item'          => __( 'Edit Job Post', 'gomo' ),
		'view_item'          => __( 'View Job Post', 'gomo' ),
		'all_items'          => __( 'All Job Posts', 'gomo' ),
		'search_items'       => __( 'Search Job Posts', 'gomo' ),
		'parent_item_colon'  => __( 'Parent Job Posts:', 'gomo' ),
		'not_found'          => __( 'No Job Posts found.', 'gomo' ),
		'not_found_in_trash' => __( 'No Job Posts found in Trash.', 'gomo' ),
	);
	$args = array(
		'labels'             => $labels,
		'description'        => __( 'Description.', 'gomo' ),
		'menu_icon'			 => 'dashicons-portfolio',
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'karriar' ),
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => true,
		'menu_position'      => null,
		'supports'           => array('title', 'editor', 'custom-fields', 'revisions', 'thumbnail', 'page-attributes')
	);
	register_post_type( 'job-posts', $args );

	//Job-location taxonomy
	$labels = array(
		'name'              => _x( 'Job Locations', 'taxonomy general name' ),
		'singular_name'     => _x( 'Job Location', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Categories' ),
		'all_items'         => __( 'All Categories' ),
		'parent_item'       => __( 'Parent Category' ),
		'parent_item_colon' => __( 'Parent Category:' ),
		'edit_item'         => __( 'Edit Category' ),
		'update_item'       => __( 'Update Category' ),
		'add_new_item'      => __( 'Add New Category' ),
		'new_item_name'     => __( 'New Category Name' ),
		'menu_name'         => __( 'Job Locations' ),
	);
	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'locations' ),
	);
	register_taxonomy( 'job-location', array( 'job-posts' ), $args );
	
// Services
	$labels = array(
		'name'               => _x( 'Services', 'post type general name', 'gomo' ),
		'singular_name'      => _x( 'Service', 'post type singular name', 'gomo' ),
		'menu_name'          => _x( 'Services', 'admin menu', 'gomo' ),
		'name_admin_bar'     => _x( 'Services', 'add new on admin bar', 'gomo' ),
		'add_new'            => _x( 'Add New', 'new service', 'gomo' ),
		'add_new_item'       => __( 'Add New Service', 'gomo' ),
		'new_item'           => __( 'New Service', 'gomo' ),
		'edit_item'          => __( 'Edit Service', 'gomo' ),
		'view_item'          => __( 'View Service', 'gomo' ),
		'all_items'          => __( 'All Services', 'gomo' ),
		'search_items'       => __( 'Search Services', 'gomo' ),
		'parent_item_colon'  => __( 'Parent Services:', 'gomo' ),
		'not_found'          => __( 'No Services found.', 'gomo' ),
		'not_found_in_trash' => __( 'No Services found in Trash.', 'gomo' ),
	);
	$args = array(
		'labels'             => $labels,
		'description'        => __( 'Description.', 'gomo' ),
		'menu_icon'			 => 'dashicons-clipboard',
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'vara-tjanster' ),
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => true,
		'menu_position'      => null,
		'supports'           => array('title', 'editor', 'custom-fields', 'revisions', 'thumbnail', 'page-attributes')
	);
	register_post_type( 'services', $args );

// FAQs
	/*$labels = array(
		'name'               => _x( 'FAQs', 'post type general name', 'gomo' ),
		'singular_name'      => _x( 'FAQ', 'post type singular name', 'gomo' ),
		'menu_name'          => _x( 'FAQs', 'admin menu', 'gomo' ),
		'name_admin_bar'     => _x( 'FAQs', 'add new on admin bar', 'gomo' ),
		'add_new'            => _x( 'Add New', 'new faq', 'gomo' ),
		'add_new_item'       => __( 'Add New FAQ', 'gomo' ),
		'new_item'           => __( 'New FAQ', 'gomo' ),
		'edit_item'          => __( 'Edit FAQ', 'gomo' ),
		'view_item'          => __( 'View FAQ', 'gomo' ),
		'all_items'          => __( 'All FAQs', 'gomo' ),
		'search_items'       => __( 'Search FAQs', 'gomo' ),
		'parent_item_colon'  => __( 'Parent FAQs:', 'gomo' ),
		'not_found'          => __( 'No FAQs found.', 'gomo' ),
		'not_found_in_trash' => __( 'No FAQs found in Trash.', 'gomo' ),
	);
	$args = array(
		'labels'             => $labels,
		'description'        => __( 'Description.', 'gomo' ),
		'menu_icon'			 => 'dashicons-testimonial',
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'faq' ),
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array('title', 'editor', 'custom-fields', 'revisions', 'thumbnail', 'page-attributes')
	);
	register_post_type( 'faq', $args );*/
}
add_action( 'init', 'register_taxonomies_init' );

function register_taxonomies_init() {
	//Insight Language taxonomy
	$labels = array(
		'name'              => _x( 'Insight Language', 'taxonomy general name' ),
		'singular_name'     => _x( 'Insight Language', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Languages' ),
		'all_items'         => __( 'All Languages' ),
		'parent_item'       => __( 'Parent Language' ),
		'parent_item_colon' => __( 'Parent Language:' ),
		'edit_item'         => __( 'Edit Language' ),
		'update_item'       => __( 'Update Language' ),
		'add_new_item'      => __( 'Add New Language' ),
		'new_item_name'     => __( 'New Language Name' ),
		'menu_name'         => __( 'Insight Languages' ),
	);
	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'insight-language' ),
	);
	register_taxonomy( 'insight_language', array( 'insights' ), $args );
	
	//Insight category taxonomy
	$labels = array(
		'name'              => _x( 'Insight Categories', 'taxonomy general name' ),
		'singular_name'     => _x( 'Insight Category', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Categories' ),
		'all_items'         => __( 'All Categories' ),
		'parent_item'       => __( 'Parent Category' ),
		'parent_item_colon' => __( 'Parent Category:' ),
		'edit_item'         => __( 'Edit Category' ),
		'update_item'       => __( 'Update Category' ),
		'add_new_item'      => __( 'Add New Category' ),
		'new_item_name'     => __( 'New Category Name' ),
		'menu_name'         => __( 'Insight Categories' ),
	);
	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'insight-categories' ),
	);
	register_taxonomy( 'insight_category', array( 'insights' ), $args );
	
	//Insight tag taxonomy
	/*$labels = array(
		'name'              => _x( 'Insight Tags', 'taxonomy general name' ),
		'singular_name'     => _x( 'Insight Tag', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Tags' ),
		'all_items'         => __( 'All Tags' ),
		'parent_item'       => __( 'Parent Tag' ),
		'parent_item_colon' => __( 'Parent Tag:' ),
		'edit_item'         => __( 'Edit Tag' ),
		'update_item'       => __( 'Update Tag' ),
		'add_new_item'      => __( 'Add New Tag' ),
		'new_item_name'     => __( 'New Tag Name' ),
		'menu_name'         => __( 'Insight Tags' ),
	);
	$args = array(
		'hierarchical'      => false,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'insight-tag' ),
	);
	register_taxonomy( 'insight_tag', array( 'insights' ), $args );*/
	
	//Insight subject taxonomy
	/*$labels = array(
		'name'              => _x( 'Insight Subjects', 'taxonomy general name' ),
		'singular_name'     => _x( 'Insight Subject', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Subjects' ),
		'all_items'         => __( 'All Subjects' ),
		'parent_item'       => __( 'Parent Subject' ),
		'parent_item_colon' => __( 'Parent Subject:' ),
		'edit_item'         => __( 'Edit Subject' ),
		'update_item'       => __( 'Update Subject' ),
		'add_new_item'      => __( 'Add New Subject' ),
		'new_item_name'     => __( 'New Subject Name' ),
		'menu_name'         => __( 'Insight Subjects' ),
	);
	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'insight-subjects' ),
	);
	register_taxonomy( 'insight_subject', array( 'insights' ), $args );*/
	
	//Insight format taxonomy
	$labels = array(
		'name'              => _x( 'Insight Format', 'taxonomy general name' ),
		'singular_name'     => _x( 'Insight Format', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Format' ),
		'all_items'         => __( 'All Formats' ),
		'parent_item'       => __( 'Parent Format' ),
		'parent_item_colon' => __( 'Parent Format:' ),
		'edit_item'         => __( 'Edit Format' ),
		'update_item'       => __( 'Update Format' ),
		'add_new_item'      => __( 'Add New Format' ),
		'new_item_name'     => __( 'New Format Name' ),
		'menu_name'         => __( 'Insight Format' ),
	);
	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'insight-format' ),
	);
	register_taxonomy( 'insight_format', array( 'insights' ), $args );
}

//Press
	$labels = array(
		'name'               => _x( 'Press', 'post type general name', 'gomo' ),
		'singular_name'      => _x( 'Press', 'post type singular name', 'gomo' ),
		'menu_name'          => _x( 'Press', 'admin menu', 'gomo' ),
		'name_admin_bar'     => _x( 'Press', 'add new on admin bar', 'gomo' ),
		'add_new'            => _x( 'Add New', 'new casestudy', 'gomo' ),
		'add_new_item'       => __( 'Add New Press', 'gomo' ),
		'new_item'           => __( 'New Press', 'gomo' ),
		'edit_item'          => __( 'Edit Press', 'gomo' ),
		'view_item'          => __( 'View Press', 'gomo' ),
		'all_items'          => __( 'All Press', 'gomo' ),
		'search_items'       => __( 'Search Press', 'gomo' ),
		'parent_item_colon'  => __( 'Parent Press:', 'gomo' ),
		'not_found'          => __( 'No Press found.', 'gomo' ),
		'not_found_in_trash' => __( 'No Press found in Trash.', 'gomo' ),
	);
	$args = array(
		'labels'             => $labels,
		'description'        => __( 'Description.', 'gomo' ),
		'menu_icon'			 => 'dashicons-analytics',
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'press' ),
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => true,
		'menu_position'      => null,
		'supports'           => array('title', 'editor', 'excerpt', 'custom-fields', 'revisions', 'thumbnail', 'page-attributes')
	);
	register_post_type( 'press', $args );

add_filter('avf_builder_boxes', 'add_builder_to_posttype');
function add_builder_to_posttype($metabox){
foreach($metabox as &$meta){
if($meta['id'] == 'avia_builder' || $meta['id'] == 'layout'){
	$meta['page'][] = 'industries';
	$meta['page'][] = 'services';
	$meta['page'][] = 'insights';
	//$meta['page'][] = 'faq';
	$meta['page'][] = 'team';
	$meta['page'][] = 'job-posts';
	$meta['page'][] = 'press';
	//$meta['page'][] = 'popup';
}
}
return $metabox;
}

function my_alb_supported_post_types( array $supported_post_types ){
	$supported_post_types[] = 'industries';
	$supported_post_types[] = 'services';
	$supported_post_types[] = 'insights';
	//$supported_post_types[] = 'faq';
	$supported_post_types[] = 'team';
	$supported_post_types[] = 'job-posts';
	return $supported_post_types;
}
add_filter('avf_alb_supported_post_types', 'my_alb_supported_post_types', 10, 1);

/*add_filter('avf_builder_boxes', 'add_builder_to_posttype');
function add_builder_to_posttype($metabox){
	foreach($metabox as &$meta){
		if($meta['id'] == 'avia_builder' || $meta['id'] == 'layout'){
			$meta['page'][] = 'industries';
			$meta['page'][] = 'services';
			$meta['page'][] = 'insights';
				}
	}
	return $metabox;
}*/
/* to support displaying custom post types
add_theme_support('add_avia_builder_post_type_option');
add_theme_support('avia_template_builder_custom_post_type_grid');
/* to display advanced portfolio setting
add_filter('avf_builder_boxes','enable_boxes_on_posts');
function enable_boxes_on_posts($boxes) {
  $boxes[] = array( 'title' =>__('Avia Layout Builder','avia_framework' ), 'id'=>'avia_builder', 'page'=>array('portfolio', 'page', 'post'), 'context'=>'normal', 'expandable'=>true );
  $boxes[] = array( 'title' =>__('Layout','avia_framework' ), 'id'=>'layout', 'page'=>array('portfolio', 'page', 'post'), 'context'=>'side', 'priority'=>'low');
  //$boxes[] = array( 'title' =>__('Additional Portfolio Settings','avia_framework' ), 'id'=>'preview', 'page'=>array('portfolio', 'project'), 'context'=>'normal', 'priority'=>'high' );
  return $boxes;
}*/
// Industries List
add_shortcode( 'industries-list', 'industries_list_function' );
function industries_list_function( $atts ) {
	ob_start();
	$initial_offset = 5;
	$posts_per_page = 6;
	$args = array(
		'post_type' => 'industries',
		'post_status' => 'publish',
		'posts_per_page' => $initial_offset,
		'order' => 'ASC',
		'orderby' => 'menu_order',
		'suppress_filters' => false,
	);

	$query = new WP_Query($args);
	$posts_count = $query->found_posts;
	$max_pages = $query->max_num_pages;
	if ( $query->have_posts() ) { ?>
	<div class="industries-archieve home-slider listing-section avia-section container_wrap fullsize">
		<div class="cycle-slideshow home-slideshow industries-posts-wrap flex_column_table sc-av_one_half av-equal-height-column-flextable">
			<?php
				$i=1;
				$html = '';
				while ( $query->have_posts() ) : $query->the_post();
					//$imgurl = get_the_post_thumbnail( get_the_ID(), 'full' );
					$img = get_field("case_listing_image");
					$imgurl = $img['url'];
					if(!$imgurl){
						$imgurl = get_stylesheet_directory_uri().'/images/post-placeholder.png';
						//$imgurl = '<img src="'.$url.'">';
					}
						
					$terms = get_the_terms(get_the_id(), 'industries_category');

					$display_cat = '';
					if($terms){
						$display_cat = $terms[0]->name;
					}
			?>
			<?php if($i == 1){
			$html .= '<div class="posts full-width-posts">';
				$html .= '<div class="industries-banner"><a href="'.get_permalink().'"><img src="'.$imgurl.'"></a></div>';
				$html .= '<div class="caption">';
					$html .= '<div class="con">';
						$html .= '<div class="left-side-tile">';
							  $html .= '<p class="category-pull">'.$display_cat.'</p>';
							  $html .= '<h4><a href="'.get_permalink().'">'.get_the_title().'</a></h4>';
						$html .= '</div>';
						$html .= '<div class="right-side-excerpt">';
							  //$html .= '<p>'.substr(get_the_excerpt(), 0, strrpos(substr(get_the_excerpt(), 0, 250), ' ')).'...</p>';
							  $html .= '<p>'.get_the_excerpt().'</p>';
							  $html .= '<a href="'.get_permalink().'" class="avia-button avia-color-theme-color">';
								if(ICL_LANGUAGE_CODE == "sv"){
									$html .= 'Läs mer';
								}else{
									$html .= 'READ MORE';
								}
							  $html .= '</a>';
						$html .= '</div>';
					$html .= '</div>';
			   $html .= '</div>
				</div>';
			}else{
				$imgurl = get_the_post_thumbnail( get_the_ID(), 'full' );
				if(!$imgurl){
					$url = get_stylesheet_directory_uri().'/images/post-placeholder.png';
					$imgurl = '<img src="'.$url.'">';
				}
				$html .= '<div class="posts half-width-posts flex_column av_one_half av-equal-height-column av-align-top">
							<div class="industries-banner"><a href="'.get_permalink().'">'.$imgurl.'</a></div>
							<div class="caption">
								<div class="con">
								  <p class="category-pull">'.$display_cat.'</p>
								  <h4><a href="'.get_permalink().'">'.get_the_title().'</a></h4>
								  <p>'.get_the_excerpt().'</p>
								  <a href="'.get_permalink().'" class="avia-button avia-color-theme-color-subtle">';
									if(ICL_LANGUAGE_CODE == "sv"){
										$html .= 'Läs mer';
									}else{
										$html .= 'READ MORE';
									}
								  $html .= '</a>
								</div>
							</div>
						</div>';
					} ?>
			<?php $i++; ?>
			<?php endwhile;
			wp_reset_postdata();
			echo $html; ?>
		</div>
		<?php 
		$cls = '';
		if( ($max_pages) && ( $posts_count <= $initial_offset ) ){ $cls = 'hidden';} ?>
		<div class="text-center industries-loadmore <?php echo $cls; ?>" style="clear:both; float: none; text-align: center;">
			<a id="load-more" class="gomo-loadmore avia-button avia-color-theme-color" data-posts-per-page="<?php echo $posts_per_page; ?>" data-initial-offset="<?php echo $initial_offset; ?>" data-offset="5" data-total="<?php echo $posts_count; ?>" data-lang="<?php if (defined('ICL_LANGUAGE_CODE')){echo ICL_LANGUAGE_CODE;} ?>" href="#"><?php if(ICL_LANGUAGE_CODE == "en"){echo 'MORE CASES';}else{ echo 'Läs mer';} ?></a>
		</div>
	</div>
	<?php 
	}
	$myvariable = ob_get_clean();
	return $myvariable;
}
// shortcode code ends here


/*********** Insikter List *********/
add_shortcode( 'insikter-list', 'insikterlist_function' );
function insikterlist_function( $atts ) {
	ob_start();
	?>
	<style type="text/css">
.filters-section{
	padding-top: 30px;
	padding-bottom: 30px;
	border-bottom: 1px solid #dbdbdb;
}
.filters-section h3{
	font-family: 'Roboto Slab', serif;
	font-size: 32px;
	line-height: 35px;
	font-weight: bold;
	letter-spacing: -1px;
	color: #0e4d78;
}
.current-filters-section{
	display: none;
	padding-top: 30px;
	padding-bottom: 30px;
}
.current-filters-section .current-filter-text{
	display: inline-block;
	font-size: 19px;
	line-height: 24px;
	letter-spacing: 1px;
	color: #fff;
	text-transform: uppercase;
}
.current-filters-section .current-filters{
	display: inline-block;
	padding-left: 30px;
}
.current-filters-section .filter-in-bucket{
	display: inline-block;
	border: 0;
	margin-right: 10px;
	position: relative;
	-webkit-transition: all 500ms ease;
	-moz-transition: all 500ms ease;
	-o-transition: all 500ms ease;
	-ms-transition: all 500ms ease;
	transition: all 500ms ease;
	background: #fff;
}
.current-filters-section .current-filters span{
	display: inline-block;
	font-size: 14px;
	line-height: 16px;
	font-weight: 400;
	color: #101820;
	padding: 6px 8px;
}
.current-filters-section .filter-in-bucket .close{
	padding: 6px 12px;
	font-size: 16px;
	font-weight: 100;
	background: #1c9ada !important;
	color: #fff;
	display: inline-block;
	cursor: pointer;
	-webkit-transition: all 500ms ease;
	-moz-transition: all 500ms ease;
	-o-transition: all 500ms ease;
	-ms-transition: all 500ms ease;
	transition: all 500ms ease;
	/*filter: alpha(opacity=50);
	opacity: 0.5;*/
	text-shadow: none;
}
.current-filters-section .filter-in-bucket .close:hover{
	background: #C41818;
	color: #fff;
	text-decoration: none;
	filter: alpha(opacity=100);
	opacity: 1;
}
.page-template-page-insights .blogs-section .post-block{
	margin-bottom: 40px;
}
.page-template-page-insights .blogs-section{
	padding-bottom: 20px;
}
.insights-posts-wrap{
	position: relative;
}
.insights-posts-wrap.loading .service-item{
	opacity: 0.3;
}
.insights-posts-wrap .loader{
	display: none;
	position: absolute;
	font-size: 40px;
	color: #0e4d78;
	top: 20%;
	left: 0;
	right: 0;
	text-align: center;
	z-index: 2;
}
.insights-posts-wrap.loading .loader{
	display: block;
}
.insights-loadmore {
	margin-bottom: 30px;
}
.insights-loadmore .small-loader{
	margin-left: 10px;
	display: none;
	position: absolute;
	top: 15px;
}
.insights-loadmore.loading .small-loader{
	display: inline-block;
}
@media (min-width: 992px){
	.col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12,
	.col-lg-1, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-lg-10, .col-lg-11, .col-lg-12,{
		float: left;
	}
	.col-md-3 {
		width: 25%;
	}
	.col-md-4 {
		width: 33.33333333%;
	}
	.col-md-9 {
		width: 75%;
	}
}
@media (min-width: 1200px){
	.col-lg-3{
		width: 25%;
	}
}
</style>

<div class='container_wrap container_wrap_first main_color <?php avia_layout_class( 'main' ); ?>'>
	<div class='container template-blog template-single-blog '>
		<div class="container-fluid no-padding filters-section section-wrap" style="padding-top: 60px;">
			<div class="col-xs-12 col-md-3">
				<h3>
					<?php if(ICL_LANGUAGE_CODE == "sv"){ ?>Filtrera resultat <?php }else{ ?> Filter Result <?php } ?>
				</h3>
			</div>
			<?php
				$initial_offset = 5;
				$posts_per_page = 6;
				$args = array(
					'post_type' => 'insights',
					'post_status' => 'publish',
					'posts_per_page' => $initial_offset,
					'orderby' => 'publish_date', 
					'order' => 'ASEC',
					'suppress_filters' => true,
				);
				/*if(ICL_LANGUAGE_CODE == "en"){
					$args['tax_query'][] = array(
						'taxonomy' => 'insight_language',
						'field'    => 'slug',
						'terms'    => 'english',
					);
				}
				if(ICL_LANGUAGE_CODE == "sv"){
					$args['tax_query'][] = array(
						'taxonomy' => 'insight_language',
						'field'    => 'slug',
						'terms'    => 'swedish',
					);
				}*/
				$the_query = new WP_Query($args);
				//print_r($the_query);exit;
				$posts_count = $the_query->found_posts;
				$max_pages = $the_query->max_num_pages;
				$html = "";
				if( $the_query->have_posts() ){
					$i = 1;
					while( $the_query->have_posts() ){
						$the_query->the_post();
						$insight_classes = array();
						$cat = '';
						$listingimage = get_field('listing_banner_image');
						if($listingimage){
							$listingimage_url = $listingimage['url'];
							$listingimage_alt = $listingimage['alt'];
							$listingimgurl = '<img src="'.$listingimage_url.'" alt="'.$listingimage_alt.'" />';
						}else{
							$url = get_stylesheet_directory_uri().'/images/post-placeholder.png';
							$listingimgurl = '<img src="'.$url.'">';
						}
						/*$imgurl = get_the_post_thumbnail( get_the_ID(), 'full' );*/
						$image = get_field('carousel_image');
						if($image){
							$image_url = $image['url'];
							$image_alt = $image['alt'];
							$imgurl = "<img src='".$image_url."' alt='".$image_alt."' />";
						}else{
							$url = get_stylesheet_directory_uri().'/images/post-placeholder.png';
							$imgurl = '<img src="'.$url.'">';
						}
						$pCont = get_the_excerpt();
						if (strlen($pCont) > 250){
			                $pCont = substr($pCont, 0, 250) . '...';
			            }
						if($cat == ''){
							$categories = get_the_terms( get_the_id(), 'insight_category' );
							if ( ! empty( $categories ) ) {
								$cat = esc_html( $categories[0]->name );
							}
						}
						$services = get_field('services');
						if($services)foreach($services as $service){
							$service_name = $service->post_name;
							if(!in_array($service_name, $insight_classes)){
								$insight_classes[] = $service_name;
							}
						}
						$formats = get_the_terms( get_the_id(), 'insight_format' );
						if($formats)foreach($formats as $format){
							$format_name = $format->slug;
							if(!in_array($format_name, $insight_classes)){
								$insight_classes[] = $format_name;
							}
						}
						$str = implode(" ", $insight_classes);
						if($i == 1){
							$html .= '<div class="posts full-width-posts '.$str.'" data-id="'.get_the_id().'">';
							$html .= '<div class="industries-banner"><a href="'.get_permalink().'">'.$listingimgurl.'</a></div>';
							$html .= '<div class="caption">';
								$html .= '<div class="con">';
									$html .= '<div class="left-side-tile">';
										  $html .= '<p class="category-pull">'.$cat.'</p>';
										  $html .= '<h4><a href="'.get_permalink().'">'.get_the_title().'</a></h4>';
									$html .= '</div>';
									$html .= '<div class="right-side-excerpt">';
										  //$html .= '<p>'.substr(get_the_excerpt(), 0, strrpos(substr(get_the_excerpt(), 0, 250), ' ')).'...</p>';
										  $html .= '<p>'.$pCont.'</p>';
										  $html .= '<a href="'.get_permalink().'" class="avia-button avia-color-theme-color">';
											if(ICL_LANGUAGE_CODE == "sv"){
												$html .= 'Läs mer';
											}else{
												$html .= 'READ MORE';
											}
										  $html .= '</a>';
									$html .= '</div>';
								$html .= '</div>';
						   $html .= '</div></div>';
						}else{
							$html .= '<div class="posts half-width-posts '.$str.'">
							   <div class="industries-banner"><a href="'.get_permalink().'">'.$imgurl.'</a></div>
							   <div class="caption">
									<div class="con">
									  <p class="category-pull">'.$cat.'</p>
									  <h4><a href="'.get_permalink().'">'.get_the_title().'</a></h4>
									  <p>'.$pCont.'</p>
									  <a href="'.get_permalink().'" class="avia-button avia-color-theme-color-subtle">';
										if(ICL_LANGUAGE_CODE == "sv"){
											$html .= 'Läs mer';
										}else{
											$html .= 'READ MORE';
										}
									  $html .= '</a>
									</div>
							   </div>
							</div>';
						}
						$i++;
					}
					wp_reset_postdata();
				}
				//Services_options
				$s_args = array(
					'post_type' => 'services',
					'posts_per_page' => -1,
					'order' => 'DESC',
					'orderby' => 'menu_order',
				);
				$s_query = new WP_Query($s_args);
				//Industries_options
				/*$i_args = array(
					'post_type' => 'industries',
					'posts_per_page' => -1,
					'order' => 'DESC',
					'orderby' => 'menu_order',
				);
				$i_query = new WP_Query($i_args);*/
			?>
			<div class="col-xs-12 col-md-9">
				<div class="row">
					<?php if($s_query->have_posts()){ ?>
					<div class="insight-filter col-sm-4 col-md-4">
						<div class="form-group">
							<select class="selectpicker service-filter form-control" data-filter-group="service">
								<?php if(ICL_LANGUAGE_CODE == "sv"){ ?>
									<option value="*" data-filter-value="">Välj tjänst</option>
								<?php }else{ ?>
									<option value="*" data-filter-value="">All services</option>
								<?php } ?>
								<?php while( $s_query->have_posts() ){
										$s_query->the_post();
										echo '<option value="'.get_the_id().'" data-filter-value=".'.get_post_field( 'post_name', get_the_id() ).'">'.get_the_title().'</option>';
									}
									wp_reset_postdata();
								?>
							</select>
						</div>
					</div>
					<?php } ?>
					<div class="insight-filter col-sm-4 col-md-4" style="width:32.5%">
						<div class="form-group">
							<select class="selectpicker format-filter form-control" data-filter-group="format">
							<?php if(ICL_LANGUAGE_CODE == "sv"){ ?>
								<option value="*" data-filter-value="">Välj format</option>
							<?php }else{ ?>
								<option value="*" data-filter-value="">All formats</option>
							<?php } ?>
							<?php $formats = get_terms('insight_format');
								if($formats)foreach($formats as $format){
									$format_name = $format->slug;
									$format_title = $format->name;
									$insight_classes[] = $format_name;
									echo '<option value="'.$format_name.'" data-filter-value=".'.$format_name.'">'.$format_title.'</option>';
								} ?>
							</select>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="container-fluid current-filters-section section-wrap">
			<div class="col-xs-12">
				<?php if(ICL_LANGUAGE_CODE == "sv"){ ?>
					<div class="current-filter-text">Nuvarande Filter:</div>
				<?php }else{ ?>
					<div class="current-filter-text">Current Filters:</div>
				<?php } ?>
				<div class="current-filters"></div>
			</div>
		</div>
		<?php if($html){ ?>
		<div class="container-fluid content-wrap section-wrap listing-section grey-bg border-top" style="padding-bottom: 10px">
			<div class="home-slider">
				<div class="cycle-slideshow home-slideshow insights-posts-wrap" data-cycle-slides="&gt; div" data-cycle-pager=".home-pager" data-cycle-timeout="5000" data-cycle-prev="#HomePrev" data-cycle-next="#HomeNext">
				<?php echo $html; ?>
				</div>
				<?php 
				$cls = '';
				if( ($max_pages) && ( $posts_count <= $initial_offset ) ){ $cls = 'hidden';} ?>
                <div class="text-center insights-loadmore <?php echo $cls; ?>">
                    <a id="load-more" class="gomo-loadmore avia-button avia-color-theme-color" data-posts-per-page="<?php echo $posts_per_page; ?>" data-initial-offset="<?php echo $initial_offset; ?>" data-offset="5" data-total="<?php echo $posts_count; ?>" data-lang="<?php if (defined('ICL_LANGUAGE_CODE')){echo ICL_LANGUAGE_CODE;} ?>" href="#"><?php if(ICL_LANGUAGE_CODE == "en"){echo 'MORE ARTICLES';}else{ echo 'Läs mer';} ?></a>
                </div>
                <?php if(ICL_LANGUAGE_CODE == "sv"){ ?>
                    <div id="no-posts-msg" class="col-xs-12 notice">Inga insikter finns tillgängliga för de valda filtren</div>
                <?php }else{ ?>
                    <div id="no-posts-msg" class="col-xs-12 notice">No insights are available for the selected filters</div>
                <?php } ?>
			</div>
		</div>
		<?php } //endif ?>
	</div>
</div>
	<?php
	return ob_get_clean();
}

add_shortcode( 'job_listing_shortcode', 'job_listing_function' );
function job_listing_function() {
	$html = "";
	$args = array(
	    'post_type' => 'job-posts',
	    'posts_per_page' => -1,
	    'post_status' => 'publish',
	);
	
	$query1 = new WP_Query( $args );
	if ( $query1->have_posts() ) {
		$html .= '<div class="job-list-posts text-by-img">
					<div class="cycle-slideshow home-slideshow">';
						while ( $query1->have_posts() ){
							$query1->the_post();
							$id = get_the_ID();
							$html .= '<div class="current-job-posts" data-strings="">
										<div class="openings">
											<a class="parallel-openings" href="'. get_permalink() .'">
												<h4>'. get_the_title() .'</h4>';
												$html .= '<p class="location-text-taxonomy">' . strip_tags( get_the_term_list( $id, 'job-location', '', '/') ) . '</p>';
												$html .= '<span class="avia-button avia-color-theme-color-subtle"></span>
											</a>
										</div>
									</div>';
						}
			wp_reset_postdata();
		$html .= '</div>
		</div>';
	}
	return $html;
}

// shortcode code ends here
/*************************************
** Define javascript ajaxurl variable for ajax
**************************************/
add_action('wp_head','define_js_var');
function define_js_var() {
?>
	<script type="text/javascript">
		var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
		var homeurl = '<?php echo home_url(); ?>';
		var themeurl = '<?php echo get_stylesheet_directory_uri(); ?>';
	</script>
<?php
}
// Team List
add_shortcode( 'team-list', 'team_member_list' );
function team_member_list( $atts ) {
	ob_start();
	$query = new WP_Query( array(
		'posts_per_page' => -1,
		'post__not_in' => array (3047),
		'post_type' => 'team',
		'tax_query' =>  array(
			array(
				'taxonomy' => 'team_category',
				'field'    => 'slug',
				'terms'    => 'management',
			)
		),
	) );
	if ( $query->have_posts() ) { ?>
	  <div class="team-list-posts col-xs-3">
	   <div class="container">
		<div class="cycle-slideshow home-slideshow" data-cycle-slides="&gt; div" data-cycle-pager=".home-pager" data-cycle-timeout="5000" data-cycle-prev="#HomePrev" data-cycle-next="#HomeNext">
			<?php
				while ( $query->have_posts() ) : $query->the_post();
				$imgurl = get_the_post_thumbnail( get_the_ID(), 'full' );
			?>
			<div class="posts">
			   <div class="team-member-img"><?php echo $imgurl; ?></div>
			   <div class="caption">
					<div class="con">
					  <h1><?php the_title(); ?></h1>
					  <p><?php the_excerpt(); ?></p>
					</div>
			   </div>
			</div>
			<?php endwhile;
			wp_reset_postdata(); ?>
		</div>
	   </div>
	</div>
	<?php 
	}
	return ob_get_clean();
}


//Shortcode to display social share
//Use [post_social_share back_btn_link="https://gomostaging.com/gomogroup.com/karri%C3%A4r" back_btn_text="BACK"]
add_shortcode( 'post_social_share', 'post_social_share_function' );
function post_social_share_function($params, $content = null) {
	extract(shortcode_atts(array(
		'back_btn_link' => '#',
		'back_btn_text' => 'BACK',
	), $params));
	ob_start(); ?>

	<?php global $post; ?>
	<div class="article-top-links">
		<a class="back-to-insights" href="<?php echo $back_btn_link; ?>"><?php echo $back_btn_text; ?></a>
		<div class="social-share">
			<a class="facebook-btn" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo get_permalink();?>" target="_blank" rel="nofollow" data-toggle="tooltip" data-placement="right" title="Share on facebook"><img src="<?php echo get_stylesheet_directory_uri().'/images/social/facebook.png'; ?>" alt="facebook"></a>
			<a class="twitter-btn" href="https://twitter.com/intent/tweet?text=<?php echo get_the_title(); ?>&amp;url=<?php echo get_permalink();?>" target="_blank" rel="nofollow" data-toggle="tooltip" data-placement="right" title="Share on twitter"><img src="<?php echo get_stylesheet_directory_uri().'/images/social/twitter.png'; ?>" alt="twitter"></a>
			<a class="linkedin-btn" href="https://www.linkedin.com/cws/share?url=<?php echo get_permalink();?>" target="_blank" rel="nofollow" data-toggle="tooltip" data-placement="right" title="Share on linkedin"><img src="<?php echo get_stylesheet_directory_uri().'/images/social/linkedin.png'; ?>" alt="linkedin"></a>
			<a class="print-btn" href="#" rel="nofollow" data-toggle="tooltip" data-placement="right" title="Print this document"><img src="<?php echo get_stylesheet_directory_uri().'/images/social/print.png'; ?>" alt="print"></a>
			<a class="mail-btn" href="mailto:?subject=Please read this artical&amp;body=Check out the post <?php echo get_permalink();?>" target="_blank" rel="nofollow" data-toggle="tooltip" data-placement="right" title="Share with email"><img src="<?php echo get_stylesheet_directory_uri().'/images/social/mail.png'; ?>" alt="facebook"></a>
		</div>
	</div>

	<?php $result = ob_get_clean();
	return $result;
}



//Shortcode to display categoery
//Use [post_cat_display]
add_shortcode( 'post_cat_display', 'post_cat_display_function' );
function post_cat_display_function($params, $content = null) {
	 
	ob_start(); ?>

	 
	<div class="categoery-top-links" style="display:none">
		<div class="categoery-list">
			<?php  
				global $post;
				$category_detail = get_the_category($post->ID); //$post->ID
					foreach( $category_detail as $cd )
					{
					   echo $cd->name;
					}
			
		?>
		</div>
	</div>

	<?php $result = ob_get_clean();
	return $result;
}

// job title for enquiry form
add_filter('query_vars', 'parameter_queryvars' );
function parameter_queryvars( $qvars ) {
	$qvars[] = 'job';
	return $qvars;
}

//Shortcode to be used in cf7 form
add_action( 'wpcf7_init', 'wpcf7_add_form_tag_job_title' );
function wpcf7_add_form_tag_job_title() {
	wpcf7_add_form_tag( array( 'job_title', 'job_title*'),
		'wpcf7_job_title_form_tag_handler', array( 'name-attr' => true ) );
}
function wpcf7_job_title_form_tag_handler( $tag ) {
	$tag = new WPCF7_FormTag( $tag );

	if ( empty( $tag->name ) ) {
		return '';
	}

	$atts = array();

	$class = wpcf7_form_controls_class( $tag->type );
	$atts['class'] = $tag->get_class_option( $class );
	$atts['id'] = $tag->get_id_option();

	$atts['name'] = $tag->name;
	//$atts['value'] = ;
	//$atts = wpcf7_format_atts( $atts );

	global $wp_query, $post;
	$job = $job_title = '';
	if (isset($wp_query->query_vars['job'])){
		$job = $wp_query->query_vars['job'];
		
		if ( $post = get_page_by_path( "$job", OBJECT, 'job-posts' ) )
			$job_title = $post->post_title;
	}

	ob_start(); ?>

	<input type="hidden" id="<?php echo $atts['id']; ?>" name="<?php echo $atts['name']; ?>" value="<?php echo $job_title; ?>" class="<?php echo $atts['class']; ?>" />

	<?php
	$result = ob_get_clean();
	return $result;
}


//Shortcode to display social share
//Use [get_job_field field="post_title"]
add_shortcode( 'get_job_field', 'get_job_field_function' );
function get_job_field_function($params, $content = null) {
	extract(shortcode_atts(array(
		'field' => 'post_title',
	), $params));

	global $wp_query, $post;
	$job = $job_field = '';
	if (isset($wp_query->query_vars['job'])){
		$job = $wp_query->query_vars['job'];
		$post = get_page_by_path( "$job", OBJECT, 'job-posts' );
		if($post){
			//display title
			if($field == 'post_title'){
				$job_field = $post->$field;
			//display slug
			}else if($field == 'post_name'){
				$job_field = $post->$field;
			//display slug
			}else if($field == 'post_date'){
				$job_field = $post->$field;
			//display excerpt
			}else if($field == 'post_excerpt'){
				$job_field = $post->$field;
			//display job type
			}else if($field == 'job_type'){
				$job_field = get_field('job_type', $post->ID);
			//display job location
			}else if($field == 'job-location'){
				$job_locations = get_the_terms($post, 'job-location');
				if ( $job_locations && ! is_wp_error( $job_locations ) ) :
					foreach ( $job_locations as $job_location ) {
						$job_field = $job_location->name;
						//$job_field = '';
					}
				endif;
			//display job contact person
			}else if($field == 'job_contact_person'){
				$job_field = get_field('job_contact_person', $post->ID);
			}else{
				$job_field = '(Allowed field names: post_title, post_name, post_date, post_excerpt, job_type, job-location, job_contact_person in the shortcode)';
			}
		}
	}else{
		if($field == 'post_title'){
			$job_field = '';
		//display slug
		}else if($field == 'post_name'){
			$job_field = '';
		//display slug
		}else if($field == 'post_date'){
			$job_field = '';
		//display excerpt
		}else if($field == 'post_excerpt'){
			$job_field = '';
		//display job type
		}else if($field == 'job_type'){
			$job_field = 'FULL TIME';
		//display job location
		}else if($field == 'job-location'){
			$job_field = 'GÖTEBORG';
			
		}else if($field == 'job_contact_person'){
			$job_field = 'Head of Client Success';
		}
	}

	ob_start();

	echo $job_field;

	$result = ob_get_clean();
	return $result;
}

/*//ACF Option Page
if( function_exists('acf_add_options_page') ) {
	
	acf_add_options_page(array(
		'page_title' 	=> 'Insights Authors',
		'menu_title'	=> 'Insights Authors',
		'menu_slug' 	=> 'insights-authors',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));
	
}*/

//Blog Author shortcode
add_shortcode( 'author_block', 'author_block_function' );
function author_block_function($params, $content ) {
	/*extract(shortcode_atts(array(
		//'back_btn_text' => 'BACK',
	), $params));*/
	ob_start(); ?>
	
	<?php global $post; ?>

	<?php $select_author = get_field( 'select_author', $post->ID ); ?>
	<?php if( $select_author): ?>
	<div class="flex_column av_one_full  avia-builder-el-10  el_before_av_hr avia-builder-el-first first flex_column_div">
		<section class="av_textblock_section " itemscope="itemscope">
			<?php 
				$title = $select_author->post_title;
				$imgurl = get_the_post_thumbnail_url($select_author->ID, 'full');
				$des = get_field( 'author_designation', $select_author->ID );
				$content = $select_author->post_content;
			?>
			<div class="avia_textblock  " itemprop="text">
				<div class="author-block">
					<div class="author-img"><?php if($imgurl){ ?><img src="<?php echo $imgurl; ?>"><?php } ?></div>
					<div class="author-content">
						<p class="name"><?php echo $title; ?></p>
						<p class="designation"><?php echo $des; ?></p>
					</div>
					<div class="slick-content">
						<p><?php echo $content; ?></p>
					</div>
				</div>
			</div>
		</section>
	</div>
	<?php endif; ?>

	<?php $result = ob_get_clean();
	return $result;
}

// Drop-down blank replaced with title
function my_wpcf7_form_elements($html) {
	if(ICL_LANGUAGE_CODE == "en"){
		$text = 'Country';
	} else { 
		$text = 'Land';
	}
	// $text = 'Country';
	$html = str_replace('<option value="">---</option>', '<option value="">' . $text . '</option>', $html);
	return $html;
}
add_filter('wpcf7_form_elements', 'my_wpcf7_form_elements');


/******* Display Featured checkbox on Post listing page ******/
add_filter('manage_insights_posts_columns' , 'insights_columns');
function insights_columns($cols) {
	/*unset(
		$cols['insight_subject'],
	);*/

	//Add custom columns
	$new_columns = array(
		'featured' => __('Featured', 'gomo'),
		'home_featured' => __('Home Featured', 'gomo'),
	);
	return array_merge($cols, $new_columns);
}

add_filter('manage_insights_posts_custom_column','acf_admin_custom_columns',10, 2);
function acf_admin_custom_columns( $column, $post_id ) {
	switch( $column ) {
		case 'featured':
			$featured = get_field('use_as_featured', $post_id);
			if($featured){
				echo 'Yes';
			}else{
				echo 'No';
			}
			break;
		case 'home_featured':
			$home_featured = get_field('use_as_home_featured', $post_id);
			if($home_featured){
				echo 'Yes';
			}else{
				echo 'No';
			}
			break;
   }   
}

//To change the order of search results: pages and then posts/
function order_search_by_posttype($orderby){
	if (!is_admin() || is_search()) :
		global $wpdb;
		$orderby =
			"CASE WHEN {$wpdb->prefix}posts.post_type = 'page' THEN '1' 
				WHEN {$wpdb->prefix}posts.post_type = 'services' THEN '2' 
				WHEN {$wpdb->prefix}posts.post_type = 'industries' THEN '3'
				WHEN {$wpdb->prefix}posts.post_type = 'insights' THEN '4' 
				WHEN {$wpdb->prefix}posts.post_type = 'job-posts' THEN '5' 
			ELSE {$wpdb->prefix}posts.post_type END ASC, 
			{$wpdb->prefix}posts.post_title ASC";
	endif;
	return $orderby;
}
add_filter('posts_orderby', 'order_search_by_posttype');

//ICL Class in Body
add_filter('body_class', 'append_language_class');
function append_language_class($classes){
  $classes[] = ICL_LANGUAGE_CODE;  //or however you want to name your class based on the language code
  return $classes;
}


/*****************************************
 ***** Allow SVG to upload  *********
*****************************************/
function cc_mime_types($mimes) {
 $mimes['svg'] = 'image/svg+xml';
 return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');

/*****************************************
 ***** Allow SVG to upload  *********
*****************************************/

add_filter( 'body_class', 'add_password_protected_body_class' );
function add_password_protected_body_class( $classes ) {
if ( post_password_required() ) 
   $classes[] = 'password-protected';
   return $classes;
}

/*****************************************
 ***** Edit password protected page content  *********
*****************************************/

add_filter('the_password_form', 'my_custom_password_form');
function my_custom_password_form() {
		global $post;
		$label = 'pwbox-' . ( empty( $post->ID ) ? rand() : $post->ID );
		$output = '
		<div class="boldgrid-section">
			<div class="container">
				<form action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" class="form-inline post-password-form" method="post">
					<p>' . ( 'This content is password protected. Please enter your password below:' ) . '</p>
					<label for="' . $label . '">' . 'Password:' . ' <input name="post_password" id="' . $label . '" type="password" size="20" class="form-control" /></label><button type="submit" name="Submit" class="button-primary password-btn">' . 'Submit'. '</button>
				</form>
			</div>
		</div>';
	return $output;
}



add_filter( 'the_password_form', 'wp_custom_post_password_msg' );
function wp_custom_post_password_msg( $form ){   
    if ( ! isset ( $_COOKIE[ 'wp-postpass_' . COOKIEHASH ] ) )
        return $form;
    if ( ! wp_get_raw_referer() == get_permalink() )
        return $form;   
    $msg = 'Sorry, your password is wrong.';
    $msg = "<p class='custom-password-message'>". $msg ."</p>";
    return $msg . $form;
}

// Web Design and Development Portfolio
add_shortcode( 'web_showcase_carousel', 'web_showcase_carousel_function' );
function web_showcase_carousel_function(){
	$html = "";
	if( have_rows('design_and_development') ){
		$html .= '<div class="swiper-container web_showcase-slider article-slider"><div class="swiper-wrapper">';
			$blocks = get_field('design_and_development');
			foreach( $blocks as $index => $block ) :
				//Get current slide image
				$image = $block['image'];
				
				$lastIndex = intval(count($blocks) - 1);

				//Get next slide
				//Check if not last slide
				if($index == $lastIndex){
					$nextIndex = 0;
				}else{
					$nextIndex = $index + 1;
				}

				//Get next slide details
				$next_image = $blocks[$nextIndex]['image'];
				$client_name = $blocks[$nextIndex]['client_name'];
				$short_description = $blocks[$nextIndex]['short_description'];
				$html .= '<div class="swiper-slide web-slides">
							<div class="slider-top">
								<div class="big-img"><img class="big-showcase" src="'.$image['url'].'" alt="'.$image['alt'].'"></div>
							</div>
							<div class="row">
								<div class="next-article-slider col-md-6">
									<div class="next-article-wrap">
										<div class="next-article-img"><img class="big-showcase" src="'.$next_image['sizes']['thumbnail'].'" alt="'.$next_image['alt'].'"></div>
										<div class="next-article-text"><div class="nx-pr">';
										
											if(ICL_LANGUAGE_CODE == 'en') { 
												$html .= 'Next Example';
											}else{ 
												$html .= 'NÄSTA EXEMPEL';
											}
										
										$html .= '</div>
										<div class="">'.$client_name.'</div>
											'.$short_description.'
										</div>
										<div class="slider-btn-wrap">
											<div class="swiper-button-next"></div>
											<div class="swiper-button-prev"></div>
										</div>
									</div>
								</div>
							</div>
						</div>';
			endforeach;
		$html .= '</div></div>';
	}
	return $html;
}


/*****************************************
**** Insights loadmore and filter ajax ***
*****************************************/
add_action( 'wp_ajax_get_insights', 'get_insights' );
add_action( 'wp_ajax_nopriv_get_insights', 'get_insights' );
function get_insights(){
	extract($_POST);
	
	$service_filter = ($service_filter == '*')? '' : $service_filter;
	$format_filter = ($format_filter == '*')? '' : $format_filter;

	$args = array(
		'post_type' => 'insights',
		'post_status' => 'publish', 
		'posts_per_page' => $posts_per_page,
		'orderby' => 'publish_date', 
		'order' => 'DESC',
		'suppress_filters' => false,
	);

	if($offset){
		$args['offset'] = $offset;
	}
	if($service_filter){
		$args['meta_query'][] = array(
			'key'     => 'services',
			'value'   => '"'.$service_filter.'"',
			'compare' => 'LIKE',
		);
	}
	if($format_filter != ''){
		$args['tax_query'][] = array(
			'taxonomy' => 'insight_format',
			'field'    => 'slug',
			'terms'    => '"'.$format_filter.'"',
		);
	}
	/*if($current_lang == "en"){
		$args['tax_query'][] = array(
			'taxonomy' => 'insight_language',
			'field'    => 'slug',
			'terms'    => 'english',
		);
	}
	if($current_lang == "sv"){
		$args['tax_query'][] = array(
			'taxonomy' => 'insight_language',
			'field'    => 'slug',
			'terms'    => 'swedish',
		);
	}*/
	if( ($service_filter != '') || ($format_filter != '') ){
		$args['meta_query']['relation'] = 'OR';
	}

	$return_arr['args'] = $args;

	$the_query = new WP_Query($args);
	$html = "";
	if( $the_query->have_posts() ){
		$return_arr['found_posts'] = $the_query->found_posts;
		$i = 1;
		while( $the_query->have_posts() ){
			$the_query->the_post();
			$insight_classes = array();
			$cat = '';
			
			$cat = get_field('category_for_display');
			if($cat == ''){
				$categories = get_the_terms( get_the_id(), 'insight_category' );
				if ( ! empty( $categories ) ) {
					$cat = esc_html( $categories[0]->name );
				}
			}
			
			/*$imgurl = get_the_post_thumbnail( get_the_ID(), 'full' );*/
			$image = get_field('carousel_image');
			$imgurl = '<img src="'.$image['url'].'" alt="'.$image['alt'].'" />';
			if(!$image){
				$url = get_stylesheet_directory_uri().'/images/post-placeholder.png';
				$imgurl = '<img src="'.$url.'" alt="">';
			}
			
			$pCont = get_the_excerpt();
			if (strlen($pCont) > 150){
                $pCont = substr($pCont, 0, 150) . '...';
            }
			
			//$str = implode(" ", $insight_classes);

			$str = $service_filter.' '. $format_filter ;
			if($offset > 0){
				$html .= '<div class="posts half-width-posts '.$str.'" data-id="'.get_the_id().'">
							<div class="industries-banner"><a href="'.get_permalink().'">'.$imgurl.'</a></div>
							<div class="caption">
								<div class="con">
									<p class="category-pull">'.$cat.'</p>
									<h4><a href="'.get_permalink().'">'.get_the_title().'</a></h4>
									<p>'.$pCont.'</p>
									<a href="'.get_permalink().'" class="avia-button avia-color-theme-color-subtle">';
										if(ICL_LANGUAGE_CODE == "sv"){
											$html .= 'Läs mer';
										}else{
											$html .= 'READ MORE';
										}
									$html .= '</a>
								</div>
							</div>
						</div>';
			}else{
				if($i == 1 ){
					$html .= '<div class="posts full-width-posts '.$str.'">';
						$html .= '<div class="industries-banner"><a href="'.get_permalink().'">'.$imgurl.'</a></div>';
						$html .= '<div class="caption">';
							$html .= '<div class="con">';
								$html .= '<div class="left-side-tile">';
									$html .= '<p class="category-pull">'.$cat.'</p>';
									$html .= '<h4><a href="'.get_permalink().'">'.get_the_title().'</a></h4>';
								$html .= '</div>';
								$html .= '<div class="right-side-excerpt">';
									$html .= '<p>'.$pCont.'</p>';
									$html .= '<a href="'.get_permalink().'" class="avia-button avia-color-theme-color">';
										if(ICL_LANGUAGE_CODE == "sv"){
											$html .= 'Läs mer';
										}else{
											$html .= 'READ MORE';
										}
									$html .= '</a>';
								$html .= '</div>';
						$html .= '</div>';
					$html .= '</div></div>';
				}else{
					$html .= '<div class="posts half-width-posts '.$str.'">
								<div class="industries-banner"><a href="'.get_permalink().'">'.$imgurl.'</a></div>
								<div class="caption">
									<div class="con">
										<p class="category-pull">'.$cat.'</p>
										<h4><a href="'.get_permalink().'">'.get_the_title().'</a></h4>
										<p>'.$pCont.'</p>
										<a href="'.get_permalink().'" class="avia-button avia-color-theme-color-subtle">';
											if(ICL_LANGUAGE_CODE == "sv"){
												$html .= 'Läs mer';
											}else{
												$html .= 'READ MORE';
											}
										$html .= '</a>
									</div>
								</div>
							</div>';
				}
			}
			$i++;
		}
		wp_reset_postdata();
	}else{
		$return_arr['error'] = 'no_posts_found';
	}

	$return_arr['html'] = $html;
	echo json_encode($return_arr);
	wp_reset_postdata();
	die();
}