<?php

/***************************
***  index.php location reddirect ***
****************************/

header("Location:  https://www.beautycentre.se/", true, 301);
exit();
	
	
/***************************
***  Funcation.php hacks ***
****************************/

//***
//*** WP Enqueue style & script 
//***
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles', 99 );
function theme_enqueue_styles() {
	wp_enqueue_style('parent-style', get_template_directory_uri().'/style.css');
	wp_enqueue_style('custom-style', get_stylesheet_directory_uri().'/css/custom-style.css');
	wp_enqueue_style('swiper-css', get_stylesheet_directory_uri().'/css/swiper-bundle.min.css');
	wp_enqueue_style('print-css', get_stylesheet_directory_uri().'/css/print.css', array(), '0.1', 'print');
	wp_enqueue_style('child-style', get_stylesheet_directory_uri().'/style.css');
	wp_enqueue_script('swiper-js', get_stylesheet_directory_uri().'/js/swiper-bundle.min.js', array('jquery'), '1.0.0', true);
	if( is_page(353) || is_page(15116) ){
		wp_enqueue_script( 'matchheight_js', get_stylesheet_directory_uri() . '/js/jquery.matchHeight-min.js', array('jquery'), false, true );
	}
	if( is_page(12519) || is_page(15196) ){
		wp_enqueue_script( 'matchheight_js', get_stylesheet_directory_uri() . '/js/jquery.matchHeight-min.js', array('jquery'), false, true );
	}
	wp_enqueue_script('custom-js', get_stylesheet_directory_uri().'/js/custom.js');
	if( is_page(353) || is_page(15116) ){
		wp_enqueue_script( 'insights_js', get_stylesheet_directory_uri() . '/js/insights_main.js', array('jquery', 'custom-js'), false, true );
	}
	if( is_page(12519) || is_page(15196) ){
		wp_enqueue_script( 'industries_js', get_stylesheet_directory_uri() . '/js/industries_main.js', array('jquery', 'custom-js'), false, true );
	}
}

//***
//*** Comments
//***


//***
//*** Drop-down blank replaced with title CF7
//***

function my_wpcf7_form_elements($html) {
	$text = 'Country';
	$html = str_replace('<option value="">---</option>', '<option value="">' . $text . '</option>', $html);
	return $html;
}
add_filter('wpcf7_form_elements', 'my_wpcf7_form_elements');


//***
//*** Blog Author shortcode 
//***

