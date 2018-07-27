<?php

/**

 * The Header for our theme.

 *

 * @package mahjong-org.azurewebsites.net

 * @subpackage mahjong

 * @since Mahjong-ca 1.0

 */

?><!DOCTYPE html>

<html <?php language_attributes(); ?>><head>

<meta charset="<?php bloginfo( 'charset' ); ?>" />

<title><?php

	/*

	 * Print the <title> tag based on what is being viewed.

	 */

	global $page, $paged;



	wp_title( '-', true, 'right' );



	// Add the blog name.

	bloginfo( 'name' );



	// Add the blog description for the home/front page.

	$site_description = get_bloginfo( 'description', 'display' );

	if ( $site_description && ( is_home() || is_front_page() ) )

		echo " - $site_description";



	// Add a page number if necessary:

	if ( $paged >= 2 || $page >= 2 )

		echo ' - ' . sprintf( __( 'Page %s', 'mahjong' ), max( $paged, $page ) );



	?></title>

<link rel="profile" href="http://gmpg.org/xfn/11" />

<link href="http://www.mahjong-org.azurewebsites.net/favicon.ico" rel="shortcut icon">

<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />

<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

<link rel="stylesheet" type="text/css" href="<?php bloginfo( 'template_url' ); ?>/style/nivo-slider.css"/>

<script type="text/javascript" src="<?php bloginfo( 'template_url' ); ?>/js/jquery-1.8.3.min.js"></script>

<script type="text/javascript" src="<?php bloginfo( 'template_url' ); ?>/js/jq-mahjong.js"></script>

<!-- 加载 Fancybox CSS文件 -->

<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/fancybox/jquery.fancybox.css" />

<!-- 加载 Fancybox JS文件 -->

<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/fancybox/jquery.fancybox.js"></script>

<!--[if IE 6]><script type="text/javascript" src="<?php bloginfo( 'template_url' ); ?>/js/DD_belatedPNG_0.0.8a-min.js"></script>

<script>

  DD_belatedPNG.fix('.pngFix,.pngFix:hover,.menubox li,.menubox li a.active,.video_playbtn,bm_btn a');

</script>

<![endif]-->

<?php

	if ( is_singular() && get_option( 'thread_comments' ) )

		wp_enqueue_script( 'comment-reply' );

	wp_head();

?>

</head>



<body <?php body_class(); ?>>

<div id="topnav" class="font_yahei">

  <div class="wrap">

   <div class="left">

    <ul>

     <li><a onclick="SetHome(this,window.location)">设为首页</a><span class="f">|</span></li>

     <li><a onclick="AddFavorite(window.location,document.title)">加入收藏</a></li>

    </ul>

   </div>

   <div class="right">

     <div class="language_list">

       <ul>

        <li><a href="javascript:zh_tran('s');" class="zh_click" id="zh_click_s">简体中文</a><span class="f">|</span></li>

        <li><a href="javascript:zh_tran('t');" class="zh_click" id="zh_click_t">繁体中文</a><span class="f">|</span></li>

        <li><a href="/en/">English</a></li>

        <li style="display:none;"><a href="#">França</a></li>

       </ul>

     </div>

     <div class="top_searchbox">

       <i class="ico"></i>

       <div class="formbox">

        

        <form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">

		<input type="text" class="field t" name="s" id="s" placeholder="<?php esc_attr_e( 'Search', 'mahjong' ); ?>" />

		<input type="submit" class="submit b" name="submit" id="searchsubmit" value="<?php esc_attr_e( 'Search', 'mahjong' ); ?>" />

	</form>

       </div>

     </div>

   </div>

  <!--wrap end-->

  </div>

 <!--topnav end-->

 </div>

 

 <div class="main_menu">

 	<div class="wrap">

      <div class="logobox"><a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><div class="logo pngFix"><?php bloginfo( 'name' ); ?></div></a></div>

      <div id="menu_mind" class="menubox font_yahei">

        <ul>

         <li class="i1">

         	<div class="m_cont">

            <a href="<?php echo home_url( '/' ); ?>" class="a1"><span>首页</span><i></i></a>

            <a href="<?php echo home_url( '/' ); ?>" class="a2 active"><span>首页</span><i></i></a>

            </div>

         </li>

         <li class="i2">

         	<div class="m_cont">

         	<a href="/category/news" class="a1"><span>动态</span><i></i></a>

            <a href="/category/news" class="a2 active"><span>动态</span><i></i></a>

            </div>

         </li>

         

         <li class="i3">

            <div class="m_cont">

         	<a href="/category/culture/" class="a1"><span>文化</span><i></i></a>

            <a href="/category/culture/" class="a2 active"><span>文化</span><i></i></a>

            </div>

            <ul class="submenu">

             <li><a href="/category/culture/baike/">麻将百科</a></li>

             <li><a href="/2013/08/15/256/">麻将起源</a></li>

             <li><a href="/category/culture/localrules/">地方打法</a></li>

            </ul>

         </li>

         <li class="i4">

            <div class="m_cont">

         	<a href="/category/rules/" class="a1"><span>规则</span><i></i></a>

            <a href="/category/rules/" class="a2 active"><span>规则</span><i></i></a>

            </div>

            <ul class="submenu">

             <li><a href="/2013/08/15/235/">国际规则</a></li>

             <li><a href="/2013/08/15/249/">品级认定</a></li>

             <li><a href="/2013/08/15/235/">规则下载</a></li>

            </ul>

         </li>

         <li class="i5" style="display:none;">

            <div class="m_cont">

         	<a href="/category/match" class="a1"><span>赛事</span><i></i></a>

            <a href="/category/match" class="a2 active"><span>赛事</span><i></i></a>

            </div>

            <ul class="submenu">

             <li><a href="#">赛事</a></li>

             <li><a href="#">成绩</a></li>

             <li><a href="#">纪录</a></li>

            </ul>

         </li>

         <li class="i6">

            <div class="m_cont">

         	<a href="/category/video" class="a1"><span>视频</span><i></i></a>

            <a href="/category/video" class="a2 active"><span>视频</span><i></i></a>

            </div>

         </li>

         <li class="i7">

            <div class="m_cont">

         	<a href="/category/album/" class="a1"><span>图片</span><i></i></a>

            <a href="/category/album/" class="a2 active"><span>图片</span><i></i></a>

            </div>

         </li>

         <li class="i8">

            <div class="m_cont">

         	<a href="/about" class="a1"><span>关于</span><i></i></a>

            <a href="/about" class="a2 active"><span>关于</span><i></i></a>

            </div>

         </li>

         <li class="i9">

            <div class="m_cont">

         	<a href="/contact" class="a1"><span>联系</span><i></i></a>

            <a href="/contact" class="a2 active"><span>联系</span><i></i></a>

            </div>

         </li>

         

         <li class="i10">

            <div class="m_cont">

         	<a href="http://www.mahjong-org.azurewebsites.net/bbs/" class="a1"><span>论坛</span><i></i></a>

            <a href="http://www.mahjong-org.azurewebsites.net/bbs/" class="a2 active"><span>论坛</span><i></i></a>

            </div>

         </li>

        </ul>

      <!--menubox end-->

      </div>

    <!--wrap end-->

    </div>

 <!--main_menu end-->

 </div>