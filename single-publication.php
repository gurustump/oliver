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
							$author = $author->ID == get_the_ID() ? false : $author;
							$links = get_post_meta(get_the_ID(), '_oliver_publications_links', true);
							/*echo '<pre style="border:1px solid #ccc;padding:20px">';
							print_r($links); 
							echo '</pre>';*/
							$thisPubCats = get_the_terms(get_the_ID(), 'publication_cat');
							$pubCatsHumanStr = '';
							if ($thisPubCats[0]) {
								foreach($thisPubCats as $k => $pubCat) {
									$pubCatsHumanStr .= $pubCat->name . ($k + 1 == count($thisPubCats) ? '' : ', ');
								}
							}
							?>
							<article id="post-<?php the_ID(); ?>" <?php post_class('cf'); ?> role="article">

								<header class="article-header">

									<h1 class="single-title custom-post-type-title"><?php the_title(); ?></h1>
									<?php if ($author || $meta[_oliver_publications_num_pages][0] || $meta[_oliver_publications_price][0] || $meta[_oliver_publications_isbn][0]) { ?>
									<p class="byline">
										<?php if ($author) { ?>
										<span class="author pub-author">By <a href="<?php echo get_the_permalink($author->ID); ?>"><?php echo $author->post_title;?></a></span>
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
										<?php if ($pubCatsHumanStr != '') { ?>
										<span class="pub-cats"><?php echo $pubCatsHumanStr; ?></span>
										<?php } ?>
									</p>
									<?php } ?>

								</header>

								<section class="entry-content cf">
									<div class="post-thumb-container">
										<?php if ($links) { ?>
											<div class="link-container-upper">
												<a href="<?php echo $links[0][url]; ?>" class="btn btn-external btn-<?php echo $links[0][css_select]; ?>" target="_blank"><?php echo $links[0][title]; ?></a>
											</div>
										<?php } ?>
										<?php the_post_thumbnail('cover-medium'); ?>
										<?php if (count($links) > 1) { ?>
											<div class="link-container-lower">
												<?php foreach($links as $key => $link) { 
													if ($key != 0) {
													?>
													<a href="<?php echo $link[url]; ?>" class="btn btn-external btn-<?php echo $link[css_select]; ?>" target="_blank"><?php echo $link[title]; ?></a>
												<?php } } ?>
											</div>
										<?php } ?>
									</div>
									<?php
										the_content();
									?>
								</section> <!-- end article section -->

								<footer class="article-footer">
									<?php if ($author) { ?>
									<div class="about-author entry-content">
										<a class="author-img" href="<?php echo get_the_permalink($author->ID); ?>">
											<?php echo get_the_post_thumbnail($author->ID, 'thumbnail', array('class' => 'alignright')); ?>
										</a>
										<h2>About <a href="<?php echo get_the_permalink($author->ID); ?>"><?php echo $author->post_title; ?></a></h2>
										<div>
											<?php echo $author->post_content; ?>
										</div>
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
