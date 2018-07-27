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
   <h2 class="tt">相关链接</h2>
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
  <p>加拿大国标麻将协会官方网站  版权所有 &copy; 加拿大国标麻将协会</p>
  <p>E-mail: <a href="mailto:info@mahjong-org.azurewebsites.net">info@mahjong-org.azurewebsites.net</a></p>
  </div>
 <!--footer end-->
 </div>

 <div class="wrap" style="display:none;"><div class="bm_btn"><a href="/training_apply/">免费报名</a></div></div>

<script src="<?php bloginfo( 'template_url' ); ?>/js/lang.js" type="text/javascript"></script>
<?php wp_footer();?>
</body>
</html>
