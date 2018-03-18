<?php
require_once(__DIR__."/pre_processing.php");


function check_password($pwd, & $errors) {
  if (strlen($pwd) < 8) {
      $errors[] = "Password too short! Must be at least 8 characters long.";
  }
  if (!preg_match("#[0-9]+#", $pwd)) {
      $errors[] = "Password must include at least one number (0-9).";
  }
  if (!preg_match("#[A-Z]+#", $pwd)) {
      $errors[] = "Password must include at least one upper-case letter (A-Z).";
  }
  if (!preg_match("#[a-z]+#", $pwd)) {
      $errors[] = "Password must include at least one lower-case letter (A-Z).";
  }
}

function check_password_pair($pwd1, $pwd2, & $errors) {
  check_password($pwd1, $errors);
  if ($pwd1 && $pwd1 !== $pwd2) {
    $errors[] = "Password and Confirm Password do not match.";
  }
}

function check_email_address($email, & $errors) {
  if ($email) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors[] = "'$email' is not a valid email address.";
    }
  } else {
    $errors[] = "Email address cannot be empty.";
  }
}

function check_discussion_title($title, & $errors) {
  $len = strlen($title);
  if ($len < 8) {
    $errors[] = "Title must be at least 8 characters long";
  } else if ($len > 128) {
    $errors[] = "Title must be at most 128 characters long";
  }
}
function check_discussion_text($text, & $errors) {
  $len = strlen($text);
  if ($len < 12) {
    $errors[] = "Discussion content must be at least 12 characters long.";
  } else if ($len > 4096) {
    $errors[] = "Discussion content must be at most 4,096 characters long.";
  }
}
function check_discussion_attachment($image, & $errors) {
  if (isset($image['tmp_name']) && $image['tmp_name']) {
    $size = getimagesize($image['tmp_name']);
    if ($size === false) {
      $errors[] = 'The attachment is invalid.';
    } else if ($size > (12*1048576)) {
      $errors[] = 'The attachment is too big. Maximum size is 12mb.';
    }
  } else {
    $errors[] = 'A relevant image must be attached.';
  }
}

function check_register_inputs(& $inputs, & $errors) {
  $inputs["email_address"] = isset($inputs["email_address"]) ? sanitize_email($inputs["email_address"]) : null;
  $inputs["password"] = isset($inputs["password"]) ? $inputs["password"] : null;
  $inputs["confirm_password"] = isset($inputs["confirm_password"]) ? $inputs["confirm_password"] : null;

  check_email_address($inputs['email_address'], $errors);
  check_password_pair($inputs['password'], $inputs['confirm_password'], $errors);
}

function check_login_inputs(& $inputs, & $errors) {
  $inputs["email_address"] = isset($inputs["email_address"]) ? sanitize_email($inputs["email_address"]) : null;
  $inputs["password"] = isset($inputs["password"]) ? $inputs["password"] : null;

  check_email_address($inputs['email_address'], $errors);
}
?>
