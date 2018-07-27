<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); 
function tpl_hide_credits_hidden($creditsrequire) {
global $_G;
?><?php
$return = <<<EOF
<div class="locked">
EOF;
 if($_G['uid']) { 
$return .= <<<EOF
{$_G['username']}
EOF;
 } else { 
$return .= <<<EOF
游客
EOF;
 } 
$return .= <<<EOF
，本帖隐藏的内容需要积分高于 {$creditsrequire} 才可浏览，您当前积分为 {$_G['member']['credits']}</div>
EOF;
?><?php 
return $return;
}

function tpl_hide_credits($creditsrequire, $message) {
?><?php
$return = <<<EOF
<div class="locked">以下内容需要积分高于 {$creditsrequire} 才可浏览</div>
{$message}<br /><br />

EOF;
?><?php 
return $return;
}

function tpl_codedisp($code) {
$randomid = 'code_'.random(3);
?><?php
$return = <<<EOF
<div class="blockcode"><div id="{$randomid}"><ol><li>{$code}</ol></div><em onclick="copycode($('{$randomid}'));">复制代码</em></div>
EOF;
?><?php 
return $return;
}

function tpl_quote() {
?><?php
$return = <<<EOF
<div class="quote"><blockquote>\\1</blockquote></div>
EOF;
?><?php 
return $return;
}

function tpl_free() {
?><?php
$return = <<<EOF
<div class="quote"><blockquote>\\1</blockquote></div>
EOF;
?><?php 
return $return;
}

function tpl_hide_reply() {
global $_G;
?><?php
$return = <<<EOF
<div class="showhide"><h4>本帖隐藏的内容</h4>\\1</div>

EOF;
?><?php 
return $return;
}

function tpl_hide_reply_hidden() {
global $_G;
?><?php
$return = <<<EOF
<div class="locked">
EOF;
 if($_G['uid']) { 
$return .= <<<EOF
{$_G['username']}
EOF;
 } else { 
$return .= <<<EOF
游客
EOF;
 } 
$return .= <<<EOF
，如果您要查看本帖隐藏内容请<a href="forum.php?mod=post&amp;action=reply&amp;fid={$_G['fid']}&amp;tid={$_G['tid']}" onclick="showWindow('reply', this.href)">回复</a></div>
EOF;
?><?php 
return $return;
}

