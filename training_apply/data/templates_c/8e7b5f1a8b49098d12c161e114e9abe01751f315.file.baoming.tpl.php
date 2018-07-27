<?php /* Smarty version Smarty-3.1.12, created on 2018-06-21 03:59:08
         compiled from "/home/mahjo873/public_html/training_apply/view/baoming.tpl" */ ?>
<?php /*%%SmartyHeaderCode:4044966725b2b5acc2f6107-67454527%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8e7b5f1a8b49098d12c161e114e9abe01751f315' => 
    array (
      0 => '/home/mahjo873/public_html/training_apply/view/baoming.tpl',
      1 => 1378409186,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '4044966725b2b5acc2f6107-67454527',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'form_action' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_5b2b5acc69df83_65901665',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5b2b5acc69df83_65901665')) {function content_5b2b5acc69df83_65901665($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">



<html xmlns="http://www.w3.org/1999/xhtml">



<head>



<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />



<title>大多地区国际麻将公开赛 - 运动员及裁判免费报名</title>



<link href="/training_apply/static/style/style.css" rel="stylesheet" type="text/css" />







<style type="text/css">



.error { background-color: #f60; }



</style>







<script type="text/javascript" src="/training_apply/static/js/jquery/jquery-1.8.3.js"></script>



<script type="text/javascript" src="/training_apply/static/js/jquery-validation/dist/jquery.validate.min.js"></script>







<script type="text/javascript" src="/training_apply/static/js/baoming.js"></script>











</head>







<body>



 <div class="navtop"></div>



 <div class="header">



   <div class="wrap"></div>



 <!--header end-->



 </div>



 



 <div class="container wrap">



   <div class="block1">



    <span class="c_blue">国际麻将</span>是麻将的一种玩法，其规则为世界麻将组织2006年所制定的《麻将竞赛规则》，为现在世界许多国家和地区的大众所接受，成为一种时尚的娱乐方式。

<br />

<a class="download" target="_blank" href="/training_apply/static/download/mjgz060510.pdf">点击下载《国际麻将手册中文完整版》</a>

   <!--block1 end-->

   </div>



   



   <h2 class="title">国标麻将运动员及裁判员 免费培训</h2>



   <div class="block2" style="position: relative;">



    <p>第一届大多地区国标麻将公开赛将于明年二月开锣。为倡导这一健康文明益智类竞技体育项目，主办方定于今年8月开始进行国标麻将运动员和裁判员的免费培训。</p>



    <ul class="info">



     <li style="display:none;"><span>时间</span>8月2日09：00 开始。</li>



     <li style="display:none;"><span>地点</span><div class="add">Steel 以南： 519 church St. Toronto,ON.M4Y 2C9<br />

Steel 以北： 8400 Woodbine Ave,Markham,ON,L3R 4X7</div></li>



	 <li><div class="add2"><a href="http://www.mahjong-org.azurewebsites.net/calendar.html" target="_blank" style="background: none repeat scroll 0 0 #F25000; border-radius: 8px 8px 8px 8px; padding: 6px 20px; color: #FFFFFF; text-decoration: none;">点击查看培训日程表</a></div></li>



     <li><span>报名方式</span>填写本页面的报名表，提交即可。</li>



    </ul>



    <div class="zlogo">

     <dl>

      <dt>培训场地赞助：</dt>

      <dd class="l1"><a href="http://www.everydaybadminton.com/" target="_blank" title="天天羽球馆">天天羽球馆</a></dd>

      <dd class="l2"><a href="http://www.honeydating.ca/" target="_blank" title="甜蜜婚介">甜蜜婚介</a></dd>

     </dl>

    </div>



    <div class="phone">



     详情咨询：<span class="f_no">1-855-688-3388</span>



    </div>



   <!--block2 end-->



   </div>



   



   <h2 class="title  mt30">免费培训 申请表</h2>



   <div class="main_form">



     <form id="baoming-form" method="post" action="<?php echo $_smarty_tpl->tpl_vars['form_action']->value;?>
">











    <ul class="f01">



    	<li class="h">



        <label for="name">姓名：</label>



        <input type="text" id="name" name="name" />



        <div class="display_error_here"></div>



    	</li>



    	<li class="h">



        <label for="age">年龄：</label>



        <input type="text" id="age" name="age" />



        <div class="display_error_here"></div>



    	</li>



    	<li class="h">



        <label for="phone">电话：</label>



        <input type="text" id="phone" name="phone" />



        <div class="display_error_here"></div>



    	</li>



    	<li class="h">



        <label for="email">邮箱：</label>



        <input type="text" id="email" name="email" />



        <div class="display_error_here"></div>



    	</li>



    	<li class="a">



        <label for="address">地址：</label>



        <input type="text" id="address" name="address" />



        <div class="display_error_here"></div>



    	</li>



    </ul>



    



    <div class="clear"></div>



    



    <div class="s_box">



        <label class="tt">您的麻将基本水平：（选择下面的选项）</label>



        <input type="radio" id='level-1' name="level" value="1" />



        <label for="level-1">会打国标麻将</label>



        <input type="radio" id='level-2' name="level" value="2" />



        <label for="level-2">不会打国标麻将，会自己家乡的麻将</label>



        <input type="radio" id='level-3' name="level" value="3" />



        <label for="level-3">完全不会</label>



        <div class="display_error_here"></div>



    </div>



    <div class="s_box">



        <label class="tt">您选择的培训地点：（选择下面的选项）</label>



        <input type="radio" id='site-1' name="site" value="1" />



        <label for="site-1">Scarborough</label>



        <input type="radio" id='site-2' name="site" value="2" />



        <label for="site-2">Markham</label>



        <div class="display_error_here"></div>



    </div>



    <div class="jy">



        <label>您的建议：</label>



        <textarea name='advice'></textarea>



    </div>







    <div class="jj_lxr clearfix">



        <h3>紧急联系人</h3>



        <ul>



            <li>



            <label for="emergency-name">联系人姓名:</label>



            <input type="text" id="emergency-name" name="emergency_name" />



            <div class="display_error_here"></div>



            </li>



            <li>



            <label for="emergency-phone">联系人电话:</label>



            <input type="text" id="emergency-phone" name="emergency_phone" />



            <div class="display_error_here"></div>



            </li>



        </ul>



    </div>



    



    <div class="clear"></div>







    <div class="sm">



        <h2>国标麻将免费培训班免责声明</h2>



        <ol>



            <li>所有参与培训者，应当严格注意和遵守个人及交通安全。活动过程中个人如发生任何意外，组织者不承担任何连带责任。</li>



            <li>参加培训人员发生任何民事责任或是刑事责任，都将自负、自理承担，组织者与其他参与活动人员不承担任何连带责任。</li>



            <li>参加培训人员在任何时候都需本着和睦相处、交流为主，不得恶言相对及因为任何事情起引起闹事、殴打行为。如发生不愉快应遵从群体参与活动人员劝解，若劝解无效，群体任何参与活动人员都有权报警，发生民事责任全由肇事者自行承担所有连带责任，组织者以及参与人员不负责任何连带责任。</li>



            <li>参加培训人员，活动过程中个人出现心脏病或各种突发性事件或不可预测事件，全由自行承担全部责任，组织者或群体所有参与人员不承担任何连带责任。</li>



            <li>一经报名确认参加培训，则视为默认本『免责声明』。其他未尽事宜，组织者有权补充和保留最终解释权。</li>



        </ol>



    </div>



    



    <div class="ty">



    	<input type="checkbox" id="legal-info" name='legal_info' value="1" />



    	<label for="legal-info">我已阅读，并且同意以上声明中的所有条款</label>



    	<div class="display_error_here"></div>



    </div>







    <div class="ty">



        <button type="submit">确认提交申请</button>



    </div>







    <div id='error'></div>



   </form>



   <!--main_form end-->



   </div>



   



   <div class="clear"></div>



   



   <h2 class="title mt30" style="display: none;">国际麻将记分方式<span>（和牌后的各方得分）</span></h2>



   <div class="block3" style="display: none;">



     <div class="left">



     1、胜方： <br />



自摸和牌：(8+番数)×3<br />



别人点炮和：8×3+番数



     </div>



     <div class="right">



     2、负方：<br /> 



点炮者：-(8+番数)<br />



非点炮者：-8   <br />            自摸和牌：-(8+番数)



     </div>



   </div>



   



   <div class="clear"></div>



   



   <h2 class="title mt30" style="display: none;">国际麻将规则要点</h2>



   <div class="block4">



    <ol style="display: none;">



     <li>一场比赛打四圈，不设连庄，因此每场一定是打16局</li>



     <li>没有任何和牌限制：即使下家同巡跟打你刚打过的牌，一样可以和</li>



     <li>第三圈（西圈）开始前，东家跟南家，西家跟北家对调座位，由原本的南家先做庄</li>



     <li>暗杠时，4只牌牌面向下，不用给其他人看，唯在牌局完结后，不论有否和牌，都需打开供对手查阅</li>



    </ol>



    



    



   </div>



 <!--container end-->



 </div>







 <div class="clear"></div>







 <div class="footer">



 &copy; 2013 第一届大多地区国标麻将公开赛组委会



 </div>







</body>



</html>



<?php }} ?>