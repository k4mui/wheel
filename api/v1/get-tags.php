<?php
$root = __DIR__.'/../..';
require_once("$root/includes/init.php");
require_once("$root/includes/classes/database.php");

$fd = array();
if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    die('{"error":true, "error_message": "invalid request"}');
}
$did = isset($_GET['id']) ? (int)$_GET['id'] : null;
if ($did) {
    $da = data_access::get_instance();
    $replies = $da->get_tags_by_post_id($did);
    $fd['data'] = $replies;
    //$replies['reply_count'] = count($replies);
    //$replies['error'] = false;
    //$replies['error_message'] = null;
    echo json_encode($fd);
} else {
    die('{"error":true, "error_message": "invalid request"}');
}

?>