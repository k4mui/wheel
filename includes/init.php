<?php
date_default_timezone_set('UTC');

define('DEBUG', true);
define('WHEEL_ROOT', '/home/obi/dev/localhost/wheel');

require(WHEEL_ROOT.'/includes/classes/user.php');

error_reporting(E_ALL);
ini_set('display_errors', DEBUG ? 'On' : 'Off');
ini_set('display_startup_errors', DEBUG ? 'On' : 'Off');
ini_set('error_log', WHEEL_ROOT.'/errors.log');
ini_set('log_errors', DEBUG ? 'Off' : 'On');
ini_set('log_errors_max_len', '0');
ini_set('file_uploads', 'On');
ini_set('post_max_size', '13M');
ini_set('upload_max_filesize', '12M');

$dbname = 'wheel';
$dbpwd = '555-137';
$dbserver = '127.0.0.1';
$dbuser = 'wheel_admin';

/* SESSION START
*/
session_start();

/* USER OBJECT SETUP
*/
$user = null;

if (!isset($_SESSION['u'])) {
  $_SESSION['u'] = new user;
}
$user = $_SESSION['u'];

?>
