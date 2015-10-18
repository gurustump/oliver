			
				
					<div id="sidebar" class="sidebar">
						<?php
							$logoTag = is_front_page() || is_home() ? 'h1' : 'p';
						?>
						<p id="sidebarLogo" class="h1" itemscope itemtype="http://schema.org/Organization">
							<a href="<?php echo home_url(); ?>" rel="nofollow">
								<?php /*<img class="mobile-logo" src="<?php echo get_template_directory_uri(); ?>/library/images/horiz-logo-575w.png" alt="<?php bloginfo('name'); ?>" /> */ ?>
								<img class="standard-logo" src="<?php echo get_template_directory_uri(); ?>/library/images/oliver-arts-logo-320x.jpg" alt="<?php bloginfo('name'); ?>" />
							</a>
						</p>

						<?php // if you'd like to use the site description you can un-comment it below ?>
						<?php // bloginfo('description'); ?>

						<nav role="navigation" itemscope itemtype="http://schema.org/SiteNavigationElement" class="MAIN_NAV main-nav">
							<?php wp_nav_menu(array(
								'container' => false,                           // remove nav container
								'container_class' => 'menu',                 // class of container (should you choose to use it)
								'menu' => __( 'The Main Menu', 'bonestheme' ),  // nav name
								'menu_class' => 'nav top-nav',               // adding custom nav class
								'theme_location' => 'main-nav',                 // where it's located in the theme
								'before' => '',                                 // before the menu
									'after' => '',                                  // after the menu
									'link_before' => '',                            // before each link
									'link_after' => '',                             // after each link
									'depth' => 0,                                   // limit the depth of the nav
								'fallback_cb' => ''                             // fallback function (if there is one)
							)); ?>
						</nav>
						<div class="sidebar-desktop">
							<?php get_sidebar(); ?>
						</div>
					</div>
					<div class="sidebar-mobile">
						<?php include( TEMPLATEPATH . '/sidebar.php'); ?>
					</div>
				</div><?php // end inner-content ?>
			</div><?php // end content ?>
			
			<footer class="footer" role="contentinfo" itemscope itemtype="http://schema.org/WPFooter">

				<div id="inner-footer" class="inner-footer wrap cf">

					<nav role="navigation">
						<?php wp_nav_menu(array(
    					'container' => 'div',                           // enter '' to remove nav container (just make sure .footer-links in _base.scss isn't wrapping)
    					'container_class' => 'footer-links cf',         // class of container (should you choose to use it)
    					'menu' => __( 'Footer Links', 'bonestheme' ),   // nav name
    					'menu_class' => 'nav footer-nav cf',            // adding custom nav class
    					'theme_location' => 'footer-links',             // where it's located in the theme
    					'before' => '',                                 // before the menu
    					'after' => '',                                  // after the menu
    					'link_before' => '',                            // before each link
    					'link_after' => '',                             // after each link
    					'depth' => 0,                                   // limit the depth of the nav
    					'fallback_cb' => 'bones_footer_links_fallback'  // fallback function
						)); ?>
					</nav>

					<p class="source-org copyright">&copy; <?php echo date('Y'); ?> <?php bloginfo( 'name' ); ?></p>

				</div>

			</footer>

		</div>

		<?php // all js scripts are loaded in library/bones.php ?>
		<?php wp_footer(); ?>

	</body>

</html> <!-- end of site. what a ride! -->
