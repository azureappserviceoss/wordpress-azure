<?php
/**
 * Lazy loading meta box.
 *
 * @since 3.2.0
 * @package WP_Smush
 *
 * @var array $conflicts  Conflicting plugins.
 * @var array $cpts       Custom post types.
 * @var array $settings   Lazy loading settings.
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

// We need this for uploader to work properly.
wp_enqueue_media();
wp_enqueue_style( 'wp-color-picker' );

?>

<p>
	<?php
	esc_html_e( 'This feature stops offscreen images from loading until a visitor scrolls to them. Make your page load faster, use less bandwidth and fix the “defer offscreen images” recommendation from a Google PageSpeed test.', 'wp-smushit' );
	?>
</p>

<?php if ( ! $conflicts || ! is_array( $conflicts ) || empty( $conflicts ) ) : ?>
	<div class="sui-notice sui-notice-success">
		<div class="sui-notice-content">
			<div class="sui-notice-message">
				<i class="sui-notice-icon sui-icon-check-tick sui-md" aria-hidden="true"></i>
				<p><?php esc_html_e( 'Lazy loading is active.', 'wp-smushit' ); ?></p>
			</div>
		</div>
	</div>
<?php else : ?>
	<div class="sui-notice sui-notice-warning">
		<div class="sui-notice-content">
			<div class="sui-notice-message">
				<i class="sui-notice-icon sui-icon-warning-alert sui-md" aria-hidden="true"></i>
				<p>
					<?php
					printf( /* translators: %s - list of plugins */
						esc_html__( "We've detected another active plugin that offers Lazy Load: %s. Smush may not work as expected if Lazy Load is enabled in multiple plugins. For best results, activate Lazy Load in only one plugin at a time.", 'wp-smushit' ),
						'<strong>' . esc_html( join( ', ', $conflicts ) ) . '</strong>'
					);
					?>
				</p>
			</div>
		</div>
	</div>
<?php endif; ?>

<div class="sui-box-settings-row">
	<div class="sui-box-settings-col-1">
		<span class="sui-settings-label">
			<?php esc_html_e( 'Media Types', 'wp-smushit' ); ?>
		</span>
		<span class="sui-description">
			<?php esc_html_e( 'Choose which media types you want to lazy load.', 'wp-smushit' ); ?>
		</span>
	</div>
	<div class="sui-box-settings-col-2">
		<label for="format-jpeg" class="sui-checkbox sui-checkbox-stacked">
			<input type='hidden' value='0' name='format[jpeg]' />
			<input type="checkbox" name="format[jpeg]" id="format-jpeg" <?php checked( $settings['format']['jpeg'] ); ?> />
			<span aria-hidden="true"></span>
			<span><?php esc_html_e( '.jpeg', 'wp-smushit' ); ?></span>
		</label>
		<label for="format-png" class="sui-checkbox sui-checkbox-stacked">
			<input type='hidden' value='0' name='format[png]' />
			<input type="checkbox" name="format[png]" id="format-png" <?php checked( $settings['format']['png'] ); ?> />
			<span aria-hidden="true"></span>
			<span><?php esc_html_e( '.png', 'wp-smushit' ); ?></span>
		</label>
		<label for="format-webp" class="sui-checkbox sui-checkbox-stacked">
			<input type='hidden' value='0' name='format[webp]' />
			<input type="checkbox" name="format[webp]" id="format-webp" <?php checked( ! isset( $settings['format']['webp'] ) || $settings['format']['webp'] ); ?> />
			<span aria-hidden="true"></span>
			<span><?php esc_html_e( '.webp', 'wp-smushit' ); ?></span>
		</label>
		<label for="format-gif" class="sui-checkbox sui-checkbox-stacked">
			<input type='hidden' value='0' name='format[gif]' />
			<input type="checkbox" name="format[gif]" id="format-gif" <?php checked( $settings['format']['gif'] ); ?> />
			<span aria-hidden="true"></span>
			<span><?php esc_html_e( '.gif', 'wp-smushit' ); ?></span>
		</label>
		<label for="format-svg" class="sui-checkbox sui-checkbox-stacked">
			<input type='hidden' value='0' name='format[svg]' />
			<input type="checkbox" name="format[svg]" id="format-svg" <?php checked( $settings['format']['svg'] ); ?> />
			<span aria-hidden="true"></span>
			<span><?php esc_html_e( '.svg', 'wp-smushit' ); ?></span>
		</label>
		<label for="format-iframe" class="sui-checkbox sui-checkbox-stacked">
			<input type='hidden' value='0' name='format[iframe]' />
			<input type="checkbox" name="format[iframe]" id="format-iframe" <?php checked( ! isset( $settings['format']['iframe'] ) || $settings['format']['iframe'] ); ?> />
			<span aria-hidden="true"></span>
			<span><?php esc_html_e( 'iframe', 'wp-smushit' ); ?></span>
		</label>
	</div>