function attachlist($attach) {
global $_G;
$attach['refcheck'] = (!$attach['remote'] && $_G['setting']['attachrefcheck']) || ($attach['remote'] && ($_G['setting']['ftp']['hideurl'] || ($attach['isimage'] && $_G['setting']['attachimgpost'] && strtolower(substr($_G['setting']['ftp']['attachurl'], 0, 3)) == 'ftp')));
$aidencode = packaids($attach);
$widthcode = attachwidth($attach['width']);
$is_archive = $_G['forum_thread']['is_archived'] ? "&fid=".$_G['fid']."&archiveid=".$_G[forum_thread][archiveid] : '';
$pluginhook = !empty($_G['setting']['pluginhooks']['viewthread_attach_extra'][$attach[aid]]) ? $_G['setting']['pluginhooks']['viewthread_attach_extra'][$attach[aid]] : '';
?><?php
$return = <<<EOF

<ignore_js_op>
<dl class="tattl">
<dt>
{$attach['attachicon']}
</dt>
<dd>
<p class="attnm">

EOF;
 if(!$attach['price'] || $attach['payed']) { 
$return .= <<<EOF

<a href="forum.php?mod=attachment{$is_archive}&amp;aid={$aidencode}" 
EOF;
 if($_GET['from'] != 'preview') { 
$return .= <<<EOF
onmouseover="showMenu({'ctrlid':this.id,'pos':'12'})"
EOF;
 } 
$return .= <<<EOF
 id="aid{$attach['aid']}" target="_blank">{$attach['filename']}</a>

EOF;
 } else { 
$return .= <<<EOF

<a href="forum.php?mod=misc&amp;action=attachpay&amp;aid={$attach['aid']}&amp;tid={$attach['tid']}" onclick="showWindow('attachpay', this.href)">{$attach['filename']}</a>

EOF;
 } 
$return .= <<<EOF

<div class="tip tip_4" id="aid{$attach['aid']}_menu" style="display: none" disautofocus="true">
<div class="tip_c">
<p class="y">{$attach['dateline']} 上传</p>
<p>点击文件名下载附件</p>

EOF;
 if(!$attach['attachimg'] && $_G['getattachcredits']) { 
$return .= <<<EOF
下载积分: {$_G['getattachcredits']}<br />
EOF;
 } 
$return .= <<<EOF

</div>
<div class="tip_horn"></div>
</div>
</p>
<p>{$attach['attachsize']}
EOF;
 if($attach['readperm']) { 
$return .= <<<EOF
, 阅读权限: <strong>{$attach['readperm']}</strong>
EOF;
 } 
$return .= <<<EOF
, 下载次数: {$attach['downloads']}
EOF;
 if(!$attach['attachimg'] && $_G['getattachcredits']) { 
$return .= <<<EOF
, 下载积分: {$_G['getattachcredits']}
EOF;
 } 
$return .= <<<EOF
</p>
<p>

EOF;
 if($attach['price']) { 
$return .= <<<EOF

售价: <strong>{$attach['price']} {$_G['setting']['extcredits'][$_G['setting']['creditstransextra']['1']]['unit']}{$_G['setting']['extcredits'][$_G['setting']['creditstransextra']['1']]['title']}</strong> &nbsp;[<a href="forum.php?mod=misc&amp;action=viewattachpayments&amp;aid={$attach['aid']}" onclick="showWindow('attachpay', this.href)" target="_blank">记录</a>]

EOF;
 if(!$attach['payed']) { 
$return .= <<<EOF

&nbsp;[<a href="forum.php?mod=misc&amp;action=attachpay&amp;aid={$attach['aid']}&amp;tid={$attach['tid']}" onclick="showWindow('attachpay', this.href)">购买</a>]

EOF;
 } } 
$return .= <<<EOF

</p>

EOF;
 if($attach['description']) { 
$return .= <<<EOF
<p class="xg2">{$attach['description']}</p>
EOF;
 } 
$return .= <<<EOF

{$pluginhook}
</dd>
</dl>
</ignore_js_op>

EOF;
?><?php 
return $return;
}

