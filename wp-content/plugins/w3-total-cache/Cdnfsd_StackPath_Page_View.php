<?php
namespace W3TC;

if ( !defined( 'W3TC' ) )
	die();

$key = $config->get_string( 'cdnfsd.stackpath.api_key' );
$authorized = !empty( $key );

?>
<form id="cdn_form" action="admin.php?page=w3tc_cdn" method="post">
	<div class="metabox-holder">
		<?php Util_Ui::postbox_header( __( 'Configuration: Full-Site Delivery', 'w3-total-cache' ),
	'', 'configuration' ); ?>
		<table class="form-table">
			<tr>
				<th style="width: 300px;">
					<label>
						<?php
_e( 'Specify account credentials:',
	'w3-total-cache' );
?>
					</label>
				</th>
				<td>
					<?php if ( $authorized ): ?>
						<input class="w3tc_cdn_stackpath_fsd_authorize button-primary"
							type="button"
							value="<?php _e( 'Reauthorize', 'w3-total-cache' ); ?>"
							/>
					<?php else: ?>
						<input class="w3tc_cdn_stackpath_fsd_authorize button-primary"
							type="button"
							value="<?php _e( 'Authorize', 'w3-total-cache' ); ?>"
							/>
					<?php endif ?>
				</td>
			</tr>

			<?php if ( $authorized ): ?>
			<tr>
				<th>
					<label><?php _e( '<acronym title="Content Delivery Network">CDN</acronym> <acronym title="Canonical Name">CNAME</acronym>:', 'w3-total-cache' ); ?></label>
				</th>
				<td class="w3tc_config_value_text">
					<?php
echo $config->get_string( 'cdnfsd.stackpath.zone_domain' )
?>
					<p class="description">
						The website domain must <acronym title="Canonical Name">CNAME</acronym> point to the
						<acronym title="Content Delivery Network">CDN</acronym> domain
					</p>
				</td>
			</tr>
			<?php endif ?>
		</table>

		<?php Util_Ui::button_config_save( 'cdn_configuration' ); ?>
		<?php Util_Ui::postbox_footer(); ?>
	</div>
</form>
