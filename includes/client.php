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
    return file_get_contents('https://api.ipify.org/');
    return $ip;
}

function load_client_info(array & $client_info) {
    $user_info = json_decode(file_get_contents('https://ipapi.co/'.get_client_ip().'/json/'), true);
    $client_info['utc_offset'] = $user_info['utc_offset'];
    $client_info['country'] = $user_info['country'];
}
?>