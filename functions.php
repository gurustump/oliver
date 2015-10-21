<?php
/*
Author: Eddie Machado
URL: http://themble.com/bones/

This is where you can drop your custom functions or
just edit things like thumbnail sizes, header images,
sidebars, comments, etc.
*/

// LOAD BONES CORE (if you remove this, the theme will break)
require_once( 'library/bones.php' );

// CUSTOMIZE THE WORDPRESS ADMIN (off by default)
require_once( 'library/admin.php' );

/*********************
LAUNCH BONES
Let's get everything up and running.
*********************/

function bones_ahoy() {

  //Allow editor style.
  add_editor_style( get_stylesheet_directory_uri() . '/library/css/editor-style.css' );

  // let's get language support going, if you need it
  load_theme_textdomain( 'bonestheme', get_template_directory() . '/library/translation' );

  // USE THIS TEMPLATE TO CREATE CUSTOM POST TYPES EASILY
  require_once( 'library/custom-post-type.php' );

  // launching operation cleanup
  add_action( 'init', 'bones_head_cleanup' );
  // A better title
  add_filter( 'wp_title', 'rw_title', 10, 3 );
  // remove WP version from RSS
  add_filter( 'the_generator', 'bones_rss_version' );
  // remove pesky injected css for recent comments widget
  add_filter( 'wp_head', 'bones_remove_wp_widget_recent_comments_style', 1 );
  // clean up comment styles in the head
  add_action( 'wp_head', 'bones_remove_recent_comments_style', 1 );
  // clean up gallery output in wp
  add_filter( 'gallery_style', 'bones_gallery_style' );

  // enqueue base scripts and styles
  add_action( 'wp_enqueue_scripts', 'bones_scripts_and_styles', 999 );
  // ie conditional wrapper

  // launching this stuff after theme setup
  bones_theme_support();

  // adding sidebars to Wordpress (these are created in functions.php)
  add_action( 'widgets_init', 'bones_register_sidebars' );

  // cleaning up random code around images
  add_filter( 'the_content', 'bones_filter_ptags_on_images' );
  // cleaning up excerpt
  add_filter( 'excerpt_more', 'bones_excerpt_more' );

} /* end bones ahoy */

// let's get this party started
add_action( 'after_setup_theme', 'bones_ahoy' );


/************* OEMBED SIZE OPTIONS *************/

if ( ! isset( $content_width ) ) {
	$content_width = 680;
}

/************* THUMBNAIL SIZE OPTIONS *************/

// Thumbnail sizes
add_image_size( 'cover-small', 160, 240 );
add_image_size( 'cover-medium', 320, 480 );

/*
to add more sizes, simply copy a line from above
and change the dimensions & name. As long as you
upload a "featured image" as large as the biggest
set width or height, all the other sizes will be
auto-cropped.

To call a different size, simply change the text
inside the thumbnail function.

For example, to call the 300 x 100 sized image,
we would use the function:
<?php the_post_thumbnail( 'bones-thumb-300' ); ?>
for the 600 x 150 image:
<?php the_post_thumbnail( 'bones-thumb-600' ); ?>

You can change the names and dimensions to whatever
you like. Enjoy!
*/

add_filter( 'image_size_names_choose', 'bones_custom_image_sizes' );

function bones_custom_image_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'cover-small' => __('160px by 240px'),
        'cover-medium' => __('320px by 240px'),
    ) );
}

/*
The function above adds the ability to use the dropdown menu to select
the new images sizes you have just created from within the media manager
when you add media to your content blocks. If you add more image sizes,
duplicate one of the lines in the array and name it according to your
new image size.
*/

/************* THEME CUSTOMIZE *********************/

/* 
  A good tutorial for creating your own Sections, Controls and Settings:
  http://code.tutsplus.com/series/a-guide-to-the-wordpress-theme-customizer--wp-33722
  
  Good articles on modifying the default options:
  http://natko.com/changing-default-wordpress-theme-customization-api-sections/
  http://code.tutsplus.com/tutorials/digging-into-the-theme-customizer-components--wp-27162
  
  To do:
  - Create a js for the postmessage transport method
  - Create some sanitize functions to sanitize inputs
  - Create some boilerplate Sections, Controls and Settings
*/

function bones_theme_customizer($wp_customize) {
  // $wp_customize calls go here.
  //
  // Uncomment the below lines to remove the default customize sections 

  // $wp_customize->remove_section('title_tagline');
  // $wp_customize->remove_section('colors');
  // $wp_customize->remove_section('background_image');
  // $wp_customize->remove_section('static_front_page');
  // $wp_customize->remove_section('nav');

  // Uncomment the below lines to remove the default controls
  // $wp_customize->remove_control('blogdescription');
  
  // Uncomment the following to change the default section titles
  // $wp_customize->get_section('colors')->title = __( 'Theme Colors' );
  // $wp_customize->get_section('background_image')->title = __( 'Images' );
}

