<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: check.php 33460 2013-06-19 08:03:48Z andyzheng $
 */

@include '../../../data/sysdata/cache_mobile.php';

echo str_replace(array('"X3"', '"X3 RC"', '"X3 Beta"'), '"X2.5"', $mobilecheck);

?>