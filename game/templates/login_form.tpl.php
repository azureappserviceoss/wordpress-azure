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

    <div class="formbox formboxlogin clearfix">



        <form action="./login.php" method="post">

            <ul>

                <li><input type="text" name="username" value="用户名 / Username" /></li>

                <li><input type="password" name="password" value="password" /></li>

                <li><button type="submit">登录 Login</button></li>

                

                <li><a href="http://www.mahjong-org.azurewebsites.net/bbs/member.php?mod=register" target="_blank" class="reg">注册 Register</a></li>

            </ul>

        </form>



        <!--formbox end-->

    </div>

    <div class="minibox_bt"></div>

    <!--mid_minibox end-->

</div>





</body>

</html>