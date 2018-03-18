<?php

$_links = array(
  "register" => "/register.php",
  "login" => "/login.php",
  "logout" => "/logout.php",
  "admin_panel" => "/admin_panel.php",
  "mod_panel" => "/mod_panel.php",
  "profile" => "/profile.php",
  "inbox" => "/inbox.php"
);
$_icons = array(
  "register" => "user-plus",
  "login" => "sign-in-alt",
  "logout" => "sign-out-alt",
  "admin_panel" => "/admin_panel.php",
  "mod_panel" => "/mod_panel.php",
  "profile" => "user",
  "inbox" => "envelope"
);
$_texts = array(
  "register" => "Register",
  "login" => "Login",
  "logout" => "Logout",
  "admin_panel" => "Admin Panel",
  "mod_panel" => "Moderator Panel",
  "profile" => "{USERNAME}",
  "inbox" => "Inbox {UNREAD_COUNT}"
);
$_d = array(
  "re"
);
$_item = "<li><i class='fas fa-{ICON}'></i> <a href='{LINK}'>{TEXT}</a></li>";
$_anon = array("register", "login");
$_admin = array("admin_panel", "inbox", "profile", "logout");
$_mod = array("mod_panel", "inbox", "profile", "logout");
function getUserPanel(& $_user) {
  $_str = "";
  if ($_user->getRole() == "anon") {
    foreach ($_anon as $v) {
      $_str .= "<li><i class='fas fa-'";
    }
    //return '<li><i class="fas fa-user-plus"></i> <a href="register.php">Register</a></li><li><i class="fas fa-sign-in-alt"></i> <a href="login.php">Login</a></li>';
  }
  return '<li><i class="fas fa-envelope"></i> <a href="inbox.php">Inbox ('.$_user->getUnreadCount().')</a></li><li><i class="fas fa-user"></i> <a href="profile.php?id='.$_user->getId().'">'.$_user->getUsername().'</a></li><li><i class="fas fa-sign-out-alt"></i> <a href="logout.php">Logout</a></li>';
}
?>
