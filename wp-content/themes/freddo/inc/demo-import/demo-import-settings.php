<?php
add_filter( 'pt-ocdi/disable_pt_branding', '__return_true' );
if ( ! function_exists( 'freddo_ocdi_import_files' ) ) :
	function freddo_ocdi_import_files() {
	  return array(
		array(
		  'import_file_name'             => esc_html__( 'Freddo Demo Content', 'freddo' ),
		  'import_file_url'            => 'https://crestaproject.com/demo-import/freddo-content-demo.xml',
		  'import_customizer_file_url' => 'https://crestaproject.com/demo-import/freddo-customizer-demo.dat',
		  'import_preview_image_url'     => trailingslashit( get_template_directory() ) . 'screenshot.png',
		  'import_notice'                => esc_html__( 'We recommend importing the demo content to new websites without content. This is to avoid filling the website with new pages and posts that could create confusion with existing content. After you import the demo, the main menu, widgets, and some parts of the theme (like the site logo) must be set manually.', 'freddo' ),
		  'preview_url'                  => 'https://crestaproject.com/demo/freddo/',
		),
	  );
	}
	add_filter( 'pt-ocdi/import_files', 'freddo_ocdi_import_files' );
endif;

if ( ! function_exists( 'freddo_ocdi_after_import_setup' ) ) :
	function freddo_ocdi_after_import_setup() {
		// Assign menus to their locations.
		$main_menu = get_term_by( 'name', 'Primary', 'nav_menu' );
		set_theme_mod( 'nav_menu_locations', array(
				'primary' => $main_menu->term_id,
			)
		);
		// Assign front page and posts page (blog page).
		$front_page_id = get_page_by_title( 'Home' );
		$blog_page_id  = get_page_by_title( 'Blog' );
		// Assign block pages for the onepage template.
		$aboutus_page_id = get_page_by_title( 'Maecenas a auctor ante.' );
		$features1 = get_page_by_title( 'Features 1' );
		$features2 = get_page_by_title( 'Features 2' );
		$features3 = get_page_by_title( 'Features 3' );
		$services1 = get_page_by_title( 'Service 1' );
		$services2 = get_page_by_title( 'Service 2' );
		$services3 = get_page_by_title( 'Service 3' );
		$services4 = get_page_by_title( 'Service 4' );
		$team1 = get_page_by_title( 'Amanda Sparks' );
		$team2 = get_page_by_title( 'Jonathan Futral' );
		$team3 = get_page_by_title( 'Arcelia Riggs' );
		$team4 = get_page_by_title( 'Mario Dehart' );

		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $front_page_id->ID );
		update_option( 'page_for_posts', $blog_page_id->ID );
		$options = get_option('freddo_theme_options');
		$options['_onepage_choosepage_aboutus'] = $aboutus_page_id->ID;
		$options['_onepage_choosepage_1_features'] = $features1->ID;
		$options['_onepage_choosepage_2_features'] = $features2->ID;
		$options['_onepage_choosepage_3_features'] = $features3->ID;
		$options['_onepage_choosepage_1_services'] = $services1->ID;
		$options['_onepage_choosepage_2_services'] = $services2->ID;
		$options['_onepage_choosepage_3_services'] = $services3->ID;
		$options['_onepage_choosepage_4_services'] = $services4->ID;
		$options['_onepage_choosepage_1_team'] = $team1->ID;
		$options['_onepage_choosepage_2_team'] = $team2->ID;
		$options['_onepage_choosepage_3_team'] = $team3->ID;
		$options['_onepage_choosepage_4_team'] = $team4->ID;
		$options['_onepage_image_1_slider'] = get_template_directory_uri() . '/images/example/freddo_slider_example_1.jpg';
		$options['_onepage_image_2_slider'] = get_template_directory_uri() . '/images/example/freddo_slider_example_2.jpg';
		$options['_onepage_imgback_cta'] = freddo_get_upload_dir_var('baseurl') . '/2017/11/cta-example.jpg';
		$options['_onepage_servimage_services'] = freddo_get_upload_dir_var('baseurl') . '/2017/11/service-example.jpg';
		update_option('freddo_theme_options', $options);
	}
	add_action( 'pt-ocdi/after_import', 'freddo_ocdi_after_import_setup' );
endif;

/* Fix for wp_upload_dir() to return https if the website is SSL */
if ( ! function_exists( 'freddo_get_upload_dir_var' ) ) :
	function freddo_get_upload_dir_var( $param, $subfolder = '' ) {
		$upload_dir = wp_upload_dir();
		$url = $upload_dir[ $param ];
		if ( $param === 'baseurl' && is_ssl() ) {
			$url = str_replace( 'http://', 'https://', $url );
		}
		return $url . $subfolder;
	}
endif;