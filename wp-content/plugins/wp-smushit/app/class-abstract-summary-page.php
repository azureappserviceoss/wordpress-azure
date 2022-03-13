<?php
/**
 * Abstract class for pages with a common top summary meta box.
 *
 * @since 3.8.6
 * @package Smush\App
 */

namespace Smush\App;

use Smush\Core\Settings;
use WP_Smush;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Abstract_Summary_Page
 */
abstract class Abstract_Summary_Page extends Abstract_Page {
	/**
	 * Function triggered when the page is loaded before render any content.
	 */
	public function on_load() {
		// Add stats to stats box.
		add_action( 'stats_ui_after_resize_savings', array( $this, 'pro_savings_stats' ), 15 );
		add_action( 'stats_ui_after_resize_savings', array( $this, 'conversion_savings_stats' ), 15 );
		add_action( 'stats_ui_after_resize_savings', array( $this, 'cdn_stats_ui' ), 20 );
		if ( Abstract_Page::should_render( 'directory' ) ) {
			add_action( 'stats_ui_after_resize_savings', array( $this, 'directory_stats_ui' ), 10 );
		}
	}

	/**
	 * Register meta boxes.
	 */
	public function register_meta_boxes() {
		if ( ! is_network_admin() ) {
			$this->add_meta_box(
				'summary',
				null,
				array( $this, 'dashboard_summary_meta_box' ),
				null,
				null,
				'main',
				array(
					'box_class'         => 'sui-box sui-summary sui-summary-smush ' . $this->get_whitelabel_class(),
					'box_content_class' => false,
				)
			);

			// If not a pro user.
			if ( ! WP_Smush::is_pro() ) {
				/**
				 * Allows to hook in additional containers after stats box for free version
				 * Pro Version has a full width settings box, so we don't want to do it there.
				 */
				do_action( 'wp_smush_after_stats_box' );
			}
		}
	}

	/**
	 * Return rebranded class.
	 *
	 * @since 3.8.8
	 *
	 * @return string
	 */
	private function get_whitelabel_class() {
		if ( ! apply_filters( 'wpmudev_branding_hide_branding', false ) ) {
			return '';
		}

		return apply_filters( 'wpmudev_branding_hero_image', '' ) ? 'sui-rebranded' : 'sui-unbranded';
	}

	/**
	 * Summary meta box.
	 */
	public function dashboard_summary_meta_box() {
		$core = WP_Smush::get_instance()->core();

		$resize_count = $core->get_savings( 'resize', false, false, true );

		// Split human size to get format and size.
		$human = explode( ' ', $core->stats['human'] );

		$resize_savings = 0;
		// Get current resize savings.
		if ( ! empty( $core->stats['resize_savings'] ) && $core->stats['resize_savings'] > 0 ) {
			$resize_savings = size_format( $core->stats['resize_savings'], 1 );
		}

		$this->view(
			'summary/meta-box',
			array(
				'human_format'    => empty( $human[1] ) ? 'B' : $human[1],
				'human_size'      => empty( $human[0] ) ? '0' : $human[0],
				'remaining'       => $this->get_total_images_to_smush(),
				'resize_count'    => ! $resize_count ? 0 : $resize_count,
				'resize_enabled'  => (bool) $this->settings->get( 'resize' ),
				'resize_savings'  => $resize_savings,
				'stats_percent'   => $core->stats['percent'] > 0 ? number_format_i18n( $core->stats['percent'], 1 ) : 0,
				'total_optimized' => $core->stats['total_images'],
			)
		);
	}

