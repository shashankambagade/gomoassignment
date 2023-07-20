<?php 

/****************************
*** Enquee files CSS & JS ***
*****************************/

function theme_enqueue_styles() {
	wp_enqueue_style('parent-style', get_template_directory_uri().'/style.css');
	wp_enqueue_style('swiper-css', get_stylesheet_directory_uri().'/css/swiper-bundle.min.css');
	//wp_enqueue_style('jquery-ui-css', get_stylesheet_directory_uri().'/css/jquery-ui.min.css');
	//wp_enqueue_style('jquery-ui-css', 'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.min.css');
	wp_enqueue_style('custom-css', get_stylesheet_directory_uri().'/css/custom-style.css', array('parent-style'));

	wp_enqueue_script('swiper-js', get_stylesheet_directory_uri().'/js/swiper-bundle.min.js', array('jquery'), '1.0.0', true);
	//wp_enqueue_script('jquery-ui-slider');
	//wp_enqueue_script('touch-punch-js', get_stylesheet_directory_uri().'/js/jquery.ui.touch-punch.min.js', array('jquery'), '1.0.0', true);

	wp_enqueue_script ('jquery');
	wp_enqueue_script('custom-js', get_stylesheet_directory_uri().'/js/custom.js', array('jquery'), '1.0.0', true);
	wp_enqueue_script('stellar-js', get_stylesheet_directory_uri().'/js/stellar.js', array('jquery'), '1.0.0', true);
	

}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );


/***************************************
*** Activate enfold builder for CPTs ***
****************************************/
function my_alb_supported_post_types( array $supported_post_types ){
	$supported_post_types[] = 'casestudy';
	return $supported_post_types;
}
add_filter('avf_alb_supported_post_types', 'my_alb_supported_post_types', 10, 1);

function add_builder_to_posttype($metabox){
	foreach($metabox as &$meta){
		if($meta['id'] == 'avia_builder' || $meta['id'] == 'layout'){
			$meta['page'][] = 'casestudy';
		}
	}
	return $metabox;
}
add_filter('avf_builder_boxes', 'add_builder_to_posttype');

/********************************
*** Theme Option for backend  ***
*********************************/

function setup_theme_admin_menus() {
	if( function_exists('acf_add_options_page') ) {
		$theme_options = acf_add_options_page( array(
			'page_title'    => __('Theme Settings', 'focusneo'),
			'menu_title'    => __('Theme Settings', 'focusneo'),
			'menu_slug'     => 'theme-settings',
			'capability'    => 'edit_posts',
			'redirect'  => false
		));
	}
}
// This tells WordPress to call the function named "setup_theme_admin_menus"
// when it's time to create the menu pages.
add_action("admin_menu", "setup_theme_admin_menus");


/********************************
*** Register Custom Post Type ***
*********************************/

function register_custom_post_types() {

	$labels = array(
		'name'               => _x( 'Casestudy', 'Casestudy', 'focusneo' ),
		'singular_name'      => _x( 'Casestudy', 'Casestudy', 'focusneo' ),
		'menu_name'          => _x( 'Casestudy', 'admin menu', 'focusneo' ),
		'name_admin_bar'     => _x( 'Casestudy', 'add new on admin bar', 'focusneo' ),
		'add_new'            => _x( 'Add New', 'Casestudy', 'focusneo' ),
		'add_new_item'       => __( 'Add New Casestudy', 'focusneo' ),
		'new_item'           => __( 'New Casestudy', 'focusneo' ),
		'edit_item'          => __( 'Edit Casestudy', 'focusneo' ),
		'view_item'          => __( 'View Casestudy', 'focusneo' ),
		'all_items'          => __( 'All Casestudy', 'focusneo' ),
		'search_items'       => __( 'Search Casestudy', 'focusneo' ),
		'parent_item_colon'  => __( 'Parent Casestudy:', 'focusneo' ),
		'not_found'          => __( 'No Casestudy found.', 'focusneo' ),
		'not_found_in_trash' => __( 'No Casestudy found in Trash.', 'focusneo' )
	);
	$args = array(
		'labels'             => $labels,
		'description'        => __( 'Description.', 'focusneo' ),
		'menu_icon'			 => 'dashicons-text-page',
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'casestudy' ),
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => true,
		'menu_position'      => null,
		'supports'           => array('title', 'editor', 'custom-fields', 'revisions', 'thumbnail', 'page-attributes')
	);
	register_post_type( 'casestudy', $args );

	$labels = array(
		'name'               => _x( 'Product', 'Product', 'focusneo' ),
		'singular_name'      => _x( 'Product', 'Product', 'focusneo' ),
		'menu_name'          => _x( 'Product', 'admin menu', 'focusneo' ),
		'name_admin_bar'     => _x( 'Product', 'add new on admin bar', 'focusneo' ),
		'add_new'            => _x( 'Add New', 'Product', 'focusneo' ),
		'add_new_item'       => __( 'Add New Product', 'focusneo' ),
		'new_item'           => __( 'New Product', 'focusneo' ),
		'edit_item'          => __( 'Edit Product', 'focusneo' ),
		'view_item'          => __( 'View Product', 'focusneo' ),
		'all_items'          => __( 'All Product', 'focusneo' ),
		'search_items'       => __( 'Search Product', 'focusneo' ),
		'parent_item_colon'  => __( 'Parent Product:', 'focusneo' ),
		'not_found'          => __( 'No Product found.', 'focusneo' ),
		'not_found_in_trash' => __( 'No Product found in Trash.', 'focusneo' )
	);
	$args = array(
		'labels'             => $labels,
		'description'        => __( 'Description.', 'focusneo' ),
		'menu_icon'			 => 'dashicons-archive',
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'product' ),
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => true,
		'menu_position'      => null,
		'supports'           => array('title', 'editor', 'custom-fields', 'revisions', 'thumbnail', 'page-attributes')
	);
	register_post_type( 'product', $args );


}
add_action('init', 'register_custom_post_types');


