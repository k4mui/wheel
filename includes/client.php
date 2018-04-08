<?php

function get_client_ip() {
    $ip = $_SERVER['REMOTE_ADDR'];
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) &&
        preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s',
                    $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)
    ) {
    foreach ($matches[0] as $xip) {
        if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip)) {
        $ip = $xip;
        break;
        }
    }
    } elseif (isset($_SERVER['HTTP_CLIENT_IP']) &&
            preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/',
                        $_SERVER['HTTP_CLIENT_IP'])
    ) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['HTTP_CF_CONNECTING_IP']) &&
            preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/',
                        $_SERVER['HTTP_CF_CONNECTING_IP'])
    ) {
    $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
    } elseif (isset($_SERVER['HTTP_X_REAL_IP']) &&
            preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/',
                        $_SERVER['HTTP_X_REAL_IP'])
    ) {
    $ip = $_SERVER['HTTP_X_REAL_IP'];
    }
    return $ip;
}

function get_client_info() {
    //$api_token = '45769edaba6feed4489cc3bf5340983817d2855c64eea6b7';
    $ip = get_client_ip();
    //echo $ip;
    //$user_info = json_decode(file_get_contents("https://usercountry.com/v1.0/json/$ip?token=$api_token"), true);
    $user_info = json_decode(file_get_contents("https://ipapi.co/$ip/json/"), true);
    //print_r($user_info);
    /*$city = $user_info['region']['city'];
    $country = $user_info['country']['alpha-2'];
    $timezone = $user_info['timezone']['name'];
    echo "$city - $country - $timezone";*/
    return $user_info;
}

?>