function imagelist($attach, $firstpost = 0) {
global $_G;
$attach['refcheck'] = (!$attach['remote'] && $_G['setting']['attachrefcheck']) || ($attach['remote'] && ($_G['setting']['ftp']['hideurl'] || ($attach['isimage'] && $_G['setting']['attachimgpost'] && strtolower(substr($_G['setting']['ftp']['attachurl'], 0, 3)) == 'ftp')));
$aidencode = packaids($attach);
$widthcode = attachwidth($attach['width']);
$is_archive = $_G['forum_thread']['is_archived'] ? "&fid=".$_G['fid']."&archiveid=".$_G[forum_thread][archiveid] : '';
$attachthumb = getimgthumbname($attach['attachment']);
$pluginhook = !empty($_G['setting']['pluginhooks']['viewthread_attach_extra'][$attach[aid]]) ? $_G['setting']['pluginhooks']['viewthread_attach_extra'][$attach[aid]] : '';
$guestviewthumb = !empty($_G['setting']['guestviewthumb']['flag']) && !$_G['uid'];
if($guestviewthumb) {
$guestviewthumbcss = guestviewthumbstyle();
}
?><?php
$__STATICURL = STATICURL;$return = <<<EOF


EOF;
 if($attach['attachimg'] && $_G['setting']['showimages'] && (($_G['group']['allowgetimage'] || $_G['uid'] == $attach['uid']) || (($guestviewthumb)))) { 
$return .= <<<EOF

<ignore_js_op>

EOF;
 if(!IS_ROBOT) { 
$return .= <<<EOF

<dl class="tattl attm">
<dt></dt>
<dd>

EOF;
 if(!$guestviewthumb) { 
$return .= <<<EOF

<p class="mbn">
<a href="forum.php?mod=attachment{$is_archive}&amp;aid={$aidencode}&amp;nothumb=yes" 
EOF;
 if($_GET['from'] != 'preview') { 
$return .= <<<EOF
onmouseover="showMenu({'ctrlid':this.id,'pos':'12'})" id="aid{$attach['aid']}"
EOF;
 } 
$return .= <<<EOF
 class="xw1" target="_blank">{$attach['filename']}</a>
<em class="xg1">({$attach['attachsize']}, 下载次数: {$attach['downloads']})</em>
</p>
<div class="tip tip_4" id="aid{$attach['aid']}_menu" style="display: none" disautofocus="true">
<div>
<p>
<a href="forum.php?mod=attachment{$is_archive}&amp;aid={$aidencode}&amp;nothumb=yes" target="_blank">下载附件</a>

EOF;
 if(helper_access::check_module('album')) { 
$return .= <<<EOF

&nbsp;<a href="javascript:;" onclick="showWindow(this.id, this.getAttribute('url'), 'get', 0);" id="savephoto_{$attach['aid']}" url="home.php?mod=spacecp&amp;ac=album&amp;op=saveforumphoto&amp;aid={$attach['aid']}&amp;handlekey=savephoto_{$attach['aid']}">保存到相册</a>

EOF;
 } if($firstpost && $_G['fid'] && $_G['forum']['picstyle'] && ($_G['forum']['ismoderator'] || $_G['uid'] == $attach['uid'])) { 
$return .= <<<EOF

&nbsp;<a href="forum.php?mod=ajax&amp;action=setthreadcover&amp;aid={$attach['aid']}&amp;fid={$_G['fid']}" onclick="showWindow('setcover{$attach['aid']}', this.href)">设为封面</a>

EOF;
 } 
$return .= <<<EOF

</p>
<p>
<span class="y">{$attach['dateline']} 上传</span>
<a href="javascript:;" onclick="imageRotate('aimg_{$attach['aid']}', 1)"><img src="{$__STATICURL}image/common/rleft.gif" class="vm" /></a>
<a href="javascript:;" onclick="imageRotate('aimg_{$attach['aid']}', 2)"><img src="{$__STATICURL}image/common/rright.gif" class="vm" /></a>
</p>
</div>
<div class="tip_horn"></div>
</div>
<p class="mbn">

EOF;
 if($attach['readperm']) { 
$return .= <<<EOF
阅读权限: <strong>{$attach['readperm']}</strong>
EOF;
 } if($attach['price']) { 
$return .= <<<EOF
售价: <strong>{$attach['price']} {$_G['setting']['extcredits'][$_G['setting']['creditstransextra']['1']]['unit']}{$_G['setting']['extcredits'][$_G['setting']['creditstransextra']['1']]['title']}</strong> &nbsp;[<a href="forum.php?mod=misc&amp;action=viewattachpayments&amp;aid={$attach['aid']}" onclick="showWindow('attachpay', this.href)" target="_blank">记录</a>]

EOF;
 if(!$attach['payed']) { 
$return .= <<<EOF

&nbsp;[<a href="forum.php?mod=misc&amp;action=attachpay&amp;aid={$attach['aid']}&amp;tid={$attach['tid']}" onclick="showWindow('attachpay', this.href)" target="_blank">购买</a>]

EOF;
 } } 
$return .= <<<EOF

</p>

EOF;
 if($attach['description']) { 
$return .= <<<EOF
<p class="mbn xg2">{$attach['description']}</p>
EOF;
 } } 
$return .= <<<EOF

{$pluginhook}

EOF;
 if($guestviewthumb) { 
$thumbpath = helper_attach::attachpreurl().'image/'.helper_attach::makethumbpath($attach['aid'], $_G['setting']['guestviewthumb']['width'], $_G['setting']['guestviewthumb']['height']);
$makefile = 'forum.php?mod=image&aid='.$attach['aid'].'&size='.$_G['setting']['guestviewthumb']['width'].'x'.$_G['setting']['guestviewthumb']['height'].'&key='.dsign($attach['aid'].'|'.$_G['setting']['guestviewthumb']['width'].'|'.$_G['setting']['guestviewthumb']['height']).'&type=1';

$return .= <<<EOF
{$guestviewthumbcss}
<div class="guestviewthumb">
<img id="aimg_{$attach['aid']}" class="guestviewthumb_cur" aid="{$attach['aid']}" src="{$__STATICURL}image/common/none.gif" onclick="showWindow('login', 'member.php?mod=logging&action=login'+'&referer='+encodeURIComponent(location))" onerror="javascript:if(this.getAttribute('makefile')){this.src=this.getAttribute('makefile'); this.removeAttribute('makefile');}" file="{$thumbpath}" makefile="{$makefile}" alt="{$attach['imgalt']}" title="{$attach['imgalt']}"/>
<br>
<a href="member.php?mod=logging&amp;action=login" onclick="showWindow('login', this.href+'&referer='+encodeURIComponent(location));">登录/注册后可看大图</a>
</div>

EOF;
 } elseif(!$attach['price'] || $attach['payed']) { 
$return .= <<<EOF

<div class="mbn savephotop">

EOF;
 if($_G['setting']['thumbstatus'] && $attach['thumb']) { 
$return .= <<<EOF

<a href="javascript:;"><img id="aimg_{$attach['aid']}" aid="{$attach['aid']}" src="{$__STATICURL}image/common/none.gif" onclick="zoom(this, this.getAttribute('zoomfile'), 0, 0, '{$_G['setting']['showexif']}')" zoomfile="
EOF;
 if($attach['refcheck']) { 
$return .= <<<EOF
forum.php?mod=attachment{$is_archive}&aid={$aidencode}&noupdate=yes&nothumb=yes
EOF;
 } else { 
$return .= <<<EOF
{$attach['url']}{$attach['attachment']}
EOF;
 } 
$return .= <<<EOF
" file="
EOF;
 if($attach['refcheck']) { 
$return .= <<<EOF
forum.php?mod=attachment{$is_archive}&aid={$aidencode}
EOF;
 } else { 
$return .= <<<EOF
{$attach['url']}{$attachthumb}
EOF;
 } 
$return .= <<<EOF
" alt="{$attach['imgalt']}" title="{$attach['imgalt']}" w="{$attach['width']}" /></a>

EOF;
 } else { 
$return .= <<<EOF

<img id="aimg_{$attach['aid']}" aid="{$attach['aid']}" src="{$__STATICURL}image/common/none.gif" zoomfile="
EOF;
 if($attach['refcheck']) { 
$return .= <<<EOF
forum.php?mod=attachment{$is_archive}&aid={$aidencode}&noupdate=yes&nothumb=yes
EOF;
 } else { 
$return .= <<<EOF
{$attach['url']}{$attach['attachment']}
EOF;
 } 
$return .= <<<EOF
" file="
EOF;
 if($attach['refcheck']) { 
$return .= <<<EOF
forum.php?mod=attachment{$is_archive}&aid={$aidencode}&noupdate=yes
EOF;
 } else { 
$return .= <<<EOF
{$attach['url']}{$attach['attachment']}
EOF;
 } 
$return .= <<<EOF
" {$widthcode} alt="{$attach['imgalt']}" title="{$attach['imgalt']}" w="{$attach['width']}" />

EOF;
 } 
$return .= <<<EOF

</div>

EOF;
 } 
$return .= <<<EOF

</dd>
</dl>

EOF;
 } else { 
$return .= <<<EOF

<dl class="tattl attm">

EOF;
 if(!$attach['price'] || $attach['payed']) { 
$return .= <<<EOF

<dd>

EOF;
 if($attach['description']) { 
$return .= <<<EOF
<p>{$attach['description']}</p>
EOF;
 } 
$return .= <<<EOF

<img src="
EOF;
 if($attach['refcheck']) { 
$return .= <<<EOF
forum.php?mod=attachment{$is_archive}&aid={$aidencode}&noupdate=yes
EOF;
 } else { 
$return .= <<<EOF
{$attach['url']}{$attach['attachment']}
EOF;
 } 
$return .= <<<EOF
" alt="{$attach['imgalt']}" title="{$attach['imgalt']}" />
</dd>

EOF;
 } 
$return .= <<<EOF

</dl>

EOF;
 } 
$return .= <<<EOF

</ignore_js_op>

EOF;
 } 
$return .= <<<EOF


EOF;
?><?php 
return $return;
}

