<?php
/**
 * freddo functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package freddo
 */

if ( ! function_exists( 'freddo_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function freddo_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on freddo, use a find and replace
		 * to change 'freddo' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'freddo', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );
		
		// Add support for responsive embedded content.
		add_theme_support( 'responsive-embeds' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );
		add_image_size( 'freddo-the-post-small' , 980, 600, true);
		add_image_size( 'freddo-the-post-big' , 1920, 99999);
		add_image_size( 'freddo-little-post' , 370, 220, true);

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'menu-1' => esc_html__( 'Primary', 'freddo' ),
			'footer' => esc_html__( 'Footer', 'freddo' ),
		) );
		
		// Adds support for editor font sizes.
		add_theme_support( 'editor-font-sizes', array(
			array(
				'name'      => __( 'Small', 'freddo' ),
				'shortName' => __( 'S', 'freddo' ),
				'size'      => 12,
				'slug'      => 'small'
			),
			array(
				'name'      => __( 'Regular', 'freddo' ),
				'shortName' => __( 'M', 'freddo' ),
				'size'      => 14,
				'slug'      => 'regular'
			),
			array(
				'name'      => __( 'Large', 'freddo' ),
				'shortName' => __( 'L', 'freddo' ),
				'size'      => 18,
				'slug'      => 'large'
			),
			array(
				'name'      => __( 'Larger', 'freddo' ),
				'shortName' => __( 'XL', 'freddo' ),
				'size'      => 22,
				'slug'      => 'larger'
			)
		) );
		
		/* Support for wide images on Gutenberg */
		add_theme_support( 'align-wide' );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'script',
			'style',
		) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 60,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
			'header-text' => array( 'site-title', 'site-description' ),
		) );
		
		/*
	 * Starter Content Support
	 */
	add_theme_support( 'starter-content', array(
		'posts' => array(
			'home' => array(
				'template' => 'template-onepage.php',
			),
			'blog',
		),
		'options' => array(
			'show_on_front'  => 'page',
			'page_on_front'  => '{{home}}',
			'page_for_posts' => '{{blog}}',
			'freddo_theme_options[_onepage_section_slider]' => '1',
			'freddo_theme_options[_onepage_image_1_slider]' => get_template_directory_uri().'/images/example/freddo_slider_example_1.jpg',
			'freddo_theme_options[_onepage_image_2_slider]' => get_template_directory_uri().'/images/example/freddo_slider_example_2.jpg',
			'freddo_theme_options[_onepage_text_1_slider]' => 'Welcome to Freddo Theme',
			'freddo_theme_options[_onepage_subtext_1_slider]' => 'Use the customizer to customize the onepage sections',
			'freddo_theme_options[_onepage_text_2_slider]' => 'Read the documentation',
			'freddo_theme_options[_onepage_subtext_2_slider]' => 'You can find the documentation in "Appearance-> About Freddo-> Documentation"',
			'freddo_theme_options[_onepage_section_skills]' => '1',
			'freddo_theme_options[_onepage_skillname_1_skills]' => 'Design',
			'freddo_theme_options[_onepage_skillvalue_1_skills]' => '84',
			'freddo_theme_options[_onepage_skillname_2_skills]' => 'WordPress',
			'freddo_theme_options[_onepage_skillvalue_2_skills]' => '93',
			'freddo_theme_options[_onepage_skillname_3_skills]' => 'SEO',
			'freddo_theme_options[_onepage_skillvalue_3_skills]' => '76',
			'freddo_theme_options[_onepage_skillname_4_skills]' => 'Support',
			'freddo_theme_options[_onepage_skillvalue_4_skills]' => '90',
			'freddo_theme_options[_onepage_skillname_5_skills]' => 'Customization',
			'freddo_theme_options[_onepage_skillvalue_5_skills]' => '89',
			'freddo_theme_options[_onepage_skillname_6_skills]' => 'Updates',
			'freddo_theme_options[_onepage_skillvalue_6_skills]' => '87',
			'freddo_theme_options[_onepage_section_cta]' => '1',
			'freddo_theme_options[_onepage_phrase_cta]' => 'Do you want more?',
			'freddo_theme_options[_onepage_desc_cta]' => 'Take a look at Freddo PRO version...',
			'freddo_theme_options[_onepage_textbutton_cta]' => 'PRO Version',
			'freddo_theme_options[_onepage_urlbutton_cta]' => 'https://crestaproject.com/demo/freddo-pro/',
		),
		'nav_menus' => array(
			'primary' => array(
				'name' => __( 'Primary', 'freddo' ),
				'items' => array(
					'link_home',
					'page_blog',
				),
			),
		),
	) );
	}
