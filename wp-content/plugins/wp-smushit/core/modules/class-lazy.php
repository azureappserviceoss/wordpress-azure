<?php
/**
 * Lazy load images class: Lazy
 *
 * @since 3.2.0
 * @package Smush\Core\Modules
 */

namespace Smush\Core\Modules;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Lazy
 */
class Lazy extends Abstract_Module {

	/**
	 * Lazy loading settings.
	 *
	 * @since 3.2.0
	 * @var array $settings
	 */
	private $options;

	/**
	 * Page parser.
	 *
	 * @since 3.2.2
	 * @var Helpers\Parser $parser
	 */
	protected $parser;

	/**
	 * Excluded classes list.
	 *
	 * @since 3.6.2
	 * @var array
	 */
	private $excluded_classes = array(
		'no-lazyload', // Internal class to skip images.
		'skip-lazy',
		'rev-slidebg', // Skip Revolution slider images.
		'soliloquy-preload', // Soliloquy slider.
	);

	/**
	 * Lazy constructor.
	 *
	 * @since 3.2.2
	 * @param Helpers\Parser $parser  Page parser instance.
	 */
	public function __construct( Helpers\Parser $parser ) {
		$this->parser = $parser;
		parent::__construct();
	}

	/**
	 * Initialize module actions.
	 *
	 * @since 3.2.0
	 */
	public function init() {
		// Only run on front end and if lazy loading is enabled.
		if ( is_admin() || ! $this->settings->get( 'lazy_load' ) ) {
			return;
		}

		$this->options = $this->settings->get_setting( 'wp-smush-lazy_load' );

		// Enabled without settings? Don't think so... Exit.
		if ( ! $this->options ) {
			return;
		}

		// Disable WordPress native lazy load.
		add_filter( 'wp_lazy_loading_enabled', '__return_false' );

		// Load js file that is required in public facing pages.
		add_action( 'wp_head', array( $this, 'add_inline_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ), 99 );
		if ( defined( 'WP_SMUSH_ASYNC_LAZY' ) && WP_SMUSH_ASYNC_LAZY ) {
			add_filter( 'script_loader_tag', array( $this, 'async_load' ), 10, 2 );
		}

		// Allow lazy load attributes in img tag.
		add_filter( 'wp_kses_allowed_html', array( $this, 'add_lazy_load_attributes' ) );

		$this->parser->enable( 'lazy_load' );
		if ( isset( $this->options['format']['iframe'] ) && $this->options['format']['iframe'] ) {
			$this->parser->enable( 'iframes' );
		}

		add_filter( 'wp_smush_should_skip_parse', array( $this, 'maybe_skip_parse' ) );

		// Filter images.
		if ( ! isset( $this->options['output']['content'] ) || ! $this->options['output']['content'] ) {
			add_filter( 'the_content', array( $this, 'exclude_from_lazy_loading' ), 100 );
		}
		if ( ! isset( $this->options['output']['thumbnails'] ) || ! $this->options['output']['thumbnails'] ) {
			add_filter( 'post_thumbnail_html', array( $this, 'exclude_from_lazy_loading' ), 100 );
		}
		if ( ! isset( $this->options['output']['gravatars'] ) || ! $this->options['output']['gravatars'] ) {
			add_filter( 'get_avatar', array( $this, 'exclude_from_lazy_loading' ), 100 );
		}
		if ( ! isset( $this->options['output']['widgets'] ) || ! $this->options['output']['widgets'] ) {
			add_action( 'dynamic_sidebar_before', array( $this, 'filter_sidebar_content_start' ), 0 );
			add_action( 'dynamic_sidebar_after', array( $this, 'filter_sidebar_content_end' ), 1000 );
		}
	}

	/**
	 * Add inline styles at the top of the page for pre-loaders and effects.
	 *
	 * @since 3.2.0
	 */
	public function add_inline_styles() {
		if ( $this->is_amp() ) {
			return;
		}
		// Fix for poorly coded themes that do not remove the no-js in the HTML class.
		?>
		<script>
			document.documentElement.className = document.documentElement.className.replace( 'no-js', 'js' );
		</script>
		<?php
		if ( ! $this->options['animation']['selected'] || 'none' === $this->options['animation']['selected'] ) {
			return;
		}

		// Spinner.
		if ( 'spinner' === $this->options['animation']['selected'] ) {
			$loader = WP_SMUSH_URL . 'app/assets/images/smush-lazyloader-' . $this->options['animation']['spinner']['selected'] . '.gif';
			if ( isset( $this->options['animation']['spinner']['selected'] ) && 5 < (int) $this->options['animation']['spinner']['selected'] ) {
				$loader = wp_get_attachment_image_src( $this->options['animation']['spinner']['selected'], 'full' );
				$loader = $loader[0];
			}
			$background = 'rgba(255, 255, 255, 0)';
		} else {
			// Placeholder.
			$loader     = WP_SMUSH_URL . 'app/assets/images/smush-placeholder.png';
			$background = '#FAFAFA';
			if ( isset( $this->options['animation']['placeholder']['selected'] ) && 2 === (int) $this->options['animation']['placeholder']['selected'] ) {
				$background = '#333333';
			}
			if ( isset( $this->options['animation']['placeholder']['selected'] ) && 2 < (int) $this->options['animation']['placeholder']['selected'] ) {
				$loader = wp_get_attachment_image_src( (int) $this->options['animation']['placeholder']['selected'], 'full' );

				// Can't find a loader on multisite? Try main site.
				if ( ! $loader && is_multisite() ) {
					switch_to_blog( 1 );
					$loader = wp_get_attachment_image_src( (int) $this->options['animation']['placeholder']['selected'], 'full' );
					restore_current_blog();
				}

				$loader = $loader[0];
			}
			if ( isset( $this->options['animation']['placeholder']['color'] ) ) {
				$background = $this->options['animation']['placeholder']['color'];
			}
		}

		// Fade in.
		$fadein = isset( $this->options['animation']['fadein']['duration'] ) ? $this->options['animation']['fadein']['duration'] : 0;
		$delay  = isset( $this->options['animation']['fadein']['delay'] ) ? $this->options['animation']['fadein']['delay'] : 0;
		?>
		<style>
			.no-js img.lazyload { display: none; }
			figure.wp-block-image img.lazyloading { min-width: 150px; }
			<?php if ( 'fadein' === $this->options['animation']['selected'] ) : ?>
				.lazyload, .lazyloading { opacity: 0; }
				.lazyloaded {
					opacity: 1;
					transition: opacity <?php echo esc_html( $fadein ); ?>ms;
					transition-delay: <?php echo esc_html( $delay ); ?>ms;
				}
			<?php else : ?>
				.lazyload { opacity: 0; }
				.lazyloading {
					border: 0 !important;
					opacity: 1;
					background: <?php echo esc_attr( $background ); ?> url('<?php echo esc_url( $loader ); ?>') no-repeat center !important;
					background-size: 16px auto !important;
					min-width: 16px;
				}
			<?php endif; ?>
		</style>
		<?php
	}

	/**
	 * Enqueue JS files required in public pages.
	 *
	 * @since 3.2.0
	 */
	public function enqueue_assets() {
		if ( $this->is_amp() ) {
			return;
		}

		$script = WP_SMUSH_URL . 'app/assets/js/smush-lazy-load.min.js';

		// Native lazy loading support.
		if ( isset( $this->options['native'] ) && $this->options['native'] ) {
			$script = WP_SMUSH_URL . 'app/assets/js/smush-lazy-load-native.min.js';
		}

		$in_footer = isset( $this->options['footer'] ) ? $this->options['footer'] : true;

		wp_enqueue_script(
			'smush-lazy-load',
			$script,
			array(),
			WP_SMUSH_VERSION,
			$in_footer
		);

		$this->add_masonry_support();
		if ( defined( 'WP_SMUSH_LAZY_LOAD_AVADA' ) && WP_SMUSH_LAZY_LOAD_AVADA ) {
			$this->add_avada_support();
		}
		$this->add_divi_support();
		$this->add_soliloquy_support();
	}

	/**
	 * Async load the lazy load scripts.
	 *
	 * @since 3.7.0
	 *
	 * @param string $tag     The <script> tag for the enqueued script.
	 * @param string $handle  The script's registered handle.
	 *
	 * @return string
	 */
	public function async_load( $tag, $handle ) {
		if ( 'smush-lazy-load' === $handle ) {
			return str_replace( ' src', ' async="async" src', $tag );
		}

		return $tag;
	}

	/**
	 * Add support for plugins that use the masonry grid system (Block Gallery and CoBlocks plugins).
	 *
	 * @since 3.5.0
	 *
	 * @see https://wordpress.org/plugins/coblocks/
	 * @see https://github.com/godaddy/block-gallery
	 * @see https://masonry.desandro.com/methods.html#layout-masonry
	 */
	private function add_masonry_support() {
		if ( ! function_exists( 'has_block' ) ) {
			return;
		}

		// None of the supported blocks are active - exit.
		if ( ! has_block( 'blockgallery/masonry' ) && ! has_block( 'coblocks/gallery-masonry' ) ) {
			return;
		}

		$js = "var e = jQuery( '.wp-block-coblocks-gallery-masonry ul' );";
		if ( has_block( 'blockgallery/masonry' ) ) {
			$js = "var e = jQuery( '.wp-block-blockgallery-masonry ul' );";
		}

		$block_gallery_compat = "jQuery(document).on('lazyloaded', function(){{$js} if ('function' === typeof e.masonry) e.masonry();});";

		wp_add_inline_script( 'smush-lazy-load', $block_gallery_compat );
	}

	/**
	 * Add fusion gallery support in Avada theme.
	 *
	 * @since 3.7.0
	 */
	private function add_avada_support() {
		if ( ! defined( 'FUSION_BUILDER_VERSION' ) ) {
			return;
		}

		$js = "var e = jQuery( '.fusion-gallery' );";

		$block_gallery_compat = "jQuery(document).on('lazyloaded', function(){{$js} if ('function' === typeof e.isotope) e.isotope();});";

		wp_add_inline_script( 'smush-lazy-load', $block_gallery_compat );
	}

	/**
	 * Adds lazyload support to Divi & it's Waypoint library.
	 *
	 * @since 3.9.0
	 */
	private function add_divi_support() {
		if ( ! defined( 'ET_BUILDER_THEME' ) || ! ET_BUILDER_THEME ) {
			return;
		}

		$script = "function rw() { Waypoint.refreshAll(); } window.addEventListener( 'lazybeforeunveil', rw, false); window.addEventListener( 'lazyloaded', rw, false);";

		wp_add_inline_script( 'smush-lazy-load', $script );
	}

	/**
	 * Prevents the navigation from being missaligned in Soliloquy when lazy loading.
	 *
	 * @since 3.7.0
	 */
	private function add_soliloquy_support() {
		if ( ! function_exists( 'soliloquy' ) ) {
			return;
		}

		$js = "var e = jQuery( '.soliloquy-image:not(.lazyloaded)' );";

		$soliloquy = "jQuery(document).on('lazybeforeunveil', function(){{$js}e.each(function(){lazySizes.loader.unveil(this);});});";

		wp_add_inline_script( 'smush-lazy-load', $soliloquy );
	}

	/**
	 * Make sure WordPress does not filter out img elements with lazy load attributes.
	 *
	 * @since 3.2.0
	 *
	 * @param array $allowedposttags  Allowed post tags.
	 *
	 * @return mixed
	 */
	public function add_lazy_load_attributes( $allowedposttags ) {
		if ( ! isset( $allowedposttags['img'] ) ) {
			return $allowedposttags;
		}

		$smush_attributes = array(
			'data-src'    => true,
			'data-srcset' => true,
			'data-sizes'  => true,
		);

		$img_attributes = array_merge( $allowedposttags['img'], $smush_attributes );

		$allowedposttags['img'] = $img_attributes;

		return $allowedposttags;
	}

	/**
	 * Check if we need to skip parsing of this page.
	 *
	 * @since 3.2.2
	 * @param bool $skip  Skip parsing.
	 *
	 * @return bool
	 */
	public function maybe_skip_parse( $skip ) {
		// Don't lazy load for feeds, previews, embeds.
		if ( is_feed() || is_preview() || is_embed() ) {
			$skip = true;
		}

		if ( $this->skip_post_type() || $this->is_exluded_uri() ) {
			$skip = true;
		}

		return $skip;
	}

	/**
	 * Parse image for Lazy load.
	 *
	 * @since 3.2.2
	 *
	 * @param string $src    Image URL.
	 * @param string $image  Image tag (<img>).
	 * @param string $type   Element type. Accepts: 'img', 'source' or 'iframe'. Default: 'img'.
	 *
	 * @return string
	 */
	public function parse_image( $src, $image, $type = 'img' ) {
		if ( $this->is_amp() ) {
			return $image;
		}

		/**
		 * Filter to skip a single image from lazy load.
		 *
		 * @since 3.3.0 Added $image param.
		 *
		 * @param bool   $skip   Should skip? Default: false.
		 * @param string $src    Image url.
		 * @param string $image  Image.
		 */
		if ( apply_filters( 'smush_skip_image_from_lazy_load', false, $src, $image ) ) {
			return $image;
		}

		$is_gravatar = false !== strpos( $src, 'gravatar.com' );

		$path = wp_parse_url( $src, PHP_URL_PATH );
		$ext  = strtolower( pathinfo( $path, PATHINFO_EXTENSION ) );
		$ext  = 'jpg' === $ext ? 'jpeg' : $ext;

		// If not a supported image in src or not an iframe - skip.
		$iframe = 'iframe' === substr( $image, 1, 6 );
		if ( ! $is_gravatar && ! in_array( $ext, array( 'jpeg', 'gif', 'png', 'svg', 'webp' ), true ) && ! $iframe ) {
			return $image;
		}

		// Check if some image formats are excluded.
		if ( in_array( false, $this->options['format'], true ) && isset( $this->options['format'][ $ext ] ) && ! $this->options['format'][ $ext ] ) {
			return $image;
		}

		// Check if iframes are excluded.
		if ( $iframe && isset( $this->options['format']['iframe'] ) && ! $this->options['format']['iframe'] ) {
			return $image;
		}

		/**
		 * Filter to skip a iframe from lazy load.
		 *
		 * @since 3.4.2
		 * @since 3.7.0  Added filtering by empty source. Better approach to make the get_images_from_content() work
		 *               by finding all the images (even escaped). But it does what it does.
		 *
		 * @param bool   $skip  Should skip? Default: false.
		 * @param string $src   Iframe url.
		 */
		if ( empty( $src ) || ( $iframe && apply_filters( 'smush_skip_iframe_from_lazy_load', false, $src ) ) ) {
			return $image;
		}

		// Check if the iframe URL is valid if not skip it from lazy load.
		if ( $iframe && esc_url_raw( $src ) !== $src ) {
			return $image;
		}

		if ( $this->has_excluded_class_or_id( $image ) ) {
			return $image;
		}

		// Check for the data-skip-lazy attribute.
		if ( false !== strpos( $image, 'data-skip-lazy' ) ) {
			return $image;
		}

		$new_image = $image;

		/**
		 * The sizes attribute does not have to be replaced to data-sizes, but it fixes the W3C validation.
		 *
		 * @since 3.6.2
		 */
		$attributes = array( 'src', 'sizes' );
		foreach ( $attributes as $attribute ) {
			$attr = Helpers\Parser::get_attribute( $new_image, $attribute );
			if ( $attr ) {
				Helpers\Parser::remove_attribute( $new_image, $attribute );
				Helpers\Parser::add_attribute( $new_image, "data-{$attribute}", $attr );
			}
		}

		// Change srcset to data-srcset attribute.
		$new_image = preg_replace( '/<(.*?)(srcset=)(.*?)>/i', '<$1data-$2$3>', $new_image );

		// Exit early if this is a <source> element from <picture>.
		if ( 'source' === $type ) {
			return $new_image;
		}

		// Add .lazyload class.
		$class = Helpers\Parser::get_attribute( $new_image, 'class' );
		if ( $class ) {
			$class .= ' lazyload';
		} else {
			$class = 'lazyload';
		}

		Helpers\Parser::remove_attribute( $new_image, 'class' );
		Helpers\Parser::add_attribute( $new_image, 'class', apply_filters( 'wp_smush_lazy_load_classes', $class ) );

		Helpers\Parser::add_attribute( $new_image, 'src', 'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==' );

		// Use noscript element in HTML to load elements normally when JavaScript is disabled in browser.
		if ( ! $iframe && ( ! isset( $this->options['noscript'] ) || ! $this->options['noscript'] ) ) {
			$new_image .= '<noscript>' . $image . '</noscript>';
		}

		return $new_image;
	}

	/**
	 * Get images from content and add exclusion class.
	 *
	 * @since 3.2.2
	 *
	 * @param string $content  Page/block content.
	 *
	 * @return string
	 */
	public function exclude_from_lazy_loading( $content ) {
		$images = $this->parser->get_images_from_content( $content );

		if ( empty( $images ) ) {
			return $content;
		}

		foreach ( $images[0] as $key => $image ) {
			$new_image = $image;

			// Add .no-lazyload class.
			$class = Helpers\Parser::get_attribute( $new_image, 'class' );

			if ( $class ) {
				Helpers\Parser::remove_attribute( $new_image, 'class' );
				$class .= ' no-lazyload';
			} else {
				$class = 'no-lazyload';
			}

			Helpers\Parser::add_attribute( $new_image, 'class', $class );

			/**
			 * Filters the no-lazyload image.
			 *
			 * @since 3.8.5
			 *
			 * @param string $text The image that can be filtered.
			 */
			$new_image = apply_filters( 'wp_smush_filter_no_lazyload_image', $new_image );

			$content = str_replace( $image, $new_image, $content );
		}

		return $content;
	}

	/**
	 * Check if this is part of the allowed post type.
	 *
	 * @since 3.2.0
	 *
	 * @return bool
	 */
	private function skip_post_type() {
		// If not settings are set, probably, all are disabled.
		if ( ! is_array( $this->options['include'] ) ) {
			return true;
		}

		$blog_is_frontpage = ( 'posts' === get_option( 'show_on_front' ) && ! is_multisite() ) ? true : false;

		if ( is_front_page() && isset( $this->options['include']['frontpage'] ) && ! $this->options['include']['frontpage'] ) {
			return true;
		} elseif ( is_home() && isset( $this->options['include']['home'] ) && ! $this->options['include']['home'] && ! $blog_is_frontpage ) {
			return true;
		} elseif ( is_page() && isset( $this->options['include']['page'] ) && ! $this->options['include']['page'] ) {
			return true;
		} elseif ( is_single() && isset( $this->options['include']['single'] ) && ! $this->options['include']['single'] ) {
			return true;
		} elseif ( is_archive() && isset( $this->options['include']['archive'] ) && ! $this->options['include']['archive'] ) {
			return true;
		} elseif ( is_category() && isset( $this->options['include']['category'] ) && ! $this->options['include']['category'] ) {
			return true;
		} elseif ( is_tag() && isset( $this->options['include']['tag'] ) && ! $this->options['include']['tag'] ) {
			return true;
		} elseif ( self::skip_custom_post_type( get_post_type() ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Skip custom post type added in settings.
	 *
	 * @since 3.5.0
	 *
	 * @param string $post_type  Post type to check in settings.
	 *
	 * @return bool
	 */
	private function skip_custom_post_type( $post_type ) {
		if ( isset( $this->options['include'][ $post_type ] ) && ! $this->options['include'][ $post_type ] ) {
			return true;
		}

		return false;
	}

	/**
	 * Check if the page has been added to Post, Pages & URLs filter in lazy loading settings.
	 *
	 * @since 3.2.0
	 *
	 * @return bool
	 */
	private function is_exluded_uri() {
		// No exclusion rules defined.
		if ( ! isset( $this->options['exclude-pages'] ) || empty( $this->options['exclude-pages'] ) ) {
			return false;
		}

		$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';

		// Remove empty values.
		$uri_pattern = array_filter( $this->options['exclude-pages'] );
		$uri_pattern = implode( '|', $uri_pattern );

		if ( preg_match( "#{$uri_pattern}#i", $request_uri ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Check if the image has a defined class or ID.
	 *
	 * @since 3.2.0
	 *
	 * @param string $image  Image.
	 *
	 * @return bool
	 */
	private function has_excluded_class_or_id( $image ) {
		$image_classes = Helpers\Parser::get_attribute( $image, 'class' );
		$image_classes = explode( ' ', $image_classes );
		$image_id      = '#' . Helpers\Parser::get_attribute( $image, 'id' );

		if ( in_array( $image_id, $this->options['exclude-classes'], true ) ) {
			return true;
		}

		foreach ( $image_classes as $class ) {
			if ( in_array( $class, $this->excluded_classes, true ) ) {
				return true;
			}

			if ( in_array( ".{$class}", $this->options['exclude-classes'], true ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Buffer sidebar content.
	 *
	 * @since 3.2.0
	 */
	public function filter_sidebar_content_start() {
		ob_start();
	}

	/**
	 * Process buffered content.
	 *
	 * @since 3.2.0
	 */
	public function filter_sidebar_content_end() {
		$content = ob_get_clean();

		echo $this->exclude_from_lazy_loading( $content );

		unset( $content );
	}

	/**
	 * Determine whether it is an AMP page.
	 *
	 * @since 3.4.0
	 *
	 * @return bool Whether AMP.
	 */
	private function is_amp() {
		return function_exists( 'is_amp_endpoint' ) && is_amp_endpoint();
	}
}