</div>

<div class="sui-box-settings-row">
	<div class="sui-box-settings-col-1">
		<span class="sui-settings-label">
			<?php esc_html_e( 'Output Locations', 'wp-smushit' ); ?>
		</span>
		<span class="sui-description">
			<?php esc_html_e( 'By default we will lazy load all images, but you can refine this to specific media outputs too.', 'wp-smushit' ); ?>
		</span>
	</div>
	<div class="sui-box-settings-col-2">
		<label for="output-content" class="sui-checkbox sui-checkbox-stacked">
			<input type='hidden' value='0' name='output[content]' />
			<input type="checkbox" name="output[content]" id="output-content" <?php checked( $settings['output']['content'] ); ?> />
			<span aria-hidden="true"></span>
			<span><?php esc_html_e( 'Content', 'wp-smushit' ); ?></span>
		</label>
		<label for="output-widgets" class="sui-checkbox sui-checkbox-stacked">
			<input type='hidden' value='0' name='output[widgets]' />
			<input type="checkbox" name="output[widgets]" id="output-widgets" <?php checked( $settings['output']['widgets'] ); ?> />
			<span aria-hidden="true"></span>
			<span><?php esc_html_e( 'Widgets', 'wp-smushit' ); ?></span>
		</label>
		<label for="output-thumbnails" class="sui-checkbox sui-checkbox-stacked">
			<input type='hidden' value='0' name='output[thumbnails]' />
			<input type="checkbox" name="output[thumbnails]" id="output-thumbnails" <?php checked( $settings['output']['thumbnails'] ); ?> />
			<span aria-hidden="true"></span>
			<span><?php esc_html_e( 'Post Thumbnail', 'wp-smushit' ); ?></span>
		</label>
		<label for="output-gravatars" class="sui-checkbox sui-checkbox-stacked">
			<input type='hidden' value='0' name='output[gravatars]' />
			<input type="checkbox" name="output[gravatars]" id="output-gravatars" <?php checked( $settings['output']['gravatars'] ); ?> />
			<span aria-hidden="true"></span>
			<span><?php esc_html_e( 'Gravatars', 'wp-smushit' ); ?></span>
		</label>
	</div>
</div>

