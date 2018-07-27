<?php /* Smarty version Smarty-3.1.12, created on 2014-02-05 14:24:02
         compiled from "/home/mahjong/public_html/training_apply/view/dump.tpl" */ ?>
<?php /*%%SmartyHeaderCode:409745223524b201a190fb0-42532072%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '20634b4bbdedcc630f5898fa122b1578d17e3ec2' => 
    array (
      0 => '/home/mahjong/public_html/training_apply/view/dump.tpl',
      1 => 1391628239,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '409745223524b201a190fb0-42532072',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_524b201a382c53_14380249',
  'variables' => 
  array (
    'form_action' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_524b201a382c53_14380249')) {function content_524b201a382c53_14380249($_smarty_tpl) {?><?php if (!is_callable('smarty_function_html_select_date')) include '/home/mahjong/public_html/training_apply/thirdpart/smarty/libs/plugins/function.html_select_date.php';
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>报名者信息导出</title>

<link href="/training_apply/static/style/dump.css" rel="stylesheet" type="text/css" />

</head>



<body>

<h1>报名者信息导出</h1>

<form action="<?php echo $_smarty_tpl->tpl_vars['form_action']->value;?>
" method="post">

    <div>
        <label>导出指定日期之后的数据</label>
        <?php echo smarty_function_html_select_date(array('field_order'=>'YMD','start_year'=>'2013'),$_smarty_tpl);?>

    </div>

    <div>
        <label>密码</label>
        <input type='password' name='password' value="" />
    </div>

    <div>
        <button type="submit">立刻导出</button>
    </div>


</form>


</body>

</html>

<?php }} ?>