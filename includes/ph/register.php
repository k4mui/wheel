<?php
require_once(__DIR__.'/../validation.php');
require_once(__DIR__.'/../classes/database.php');

$errors = array();

$un = isset($_POST['un']) ? strtolower($_POST['un']) : null;
$ue = isset($_POST['ue']) ? strtolower($_POST['ue']) : null;
$us = isset($_POST['us']) ? $_POST['us'] : null;
$cus = isset($_POST['cus']) ? $_POST['cus'] : null;
$dob_d = isset($_POST['dob_d']) ? (int)$_POST['dob_d'] : null;
$dob_m = isset($_POST['dob_m']) ? (int)$_POST['dob_m'] : null;
$dob_y = isset($_POST['dob_y']) ? (int)$_POST['dob_y'] : null;
$dob = "$dob_y-$dob_m-$dob_d";

check_date_of_birth($dob_d, $dob_m, $dob_y, $dob, $errors);
check_email_address($ue, $errors);
check_username($un, $errors);
check_password_pair($us, $cus, $errors);

if (!$errors) {
    $da = data_access::get_instance();
    if ($da) {
        $res = $da->insert_user($un, $ue, md5($us), $dob);
        unset($da);
        if ($res) {
            die('Registration successful. You can login to your account now.');
        } else {
            die('Username or email already taken.');
        }
    } else {
        die('DB Connect error.');
    }
}
?>