<div class="sui-box-settings-row">
	<div class="sui-box-settings-col-1">
		<span class="sui-settings-label">
			<?php esc_html_e( 'Display & Animation', 'wp-smushit' ); ?>
		</span>
		<span class="sui-description">
			<?php esc_html_e( 'Choose how you want preloading images to be displayed, as well as how they animate into view.', 'wp-smushit' ); ?>
		</span>
	</div>
	<div class="sui-box-settings-col-2">
		<strong><?php esc_html_e( 'Display', 'wp-smushit' ); ?></strong>
		<div class="sui-description">
			<?php esc_html_e( 'Choose how you want the non-loaded image to look.', 'wp-smushit' ); ?>
		</div>

		<div class="sui-side-tabs sui-tabs">
			<div data-tabs>
				<label for="animation-fadein" class="sui-tab-item <?php echo 'fadein' === $settings['animation']['selected'] ? 'active' : ''; ?>">
					<input type="radio" name="animation[selected]" value="fadein" id="animation-fadein" <?php checked( $settings['animation']['selected'], 'fadein' ); ?> />
					<?php esc_html_e( 'Fade In', 'wp-smushit' ); ?>
				</label>
				<label for="animation-spinner" class="sui-tab-item <?php echo 'spinner' === $settings['animation']['selected'] ? 'active' : ''; ?>">
					<input type="radio" name="animation[selected]" value="spinner" id="animation-spinner" <?php checked( $settings['animation']['selected'], 'spinner' ); ?> />
					<?php esc_html_e( 'Spinner', 'wp-smushit' ); ?>
				</label>
				<label for="animation-placeholder" class="sui-tab-item <?php echo 'placeholder' === $settings['animation']['selected'] ? 'active' : ''; ?>">
					<input type="radio" name="animation[selected]" value="placeholder" id="animation-placeholder" <?php checked( $settings['animation']['selected'], 'placeholder' ); ?> />
					<?php esc_html_e( 'Placeholder', 'wp-smushit' ); ?>
				</label>
				<label for="animation-disabled" class="sui-tab-item <?php echo 'none' === $settings['animation']['selected'] ? 'active' : ''; ?>">
					<input type="radio" name="animation[selected]" value="none" id="animation-disabled" <?php checked( $settings['animation']['selected'], 'none' ); ?> />
					<?php esc_html_e( 'None', 'wp-smushit' ); ?>
				</label>
			</div><!-- end data-tabs -->
			<div data-panes>
				<div class="sui-tab-boxed <?php echo 'fadein' === $settings['animation']['selected'] ? 'active' : ''; ?>">
					<strong><?php esc_html_e( 'Animation', 'wp-smushit' ); ?></strong>
					<span class="sui-description">
						<?php esc_html_e( 'Once the image has loaded, choose how you want the image to display when it comes into view.', 'wp-smushit' ); ?>
					</span>
					<div class="sui-form-field-inline">
						<div class="sui-form-field">
							<label for="fadein-duration" class="sui-label"><?php esc_html_e( 'Duration', 'wp-smushit' ); ?></label>
							<input type='hidden' value='0' name='animation[duration]' />
							<input type="number" name="animation[duration]" placeholder="400" value="<?php echo absint( $settings['animation']['fadein']['duration'] ); ?>" id="fadein-duration" class="sui-form-control sui-input-sm sui-field-has-suffix">
							<span class="sui-field-suffix"><?php esc_html_e( 'ms', 'wp-smushit' ); ?></span>
						</div>
						<div class="sui-form-field">
							<label for="fadein-delay" class="sui-label"><?php esc_html_e( 'Delay', 'wp-smushit' ); ?></label>
							<input type='hidden' value='0' name='animation[delay]' />
							<input type="number" name="animation[delay]" placeholder="0" value="<?php echo absint( $settings['animation']['fadein']['delay'] ); ?>" id="fadein-delay" class="sui-form-control sui-input-sm sui-field-has-suffix">
							<span class="sui-field-suffix"><?php esc_html_e( 'ms', 'wp-smushit' ); ?></span>
						</div>
					</div>
				</div>

				<div class="sui-tab-boxed <?php echo 'spinner' === $settings['animation']['selected'] ? 'active' : ''; ?>" id="smush-lazy-load-spinners">
					<span class="sui-description">
						<?php esc_html_e( 'Display a spinner where the image will be during lazy loading. You can choose a predefined spinner, or upload your own GIF.', 'wp-smushit' ); ?>
					</span>
					<label class="sui-label"><?php esc_html_e( 'Spinner', 'wp-smushit' ); ?></label>
					<div class="sui-box-selectors">
						<ul>
							<?php for ( $i = 1; $i <= 5; $i++ ) : ?>
								<li><label for="spinner-<?php echo absint( $i ); ?>" class="sui-box-selector">
									<input type="radio" name="animation[spinner-icon]" id="spinner-<?php echo absint( $i ); ?>" value="<?php echo absint( $i ); ?>" <?php checked( (int) $settings['animation']['spinner']['selected'] === $i ); ?> />
									<span>
										<img alt="<?php esc_attr_e( 'Spinner image', 'wp-smushit' ); ?>&nbsp;<?php echo absint( $i ); ?>" src="<?php echo esc_url( WP_SMUSH_URL . 'app/assets/images/smush-lazyloader-' . $i . '.gif' ); ?>" />
									</span>
									</label></li>
							<?php endfor; ?>

							<?php foreach ( $settings['animation']['spinner']['custom'] as $image ) : ?>
								<?php $custom_link = wp_get_attachment_image_src( $image, 'full' ); ?>
								<li><label for="spinner-<?php echo absint( $image ); ?>" class="sui-box-selector">
									<input type="radio" name="animation[spinner-icon]" id="spinner-<?php echo absint( $image ); ?>" value="<?php echo absint( $image ); ?>" <?php checked( $image === $settings['animation']['spinner']['selected'] ); ?> />
									<span>
										<button class="remove-selector sui-button-icon sui-tooltip smush-ll-remove" data-tooltip="<?php esc_attr_e( 'Remove', 'wp-smushit' ); ?>">
											<i class="sui-icon-close" aria-hidden="true" data-id="<?php echo absint( $image ); ?>" data-type="spinner"></i>
										</button>

										<img alt="<?php esc_attr_e( 'Spinner image', 'wp-smushit' ); ?>&nbsp;<?php echo absint( $image ); ?>" src="<?php echo esc_url( $custom_link[0] ); ?>" />
									</span>
									</label></li>
							<?php endforeach; ?>
						</ul>

						<div class="sui-upload">
							<input type="hidden" name="animation[custom-spinner]" id="smush-spinner-icon-file" value="">

							<div class="sui-upload-image" aria-hidden="true">
								<div class="sui-image-mask"></div>
								<div role="button" class="sui-image-preview" id="smush-spinner-icon-preview" onclick="WP_Smush.Lazyload.addLoaderIcon()"></div>
							</div>

							<a class="sui-upload-button" id="smush-upload-spinner" onclick="WP_Smush.Lazyload.addLoaderIcon()">
								<i class="sui-icon-upload-cloud" aria-hidden="true"></i> <?php esc_html_e( 'Upload file', 'wp-smushit' ); ?>
							</a>

							<div class="sui-upload-file" id="smush-remove-spinner">
								<span></span>
								<button aria-label="<?php esc_attr_e( 'Remove file', 'wp-smushit' ); ?>">
									<i class="sui-icon-close" aria-hidden="true"></i>
								</button>
							</div>
						</div>
					</div>
				</div>

				<div class="sui-tab-boxed <?php echo 'placeholder' === $settings['animation']['selected'] ? 'active' : ''; ?>" id="smush-lazy-load-placeholder">
					<span class="sui-description">
						<?php esc_html_e( 'Display a placeholder to display instead of the actual image during lazy loading. You can choose a predefined image, or upload your own.', 'wp-smushit' ); ?>
					</span>
					<label class="sui-label"><?php esc_html_e( 'Image', 'wp-smushit' ); ?></label>
					<div class="sui-box-selectors">
						<ul>
							<?php for ( $i = 1; $i <= 2; $i++ ) : ?>
								<li><label for="placeholder-icon-<?php echo absint( $i ); ?>" class="sui-box-selector">
									<input type="radio" name="animation[placeholder-icon]" id="placeholder-icon-<?php echo absint( $i ); ?>" value="<?php echo absint( $i ); ?>" <?php checked( (int) $settings['animation']['placeholder']['selected'] === $i ); ?> />
									<span>
										<img alt="<?php esc_attr_e( 'Placeholder image', 'wp-smushit' ); ?>&nbsp;<?php echo absint( $i ); ?>" src="<?php echo esc_url( WP_SMUSH_URL . 'app/assets/images/smush-placeholder.png' ); ?>" />
									</span>
									</label></li>
							<?php endfor; ?>

							<?php foreach ( $settings['animation']['placeholder']['custom'] as $image ) : ?>
								<?php $custom_link = wp_get_attachment_image_src( $image, 'full' ); ?>
								<li><label for="placeholder-icon-<?php echo absint( $image ); ?>" class="sui-box-selector">
									<input type="radio" name="animation[placeholder-icon]" id="placeholder-icon-<?php echo absint( $image ); ?>" value="<?php echo absint( $image ); ?>" <?php checked( $image === $settings['animation']['placeholder']['selected'] ); ?> />
									<span>
										<button class="remove-selector sui-button-icon sui-tooltip smush-ll-remove" data-tooltip="<?php esc_attr_e( 'Remove', 'wp-smushit' ); ?>">
											<i class="sui-icon-close" aria-hidden="true" data-id="<?php echo absint( $image ); ?>" data-type="placeholder"></i>
										</button>
										<img alt="<?php esc_attr_e( 'Placeholder image', 'wp-smushit' ); ?>&nbsp;<?php echo absint( $image ); ?>" src="<?php echo esc_url( $custom_link[0] ); ?>" />
									</span>
									</label></li>
							<?php endforeach; ?>
						</ul>

						<div class="sui-upload">
							<input type="hidden" name="animation[custom-placeholder]" id="smush-placeholder-icon-file" value="" />

							<div class="sui-upload-image" aria-hidden="true">
								<div class="sui-image-mask"></div>
								<div role="button" class="sui-image-preview" id="smush-placeholder-icon-preview" onclick="WP_Smush.Lazyload.addLoaderIcon('placeholder')"></div>
							</div>

							<a class="sui-upload-button" id="smush-upload-placeholder" onclick="WP_Smush.Lazyload.addLoaderIcon('placeholder')">
								<i class="sui-icon-upload-cloud" aria-hidden="true"></i> <?php esc_html_e( 'Upload file', 'wp-smushit' ); ?>
							</a>

							<div class="sui-upload-file" id="smush-remove-placeholder">
								<span></span>
								<button aria-label="<?php esc_attr_e( 'Remove file', 'wp-smushit' ); ?>">
									<i class="sui-icon-close" aria-hidden="true"></i>
								</button>
							</div>
						</div>
					</div>

					<?php $color = isset( $settings['animation']['placeholder']['color'] ) ? $settings['animation']['placeholder']['color'] : '#F3F3F3'; ?>
					<label class="sui-label" for="smush-color-picker"><?php esc_html_e( 'Background color', 'wp-smushit' ); ?></label>
					<div class="sui-colorpicker-wrap">
						<div class="sui-colorpicker sui-colorpicker-hex" aria-hidden="true">
							<div class="sui-colorpicker-value">
								<span role="button">
									<span style="background-color: <?php echo esc_attr( $color ); ?>"></span>
								</span>
								<input type="text" value="<?php echo esc_attr( $color ); ?>" readonly="readonly" />
								<button><i class="sui-icon-close" aria-hidden="true"></i></button>
							</div>
							<button class="sui-button"><?php esc_html_e( 'Select', 'wp-smushit' ); ?></button>
						</div>
						<input type="text" name="animation[color]" value="<?php echo esc_attr( $color ); ?>" id="smush-color-picker" class="sui-colorpicker-input" data-attribute="<?php echo esc_attr( $color ); ?>" />
					</div>
				</div>

				<div class="sui-notice <?php echo ! $settings['animation']['selected'] ? 'active' : ''; ?>">
					<div class="sui-notice-content">
						<div class="sui-notice-message">
							<i class="sui-notice-icon sui-icon-info sui-md" aria-hidden="true"></i>
							<p><?php esc_html_e( 'Images will flash into view as soon as they are ready to display.', 'wp-smushit' ); ?></p>
						</div>
					</div>
				</div>
			</div><!-- end data-panes -->
		</div><!-- end .sui-tabs -->
	</div><!-- end .sui-box-settings-col-2 -->
	<script>
		jQuery(document).ready(function($){
			var $suiPickerInputs = $('#smush-color-picker');

			$suiPickerInputs.wpColorPicker({
				width: 300,
				change: function(event, ui) {
					$(this).val( ui.color.toCSS() ).trigger('change');
				}
			});

			if ( $suiPickerInputs.hasClass('wp-color-picker') ) {
				$suiPickerInputs.each( function() {
					var $suiPickerInput = $(this),
						$suiPicker      = $suiPickerInput.closest('.sui-colorpicker-wrap'),
						$suiPickerColor = $suiPicker.find('.sui-colorpicker-value span[role=button]'),
						$suiPickerValue = $suiPicker.find('.sui-colorpicker-value'),
						$wpPicker       = $suiPickerInput.closest('.wp-picker-container'),
						$wpPickerButton = $wpPicker.find('.wp-color-result');

					// Listen to color change
					$suiPickerInput.on('change', function() {
						// Change color preview
						$suiPickerColor.find('span').css({
							'background-color': $wpPickerButton.css('background-color')
						});

						// Change color value
						$suiPickerValue.find('input').val( $suiPickerInput.val() );
					});

					// Open iris picker
					$suiPicker.find('.sui-button, span[role=button]').on('click', function(e) {
						$wpPickerButton.trigger('click');

						e.preventDefault();
						e.stopPropagation();
					});

					// Clear color value
					$suiPickerValue.find('button').on( 'click', function(e) {
						e.preventDefault();

						$wpPicker.find('.wp-picker-clear').trigger('click');
						$suiPickerValue.find('input').val('');
						$suiPickerInput.val('').trigger('change');
						$suiPickerColor.find('span').css({
							'background-color': ''
						});

						e.preventDefault();
						e.stopPropagation();
					});

				});
			}
		});
	</script>
	<style>
		#smush-lazy-load-placeholder .sui-box-selector input + span,
		#smush-lazy-load-placeholder .sui-box-selector input:checked + span {
			background-color: <?php echo esc_attr( $color ); ?>;
		}
	</style>
