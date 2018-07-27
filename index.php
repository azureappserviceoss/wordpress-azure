<?php
/**
 * Front to the WordPress application. This file doesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package WordPress
 */

/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 * @var bool
 */


//$user_lang = "zh";

//$user_country = "CA";

//$user_id = 12345;

//$user_session_id = "oCBxlr";
//$timestamp = time();
//$st=$timestamp.KEY;
//$username="horse";
//$code = md5($user_id . $user_country . $user_lang . $user_session_id. $username . $timestamp . KEY);


define('WP_USE_THEMES', true);

/** Loads the WordPress Environment and Template */
require( dirname( __FILE__ ) . '/wp-blog-header.php' );
