<?php
$root = __DIR__.'/../..';
require_once("$root/includes/init.php");
require_once("$root/includes/classes/database.php");

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
    $discussion_title = isset($_GET['title']) ? $_GET['title'] : null;

    if ($discussion_title) {
        $da = data_access::get_instance();
        $result = $da->get_discussion_titles();
        $res['data'] = $result;
    } else {
        $res['error'][0] = true;
        $res['error'][1] = 'Invalid discussion title';
    }
}

echo json_encode($res);

?>