<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); 
function profile_node_numbercard($post, $s, $e) {
foreach($post['numbercard'] as $numbercardkey => $numbercard) $return .= ($numbercardkey == 2 ? '<td>' : '<th>').'<p><a href="'.$numbercard[link].'" class="xi2">'.dnumber($numbercard[value]).'</a></p>'.$numbercard[lang].($numbercardkey == 2 ? '</td>' : '</th>');
?><?php
$return = <<<EOF
{$s}<div class="tns xg2"><table cellspacing="0" cellpadding="0">{$return}</table></div>{$e}
EOF;
?><?php 
return $return;
}

function profile_node_groupicon($post, $s, $e) {
?><?php
$return = <<<EOF

EOF;
 if($post['groupicon']) { 
$return .= <<<EOF
{$s}<a href="home.php?mod=spacecp&amp;ac=usergroup&amp;gid={$post['groupid']}" target="_blank">{$post['groupicon']}</a>{$e}
EOF;
 } 
$return .= <<<EOF

EOF;
?><?php 
return $return;
}

function profile_node_authortitle($post, $s, $e) {
?><?php
$return = <<<EOF
{$s}<a href="home.php?mod=spacecp&amp;ac=usergroup&amp;gid={$post['groupid']}" target="_blank">{$post['authortitle']}</a>{$e}
EOF;
?><?php 
return $return;
}

function profile_node_customstatus($post, $s, $e) {
?><?php
$return = <<<EOF

EOF;
 if($post['customstatus']) { 
$return .= <<<EOF
{$s}{$post['customstatus']}{$e}
EOF;
 } 
$return .= <<<EOF

EOF;
?><?php 
return $return;
}

function profile_node_medal($post, $s, $e) {
if(!$post['medals']) return;
foreach($post['medals'] as $medal) $return .= '<img id="md_'.$post['pid'].'_'.$medal['medalid'].'" src="'.STATICURL.'image/common/'.$medal['image'].'" alt="'.$medal['name'].'" title="" onmouseover="showMenu({\'ctrlid\':this.id, \'menuid\':\'md_'.$medal['medalid'].'_menu\', \'pos\':\'12!\'})" />';
?><?php
$return = <<<EOF
{$s}<a href="home.php?mod=medal">{$return}</a>{$e}
EOF;
?><?php 
return $return;
}

function profile_node_star($post, $s, $e, $upgrademenu = 1) {
$stars = showstars($post['stars']);
$menu = $post['upgradecredit'] !== false ? profile_node_upgrade_menu($post) : '';
?><?php
$return = <<<EOF

{$s}<span
EOF;
 if($post['upgradecredit'] !== false && $upgrademenu) { 
$return .= <<<EOF
 id="g_up{$post['pid']}" onmouseover="showMenu({'ctrlid':this.id, 'pos':'12!'});"
EOF;
 } 
$return .= <<<EOF
>{$stars}</span>{$e}

EOF;
 if($upgrademenu) { 
$return .= <<<EOF
{$menu}
EOF;
 } 
$return .= <<<EOF


EOF;
?><?php 
return $return;
}

function profile_node_upgradeprogress($post, $s, $e, $upgrademenu = 1) {
if($post['upgradecredit'] !== false) {
$menu = profile_node_upgrade_menu($post);
?><?php
$return = <<<EOF

{$s}<span class="pbg2" 
EOF;
 if($upgrademenu) { 
$return .= <<<EOF
 id="upgradeprogress_{$post['pid']}" onmouseover="showMenu({'ctrlid':this.id, 'pos':'12!', 'menuid':'g_up{$post['pid']}_menu'});"
EOF;
 } 
$return .= <<<EOF
><span class="pbr2" style="width:{$post['upgradeprogress']}%;"></span></span>{$e}

EOF;
 if($upgrademenu) { 
$return .= <<<EOF
{$menu}
EOF;
 } 
$return .= <<<EOF


EOF;
?><?php 
return $return;
}
}

function profile_node_upgrade_menu($post) {
global $_G;
?><?php
$return = <<<EOF
<div id="g_up{$post['pid']}_menu" class="tip tip_4" style="display: none;"><div class="tip_horn"></div><div class="tip_c">{$post['authortitle']}, 积分 {$post['credits']}, 距离下一级还需 {$post['upgradecredit']} 积分</div></div>
EOF;
?><?php 
return $return;
}

function profile_node_baseinfo($post, $s, $e, $extra) {
$str = viewthread_baseinfo($post, $extra);
return $str !== '' ? $s.$str.$e : '';
}
?>