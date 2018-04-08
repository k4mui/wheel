<?php
$dbname = 'wheelim';
$dbpwd = 'qwertY33';
$dbserver = '127.0.0.1';
$dbuser = 'root';

define('DEBUG', false);
define('WHEEL_ROOT', '/home/obi/dev/localhost/wheel');

//require(WHEEL_ROOT.'/includes/classes/user.php');

error_reporting(E_ALL);
ini_set('display_errors', DEBUG ? 'On' : 'Off');
ini_set('display_startup_errors', DEBUG ? 'On' : 'Off');
ini_set('error_log', WHEEL_ROOT.'/errors.log');
ini_set('log_errors', DEBUG ? 'Off' : 'On');
ini_set('log_errors_max_len', '0');
ini_set('file_uploads', 'On');
ini_set('post_max_size', '13M');
ini_set('upload_max_filesize', '12M');

session_start();

require_once("classes/database.php");
require_once("client.php");


$da = data_access::get_instance();
if (!$da) {
  die('Something is fishy!');
}

/* TIMEZONE & COUNTRY SETTING
*/
$ud_timezone = isset($_SESSION['tz']) ? $_SESSION['tz'] : null;
$ud_country_code = isset($_SESSION['cc']) ? $_SESSION['cc'] : null;

if (!$ud_timezone || !$ud_country_code) {
  $user_info = get_client_info();
  if (!$ud_country_code) {
    $ud_country_code = $user_info['country'];
    $_SESSION['cc'] = $ud_country_code;
    $da->insert_country($ud_country_code,
                        $user_info['country_name'],
                        $user_info['continent_code']);
  }
  if (!$ud_timezone) {
    $ud_timezone = $user_info['timezone'];
    $_SESSION['tz'] = $ud_timezone;
  }
}
//date_default_timezone_set($ud_timezone);


/* USER SETTING
*/
//print_r($_SESSION);
?>
