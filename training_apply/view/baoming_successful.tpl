<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title>
    {if $lang == 'zh'}
    大多地区国际麻将公开赛 - 消息
    {else}
    Message
    {/if}
    </title>

    {if $lang == 'zh'}
    <link href="/training_apply/static/style/style.css" rel="stylesheet" type="text/css" />
    {else}
    <link href="/training_apply/static/style/style_en.css" rel="stylesheet" type="text/css" />
    {/if}

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
        <p>{$message}</p>
    </div>
    <div class="clear"></div>
    <div class="done_button">{$buttons}</div>
    </div>
</div>



</body>
</html>