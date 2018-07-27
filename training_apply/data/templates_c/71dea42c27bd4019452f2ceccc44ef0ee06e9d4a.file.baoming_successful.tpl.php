<?php /* Smarty version Smarty-3.1.12, created on 2013-09-03 17:30:38
         compiled from "/home/mahjong/public_html/training_apply/view/baoming_successful.tpl" */ ?>
<?php /*%%SmartyHeaderCode:725107353520cec5eb9a680-24079136%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '71dea42c27bd4019452f2ceccc44ef0ee06e9d4a' => 
    array (
      0 => '/home/mahjong/public_html/training_apply/view/baoming_successful.tpl',
      1 => 1378243836,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '725107353520cec5eb9a680-24079136',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_520cec5ebcad49_02529981',
  'variables' => 
  array (
    'lang' => 0,
    'message' => 0,
    'buttons' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_520cec5ebcad49_02529981')) {function content_520cec5ebcad49_02529981($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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