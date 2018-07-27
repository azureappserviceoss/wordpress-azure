<?php

/**

 * Created by PhpStorm.

 * User: Max

 * Date: 25/10/13

 * Time: 3:26 PM

 */

require './inc/init.inc.php';

?>

<!DOCTYPE html>

<html>

<head>

    <meta charset="utf-8">

    <title><?php echo $l['game_name']; ?> - <?php echo $l['official_website_title']; ?></title>

</head>



<body>

<?php

if ($uid > 0) {

    $user_country = get_user_country();

    $cookie_expired = time() + 3600 * 24 * 30;

    setcookie('mahjoing_user_country', $user_country, $cookie_expired);



    //如果 cookie 中保存了语言选项，则跳过语言选择表单

    if ( isset( $_COOKIE['mahjoing_game_lang'] ) ) {

        header('Location: start.php');

    }



    include './templates/chose_lang.tpl.php';

} else {

    $user_country = false;

    include './templates/login_form.tpl.php';

}

?>

</body>

</html>

