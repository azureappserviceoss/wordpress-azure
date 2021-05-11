<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package freddo
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php 
if ( function_exists( 'wp_body_open' ) ) {
    wp_body_open();
} else {
    do_action( 'wp_body_open' );
}
?>
<?php if(freddo_options('_show_loader', '0') == 1 ) : ?>
	<div class="freddoLoader">
		<?php freddo_loadingPage(); ?>
	</div>
<?php endif; ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'freddo' ); ?></a>
	<?php if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'header' ) ) : ?>
		<?php $menuFixedMobile = freddo_options('_menu_fixed_mobile', ''); ?>
		<header id="masthead" class="site-header <?php echo $menuFixedMobile ? 'yesMobileFixed' : 'noMobileFixed' ?>">
			

			<div class="mainHeader">
				<div class="mainLogo">
					<div class="freddoSubHeader title">
						<div class="site-branding">
							<?php
							if ( function_exists( 'the_custom_logo' ) ) : ?>
							<div class="freddoLogo" itemscope itemtype="http://schema.org/Organization">
								<?php the_custom_logo(); ?>
							<?php endif; ?>
							<div class="freddoTitleText">
								<?php if ( is_front_page() && is_home() || is_page_template('template-onepage.php') ) : ?>
									<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
								<?php else : ?>
									<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
								<?php
								endif;

								$freddo_description = get_bloginfo( 'description', 'display' );
								if ( $freddo_description || is_customize_preview() ) : ?>
									<p class="site-description"><?php echo $freddo_description; /* // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped */ ?></p>
								<?php
								endif; ?>
							</div>
							</div>
						</div><!-- .site-branding -->
					</div>
				</div>
				<?php if ( is_active_sidebar( 'sidebar-push' ) ) : ?>
					<div class="hamburger-menu">
						<div class="hamburger-box">
							<div class="hamburger-inner"></div>
						</div>
					</div>
				<?php endif; ?>
				<?php $showSearchButton = freddo_options('_search_button', '1');
				if ($showSearchButton) : ?>
				<div class="search-button">
					<div class="search-circle"></div>
					<div class="search-line"></div>
				</div>
				<?php endif; ?>
				<div class="freddoHeader">
					<div class="freddoSubHeader">
						<nav id="site-navigation" class="main-navigation">
							<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false" aria-label="<?php esc_attr_e( 'Menu', 'freddo' ); ?>"><i class="fa fa-lg fa-bars" aria-hidden="true"></i></button>
							<?php
								wp_nav_menu( array(
									'theme_location' => 'menu-1',
									'menu_id'        => 'primary-menu',
								) );
							?>
						</nav><!-- #site-navigation -->
					</div>
				</div>
			</div>
		</header><!-- #masthead -->
	<?php endif; ?>
	<?php if (is_home() && !is_front_page() ) : ?>
		<?php
			$pageID = get_option('page_for_posts');
			if ('' != get_the_post_thumbnail($pageID)) : 
			$effectFeatImage = freddo_options('_effect_featimage', 'withZoom');
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( $pageID ), 'freddo-the-post-big' );
		?>
			<div class="freddoBox">
				<div class="freddoBigImage <?php echo esc_attr($effectFeatImage); ?>" style="background-image: url(<?php echo esc_url($image[0]); ?>);">
					<div class="freddoImageOp">
					</div>
				</div>
			</div>
		<?php endif; ?>
	<?php endif; ?>
	<?php if (is_singular(array( 'post', 'page' )) && '' != get_the_post_thumbnail() && !is_page_template('template-onepage.php') ) : ?>
		<?php while ( have_posts() ) : 
		the_post(); ?>
		<?php 
			$src = wp_get_attachment_image_src( get_post_thumbnail_id(), 'freddo-the-post-big');
			$showScrollDownButton = freddo_options('_scrolldown_button', '1');
			$effectFeatImage = freddo_options('_effect_featimage', 'withZoom');
		?>
		<div class="freddoBox">
			<div class="freddoBigImage <?php echo esc_attr($effectFeatImage); ?>" style="background-image: url(<?php echo esc_url($src[0]); ?>);">
				<div class="freddoImageOp">
				</div>
			</div>
			<div class="freddoBigText">
				<header class="entry-header">
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
					<?php if ( 'post' === get_post_type() ) : ?>
						<div class="entry-meta">
							<?php freddo_posted_on(); ?>
						</div><!-- .entry-meta -->
					<?php endif; ?>
					<?php if ($showScrollDownButton) : ?>
						<?php $scrollText = freddo_options('_post_scrolldown_text', __('Scroll Down', 'freddo')); ?>
						<div class="scrollDown"><span><?php echo esc_html($scrollText); ?></span></div>
					<?php endif; ?>
				</header><!-- .entry-header -->
			</div>
		</div>
		<?php endwhile; ?>
	<?php endif; ?>

	<?php 
	$showInFloat = freddo_options('_social_float', '');
	if ($showInFloat == 1) {
		freddo_show_social_network('float');
	} ?>
	
	<div id="content" class="site-content">
	<?php freddo_the_breadcrumb(); ?>
	<div class="freddo-inner">
