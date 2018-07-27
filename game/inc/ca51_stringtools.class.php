<?php
/**
 * Created by PhpStorm.
 * User: Max
 * Date: 22/11/13
 * Time: 12:46 PM
 */

class Ca51_StringTools {

    /**
     * 批量去除 $_POST 中所传递的字符串值两端的空格
     * @param array $array
     * @return array
     */
    static public function trimPost($array) {
        array_walk($array, array('Ca51_StringTools', '_walk_trim') );
        return $array;
    }

    /**
     * 批量去除 $_GET 中所传递的字符串值两端的空格
     * @param array $array
     * @return array
     */
    static public function trimGet($array) {
        return self::trimPost($array);
    }

    /**
     * 去除字符串两端的空格
     * @param string $item
     */
    static private function _walk_trim(& $item) {
        $item = trim($item);
    }
} 