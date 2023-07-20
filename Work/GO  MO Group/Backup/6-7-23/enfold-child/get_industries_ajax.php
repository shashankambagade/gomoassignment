<?php
require_once("../../../wp-load.php");

global $sitepress;

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
	exit();
}
$return_arr = array();

$posts_per_page = $_POST['posts_per_page'];
$offset = 0;


// lang from your javascript
$lang = $_POST['current_lang'];

// switch to the desired language
$sitepress->switch_lang( $lang );

if(isset($_POST['offset'])){
	$offset = $_POST['offset'];
}

$args = array(
	'post_type' => 'industries',
	'posts_per_page' => $posts_per_page,
	'orderby' => 'menu_order',
	'order' => 'ASC',
	'suppress_filters' => false,
);
if($offset){
	$args['offset'] = $offset;
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
			$categories = get_the_terms( get_the_id(), 'industries_category' );
			if ( ! empty( $categories ) ) {
				$cat = esc_html( $categories[0]->name );
			}
		}
		$imgurl = get_the_post_thumbnail( get_the_ID(), 'full' );
		if(!$imgurl){
			$url = get_stylesheet_directory_uri().'/images/post-placeholder.png';
			$imgurl = "<img src='".$url."' >";
		}
		
		if($offset > 0){
			$html .= '<div class="posts half-width-posts">
						<div class="industries-banner"><a href="'.get_permalink().'">'.$imgurl.'</a></div>
						<div class="caption">
							<div class="con">
								<p class="category-pull">'.$cat.'</p>
								<h4><a href="'.get_permalink().'">'.get_the_title().'</a></h4>
								<p>'.get_the_excerpt().'</p>
								<a href="'.get_permalink().'" class="avia-button avia-color-theme-color-subtle">';
									if($current_lang == "sv"){
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
									if($current_lang == "sv"){
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
										if($current_lang == "sv"){
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
