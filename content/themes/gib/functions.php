<?php
/**
 * _s functions and definitions
 *
 * @package _s
 * @since _s 1.0
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * @since _s 1.0
 */



if ( ! isset( $content_width ) )
	$content_width = 640; /* pixels */

if ( ! function_exists( '_s_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * @since _s 1.0
 */

function _s_setup() {

    /**
     * Include function files
     */
    require_once( get_template_directory() . '/inc/gravityforms_functions.php' );
    require_once( get_template_directory() . '/inc/shortcodes.php' );
    require_once( get_template_directory() . '/inc/cpt-projects.php' );

	/**
	 * Custom template tags for this theme.
	 */

	require( get_template_directory() . '/inc/template-tags.php' );
    require( get_template_directory() . '/wp-less/wp-less.php' );

    // require( '../../plugins-mu/custom-meta-boxes.php' );


	/**
	 * Custom functions that act independently of the theme templates
	 */
	//require( get_template_directory() . '/inc/tweaks.php' );

	/**
	 * Custom Theme Options
	 */
	//require( get_template_directory() . '/inc/theme-options/theme-options.php' );

	/**
	 * WordPress.com-specific functions and definitions
	 */
	//require( get_template_directory() . '/inc/wpcom.php' );

	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on _s, use a find and replace
	 * to change '_s' to the name of your theme in all the template files
	 */
	load_theme_textdomain( '_s', get_template_directory() . '/languages' );

	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );

	/**
	 * Enable support for Post Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	/**
	 * This theme uses wp_nav_menu() in one location.
	 */
	register_nav_menus( array(
		'primary' => __( 'Top Menu', '_s' ),
		'footer' => __( 'Footer Menu', '_s' ),
		'mobile' => __( 'Mobile Menu', '_s' ),
	) );

	/**
	 * Add support for the Aside Post Formats
	 */
	add_theme_support( 'post-formats', array( 'aside', ) );

    /**
     * Add Image Size for Posts
     */

}
endif; // _s_setup
add_action( 'after_setup_theme', '_s_setup' );

/**
 * Register widgetized area and update sidebar with default widgets
 *
 * @since _s 1.0
 */
function _s_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Sidebar', '_s' ),
		'id' => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h2 class="widget-title">',
		'after_title' => '</h2>',
	) );
}
add_action( 'widgets_init', '_s_widgets_init' );

/**
 * Enqueue scripts and styles
 */
function _s_scripts() {

	global $post;

	wp_enqueue_script( 'fontscom', 'http://fast.fonts.com/jsapi/a3320f6f-ad09-44a3-8051-2599557bc0c8.js', true );
	wp_enqueue_script( 'respond', get_template_directory_uri() . '/js/respond.min.js', true );
	wp_enqueue_script( 'prettyPhoto', get_template_directory_uri() . '/js/jquery.prettyPhoto.js', array( 'jquery' ), true );
	wp_enqueue_script( 'geb', get_template_directory_uri() . '/js/geb.js', array( 'jquery' ), true );

    //waypoints for sticky bar

    if ( is_tax( 'geb_projects_cat' ) ) {
        wp_enqueue_script( 'waypoints', get_template_directory_uri() . '/js/jquery.stickyscroll.js', array( 'jquery' ), true );
    }

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image( $post->ID ) ) {
		wp_enqueue_script( 'keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20120202' );
	}

    wp_enqueue_style( 'less-style', get_template_directory_uri() . '/less/master.less', array(), 20120202 );
}
add_action( 'wp_enqueue_scripts', '_s_scripts' );


/**
 * Implement the Custom Header feature
 */


// Sibling Link

function nt_parent() {

    global $post;
    return $post->post_parent;

}

function nt_sibling_list() {

    // Initialize

    $pages = array();

    global $post;
    $parent = $post->post_parent;

    // Grab Data

    $siblings = get_pages( array (
            'child_of' => $parent,
            'parent' => $parent,
            'sort_column' => 'menu_order',
            'sort_order' => 'ASC',
            'post_type' => 'page',
            'post_status' => 'publish'
        )
    );

    foreach ($siblings as $key => $page){
        $id = $page->ID;
        $order = $page->menu_order;
        $pages[] = $id;
    }

    return $pages;

}

function nt_sibling_links($link) {

    global $post;
    $postid = $post->ID;

    $pages = nt_sibling_list();

    $position = array_search( $postid , $pages );

    if ( $link == 'before' ) {

        $prev = $pages[$position-1];

        if ( $prev != null ) {
            return '<a href="' . get_permalink($prev) . '"><span class="prev"></span>' . get_the_title($prev) . '</a>';
        } else {
            return '';
        }
    }

    if ( $link == 'after' ) {

        $next = $pages[$position+1];

        if ( $next != null ) {
            return '<a href="' . get_permalink($next) . '">' . get_the_title($next) . '<span class="next"></span></a>';
        } else {
            return '';
        }
    }
}

function hm_the_excerpt( $length, $post_id = null ) {

    if ( is_null( $post_id ) )
        $post_id = get_the_id();

    global $hm_excerpt_length_hack;

    $hm_excerpt_length_hack = $length;

    $filter = function() {

        global $hm_excerpt_length_hack;

        return (int) $hm_excerpt_length_hack;

    };

    add_filter( 'excerpt_length', $filter );

    echo apply_filters( 'get_the_excerpt', get_post_field( 'post_excerpt', $post_id ) );

    remove_filter( 'excerpt_length', $filter );

}

