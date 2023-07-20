<?php
/*
Template Name: Industries Page
*/
	if ( !defined('ABSPATH') ){ die(); }
	
	global $avia_config, $wp_query;

	/*
	 * get_header is a basic wordpress function, used to retrieve the header.php file in your theme directory.
	 */
	get_header();

	/**
	 * @used_by				enfold\config-wpml\config.php				10
	 * @since 4.5.1
	 */
	do_action( 'ava_page_template_after_header' );

 	 if( get_post_meta(get_the_ID(), 'header', true) != 'no') echo avia_title();
 	 
 	 do_action( 'ava_after_main_title' );
	 ?>
	 	<style type="style/css">
	 		@media(min-width: 768px){
		 		.col-sm-6{
		 			width: 50%;
		 			float: left;
		 		}
	 		}
	 	</style>
		<div class='container_wrap container_wrap_first main_color <?php avia_layout_class( 'main' ); ?>'>

			<div class='container'>



			</div><!--end container-->

		</div><!-- close default .container_wrap element -->



<?php 
get_footer();
