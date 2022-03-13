<?php
/**
 * Integration with Envira Gallery
 *
 * @since 3.3.0
 * @package Smush\Core\Integrations
 */

namespace Smush\Core\Integrations;

use Smush\Core\Modules\CDN;
use Smush\Core\Modules\Helpers\Parser;
use Smush\Core\Settings;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Envira
 */
class Envira {

	/**
	 * CDN module instance.
	 *
	 * @var CDN $cdn
	 */
	private $cdn;

	/**
	 * Envira constructor.
	 *
	 * @since 3.3.0
	 *
	 * @param CDN $cdn  CDN module.
	 */
	public function __construct( CDN $cdn ) {
		if ( is_admin() ) {
			return;
		}

		$settings = Settings::get_instance();

		if ( $settings->get( 'lazy_load' ) ) {
			add_filter( 'smush_skip_image_from_lazy_load', array( $this, 'skip_lazy_load' ), 10, 3 );
			add_filter( 'envira_gallery_indexable_images', array( $this, 'add_no_lazyload_class' ) );
		}

		if ( $settings->get( 'cdn' ) ) {
			$this->cdn = $cdn;
			add_filter( 'smush_cdn_image_tag', array( $this, 'replace_cdn_links' ) );
		}
	}

	/**
	 * Do not lazy load images from Envira Gallery.
	 *
	 * @since 3.3.0
	 *
	 * @param bool   $lazy_load  Should skip? Default: false.
	 * @param string $src        Image url.
	 * @param string $img        Image.
	 *
	 * @return bool
	 */
	public function skip_lazy_load( $lazy_load, $src, $img ) {
		$classes = Parser::get_attribute( $img, 'class' );
		return false !== strpos( $classes, 'envira-lazy' );
	}

	/**
	 * Replace images from data-envira-src and data-envira-srcset with CDN links.
	 *
	 * @since 3.3.0
	 *
	 * @param string $img  Image.
	 *
	 * @return string
	 */
	public function replace_cdn_links( $img ) {
		$image_src = Parser::get_attribute( $img, 'data-envira-src' );
		if ( $image_src ) {
			// Store the original source to be used later on.
			$original_src = $image_src;

			// Replace the data-envira-src of the image with CDN link.
			$image_src = $this->convert_url_to_cdn( $image_src );
			if ( $image_src ) {
				$img = preg_replace( '#(data-envira-src=["|\'])' . $original_src . '(["|\'])#i', '\1' . $image_src . '\2', $img, 1 );
			}
		}

		$image_srcset = Parser::get_attribute( $img, 'data-envira-srcset' );
		if ( $image_srcset ) {
			// Do not add our own srcset attributes.
			add_filter( 'smush_skip_adding_srcset', '__return_true' );

			// Store the original source to be used later on.
			$original_src = $image_srcset;
			$replace      = false;

			$images = Parser::get_links_from_content( $image_srcset );
			if ( isset( $images[0] ) && is_array( $images[0] ) ) {
				foreach ( $images[0] as $image ) {
					// Replace the data-envira-srcset of the image with CDN link.
					$image_src = $this->convert_url_to_cdn( $image );
					if ( $image_src ) {
						$replace      = true;
						$image_srcset = preg_replace( '#' . $image . '#i', '\1' . $image_src . '\2', $image_srcset, 1 );
					}
				}
			}

			if ( $replace ) {
				$img = preg_replace( '#(data-envira-srcset=["|\'])' . $original_src . '(["|\'])#i', '\1' . $image_srcset . '\2', $img, 1 );
			}
		}

		return $img;
	}

	/**
	 * Galleries in Envira will use a noscript tag with images. Smush can't filter the DOM tree, so we will add
	 * a no-lazyload class to every image.
	 *
	 * @since 3.5.0
	 *
	 * @param string $images  String of img tags that will go inside nocscript element.
	 *
	 * @return string
	 */
	public function add_no_lazyload_class( $images ) {
		$parsed = ( new Parser() )->get_images_from_content( $images );

		if ( empty( $parsed ) ) {
			return $images;
		}

		foreach ( $parsed[0] as $image ) {
			$original = $image;
			$class    = Parser::get_attribute( $image, 'class' );
			if ( ! $class ) {
				Parser::add_attribute( $image, 'class', 'no-lazyload' );
			} else {
				Parser::add_attribute( $image, 'class', $class . ' no-lazyload' );
			}

			$images = str_replace( $original, $image, $images );
		}

		return $images;
	}

	/**
	 * Convert URL to CDN link.
	 *
	 * @since 3.3.0
	 *
	 * @param string $url  Image URL.
	 *
	 * @return bool|string
	 */
	private function convert_url_to_cdn( $url ) {
		if ( ! $this->cdn->is_supported_path( $url ) ) {
			return false;
		}

		return $this->cdn->generate_cdn_url( $url );
	}

}
