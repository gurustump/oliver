<?php
/*
 Template Name: List
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

							<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

							<article id="post-<?php the_ID(); ?>" <?php post_class( 'cf' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">

								<header class="article-header">

									<h1 class="page-title single-title" itemprop="headline"><?php the_title(); ?></h1>

								</header> <?php // end article header ?>

								<section class="entry-content cf" itemprop="articleBody">
									<?php
										// the content (pretty self explanatory huh)
										the_content();
									?>
						
									<?php 
									$pubs = get_posts(array(
										'post_type' => array('publication'),
										'numberposts' => -1,
									));
									if(count($pubs) > 0) { ?>
										<div class="films-list row-index">
											<div class="row-index-inner">
												<ul>
												<?php global $post;
												foreach($pubs as $key => $item) {
													$post = $item;
													setup_postdata($post);
													$meta = get_post_meta($item->ID); 
													$author = get_post($meta[_oliver_publications_author_ID][0]);
													$author = $author->ID == $item->ID ? false : $author;
													$links = get_post_meta($item->ID, '_oliver_publications_links', true);
													$itemThumbArray = wp_get_attachment_image_src( get_post_thumbnail_id($item->ID), 'cover-small'); ?>
													<li>
														<a class="img-container" href="<?php the_permalink(); ?>">
															<img class="item-thumb" src="<?php echo $itemThumbArray[0]; ?>" />
														</a>
														<div class="item-content">
															<h2 class="item-head"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
															<?php if ($author || $meta[_oliver_publications_num_pages][0] || $meta[_oliver_publications_price][0] || $meta[_oliver_publications_isbn][0]) { ?>
															<p class="byline">
																<?php if ($author) { ?>
																<span class="author">By <a href="<?php echo get_the_permalink($author->ID); ?>"><?php echo $author->post_title;?></a></span>
																<?php } ?>
																<?php if ($meta[_oliver_publications_num_pages][0]) { ?>
																<span class="pages"><?php echo $meta[_oliver_publications_num_pages][0]; ?> pages</span>
																<?php } ?>
																<?php if ($meta[_oliver_publications_price][0]) { ?>
																<span class="price">$<?php echo $meta[_oliver_publications_price][0]; ?></span>
																<?php } ?>
																<?php if ($meta[_oliver_publications_isbn][0]) { ?>
																<span class="isbn">ISBN: <?php echo $meta[_oliver_publications_isbn][0]; ?></span>
																<?php } ?>
															</p>
															<?php } ?>
															<div class="item-body">
																<?php the_excerpt(); ?>
																<?php if ($links) { ?>
																	<div class="link-container">
																		<?php foreach($links as $key => $link) { ?>
																			<a href="<?php echo $link[url]; ?>" class="btn btn-small btn-external btn-<?php echo $link[css_select]; ?>" target="_blank"><?php echo $link[title]; ?></a>
																		<?php } ?>
																	</div>
																<?php } ?>
															</div>
														</div>
													</li>
												<?php } ?>
												</ul>
												
											</div>
										</div>
									<?php } ?>
								</section> <?php // end article section ?>

								<footer class="article-footer cf">

								</footer>


							</article>
							<?php 	endwhile; endif; ?>
							
						</main>


<?php get_footer(); ?>
