<?php
/**
 * WordPress 基础配置文件。
 *
 * 本文件包含以下配置选项：MySQL 设置、数据库表名前缀、密钥、
 * WordPress 语言设定以及 ABSPATH。如需更多信息，请访问
 * {@link http://codex.wordpress.org/zh-cn:%E7%BC%96%E8%BE%91_wp-config.php
 * 编辑 wp-config.php} Codex 页面。MySQL 设置具体信息请咨询您的空间提供商。
 *
 * 这个文件用在于安装程序自动生成 wp-config.php 配置文件，
 * 您可以手动复制这个文件，并重命名为“wp-config.php”，然后输入相关信息。
 *
 * @package WordPress
 */

// ** MySQL 设置 - 具体信息来自您正在使用的主机 ** //
/** WordPress 数据库的名称 */
define('DB_NAME', 'mahjong_wpmahjong');

/** MySQL 数据库用户名 */
define('DB_USER', 'mahjong_wpadmin');

/** MySQL 数据库密码 */
define('DB_PASSWORD', 'rW~k~A*}x(Lf');

/** MySQL 主机 */
define('DB_HOST', 'mahjongsite.mysql.database.azure.com');  // MAX MA TESTING
//define('DB_HOST', 'mahjong-ca.org');

/** 创建数据表时默认的文字编码 */
define('DB_CHARSET', 'utf8');

/** 数据库整理类型。如不确定请勿更改 */
define('DB_COLLATE', '');

/**#@+
 * 身份认证密匙设定。
 *
 * 您可以随意写一些字符
 * 或者直接访问 {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org 私钥生成服务}，
 * 任何修改都会导致 cookie 失效，所有用户必须重新登录。
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'm8+lK(w*Ap&KKT0]d{{0To9*?K,u{bw**=aN;H:PW4@R5#8C7(mZ+0]nVewYk/g$');
define('SECURE_AUTH_KEY',  ';}&]XU$|KXL=#M:Z&IlN.;4P/+vBsC3a}c7*d,FMY?1D`:1Q^L?]10MLitCN ?<!');
define('LOGGED_IN_KEY',    '9-%tp?[:AT(SXS$WNj?TaK{$Ps=lbO~)vB)`kPnLei&}kgAO=ZLy)y_<5)Sjg=Od');
define('NONCE_KEY',        'Jm5}eLnRXzDaRRbA|n5fs?ZBAS?q%`yG#7qXwCLhtdrq?}U]Nk[;-:;Zm#<s{5Q*');
define('AUTH_SALT',        'CZZ7H3oiXAbvO;K4 |236iqb3DdL(~GU90gt+Ho?n=KD<[W7LV;Z<ixqU:{X5Lvw');
define('SECURE_AUTH_SALT', '7?i4#@SLfed TjKnOGN3#hu+#[::C=]G>{W3-K9bc,wvtH#<sD/!GF>R==0Z-ig ');
define('LOGGED_IN_SALT',   '4Iv+;f1av<[Q-5DsW1):T:cJUI<4A?tV_CTRQI`O]&L`Q*0Wz(|I>lA(j0a#W4?H');
define('NONCE_SALT',       'LS_B6C)MQNX-uirh$+ [{9ufauM[IWk)GS+ # 7:Y(hehTB+?cQI`TFy*q*LqAMF');

/**#@-*/

/**
 * WordPress 数据表前缀。
 *
 * 如果您有在同一数据库内安装多个 WordPress 的需求，请为每个 WordPress 设置不同的数据表前缀。
 * 前缀名只能为数字、字母加下划线。
 */
$table_prefix  = 'wp_';

/**
 * WordPress 语言设置，中文版本默认为中文。
 *
 * 本项设定能够让 WordPress 显示您需要的语言。
 * wp-content/languages 内应放置同名的 .mo 语言文件。
 * 要使用 WordPress 简体中文界面，只需填入 zh_CN。
 */
define('WPLANG', 'zh_CN');

/**
 * 开发者专用：WordPress 调试模式。
 *
 * 将这个值改为“true”，WordPress 将显示所有用于开发的提示。
 * 强烈建议插件开发者在开发环境中启用本功能。
 */
define('WP_DEBUG', false);

/* 多站点开启 */
define('WP_ALLOW_MULTISITE', true);

define('MULTISITE', true);
define('SUBDOMAIN_INSTALL', false);
//define('DOMAIN_CURRENT_SITE', 'www.mahjong-ca.org');
//define('DOMAIN_CURRENT_SITE', 'localhost:45567/?XDEBUG_SESSION_START=E966586F');


define('PATH_CURRENT_SITE', '/');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 6);


/* 好了！请不要再继续编辑。请保存本文件。使用愉快！ */

/** WordPress 目录的绝对路径。 */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** 设置 WordPress 变量和包含文件。 */
require_once(ABSPATH . 'wp-settings.php');
