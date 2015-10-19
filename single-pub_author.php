<?php
/*
 * CUSTOM POST TYPE TEMPLATE
 *
 * This is the custom post type post template. If you edit the post type name, you've got
 * to change the name of this template to reflect that name change.
 *
 * For Example, if your custom post type is "register_post_type( 'bookmarks')",
 * then your single template should be single-bookmarks.php
 *
 * Be aware that you should rename 'custom_cat' and 'custom_tag' to the appropiate custom
 * category and taxonomy slugs, or this template will not finish to load properly.
 *
 * For more info: http://codex.wordpress.org/Post_Type_Templates
*/
?>

<?php get_header(); ?>

						<main id="main" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">

							<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
							<?php $meta = get_post_meta(get_the_ID());
							/* echo '<pre style="border:1px solid #ccc;padding:20px">';
							print_r($meta); 
							echo '</pre>'; */
							$author = get_post($meta[_oliver_publications_author_ID][0]);
							/* echo '<pre style="border:1px solid #ccc;padding:20px">';
							print_r($author); 
							echo '</pre>'; */
							?>
							<article id="post-<?php the_ID(); ?>" <?php post_class('cf'); ?> role="article">

								<header class="article-header">

									<h1 class="single-title custom-post-type-title"><?php the_title(); ?></h1>

								</header>

								<section class="entry-content cf">
									<?php
										// the content (pretty self explanatory huh)
										the_post_thumbnail('medium');
										the_content();

									?>
								</section> <!-- end article section -->

								<footer class="article-footer">
									<?php 
									/*
									echo '<pre style="border:1px solid #ccc;padding:20px">';
									print_r(get_the_ID()); 
									echo '</pre>';
									*/
									$pubs = get_posts(array(
										'post_type' => 'publication',
										'meta_key' => '_oliver_publications_author_ID',
										'meta_query' => array (
											'key' => '_oliver_publications_author_ID',
											'value' => get_the_ID(),
											'compare' => 'LIKE',
										)
									)); 
									/*
									echo '<pre style="border:1px solid #ccc;padding:20px">';
									print_r($pubs); 
									echo '</pre>';
									
									echo '<pre style="border:1px solid #ccc;padding:20px">';
									print_r(get_post_meta($pubs[0]->ID)); 
									echo '</pre>';*/
									if(count($pubs) > 0) { ?>
									<div class="author-publications thumb-grid">
										<h2>Pubilcations by this Author</h2>
										<ul class="thumbs">
											<?php foreach($pubs as $key => $item) {
												$itemThumbArray = wp_get_attachment_image_src( get_post_thumbnail_id($item->ID), 'cover-small');
												?>
												<li>
													<a href="<?php echo get_the_permalink($item->ID); ?>">
														<span class="item-thumb-container">
															<img class="item-thumb" src="<?php echo $itemThumbArray[0]; ?>" />
														</span>
														<span class="item-text-container">
															<span class="item-title"><?php echo $item->post_title; ?></span>
														</span>
													</a>
												</li>
											<?php } ?>
										</ul>
									</div>
									<?php } ?>
								</footer>

								<?php // comments_template(); ?>

							</article>

							<?php endwhile; ?>

							<?php else : ?>

									<article id="post-not-found" class="hentry cf">
										<header class="article-header">
											<h1><?php _e( 'Oops, Post Not Found!', 'bonestheme' ); ?></h1>
										</header>
										<section class="entry-content">
											<p><?php _e( 'Uh Oh. Something is missing. Try double checking things.', 'bonestheme' ); ?></p>
										</section>
										<footer class="article-footer">
											<p><?php _e( 'This is the error message in the single-custom_type.php template.', 'bonestheme' ); ?></p>
										</footer>
									</article>

							<?php endif; ?>

						</main>

<?php get_footer(); ?>
