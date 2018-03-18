<?php
require_once(__DIR__."/../models/user.php");
require_once(__DIR__."/../lib/load_config.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$user = NULL;

session_start();

function set_user($u) {
  global $user;

  if (!isset($_SESSION["user"])) {
    $_SESSION["user"] = $u ? $u : new user;
  } else if ($u) {
    unset($_SESSION["user"]);
    $_SESSION["user"] = $u;
  }
  $user = $_SESSION["user"];
}

set_user(NULL);
?>
