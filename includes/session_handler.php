<?php
session_start();

$client_info = array();
$client_ip = get_client_ip();
$client_agent = 'nil';

if (isset($_SERVER['HTTP_USER_AGENT']) && $_SERVER['HTTP_USER_AGENT']) {
    if (strlen($_SERVER['HTTP_USER_AGENT']) <= 255) {
        $client_agent = $_SERVER['HTTP_USER_AGENT'];
    } else {
        $client_agent = 'too large';
    }
}

if (!isset($_SESSION['timezone_id'])) { // session info not set
    load_client_info($client_info);
    if ($client_info) {
        $country = $da->get_country($client_info['country']);
        $timezone = $da->get_timezone($client_info['utc_offset']);
        $_SESSION['timezone_id'] = $timezone['id'];
        $_SESSION['utc_offset'] = $client_info['utc_offset'];
        $_SESSION['timezone_name'] = substr($timezone['display_text'], 1, 10);
        $_SESSION['country_id'] = $country['id'];
        $_SESSION['country_name'] = ucfirst($country['country_name']);
        $_SESSION['client_id_checksum'] = md5($client_ip.$client_agent);
        $_SESSION['client_id'] = $da->get_client_id($client_ip, $client_agent);
    } else { // could not fetch client info
        throw new external_service_error(__FILE__, __LINE__);
    }
} else { // timezone, country already set
    if ($_SESSION['client_id_checksum'] !== md5($client_ip.$client_agent)) {
        // client info doesn't match previous info
        $_SESSION['client_id'] = $da->get_client_id($client_ip, $client_agent);
    }
}

//print_r($_SESSION);

?>