function register_taxonomies_init() {
	$labels = array(
		'name'              => _x( 'Casestudy Categories', 'taxonomy general name' ),
		'singular_name'     => _x( 'Casestudy Category', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Categories' ),
		'all_items'         => __( 'All Categories' ),
		'parent_item'       => __( 'Parent Category' ),
		'parent_item_colon' => __( 'Parent Category:' ),
		'edit_item'         => __( 'Edit Category' ),
		'update_item'       => __( 'Update Category' ),
		'add_new_item'      => __( 'Add New Category' ),
		'new_item_name'     => __( 'New Category Name' ),
		'menu_name'         => __( 'Casestudy Categories' ),
	);
	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels, 
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'casestudy-categories' ),
	);
	register_taxonomy( 'casestudy_category', array( 'casestudy' ), $args );

	/*** Filters ***/
	$labels = array(
		'name'              => _x( 'Tags', 'taxonomy general name' ),
		'singular_name'     => _x( 'Tags', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Tags' ),
		'all_items'         => __( 'All Tags' ),
		'parent_item'       => __( 'Parent Tags' ),
		'parent_item_colon' => __( 'Parent Tags:' ),
		'edit_item'         => __( 'Edit Tags' ),
		'update_item'       => __( 'Update Tags' ),
		'add_new_item'      => __( 'Add New Tags' ),
		'new_item_name'     => __( 'New Tags' ),
		'menu_name'         => __( 'Tags' ),
	);
	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels, 
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'case-tags' ),
	);
	register_taxonomy( 'case_tags', array( 'casestudy'), $args );


	$labels = array(
		'name'              => _x( 'Filter by Function', 'taxonomy general name' ),
		'singular_name'     => _x( 'Filter by Functions', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Filter by Function' ),
		'all_items'         => __( 'All Filter by Function' ),
		'parent_item'       => __( 'Parent Filter by Function' ),
		'parent_item_colon' => __( 'Parent Filter by Function:' ),
		'edit_item'         => __( 'Edit Filter by Function' ),
		'update_item'       => __( 'Update Filter by Function' ),
		'add_new_item'      => __( 'Add New Filter by Function' ),
		'new_item_name'     => __( 'New Filter by Function' ),
		'menu_name'         => __( 'Filter by Function' ),
	);
	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels, 
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'filter-by-fun' ),
	);
	register_taxonomy( 'filter_by_fun', array( 'casestudy','post', 'product','page' ), $args );

	$labels = array(
		'name'              => _x( 'Filter by Product', 'taxonomy general name' ),
		'singular_name'     => _x( 'Filter by Products', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Filter by Product' ),
		'all_items'         => __( 'All Filter by Product' ),
		'parent_item'       => __( 'Parent Filter by Product' ),
		'parent_item_colon' => __( 'Parent Filter by Product:' ),
		'edit_item'         => __( 'Edit Filter by Product' ),
		'update_item'       => __( 'Update Filter by Product' ),
		'add_new_item'      => __( 'Add New Filter by Product' ),
		'new_item_name'     => __( 'New Filter by Product' ),
		'menu_name'         => __( 'Filter by Product' ),
	);
	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels, 
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'filter-by-pro' ),
	);
	register_taxonomy( 'filter_by_pro', array( 'casestudy','post', 'product','page' ), $args );

}
add_action( 'init', 'register_taxonomies_init' );



/****************************************************
*** Remove the slug from published post permalinks***
*** Only affect our custom post type, though.********
*****************************************************/
function gp_remove_cpt_slug( $post_link, $post ) {

    if ( 'casestudy' === $post->post_type && 'publish' === $post->post_status ) {
        $post_link = str_replace( '/' . $post->post_type . '/', '/', $post_link );
    }

    return $post_link;
}
add_filter( 'post_type_link', 'gp_remove_cpt_slug', 10, 2 );

function gpt_remove_cpt_slug( $post_link, $post ) {

    if ( 'product' === $post->post_type && 'publish' === $post->post_status ) {
        $post_link = str_replace( '/' . $post->post_type . '/', '/', $post_link );
    }

    return $post_link;
}
add_filter( 'post_type_link', 'gpt_remove_cpt_slug', 10, 2 );

/***
 * Have WordPress match postname to any of our public post types (post, page, race).
 * All of our public post types can have /post-name/ as the slug, so they need to be unique across all posts.
 * By default, WordPress only accounts for posts and pages where the slug is /post-name/.
 *
 * @param $query The current query.
***/

