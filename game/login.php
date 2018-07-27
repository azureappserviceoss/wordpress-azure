<?php

/**

 * Created by PhpStorm.

 * User: Max

 * Date: 25/10/13

 * Time: 4:33 PM

 */

require './inc/init.inc.php';



extract( $_POST );



#有关同步登陆，参考 <http://dev.discuz.org/wiki/index.php?title=Uc_user_synlogin>

list($uid, $displayname) = uc_user_login($username, $password);

if ($uid > 0) {

    $html = uc_user_synlogin($uid);

    $msg = '登录成功';

    $jump_time = 3;

} else {

    $html = '';

    $msg = '登录失败，请再次尝试。';

    $jump_time = 2;

}

?>

<!DOCTYPE html>

<html>

    <head>

        <meta charset="utf-8">

        <meta http-equiv="refresh" content="<?php echo $jump_time; ?>;url=./index.php">

        <title>跳转中……</title>

        <?php echo $html; //这里输出的 js 脚本用于同步登录，因此必须留给它充足的时间运行完毕，然后再进行页面跳转 ?>

    </head>

    <body>

        <p><?php echo $msg; ?>，页面跳转中……</p>

    </body>

</html>

