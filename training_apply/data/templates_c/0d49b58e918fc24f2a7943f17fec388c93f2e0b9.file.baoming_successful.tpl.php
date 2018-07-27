<?php /* Smarty version Smarty-3.1.12, created on 2013-07-08 16:36:39
         compiled from "/home/mahjong/public_html/view/baoming_successful.tpl" */ ?>
<?php /*%%SmartyHeaderCode:106524737251d7325d675123-10736694%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0d49b58e918fc24f2a7943f17fec388c93f2e0b9' => 
    array (
      0 => '/home/mahjong/public_html/view/baoming_successful.tpl',
      1 => 1373314621,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '106524737251d7325d675123-10736694',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_51d7325d6bbe20_78952583',
  'variables' => 
  array (
    'message' => 0,
    'buttons' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51d7325d6bbe20_78952583')) {function content_51d7325d6bbe20_78952583($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>大多地区国际麻将公开赛 - 消息</title>
    <link href="/static/style/style.css" rel="stylesheet" type="text/css" />

    <script type="text/javascript" src="/static/js/jquery/jquery-1.8.3.js"></script>
    <script type="text/javascript" src="/static/js/happy/happy.js"></script>
    <script type="text/javascript" src="/static/js/jquery-validation/dist/jquery.validate.min.js"></script>
    <script type="text/javascript" src="/static/js/baoming.js"></script>

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