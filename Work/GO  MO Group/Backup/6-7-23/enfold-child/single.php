<?php
	if ( ! defined( 'ABSPATH' ) )	{ die(); }
	
	global $avia_config;

	/*
	 * get_header is a basic wordpress function, used to retrieve the header.php file in your theme directory.
	 */
	get_header();

	 
	$title  = __('Blog - Latest News', 'avia_framework'); //default blog title
	$t_link = home_url('/');
	$t_sub = '';

	if( avia_get_option( 'frontpage' ) && $new = avia_get_option( 'blogpage' ) )
	{
		$title = get_the_title( $new ); //if the blog is attached to a page use this title
		$t_link = get_permalink( $new );
		$t_sub = avia_post_meta( $new, 'subtitle' );
	}

	if( get_post_meta( get_the_ID(), 'header', true ) != 'no' ) 
	{
		echo avia_title( array( 'heading' => 'strong', 'title' => $title, 'link' => $t_link, 'subtitle' => $t_sub ) );
	}
	
	do_action( 'ava_after_main_title' );

?>

		<div class='container_wrap container_wrap_first main_color <?php avia_layout_class( 'main' ); ?>'>

			<div class='container template-blog template-single-blog '>

				<main class='content units <?php avia_layout_class( 'content' ); ?> <?php echo avia_blog_class_string(); ?>' <?php avia_markup_helper(array('context' => 'content','post_type'=>'post'));?>>
					<div class="article-top-links">
						<a class="back-to-insights" href="<?php echo home_url(). '/insikter/' ?>">BACK</a>
						<div class="social-share" style="margin-top: 10px;">
							<a class="facebook-btn" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo get_permalink();?>" target="_blank" rel="nofollow" data-toggle="tooltip" data-placement="right" title="Share on facebook"><img src="<?php echo get_stylesheet_directory_uri().'/images/social/facebook.png'; ?>" alt="facebook"></a>
							<a class="twitter-btn" href="https://twitter.com/intent/tweet?text=<?php echo get_the_title(); ?>&amp;url=<?php echo get_permalink();?>" target="_blank" rel="nofollow" data-toggle="tooltip" data-placement="right" title="Share on twitter"><img src="<?php echo get_stylesheet_directory_uri().'/images/social/twitter.png'; ?>" alt="twitter"></a>
							<a class="linkedin-btn" href="https://www.linkedin.com/cws/share?url=<?php echo get_permalink();?>" target="_blank" rel="nofollow" data-toggle="tooltip" data-placement="right" title="Share on linkedin"><img src="<?php echo get_stylesheet_directory_uri().'/images/social/linkedin.png'; ?>" alt="linkedin"></a>
							<a class="print-btn" rel="nofollow" data-toggle="tooltip" data-placement="right" title="Print this document"><img src="<?php echo get_stylesheet_directory_uri().'/images/social/print.png'; ?>" alt="print"></a>
							<a class="mail-btn" href="mailto:?subject=Please read this artical&amp;body=Check out the post <?php echo get_permalink();?>" target="_blank" rel="nofollow" data-toggle="tooltip" data-placement="right" title="Share with email"><img src="<?php echo get_stylesheet_directory_uri().'/images/social/mail.png'; ?>" alt="facebook"></a>
						</div>
					</div>
                    <?php
                    /* Run the loop to output the posts.
                    * If you want to overload this in a child theme then include a file
                    * called loop-index.php and that will be used instead.
                    *
                    */

                        get_template_part( 'includes/loop', 'index' );
						
						$blog_disabled = ( avia_get_option('disable_blog') == 'disable_blog' ) ? true : false;
						
						if( ! $blog_disabled )
						{
	                        //show related posts based on tags if there are any
	                        get_template_part( 'includes/related-posts' );
	
	                        //wordpress function that loads the comments template "comments.php"
	                        //comments_template();
						}
                    ?>

				<!--end content-->
				</main>

				<?php
				$avia_config['currently_viewing'] = 'blog';
				//get the sidebar
				get_sidebar();


				?>


			</div><!--end container-->

		</div><!-- close default .container_wrap element -->


<?php 
		get_footer();
		
		