</div><!-- end .sui-box-settings-row -->

<div class="sui-box-settings-row">
	<div class="sui-box-settings-col-1">
		<span class="sui-settings-label">
			<?php esc_html_e( 'Include/Exclude', 'wp-smushit' ); ?>
		</span>
		<span class="sui-description">
			<?php esc_html_e( 'Disable lazy loading for specific pages, posts or image classes that you wish to prevent lazyloading on.', 'wp-smushit' ); ?>
		</span>
	</div>
	<div class="sui-box-settings-col-2">
		<div class="sui-form-field">
			<strong><?php esc_html_e( 'Post Types', 'wp-smushit' ); ?></strong>
			<div class="sui-description">
				<?php esc_html_e( 'Choose the post types you want to lazy load.', 'wp-smushit' ); ?>
			</div>
			<table class="sui-table">
				<thead>
				<tr>
					<th><?php esc_html_e( 'Name', 'wp-smushit' ); ?></th>
					<th><?php esc_html_e( 'Type', 'wp-smushit' ); ?></th>
					<th>&nbsp;</th>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td><strong><?php esc_html_e( 'Frontpage', 'wp-smushit' ); ?></strong></td>
					<td>frontpage</td>
					<td>
						<label class="sui-toggle" for="include-frontpage">
							<input type='hidden' value='0' name='include[frontpage]' />
							<input type="checkbox" name="include[frontpage]" id="include-frontpage" <?php checked( $settings['include']['frontpage'] ); ?>>
							<span class="sui-toggle-slider"></span>
						</label>
					</td>
				</tr>
				<tr>
					<td><strong><?php esc_html_e( 'Blog', 'wp-smushit' ); ?></strong></td>
					<td>home</td>
					<td>
						<label class="sui-toggle" for="include-home">
							<input type='hidden' value='0' name='include[home]' />
							<input type="checkbox" name="include[home]" id="include-home" <?php checked( $settings['include']['home'] ); ?>>
							<span class="sui-toggle-slider"></span>
						</label>
					</td>
				</tr>
				<tr>
					<td><strong><?php esc_html_e( 'Pages', 'wp-smushit' ); ?></strong></td>
					<td>page</td>
					<td>
						<label class="sui-toggle" for="include-page">
							<input type='hidden' value='0' name='include[page]' />
							<input type="checkbox" name="include[page]" id="include-page" <?php checked( $settings['include']['page'] ); ?>>
							<span class="sui-toggle-slider"></span>
						</label>
					</td>
				</tr>
				<tr>
					<td><strong><?php esc_html_e( 'Posts', 'wp-smushit' ); ?></strong></td>
					<td>single</td>
					<td>
						<label class="sui-toggle" for="include-single">
							<input type='hidden' value='0' name='include[single]' />
							<input type="checkbox" name="include[single]" id="include-single" <?php checked( $settings['include']['single'] ); ?>>
							<span class="sui-toggle-slider"></span>
						</label>
					</td>
				</tr>
				<tr>
					<td><strong><?php esc_html_e( 'Archives', 'wp-smushit' ); ?></strong></td>
					<td>archive</td>
					<td>
						<label class="sui-toggle" for="include-archive">
							<input type='hidden' value='0' name='include[archive]' />
							<input type="checkbox" name="include[archive]" id="include-archive" <?php checked( $settings['include']['archive'] ); ?>>
							<span class="sui-toggle-slider"></span>
						</label>
					</td>
				</tr>
				<tr>
					<td><strong><?php esc_html_e( 'Categories', 'wp-smushit' ); ?></strong></td>
					<td>category</td>
					<td>
						<label class="sui-toggle" for="include-category">
							<input type='hidden' value='0' name='include[category]' />
							<input type="checkbox" name="include[category]" id="include-category" <?php checked( $settings['include']['category'] ); ?>>
							<span class="sui-toggle-slider"></span>
						</label>
					</td>
				</tr>
				<tr>
					<td><strong><?php esc_html_e( 'Tags', 'wp-smushit' ); ?></strong></td>
					<td>tag</td>
					<td>
						<label class="sui-toggle" for="include-tag">
							<input type='hidden' value='0' name='include[tag]' />
							<input type="checkbox" name="include[tag]" id="include-tag" <?php checked( $settings['include']['tag'] ); ?>>
							<span class="sui-toggle-slider"></span>
						</label>
					</td>
				</tr>
				<?php foreach ( $cpts  as $custom_post_type ) : ?>
					<tr>
						<td><strong><?php echo esc_html( $custom_post_type->label ); ?></strong></td>
						<td><?php echo esc_html( $custom_post_type->name ); ?></td>
						<td>
							<label class="sui-toggle" for="include-<?php echo esc_attr( $custom_post_type->name ); ?>">
								<input type='hidden' value='0' name='include[<?php echo esc_attr( $custom_post_type->name ); ?>]' />
								<input type="checkbox" name="include[<?php echo esc_attr( $custom_post_type->name ); ?>]" id="include-<?php echo esc_attr( $custom_post_type->name ); ?>"
									<?php checked( ! isset( $settings['include'][ $custom_post_type->name ] ) || $settings['include'][ $custom_post_type->name ] ); ?> />
								<span class="sui-toggle-slider"></span>
							</label>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>

		<div class="sui-form-field">
			<strong><?php esc_html_e( 'Post, Pages & URLs', 'wp-smushit' ); ?></strong>
			<div class="sui-description">
				<?php esc_html_e( 'Add URLs to the posts and/or pages you want to disable lazy loading on.', 'wp-smushit' ); ?>
			</div>
			<?php
			$strings = '';
			if ( is_array( $settings['exclude-pages'] ) ) {
				$strings = join( PHP_EOL, $settings['exclude-pages'] );
			}
			?>
			<textarea class="sui-form-control" name="exclude-pages" placeholder="<?php esc_attr_e( 'E.g. /page', 'wp-smushit' ); ?>"><?php echo esc_attr( $strings ); ?></textarea>
			<div class="sui-description">
				<?php
				printf(
					/* translators: %1$s - opening strong tag, %2$s - closing strong tag */
					esc_html__( 'Add page or post URLs one per line in relative format. I.e. %1$s/example-page%2$s or %1$s/example-page/sub-page/%2$s.', 'wp-smushit' ),
					'<strong>',
					'</strong>'
				);
				?>
			</div>
		</div>

		<div class="sui-form-field">
			<strong><?php esc_html_e( 'Classes & IDs', 'wp-smushit' ); ?></strong>
			<div class="sui-description">
				<?php esc_html_e( 'Additionally, you can specify classes or IDs to avoid lazy loading. This gives you absolute control over each image on a page, not just the page itself.', 'wp-smushit' ); ?>
			</div>
			<?php
			$strings = '';
			if ( is_array( $settings['exclude-classes'] ) ) {
				$strings = join( PHP_EOL, $settings['exclude-classes'] );
			}
			?>
			<textarea class="sui-form-control" name="exclude-classes" placeholder="<?php esc_attr_e( 'Add classes or IDs, one per line', 'wp-smushit' ); ?>"><?php echo esc_attr( $strings ); ?></textarea>
			<div class="sui-description">
				<?php
				printf(
					/* translators: %1$s - opening strong tag, %2$s - closing strong tag */
					esc_html__( 'Add one class or ID per line, including the prefix. E.g %1$s#image-id%2$s or %1$s.image-class%2$s.', 'wp-smushit' ),
					'<strong>',
					'</strong>'
				);
				?>
			</div>
		</div>
	</div>
