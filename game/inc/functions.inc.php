<?php
/**
 * Created by PhpStorm.
 * User: Max
 * Date: 25/10/13
 * Time: 4:08 PM
 */

/**
 * 获取用户的 IP 地址，并通过 <http://www.wipmania.com/en/api/> 所提供的 API 查询对应的国家代码
 *
 * @param  void
 * @return string 如果返回的国家代码为“XX”则表示获取失败；具体的国家代码请参考 <http://www.wipmania.com/en/map/>
 */
function get_user_country() {
    $user_ip = $_SERVER['REMOTE_ADDR'];
    $curl_url = "http://api.wipmania.com/{$user_ip}";
    $ch = curl_init($curl_url);

    if ($ch !== false) {
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $country_code = curl_exec($ch);
    } else {
        $country_code = 'CA';
    }
    curl_close($ch);

    return $country_code;
}