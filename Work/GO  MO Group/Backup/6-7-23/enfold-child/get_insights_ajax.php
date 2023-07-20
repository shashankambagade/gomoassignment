<?php
define('WP_USE_THEMES', false);  
require_once('../../../wp-load.php');

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
	exit();
}
$return_arr = array();

$posts_per_page = $_POST['posts_per_page'];
$service_filter = $_POST['service_filter'];
//$industry_filter = $_POST['industry_filter'];
$format_filter = $_POST['format_filter'];
//$current_lang = $_POST['current_lang'];
$offset = 0;

if(isset($_POST['offset'])){
	$offset = $_POST['offset'];
}

$service_filter = ($service_filter == '*')? '' : $service_filter;
//$industry_filter = ($industry_filter == '*')? '' : $industry_filter;
$format_filter = ($format_filter == '*')? '' : $format_filter;

$args = array(
	'post_type' => 'insights',
	'post_status' => 'publish', 
	'posts_per_page' => $posts_per_page,
	'orderby' => 'publish_date', 
	'order' => 'DESC',
	//'suppress_filters' => false,
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
	$args['meta_query'][] = array(
		'key'     => 'format_filters',
		'value'   => '"'.$format_filter.'"',
		'compare' => 'LIKE',
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
		/*$post_tags = get_the_terms( get_the_id(), 'insight_tag' );
		$post_tags_str = '';*/

		$str = $service_filter.' '. $format_filter ;
		if($offset > 0){
			$html .= '<div class="posts half-width-posts" data-id="'.get_the_id().'">
						<div class="industries-banner"><a href="'.get_permalink().'">'.$imgurl.'</a></div>
						<div class="caption">
							<div class="con">
								<p class="category-pull">'.$cat.'</p>
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
		}else{
			if($i == 1 ){
				$html .= '<div class="posts full-width-posts">';
					$html .= '<div class="industries-banner"><a href="'.get_permalink().'">'.$imgurl.'</a></div>';
					$html .= '<div class="caption">';
						$html .= '<div class="con">';
							$html .= '<div class="left-side-tile">';
								$html .= '<p class="category-pull">'.$cat.'</p>';
								$html .= '<h4><a href="'.get_permalink().'">'.get_the_title().'</a></h4>';
							$html .= '</div>';
							$html .= '<div class="right-side-excerpt">';
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
				$html .= '</div></div>';
			}else{
				$html .= '<div class="posts half-width-posts">
							<div class="industries-banner"><a href="'.get_permalink().'">'.$imgurl.'</a></div>
							<div class="caption">
								<div class="con">
									<p class="category-pull">'.$cat.'</p>
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