/**
 * Set the excerpt more character
 */
add_filter( 'excerpt_more', function( $more ) {

    return '&hellip;';

}, 999 );

// Home Slider


add_filter( 'cmb_meta_boxes', function( array $meta_boxes )  {

	// Start with an underscore to hide fields from custom fields list
	
	$prefix = 'geb_';

	$meta_boxes[] = array(
		'id'         => 'geb_home',
		'title'      => 'Homepage Sliders',
		'pages'      => array( 'page', ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_on' => array( 'key' => 'page-template', 'value' => 'page-home.php' ),
		'show_names' => true, // Show field names on the left
		'fields'     => array(
		
			array(
				'name' => 'Slide Image 1',
				'desc' => 'Upload an image or enter a URL.',
				'id'   => $prefix . 'slide_img_1',
				'type' => 'file',
			),
			array(
				'name' => 'Slide Title 1',
				'desc' => 'Title of slide',
				'id'   => $prefix . 'slide_title_1',
				'type' => 'text',
			),
			array(
				'name' => 'Slide Text 1',
				'desc' => 'Text of slide',
				'id'   => $prefix . 'slide_text_1',
				'type' => 'text',
			),
			array(
				'name' => 'Slide Button Text 1',
				'desc' => 'Button Text',
				'id'   => $prefix . 'slide_buttontext_1',
				'type' => 'text',
			),
			array(
				'name' => 'Slide Link 1',
				'desc' => 'Button Link',
				'id'   => $prefix . 'slide_link_1',
				'type' => 'text',
			),

			array(
				'name' => 'Slide Image 2',
				'desc' => 'Upload an image or enter a URL.',
				'id'   => $prefix . 'slide_img_2',
				'type' => 'file',
			),
			array(
				'name' => 'Slide Title 2',
				'desc' => 'Title of slide',
				'id'   => $prefix . 'slide_title_2',
				'type' => 'text',
			),
			array(
				'name' => 'Slide Text 2',
				'desc' => 'Text of slide',
				'id'   => $prefix . 'slide_text_2',
				'type' => 'text',
			),
			array(
				'name' => 'Slide Button Text 2',
				'desc' => 'Button Text',
				'id'   => $prefix . 'slide_buttontext_2',
				'type' => 'text',
			),
			array(
				'name' => 'Slide Link 2',
				'desc' => 'Button Link',
				'id'   => $prefix . 'slide_link_2',
				'type' => 'text',
			),

			array(
				'name' => 'Slide Image 3',
				'desc' => 'Upload an image or enter a URL.',
				'id'   => $prefix . 'slide_img_3',
				'type' => 'file',
			),
			array(
				'name' => 'Slide Title 3',
				'desc' => 'Title of slide',
				'id'   => $prefix . 'slide_title_3',
				'type' => 'text',
			),
			array(
				'name' => 'Slide Text 3',
				'desc' => 'Text of slide',
				'id'   => $prefix . 'slide_text_3',
				'type' => 'text',
			),
			array(
				'name' => 'Slide Button Text 3',
				'desc' => 'Button Text',
				'id'   => $prefix . 'slide_buttontext_3',
				'type' => 'text',
			),
			array(
				'name' => 'Slide Link 3',
				'desc' => 'Button Link',
				'id'   => $prefix . 'slide_link_3',
				'type' => 'text',
			),

			array(
				'name' => 'Slide Image 4',
				'desc' => 'Upload an image or enter a URL.',
				'id'   => $prefix . 'slide_img_4',
				'type' => 'file',
			),
			array(
				'name' => 'Slide Title 4',
				'desc' => 'Title of slide',
				'id'   => $prefix . 'slide_title_4',
				'type' => 'text',
			),
			array(
				'name' => 'Slide Text 4',
				'desc' => 'Text of slide',
				'id'   => $prefix . 'slide_text_4',
				'type' => 'text',
			),
			array(
				'name' => 'Slide Button Text 4',
				'desc' => 'Button Text',
				'id'   => $prefix . 'slide_buttontext_4',
				'type' => 'text',
			),
			array(
				'name' => 'Slide Link 4',
				'desc' => 'Button Link',
				'id'   => $prefix . 'slide_link_4',
				'type' => 'text',
			),

			array(
				'name' => 'Slide Image 5',
				'desc' => 'Upload an image or enter a URL.',
				'id'   => $prefix . 'slide_img_5',
				'type' => 'file',
			),
			array(
				'name' => 'Slide Title 5',
				'desc' => 'Title of slide',
				'id'   => $prefix . 'slide_title_5',
				'type' => 'text',
			),
			array(
				'name' => 'Slide Text 5',
				'desc' => 'Text of slide',
				'id'   => $prefix . 'slide_text_5',
				'type' => 'text',
			),
			array(
				'name' => 'Slide Button Text 5',
				'desc' => 'Button Text',
				'id'   => $prefix . 'slide_buttontext_5',
				'type' => 'text',
			),
			array(
				'name' => 'Slide Link 5',
				'desc' => 'Button Link',
				'id'   => $prefix . 'slide_link_5',
				'type' => 'text',
			),


		),
	);

	// Add other metaboxes as needed

	return $meta_boxes;
} );