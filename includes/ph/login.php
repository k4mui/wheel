<?php
require_once(__DIR__.'/../validation.php');

$errors = array();

$ue = isset($_POST['ue']) ? $_POST['ue'] : null;
$us = isset($_POST['us']) ? $_POST['us'] : null;
$rm = isset($_POST['rm']) ? true : false;

check_password($us, $errors);
if (!$errors) {
    $user = $da->get_user($ue);
    if (!$user) {
        $errors[] = 'No account is associated with this username or email.';
    } elseif ($user['user_secret'] !== md5($us)) {
        $errors[] = 'Wrong password.';
    } else { // success
        if ($rm) {
            $cookie_value = $da->get_session_id($user['user_id']);
            setcookie('wsess', $cookie_value, time() + (86400 * 30), '/');
        }
        $_SESSION['user_id'] = $user['user_id'];
        header('Location: /index.php');
        die();
    }
}
?>