add_action( 'customize_register', 'bones_theme_customizer' );

/************* ACTIVE SIDEBARS ********************/

// Sidebars & Widgetizes Areas
function bones_register_sidebars() {
	register_sidebar(array(
		'id' => 'sidebar1',
		'name' => __( 'Sidebar 1', 'bonestheme' ),
		'description' => __( 'The first (primary) sidebar.', 'bonestheme' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	));

	/*
	to add more sidebars or widgetized areas, just copy
	and edit the above sidebar code. In order to call
	your new sidebar just use the following code:

	Just change the name to whatever your new
	sidebar's id is, for example:

	register_sidebar(array(
		'id' => 'sidebar2',
		'name' => __( 'Sidebar 2', 'bonestheme' ),
		'description' => __( 'The second (secondary) sidebar.', 'bonestheme' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	));

	To call the sidebar in your template, you can just copy
	the sidebar.php file and rename it to your sidebar's name.
	So using the above example, it would be:
	sidebar-sidebar2.php

	*/
} // don't remove this bracket!


/************* COMMENT LAYOUT *********************/

// Comment Layout
function bones_comments( $comment, $args, $depth ) {
   $GLOBALS['comment'] = $comment; ?>
  <div id="comment-<?php comment_ID(); ?>" <?php comment_class('cf'); ?>>
    <article  class="cf">
      <header class="comment-author vcard">
        <?php
        /*
          this is the new responsive optimized comment image. It used the new HTML5 data-attribute to display comment gravatars on larger screens only. What this means is that on larger posts, mobile sites don't have a ton of requests for comment images. This makes load time incredibly fast! If you'd like to change it back, just replace it with the regular wordpress gravatar call:
          echo get_avatar($comment,$size='32',$default='<path_to_url>' );
        */
        ?>
        <?php // custom gravatar call ?>
        <?php
          // create variable
          $bgauthemail = get_comment_author_email();
        ?>
        <img data-gravatar="http://www.gravatar.com/avatar/<?php echo md5( $bgauthemail ); ?>?s=40" class="load-gravatar avatar avatar-48 photo" height="40" width="40" src="<?php echo get_template_directory_uri(); ?>/library/images/nothing.gif" />
        <?php // end custom gravatar call ?>
        <?php printf(__( '<cite class="fn">%1$s</cite> %2$s', 'bonestheme' ), get_comment_author_link(), edit_comment_link(__( '(Edit)', 'bonestheme' ),'  ','') ) ?>
        <time datetime="<?php echo comment_time('Y-m-j'); ?>"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php comment_time(__( 'F jS, Y', 'bonestheme' )); ?> </a></time>

      </header>
      <?php if ($comment->comment_approved == '0') : ?>
        <div class="alert alert-info">
          <p><?php _e( 'Your comment is awaiting moderation.', 'bonestheme' ) ?></p>
        </div>
      <?php endif; ?>
      <section class="comment_content cf">
        <?php comment_text() ?>
      </section>
      <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
    </article>
  <?php // </li> is added by WordPress automatically ?>
<?php
} // don't remove this bracket!


/*
This is a modification of a function found in the
twentythirteen theme where we can declare some
external fonts. If you're using Google Fonts, you
can replace these fonts, change it in your scss files
and be up and running in seconds.
*/
function bones_fonts() {
  wp_enqueue_style('googleFonts', 'https://fonts.googleapis.com/css?family=Montserrat:400,700|Fira+Sans:300,400|Merriweather:400,700,400italic,700italic');
}
add_action('wp_enqueue_scripts', 'bones_fonts');

require_once( 'cmb-functions.php' );
require_once( 'cmb2-extra-functions/cmb2_post_search_field.php' );

// shortcodes
function root_path_shortcode() {
	return get_bloginfo('url') . '/';
}
add_shortcode('root_path', 'root_path_shortcode');

function stylesheet_path_shortcode() {
	return get_stylesheet_directory_uri() . '/';
}
add_shortcode('stylesheet_path', 'stylesheet_path_shortcode');

// sorts get_posts object by title, ignoring articles like "the" or "a" at the beginning
function sort_by_title($a, $b) {
	$aTitle = removeArticle($a->post_title);
	$bTitle = removeArticle($b->post_title);
	return strcasecmp($aTitle, $bTitle);
}
function removeArticle($string) {
	$stringArray = explode(' ',trim($string));
	$firstWord = $stringArray[0];
	if (in_array(strtolower($firstWord), array('the','a','an'))) {
		array_shift($stringArray);
		return implode(' ', $stringArray);
	} else {
		return $string;
	}
}

function thumbGrid($itemObject, $title, $className) {
	echo '<div class="'.$className.' thumb-grid THUMB_GRID">';
		echo'<h2>'.$title.'</h2>';
		echo'<div class="thumb-grid-inner THUMB_GRID_INNER">';
			echo '<ul class="thumbs THUMBS">';
				foreach($itemObject as $key => $item) {
					$itemThumbArray = wp_get_attachment_image_src( get_post_thumbnail_id($item->ID), 'cover-small');
					echo '<li>';
						echo '<a href="'.get_the_permalink($item->ID).'">';
							echo '<span class="item-thumb-container">';
								echo '<img class="item-thumb" src="'.$itemThumbArray[0].'" />';
							echo '</span>';
							echo '<span class="item-text-container">';
								echo '<span class="item-title">'.$item->post_title.'</span>';
							echo '</span>';
						echo '</a>';
					echo '</li>';
				}
			echo '</ul>';
		echo '</div>';
		echo '<div class="thumb-grid-nav THUMB_GRID_NAV">';
			echo '<a href="#" class="prev PREV">Previous</a>';
			echo '<a href="#" class="next NEXT">Next</a>';
		echo '</div>';
	echo '</div>';
}

// Creating the widget 
class oilve_recommended extends WP_Widget {
	function __construct() {
		parent::__construct(
			// Base ID of your widget
			'oilve_recommended', 

			// Widget name will appear in UI
			__('Recommended Publications', 'olive_widgets'), 

			// Widget description
			array( 'description' => __( 'Widget for displaying publications from the "Recommended" category', 'olive_widgets' ), ) 
		);
	}

	// Creating widget front-end
	// This is where the action happens
	public function widget( $args, $instance ) {
		$title = empty($instance['title']) ? '' : apply_filters( 'widget_title', $instance['title'] );
		$number_of_posts = empty($instance['number_of_posts']) ? '4' : $instance['number_of_posts'];
		
		// before and after widget arguments are defined by themes
		echo $args['before_widget'];
		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
		/*
		echo '<pre>';
		print_r($instance);
		echo '</pre>';
		*/
		$pubs = get_posts(array(
			'post_type' => array('publication'),
			'numberposts' => $number_of_posts,
			'orderby' => 'rand',
			'category_name' => 'recommended'
		));
		echo '<ul>';
		foreach($pubs as $key => $item) { 
			$links = get_post_meta($item->ID, '_oliver_publications_links', true);
			?>
			<li>
				<a class="recommended-cover-img" href="<?php echo get_the_permalink($item->ID); ?>">
					<?php echo get_the_post_thumbnail($item->ID, 'cover-small'); ?>
				</a>
				<h5><a href="<?php echo get_the_permalink($item->ID); ?>"><?php echo get_the_title($item->ID); ?></a></h5>
				<?php if ($links) { ?>
					<div class="link-container-upper">
						<a href="<?php echo $links[0][url]; ?>" class="btn btn-small btn-external btn-<?php echo $links[0][css_select]; ?>" target="_blank"><?php echo $links[0][title]; ?></a>
					</div>
				<?php } ?>
			</li>
		<?php }
		echo '</ul>';
		
		echo $args['after_widget'];
	}
			
	// Widget Backend 
	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance['title'];
		} else {
			$title = __( 'Recommended Publications', 'olive_widgets' );
		}
		$number_of_posts = $instance['number_of_posts'];
		 
		// Widget admin form
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('number_of_posts'); ?>">Number of Publications to Display:</label>
			<select class='widefat' id="<?php echo $this->get_field_id('number_of_posts'); ?>" name="<?php echo $this->get_field_name('number_of_posts'); ?>" type="text">
				<option value='1'<?php echo ($number_of_posts=='1')?'selected':''; ?>>1</option>
				<option value='2'<?php echo ($number_of_posts=='2')?'selected':''; ?>>2</option> 
				<option value='3'<?php echo ($number_of_posts=='3')?'selected':''; ?>>3</option> 
				<option value='4'<?php echo ($number_of_posts=='4')?'selected':''; ?>>4</option> 
				<option value='5'<?php echo ($number_of_posts=='5')?'selected':''; ?>>5</option> 
				<option value='6'<?php echo ($number_of_posts=='6')?'selected':''; ?>>6</option> 
				<option value='7'<?php echo ($number_of_posts=='7')?'selected':''; ?>>7</option> 
				<option value='8'<?php echo ($number_of_posts=='8')?'selected':''; ?>>8</option> 
				<option value='9'<?php echo ($number_of_posts=='9')?'selected':''; ?>>9</option> 
				<option value='10'<?php echo ($number_of_posts=='10')?'selected':''; ?>>10</option> 
			</select>     
		</p>
	<?php 
}
	
	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['number_of_posts'] = $new_instance['number_of_posts'];
		return $instance;
	}
} // Class oilve_recommended ends here

// Register and load the widget
function wpb_load_widget() {
	register_widget( 'oilve_recommended' );
}
add_action( 'widgets_init', 'wpb_load_widget' );



/* DON'T DELETE THIS CLOSING TAG */ ?>