function gp_add_cpt_post_names_to_main_query( $query ) {
	if ( ! $query->is_main_query() ) {
		return;
	}

	if ( ! isset( $query->query['page'] ) || 2 !== count( $query->query ) ) {
		return;
	}
	
	if ( empty( $query->query['name'] ) ) {
		return;
	}

	// Add CPT to the list of post types WP will include when it queries based on the post name.
	$query->set( 'post_type', array( 'post', 'page', 'casestudy' , 'product' ) );
}
add_action( 'pre_get_posts', 'gp_add_cpt_post_names_to_main_query' );


/****************************
*** Wayfinding title List ***
*****************************/


function wayfinding_case_list(){
	ob_start();
	$args = array(
		'post_type' => 'casestudy',
		'casestudy_category' => 'wayfinding',
		'posts_per_page' => 4, 
		'post_status' => 'publish',
		'orderby'   => 'date',
		'order'=> 'DSC',
        'meta_query' => array(
			array(
				'key'   => 'use_as_featured',
				'value' => '1',
				)
			),
		'suppress_filters' => true,
	);
	$query = new WP_Query($args);
	$html = "";
	if($query->have_posts()){
		$html .= '<div class="casestudy-title-list">';
				while($query->have_posts()){
					$query->the_post();
					$html .= '<div class="list-slide">
							  	<a href="'.get_the_permalink().'" class="slider-title">'.get_the_title().'</a>
							  </div>';
					}
			wp_reset_postdata();
		$html .= '</div>';
		return $html;
	}
}
add_shortcode( 'wayfinding_casestudy_list', 'wayfinding_case_list' );


/******************************
*** Wayfinding slider image ***
*******************************/

function wayfinding_case_slider(){
	ob_start();
	$args = array(
		'post_type' => 'casestudy',
		'casestudy_category' => 'wayfinding',
		'posts_per_page' => 4, 
		'post_status' => 'publish',
		'orderby'   => 'date',
		'order'=> 'DSC',
		'meta_query' => array(
			array(
				'key'   => 'use_as_featured',
				'value' => '1',
				)
			),
		'suppress_filters' => true,
	);
	$query = new WP_Query($args);
	$html = "";
	if($query->have_posts()){
		$html .= '<div class="casestudy-image-slider"><p class="new-heading">Wayfinding</p>';
			while($query->have_posts()){
				$query->the_post();
				$slider_big_image = get_field('slider_big_image');
				$slider_small_image = get_field('slider_small_image');
				$html .= '<div data-stellar-background-ratio="0.1" class="image-slide" style="background-image:url('.$slider_big_image['url'].')">
							<p class="sticky-client"><span class="client-name">Client: </span><span class="client-display">'.get_field('client_name').'</span></p>
								<a href="'.get_the_permalink().'"><img src="'.$slider_small_image['url'].'" alt="'.$slider_small_image['alt'].'"  /> </a>
						  </div>';
					}
			wp_reset_postdata();
		$html .= '</div>';
		return $html;
	}
}
add_shortcode( 'wayfinding_casestudy_slider', 'wayfinding_case_slider' );

/*************************************
*** Wayfinding slider image MOBILE ***
**************************************/

function wayfinding_case_slider_mobile(){
	ob_start();
	$args = array(
		'post_type' => 'casestudy',
		'casestudy_category' => 'wayfinding',
		'posts_per_page' => 4, 
		'post_status' => 'publish',
		'orderby'   => 'date',
		'order'=> 'DSC',
		'meta_query' => array(
			array(
				'key'   => 'use_as_featured',
				'value' => '1',
				)
			),
		'suppress_filters' => true,
	);
	$query = new WP_Query($args);
	$html = "";
	if($query->have_posts()){
		$html .= '<div class="mobile-image-slider swiper">
					<div class="swiper-wrapper">';
			while($query->have_posts()){
				$query->the_post();
				$slider_big_image = get_field('slider_big_image');
				$slider_small_image = get_field('slider_small_image');
				$html .= '<div class="mobile-slide swiper-slide"  style="background-image:url('.$slider_big_image['url'].')">
							
								<p class="sticky-client"><span class="client-name">Client: </span><span class="client-display">'.get_field('client_name').'</span></p>
								<img src="'.$slider_small_image['url'].'" alt="'.$slider_small_image['alt'].'"  />
						  </div>';
					}
			wp_reset_postdata();
			$html .= '</div>
					<div class="swiper-button-next"></div>
					<div class="swiper-button-prev"></div>
				</div>';
		return $html;
	}
}
add_shortcode( 'wayfinding_image_slidermobile', 'wayfinding_case_slider_mobile' );



/**************************
*** Branding title List ***
***************************/

function branding_case_list(){
	ob_start();
	$args = array(
		'post_type' => 'casestudy',
		'casestudy_category' => 'branding',
		'posts_per_page' => 4, 
		'post_status' => 'publish',
		'orderby'   => 'date',
		'order'=> 'DSC',
        'meta_query' => array(
			array(
				'key'   => 'use_as_featured',
				'value' => '1',
				)
			),
		'suppress_filters' => true,
	);
	$query = new WP_Query($args);
	$html = "";
	if($query->have_posts()){
		$html .= '<div class="casestudy-title-list">';
				while($query->have_posts()){
					$query->the_post();
					$html .= '<div class="list-slide">
							  	<a href="'.get_the_permalink().'" class="slider-title">'.get_the_title().'</a>
							  </div>';
					}
			wp_reset_postdata();
		$html .= '</div>';
		return $html;
	}
}
add_shortcode( 'branding_casestudy_list', 'branding_case_list' );


