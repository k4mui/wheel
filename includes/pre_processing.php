<?php
function strsanitize($str) {
  return strip_tags(trim($str));
}
function sanitize_email($email) {
  $email = preg_replace('/\s/', '', strip_tags($email));
  return $email;
}
?>
