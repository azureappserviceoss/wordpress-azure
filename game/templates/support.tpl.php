<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo $l['game_name']; ?> - <?php echo $l['official_website_title']; ?></title>
    <link href="style/style.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="js/jquery-1.8.3.js"></script>
    <script type="text/javascript" src="js/mahjonggame.js"></script>
</head>

<body>

<div class="mid_minibox mid_minibox_support clearfix">
    <div class="toplogo clearfix supportboxtt"></div>
    <div class="formbox formboxlanguage clearfix">
    <div class="supportbox">

    <?php if ( strlen($message) == 0 ): ?>

    <!-- 用户反馈 Begin -->
    <form id="user-support-form" action="user_support.php?op=send" method="post" enctype="multipart/form-data">
        <div class="li1">
            <label><?php echo $l['user_support_table']; ?>:</label>
            <input type="text" name="table" />
        </div>
        <div class="li1">
            <label><?php echo $l['user_support_hand']; ?>:</label>
            <input type="text" name="hand" />
        </div>
        <div class="li1">
            <label><?php echo $l['user_support_subject']; ?>:</label>
            <input type="text" name="subject" />
        </div>
        <div class="li1 li3">
            <label><?php echo $l['user_support_email']; ?>:</label>
            <input type="text" name="email" value="<?php echo $user_email; ?>" />
        </div>
        <div class="li2">
            <label><?php echo $l['user_support_content']; ?>:</label>
            <textarea rows="5" cols="30" name="content"></textarea>
        </div>
        
        <div class="li4">
            <label><?php echo $l['user_support_screen']; ?>:</label>
            <input type="file" name="attachment" />
            <div class="tips"><?php echo $l['user_support_form_attachment']; ?></div>
        </div>
        <div class="btns">
            <button type="button" id="button" onclick="sendUserSupport()"><?php echo $l['user_support_btn']; ?></button>
        </div>
        <p class="z"><?php echo $l['user_support_note']; ?></p>

        <input type="hidden" name="uid" value="<?php echo $user_id; ?>" />
        <input type="hidden" name="username" value="<?php echo $username; ?>" />
    </form>
    <!-- 用户反馈 End -->

    <?php else: ?>

    <!-- 用户反馈 Begin -->
    <div id="message-wrap">
        <p><?php echo $message; ?></p>
        <p>
            <?php if (! $found_error): ?>
            <a href="javascript:window.close();" style="display: block; clear: both;">关闭窗口</a>
            <?php else: ?>
            <a href="javascript:windows.history.back();">返回</a>
            <?php endif; ?>
        </p>
    </div><!-- /#message-wrap -->
    <!-- 用户反馈 End -->

    <?php endif; ?>
    <!--supportbox end-->
    </div>
    <!--formbox end-->
    </div>
    <div class="minibox_bt"></div>
    <!--mid_minibox end-->
</div>

</body>
</html>