/****************************
*** Branding slider image ***
*****************************/

function branding_case_slider(){
	ob_start();
	$args = array(
		'post_type' => 'casestudy',
		'casestudy_category' => 'branding',
		'posts_per_page' => 4, 
		'post_status' => 'publish',
		'orderby'   => 'date',
		'order'=> 'DSC',
		'meta_query' => array(
			array(
				'key'   => 'use_as_featured',
				'value' => '1',
				)
			),
		'suppress_filters' => true,
	);
	$query = new WP_Query($args);
	$html = "";
	if($query->have_posts()){
		$html .= '<div class="casestudy-image-slider"><p class="new-heading">Branding</p>';
			while($query->have_posts()){
				$query->the_post();
				$slider_big_image = get_field('slider_big_image');
				$slider_small_image = get_field('slider_small_image');
				$html .= '<div data-stellar-background-ratio="0.1" class="image-slide" style="background-image:url('.$slider_big_image['url'].')">
							<p class="sticky-client"><span class="client-name">Client: </span><span class="client-display">'.get_field('client_name').'</span></p>
								<a href="'.get_the_permalink().'"><img src="'.$slider_small_image['url'].'" alt="'.$slider_small_image['alt'].'"  /> </a>
						  </div>';
					}
			wp_reset_postdata();
		$html .= '</div>';
		return $html;
	}
}
add_shortcode( 'branding_casestudy_slider', 'branding_case_slider' );

/************************************
*** Branding slider image  MOBILE ***
*************************************/

function branding_case_slider_mobile(){
	ob_start();
	$args = array(
		'post_type' => 'casestudy',
		'casestudy_category' => 'branding',
		'posts_per_page' => 4, 
		'post_status' => 'publish',
		'orderby'   => 'date',
		'order'=> 'DSC',
		'meta_query' => array(
			array(
				'key'   => 'use_as_featured',
				'value' => '1',
				)
			),
		'suppress_filters' => true,
	);
	$query = new WP_Query($args);
	$html = "";
	if($query->have_posts()){
		$html .= '<div class="mobile-image-slider swiper"><div class="swiper-wrapper">';
			while($query->have_posts()){
				$query->the_post();
				$slider_big_image = get_field('slider_big_image');
				$slider_small_image = get_field('slider_small_image');
				$html .= '<div class="mobile-slide swiper-slide"  style="background-image:url('.$slider_big_image['url'].')">
							
								<p class="sticky-client"><span class="client-name">Client: </span><span class="client-display">'.get_field('client_name').'</span></p>
								<img src="'.$slider_small_image['url'].'" alt="'.$slider_small_image['alt'].'"  />
						  </div>';
					}
			wp_reset_postdata();
			$html .= '</div>
						<div class="swiper-button-next"></div>
						<div class="swiper-button-prev"></div>
					</div>';
			return $html;
	}
}
add_shortcode( 'branding_image_slidermobile', 'branding_case_slider_mobile' );


/*******************************************
*** Wayfind/Branding templates shortcodes***
********************************************/


function case_typea_slider(){
	ob_start();
	$args = array(
		'post_type' => 'casestudy', 
		'post_status' => 'publish',
		'orderby'   => 'date',
		'order'=> 'DSC',
		'suppress_filters' => true,
	);
	$query = new WP_Query($args);
	$html = "";
	if($query->have_posts()){
		$html .= '<div class="case-type-a-slider swiper"><div class="swiper-wrapper">';
	 
			$images = get_field('full_width_slider',$post_id);
			$size = 'full'; // (thumbnail, medium, large, full or custom size)
			if( $images ){
				foreach( $images as $image ): 
					$html .= '<div class="swiper-slide">
								<img src="'.$image['url'].'" height="700" />
								<div class="floating-box"> 
									<span class="case-client"><span class="client-name">Client: </span><span class="client-display">'.get_field('client_name').'</span>
										</span>
									<p class="slider-title">'.get_field('slider_text').'</p>
								</div>								
							</div>';
				 endforeach; 
					}
					wp_reset_postdata();
		$html .= '</div>
					<div class="swiper-pagination"></div>
				</div>';
		return $html;
		}
		 
}
add_shortcode( 'type_a_caseslider', 'case_typea_slider' );


function case_mobile_slider(){
	ob_start();
	$args = array(
		'post_type' => 'casestudy', 
		'post_status' => 'publish',
		'orderby'   => 'date',
		'order'=> 'DSC',
		'suppress_filters' => true,
	);
	$query = new WP_Query($args);
	$html = "";
	if($query->have_posts()){
		$html .= '<div class="mobile-image-slider swiper"><div class="swiper-wrapper">';
	 
			$images = get_field('mobile_slider_images',$post_id);
			$size = 'full'; // (thumbnail, medium, large, full or custom size)
			if( $images ){
				foreach( $images as $image ): 
					$html .= '<div class="mobile-slide swiper-slide"><img src="'.$image['url'].'" /></div>';
				 endforeach; 
					}
					wp_reset_postdata();
		$html .= '</div>
			<div class="swiper-button-next"></div>
  		    <div class="swiper-button-prev"></div>
				</div>';
		return $html;
		}
		 
}
add_shortcode( 'single_case_mobile_slider', 'case_mobile_slider' );

