<?php
require_once(__DIR__.'/../validation.php');

$errors = array();

$ue = isset($_POST['ue']) ? $_POST['ue'] : null;
$us = isset($_POST['us']) ? $_POST['us'] : null;

if (!$ue) {
    $errors[] = 'Username cannot be empty.';
}
check_password($us, $errors);
?>