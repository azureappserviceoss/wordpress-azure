<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); hookscriptoutput('spacecp_index');
0
|| checktplrefresh('./template/mahjong/home/spacecp_index.htm', './template/mahjong/common/seditor.htm', 1498570812, '2', './data/template/4_2_home_spacecp_index.tpl.php', './template/mahjong', 'home/spacecp_index')
|| checktplrefresh('./template/mahjong/home/spacecp_index.htm', './template/mahjong/home/space_header_personalnv.htm', 1498570812, '2', './data/template/4_2_home_spacecp_index.tpl.php', './template/mahjong', 'home/spacecp_index')
;?><?php include template('common/header'); if($_GET['op'] == 'start') { ?>
<ul id="contentstart" class="content">
<li><a href="javascript:;" onclick="spaceDiy.getdiy('layout');return false;"><img src="<?php echo STATICURL;?>image/diy/layout.png" />版式</a></li>
<li><a href="javascript:;" onclick="spaceDiy.getdiy('style');return false;"><img src="<?php echo STATICURL;?>image/diy/style.png" />风格</a></li>
<li><a href="javascript:;" onclick="spaceDiy.getdiy('block');return false;"><img src="<?php echo STATICURL;?>image/diy/module.png" />添加模块</a></li>
<li><a href="javascript:;" onclick="spaceDiy.getdiy('diy', 'topicid', '<?php echo $topic['topicid'];?>');return false;"><img src="<?php echo STATICURL;?>image/diy/diy.png" />自定义</a></li>
</ul>
<?php } elseif($_GET['op'] == 'layout') { ?>
<ul id="contentframe" class="content selector"><?php if(is_array($layoutarr)) foreach($layoutarr as $key => $value) { $widthstr = implode(' ',$value);?><li id="layout<?php echo $key;?>" data="<?php echo $widthstr;?>"><a href="javascript:;" onclick="spaceDiy.changeLayout('<?php echo $key;?>');this.blur();return false;"><?php echo $key;?></a></li>
<?php } ?>
</ul>

<?php } elseif($_GET['op'] == 'style') { ?>
<ul class="content" style="overflow-y: auto; height: 90px;"><?php if(is_array($themes)) foreach($themes as $value) { ?>  <li><a href="javascript:;" onclick="spaceDiy.changeStyle('<?php echo $value['dir'];?>');this.blur();return false;"><img src="<?php echo STATICURL;?><?php echo $value['dir'];?>/preview.jpg" /><?php echo $value['name'];?></a></li>
<?php } ?>
</ul>
<?php } elseif($_GET['op'] == 'block') { ?>
<ul class="blocks content selector"><?php if(is_array($block)) foreach($block as $key => $value) { if(check_ban_block($key, $space)) { ?>
<li id="chk<?php echo $key;?>"><a href="javascript:;" onclick="drag.toggleBlock('<?php echo $key;?>');this.blur();return false;"><?php echo $value;?></a></li>
<?php } } ?>
</ul>
<?php } elseif($_GET['op'] == 'image') { $friendsname = array(1 => '仅好友可见',2 => '指定好友可见',3 => '仅自己可见',4 => '凭密码可见');?><div id="diyimg_prev" class="z"><?php echo $multi;?></div>
<ul id="imagebody"><?php if(is_array($list)) foreach($list as $key => $value) { ?><li class="thumb"><a href="javascript:;" onclick="return false;"><img src="<?php echo $value['pic'];?>" alt="" onclick="spaceDiy.setBgImage(this);"/></a></li>
<?php } ?>
</ul>
<div id="diyimg_next" class="z"><?php echo $multi;?></div>
<?php if($albumlist[$albumid]['friend']) { ?>
<script type="text/javascript">showDialog('该相册中的图片<?php echo $friendsname[$albumlist[$albumid]['friend']];?>','alert');</script>
<?php } } elseif($_GET['op'] == 'diy') { ?>
<dl class='diy'>
<dt class="cl pns">
<div class="y">
<button type="button" id="uploadmsg_button" onclick="Util.toggleEle('upload');" class="pn pnc z <?php if(!$list) { ?> hide<?php } ?>"><span>上传新图片</span></button>
<div id="upload" class="z<?php if($list) { ?> hide<?php } ?>"><iframe id="uploadframe" name="uploadframe" width="0" height="0" marginwidth="0" frameborder="0" src="about:blank"></iframe>
<form method="post" autocomplete="off" name="uploadpic" id="uploadpic" action="home.php?mod=spacecp&amp;ac=index" enctype="multipart/form-data" target="uploadframe" onsubmit="return spaceDiy.uploadSubmit();">
<input type="file" class="t_input" name="attach" size="15">
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
<input type="hidden" name="albumid" value="<?php echo $albumid;?>" />
<button type="submit" name="uploadsubmit" id="btnupload" class="pn" value="true"><span>开始上传</span></button>
</form>
</div>
<span id="uploadmsg" class="z"></span>
</div>
<span style="margin-right: 40px;">
<select name="selectalbum" id="selectalbum" onchange="spaceDiy.getdiy('image', 'albumid', this.value);"><?php if(is_array($albumlist)) foreach($albumlist as $album) { ?><option value="<?php echo $album['albumid'];?>" <?php echo $album[albumid] == $albumid ? 'selected' : '';?> ><?php echo $album['albumname'];?> - (<?php echo $album['picnum'];?> 张)</option>
<?php } ?>
</select>
</span>
<span>正在编辑:</span>
<a id="diy_tag_body" href="javascript:;" onclick="spaceDiy.setCurrentDiy('body');return false;">背景</a>
<span class="pipe">|</span><a id="diy_tag_hd" href="javascript:;" onclick="spaceDiy.setCurrentDiy('hd');return false;">头部</a>
<span class="pipe">|</span><a id="diy_tag_blocktitle" href="javascript:;" onclick="spaceDiy.setCurrentDiy('blocktitle');return false;">标题栏</a>
<span class="pipe">|</span><a id="diy_tag_ct" href="javascript:;" onclick="spaceDiy.setCurrentDiy('ct');return false;">内容区</a>

<a style="margin-left: 40px;" id="bg_button" href="javascript:;" onclick="spaceDiy.hideBg();return false;">取消背景图</a>
<span class="pipe">|</span><a id="recover_button" href="javascript:;" onclick="spaceDiy.recoverStyle();return false;">恢复原装皮肤</a>
</dt>
<dd>
<div class="photo_list cl">
<div id="currentimgdiv" class="z" style="width:446px;">
<center><ul><li class="thumb" style="border:1px solid #ccc; padding:2px;"><img id="currentimg" alt="" src=""/></li></ul>
<div class="z cur1" onclick="spaceDiy.changeBgImgDiv();">更换</div></center>
</div>
<div id="diyimages" class="z" style="width:446px;display:none;">
<div id="diyimg_prev" class="z"><?php echo $multi;?></div>
<ul id="imagebody"><?php if(is_array($list)) foreach($list as $key => $value) { ?><li class="thumb"><a href="javascript:;" ><img src="<?php echo $value['pic'];?>" alt="" onclick="spaceDiy.setBgImage(this);return false;"/></a></li>
<?php } ?>
</ul>
<div id="diyimg_next" class="z"><?php echo $multi;?></div>
</div>
<div class="z" style="padding-left: 7px; width: 160px; border: solid #CCC; border-width: 0 1px;">
<table cellpadding="0" cellspacing="0">
<tr>
<td><label for="repeat_mode">图片平铺:</label></td>
<td>
<select id="repeat_mode" name="repeat_mode" onclick="spaceDiy.setBgRepeat(this.value);">
<option value="0" selected="selected">平铺</option>
<option value="1">直接使用</option>
<option value="2">横向平铺</option>
<option value="3">纵向平铺</option>
</select>
</td>
</tr>
<tr>
<td>图片位置:</td>
<td>
<table cellpadding="0" cellspacing="0" id="positiontable">
<tr>
<td id="bgimgposition0" onclick="spaceDiy.setBgPosition(this.id)">&nbsp;</td>
<td id="bgimgposition1" onclick="spaceDiy.setBgPosition(this.id)">&nbsp;</td>
<td id="bgimgposition2" onclick="spaceDiy.setBgPosition(this.id)">&nbsp;</td>
</tr>
<tr>
<td id="bgimgposition3" onclick="spaceDiy.setBgPosition(this.id)">&nbsp;</td>
<td id="bgimgposition4" onclick="spaceDiy.setBgPosition(this.id)">&nbsp;</td>
<td id="bgimgposition5" onclick="spaceDiy.setBgPosition(this.id)">&nbsp;</td>
</tr>
<tr>
<td id="bgimgposition6" onclick="spaceDiy.setBgPosition(this.id)">&nbsp;</td>
<td id="bgimgposition7" onclick="spaceDiy.setBgPosition(this.id)">&nbsp;</td>
<td id="bgimgposition8" onclick="spaceDiy.setBgPosition(this.id)">&nbsp;</td>
</tr>
</table>
</td>
</tr>
</table>
</div>
<div class="z diywin" style="padding-left: 7px; width: 160px;">
<table cellpadding="0" cellspacing="0">
<tr>
<td>背景滚动:</td>
<td>
<label for="rabga0"><input type="radio" id="rabga0" name="attachment_mode" onclick="spaceDiy.setBgAttachment(0);" class="pr" />滚动</label>
<label for="rabga1"><input type="radio" id="rabga1" name="attachment_mode" onclick="spaceDiy.setBgAttachment(1);" class="pr" />固定</label>
</td>
</tr>
<tr>
<td>背景颜色:</td>
<td><input type="text" id="colorValue" value="" size="6" onchange="spaceDiy.setBgColor(this.value);" class="px vm" style="font-size: 12px; padding: 2px;" />
<input id="cbpb" onclick="createPalette('bpb', 'colorValue', 'spaceDiy.setBgColor');" type="button" class="pn colorwd" value="" />
</td>
</tr>
</table>
</div>
<div class="z diywin" style="padding-left: 7px; width: 160px;">
<table cellpadding="0" cellspacing="0">
<tr>
<td>文字颜色:</td>
<td><input type="text" id="textColorValue" value="" size="6" onchange="spaceDiy.setTextColor(this.value);" class="px vm" style="font-size: 12px; padding: 2px;" />
<input id="ctpb" onclick="createPalette('tpb', 'textColorValue', 'spaceDiy.setTextColor');" type="button" class="pn colorwd" value="" />
</td>
</tr>
<tr>
<td>链接颜色:</td>
<td><input type="text" id="linkColorValue" value="" size="6" onchange="spaceDiy.setLinkColor(this.value);" class="px vm" style="font-size: 12px; padding: 2px;" />
<input id="clpb" onclick="createPalette('lpb', 'linkColorValue', 'spaceDiy.setLinkColor');" type="button" class="pn colorwd" value="" />
</td>
</tr>
</table>
</div>
</div>
</dd>
</dl>
<?php } elseif($_GET['op'] == 'getblock') { ?>
<?php echo $blockhtml;?>
<?php } elseif($_GET['op'] == 'editnv') { ?>
<h3 class="flb">
<em id="return_<?php echo $_GET['handlekey'];?>">编辑导航菜单名称</em>
<span>
<?php if($_G['inajax']) { ?><a href="javascript:;" class="flbc" onclick="hideWindow('<?php echo $_GET['handlekey'];?>');return false;" title="关闭">关闭</a><?php } ?>
</span>
</h3>
<form id="nvformsetting" name="nvformsetting" method="post" autocomplete="off" action="home.php?mod=spacecp&amp;ac=index" onsubmit="ajaxpost('nvformsetting','return_<?php echo $_GET['handlekey'];?>','return_<?php echo $_GET['handlekey'];?>','onerror');" class="fdiy">
<div class="c diywin" style="max-height:350px;width:420px;height:auto !important;height:320px;_margin-right:20px;overflow-y:auto;">
<div id="nv_setting">
<table class="tfm">
<tr>
<th>隐藏导航</th>
<td>
<label><input type="radio" name="nvhidden" value="1"<?php if($personalnv['nvhidden'] == '1') { ?> checked="checked"<?php } ?>>是</label>
<label><input type="radio" name="nvhidden" value="0"<?php if(empty($personalnv['nvhidden'])) { ?> checked="checked"<?php } ?>>否</label>
</td>
</tr>
<tr>
<th>空间首页</th>
<td>
<input type="text" name="index" value="<?php if(!isset($personalnv['items']['index'])) { ?>空间首页<?php } else { ?><?php echo $personalnv['items']['index'];?><?php } ?>" class="px" />
<label><input type="radio" name="banindex" value="1"<?php if(!empty($personalnv['banitems']['index'])) { ?> checked="checked"<?php } ?>>隐藏</label>
<label><input type="radio" name="banindex" value="0"<?php if(empty($personalnv['banitems']['index'])) { ?> checked="checked"<?php } ?>>显示</label>
</td>
</tr>
<tr>
<th>动态</th>
<td>
<input type="text" name="feed" value="<?php if(!isset($personalnv['items']['feed'])) { ?>动态<?php } else { ?><?php echo $personalnv['items']['feed'];?><?php } ?>" class="px" />
<label><input type="radio" name="banfeed" value="1"<?php if(!empty($personalnv['banitems']['feed'])) { ?> checked="checked"<?php } ?>>隐藏</label>
<label><input type="radio" name="banfeed" value="0"<?php if(empty($personalnv['banitems']['feed'])) { ?> checked="checked"<?php } ?>>显示</label>
</td>
</tr>
<tr>
<th>记录</th>
<td>
<input type="text" name="doing" value="<?php if(!isset($personalnv['items']['doing'])) { ?>记录<?php } else { ?><?php echo $personalnv['items']['doing'];?><?php } ?>" class="px" />
<label><input type="radio" name="bandoing" value="1"<?php if(!empty($personalnv['banitems']['doing'])) { ?> checked="checked"<?php } ?>>隐藏</label>
<label><input type="radio" name="bandoing" value="0"<?php if(empty($personalnv['banitems']['doing'])) { ?> checked="checked"<?php } ?>>显示</label>
</td>
</tr>
<tr>
<th>日志</th>
<td>
<input type="text" name="blog" value="<?php if(!isset($personalnv['items']['blog'])) { ?>日志<?php } else { ?><?php echo $personalnv['items']['blog'];?><?php } ?>" class="px" />
<label><input type="radio" name="banblog" value="1"<?php if(!empty($personalnv['banitems']['blog'])) { ?> checked="checked"<?php } ?>>隐藏</label>
<label><input type="radio" name="banblog" value="0"<?php if(empty($personalnv['banitems']['blog'])) { ?> checked="checked"<?php } ?>>显示</label>
</td>
</tr>
<tr>
<th>相册</th>
<td>
<input type="text" name="album" value="<?php if(!isset($personalnv['items']['album'])) { ?>相册<?php } else { ?><?php echo $personalnv['items']['album'];?><?php } ?>" class="px" />
<label><input type="radio" name="banalbum" value="1"<?php if(!empty($personalnv['banitems']['album'])) { ?> checked="checked"<?php } ?>>隐藏</label>
<label><input type="radio" name="banalbum" value="0"<?php if(empty($personalnv['banitems']['album'])) { ?> checked="checked"<?php } ?>>显示</label>
</td>
</tr>
<tr>
<th>广播</th>
<td>
<input type="text" name="follow" value="<?php if(!isset($personalnv['items']['follow'])) { ?>广播<?php } else { ?><?php echo $personalnv['items']['follow'];?><?php } ?>" class="px" />
<label><input type="radio" name="banfollow" value="1"<?php if(!empty($personalnv['banitems']['follow'])) { ?> checked="checked"<?php } ?>>隐藏</label>
<label><input type="radio" name="banfollow" value="0"<?php if(empty($personalnv['banitems']['follow'])) { ?> checked="checked"<?php } ?>>显示</label>
</td>
</tr>
<?php if($_G['setting']['allowviewuserthread'] !== false) { ?>
<tr>
<th>主题</th>
<td>
<input type="text" name="topic" value="<?php if(!isset($personalnv['items']['topic'])) { ?>主题<?php } else { ?><?php echo $personalnv['items']['topic'];?><?php } ?>" class="px" />
<label><input type="radio" name="bantopic" value="1"<?php if(!empty($personalnv['banitems']['topic'])) { ?> checked="checked"<?php } ?>>隐藏</label>
<label><input type="radio" name="bantopic" value="0"<?php if(empty($personalnv['banitems']['topic'])) { ?> checked="checked"<?php } ?>>显示</label>
</td>
</tr>
<?php } ?>
<tr>
<th>分享</th>
<td>
<input type="text" name="share" value="<?php if(!isset($personalnv['items']['share'])) { ?>分享<?php } else { ?><?php echo $personalnv['items']['share'];?><?php } ?>" class="px" />
<label><input type="radio" name="banshare" value="1"<?php if(!empty($personalnv['banitems']['share'])) { ?> checked="checked"<?php } ?>>隐藏</label>
<label><input type="radio" name="banshare" value="0"<?php if(empty($personalnv['banitems']['share'])) { ?> checked="checked"<?php } ?>>显示</label>
</td>
</tr>
<tr>
<th>好友</th>
<td>
<input type="text" name="friends" value="<?php if(!isset($personalnv['items']['friends'])) { ?>好友<?php } else { ?><?php echo $personalnv['items']['friends'];?><?php } ?>" class="px" />
<label><input type="radio" name="banfriends" value="1"<?php if(!empty($personalnv['banitems']['friends'])) { ?> checked="checked"<?php } ?>>隐藏</label>
<label><input type="radio" name="banfriends" value="0"<?php if(empty($personalnv['banitems']['friends'])) { ?> checked="checked"<?php } ?>>显示</label>
</td>
</tr>
<tr>
<th>留言板</th>
<td>
<input type="text" name="wall" value="<?php if(!isset($personalnv['items']['wall'])) { ?>留言板<?php } else { ?><?php echo $personalnv['items']['wall'];?><?php } ?>" class="px" />
<label><input type="radio" name="banwall" value="1"<?php if(!empty($personalnv['banitems']['wall'])) { ?> checked="checked"<?php } ?>>隐藏</label>
<label><input type="radio" name="banwall" value="0"<?php if(empty($personalnv['banitems']['wall'])) { ?> checked="checked"<?php } ?>>显示</label>
</td>
</tr>
<tr>
<th>个人资料</th>
<td>
<input type="text" name="profile" value="<?php if(!isset($personalnv['items']['profile'])) { ?>个人资料<?php } else { ?><?php echo $personalnv['items']['profile'];?><?php } ?>" class="px" />
<label><input type="radio" name="banprofile" value="1"<?php if(!empty($personalnv['banitems']['profile'])) { ?> checked="checked"<?php } ?>>隐藏</label>
<label><input type="radio" name="banprofile" value="0"<?php if(empty($personalnv['banitems']['profile'])) { ?> checked="checked"<?php } ?>>显示</label>
</td>
</tr>
</table>
</div>
</div>
<div class="o pns">
<input type="hidden" name="editnvsubmit" value="true" />
<input type="hidden" name="handlekey" value="<?php echo $_GET['handlekey'];?>" />
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
<button type="submit" class="pn pnc" id="editnvsubmitbtn"><strong>确定</strong></button>
</div>
</form>
<script type="text/javascript" reload="1">
function succeedhandle_<?php echo $_GET['handlekey'];?> (url, message, values) {
spaceDiy.getPersonalNv();
hideWindow('<?php echo $_GET['handlekey'];?>');}
</script>
<?php } elseif($_GET['op'] == 'edit') { ?>
<h3 class="flb">
<em id="return_<?php echo $_GET['handlekey'];?>">编辑模块</em>
<span>
<?php if($_G['inajax']) { ?><a href="javascript:;" class="flbc" onclick="spaceDiy.delIframe();hideWindow('<?php echo $_GET['handlekey'];?>');return false;" title="关闭">关闭</a><?php } ?>
</span>
</h3>
<?php if(($blockname != 'music')) { ?>
<form id="blockformsetting" name="blockformsetting" method="post" autocomplete="off" action="home.php?mod=spacecp&amp;ac=index&amp;blockname=<?php echo $blockname;?>" onsubmit="ajaxpost('blockformsetting','return_<?php echo $_GET['handlekey'];?>','return_<?php echo $_GET['handlekey'];?>','onerror');" class="fdiy">
<div class="c diywin" style="max-height:350px;width:420px;height:auto !important;height:320px;_margin-right:20px;overflow-y:auto;">
<div id="block_setting">
<table class="tfm">
<tr>
<th>模块名称</th>
<td><input type="text" name="blocktitle" value="<?php echo $para['title'];?>" class="px" /></td>
</tr>
<?php if(($blockname == 'profile')) { $para['banavatar'] = empty($para['banavatar']) ? 'middle' : $para['banavatar'];?><tr>
<th>头像大小</th>
<td>
<label><input type="radio" name="avatar" value="big"<?php if($para['banavatar'] == 'big') { ?> checked="checked"<?php } ?>>大</label>
<label><input type="radio" name="avatar" value="middle"<?php if($para['banavatar'] == 'middle') { ?> checked="checked"<?php } ?>>中</label>
<label><input type="radio" name="avatar" value="small"<?php if($para['banavatar'] == 'small') { ?> checked="checked"<?php } ?>>小</label>
</td>
</tr>

<?php } elseif(($blockname == 'statistic')) { ?>
<tr>
<th>显示统计内容</th>
<td>
<label><input type="checkbox" name="credits" value="1" class="px"<?php if(empty($para['bancredits'])) { ?> checked="checked"<?php } ?> />积分</label>
<label><input type="checkbox" name="friends" value="1" class="px"<?php if(empty($para['banfriends'])) { ?> checked="checked"<?php } ?> />好友数</label>
<label><input type="checkbox" name="threads" value="1" class="px"<?php if(empty($para['banthreads'])) { ?> checked="checked"<?php } ?> />主题数</label>
<label><input type="checkbox" name="blogs" value="1" class="px"<?php if(empty($para['banblogs'])) { ?> checked="checked"<?php } ?> />日志数</label>
<label><input type="checkbox" name="albums" value="1" class="px"<?php if(empty($para['banalbums'])) { ?> checked="checked"<?php } ?> />相册数</label>
<label><input type="checkbox" name="sharings" value="1" class="px"<?php if(empty($para['bansharings'])) { ?> checked="checked"<?php } ?> />分享数</label>
<label><input type="checkbox" name="views" value="1" class="px"<?php if(empty($para['banviews'])) { ?> checked="checked"<?php } ?> />空间查看数</label>
</td>
</tr>
<?php } elseif(in_array($blockname, array('block1', 'block2', 'block3', 'block4', 'block5'))) { ?>
<tr><?php $msg .= $_G['group']['allowspacediyhtml'] ? 'HTML ' : ''?><?php $msg .= $_G['group']['allowspacediybbcode'] ? 'BBCODE ' : ''?><?php $msg .= $_G['group']['allowspacediyimgcode'] ? 'IMG ' : ''?><?php $msg = $msg ? lang('spacecp', 'spacecp_message_prompt', array('msg' => $msg)) : ''?><?php $para['content'] = dhtmlspecialchars($para['content']);?><th>自定义内容<br><span style=" font-weight: 400; "><?php echo $msg;?></span></th>
<td>
<div class="tedt">
<div class="bar"><?php $editicons = array();?><?php if($_G['group']['allowspacediybbcode']) $editicons = array('bold', 'color', 'link', 'quote', 'code', 'smilies');?><?php if($_G['group']['allowspacediyimgcode']) $editicons[] = 'img';?><?php $seditor = array('content', $editicons);?><script src="<?php echo $_G['setting']['jspath'];?>seditor.js?<?php echo VERHASH;?>" type="text/javascript"></script>
<div class="fpd">
<?php if(in_array('bold', $seditor['1'])) { ?>
<a href="javascript:;" title="文字加粗" class="fbld"<?php if(empty($seditor['2'])) { ?> onclick="seditor_insertunit('<?php echo $seditor['0'];?>', '[b]', '[/b]');doane(event);"<?php } ?>>B</a>
<?php } if(in_array('color', $seditor['1'])) { ?>
<a href="javascript:;" title="设置文字颜色" class="fclr" id="<?php echo $seditor['0'];?>forecolor"<?php if(empty($seditor['2'])) { ?> onclick="showColorBox(this.id, 2, '<?php echo $seditor['0'];?>');doane(event);"<?php } ?>>Color</a>
<?php } if(in_array('img', $seditor['1'])) { ?>
<a id="<?php echo $seditor['0'];?>img" href="javascript:;" title="图片" class="fmg"<?php if(empty($seditor['2'])) { ?> onclick="seditor_menu('<?php echo $seditor['0'];?>', 'img');doane(event);"<?php } ?>>Image</a>
<?php } if(in_array('link', $seditor['1'])) { ?>
<a id="<?php echo $seditor['0'];?>url" href="javascript:;" title="添加链接" class="flnk"<?php if(empty($seditor['2'])) { ?> onclick="seditor_menu('<?php echo $seditor['0'];?>', 'url');doane(event);"<?php } ?>>Link</a>
<?php } if(in_array('quote', $seditor['1'])) { ?>
<a id="<?php echo $seditor['0'];?>quote" href="javascript:;" title="引用" class="fqt"<?php if(empty($seditor['2'])) { ?> onclick="seditor_menu('<?php echo $seditor['0'];?>', 'quote');doane(event);"<?php } ?>>Quote</a>
<?php } if(in_array('code', $seditor['1'])) { ?>
<a id="<?php echo $seditor['0'];?>code" href="javascript:;" title="代码" class="fcd"<?php if(empty($seditor['2'])) { ?> onclick="seditor_menu('<?php echo $seditor['0'];?>', 'code');doane(event);"<?php } ?>>Code</a>
<?php } if(in_array('smilies', $seditor['1'])) { ?>
<a href="javascript:;" class="fsml" id="<?php echo $seditor['0'];?>sml"<?php if(empty($seditor['2'])) { ?> onclick="showMenu({'ctrlid':this.id,'evt':'click','layer':2});return false;"<?php } ?>>Smilies</a>
<?php if(empty($seditor['2'])) { ?>
<script type="text/javascript" reload="1">smilies_show('<?php echo $seditor['0'];?>smiliesdiv', <?php echo $_G['setting']['smcols'];?>, '<?php echo $seditor['0'];?>');</script>
<?php } } if(in_array('at', $seditor['1']) && $_G['group']['allowat']) { ?>
<script src="<?php echo $_G['setting']['jspath'];?>at.js?<?php echo VERHASH;?>" type="text/javascript"></script>
<a id="<?php echo $seditor['0'];?>at" href="javascript:;" title="@朋友" class="fat"<?php if(empty($seditor['2'])) { ?> onclick="seditor_menu('<?php echo $seditor['0'];?>', 'at');doane(event);"<?php } ?>>@朋友</a>
<?php } ?>
<?php echo $seditor['3'];?>
</div></div>
<div class="area">
<textarea name="content" id="contentmessage" style="width: 100%;"cols="40" rows="3" class="pt" onkeydown="ctrlEnter(event, 'blocksubmitbtn');"><?php echo $para['content'];?></textarea>
</div>
</div>
<script src="<?php echo $_G['setting']['jspath'];?>bbcode.js?<?php echo VERHASH;?>" type="text/javascript"></script>
<script type="text/javascript">var forumallowhtml = 0,allowhtml = parseInt('<?php echo $_G['group']['allowspacediyhtml'];?>'),allowsmilies = 0,allowbbcode = parseInt('<?php echo $_G['group']['allowspacediybbcode'];?>'),allowimgcode = parseInt('<?php echo $_G['group']['allowspacediyimgcode'];?>');var DISCUZCODE = [];DISCUZCODE['num'] = '-1';DISCUZCODE['html'] = [];
</script>
</td>
</tr>
<?php } elseif(in_array($blockname, array('personalinfo'))) { } else { ?>
<tr>
<th>显示条数</th>
<td><input type="text" name="shownum" value="<?php echo $para['shownum'];?>" class="px" /></td>
</tr>
<?php } if($blockname == 'blog') { ?>
<tr>
<th>摘要长度</th>
<td><input type="text" name="showmessage" value="<?php echo $para['showmessage'];?>" class="px" />  单位字节，0 将不显示摘要</td>
</tr>
<?php } elseif($blockname == 'myapp') { $para['logotype'] = empty($para['logotype']) ? 'icon' : $para['logotype'];?><tr>
<th>图标大小</th>
<td>
<label><input type="radio" name="logotype" value="logo"<?php if($para['logotype'] == 'logo') { ?> checked="checked"<?php } ?>>大</label>
<label><input type="radio" name="logotype" value="icon"<?php if($para['logotype'] == 'icon') { ?> checked="checked"<?php } ?>>小</label>
</td>
</tr>
<?php } ?>
</table>
</div>
</div>
<div class="o pns">
<input type="hidden" name="blocksubmit" value="true" />
<input type="hidden" name="handlekey" value="<?php echo $_GET['handlekey'];?>" />
<input type="hidden" name="eleid" value="<?php echo $_GET['eleid'];?>" />
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
<button type="submit" class="pn pnc" id="blocksubmitbtn"><strong>确定</strong></button>
</div>
</form>
<?php } else { $musicmsgs = $userdiy['parameters']['music'];$config = $musicmsgs['config'];?><?php if(empty($musicmsgs['mp3list']) ) { $addshow = 'block';$addtabshow = 'class="a"';$listshow = 'none';$listtabshow = '';?><?php } else { $addshow = 'none';$addtabshow = '';$listshow = 'block';$listtabshow = 'class="a"';?><?php } ?>
<ul id="menutabs" class="tb cl">
<li id="musicadd"<?php echo $addtabshow;?>><a href="javascript:;" onclick="spaceDiy.menuChange('menutabs' ,'musicadd');this.blur();return false;">添加音乐</a></li>
<li id="musiclist"<?php echo $listtabshow;?>><a href="javascript:;" onclick="spaceDiy.menuChange('menutabs' ,'musiclist');this.blur();return false;">当前播放列表</a></li>
<li id="musicconfig"><a href="javascript:;" onclick="spaceDiy.menuChange('menutabs' ,'musicconfig');this.blur();return false;">播放器配置</a></li>
</ul>
<div id="musicconfig_content" style="display:none">
<form method="post" name="musicconfigform" id="musicconfigform" autocomplete="off" action="home.php?mod=spacecp&amp;ac=index&amp;blockname=<?php echo $blockname;?>" onsubmit="spaceDiy.delIframe();ajaxpost('musicconfigform','return_<?php echo $_GET['handlekey'];?>','return_<?php echo $_GET['handlekey'];?>','onerror');">
<div class="c diywin" style="max-height:350px;width:480px;height:auto !important;height:320px;_margin-right:20px;overflow-y:auto;">
<table class="tfm">
<tr>
<th>模块名称</th>
<td><input type="text" name="blocktitle" value="<?php echo $para['title'];?>" class="px" /></td>
</tr>
<tr>
<th>显示模式</th><?php $bigmod = $config['showmod'] == 'big' ? ' checked' : ''; $defaultmod = $config['showmod'] == 'default' ? ' checked' : '';?><td> <input type="radio" value="big" name="showmod"<?php echo $bigmod;?>>完整 <input type="radio" value="default" name="showmod"<?php echo $defaultmod;?>>列表</td>
</tr>
<tr>
<th>开始模式</th><?php $autorun1 = $config['autorun'] == 'true' ? ' checked' : ''; $autorun2 = $config['autorun'] == 'false' ? ' checked' : '';?><td> <input type="radio" value="true" name="autorun"<?php echo $autorun1;?>>自动 <input type="radio" value="false" name="autorun"<?php echo $autorun2;?>>手动</td>
</tr>
<tr>
<th>播放模式</th><?php $shuffle1 = $config['shuffle'] == 'true' ? ' checked' : ''; $shuffle2 = $config['shuffle'] == 'false' ? ' checked' : '';?><td> <input type="radio" value="true" name="shuffle"<?php echo $shuffle1;?>>随机顺序 <input type="radio" value="false" name="shuffle"<?php echo $shuffle2;?>>列表顺序</td>
</tr>
<tr>
<th>界面颜色</th>
<td>
<p class="mbn">
面板背景颜色
<input type="text" name="crontabcolor" id="usercrontabcolor_v" value="<?php echo $config['crontabcolor'];?>" size="7" class="px p_fre" />
<input id="cm_ctc" onclick="createPalette('m_ctc', 'usercrontabcolor_v');" type="button" class="pn colorwd" value="" style="background-color: <?php echo $config['crontabcolor'];?>">
</p>
<p class="mbn">
字体按钮颜色
<input type="text" name="buttoncolor" id="userbuttoncolor_v" value="<?php echo $config['buttoncolor'];?>" size="7" class="px p_fre" />
<input id="cm_bc" onclick="createPalette('m_bc', 'userbuttoncolor_v');" type="button" class="pn colorwd" value="" style="background-color: <?php echo $config['buttoncolor'];?>">
</p>
<p class="mbn">
播放曲目颜色
<input type="text" name="fontcolor" id="userfontcolor_v" value="<?php echo $config['fontcolor'];?>" size="7" class="px p_fre" />
<input id="cm_fc" onclick="createPalette('m_fc', 'userfontcolor_v');" type="button" class="pn colorwd" value="" style="background-color: <?php echo $config['fontcolor'];?>">
</p>
</td>
</tr>
<tr>
<th>面板背景</th>
<td><input type="text" name="crontabbj" value="<?php echo $config['crontabbj'];?>" size="40" maxlength="200" class="px" />
<br />完整模式才有效果，建议选择 200*18 的 jpg 图片</td>
</tr>
<tr>
<th>高度</th><?php $config['height'] = empty($config['height']) && $config['height'] !== 0 ? 200 : $config['height'];?><td><input type="text" name="height" value="<?php echo $config['height'];?>" size="10" maxlength="10" class="px p_fre" />px
<br />设置音乐盒的高度</td>
</tr>
</table>
</div>
<div class="o pns">
<input type="hidden" name="musicsubmit" value="true" />
<input type="hidden" name="handlekey" value="<?php echo $_GET['handlekey'];?>" />
<input type="hidden" name="act" value="config" />
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
<button type="submit" class="pn pnc"><strong>确定</strong></button>
</div>
</form>
</div>

<div id="musicadd_content" style="display:<?php echo $addshow;?>;">
<script type="text/javascript">
function addMenu() {
newnode = $("tb_menu_new").rows[0].cloneNode(true);
tags = newnode.getElementsByTagName('input');
for(i in tags) {
tags[i].value = '';
}
$("tb_menu_new").appendChild(newnode);
}

function exchangeNode(obj, opId) {
var currentlyNode = obj.parentNode.parentNode.parentNode;
var opIndex = parseInt(currentlyNode.id);
var opNode = aimNode = '';
var aimId = 0;
if(opId == 1) {
aimId = opIndex+1;
if($('thetable').rows[aimId] == undefined) {
alert("已到最后一个");
return false;
}
} else {
aimId = opIndex-1;
if(aimId == 0) {
alert("已是第一个");
return false;
}
}
opNode = currentlyNode.rows[0].cloneNode(true);
aimNode = $('thetable').rows[aimId].parentNode;
var caimNode = aimNode.rows[0].cloneNode(true);
aimNode.removeChild(aimNode.rows[0]);
aimNode.appendChild(opNode);
currentlyNode.removeChild(currentlyNode.rows[0]);
currentlyNode.appendChild(caimNode);
}

function delMenu(obj) {
if($("tb_menu_new").rows.length > 1) {
$("tb_menu_new").removeChild(obj.parentNode.parentNode);
} else {
alert('最后一行不允许删除');
}
}
function delList() {
 var inputs = $('musiclistform').getElementsByTagName('input');
 var ids = [];
 for (var i=0;i<inputs.length;i++){
 if (inputs[i].type == 'checkbox') ids.push(inputs[i]);
 }
 var id = '';
 for (var i in ids) {
 if (typeof ids[i] == 'object' && ids[i].checked) {
id = parseInt(ids[i].value)+1;
var obj = $(id);
if(obj) {
obj.parentNode.removeChild(obj);
}
 }
 }
}
</script>

<form method="post" name="musicaddform" id="musicaddform" autocomplete="off" action="home.php?mod=spacecp&amp;ac=index&amp;blockname=<?php echo $blockname;?>" onsubmit="spaceDiy.delIframe();ajaxpost('musicaddform','return_<?php echo $_GET['handlekey'];?>','return_<?php echo $_GET['handlekey'];?>','onerror');">
<div class="c diywin" style="max-height:260px;width:480px;height:auto !important;height:260px;_margin-right:20px;overflow-y:auto;">
<table class="tfm">
<tr><td colspan="2" align="center">注意:仅支持 mp3 格式添加,即:必须是以 http:// 开始，以 .mp3 结尾</td></tr>
<tr><td colspan="2"><hr size="1" color="#EEEEEE" /></td></tr>
<tbody id="tb_menu_new">
<tr>
<td>
<table width="95%" align="center" border="0" cellspacing="0" cellpadding="0">
<tr>
<th>mp3地址</th>
<td><input type="text" name="mp3url[]" value="" size="40" maxlength="200" class="px" /></td>
</tr>
<tr>
<th>曲目名</th>
<td><input type="text" name="mp3name[]" size="20" maxlength="30" class="px" style="width:150px;" />
   为空则自动生成名字</td>
</tr>
<tr>
<th>封面</th>
<td><input type="text" name="cdbj[]" value="" size="40" maxlength="200" class="px" />
<br />
   完整模式才有效果，建议选择 60*60 的 jpg 图片</td>
</tr>
</table></td>
<td><a href="javascript:;" onclick="delMenu(this)"> 删除</a></td>
</tr>
</tbody>
</table>
</div>

<div class="o pns">
<input type="hidden" name="musicsubmit" value="true" />
<input type="hidden" name="handlekey" value="<?php echo $_GET['handlekey'];?>" />
<input type="hidden" name="act" value="addmusic" />
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
<button type="button" name="addone" onclick="addMenu();return false;" class="pn"><em>增加</em></button>&nbsp;
<button type="submit" class="pn pnc"><strong>确定</strong></button>
</div>
</form>
</div>
<div id="musiclist_content" style="display:<?php echo $listshow;?>;">

<?php if((!empty($musicmsgs['mp3list']))) { ?>
<form method="post" name="musiclistform" id="musiclistform" autocomplete="off" action="home.php?mod=spacecp&amp;ac=index&amp;blockname=<?php echo $blockname;?>" onsubmit="delList();spaceDiy.delIframe();ajaxpost('musiclistform','return_<?php echo $_GET['handlekey'];?>','return_<?php echo $_GET['handlekey'];?>','onerror');">
<div class="c diywin" style="max-height:350px;width:480px;height:auto !important;height:320px;_margin-right:20px;overflow-y:auto;">
<table width="100%" align="center" border="0" cellspacing="2" cellpadding="2">
<tr>
<td colspan="2">唱片集封面和文件地址<br/>(不能播放的时候请检查该地址 mp3 文件是否存在)</td>
<td><div align="right">全选删除
<input id="chkall" name="chkall" onclick="checkall(this.form, 'id')" type="checkbox">
</div></td>
</tr>
<tr><td colspan="3">
<table width="100%" align="center" border="0" cellspacing="1" cellpadding="1" id="thetable">
<tbody style="display:none;"><tr><td colspan="2"><hr size="0" /></td></tr></tbody><?php if(is_array($musicmsgs['mp3list'])) foreach($musicmsgs['mp3list'] as $key => $list) { $list['cdbj'] = empty($list['cdbj']) ? IMGDIR.'/nophotosmall.gif' : $list['cdbj'];?><?php $list['mp3name'] = dhtmlspecialchars($list['mp3name']);$list['mp3url'] = dhtmlspecialchars($list['mp3url']);$list['cdbj'] = dhtmlspecialchars($list['cdbj']);?><?php $index_ = $key+1;?><tbody id="<?php echo $index_;?>">
      		   <tr>
      		     <td>
      		       <table class="tfm">
      		         <tbody><tr>
      		           <th>mp3地址</th>
      		           <td><input type="text" value="<?php echo $list['mp3url'];?>" maxlength="200" size="40" name="mp3url[]" class="px" ></td>
      		         </tr>
      		         <tr>
      		           <th>曲目名</th>
      		           <td><input type="text" value="<?php echo $list['mp3name'];?>" maxlength="30" size="20" name="mp3name[]" class="px" >
      		             </td>
      		         </tr>
      		         <tr>
      		           <th>封面</th>
      		           <td><input type="text" value="<?php echo $list['cdbj'];?>" maxlength="200" size="40" name="cdbj[]" class="px" >
   <p><img border="0" class="musicbj mtn" src="<?php echo $list['cdbj'];?>"></p>
      		            </td>
      		         </tr>
      		     </tbody></table></td>
      		     <td width="50px"><input type="checkbox" value="<?php echo $key;?>" id="id_<?php echo $key;?>" name="ids"><a onclick="exchangeNode(this, -1)" href="javascript:;"><img width="11" height="12" border="0" src="<?php echo IMGDIR;?>/icon_top.gif"></a><a onclick="exchangeNode(this, 1)" href="javascript:;"><img width="11" height="12" border="0" src="<?php echo IMGDIR;?>/icon_down.gif"></a></td>
      		   </tr>
      		</tbody>
<?php } ?>
</table>
</td>
</tr>
</table>
</div>
<div class="o pns">
<input type="hidden" name="musicsubmit" value="true" />
<input type="hidden" name="handlekey" value="<?php echo $_GET['handlekey'];?>" />
<input type="hidden" name="act" value="editlist" />
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
<button type="submit" class="pn pnc"><strong>更新当前音乐列表</strong></button>
</div>
</form>
<?php } else { ?>
<div class="c diywin" style="max-height:350px;width:420px;height:auto !important;height:320px;_margin-right:20px;overflow-y:auto;">
<div style="line-height:40px;text-align:center;">暂无音乐播放列表
<button onclick="spaceDiy.menuChange('menutabs' ,'musicadd');;" class="pn"><em>添加音乐</em></button>
</div>
</div>
<?php } ?>
</div>
<?php } ?>
<script type="text/javascript" reload="1">
function succeedhandle_<?php echo $_GET['handlekey'];?> (url, message, values) {
var x = new Ajax();
x.get('home.php?mod=spacecp&ac=index&op=getblock&blockname='+values['blockname']+'&inajax=1', function(s) {
s = s.replace(/\<script.*\<\/script\>/ig,'<font color="red"> [javascript 脚本保存后显示] </font>');
$(values['blockname']).innerHTML = s;
drag.initPosition();
});
hideWindow('<?php echo $_GET['handlekey'];?>');}
</script>
<?php } elseif($_GET['op'] == 'savespaceinfo') { $space[domainurl] = space_domain($space);?><strong id="spacename"><?php if($space['spacename']) { ?><?php echo $space['spacename'];?><?php } else { ?><?php echo $space['username'];?>的个人空间<?php } ?></strong>
<a id="domainurl" href="<?php echo $space['domainurl'];?>" onclick="setCopy('<?php echo $space['domainurl'];?>', '空间地址复制成功');return false;" class="xs0 xw0"><?php echo $space['domainurl'];?></a>
<span id="spacedescription" class="xw0"><?php echo $space['spacedescription'];?></span>
<script type="text/javascript" reload="1">spaceDiy.initSpaceInfo();</script>

<?php } elseif($_GET['op'] == 'getspaceinfo') { $space[domainurl] = space_domain($space);?><form id="savespaceinfo" action="home.php?mod=spacecp&amp;ac=index&amp;op=savespaceinfo" method="post">
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
<input type="hidden" name="savespaceinfosubmit" value="true" />
<strong class="pns mbm">
<em class="xw0 xs1">我的空间名称: </em><input type="text" class="px vm" value="<?php if($space['spacename']) { ?><?php echo $space['spacename'];?><?php } else { ?><?php echo $space['username'];?>的个人空间<?php } ?>" name="spacename" />&nbsp;
<button type="submit" class="pn pnc vm" onclick="spaceDiy.spaceInfoSave();"><em>保存</em></button>
<button type="button" class="pn vm" onclick="spaceDiy.spaceInfoCancel();"><em>取消</em></button>
</strong>
<a id="domainurledit" style="display: none;"><?php echo $space['domainurl'];?></a>
<span><em class="xw0 xs1">我的空间描述: </em><input type="text" class="px" style="width:600px" value="<?php echo $space['spacedescription'];?>" name="spacedescription" /></span>
</form>
<?php } elseif($_GET['op'] == 'getpersonalnv') { if($_G['adminid'] == 1 && empty($space['self'])) { $personalnv['items'] = array(); $personalnv['banitems'] = array(); $personalnv['nvhidden'] = 0;?><?php } $nvclass = !empty($personalnv['nvhidden']) ? ' class="mininv"' : '';?><div id="nv">
<ul<?php echo $nvclass;?>>
<?php if(empty($personalnv['nvhidden'])) { if(empty($personalnv['banitems']['index'])) { if($_G['adminid'] == 1 && $_G['setting']['allowquickviewprofile'] == 1) { ?>
<li><a href="home.php?mod=space&amp;uid=<?php echo $space['uid'];?>&amp;do=index&amp;view=admin"><?php if(!empty($personalnv['items']['index'])) { ?><?php echo $personalnv['items']['index'];?><?php } else { ?>空间首页<?php } ?></a></li>
<?php } else { ?>
<li><a href="home.php?mod=space&amp;uid=<?php echo $space['uid'];?>&amp;do=index"><?php if(!empty($personalnv['items']['index'])) { ?><?php echo $personalnv['items']['index'];?><?php } else { ?>空间首页<?php } ?></a></li>
<?php } } if(empty($personalnv['banitems']['feed']) && helper_access::check_module('feed')) { ?>
<li><a href="home.php?mod=space&amp;uid=<?php echo $space['uid'];?>&amp;do=home&amp;view=me&amp;from=space"><?php if(!empty($personalnv['items']['feed'])) { ?><?php echo $personalnv['items']['feed'];?><?php } else { ?>动态<?php } ?></a></li>
<?php } if(empty($personalnv['banitems']['doing']) && helper_access::check_module('doing')) { ?>
<li><a href="home.php?mod=space&amp;uid=<?php echo $space['uid'];?>&amp;do=doing&amp;view=me&amp;from=space"><?php if(!empty($personalnv['items']['doing'])) { ?><?php echo $personalnv['items']['doing'];?><?php } else { ?>记录<?php } ?></a></li>
<?php } if(empty($personalnv['banitems']['blog']) && helper_access::check_module('blog')) { ?>
<li><a href="home.php?mod=space&amp;uid=<?php echo $space['uid'];?>&amp;do=blog&amp;view=me&amp;from=space"><?php if(!empty($personalnv['items']['blog'])) { ?><?php echo $personalnv['items']['blog'];?><?php } else { ?>日志<?php } ?></a></li>
<?php } if(empty($personalnv['banitems']['album']) && helper_access::check_module('album')) { ?>
<li><a href="home.php?mod=space&amp;uid=<?php echo $space['uid'];?>&amp;do=album&amp;view=me&amp;from=space"><?php if(!empty($personalnv['items']['album'])) { ?><?php echo $personalnv['items']['album'];?><?php } else { ?>相册<?php } ?></a></li>
<?php } if(empty($personalnv['banitems']['follow']) && helper_access::check_module('follow')) { ?>
<li><a href="home.php?mod=follow&amp;uid=<?php echo $space['uid'];?>&amp;do=view"><?php if(!empty($personalnv['items']['follow'])) { ?><?php echo $personalnv['items']['follow'];?><?php } else { ?>广播<?php } ?></a></li>
<?php } if($_G['setting']['allowviewuserthread'] !== false && (empty($personalnv['banitems']['topic']))) { ?>
<li><a href="home.php?mod=space&amp;uid=<?php echo $space['uid'];?>&amp;do=thread&amp;view=me&amp;from=space"><?php if(!empty($personalnv['items']['topic'])) { ?><?php echo $personalnv['items']['topic'];?><?php } else { ?>主题<?php } ?></a></li>
<?php } if(empty($personalnv['banitems']['share']) && helper_access::check_module('share')) { ?>
<li><a href="home.php?mod=space&amp;uid=<?php echo $space['uid'];?>&amp;do=share&amp;view=me&amp;from=space"><?php if(!empty($personalnv['items']['share'])) { ?><?php echo $personalnv['items']['share'];?><?php } else { ?>分享<?php } ?></a></li>
<?php } if(empty($personalnv['banitems']['wall']) && helper_access::check_module('wall')) { ?>
<li><a href="home.php?mod=space&amp;uid=<?php echo $space['uid'];?>&amp;do=wall"><?php if(!empty($personalnv['items']['wall'])) { ?><?php echo $personalnv['items']['wall'];?><?php } else { ?>留言板<?php } ?></a></li>
<?php } if(empty($personalnv['banitems']['profile'])) { ?>
<li><a href="home.php?mod=space&amp;uid=<?php echo $space['uid'];?>&amp;do=profile"><?php if(!empty($personalnv['items']['profile'])) { ?><?php echo $personalnv['items']['profile'];?><?php } else { ?>个人资料<?php } ?></a></li>
<?php } } ?>
</ul>
</div><?php } else { ?>
<ul>
  <li> NONE </li>
</ul>
<?php } include template('common/footer'); ?>