/* Dynamic post catgoery and client name on single post */

function waybrand_case_client(){
	ob_start();
	$args = array(
		'post_type' => 'casestudy', 
		'post_status' => 'publish',
		'orderby'   => 'date',
		'order'=> 'DSC',
		'suppress_filters' => true,
	);
	$query = new WP_Query($args);
	$html = "";
	if($query->have_posts()){
		$html .= '<div class="client-name">';
					$html .= '<h3 class="sticky"><span class="client-name">Client: </span><span class="client-display">'.get_field('client_name',$post_id).'</span></h3>
					 
					';
			wp_reset_postdata();
		$html .= '</div>';
		return $html;
	}
}
add_shortcode( 'client_code_short', 'waybrand_case_client' );


/** Case details shortcode */
function case_detail_shortcode(){
	ob_start();
	$args = array(
		'post_type' => 'casestudy', 
		'post_status' => 'publish',
		'orderby'   => 'date',
		'order'=> 'DSC',
		'suppress_filters' => true,
	);
	$query = new WP_Query($args);
	$html = "";
	if($query->have_posts()){
		$html .= '<div class="client-name">';
					$html .= '<p><b>Client:</b><br />'.get_field('client_name',$post_id).'</p>';
					if( get_field('what',$post_id) ) {
					$html .= '<p><b>What:</b><br />'.get_field('what',$post_id).'</p>';
					}
					if( get_field('when',$post_id) ) {
					$html .= '<p><b>When:</b><br />'.get_field('when',$post_id).'</p>';
					}
					if( get_field('where',$post_id) ) {
					$html .= '<p><b>Where:</b><br />'.get_field('where',$post_id).'</p>';
					}
					if( get_field('agency',$post_id) ) {
					$html .= '<p><b>Agency:</b><br />'.get_field('agency',$post_id).'</p>';
					}
					
			wp_reset_postdata();
		$html .= '</div>';
		return $html;
	}
}
add_shortcode( 'case_detail_code', 'case_detail_shortcode' );

/** Get Category on single post shortcode */
function single_case_category_fun(){
	ob_start();
	$args = array(
		'post_type' => 'casestudy', 
		'post_status' => 'publish',
		'orderby'   => 'date',
		'order'=> 'DSC',
		'suppress_filters' => true,
	);
	$query = new WP_Query($args);
	$html = "";
	if($query->have_posts()){

		$terms = get_the_terms( $post->ID, 'casestudy_category' );
			if ( ! empty( $terms ) ) {
				$term = array_shift( $terms );
				$html .= '<div class="post-taxonomy">';
				$html .= '<p class="heading_cat"><img src="https://gomowebb.com/focusneo-sv/wp-content/uploads/2022/12/right-path.png" width ="30" alt="" / >'.$term->name.'</p>';
				$html .= '</div>';
			}
		return $html;
	}

}
add_shortcode( 'single_case_category', 'single_case_category_fun' );

function single_news_category_fun(){
	ob_start();
	$args = array(
		'post_type' => 'casestudy', 
		'post_status' => 'publish',
		'orderby'   => 'date',
		'order'=> 'DSC',
		'suppress_filters' => true,
	);
	$query = new WP_Query($args);
	$html = "";
	if($query->have_posts()){

		$terms = get_the_terms( $post->ID, 'category' );
			if ( ! empty( $terms ) ) {
				$term = array_shift( $terms );
				$html .= '<div class="post-taxonomy">';
				$html .= '<p class="heading_cat"><img src="https://gomowebb.com/focusneo-sv/wp-content/uploads/2022/12/right-path.png" width ="30" alt="" / >'.$term->name.'</p>';
				$html .= '</div>';
			}
		return $html;
	}

}
add_shortcode( 'single_news_category', 'single_news_category_fun' );



/***************************
*** Team page shortcodes ***
****************************/

function team_desktop_layout(){
	$html = "";
	$html .= '<div class="team-section">';
		$rows = get_field('team_members', 'option');
		if( $rows ) {
			foreach( $rows as $row ) {
				$image = $row['profile_image'];
				
				$html .='<div class="team-box"> <!--<img src="'.$image['url'].'" />--> ';		
				$html .='<p class="namefnt14">'.$row['name'].'</p><hr />';
				$html .='<ul><li>'.$row['position'].'</li>
						<li>'.$row['email'].'</li>
						<li><a href="tel:'.$row['contact'].'">'.$row['contact'].'</a></li>
						</ul>
						<hr />
						<div class="short-desc">'.$row['short_description'].'</div></div>';
				}
	
			}
	$html .= '</div>';
	return $html;
	
}
add_shortcode('team_desktop_list', 'team_desktop_layout');

// Goteborg
function team_desktop_layout_goteborg(){
	$html = "";
	$html .= '<div class="team-section">';
		$rows = get_field('team_members_goteborg', 'option');
		if( $rows ) {
			foreach( $rows as $row ) {
				$image = $row['profile_image'];
				
				$html .='<div class="team-box"> <!--<img src="'.$image['url'].'" />--> ';		
				$html .='<p class="namefnt14">'.$row['name'].'</p><hr />';
				$html .='<ul><li>'.$row['position'].'</li>
						<li>'.$row['email'].'</li>
						<li><a href="tel:'.$row['contact'].'">'.$row['contact'].'</a></li>
						</ul>
						<hr />
						<div class="short-desc">'.$row['short_description'].'</div></div>';
				}
	
			}
	$html .= '</div>';
	return $html;
	
}
add_shortcode('team_desktop_list_got', 'team_desktop_layout_goteborg');

