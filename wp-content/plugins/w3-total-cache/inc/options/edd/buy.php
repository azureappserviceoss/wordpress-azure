<?php
namespace W3TC;

if ( !defined( 'W3TC' ) )
	die();

?>
<div>
	<?php printf( __( 'Unlock more speed, %s now!', 'w3-total-cache' ),
	'<input type="button" class="button-primary button-buy-plugin" data-src="' . esc_attr( 'page_' . $page ) .
	'" value="' . __( 'upgrade', 'w3-total-cache' ) . '" />' ) ?>
	<div id="w3tc-license-instruction" style="display: none;">
	<p class="description"><?php printf( __( 'Please enter the license key you received after successful checkout %s.', 'w3-total-cache' ),
	'<a href="' . network_admin_url( 'admin.php?page=w3tc_general#licensing' ) .'">' . __( 'here', 'w3-total-cache' ) . '</a>' )
?></p>
	</div>
</div>
