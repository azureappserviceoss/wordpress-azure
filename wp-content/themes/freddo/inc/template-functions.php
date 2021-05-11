<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package freddo
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function freddo_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}
	
	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}
	
	// Adds a class if the hero image exists
	if (is_home() && !is_front_page() ) {
		$pageID = get_option('page_for_posts');
		if ('' != get_the_post_thumbnail($pageID)) {
			$classes[] = 'freddoFeatImage';
		}
	}
	if (is_singular(array( 'post', 'page' )) && '' != get_the_post_thumbnail() && !is_page_template('template-onepage.php') ) {
		$classes[] = 'freddoFeatImage';
	}

	return $classes;
}
add_filter( 'body_class', 'freddo_body_classes' );

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function freddo_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'freddo_pingback_header' );

/* Output of breadcrumb */
function freddo_the_breadcrumb() {
	if ( function_exists('yoast_breadcrumb') && freddo_check_for_breadcrumb() ) {
		yoast_breadcrumb( '<p id="breadcrumbs" class="freddo-breadcrumbs smallText">','</p>' );
	}
	if (function_exists('rank_math_the_breadcrumbs') && freddo_check_for_breadcrumb() ) {
		rank_math_the_breadcrumbs();
	}
}

/* Check for the breadcrumb */
function freddo_check_for_breadcrumb() {
	if (is_page_template('template-onepage.php')) {
		return false;
	}
	return apply_filters( 'freddo_the_breadcrumb_filter', true );
}
