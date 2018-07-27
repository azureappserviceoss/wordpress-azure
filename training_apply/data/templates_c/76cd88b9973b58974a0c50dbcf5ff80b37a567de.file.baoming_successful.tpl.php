<?php /* Smarty version Smarty-3.1.12, created on 2018-06-21 10:32:35
         compiled from "/home/mahjo873/public_html/training_apply/view/baoming_successful.tpl" */ ?>
<?php /*%%SmartyHeaderCode:15690544085b2bb703b2d1a5-90688822%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '76cd88b9973b58974a0c50dbcf5ff80b37a567de' => 
    array (
      0 => '/home/mahjo873/public_html/training_apply/view/baoming_successful.tpl',
      1 => 1378243836,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '15690544085b2bb703b2d1a5-90688822',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'lang' => 0,
    'message' => 0,
    'buttons' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_5b2bb703bb8993_34486791',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5b2bb703bb8993_34486791')) {function content_5b2bb703bb8993_34486791($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title>
    <?php if ($_smarty_tpl->tpl_vars['lang']->value=='zh'){?>
    大多地区国际麻将公开赛 - 消息
    <?php }else{ ?>
    Message
    <?php }?>
    </title>

    <?php if ($_smarty_tpl->tpl_vars['lang']->value=='zh'){?>
    <link href="/training_apply/static/style/style.css" rel="stylesheet" type="text/css" />
    <?php }else{ ?>
    <link href="/training_apply/static/style/style_en.css" rel="stylesheet" type="text/css" />
    <?php }?>

    <script type="text/javascript" src="/training_apply/static/js/jquery/jquery-1.8.3.js"></script>
    <script type="text/javascript" src="/training_apply/static/js/happy/happy.js"></script>
    <script type="text/javascript" src="/training_apply/static/js/jquery-validation/dist/jquery.validate.min.js"></script>
    <script type="text/javascript" src="/training_apply/static/js/baoming.js"></script>

    <style type="text/css">
        .error { background-color: #f60; }
    </style>
</head>
<body>

<div class="container wrap">
    <div class="main_form">
    <div class="done_box">
        <p><?php echo $_smarty_tpl->tpl_vars['message']->value;?>
</p>
    </div>
    <div class="clear"></div>
    <div class="done_button"><?php echo $_smarty_tpl->tpl_vars['buttons']->value;?>
</div>
    </div>
</div>



</body>
</html><?php }} ?>