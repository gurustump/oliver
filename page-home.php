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

							<?php if (have_posts()) : while (have_posts()) : the_post(); 
								if (get_the_content()) { ?>
								<article id="post-<?php the_ID(); ?>" <?php post_class( 'cf' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">

									<header class="article-header"></header>
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
							$featuredPubs = get_posts(array(
								'post_type' => array('publication'),
								'numberposts' => 6,
								'category_name' => 'featured'
							));
							if(count($featuredPubs) > 0) { ?>
							<div class="featured-publications">
								<h2>Featured Pubilcations</h2>
								<ul>
									<?php foreach($featuredPubs as $key => $item) {
										$itemThumbArray = wp_get_attachment_image_src( get_post_thumbnail_id($item->ID), 'large-thumb');
										?>
										<li>
											<a href="<?php echo get_the_permalink($item->ID); ?>">
												<img class="item-thumb" src="<?php echo $itemThumbArray[0]; ?>" />
												<span class="item-title"><?php echo $item->post_title; ?></span>
											</a>
										</li>
									<?php } ?>
								</ul>
							</div>
							<?php } ?>
							
						</main>


<?php get_footer(); ?>