// Norway
function team_desktop_layout_nor(){
	$html = "";
	$html .= '<div class="team-section">';
		$rows = get_field('team_members_norway', 'option');
		if( $rows ) {
			foreach( $rows as $row ) {
				$image = $row['profile_image'];
				
				$html .='<div class="team-box"> <!--<img src="'.$image['url'].'" />--> ';		
				$html .='<p class="namefnt14">'.$row['name'].'</p><hr />';
				$html .='<ul><li>'.$row['position'].'</li>
						<li>'.$row['email'].'</li>
						<li><a href="tel:'.$row['contact'].'">'.$row['contact'].'</a></li>
						</ul>
						<hr />
						<div class="short-desc">'.$row['short_description'].'</div></div>';
				}
	
			}
	$html .= '</div>';
	return $html;
	
}
add_shortcode('team_desktop_list_nor', 'team_desktop_layout_nor');


// Finland
function team_desktop_layout_fin(){
	$html = "";
	$html .= '<div class="team-section">';
		$rows = get_field('team_members_finland', 'option');
		if( $rows ) {
			foreach( $rows as $row ) {
				$image = $row['profile_image'];
				
				$html .='<div class="team-box"> <!--<img src="'.$image['url'].'" />--> ';		
				$html .='<p class="namefnt14">'.$row['name'].'</p><hr />';
				$html .='<ul><li>'.$row['position'].'</li>
						<li>'.$row['email'].'</li>
						<li><a href="tel:'.$row['contact'].'">'.$row['contact'].'</a></li>
						</ul>
						<hr />
						<div class="short-desc">'.$row['short_description'].'</div></div>';
				}
	
			}
	$html .= '</div>';
	return $html;
	
}
add_shortcode('team_desktop_list_fin', 'team_desktop_layout_fin');


/* Team slider mobile */

function team_mobile_layout(){
	$html = "";
	$html .= '<div class="team-section team_slider swiper"><div class="swiper-wrapper">';
		$rows = get_field('team_members', 'option');
		if( $rows ) {
			foreach( $rows as $row ) {
				$image = $row['profile_image'];
				
				$html .='<div class="team-box swiper-slide"> 
							<img src="'.$image['url'].'" />
							<p class="namefnt14">'.$row['name'].'</p><hr />
							<ul>
								<li>'.$row['position'].'</li>
								<li>'.$row['email'].'</li>
								<li>'.$row['contact'].'</li>
							</ul>
							<hr />
							<div class="short-desc">'.$row['short_description'].'</div>
						</div>';
				}
	
			}
	$html .= '</div>
					<div class="swiper-button-next"></div>
			        <div class="swiper-button-prev"></div>
		</div>';
	return $html;
	
}
add_shortcode('team_mobile_slider', 'team_mobile_layout');

// Goteborg
function team_mobile_layout_got(){
	$html = "";
	$html .= '<div class="team-section team_slider swiper"><div class="swiper-wrapper">';
		$rows = get_field('team_members_goteborg', 'option');
		if( $rows ) {
			foreach( $rows as $row ) {
				$image = $row['profile_image'];
				
				$html .='<div class="team-box swiper-slide"> 
							<img src="'.$image['url'].'" />
							<p class="namefnt14">'.$row['name'].'</p><hr />
							<ul>
								<li>'.$row['position'].'</li>
								<li>'.$row['email'].'</li>
								<li>'.$row['contact'].'</li>
							</ul>
							<hr />
							<div class="short-desc">'.$row['short_description'].'</div>
						</div>';
				}
	
			}
	$html .= '</div>
					<div class="swiper-button-next"></div>
			        <div class="swiper-button-prev"></div>
		</div>';
	return $html;
	
}
add_shortcode('team_mobile_slider_got', 'team_mobile_layout_got');

// Norway
function team_mobile_layout_nor(){
	$html = "";
	$html .= '<div class="team-section team_slider swiper"><div class="swiper-wrapper">';
		$rows = get_field('team_members_norway', 'option');
		if( $rows ) {
			foreach( $rows as $row ) {
				$image = $row['profile_image'];
				
				$html .='<div class="team-box swiper-slide"> 
							<img src="'.$image['url'].'" />
							<p class="namefnt14">'.$row['name'].'</p><hr />
							<ul>
								<li>'.$row['position'].'</li>
								<li>'.$row['email'].'</li>
								<li>'.$row['contact'].'</li>
							</ul>
							<hr />
							<div class="short-desc">'.$row['short_description'].'</div>
						</div>';
				}
	
			}
	$html .= '</div>
					<div class="swiper-button-next"></div>
			        <div class="swiper-button-prev"></div>
		</div>';
	return $html;
	
}
add_shortcode('team_mobile_slider_nor', 'team_mobile_layout_nor');

