<?php /* Smarty version Smarty-3.1.12, created on 2013-07-04 15:15:08
         compiled from "D:\51workpath\majiang_baoming\view\baoming.tpl" */ ?>
<?php /*%%SmartyHeaderCode:832151d59087bbfcb6-37884634%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e5e338026bf9d09637cb6460f463c86bdd147537' => 
    array (
      0 => 'D:\\51workpath\\majiang_baoming\\view\\baoming.tpl',
      1 => 1372965273,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '832151d59087bbfcb6-37884634',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_51d59087befcf8_90834097',
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51d59087befcf8_90834097')) {function content_51d59087befcf8_90834097($_smarty_tpl) {?><!DOCTYPE html>
<html>
<head>
    <title></title>
    <script type="text/javascript" src="/static/js/jquery/jquery-1.8.3.js"></script>
    <script type="text/javascript" src="/static/js/happy/happy.js"></script>
    <script type="text/javascript" src="/static/js/jquery-validation/dist/jquery.validate.min.js"></script>
    <script type="text/javascript" src="/static/js/baoming.js"></script>

    <style type="text/css">
        .error { background-color: #f60; }
    </style>
</head>
<body>

<form id="baoming-form" method="post" action="/index.php?c=baoming&act=submit">
    <div>
        <label for="name">姓名</label>
        <input type="text" id="name" name="name" />
        <div class="display_error_here"></div>
    </div>
    <div>
        <label for="age">年龄</label>
        <input type="text" id="age" name="age" />
        <div class="display_error_here"></div>
    </div>
    <div>
        <label for="phone">电话</label>
        <input type="text" id="phone" name="phone" />
        <div class="display_error_here"></div>
    </div>
    <div>
        <label for="email">邮箱</label>
        <input type="text" id="email" name="email" />
        <div class="display_error_here"></div>
    </div>
    <div>
        <label for="address">地址</label>
        <input type="text" id="address" name="address" data-msg-required='请填写地址' />
        <div class="display_error_here"></div>
    </div>
    <div>
        <label>您的麻将基本水平</label>
        <input type="radio" id='level-1' name="level" value="1" />
        <label for="level-1">会打国标麻将</label>
        <input type="radio" id='level-2' name="level" value="2" />
        <label for="level-2">不会打国标麻将，会自己家乡的麻将</label>
        <input type="radio" id='level-3' name="level" value="3" />
        <label for="level-3">完全不会</label>
        <div class="display_error_here"></div>
    </div>
    <div>
        <label>您选择的培训地点</label>
        <input type="radio" id='site-1' name="site" value="1" />
        <label for="site-1">Scarborough</label>
        <input type="radio" id='site-2' name="site" value="2" />
        <label for="site-2">Markham</label>
        <input type="radio" id='site-3' name="site" value="3" />
        <label for="site-3">Richmond Hill</label>
        <input type="radio" id='site-4' name="site" value="4" />
        <label for="site-4">Vaughan</label>
        <div class="display_error_here"></div>
    </div>
    <div>
        <label>您的建议</label>
        <textarea name='advice'></textarea>
    </div>
    <div>
        <button type="submit">提交</button>
    </div>

    <div id='error'></div>
</form>

</body>
</html><?php }} ?>