	/**
	 * Show super smush stats in stats section.
	 *
	 * If a pro member and super smush is enabled, show super smushed
	 * stats else show message that encourage them to enable super smush.
	 * If free user show the avg savings that can be achived using Pro.
	 *
	 * @return void
	 */
	public function pro_savings_stats() {
		$core = WP_Smush::get_instance()->core();

		if ( ! WP_Smush::is_pro() ) {
			if ( empty( $core->stats ) || empty( $core->stats['pro_savings'] ) ) {
				$core->set_pro_savings();
			}
			$pro_savings      = $core->stats['pro_savings'];
			$show_pro_savings = $pro_savings['savings'] > 0;
			if ( $show_pro_savings ) {
				?>
				<li class="smush-avg-pro-savings" id="smush-avg-pro-savings">
					<span class="sui-list-label"><?php esc_html_e( 'Pro Savings', 'wp-smushit' ); ?>
						<span class="sui-tag sui-tag-pro sui-tooltip sui-tooltip-constrained" data-tooltip="<?php esc_html_e( 'Join WPMU DEV to unlock multi-pass lossy compression', 'wp-smushit' ); ?>">
							<?php esc_html_e( 'PRO', 'wp-smushit' ); ?>
						</span>
					</span>
					<span class="sui-list-detail wp-smush-stats">
						<span class="wp-smush-stats-human"><?php echo esc_html( $pro_savings['savings'] ); ?></span>
						<span class="wp-smush-stats-sep">/</span>
						<span class="wp-smush-stats-percent"><?php echo esc_html( $pro_savings['percent'] ); ?></span>%
					</span>
				</li>
				<?php
			}
		} else {
			$compression_savings = 0;
			if ( ! empty( $core->stats ) && ! empty( $core->stats['bytes'] ) ) {
				$compression_savings = $core->stats['bytes'] - $core->stats['resize_savings'];
			}
			?>
			<li class="super-smush-attachments">
				<span class="sui-list-label">
					<?php esc_html_e( 'Super-Smush Savings', 'wp-smushit' ); ?>
					<?php if ( ! $this->settings->get( 'lossy' ) ) { ?>
						<p class="wp-smush-stats-label-message sui-hidden-sm sui-hidden-md sui-hidden-lg">
							<?php
							$settings_link = '#';
							$link_class    = 'wp-smush-lossy-enable';
							if ( Settings::can_access( 'bulk' ) && 'smush-bulk' !== $this->get_slug() ) {
								$settings_link = $this->get_url( 'smush-bulk' ) . '#enable-lossy';
								$link_class    = '';
							}
							printf( /* translators: %1$s; starting a tag, %2$s: ending a tag */
								esc_html__( 'Compress images up to 2x more than regular smush with almost no visible drop in quality. %1$sEnable Super-Smush%2$s', 'wp-smushit' ),
								'<a role="button" class="' . esc_attr( $link_class ) . '" href="' . esc_url( $settings_link ) . '">',
								'</a>'
							);
							?>
						</p>
					<?php } ?>
				</span>
				<?php if ( WP_Smush::is_pro() ) : ?>
					<span class="sui-list-detail wp-smush-stats">
						<?php if ( ! $this->settings->get( 'lossy' ) ) : ?>
							<a role="button" class="sui-hidden-xs <?php echo esc_attr( $link_class ); ?>" href="<?php echo esc_url( $settings_link ); ?>">
								<?php esc_html_e( 'Enable Super-Smush', 'wp-smushit' ); ?>
							</a>
						<?php else : ?>
							<span class="smushed-savings">
								<?php echo esc_html( size_format( $compression_savings, 1 ) ); ?>
							</span>
						<?php endif; ?>
					</span>
				<?php endif; ?>
			</li>
			<?php
		}
	}

	/**
	 * Show conversion savings stats in stats section.
	 *
	 * Show Png to Jpg conversion savings in stats box if the
	 * settings enabled or savings found.
	 *
	 * @return void
	 */
	public function conversion_savings_stats() {
		$core = WP_Smush::get_instance()->core();

		if ( WP_Smush::is_pro() && ! empty( $core->stats['conversion_savings'] ) && $core->stats['conversion_savings'] > 0 ) {
			?>
			<li class="smush-conversion-savings">
				<span class="sui-list-label">
					<?php esc_html_e( 'PNG to JPEG savings', 'wp-smushit' ); ?>
				</span>
				<span class="sui-list-detail wp-smush-stats">
					<?php echo $core->stats['conversion_savings'] > 0 ? esc_html( size_format( $core->stats['conversion_savings'], 1 ) ) : '0 MB'; ?>
				</span>
			</li>
			<?php
		}
	}

	/**
	 * Calculates the total images to be smushed.
	 * This is all unsmushed images + all images to re-smush.
	 *
	 * We're not using $core->remaining_count because it excludes the resmush count
	 * when the amount of unsmushed images and amount of images to re-smush are the same.
	 * So, if you have 2 images to re-smush and 2 unsmushed images, it'll return 2 and no 4.
	 * We might need to check that there, it's used everywhere so we must be careful. Using this in the meantime.
	 *
	 * @since 3.7.2
	 *
	 * @return integer
	 */
	protected function get_total_images_to_smush() {
		$images_to_resmush = count( get_option( 'wp-smush-resmush-list', array() ) );

		// This is the same calculation used for $core->remaining_count,
		// except that we don't add the re-smushed count here.
		$unsmushed_count = WP_Smush::get_instance()->core()->total_count - WP_Smush::get_instance()->core()->smushed_count - WP_Smush::get_instance()->core()->skipped_count;

		// Sometimes this number can be negative, if there are weird issues with meta data.
		if ( $unsmushed_count > 0 ) {
			return $images_to_resmush + $unsmushed_count;
		}

		return $images_to_resmush;
	}

