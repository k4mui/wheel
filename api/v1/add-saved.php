<?php
$res = array(
    'error' => array(
        false,
        null
    ),
    'data' => null
);

try {
    $root = __DIR__.'/../..';
    require_once("$root/includes/init.php");

    if (!isset($_SESSION['user_id'])) {
        throw new forbidden_access_error(__FILE__, __LINE__);
    }

    $id = null; // related to

    if (isset($_GET['discussion_id'])) {
        $id = $_GET['discussion_id'];    
    }

    switch($_SERVER['REQUEST_METHOD']) {
        case 'GET': break;
        default: throw new invalid_request_method_error(__FILE__, __LINE__);
    }
    if ($id) {
        $result = $da->add_saved($_SESSION['user_id'], $id);
        $res['data'] = array($result);
    } else {
        throw new page_not_found_error(__FILE__, __LINE__);
    }
} catch(Exception $e) {
    $res['error'][0] = true;
    if (method_exists($e, 'process_error')) { // check if custom error
        $res['error'][1] = $e->process_error(true);
    }
    error_log($e);
    $res['error'][1] = 'Unexpected error occurred. Please try again in a '
    .                  'few minutes.';
} finally {
    echo json_encode($res);
}
?>