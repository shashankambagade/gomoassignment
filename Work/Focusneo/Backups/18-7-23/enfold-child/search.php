<?php
	if( ! defined( 'ABSPATH' ) ){ die(); }

	global $avia_config;


	/*
	 * get_header is a basic wordpress function, used to retrieve the header.php file in your theme directory.
	 */
	 get_header();

	 //	allows to customize the layout
	 do_action( 'ava_search_after_get_header' );


	 $results = avia_which_archive();
	 echo avia_title( array( 'title' => $results ) );

	 do_action( 'ava_after_main_title' );
	 ?>

		<div class='container_wrap container_wrap_first main_color <?php avia_layout_class( 'main' ); ?>'>

			<div class='container'>

				<main id='search-template' class='content template-search <?php avia_layout_class( 'content' ); ?> units' <?php avia_markup_helper( array( 'context' => 'content' ) );?>>
					<div class="content-block">
						<div class='page-heading-container clearfix'>
							<section class="search_form_field">

								<?php
									echo '<div class="inline-box ">
									<div><img src="https://gomowebb.com/focusneo/wp-content/themes/enfold-child/images/right-path.png" width="24" alt="" /> ' ;	
									get_search_form();

									echo "</div><div class='noflex'><h4 class='extra-mini-title widgettitle'>{$results}</h4></div></div>";

									if( ! empty( $_GET['s'] ) || have_posts() )
									{			
										/* Run the loop to output the posts.
										* If you want to overload this in a child theme then include a file
										* called loop-search.php and that will be used instead.
										*/
										$more = 0;
										get_template_part( 'includes/loop', 'search' );
			
									}
						 
								?>

							</section>
						</div>
					</div>
					<div class="image-block">
						<?php 
								$select_image = get_field('select_image', 'option');					
						?>		 
						
						<img src="<?php echo esc_url( $select_image['url'] ); ?>" width="" height="" alt="<?php echo esc_attr( $select_image['alt'] ); ?>" /> 
					</div>
				</main>
				
				<?php

				//get the sidebar
				$avia_config['currently_viewing'] = 'page';

				get_sidebar();

				?>

			</div><!--end container-->

		</div><!-- close default .container_wrap element -->

<?php
		get_footer();