function attachinpost($attach, $post) {
global $_G;
$firstpost = $post['first'];
$attach['refcheck'] = (!$attach['remote'] && $_G['setting']['attachrefcheck']) || ($attach['remote'] && ($_G['setting']['ftp']['hideurl'] || ($attach['isimage'] && $_G['setting']['attachimgpost'] && strtolower(substr($_G['setting']['ftp']['attachurl'], 0, 3)) == 'ftp')));
$aidencode = packaids($attach);
$widthcode = attachwidth($attach['width']);
$is_archive = $_G['forum_thread']['is_archived'] ? '&fid='.$_G['fid'].'&archiveid='.$_G[forum_thread][archiveid] : '';
$attachthumb = getimgthumbname($attach['attachment']);
$musiccode = getstatus($post[status], 7) && fileext($attach['attachment']) == 'mp3' ? (browserversion('ie') > 8 || browserversion('safari') ? '<audio controls="controls"><source src="'.$attach['url'].$attach['attachment'].'"></audio>' : parseaudio($attach['url'].$attach['attachment'], 400)) : '';
$guestviewthumb = !empty($_G['setting']['guestviewthumb']['flag']) && !$_G['uid'];
if($guestviewthumb) {
$guestviewthumbcss = guestviewthumbstyle();
}
?><?php
$__STATICURL = STATICURL;$return = <<<EOF

<ignore_js_op>

EOF;
 if($attach['attachimg'] && $_G['setting']['showimages'] && (((!$attach['price'] || $attach['payed']) && ($_G['group']['allowgetimage'] || $_G['uid'] == $attach['uid'])) || (($guestviewthumb)))) { if(!IS_ROBOT) { if($guestviewthumb) { 
$thumbpath = helper_attach::attachpreurl().'image/'.helper_attach::makethumbpath($attach['aid'], $_G['setting']['guestviewthumb']['width'], $_G['setting']['guestviewthumb']['height']);
$makefile = 'forum.php?mod=image&aid='.$attach['aid'].'&size='.$_G['setting']['guestviewthumb']['width'].'x'.$_G['setting']['guestviewthumb']['height'].'&key='.dsign($attach['aid'].'|'.$_G['setting']['guestviewthumb']['width'].'|'.$_G['setting']['guestviewthumb']['height']).'&type=1';

$return .= <<<EOF
{$guestviewthumbcss}
<div class="guestviewthumb">
<div style="margin: 0 auto;">
<img id="aimg_{$attach['aid']}" class="guestviewthumb_cur" aid="{$attach['aid']}" src="{$__STATICURL}image/common/none.gif" onclick="showWindow('login', 'member.php?mod=logging&action=login'+'&referer='+encodeURIComponent(location))" onerror="javascript:if(this.getAttribute('makefile')){this.src=this.getAttribute('makefile'); this.removeAttribute('makefile');}" file="{$thumbpath}" makefile="{$makefile}" inpost="1" alt="{$attach['imgalt']}" title="{$attach['imgalt']}"/>
<br>
<a href="member.php?mod=logging&amp;action=login" onclick="showWindow('login', this.href+'&referer='+encodeURIComponent(location));">登录/注册后可看大图</a>
</div>
</div>

EOF;
 } else { if($_G['setting']['thumbstatus'] && $attach['thumb']) { 
$return .= <<<EOF

<img
EOF;
 if($attach['price'] && $_G['forum_attachmentdown'] && $_G['uid'] != $attach['uid']) { 
$return .= <<<EOF
 class="attprice"
EOF;
 } 
$return .= <<<EOF
 style="cursor:pointer" id="aimg_{$attach['aid']}" aid="{$attach['aid']}" src="{$__STATICURL}image/common/none.gif" onclick="zoom(this, this.getAttribute('zoomfile'), 0, 0, '{$_G['setting']['showexif']}')" zoomfile="
EOF;
 if($attach['refcheck']) { 
$return .= <<<EOF
forum.php?mod=attachment{$is_archive}&aid={$aidencode}&noupdate=yes&nothumb=yes
EOF;
 } else { 
$return .= <<<EOF
{$attach['url']}{$attach['attachment']}
EOF;
 } 
$return .= <<<EOF
" file="
EOF;
 if($attach['refcheck']) { 
$return .= <<<EOF
forum.php?mod=attachment{$is_archive}&aid={$aidencode}
EOF;
 } else { 
$return .= <<<EOF
{$attach['url']}{$attachthumb}
EOF;
 } 
$return .= <<<EOF
" inpost="1"
EOF;
 if($_GET['from'] != 'preview') { 
$return .= <<<EOF
 onmouseover="showMenu({'ctrlid':this.id,'pos':'12'})"
EOF;
 } 
$return .= <<<EOF
 />

EOF;
 } else { 
$return .= <<<EOF

<img
EOF;
 if($attach['price'] && $_G['forum_attachmentdown'] && $_G['uid'] != $attach['uid']) { 
$return .= <<<EOF
 class="attprice"
EOF;
 } 
$return .= <<<EOF
 id="aimg_{$attach['aid']}" aid="{$attach['aid']}" src="{$__STATICURL}image/common/none.gif" zoomfile="
EOF;
 if($attach['refcheck']) { 
$return .= <<<EOF
forum.php?mod=attachment{$is_archive}&aid={$aidencode}&noupdate=yes&nothumb=yes
EOF;
 } else { 
$return .= <<<EOF
{$attach['url']}{$attach['attachment']}
EOF;
 } 
$return .= <<<EOF
" file="
EOF;
 if($attach['refcheck']) { 
$return .= <<<EOF
forum.php?mod=attachment{$is_archive}&aid={$aidencode}&noupdate=yes
EOF;
 } else { 
$return .= <<<EOF
{$attach['url']}{$attach['attachment']}
EOF;
 } 
$return .= <<<EOF
" {$widthcode} id="aimg_{$attach['aid']}" inpost="1"
EOF;
 if($_GET['from'] != 'preview') { 
$return .= <<<EOF
 onmouseover="showMenu({'ctrlid':this.id,'pos':'12'})"
EOF;
 } 
$return .= <<<EOF
 />

EOF;
 } } 
$return .= <<<EOF

<div class="tip tip_4 aimg_tip" id="aimg_{$attach['aid']}_menu" style="position: absolute; display: none" disautofocus="true">
<div class="xs0">
<p><strong>{$attach['filename']}</strong> <em class="xg1">({$attach['attachsize']}, 下载次数: {$attach['downloads']})</em></p>
<p>
<a href="forum.php?mod=attachment{$is_archive}&amp;aid={$aidencode}&amp;nothumb=yes" target="_blank">下载附件</a>

EOF;
 if(helper_access::check_module('album')) { 
$return .= <<<EOF

&nbsp;<a href="javascript:;" onclick="showWindow(this.id, this.getAttribute('url'), 'get', 0);" id="savephoto_{$attach['aid']}" url="home.php?mod=spacecp&amp;ac=album&amp;op=saveforumphoto&amp;aid={$attach['aid']}&amp;handlekey=savephoto_{$attach['aid']}">保存到相册</a>

EOF;
 } if($firstpost && $_G['fid'] && $_G['forum']['picstyle'] && ($_G['forum']['ismoderator'] || $_G['uid'] == $attach['uid'])) { 
$return .= <<<EOF

&nbsp;<a href="forum.php?mod=ajax&amp;action=setthreadcover&amp;aid={$attach['aid']}&amp;fid={$_G['fid']}" onclick="showWindow('setcover{$attach['aid']}', this.href)">设为封面</a>

EOF;
 } 
$return .= <<<EOF

</p>

EOF;
 if($attach['description']) { 
$return .= <<<EOF
<p>{$attach['description']}</p>
EOF;
 } 
$return .= <<<EOF

<p class="xg1 y">{$attach['dateline']} 上传</p>

EOF;
 } else { if($attach['description']) { 
$return .= <<<EOF
<p>{$attach['description']}</p>
EOF;
 } 
$return .= <<<EOF

<img src="
EOF;
 if($attach['refcheck']) { 
$return .= <<<EOF
forum.php?mod=attachment{$is_archive}&aid={$aidencode}&noupdate=yes
EOF;
 } else { 
$return .= <<<EOF
{$attach['url']}{$attach['attachment']}
EOF;
 } 
$return .= <<<EOF
" alt="{$attach['imgalt']}" title="{$attach['imgalt']}" />

EOF;
 } } else { if($musiccode) { 
$return .= <<<EOF

<div>{$musiccode}</div>

EOF;
 } 
$return .= <<<EOF

{$attach['attachicon']}
<span style="white-space: nowrap" id="attach_{$attach['aid']}" 
EOF;
 if($_GET['from'] != 'preview') { 
$return .= <<<EOF
onmouseover="showMenu({'ctrlid':this.id,'pos':'12'})"
EOF;
 } 
$return .= <<<EOF
>

EOF;
 if(!$attach['price'] || $attach['payed']) { 
$return .= <<<EOF

<a href="forum.php?mod=attachment{$is_archive}&amp;aid={$aidencode}" target="_blank">{$attach['filename']}</a>

EOF;
 } else { 
$return .= <<<EOF

<a href="forum.php?mod=misc&amp;action=attachpay&amp;aid={$attach['aid']}&amp;tid={$attach['tid']}" onclick="showWindow('attachpay', this.href)">{$attach['filename']}</a>

EOF;
 } 
$return .= <<<EOF

<em class="xg1">({$attach['attachsize']}, 下载次数: {$attach['downloads']}
EOF;
 if($attach['price']) { 
$return .= <<<EOF
, 售价: {$attach['price']} {$_G['setting']['extcredits'][$_G['setting']['creditstransextra']['1']]['unit']}{$_G['setting']['extcredits'][$_G['setting']['creditstransextra']['1']]['title']}
EOF;
 } 
$return .= <<<EOF
)</em>
</span>
<div class="tip tip_4" id="attach_{$attach['aid']}_menu" style="position: absolute; display: none" disautofocus="true">
<div class="tip_c xs0">
<div class="y">{$attach['dateline']} 上传</div>
点击文件名下载附件

EOF;
 if($attach['description']) { 
$return .= <<<EOF
<br />{$attach['description']}
EOF;
 } if($attach['readperm']) { 
$return .= <<<EOF
<br />阅读权限: {$attach['readperm']}
EOF;
 } } if(!IS_ROBOT && !$guestviewthumb) { if($attach['price']) { 
$return .= <<<EOF

<br />售价: {$attach['price']} {$_G['setting']['extcredits'][$_G['setting']['creditstransextra']['1']]['unit']}{$_G['setting']['extcredits'][$_G['setting']['creditstransextra']['1']]['title']}	&nbsp;<a href="forum.php?mod=misc&amp;action=viewattachpayments&amp;aid={$attach['aid']}" onclick="showWindow('attachpay', this.href)" target="_blank">[记录]</a>

EOF;
 if(!$attach['payed']) { 
$return .= <<<EOF

&nbsp;[<a href="forum.php?mod=misc&amp;action=attachpay&amp;aid={$attach['aid']}&amp;tid={$attach['tid']}" onclick="showWindow('attachpay', this.href)" target="_blank">购买</a>]

EOF;
 } } if(!$attach['attachimg'] && $_G['getattachcredits']) { 
$return .= <<<EOF
<br />下载积分: {$_G['getattachcredits']}
EOF;
 } 
$return .= <<<EOF

</div>
<div class="tip_horn"></div>
</div>

EOF;
 if($musiccode) { 
$return .= <<<EOF

<br />

EOF;
 } } 
$return .= <<<EOF

</ignore_js_op>

EOF;
?><?php 
return $return;
}
?>