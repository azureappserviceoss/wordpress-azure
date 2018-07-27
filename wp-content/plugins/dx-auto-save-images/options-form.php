<style type="text/css">
#dx-auto-save-images{ width:650px; margin:20px 0;border:1px solid #ddd; background-color:#f7f7f7; padding:10px; }
#dx-auto-save-images span.des{ color:#999; margin-left:10px; }
#dx-auto-save-images label{ width:100px; display:inline-block }
</style>

<div class="wrap">

	<div id="icon-options-general" class="icon32"><br></div><h2>DX-auto-save-images 选项</h2>

	<div id="dx-auto-save-images">
		<form action="" method="post">
			<p><label for="tmb">禁止缩略图：</label><input type="checkbox" id="tmb" name="tmb" value="yes" <?php checked( 'yes', $options['tmb'] );?>/> 是</p>
            <p><label for="chinese">支持中文图片：</label><input type="checkbox" id="chinese" name="chinese" value="yes" <?php checked( 'yes', $options['chinese'] );?>/> 是 <span class="des">(勾选则对图片重命名)</span></p>
            <p><label for="switch">独立开关：</label><input type="checkbox" id="switch" name="switch" value="yes" <?php checked( 'yes', $options['switch'] );?>/> 是 <span class="des">(勾选则可在文章页选择是否保存远程图片)</span></p>
            <p><label for="post-tmb">自动特色图像：</label><input type="checkbox" id="post-tmb" name="post-tmb" value="yes" <?php checked( 'yes', $options['post-tmb'] );?>/> 是 <span class="des">(勾选则自动添加特色图像，需要主题支持特色图像功能。)</span></p>
		<?php submit_button(); ?>
		</form>	
	</div>
	
	<div style="clear:both;"></div>
	
	<?php $this->form_bottom(); ?>

</div>