endif;
add_action( 'after_setup_theme', 'freddo_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function freddo_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'freddo_content_width', 640 );
}
add_action( 'after_setup_theme', 'freddo_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function freddo_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Classic Sidebar', 'freddo' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'freddo' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<div class="widget-title"><h3>',
		'after_title'   => '</h3></div>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Push Sidebar', 'freddo' ),
		'id'            => 'sidebar-push',
		'description'   => esc_html__( 'Add widgets here.', 'freddo' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<div class="widget-title"><h3>',
		'after_title'   => '</h3></div>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Footer Left', 'freddo' ),
		'id'            => 'footer-1',
		'description'   => esc_html__( 'Add widgets here.', 'freddo' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<div class="widget-title"><h3>',
		'after_title'   => '</h3></div>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Footer Center', 'freddo' ),
		'id'            => 'footer-2',
		'description'   => esc_html__( 'Add widgets here.', 'freddo' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<div class="widget-title"><h3>',
		'after_title'   => '</h3></div>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Footer Right', 'freddo' ),
		'id'            => 'footer-3',
		'description'   => esc_html__( 'Add widgets here.', 'freddo' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<div class="widget-title"><h3>',
		'after_title'   => '</h3></div>',
	) );
}
add_action( 'widgets_init', 'freddo_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function freddo_scripts() {
	wp_enqueue_style( 'freddo-style', get_stylesheet_uri(), array(), wp_get_theme()->get('Version') );
	wp_enqueue_style( 'font-awesome', get_template_directory_uri() .'/css/font-awesome.min.css', array(), '4.7.0');
	if (is_page_template('template-onepage.php')) {
		wp_enqueue_style( 'freddo-onepage', get_template_directory_uri() .'/css/onepage.min.css', array(), wp_get_theme()->get('Version'));
	}
	if ( class_exists( 'WooCommerce' ) ) {
		wp_enqueue_style( 'freddo-woocommerce', get_template_directory_uri() .'/css/woocommerce.min.css', array(), wp_get_theme()->get('Version'));
	}
	wp_enqueue_style( 'freddo-ie', get_template_directory_uri() . '/css/ie.css', array(), wp_get_theme()->get('Version'), null );
	wp_style_add_data( 'freddo-ie', 'conditional', 'IE' );
	$query_args = array(
		'family' => 'Poppins:400,700%7CMontserrat:400,700',
		'display' => 'swap'
	);
	wp_enqueue_style( 'freddo-googlefonts', add_query_arg( $query_args, "//fonts.googleapis.com/css" ), array(), null );

	wp_enqueue_script( 'freddo-navigation', get_template_directory_uri() . '/js/navigation.min.js', array(), '20151215', true );
	wp_enqueue_script( 'freddo-custom', get_template_directory_uri() . '/js/jquery.freddo.min.js', array('jquery'), wp_get_theme()->get('Version'), true );
	if ( freddo_options('_smooth_scroll', '1') == 1) {
		wp_enqueue_script( 'freddo-smooth-scroll', get_template_directory_uri() . '/js/SmoothScroll.min.js', array('jquery'), '1.4.9', true );
	}
	if (is_page_template('template-onepage.php') && freddo_options('_onepage_section_slider', '') == 1) {
		wp_enqueue_script( 'freddo-flex-slider', get_template_directory_uri() . '/js/jquery.flexslider-min.js', array(), '2.7.2', true );
	}
	if (is_page_template('template-onepage.php')) {
		wp_enqueue_script( 'freddo-waypoints', get_template_directory_uri() . '/js/jquery.waypoints.min.js', array('jquery'), '4.0.1', true );
	}

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	
	/* Dequeue default WooCommerce Layout */
	wp_dequeue_style ( 'woocommerce-layout' );
	wp_dequeue_style ( 'woocommerce-smallscreen' );
	wp_dequeue_style ( 'woocommerce-general' );
}
add_action( 'wp_enqueue_scripts', 'freddo_scripts' );

function freddo_gutenberg_scripts() {
	wp_enqueue_style( 'freddo-gutenberg-css', get_theme_file_uri( '/css/gutenberg-editor-style.css' ), array(), wp_get_theme()->get('Version') );
}
add_action( 'enqueue_block_editor_assets', 'freddo_gutenberg_scripts' );

/**
 * Register all Elementor locations
 */
function freddo_register_elementor_locations( $elementor_theme_manager ) {
	$elementor_theme_manager->register_all_core_location();
}
add_action( 'elementor/theme/register_locations', 'freddo_register_elementor_locations' );

/**
 * Define constants
 */
if ( ! function_exists( 'freddo_define_constants' ) ) :
	function freddo_define_constants() {
		/*
		 * Define the number of items in some onepage sections (real value plus 1)
		 */
		define( 'FREDDO_VALUE_FOR_SLIDER', '4' );
		define( 'FREDDO_VALUE_FOR_FEATURES', '5' );
		define( 'FREDDO_VALUE_FOR_SKILLS', '7' );
		define( 'FREDDO_VALUE_FOR_SERVICES', '7' );
		define( 'FREDDO_VALUE_FOR_TEAM', '7' );
	}
	add_action( 'after_setup_theme', 'freddo_define_constants' );
endif;

/**
 * WooCommerce Support
 */
if ( ! function_exists( 'freddo_woocommerce_support' ) ) :
	function freddo_woocommerce_support() {
		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-lightbox' );
	}
	add_action( 'after_setup_theme', 'freddo_woocommerce_support' );
endif; // freddo_woocommerce_support

/**
 * WooCommerce: Chenge default max number of related products
 */
if ( function_exists( 'is_woocommerce' ) ) :
	if ( ! function_exists( 'freddo_related_products_args' ) ) :
		add_filter( 'woocommerce_output_related_products_args', 'freddo_related_products_args' );
		function freddo_related_products_args( $args ) {
			$args['posts_per_page'] = 3;
			return $args;
		}
	endif;
endif;

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Load PRO Button in the customizer
 */
require get_template_directory() . '/inc/pro-button/class-customize.php';

/* Calling in the admin area for the Welcome Page */
if ( is_admin() ) {
	require get_template_directory() . '/inc/admin/freddo-admin-page.php';
}

/**
 * Settings for demo content import
 */
require get_template_directory() . '/inc/demo-import/demo-import-settings.php';

$local_install = $_SERVER['HTTP_HOST'] . '/wordpress-azure';

if ($local_install == 'localhost/wordpress-azure') {
    add_filter('https_ssl_verify', '__return_false');
    set_time_limit(600); // 10 min
}