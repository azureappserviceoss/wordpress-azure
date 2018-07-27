<?php
/**
 * The template for displaying the footer.
 * @package mahjong-org.azurewebsites.net
 * @subpackage mahjong
 * @since Mahjong-ca 1.0
 */
?>
	<div class="link_box">
  <div class="wrap">
   <h2 class="tt">Links</h2>
   <dl class="link_cont">
    <dt></dt>
    <dd>
    <?php if (function_exists(wpjam_blogroll)) wpjam_blogroll();?>
    </dd>
   </dl>
  <!--wrap end-->
  </div>
 <!--link_box end-->
 </div>
 <div class="footer">
  <div class="wrap">
  <p>CopyRight &copy; 2013 CANADA MCR SPORTS ASSOCIATION</p>
  <p>E-mail: <a href="mailto:gb.mahjong@hotmail.com">gb.mahjong@hotmail.com</a></p>
  </div>
 <!--footer end-->
 </div>

 <div class="wrap" style="display:none;"><div class="bm_btn"><div class="hand_p"><a href="http://www.mahjong-org.azurewebsites.net/training_apply/index.php?lang=en" target="_blank">Click Here!</a></div><a href="http://www.mahjong-org.azurewebsites.net/training_apply/index.php?lang=en" target="_blank">Enroll Online</a></div></div>

<script src="<?php bloginfo( 'template_url' ); ?>/js/lang.js" type="text/javascript"></script>
<?php wp_footer();?>
</body>
</html>
