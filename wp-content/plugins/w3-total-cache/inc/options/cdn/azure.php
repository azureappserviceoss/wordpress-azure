<?php
namespace W3TC;

if ( !defined( 'W3TC' ) )
	die();

?>
<tr>
	<th style="width: 300px;"><label for="cdn_azure_user"><?php _e( 'Account name:', 'w3-total-cache' ); ?></label></th>
	<td>
		<input id="cdn_azure_user" class="w3tc-ignore-change" type="text"
					   <?php Util_Ui::sealing_disabled( 'cdn.' ) ?> name="cdn__azure__user" value="<?php echo esc_attr( $this->_config->get_string( 'cdn.azure.user' ) ); ?>" size="30" />
	</td>
</tr>
<tr>
	<th><label for="cdn_azure_key"><?php _e( 'Account key:', 'w3-total-cache' ); ?></label></th>
	<td>
		<input id="cdn_azure_key" class="w3tc-ignore-change"
					   <?php Util_Ui::sealing_disabled( 'cdn.' ) ?> type="password" name="cdn__azure__key" value="<?php echo esc_attr( $this->_config->get_string( 'cdn.azure.key' ) ); ?>" size="60" />
	</td>
</tr>
<tr>
	<th><label for="cdn_azure_container"><?php _e( 'Container:', 'w3-total-cache' ); ?></label></th>
	<td>
		<input id="cdn_azure_container" type="text"
					   <?php Util_Ui::sealing_disabled( 'cdn.' ) ?> name="cdn__azure__container" value="<?php echo esc_attr( $this->_config->get_string( 'cdn.azure.container' ) ); ?>" size="30" />
		<input id="cdn_create_container" <?php Util_Ui::sealing_disabled( 'cdn.' ) ?> class="button {type: 'azure', nonce: '<?php echo wp_create_nonce( 'w3tc' ); ?>'}" type="button" value="<?php _e( 'Create container', 'w3-total-cache' ); ?>" />
		<span id="cdn_create_container_status" class="w3tc-status w3tc-process"></span>
	</td>
</tr>
<tr>
	<th><label for="cdn_azure_ssl"><?php _e( '<acronym title="Secure Sockets Layer">SSL</acronym> support:', 'w3-total-cache' ); ?></label></th>
	<td>
		<select id="cdn_azure_ssl" name="cdn__azure__ssl" <?php Util_Ui::sealing_disabled( 'cdn.' ) ?>>
			<option value="auto"<?php selected( $this->_config->get_string( 'cdn.azure.ssl' ), 'auto' ); ?>><?php _e( 'Auto (determine connection type automatically)', 'w3-total-cache' ); ?></option>
			<option value="enabled"<?php selected( $this->_config->get_string( 'cdn.azure.ssl' ), 'enabled' ); ?>><?php _e( 'Enabled (always use SSL)', 'w3-total-cache' ); ?></option>
			<option value="disabled"<?php selected( $this->_config->get_string( 'cdn.azure.ssl' ), 'disabled' ); ?>><?php _e( 'Disabled (always use HTTP)', 'w3-total-cache' ); ?></option>
		</select>
		<p class="description"><?php _e( 'Some <acronym title="Content Delivery Network">CDN</acronym> providers may or may not support <acronym title="Secure Sockets Layer">SSL</acronym>, contact your vendor for more information.', 'w3-total-cache' ); ?></p>
	</td>
</tr>
<tr>
	<th><?php _e( 'Replace site\'s hostname with:', 'w3-total-cache' ); ?></th>
	<td>
		<?php if ( ( $cdn_azure_user = $this->_config->get_string( 'cdn.azure.user' ) ) != '' ): ?>
			<?php echo esc_attr( $cdn_azure_user ); ?>.blob.core.windows.net
		<?php else: ?>
			&lt;account name&gt;.blob.core.windows.net
		<?php endif; ?> <?php _e( 'or <acronym title="Canonical Name">CNAME</acronym>:', 'w3-total-cache' ); ?>
		<?php $cnames = $this->_config->get_array( 'cdn.azure.cname' ); include W3TC_INC_DIR . '/options/cdn/common/cnames.php'; ?>
	</td>
</tr>
<tr>
	<th colspan="2">
		<input id="cdn_test" class="button {type: 'azure', nonce: '<?php echo wp_create_nonce( 'w3tc' ); ?>'}" type="button" value="<?php _e( 'Test Microsoft Azure Storage upload', 'w3-total-cache' ); ?>" /> <span id="cdn_test_status" class="w3tc-status w3tc-process"></span>
	</th>
</tr>
