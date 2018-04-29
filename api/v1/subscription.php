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

    if (isset($_GET['category_id'])) {
        $id = $_GET['category_id'];    
    }

    if (isset($_GET['action'])) {
        $action = $_GET['action'];
    }

    switch($_SERVER['REQUEST_METHOD']) {
        case 'GET': break;
        default: throw new invalid_request_method_error(__FILE__, __LINE__);
    }
    if ($id) {
        switch($action) {
            case 0:
                $result = $da->is_subscribed($_SESSION['user_id'], $id);
                $result = !$result ? 'unsubscribed' : 'subscribed';
                break;
            case 1:
                $result = $da->add_subscription($_SESSION['user_id'], $id);
                $result = !$result ? 'unsubscribed' : 'subscribed';
                break;
            case -1:
                $result = $da->remove_subscription($_SESSION['user_id'], $id);
                $result = $result ? 'unsubscribed' : 'subscribed';
                break;
            default:
                $result = false;
                $res['error'] = true;
                break;
                //throw new api_call_error(__FILE__);
        }
        $res['data'] = array(
            'state' => $result
        );
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