	/**
	 * Add CDN stats to stats meta box.
	 *
	 * @since 2.8.6
	 */
	public function cdn_stats_ui() {
		$status = WP_Smush::get_instance()->core()->mod->cdn->status();

		if ( 'disabled' === $status ) {
			return;
		}
		?>
		<li class="smush-cdn-stats">
			<span class="sui-list-label"><?php esc_html_e( 'CDN', 'wp-smushit' ); ?></span>
			<span class="wp-smush-stats sui-list-detail">
				<i class="sui-icon-loader sui-loading sui-hidden" aria-hidden="true" title="<?php esc_attr_e( 'Updating Stats', 'wp-smushit' ); ?>"></i>
				<?php if ( 'overcap' === $status ) : ?>
					<span class="sui-tooltip sui-tooltip-top-right sui-tooltip-constrained" data-tooltip="<?php esc_attr_e( "You've gone through your CDN bandwidth limit, so we’ve stopped serving your images via the CDN. Contact your administrator to upgrade your Smush CDN plan to reactivate this service", 'wp-smushit' ); ?>">
						<i class="sui-icon-warning-alert sui-error sui-md" aria-hidden="true"></i>
					</span>
					<span><?php esc_html_e( 'Overcap', 'wp-smushit' ); ?></span>
				<?php elseif ( 'upgrade' === $status ) : ?>
					<span class="sui-tooltip sui-tooltip-top-right sui-tooltip-constrained" data-tooltip="<?php esc_attr_e( "You're almost through your CDN bandwidth limit. Please contact your administrator to upgrade your Smush CDN plan to ensure you don't lose this service", 'wp-smushit' ); ?>">
						<i class="sui-icon-warning-alert sui-warning sui-md" aria-hidden="true"></i>
					</span>
					<span><?php esc_html_e( 'Needs upgrade', 'wp-smushit' ); ?></span>
				<?php elseif ( 'activating' === $status ) : ?>
					<i class="sui-icon-check-tick sui-info sui-md" aria-hidden="true"></i>
					<span><?php esc_html_e( 'Activating', 'wp-smushit' ); ?></span>
				<?php else : ?>
					<span class="sui-tooltip sui-tooltip-top-right sui-tooltip-constrained" data-tooltip="<?php esc_attr_e( 'Your media is currently being served from the WPMU DEV CDN. Bulk and Directory smush features are treated separately and will continue to run independently.', 'wp-smushit' ); ?>">
						<i class="sui-icon-check-tick sui-success sui-md" aria-hidden="true"></i>
					</span>
					<span><?php esc_html_e( 'Active', 'wp-smushit' ); ?></span>
				<?php endif; ?>
			</span>
		</li>
		<?php
	}

	/**
	 * Set directory smush stats to stats box.
	 *
	 * @return void
	 */
	public function directory_stats_ui() {
		$dir_smush_stats = get_option( 'dir_smush_stats' );
		$human           = 0;
		if ( ! empty( $dir_smush_stats ) && ! empty( $dir_smush_stats['dir_smush'] ) ) {
			$human = ! empty( $dir_smush_stats['dir_smush']['bytes'] ) && $dir_smush_stats['dir_smush']['bytes'] > 0 ? $dir_smush_stats['dir_smush']['bytes'] : 0;
		}
		?>
		<li class="smush-dir-savings">
			<span class="sui-list-label"><?php esc_html_e( 'Directory Smush Savings', 'wp-smushit' ); ?>
				<?php if ( $human <= 0 ) { ?>
					<p class="wp-smush-stats-label-message sui-hidden-sm sui-hidden-md sui-hidden-lg">
						<?php esc_html_e( "Smush images that aren't located in your uploads folder.", 'wp-smushit' ); ?>
						<a href="<?php echo esc_url( $this->get_url( 'smush-directory' ) ); ?>" class="wp-smush-dir-link"
							id="<?php echo 'smush-directory' === $this->get_slug() ? 'smush-directory-open-modal' : ''; ?>"
							title="<?php esc_attr_e( "Select a directory you'd like to Smush.", 'wp-smushit' ); ?>">
							<?php esc_html_e( 'Choose directory', 'wp-smushit' ); ?>
						</a>
					</p>
				<?php } ?>
			</span>
			<span class="wp-smush-stats sui-list-detail">
				<i class="sui-icon-loader sui-loading" aria-hidden="true" title="<?php esc_attr_e( 'Updating Stats', 'wp-smushit' ); ?>"></i>
				<span class="wp-smush-stats-human"></span>
				<span class="wp-smush-stats-sep sui-hidden">/</span>
				<span class="wp-smush-stats-percent"></span>
				<a href="<?php echo esc_url( $this->get_url( 'smush-directory' ) ); ?>" class="wp-smush-dir-link sui-hidden-xs sui-hidden"
					id="<?php echo 'smush-directory' === $this->get_slug() ? 'smush-directory-open-modal' : ''; ?>"
					title="<?php esc_attr_e( "Select a directory you'd like to Smush.", 'wp-smushit' ); ?>">
					<?php esc_html_e( 'Choose directory', 'wp-smushit' ); ?>
				</a>
			</span>
		</li>
		<?php
	}
}
