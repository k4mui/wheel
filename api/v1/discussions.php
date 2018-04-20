<?php
$root = __DIR__.'/../..';
require_once("$root/includes/init.php");
require_once("$root/includes/classes/database.php");

$rt = null; // related to

if (isset($_GET['relatedTo'])) {
    $rt = $_GET['relatedTo'];    
}

$res = array(
    'error' => array(
        false,
        null
    ),
    'data' => null
);

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    $res['error'][0] = true;
    $res['error'][1] = 'Invalid request';
} else {
    if ($rt) {
        $da = data_access::get_instance();
        $result = $da->get_related_discussions($rt);
        $res['data'] = $result;
    } else {
        $res['error'][0] = true;
        $res['error'][1] = 'Invalid discussion title';
    }
}

echo json_encode($res);
?>