<?php
/**
 * NextGen admin view: Nextgen class
 *
 * @package Smush\App\Pages
 */

namespace Smush\App\Pages;

use Smush\App\Abstract_Page;
use Smush\App\Admin;
use WP_Smush;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Nextgen
 */
class Nextgen extends Abstract_Page {

	/**
	 * Function triggered when the page is loaded before render any content.
	 */
	public function on_load() {
		// Localize variables for NextGen Manage gallery page.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
	}

	/**
	 * Render inner content.
	 */
	public function render_inner_content() {
		$this->view( 'smush-nextgen-page' );
	}

	/**
	 * Register meta boxes.
	 */
	public function register_meta_boxes() {
		$this->add_meta_box(
			'summary',
			null,
			array( $this, 'dashboard_summary_metabox' ),
			null,
			null,
			'summary',
			array(
				'box_class'         => 'sui-box sui-summary sui-summary-smush-nextgen',
				'box_content_class' => false,
			)
		);

		$this->add_meta_box(
			'bulk',
			__( 'Bulk Smush', 'wp-smushit' ),
			array( $this, 'bulk_metabox' ),
			array( $this, 'bulk_header_metabox' ),
			null,
			'bulk',
			array(
				'box_class' => 'sui-box bulk-smush-wrapper',
			)
		);
	}

	/**
	 * Enqueue Scripts on Manage Gallery page
	 */
	public function enqueue() {
		$current_screen = get_current_screen();
		if ( ! empty( $current_screen ) && in_array( $current_screen->base, Admin::$plugin_pages, true ) ) {
			WP_Smush::get_instance()->core()->nextgen->ng_admin->localize();
		}
	}


	/**
	 * NextGen summary meta box.
	 */
	public function dashboard_summary_metabox() {
		$ng = WP_Smush::get_instance()->core()->nextgen->ng_admin;

		$lossy_enabled = WP_Smush::is_pro() && $this->settings->get( 'lossy' );

		$smushed_image_count = 0;
		if ( $lossy_enabled ) {
			$smushed_image = $ng->ng_stats->get_ngg_images( 'smushed' );
			if ( ! empty( $smushed_image ) && is_array( $smushed_image ) && ! empty( $this->resmush_ids ) && is_array( $this->resmush_ids ) ) {
				// Get smushed images excluding resmush IDs.
				$smushed_image = array_diff_key( $smushed_image, array_flip( $this->resmush_ids ) );
			}
			$smushed_image_count = is_array( $smushed_image ) ? count( $smushed_image ) : 0;
		}

		$this->view(
			'nextgen/summary-meta-box',
			array(
				'image_count'         => $ng->image_count,
				'lossy_enabled'       => $lossy_enabled,
				'smushed_image_count' => $smushed_image_count,
				'super_smushed_count' => $ng->super_smushed,
				'stats_human'         => $ng->stats['human'] > 0 ? $ng->stats['human'] : '0 MB',
				'stats_percent'       => $ng->stats['percent'] > 0 ? number_format_i18n( $ng->stats['percent'], 1 ) : 0,
				'total_count'         => $ng->total_count,
			)
		);
	}

	/**
	 * NextGen bulk Smush header meta box.
	 */
	public function bulk_header_metabox() {
		$this->view(
			'nextgen/meta-box-header',
			array(
				'title' => __( 'Bulk Smush', 'wp-smushit' ),
			)
		);
	}

	/**
	 * NextGen bulk Smush meta box.
	 */
	public function bulk_metabox() {
		$ng = WP_Smush::get_instance()->core()->nextgen->ng_admin;

		$resmush_ids = get_option( 'wp-smush-nextgen-resmush-list', false );

		$resmush_count = $resmush_ids ? count( $resmush_ids ) : 0;

		$count = $resmush_count + $ng->remaining_count;

		$url = add_query_arg(
			array(
				'page' => 'smush#wp-smush-settings-box',
			),
			admin_url( 'upload.php' )
		);

		$this->view(
			'nextgen/meta-box',
			array(
				'total_images_to_smush' => $count,
				'ng'                    => $ng,
				'remaining_count'       => $ng->remaining_count,
				'resmush_count'         => $resmush_count,
				'total_count'           => $ng->total_count,
				'url'                   => $url,
			)
		);
	}

}
