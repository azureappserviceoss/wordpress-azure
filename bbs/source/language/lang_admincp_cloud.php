<?php

/**
 *	  [Discuz!] (C)2001-2099 Comsenz Inc.
 *	  This is NOT a freeware, use is subject to license terms
 *
 *	  $Id: lang_admincp_cloud.php 33991 2013-09-16 07:25:00Z nemohou $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$extend_lang = array
(
	'header_cloud' => '云平台',
	'header_navcloud' => 'Discuz!云平台',
	'nav_cloud' => 'Discuz!云平台',

	'menu_cloud_open' => '开通Discuz!云平台',
	'menu_cloud_upgrade' => '开通Discuz!云平台',
	'menu_cloud_applist' => '平台首页',
	'menu_cloud_siteinfo' => '站点信息',
	'menu_cloud_doctor' => '云平台诊断工具',

	'menu_setting_manyou' => '漫游应用设置',
	'menu_setting_qqconnect' => 'QQ互联设置',

	'menu_cloud_manyou' => '漫游应用',
	'menu_cloud_connect' => 'QQ互联',
	'menu_cloud_search' => '纵横搜索',
	'menu_cloud_stats' => '腾讯分析',
	'menu_cloud_security' => '防水墙',
	'menu_cloud_smilies' => 'SOSO表情',
	'menu_cloud_qqgroup' => '社区QQ群',
	'menu_cloud_union' => 'Discuz!联盟',
	'menu_cloud_storage' => '旋风存储',
	'menu_cloud_mobile' => '手机客户端',

	'close' => '关闭',
	'continue' => '继续',
	'message_title' => '提示信息',
	'jump_to_cloud' => '您即将转向Discuz!云平台（http://cp.discuz.qq.com）完成开通流程',
	'cloud_waiting' => '请稍候...',

	'cloud_status_error' => '出了点小错，由于站点ID/通信KEY等关键信息丢失导致Discuz!云平台服务出现异常，使用<a href="admin.php?action=cloud&operation=doctor">诊断工具</a>检测站点ID和KEY，如有疑问请访问<a href="http://www.discuz.net/forum-3926-1.html" target="_blank">官方论坛</a>寻求帮助',

	'cloud_timecheck' => '服务器时间检查',
	'cloud_introduction' => '查看介绍',
	'cloud_confirm_open' => '确认开通',
	'cloud_confirm_upgrade' => '确认升级',
	'cloud_page_loading' => '页面加载中...',
	'cloud_time_out' => '您无法访问Discuz!云平台，请尝试<a href="javascript:;" onClick="location.reload()">刷新</a>。',
	'cloud_unknown_dns' => '您的站点目前无法连接Discuz!云平台，请检查您服务器网络设置，使用<a href="admin.php?action=cloud&operation=doctor">诊断工具</a>检测DNS解析和接口连通性。<a href="http://cp.discuz.qq.com/faq?fId=1305690058&ADTAG=CP.CLOUD.FAQ.FID" target="_blank">查看帮助</a>，如有疑问请访问<a href="http://www.discuz.net/forum-3926-1.html" target="_blank">官方论坛</a>求助',

	'cloud_category' => '设置站点分类',
	'cloud_site_name' => '站点名称',
	'cloud_site_url' => '站点URL',
	'cloud_site_category' => '站点分类',
	'cloud_select' => '请选择',
	'cloud_agree_protocal' => '我已仔细阅读并同意',
	'read_protocal' => '《Discuz!云平台服务使用协议》',
	'cloud_will_open' => '我要开通',
	'cloud_will_upgrade' => '我要升级',
	'cloud_protocal' => 'Discuz!云平台服务协议',
	'cloud_select_category' => '请选择站点分类',
	'cloud_select_sub_category' => '请选择二级分类',
	'cloud_select_return' => '请选择站点分类，返回',
	'cloud_open_success' => 'Discuz!云平台开通成功 ',
	'cloud_upgrade_success' => 'Discuz!云平台升级成功 ',
	'cloud_network_busy' => '网络繁忙，稍后请重新操作，原因：<br />{errorMessage} (ERRCODE:{errorCode})',
	'cloud_turnto_applist' => '您的站点已开通Discuz!云平台，现跳转到平台首页',
	'cloud_site_id' => '站点ID',
	'cloud_api_ip_btn' => '设置云平台接口IP',
	'cloud_api_ip' => '云平台接口IP',
	'cloud_api_ip_comment' => '若站点服务器由于DNS解析问题无法连接到云平台接口，请填写api.discuz.qq.com的IP地址，使用<a href="admin.php?action=cloud&operation=doctor">诊断工具</a>检测，<a href="http://cp.discuz.qq.com/faq?fId=1304068911&ADTAG=CP.CLOUD.FAQ.FID" target="_blank">查看帮助</a>',
	'cloud_manyou_ip' => '漫游接口IP',
	'cloud_manyou_ip_comment' => '若站点服务器由于DNS解析问题无法连接到漫游接口，请填写api.manyou.com的IP地址，使用<a href="admin.php?action=cloud&operation=doctor">诊断工具</a>检测，<a href="http://faq.comsenz.com/viewnews-400" target="_blank">查看帮助</a>',
	'cloud_connect_api_ip' => 'QQ互联接口IP',
	'cloud_connect_api_ip_comment' => '若站点服务器由于DNS解析问题无法连接到QQ互联接口，请填写openapi.qzone.qq.com的IP地址，使用<a href="admin.php?action=cloud&operation=doctor">诊断工具</a>检测，<a href="http://cp.discuz.qq.com/faq?fId=1322796393&ADTAG=CP.CLOUD.FAQ.FID" target="_blank">查看帮助</a>',
	'cloud_ipsetting_success' => '云平台接口IP设置成功 ',
	'cloud_open_first' => '请先开启相关应用',
	'cloud_sync' => '同步站点信息',
	'cloud_sync_success' => '站点信息同步成功 ',
	'cloud_sync_failure' => '站点信息同步失败，原因：<br />{errorMessage} (ERRCODE:{errorCode})<br /><br />如有疑问，请访问<a href="http://www.discuz.net/forum-3926-1.html" target="_blank">官方论坛</a>寻求帮助',
	'cloud_resetkey' => '更换站点KEY',
	'cloud_reset_success' => '站点KEY更换成功 ',

	'cloud_siteinfo_tips' => '<li>如果站点名称或者站点URL有变动，请点击“同步站点信息”按钮。</li><li>站点KEY是站点与云平台通信的验证密钥，若近期有危险操作泄漏站点KEY等信息，请点击“更换站点KEY”按钮。<span style="color:red;">请谨慎使用此功能。</span></li>',

	'cloud_doctor_normal' => '正常',
	'cloud_doctor_tips' => '<li>Discuz!云平台诊断工具是帮助您分析站点上的状况，是否能与云平台正常通信等功能。</li><li>站点ID是您的站点在云平台的唯一标识，请勿和其他站点共用一套站点ID和站点通信KEY</li>',

	'cloud_doctor_setidkey' => '修改Discuz!上的站点ID和KEY',
	'cloud_doctor_connect_fix' => '<a href="./admin.php?action=cloud&operation=doctor&op=fixGuest">点击修复</a>',
	'cloud_doctor_setidkey_tips' => '<li style="color:red">修改Discuz!上的站点ID和KEY，可能会导致通信错误、签名错误以及其他的故障，请勿在没有官方人员指导的情况下修改。</li><li style="color:red">修改ID、KEY和状态前，请先备份论坛的common_setting表。</li>',
	'cloud_site_key' => '站点通信KEY',
	'cloud_site_key_safetips' => '(出于安全考虑，部分隐藏)',
	'cloud_site_key_comment' => '站点通信KEY请勿对外公布',
	'cloud_site_status' => '状态',
	'cloud_idkeysetting_success' => '站点ID/KEY状态设置成功 ',
	'cloud_idkeysetting_siteid_failure' => '站点ID必须为纯数字，请勿随意修改。如有必要，请在客服人员协助下修改。',
	'cloud_idkeysetting_sitekey_failure' => '站点通信KEY必须为32位，请勿随意修改。如有必要，请在客服人员协助下修改。',

	'cloud_doctor_result_success' => '<img align="absmiddle" src="static/image/admincp/cloud/right.gif" />',
	'cloud_doctor_result_failure' => '<img align="absmiddle" src="static/image/admincp/cloud/wrong.gif" /> ',

	'cloud_doctor_api_test_other' => '测试云平台其他接口IP',
	'cloud_doctor_manyou_test_other' => '测试漫游其他接口IP',
	'cloud_doctor_qzone_test_other' => '测试QQ互联其他接口IP',
	'cloud_doctor_api_test_success' => '%s 请求接口 %s 成功，耗时 %01.3f 秒 %s',
	'cloud_doctor_api_test_failure' => '%s 请求接口 %s 失败，请咨询空间商 %s',
	'cloud_doctor_status_0' => '尚未开通云平台',
	'cloud_doctor_status_1' => '已开通云平台',
	'cloud_doctor_status_2' => '注册云平台，等待完成',

	'cloud_doctor_title_status' => '系统状态',
	'cloud_doctor_modify_siteidkey' => '手动修改站点ID/KEY',
	'cloud_doctor_close_yes' => '是 (前台Connect将不显示)',

	'cloud_site_version' => '产品版本',
	'cloud_site_release' => '产品发布日期',

	'cloud_doctor_title_result' => '检测结果(<a href="#" onClick="self.location.reload();">重新检测</a>)',

	'cloud_doctor_fsockopen_function' => 'fsockopen函数',
	'cloud_doctor_gethostbyname_function' => 'DNS解析函数',
	'cloud_doctor_function_disable' => '函数被禁用，请联系空间商',

	'cloud_doctor_dns_api' => '云平台域名解析',
	'cloud_doctor_dns_api_test' => '云平台主接口测试',
	'cloud_doctor_other_api_test' => '云平台其他接口测试',
	'cloud_doctor_dns_manyou' => '漫游域名解析',
	'cloud_doctor_dns_manyou_test' => '漫游主接口测试',
	'cloud_doctor_other_manyou_test' => '漫游其他接口测试',
	'cloud_doctor_dns_qzone' => 'QQ互联域名解析',
	'cloud_doctor_dns_qzone_test' => 'QQ互联主接口测试',
	'cloud_doctor_other_qzone_test' => 'QQ互联其他接口测试',
	'cloud_doctor_site_test' => '云平台到站点测试',
	'cloud_doctor_time_check' => '<img src="{imgdir}/loading.gif" class="vm"> 请稍候...',
	'cloud_doctor_site_test_result' => '<div id="cloud_doctor_site_test_result_div"><img src="{imgdir}/loading.gif" class="vm"> 请稍候...</div>',

	'cloud_doctor_setting_ip' => '手动设置的IP：',

	'cloud_doctor_dns_success' => '%s DNS解析的IP：%s %s <a href="javascript:;" onClick="showWindow(\'cloudApiIpWin\', \'%s?action=cloud&operation=siteinfo&anchor=cloud_ip&callback=doctor\'); return false;">设置接口IP</a>',
	'cloud_doctor_dns_failure' => '<img align="absmiddle" src="static/image/admincp/cloud/wrong.gif" /> %s DNS解析失败 %s <a href="javascript:;" onClick="showWindow(\'cloudApiIpWin\', \'%s?action=cloud&operation=siteinfo&anchor=cloud_ip&callback=doctor\'); return false;">设置接口IP</a>',

	'cloud_doctor_title_plugin' => '系统插件检测',
	'cloud_doctor_system_plugin_status' => '系统插件状态',
	'cloud_doctor_system_plugin_status_false' => ' 系统插件未初始化 <a href="misc.php?mod=initsys&formhash={formhash}" target="_doctor_initframe" onClick="$(\'_doctor_initframe\').onload = function () {self.location.reload();};">点击修复</a><iframe id="_doctor_initframe" name="_doctor_initframe" src="" width="0" height="0" style="display:none;"></iframe>',
	'cloud_doctor_plugin_module_error' => 'common_plugin表modules字段值不正确',

	'cloud_doctor_title_connect' => 'QQ互联检测',
	'cloud_doctor_connect_app_id' => 'QQ互联appid',
	'cloud_doctor_connect_guestgroup' => 'QQ互联游客用户组',
	'cloud_doctor_connect_app_key' => 'QQ互联appkey',
	'cloud_doctor_connect_reopen' => '当前站点appid/appkey丢失，请<a href="admin.php?action=cloud&operation=applist">重新开通</a>QQ互联',

	'cloud_application_close' => '您的站点未开启此项云服务，请开启相应插件',
	'cloud_application_disable' => '您的站点已被禁止使用此项云服务，如果有疑问请访问<a href="http://www.discuz.net/forum.php?gid=3923" target="_blank">官方论坛</a>寻求帮助',

	'cloud_search_tips' => '<li>开启漫游搜索功能后，用户可以使用基于漫游的搜索功能。</li>',

	'cloud_stats' => '腾讯分析',
	'cloud_stats_tips' => '<li>选择的样式会显示在论坛底部的右下方。</li>',
	'cloud_stats_status' => '启用腾讯分析',
	'cloud_stats_icon_set' => '选择样式',
	'cloud_stats_icon_none' => '不显示图标和文字',
	'cloud_stats_icon_word9' => '腾讯分析',
	'cloud_stats_icon_word10' => '网站统计',
	'cloud_stats_setting' => '设置图标',
	'cloud_stats_summary' => '网站概况',

	'cloud_smilies' => 'SOSO表情',
	'cloud_smilies_tips' => '<li>搜尽天下表情，为网站带来无穷的乐趣与体验。</li><li>省略繁琐的上传表情流程，无缝“偷渡”QQ表情到您的网站，论坛表情变得不再单调无味。</li>',

	'cloud_smilies_status' => '启用SOSO 表情',
	'cloud_storage' => '旋风存储',
	'cloud_storage_tips' => '<li>为网站节省空间带宽，让用户享受超大文件的高速存储，上传下载尽在云端。</li><li>体验高速上传下载，支持单个文件高达2GB的附件上传，附件永久有效，为论坛降低成本。</li>',

	'cloud_change_info' => '变更信息',
	'cloud_change_qq' => '更换绑定QQ',

	'setting_manyou' => '漫游应用设置',
	'setting_manyou_tips' => '<li>开启漫游应用功能后，用户可以自由选择各种不同的应用(诸如德克萨斯扑克、弹弹堂、十年一剑......)在站内进行使用。</li><li>漫游应用功能由 <a target="_blank" href="http://www.manyou.com/www/">MYOP开放平台</a> 提供， Manyou Open Platform(Manyou开放平台/MYOP)服务是由 Comsenz 公司为应用开发者提供的开放平台，启用漫游服务前，<a href="http://wiki.developer.manyou.com/wiki/index.php?title=MYOP%E7%BD%91%E7%AB%99%E6%9C%8D%E5%8A%A1%E5%8D%8F%E8%AE%AE&printable=yes" target="_blank">请先阅读MYOP网站服务协议</a></li>',
	'setting_manyou_base' => '基本设置',
	'setting_manyou_base_status' => '启用漫游应用',
	'setting_manyou_base_status_comment' => '选择是否开启漫游应用。如果关闭漫游应用，您的网站用户将不能使用任何基于漫游的应用',
	'setting_manyou_search_status' => '启用漫游搜索',
	'setting_manyou_search_status_comment' => '漫游搜索是专为 Discuz! 产品量身定做的高效、全文搜索服务，无须网站的 MySQL 资源',
	'setting_manyou_search_invite' => '漫游搜索邀请码',
	'setting_manyou_search_invite_comment' => '目前处于测试阶段，需要输入邀请码方可开通漫游搜索，<a href="http://www.discuz.net/thread-1669366-1-1.html" target="_blank">点击这里申请邀请码</a>',
	'setting_manyou_base_status_no' => '尚未开启漫游功能，不能进行此管理。',
	'setting_manyou_base_ip' => '漫游应用的 IP',
	'setting_manyou_base_ip_comment' => '默认为空。如果您的服务器因 DNS 解析问题无法使用漫游应用服务，请填写漫游应用的 IP 地址。<a href="http://faq.comsenz.com/viewnews-400" target="_blank">查看漫游应用的 IP</a>',
	'setting_manyou_base_close_prompt' => '关闭漫游应用的更新提示',
	'setting_manyou_base_close_prompt_comment' => '您的站点开启了漫游应用多应用服务后，当平台有了新的信息的时候漫游应用会自动提示给管理员。关闭本功能后，您将不再获取更新提示。',
	'setting_manyou_base_open_app_prompt' => '开启漫游应用公告',
	'setting_manyou_base_open_app_prompt_comment' => '当平台有了新的应用公告时，用户打开浏览器的时候会弹出窗口提示，类似漫游应用更新提示',
	'setting_manyou_base_refresh' => '同步漫游信息',
	'setting_manyou_base_refresh_comment' => '如果更改了导航名称、搜索设置等，请同步漫游信息。',
	'setting_manyou_base_showgift_comment' => '如果您开启了漫游应用平台的礼物应用后，可以在首页显示“推荐礼物”。',
	'setting_manyou_manage' => '漫游应用管理',
	'setting_manyou_search_manage' => '漫游搜索管理',

	'connect_menu_setting' => '基本设置',
	'connect_menu_service' => '其他',
	'connect_menu_stat' => '数据统计',
	'connect_setting_allow' => '开启QQ注册/绑定/登录服务',
	'connect_setting_allow_comment' => '开启后，用户可以通过QQ帐号登录站点，以及进行更多和QQ相关的操作',
	'connect_setting_siteid' => 'QQ绑定站点ID',
	'connect_setting_sitekey' => 'QQ绑定站点密钥',
	'connect_setting_feed_allow' => '开启发帖同步推送到QQ空间动态',
	'connect_setting_feed_allow_comment' => '开启后，用户发帖可以同步推送到QQ空间动态，展现给用户的QQ空间好友',
	'connect_setting_feed_fids' => '允许推送的论坛版块',
	'connect_setting_feed_group' => '群组是否允许推送',
	'connect_setting_feed_group_comment' => '设置在群组发表的主题是否可以推送到QQ空间动态',
	'connect_setting_t_allow' => '开启发帖同步推送到腾讯微博',
	'connect_setting_t_allow_comment' => '开启后，用户发帖可以同步推送到腾讯微博，展现给用户的微博听众',
	'connect_setting_t_fids' => '允许推送的论坛版块',
	'connect_setting_t_group' => '群组是否允许推送',
	'connect_setting_t_group_comment' => '设置在群组发表的主题是否可以推送到腾讯微博',
	'connect_setting_t_reply_showauthor' => '显示评论或转播的微博用户链接',
	'connect_setting_t_reply_showauthor_comment' => '设置"是"，回流时会在内容下方标注评论或转播的微博用户昵称和微博地址',
	'connect_setting_t_reply' => '允许微博评论和转播内容回流',
	'connect_setting_t_reply_comment' => '推送主题或分享主题到腾讯微博后，是否允许微博评论和转播内容回流',
	'connect_setting_like_allow' => '显示本站QQ认证空间喜欢的链接',
	'connect_setting_like_allow_comment' => '用户点击本站QQ认证空间喜欢的链接，将立即成为本站QQ认证空间的粉丝，随时收取认证空间的动态',
	'connect_setting_like_url' => '认证空间QQ号码',
	'connect_setting_like_url_comment' => '设置认证空间的 QQ 号码，提交认证申请请<a href="http://opensns.qq.com/" target="_blank">点击这里</a>',
	'connect_setting_turl_allow' => '显示本站官方微博快速收听按钮',
	'connect_setting_turl_allow_comment' => '用户点击本站官方微博快速收听按钮，将立即成为您所设置的腾讯微博帐号听众，随时收取微博的动态',
	'connect_setting_turl_qq' => '官方微博QQ号码',
	'connect_setting_turl_qq_comment' => '设置官方微博的QQ号码',
	'connect_setting_turl_qq_failed' => '官方微博QQ号码设置失败，请确保该QQ号的有效性',
	'connect_setting_qshare_allow' => '开启Q-Share功能',
	'connect_setting_qshare_allow_comment' => '用户选中帖子内容中的任何一段文本时，可方便快捷的将选中的文本内容和区域内图片转播到腾讯微博',
	'connect_setting_weibo_appkey' => '腾讯微博开放平台AppKey',
	'connect_setting_weibo_appkey_comment' => '填写AppKey将在腾讯微博中显示设置的来源字段信息，可不填写。怎样申请AppKey？请访问腾讯微博开放平台，<a href="http://open.t.qq.com/apps_welcome.php" target="_blank">创建应用获取AppKey</a>',
	'connect_member_info' => '用户信息',
	'connect_member_bindlog' => 'QQ绑定日志',
	'connect_member_bindlog_type' => '操作',
	'connect_member_bindlog_username' => '用户名',
	'connect_member_bindlog_date' => '日期',
	'connect_member_bindlog_type_1' => '绑定',
	'connect_member_bindlog_type_2' => '解除绑定',
	'connect_member_bindlog_uin' => 'QQ帐号绑定日志',
	'connect_member_bindlog_uid' => '用户帐号绑定日志',
	'connect_guest_group_name' => 'QQ游客',

	'qqgroup_menu_list' => '绑定管理',
	'qqgroup_menu_manager' => '设置名称',
	'qqgroup_menu_setting' => '推送设置',
	'qqgroup_menu_block' => '推送信息',
	'qqgroup_menu_history' => '推送历史',

	'qqgroup_msg_deficiency' => '请至少推送一条头条主题和一条列表主题',
	'qqgroup_msg_save_succeed' => '恭喜，信息成功推送到QQ群',
	'qqgroup_msg_upload_succeed' => '图片上传成功 ',
	'qqgroup_msg_upload_failure' => '图片上传失败，请选择长宽为75*75的图片，支持JPG、GIF、PNG格式，文件小于5M，并检查服务器是否开启GD库',
	'qqgroup_msg_remote_exception' => '抱歉，出了点小错。错误原因：{errmsg} 错误代号：{errno}',
	'qqgroup_msg_unknown_dns' => '抱歉，出现未知错误，请检查您的服务器与Discuz!云平台连接',
	'qqgroup_msg_remote_error' => '抱歉，服务器出错了。请稍后再试',

	'qqgroup_search_order_views' => '浏览数倒序',
	'qqgroup_search_order_replies' => '回复数倒序',
	'qqgroup_search_order_heats' => '热度倒序',
	'qqgroup_search_order_dateline' => '发布时间倒序',
	'qqgroup_search_order_lastpost' => '最后回复倒序',
	'qqgroup_search_order_recommends' => '主题评价倒序',

	'qqgroup_search_dateline_1' => '1小时内发布的主题',
	'qqgroup_search_dateline_2' => '24小时内发布的主题',
	'qqgroup_search_dateline_3' => '7天内发布的主题',
	'qqgroup_search_dateline_4' => '1个月内发布的主题',
	'qqgroup_search_dateline_0' => '不限制',

	'qqgroup_search_tid' => '主题ID:',
	'qqgroup_search_button' => '搜索',
	'qqgroup_search_threadslist' => '主题列表',
	'qqgroup_search_inforum' => '所在版块',
	'qqgroup_search_operation' => '操作',

	'qqgroup_search_loading' => '加载中...',
	'qqgroup_search_nothreads' => '未找到指定条件的主题，请尝试更换筛选条件重新搜索',

	'qqgroup_ctrl_add_miniportal_topic' => '推送到头条',
	'qqgroup_ctrl_add_miniportal_normal' => '推送到列表',
	'qqgroup_ctrl_up' => '向上移动',
	'qqgroup_ctrl_down' => '向下移动',
	'qqgroup_ctrl_edit' => '编辑',
	'qqgroup_ctrl_remove' => '移出',
	'qqgroup_ctrl_upload_image' => '上传图片',
	'qqgroup_ctrl_choose_image' => '选择图片：',
	'qqgroup_ctrl_choose_image_tips' => '请选择长宽为75*75的图片，支持JPG、GIF、PNG格式，文件小于5M。',
	'qqgroup_ctrl_close' => '关闭',

	'qqgroup_preview_tips_topic' => '点击左侧 <img src="static/image/admincp/cloud/qun_op_top.png" align="absmiddle" /> 将信息推送到头条',
	'qqgroup_preview_tips_normal' => '点击左侧 <img src="static/image/admincp/cloud/qun_op_list.png" align="absmiddle" /> 将信息推送到列表',
	'qqgroup_preview_more' => '更多',
	'qqgroup_preview_shortname' => '页卡标题',
	'qqgroup_preview_button' => '推送信息',
	'attach_img' => '图片附件',

	'qqgroup_feed_setting' => '推送设置',
	'qqgroup_usergroup_feed_list' => '允许推送的用户组',
	'qqgroup_usergroup_feed_list_comment' => '所选用户组拥有推送权限，可以按住 CTRL 多选',
	'qqgroup_setting_tips' => '<li>主题查看页的“推送到QQ群”功能，可将帖子即时推送到QQ群聊天框。</li><li><img src="static/image/admincp/cloud/feed.png" align="left"></li>',

	'security_member_list' => '违规用户',
	'security_member_tips' => '<li>违规用户来自于防水墙识别为垃圾帖的发帖用户，不包含白名单用户。</li><li>“忽略本次”操作将从违规列表中忽略指定用户，如果用户再次违规仍将会被加入违规用户列表。</li>',
	'security_member_ignore_this' => '忽略本次',
	'security_thread_list' => '违规主题',
	'security_post_list' => '违规回帖',
	'security_members_name' => '用户名',
	'security_members_eviltype' => '恶意类型',
	'security_post_eviltype' => '恶意类型',
	'security_thread_eviltype' => '恶意类型',
	'security_isreported' => '是否已经上报过',
	'security_admin_operate' => '状态',
	'security_isreported_yes' => '是',
	'security_isreported_no' => '否',
	'security_type_1' => '政治',
	'security_type_2' => '色情',
	'security_type_3' => '灌水',
	'security_type_4' => '挂马',
	'security_type_5' => '广告',
	'security_type_6' => '其他',
	'security_opreateresult_0' => '回收站',
	'security_opreateresult_1' => '正常',
	'security_opreateresult_2' => '已删除',
	'security_opreateresult_3' => '忽略',
	'security_subject' => '标题',
	'security_forum' => '版块',
	'security_author' => '作者/发帖时间',
	'security_view' => '查看',
	'security_view_thread' => '查看主题',
	'security_clicktotoggle' => '点击折叠/展开详细内容',
	'security_foldall' => '全部折叠',
	'security_exfoldall' => '全部展开',
	'security_userdeleted' => '该用户已被管理人员删除',
	'security_postdeleted' => '该帖子已被网站管理人员从回收站彻底删除',
	'security_modreason' => '防水墙自动处理',
	'security_restore_user' => '恢复该用户',
	'security_change_usergroup' => '更改用户组',
	'security_reopen' => '您的站点未成功开通防水墙，请<a href="'.ADMINSCRIPT.'?action=cloud&operation=security&anchor=reopen">再次开通</a>',

	'recyclebin_search_security_thread' => '仅包含防水墙处理的主题',
	'recyclebin_search_security_post' => '仅包含防水墙处理的回帖',

	'security_index' => '首页',
	'security_index_list' => '首页',
	'security_setting' => '白名单设置',
	'security_blanklist' => '白名单设置',
	'security_setting_list' => '白名单设置',
	'security_reopen_list' => '再次开通',
	'security_tips' => '小技巧',
	'security_tips_1' => '<li><p>系统识别到违规主题和违规回帖后，自动将主题和回帖放入<a href="admin.php?action=recyclebin" style="text-decoration:none; color:#0000FF;">回收站</a></p></li>',
	'security_white_list_setting' => '白名单设置',
	'security_usergroup_white_list' => '用户组白名单',
	'security_usergroup_white_list_comment' => '所选用户组内用户的操作，防水墙不做处理，可以按住 CTRL 多选',
	'security_forum_white_list' => '版块白名单',
	'security_forum_white_list_comment' => '所选版块内的主题和回复，防水墙不做处理，可以按住 CTRL 多选',
	'security_recyclebin_thread' => '到回收站处理主题',
	'security_recyclebin_post' => '到回收站处理回帖',
	'security_thread_status' => '主题状态',
	'security_post_status' => '回帖状态',
	'security_thread_member' => '用户状态',
	'security_thread_member_group' => '用户组',
	'security_cron_lastpost' => '更新版块最后发表(防水墙相关)',
	'security_cron_daily' => '防水墙每日优化',
	'setting_update_succeed' => '当前设置更新成功',
	'security_createtime' => '违规日期',
	'security_safe_list' => '安全设置',
	'security_safe_login_open' => '开启安全登录功能',
	'security_safe_login_open_comment' => '有效防止恶意注册！选择是，用户必须将论坛帐号与QQ帐号绑定后，才能在论坛发帖，回复',
	'security_qq_login_alone_open' => '开启QQ独立登录',
	'security_qq_login_alone_open_comment' => '有效防止盗号！开启后只能使用QQ号登录，即便盗取了论坛帐号也无法登录发帖',

	'search_menu_setting' => '设置',
	'search_menu_service' => '功能配置',
	'search_setting_allow_hot_topic' => '开启热门话题',
	'search_setting_allow_forum_recommend' => '开启版块推荐词',
	'search_setting_allow_thread_related' => '开启主题相关帖',
	'search_setting_allow_thread_related_comment' => '展示在主题帖下方，根据帖子内容计算出站内相关的帖子，挖掘相似主题，拓展用户阅读深度。',
	'search_setting_allow_recommend_related' => '开启相关推荐',
	'search_setting_allow_recommend_related_comment' => '当用户通过搜索引擎点到站内帖子页时，会在页面左下角产生一个浮动的推荐页面，帮助访客发现更多站内相关内容。',
	'search_setting_allow_forum_related' => '开启版块相关帖',
	'search_setting_allow_collection_related' => '开启淘帖相关帖',
	'search_menu_settingsearch' => '搜索设置',
	'search_menu_basicsetting' => '基本设置',
	'search_menu_modulesetting' => '模块设置',
	'search_modulesetting_tips' => '纵横搜索提供的推荐服务，在不占用网站资源的前提下，通过云计算能力为网站用户提升访问深度。',
);

$GLOBALS['admincp_actions_normal'][] = 'cloud';

?>