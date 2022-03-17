<?php
/**
 * Compress directory page.
 *
 * @package Smush\App\Pages
 */

namespace Smush\App\Pages;

use Smush\App\Abstract_Summary_Page;
use Smush\App\Interface_Page;
use Smush\Core\Core;
use WP_Smush;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Directory
 */
class Directory extends Abstract_Summary_Page implements Interface_Page {
	/**
	 * Register meta boxes.
	 */
	public function register_meta_boxes() {
		parent::register_meta_boxes();

		$this->add_meta_box(
			'directory',
			__( 'Directory Smush', 'wp-smushit' ),
			array( $this, 'directory_smush_meta_box' ),
			null,
			null,
			'main',
			array(
				'box_class' => 'sui-box sui-message sui-no-padding',
			)
		);

		// Modal for the "Choose Directory" link in the summary box.
		$this->modals['directory-list']  = array();
		$this->modals['progress-dialog'] = array();
	}

	/**
	 * Directory Smush meta box.
	 */
	public function directory_smush_meta_box() {
		// Reset the bulk limit transient.
		if ( ! WP_Smush::is_pro() ) {
			Core::check_bulk_limit( true, 'dir_sent_count' );
		}

		$core = WP_Smush::get_instance()->core();

		$upgrade_url = add_query_arg(
			array(
				'utm_source'   => 'smush',
				'utm_medium'   => 'plugin',
				'utm_campaign' => 'smush_directorysmush_limit_notice',
			),
			$this->upgrade_url
		);

		$errors = 0;
		$images = array();

		$scan = filter_input( INPUT_GET, 'scan', FILTER_SANITIZE_STRING );
		if ( 'done' === $scan ) {
			$images = $core->mod->dir->get_image_errors();
			$errors = $core->mod->dir->get_image_errors_count();
		}

		$this->view(
			'directory/meta-box',
			array(
				'errors'      => $errors,
				'images'      => $images,
				'upgrade_url' => $upgrade_url,
			)
		);
	}

	/**
	 * Show directory smush result notice.
	 *
	 * If we are redirected from a directory smush finish page,
	 * show the result notice if success/fail count is available.
	 *
	 * @since 2.9.0
	 */
	public function smush_result_notice() {
		// Get the counts from transient.
		$items          = (int) get_transient( 'wp-smush-show-dir-scan-notice' );
		$failed_items   = (int) get_transient( 'wp-smush-dir-scan-failed-items' );
		$skipped_items  = (int) get_transient( 'wp-smush-dir-scan-skipped-items' ); // Skipped because already optimized.
		$notice_message = esc_html__( 'Image compression complete.', 'wp-smushit' ) . ' ';
		$notice_class   = 'error';

		$total = $items + $failed_items + $skipped_items;

		/**
		 * 1 image was successfully optimized / 10 images were successfully optimized
		 * 1 image was skipped because it was already optimized / 5/10 images were skipped because they were already optimized
		 * 1 image resulted in an error / 5/10 images resulted in an error, check the logs for more information
		 *
		 * 2/10 images were skipped because they were already optimized and 4/10 resulted in an error
		 */

		if ( 0 === $failed_items && 0 === $skipped_items ) {
			$notice_message .= sprintf(
			/* translators: %d - number of images */
				_n(
					'%d image was successfully optimized',
					'%d images were successfully optimized',
					$items,
					'wp-smushit'
				),
				$items
			);
			$notice_class = 'success';
		} elseif ( 0 <= $skipped_items && 0 === $failed_items ) {
			$notice_message .= sprintf(
			/* translators: %1$d - number of skipped images, %2$d - total number of images */
				_n(
					'%d image was skipped because it was already optimized',
					'%1$d/%2$d images were skipped because they were already optimized',
					$skipped_items,
					'wp-smushit'
				),
				$skipped_items,
				$total
			);
			$notice_class = 'success';
		} elseif ( 0 === $skipped_items && 0 <= $failed_items ) {
			$notice_message .= sprintf(
			/* translators: %1$d - number of failed images, %2$d - total number of images */
				_n(
					'%d resulted in an error',
					'%1$d/%2$d images resulted in an error, check the logs for more information',
					$failed_items,
					'wp-smushit'
				),
				$failed_items,
				$total
			);
		} elseif ( 0 <= $skipped_items && 0 <= $failed_items ) {
			$notice_message .= sprintf(
			/* translators: %1$d - number of skipped images, %2$d - total number of images, %3$d - number of failed images */
				esc_html__( '%1$d/%2$d images were skipped because they were already optimized and %3$d/%2$d images resulted in an error', 'wp-smushit' ),
				$skipped_items,
				$total,
				$failed_items
			);
			$notice_class = 'warning';
		}

		// If we have counts, show the notice.
		if ( 0 < $total ) {
			// Delete the transients.
			delete_transient( 'wp-smush-show-dir-scan-notice' );
			delete_transient( 'wp-smush-dir-scan-failed-items' );
			delete_transient( 'wp-smush-dir-scan-skipped-items' );
			?>
			<script>
				document.addEventListener("DOMContentLoaded", function() {
					window.SUI.openNotice(
						'wp-smush-ajax-notice',
						'<p><?php echo $notice_message; ?></p>',
						{
							type: '<?php echo $notice_class; ?>',
							icon: 'info',
							dismiss: {
								show: true,
								label: '<?php esc_html_e( 'Dismiss', 'wp-smushit' ); ?>',
								tooltip: '<?php esc_html_e( 'Dismiss', 'wp-smushit' ); ?>',
							},
						}
					);
				});
			</script>
			<?php
		}
	}

}
