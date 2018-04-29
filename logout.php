<?php
try {
  $root = dirname(__FILE__);
  require "$root/includes/init.php";

  switch($_SERVER['REQUEST_METHOD']) {
    case 'GET': break;
    default: throw new invalid_request_method_error(__FILE__, __LINE__);
  }


  if (!isset($_SESSION['user_id'])) {
    throw new forbidden_access_error(__FILE__, __LINE__);
  } else {
    if (isset($_SESSION['cookie_login'])) {
      $da->remove_user_session($_SESSION['user_id']);
      unset($_SESSION['cookie_login']);
    }
    $page = isset($_SERVER['HTTP_REFERER']) 
    ?       $_SERVER['HTTP_REFERER'] : '/index.php';
    unset($_SESSION['user_id']);
    header("Location: $page");
    die();
  }
} catch(Exception $e) {
  if (method_exists($e, 'process_error')) { // check if custom error
    $e->process_error();
  }
  error_log($e);
  die('Unexpected error occurred. Please try again in a few minutes.');
}

?>