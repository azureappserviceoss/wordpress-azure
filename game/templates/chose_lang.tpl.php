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
<div class="topnav"><?php include('top_nav.tpl.php'); ?></div>

<div class="mid_minibox clearfix">
    <div class="toplogo clearfix"></div>
    <div class="formbox formboxlanguage clearfix">

        <form action='./start.php' method="post" id="choose-language">
            <ul>
                <li><a href="#" onclick="chooseLanguage('zh')">中文版</a></li>
                <li><a href="#" onclick="chooseLanguage('en')">English</a></li>
            </ul>

            <!-- zh || en-->
            <input type="hidden" name="user_lang" value="zh" />
            <input type="hidden" name="user_id" value="<?php echo $uid; ?>" />
            <input type="hidden" name="user_country" value="<?php echo $user_country; ?>" />
        </form>

        <!--formbox end-->
    </div>
    <div class="minibox_bt"></div>
    <!--mid_minibox end-->
</div>

</body>
</html>