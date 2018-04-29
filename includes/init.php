<?php
define('DEBUG', false);
//define('WHEEL_ROOT', '/home/obi/dev/localhost/wheel');
define('WHEEL_ROOT', dirname(__FILE__).'/..');

error_reporting(E_ERROR /*| E_WARNING*/ | E_PARSE | E_NOTICE);
ini_set('display_errors', DEBUG ? 'On' : 'Off');
ini_set('display_startup_errors', DEBUG ? 'On' : 'Off');
ini_set('error_log', WHEEL_ROOT.'/errors.log');
ini_set('log_errors', DEBUG ? 'Off' : 'On');
ini_set('log_errors_max_len', '0');
ini_set('file_uploads', 'On');
ini_set('post_max_size', '13M');
ini_set('upload_max_filesize', '12M');

require_once('classes/exceptions.php');
require_once('classes/database.php');

// get mysql connection
try {
    $da = data_access::get_instance();
} catch(Exception $e) {
    if (method_exists($e, 'process_error')) { // check if custom error
        $e->process_error();
    }
    error_log($e);
    die('Unexpected error occurred. Please try again in a few minutes.');
}

require_once('client.php');
require_once('session_handler.php');

?>
