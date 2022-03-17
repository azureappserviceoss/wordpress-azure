<?php
/**
 * Extend NextGen Mixin class to smush dynamic images.
 *
 * @package Smush\Core\Integrations\NextGen
 */

namespace Smush\Core\Integrations\NextGen;

use C_Component_Registry;
use C_Gallery_Storage;
use C_Image;
use Mixin;
use nggdb;
use WP_Smush;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Thumbs
 */
class Thumbs extends Mixin {
	/**
	 * Overrides the NextGen Gallery function, to smush the dynamic images and thumbnails created by gallery
	 *
	 * @param int|object|C_Image $image          Image object.
	 * @param string             $size           Size.
	 * @param array|null         $params         Optional. Parameters array.
	 * @param bool               $skip_defaults  Optional. Skip defaults.
	 *
	 * @return bool|object
	 */
	function generate_image_size( $image, $size, $params = null, $skip_defaults = false ) {
		$smush = WP_Smush::get_instance()->core()->mod->smush;

		$image_id = ! empty( $image->pid ) ? $image->pid : '';
		// Get image from storage object if we don't have it already.
		if ( empty( $image_id ) ) {
			// Get metadata For the image.
			// Registry Object for NextGen Gallery.
			$registry = C_Component_Registry::get_instance();

			/**
			 * Gallery Storage Object.
			 *
			 * @var C_Gallery_Storage $storage
			 */
			$storage = $registry->get_utility( 'I_Gallery_Storage' );

			$image_id = $storage->object->_get_image_id( $image );
		}
		// Call the actual function to generate the image, and pass the image to smush.
		$success = $this->call_parent( 'generate_image_size', $image, $size, $params, $skip_defaults );
		if ( $success ) {
			$filename = $success->fileName;
			// Smush it, if it exists.
			if ( file_exists( $filename ) ) {
				$response = $smush->do_smushit( $filename );

				// If the image was smushed.
				if ( ! is_wp_error( $response ) && ! empty( $response['data'] ) && $response['data']->bytes_saved > 0 ) {
					// Check for existing stats.
					if ( ! empty( $image->meta_data ) && ! empty( $image->meta_data['wp_smush'] ) ) {
						$stats = $image->meta_data['wp_smush'];
					} else {
						// Initialize stats array.
						$stats = array(
							'stats' => array_merge(
								$smush->get_size_signature(),
								array(
									'api_version' => - 1,
									'lossy'       => - 1,
									'keep_exif'   => false,
								)
							),
							'sizes' => array(),
						);

						$stats['bytes']       = $response['data']->bytes_saved;
						$stats['percent']     = $response['data']->compression;
						$stats['size_after']  = $response['data']->after_size;
						$stats['size_before'] = $response['data']->before_size;
						$stats['time']        = $response['data']->time;
					}
					$stats['sizes'][ $size ] = (object) $smush->array_fill_placeholders( $smush->get_size_signature(), (array) $response['data'] );

					if ( isset( $image->metadata ) ) {
						$image->meta_data['wp_smush'] = $stats;
						nggdb::update_image_meta( $image->pid, $image->meta_data );
					}

					// Allows To get the stats for each image, after the image is smushed.
					do_action( 'wp_smush_nextgen_image_stats', $image_id, $stats );
				}
			}
		}
		return $success;
	}
}