// Finland
function team_mobile_layout_fin(){
	$html = "";
	$html .= '<div class="team-section team_slider swiper"><div class="swiper-wrapper">';
		$rows = get_field('team_members_finland', 'option');
		if( $rows ) {
			foreach( $rows as $row ) {
				$image = $row['profile_image'];
				
				$html .='<div class="team-box swiper-slide"> 
							<img src="'.$image['url'].'" />
							<p class="namefnt14">'.$row['name'].'</p><hr />
							<ul>
								<li>'.$row['position'].'</li>
								<li>'.$row['email'].'</li>
								<li>'.$row['contact'].'</li>
							</ul>
							<hr />
							<div class="short-desc">'.$row['short_description'].'</div>
						</div>';
				}
	
			}
	$html .= '</div>
					<div class="swiper-button-next"></div>
			        <div class="swiper-button-prev"></div>
		</div>';
	return $html;
	
}
add_shortcode('team_mobile_slider_fin', 'team_mobile_layout_fin');



/**************************
*** Case Listing Slider ***
***************************/


function case_listing_slider_function(){
	ob_start();
	$args = array(
		'post_type' => 'casestudy',
	//	'casestudy_category' => 'wayfinding',
		'posts_per_page' => 5, 
		'post_status' => 'publish',
		'orderby'   => 'date',
		'order'=> 'DSC',
        'meta_query' => array(
			array(
				'key'   => 'case_listing_slider',
				'value' => '1',
				)
			),
		'suppress_filters' => true,
	);
	$query = new WP_Query($args);
	$html = "";
	if($query->have_posts()){
		$html .= '<div class="case-listing-slider swiper"><div class="swiper-wrapper">';
			while($query->have_posts()){
				$query->the_post();
				$list_slider_image = get_field('list_slider_image');
				$listing_slider_text = get_field('listing_slider_text');
				$html .= '<div class="swiper-slide"> 
								<div class="case-slide" style="background-image:url('.$list_slider_image['url'].')">
								<a href="'.get_the_permalink().'" class="slider-title">'.$listing_slider_text.'</a>
								<a href="'.get_the_permalink().'" class="case-client slider-btn">
									<span class="case-client"><span class="client-name"> Client: </span><span class="client-display">'.get_field('client_name').'</span>
									</span>
								</a>
								 </div>
						  </div>';
					}
			wp_reset_postdata();
		$html .= '</div>
			<div class="swiper-pagination case"></div>	
		</div>';
		return $html;
	}
}
add_shortcode( 'case_listing_slider', 'case_listing_slider_function' );


function get_next_news(){
	ob_start();
	$args = array(
		'post_type' => 'post',
		'posts_per_page' => 1, 
		'post_status' => 'publish',
		'orderby'   => 'date',
		'order'=> 'DSC',
   		'suppress_filters' => true,
	);
	$query = new WP_Query($args);
	$html = "";
	if($query->have_posts()){
		$next_post = get_adjacent_post(false, '', false);
		$prev_post = get_adjacent_post( false, '', true );
		if($next_post) {
			$html .= '<a href="' . get_permalink($next_post->ID) . '" title="' . $next_post->post_title . '" class="next-post-btn">VISA NÄSTA ARTIKEL <img width="9" src="https://gomowebb.com/focusneo-sv/wp-content/uploads/2022/12/right-path.png" /></a>'; 
		}
	 else {
			$html .= '<a href="' . get_permalink($prev_post->ID) . '" title="' . $prev_post->post_title . '" class="next-post-btn">VISA FÖREGÅENDE ARTIKEL <img width="9" src="https://gomowebb.com/focusneo-sv/wp-content/uploads/2022/12/right-path.png" /></a>'; 
		} 
	return $html;
	}
}
add_shortcode( 'next_news_link', 'get_next_news' );


/********************************
*** Display Featured checkbox ***
****** on Post listing page *****
*********************************/


add_filter('manage_casestudy_posts_columns' , 'casestudy_columns');
function casestudy_columns($cols) {
	//Add custom columns
	$new_columns = array(
		'featured' => __('Featured', 'focusneo'),
	);
	return array_merge($cols, $new_columns);
}

add_filter('manage_casestudy_posts_custom_column','acf_admin_custom_columns',10, 2);
function acf_admin_custom_columns( $column, $post_id ) {
	switch( $column ) {
		case 'featured':
			$featured = get_field('use_as_featured', $post_id);
			if($featured){
				echo 'Yes';
			}
			else{
				echo 'No';
			}
			break; 
   }   
}

/****************************
*** Product page listing  ***
*****************************/

function new_get_product(){
	ob_start();
	$args = array(
		'post_type' => array('casestudy','post', 'product','page'),
		'posts_per_page' => -1, 
		'post_status' => 'publish',
		'orderby' => 'title',
		'order'   => 'ASC',
   		'suppress_filters' => true,
	);

	$html = "";	
	
	$posts = get_posts($args);

		$alphabet = range('a', 'z');
		$post_list = array();

		foreach ($alphabet as $letter) {
			$post_list[$letter] = array();
			foreach ($posts as $post) {
				if (strtolower(substr($post->post_title, 0, 1)) == $letter) {
					$post_list[$letter][] = $post;
				}
			}
		}

		$html .= '<div class="archive-box">';
		foreach ($alphabet as $letter) {
			
			if (count($post_list[$letter]) > 0) {
				$html .= '<div class="archive">';
					$html .= '<p class="header-list">'.strtoupper($letter).'</p>';
					$html .= '<ul class="archive-list">';
					foreach ($post_list[$letter] as $post) {
						$html .= '<li><a href="' . get_permalink($post->ID) . '">' . $post->post_title . '</a></li>';
					}
					$html .= '</ul>';
				$html .= '</div>';
			}
			
		}
		$html .= '</div>';
		return $html;
	}
