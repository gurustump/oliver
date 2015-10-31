<?php
/*
 Template Name: Home
 *
 * This is your custom page template. You can create as many of these as you need.
 * Simply name is "page-whatever.php" and in add the "Template Name" title at the
 * top, the same way it is here.
 *
 * When you create your page, you can just select the template and viola, you have
 * a custom page template to call your very own. Your mother would be so proud.
 *
 * For more info: http://codex.wordpress.org/Page_Templates
*/
?>

<?php get_header(); ?>

						<main id="main" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">
						
							<?php 
							$featuredPubs = get_posts(array(
								'post_type' => array('publication'),
								'numberposts' => 12,
								'category_name' => 'featured',
								'order' => 'ASC',
								'orderby' => 'meta_value_num',
								'meta_key' => '_oliver_publications_featured_order'
							));
							if (count($featuredPubs) > 0) { 
								thumbGrid($featuredPubs, 'Featured Publications', 'featured-publications');
							} ?>

							<?php if (have_posts()) : while (have_posts()) : the_post(); 
								if (get_the_content()) { ?>
								<article id="post-<?php the_ID(); ?>" <?php post_class( 'cf' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">
									<section class="entry-content cf" itemprop="articleBody">
										<?php
											// the content (pretty self explanatory huh)
											the_content();
										?>
									</section>
								</article>
								<?php }
							endwhile; endif; ?>
							
							<?php 
							$newsletterForm = get_posts(array(
								'name' => 'newsletter-signup',
								'post_type' => 'module',
								'numberposts' => 1
							));
							echo $newsletterForm[0]->post_content; ?>
							
						</main>


<?php get_footer(); ?>