</div>

<div class="sui-box-settings-row">
	<div class="sui-box-settings-col-1">
		<span class="sui-settings-label">
			<?php esc_html_e( 'Scripts', 'wp-smushit' ); ?>
		</span>
		<span class="sui-description">
			<?php esc_html_e( 'By default we will load the required scripts in your footer for max performance benefits. If you are having issues, you can switch this to the header.', 'wp-smushit' ); ?>
		</span>
	</div>
	<div class="sui-box-settings-col-2">
		<div class="sui-form-field">
			<strong><?php esc_attr_e( 'Method', 'wp-smushit' ); ?></strong>
			<div class="sui-description">
				<?php esc_html_e( 'By default we will load the required scripts in your footer for max performance benefits. If you are having issues, you can switch this to the header.', 'wp-smushit' ); ?>
			</div>

			<div class="sui-side-tabs sui-tabs">
				<div data-tabs>
					<label for="script-footer" class="sui-tab-item <?php echo $settings['footer'] ? 'active' : ''; ?>">
						<input type="radio" name="footer" value="on" id="script-footer" <?php checked( $settings['footer'] ); ?> />
						<?php esc_html_e( 'Footer', 'wp-smushit' ); ?>
					</label>

					<label for="script-header" class="sui-tab-item <?php echo $settings['footer'] ? '' : 'active'; ?>">
						<input type="radio" name="footer" value="off" id="script-header" <?php checked( $settings['footer'], false ); ?> />
						<?php esc_html_e( 'Header', 'wp-smushit' ); ?>
					</label>
				</div>

				<div data-panes>
					<div class="sui-notice active">
						<div class="sui-notice-content">
							<div class="sui-notice-message">
								<i class="sui-notice-icon sui-icon-info sui-md" aria-hidden="true"></i>
								<p><?php esc_html_e( 'Your theme must be using the wp_footer() function.', 'wp-smushit' ); ?></p>
							</div>
						</div>
					</div>
					<div class="sui-notice">
						<div class="sui-notice-content">
							<div class="sui-notice-message">
								<i class="sui-notice-icon sui-icon-info sui-md" aria-hidden="true"></i>
								<p><?php esc_html_e( 'Your theme must be using the wp_head() function.', 'wp-smushit' ); ?></p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="sui-box-settings-row">
	<div class="sui-box-settings-col-1">
		<span class="sui-settings-label">
			<?php esc_html_e( 'Native lazy load', 'wp-smushit' ); ?>
		</span>
		<span class="sui-description">
			<?php esc_html_e( 'Enable support for native browser lazy loading.', 'wp-smushit' ); ?>
		</span>
	</div>
	<div class="sui-box-settings-col-2">
		<div class="sui-form-field">
			<label for="native" class="sui-toggle">
				<input
					type="checkbox"
					id="native"
					name="native"
					aria-labelledby="native-label"
					aria-describedby="native-description"
					<?php checked( isset( $settings['native'] ) && $settings['native'] ); ?>
				/>
				<span class="sui-toggle-slider" aria-hidden="true"></span>
				<span id="native-label" class="sui-toggle-label">
					<?php esc_html_e( 'Enable native lazy loading', 'wp-smushit' ); ?>
				</span>
				<span id="native-description" class="sui-description">
					<?php
					printf(
						/* translators: %1$s - opening a tag, %2$s - closing a tag */
						esc_html__( 'In some cases can cause the "Defer offscreen images" Google PageSpeed audit to fail. See browser compatibility %1$shere%2$s.', 'wp-smushit' ),
						'<a href="https://caniuse.com/#feat=loading-lazy-attr" target="_blank">',
						'</a>'
					);
					?>
				</span>
			</label>
		</div>
	</div>