add_shortcode( 'get_product_list_new', 'new_get_product' );



/**** 
*** Archive Mobile Template Accordion
****/

function get_product_accordion(){
	ob_start();
	$args = array(
		'post_type' => array('casestudy','post', 'product','page'),
		'posts_per_page' => -1, 
		'post_status' => 'publish',
		'orderby' => 'title',
		'order'   => 'ASC',
   		'suppress_filters' => true,
	);

	$html = "";	
	
	$posts = get_posts($args);

		$alphabet = range('a', 'z');
		$post_list = array();

		foreach ($alphabet as $letter) {
			$post_list[$letter] = array();
			foreach ($posts as $post) {
				if (strtolower(substr($post->post_title, 0, 1)) == $letter) {
					$post_list[$letter][] = $post;
				}
			}
		}

		$html .= '<div class="archive-box-mobile"><div id="archive-accordion" class="togglecontainer av-elegant-toggle avia-builder-el-3 el_after_av_codeblock avia-builder-el-last toggle_close_all enable_toggles">';
		foreach ($alphabet as $letter) {
			
			if (count($post_list[$letter]) > 0) {
				$html .= '<section class="av_toggle_section" itemscope="itemscope" itemtype="https://schema.org/CreativeWork"><div role="tablist" class="single_toggle" data-tags="{Alla} ">';
					$html .= '<p data-fake-id="#toggle-id-'.strtoupper($letter).'" class="toggler" itemprop="headline" role="tab" tabindex="0" aria-controls="toggle-id-1" style="">'.strtoupper($letter).'<span class="toggle_icon"></span></span></p>';
					$html .= '<div id="toggle-id-'.strtoupper($letter).'" class="toggle_wrap" style=""><div class="toggle_content invers-color " itemprop="text">
								<ul class="archive-list">';
					foreach ($post_list[$letter] as $post) {
						$html .= '<li><a href="' . get_permalink($post->ID) . '">' . $post->post_title . '</a></li>';
					}
					$html .= '</ul>   </div>
                    </div>
                </div>';
				$html .= ' </section>';
			}
			
		}
		$html .= '</div></div>';
		return $html;
	}
add_shortcode( 'product_accordion', 'get_product_accordion' );



/****
*** Archive filter Code
****/

function dropdown_filter_shortcode(){
    ob_start();
		include('ajax/dropdown-filter.php');
    return ob_get_clean();
}
add_shortcode('multiple-dropdown-filter', 'dropdown_filter_shortcode');

add_action( 'wp_ajax_get_posts_from_cat', 'get_posts_from_cat' );
add_action( 'wp_ajax_nopriv_get_posts_from_cat', 'get_posts_from_cat' );

function get_posts_from_cat(){
	extract($_POST);
	
	/* Data Comes from ajax request */

	$fun_d = $itfun_id;
	$pro_d = $itpro_id;

	$where_array = array();
	if( $fun_d )
		{
			$where_array[] = array('taxonomy' => 'filter_by_fun','field' => 'id','terms' => $fun_d);
		}
	if( $pro_d )
		{
			$where_array[] = array('taxonomy' => 'filter_by_pro','field' => 'id','terms' => $pro_d);
		}
	
	$args = array(
			'post_type' => array('casestudy','post', 'product','page'),
			'post_status' => 'publish',
			'posts_per_page' => 4,
			'orderby' => 'title',
			'order'   => 'ASC',
			'suppress_filters' => true,
			'meta_value' => $parent_post_id,
			'tax_query'		=> $where_array,	
		);
		$html ='';		
		/* Get Current Post detail */
		
		$post_slug = get_field('select_parent');
		$parent_post_id = $post_slug->ID;
		$my_query = new WP_Query($args);
			$post_groups = array();
			if ( $my_query->have_posts() ) {
				while ( $my_query->have_posts() ) {
					$my_query->the_post();
					$post_type = get_post_type();
					if ( ! isset( $post_groups[ $post_type ] ) ) {
						$post_groups[ $post_type ] = array();
					}
					$post_groups[ $post_type ][] = get_the_ID();
				}
			}
			else{
				$html .= '<p>No post found to your selection.</p>';
			}

			foreach ( $post_groups as $post_type => $post_ids ) {
				// Display the post type name as a heading
				$html .= '<div class="filter_list_box">';	
				$html .= '<h3>' . ucfirst( $post_type ) . 's</h3>';
				
				// Loop through the posts in the group and display them
				foreach ( $post_ids as $post_id ) {
					$post = get_post( $post_id );
					if ( has_post_thumbnail($post->ID)) {
							$html .= '<div>'.get_the_post_thumbnail( $post, 'portfolio' ).'';
						}else{
							$html .= '<div><img src="https://gomowebb.com/focusneo/wp-content/uploads/2023/03/placeholder.jpg"/>';
						}
					$html .= '<h5><a class="url_list_class" href="'.get_the_permalink($post).'">'.get_the_title($post).'</a></h5></div>';
					}
					$html .= '</div>';
				}

			
			echo $html;
			wp_die();
			
}


/**************************************************
*** Define javascript ajaxurl variable for ajax ***
***************************************************/
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