add_shortcode( 'author_block', 'author_block_function' );
function author_block_function($params, $content = null) {
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
						<span><?php 
						if(ICL_LANGUAGE_CODE == "en"){
							echo 'About the Author';
						} else { 
							echo 'Om författaren';
						} 
						?></span>
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


//***
//*** Display Featured checkbox on Post listing page backend 
//***

add_filter('manage_insights_posts_columns' , 'insights_columns');
function insights_columns($cols) {
	unset(
		$cols['insight_subject'],
	);

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


//***
//*** To change the order of search results: pages and then posts/
//***

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


-------------------------------------------------------

Display Div one page
<?php if(is_front_page()) { ?>
<div class="sliderdiv"></div>
<?php } ?>


<?php if ( function_exists('yoast_breadcrumb') ) {
  yoast_breadcrumb('<p id="breadcrumbs">','</p>');
} ?>


short title

function short_title() {
$mytitleorig = get_the_title();
$title = html_entity_decode($mytitleorig, ENT_QUOTES, "UTF-8"); 

$limit = "37";
$pad="...";

if(strlen($title) >= ($limit+3)) {
$title = substr($title, 0, $limit) . $pad; }

echo $title;
}


function short_content($num) {
$limit = $num+1;
$content = str_split(get_the_content());
$length = count($content); if ($length>=$num) {
$content = array_slice( $content, 0, $num);
$content = implode("",$content)."..."; echo '<p>'.$content.'</p>'; }
else {
the_content(); } }



<?php if ( is_page ( array( 21 ))) { ?>
<div>
<div class="body_img"> 
<img src="http://www.abilgroup.com/wp-content/themes/twentytwelve/images/Real-Estate.jpg" width="100%" height="100%">
</div>
<?php } ?>


<?php dynamic_sidebar( 'sidebar-7' ); ?>

Home Page Title Hide
<?php if(is_front_page()) { ?> <?php } else { ?><h2 class="title"><?php the_title(); ?></h2> <?php } ?>

		<h1 class="entry-title"><?php the_title(); ?></h1>


<?php if(is_front_page()) { ?> 
<body <?php body_class(); ?>
<?php } else { ?>
<body <?php body_class(); ?>  onLoad="window.scroll(0, 650)"><?php } ?>



Menu 
<?php wp_nav_menu( array('menu' => 'Project Nav' )); ?>



Formidable (Contact Form)
user:nishat45
password:  krakozia
INKMY-ASAY1-02AB1-0FFJC

<?php echo do_shortcode('[YOUR_SHORT_CODE_HERE]');?>



bootstrap = ipad @media (min-width: 1200px)
responsive = ipad (@media (min-width: 1200px))  @media (min-width: 768px) and (max-width: 979px) {
theme = All pc



http://www.ieplcareer.com/germany_about.php

http://www.intellection.in/bridgesgame/GamePlay.aspx {Game}

<div class="sliderdiv"><?php if ( function_exists( 'soliloquy_slider' ) ) soliloquy_slider( '91' ); ?></div></div>

http://jquerymobile.com/demos/1.2.0/docs/pages/popup/index.html
http://davidwalsh.name/orientation-change
http://media02.hongkiat.com/mobile-navi-with-jquery/demo/index.html


http://background-pictures.feedio.net/yellow-background/
http://livedemo00.template-help.com/wt_41287/index.html
http://jqueryui.com/tabs/


http://mattkersley.com/responsive/
http://screenqueri.es/
http://www.responsinator.com/?url=http%3A%2F%2Fwww.thecircusworks.com%2Fdemo4%2Fthe-team.html
http://share.axure.com/7LMS1F/Home.html
http://twitter.github.io/bootstrap/scaffolding.html

http://www.cssplay.co.uk/layouts/fixit.html
http://www.javascriptkit.com/dhtmltutors/cssmediaqueriteam.html



https://developers.facebook.com/docs/reference/plugins/like/


Image horizontal reel scroll slideshow
http://lifearchitectsindia.com/

http://www.htmldrive.net/items/demo/1296/very-cool-Accordion-effect {photo Gallery}
http://www.mu-sigma.com/analytics/platforms/decision-sciences.html {Header Menu}
http://www.albanx.com/?pid=1&subid=20 {IMG Zoomin , out}


https://www.facebook.com/PagesSizesDimensions {Facebook Adds Dimensions}


http://www.cssigniter.com/preview/kontrol/photos/
http://www.designrazzi.com/2013/wordpress-music-themes/
http://www.freewebsitetemplates.com/templates/page-4


css3 efects
http://learn.shayhowe.com/advanced-html-css/css-transforms
http://www.webwingz.com/services.php
http://tympanus.net/Tutorials/OriginalHoverEffects/index.html
http://wedesignthemes.com/html/core/#home

referance links
http://www.remotedataexchange.com/portfolio.php

::selection{background:#F78118;color:#FFF;text-shadow:none}


http://oursocialtimes.com/a-5-step-guide-to-reputation-management-using-social-media/

<?php echo do_shortcode('[dopts id="1"]'); ?> thumbnail scroller shortcode


Theme Worpress Login 
Theme My Login 

<div class="user_login">
 <ul>
<?php if ( is_user_logged_in() ) { ?>
<li style="border-right:1px solid #666"> <?php global $current_user;
      get_currentuserinfo(); ?>
<?php      echo 'Welcome: '. '<a href="http://www.websitename.in/profile/">' . $current_user->user_firstname . "\n" . $current_user->user_lastname . '</a>';
?></li>
<li><a href="<?php echo wp_logout_url( home_url() ); ?>" title="Logout" class="">Logout</a></li>
<?php } else { ?>
<li><a href="http://www.websitename.in/register/" class="bgcolor">Register</a></li>
<li style="padding-right:0"><a href="<?php echo wp_login_url('/'); ?>" title="Login" class="bgcolor">Login</a></li>
</ul>
<?php } ?>
</div>

Theme Worpress Register Page Extra Fileds 
Cimy User Extra Fields

<?php
header("Status: 301 Moved Permanently");
header("Location:http://www.planmymedicaltrip.com/index.html");
?>
 
--------Images Fit all sizes example-------
file:///E:/work/RND/images-style/Portfolio%20Fullwidth%20_%20Lenze%20Site%20Template.html



     <div class="errorpostdiv">
    <?php $the_query = new WP_Query( 'showposts=5' ); ?>

    <?php while ($the_query -> have_posts()) : $the_query -> the_post(); ?>
<div class="loop_div">
    <h2><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
<a href="<?php the_permalink() ?>"><?php the_post_thumbnail( array(250,200) );?></a>
    <div class="shordescription"><?php the_excerpt(__('(more…)')); ?>
<a href="<?php the_permalink() ?>">Read More</a>
</div></div>
    <?php endwhile;?>
    </div>



------------Category code------------------

[product_category category="slug" per_page="12" columns="4" orderby="price" order="asc"]



------------Cart List Code------------------

<div class="cartlist">

<?php global $woocommerce; 

  $items = $woocommerce->cart->get_cart();
  foreach($items as $item => $values) { ?>
        <? // print_r($item); ?>
        <? $_product = $values['data']->post; ?>
    <p><a href="<?php echo $woocommerce->cart->get_cart_url(); ?>">    <? echo $_product->post_title; ?></a></p>
  <?php } ?></div>



------------Cart count and price Code------------------
<?php global $woocommerce; ?>
 
<a class="cart-contents" href="<?php echo $woocommerce->cart->get_cart_url(); ?>" title="<?php _e('View your shopping cart', 'woothemes'); ?>"><?php echo sprintf(_n('%d item', '%d items', $woocommerce->cart->cart_contents_count, 'woothemes'), $woocommerce->cart->cart_contents_count);?> - <?php echo $woocommerce->cart->get_cart_total(); ?></a>


http://www.iho.in/iho_benefits



Advanced Custom Fields
<?php the_field('About_Us'); ?>
<?php the_field('projects'); ?>


skovianc_foxberry

define('DB_NAME', 'skovianc_demo');

/** MySQL database username */
define('DB_USER', 'skovianc_skovian');

/** MySQL database password */
define('DB_PASSWORD', '^=5i6^oxybJM');

Image black and white image css code

.grayscale {
filter: url("data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\'><filter id=\'grayscale\'><feColorMatrix type=\'matrix\' values=\'0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0 0 0 1 0\'/></filter></svg>#grayscale")!important; /* Firefox 10+ */
filter: gray!important; /* IE6-9 */   -webkit-filter: grayscale(100%)!important; /* Chrome 19+ & Safari 6+ */ -webkit-transition: all .6s ease!important; /* Fade to color for Chrome and Safari */
 -webkit-backface-visibility: hidden; /* Fix for transition flickering */}
.grayscale:hover {filter: url("data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\'><filter id=\'grayscale\'><feColorMatrix type=\'matrix\' values=\'1 0 0 0 0, 0 1 0 0 0, 0 0 1 0 0, 0 0 0 1 0\'/></filter></svg>#grayscale");
-webkit-filter: grayscale(0%);}

.grayscale {
    filter: url("data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\'><filter id=\'grayscale\'><feColorMatrix type=\'matrix\' values=\'0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0 0 0 1 0\'/></filter></svg>#grayscale");
}
.grayscale:hover {
    filter: url("data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\'><filter id=\'grayscale\'><feColorMatrix type=\'matrix\' values=\'1 0 0 0 0, 0 1 0 0 0, 0 0 1 0 0, 0 0 0 1 0\'/></filter></svg>#grayscale");
}


Wordpress Theme Name change

<?php/** * @package WordPress * @subpackage ZydecoMedia_ProServe1 */?>


Magento - maintenance
http://www.templatemonster.com/help/files/Magento/Magento_how_to_put_the_website_in_maintenance%20mode.htm
http://www.templatemonster.com/help/magento-how-to-put-the-website-in-maintenance-mode.html


--------------------Wordpress load script------------------------------
<script>
if (document.documentElement.clientWidth > 1080) {
$(document).ready(function(){
// pure JS


 
// with jQuery
// window.mySwipe = $('#mySwipe').Swipe().data('Swipe');
})
} 
</script>


8qP5ADn3aaeWho49IEVA1cyEyJ0Iti40
trans_jap$4610 - LassPass Password
http://sevenspark.com/docs/ubermenu-3/video-tutorials


function unset_sticky_for_category($cat) {
  $sticky = get_option( 'sticky_posts' );
  $sp = new WP_Query(
    array(
      'fields' => 'ids',
      'post__in' => $sticky,
      'cat' => $cat // your category to un-sticky
    )
  );

  if (!empty($sp->posts)) {
    $sticky = array_diff($sticky,$sp->posts);
  }

  update_option('sticky_posts',$sticky);
}
unset_sticky_for_category(16);

www.emails.skovian.com
http://www.rapidtables.com/web/tools/redirect-generator.htm


$cur_url=$_SERVER['REQUEST_URI'];
if (strpos($cur_url, 'events') !== false){ 
add_filter('body_class','twentytwelvechild_body_classes',20);
function twentytwelvechild_body_classes( $classes ) {
	foreach($classes as $key => $value) {
		if ($value == 'full-width') unset($classes[$key]);
	}
	return $classes;
}
}

cd d:
cd xampp/htdocs/irys-bi
git add .
git commit
git pull
git push

majhapaper
Host : majhapaper.com
ssh root@majhapaper.com
password- VQS5nkt7zPXy


1st Step

UPDATE wp_options 
    SET
        option_value = replace(
            option_value,
            'http://oldsite.com/path',
            'http://spankingnew.com/otherpath'
        )
    WHERE
        option_name = 'home'
    OR
option_name = 'siteurl';

2nd step

UPDATE wp_posts 
    SET
        post_content = replace(
            post_content,
            'http://oldsite.com/path',
            'http://spankingnew.com/otherpath'
        ),
        guid = replace(
            guid,
            'http://oldsite.com/path',
            'http://spankingnew.com/otherpath'
);

Tran$jap*#4610

Sakale Educon putty permission login details
ec2-user
esakal@123

AVADA APIKEY
A4CDAyJnHQ5rowBiOAsdowYIN3kZjloQ
Estimation & Payment Forms
f0f1b165-cc25-4daf-a216-2030c57c402e

Formidable APIKEY
INKMY-ASAY1-02AB1-0FFJC


----------------------

<!-----------DIV HIDE SHOW------->

<script>
		(function($) {
    $(document).ready(function() {
  $('#open_id').on('click',function(){
   $('#div_id').css('display','block');
  });
  
  $('#close_id').on('click',function(){
   $('#div_id').css('display','none');
  });
 });
})(jQuery);
		</script>
		
<!-----------DIV HIDE SHOW------->