</div>

<div class="sui-box-settings-row">
	<div class="sui-box-settings-col-1">
		<span class="sui-settings-label">
			<?php esc_html_e( 'Disable Noscript', 'wp-smushit' ); ?>
		</span>
		<span class="sui-description">
			<?php esc_html_e( 'Disable NoScript while lazy loading is enabled.', 'wp-smushit' ); ?>
		</span>
	</div>

	<div class="sui-box-settings-col-2">
		<div class="sui-form-field">
			<label for="noscript" class="sui-toggle">
				<input
					type="checkbox"
					id="noscript"
					name="noscript"
					aria-labelledby="noscript-label"
					aria-describedby="noscript-description"
					<?php checked( isset( $settings['noscript'] ) && $settings['noscript'] ); ?>
				/>
				<span class="sui-toggle-slider" aria-hidden="true"></span>
				<span id="noscript-label" class="sui-toggle-label">
					<?php esc_html_e( 'Disable Noscript', 'wp-smushit' ); ?>
				</span>
				<span id="noscript-description" class="sui-description">
					<?php
						/* translators: %1$s - opening a tag, %2$s - closing a tag */
						esc_html_e( 'Sometimes W3C HTML5 Validation may give error due to No Script.', 'wp-smushit' );
					?>
				</span>
			</label>
		</div>
	</div>
</div>

<div class="sui-box-settings-row">
	<div class="sui-box-settings-col-1">
		<span class="sui-settings-label">
			<?php esc_html_e( 'Deactivate', 'wp-smushit' ); ?>
		</span>
		<span class="sui-description">
			<?php
			esc_html_e(
				'No longer wish to use this feature? Turn it off instantly by hitting Deactivate.',
				'wp-smushit'
			);
			?>
		</span>
	</div>
	<div class="sui-box-settings-col-2">
		<button class="sui-button sui-button-ghost" id="smush-cancel-lazyload">
			<span class="sui-loading-text">
				<i class="sui-icon-power-on-off" aria-hidden="true"></i>
				<?php esc_html_e( 'Deactivate', 'wp-smushit' ); ?>
			</span>
			<i class="sui-icon-loader sui-loading" aria-hidden="true"></i>
		</button>
	</div>
</div>
