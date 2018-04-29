<?php
require_once(__DIR__.'/pre_processing.php');


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
      $errors[] = "<b>$email</b> is not a valid email address.";
    }
  } else {
    $errors[] = "Email address cannot be empty.";
  }
}

function check_date_of_birth($d, $m, $y, $dob, & $errors) {
  $c = count($errors);
  if ($d===null) {
    $errors[] = 'Date cannot be empty.';
  } elseif (!$d) {
    $errors[] = 'Date is invalid.';
  }
  if ($m===null) {
    $errors[] = 'Month cannot be empty.';
  } elseif (!$m) {
    $errors[] = 'Month is invalid.';
  }
  if ($y===null) {
    $errors[] = 'Year cannot be empty.';
  } elseif (!$y) {
    $errors[] = 'Year is invalid.';
  }
  if (!is_valid_date($dob) && (count($errors)===$c)) {
    $errors[] = "<b>$dob</b> is not a valid date.";
  }
  $d1 = new DateTime(date('Y-m-d'));
  $d2 = new DateTime($dob);

  $diff = $d2->diff($d1);

  if ($diff->y < 13) {
    $errors[] = 'You must be at least 13 years old to join this site.';
  }
}

function check_discussion_attachment($image, & $errors) {
  switch($image['error']) {
    case UPLOAD_ERR_OK: // all ok
      if (substr($image['type'], 0, 5)!=='image') {
        $errors[] = 'Attachment must be an image.';
      } else if ($image['size'] > (12*1048576)) {
        $errors[] = 'Attachment too big. Maximum size is 12mb.';
      }
      break;
    case UPLOAD_ERR_INI_SIZE:
      $errors[] = 'Attachment too big. Maximum size is 12mb.';
      break;
    case UPLOAD_ERR_FORM_SIZE:
      $errors[] = 'Attachment too big. Maximum size is 12mb.';
      break;
    case UPLOAD_ERR_NO_FILE:
      //$errors[] = 'A relevant image must be attached.';
      break;
    case UPLOAD_ERR_PARTIAL:
      $errors[] = 'Attachment partially uploaded. Try again.';
      break;
    default:
      $errors[] = $image['errpr'].' Error. Try again.';
      error_log('[FileUploadError] '.$image['error']);
  }
}

function check_discussion_content($text, & $errors) {
  $len = strlen($text);
  if ($len < 12) {
    $errors[] = "Discussion content must be at least 12 characters long.";
  } else if ($len > 8192) {
    $errors[] = "Discussion content must be at most 8,192 characters long.";
  }
}

function check_discussion_tags(& $tags, & $errors) {
  $tags_l = count($tags);
  if ($tags_l) {
    if ($tags_l > 10) {
      $errors[] = "Maximum 10 tags can be used. You have provided $tags_l.";
    } else {
      foreach($tags as $index=>$tag) {
        $tags[$index] = strtolower(trim($tags[$index]));
        if (strlen($tags[$index]) < 3) {
          $errors[] = 'Minimum length for each tag is 3.';
        } elseif (strlen($tags[$index]) > 32) {
          $errors[] = 'Maximum length for each tag is 32.';
        } elseif (!preg_match("/^[a-z0-9]+[a-z0-9-]+[a-z0-9]+$/", $tags[$index])) {
            $errors[] = "Invalid tag: {$tags[$index]}. Tag must start and end with a 
                         letter or digit and can only contain letters, digits and hyphen(-).";
        }
      }
    }
  } else {
    $errors[] = "You must provide at least one relevant tag.";
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

function is_valid_date($date, $format = 'Y-m-d') {
  $d = DateTime::createFromFormat($format, $date);
  return $d && $d->format($format) == $date;
}

function check_username(string $un, & $errors) {
  $c = count($errors);
  $username_length = strlen($un);
  $first_character = ord(substr($un, 0, 1));
  if ($username_length) {
    if ($username_length < 3) {
      $errors[] = 'Username too small. Minimum 3 characters needed.';
    } elseif ($username_length > 24) {
      $errors[] = 'Username too big. Maximum 24 characters allowed.';
    } elseif ($first_character<97 || $first_character>122) {
      $errors[] = 'Username must start with a letter (a-z).';
    } elseif (preg_match('/[^a-z_\-0-9]/', $un)) {
      $errors[] = 'Username can only contain letters(a-z), numbers(0-9), '
      .           'hyphens(-) and underscores(_).';
    }
  } else {
    $errors[] = 'Username cannot be empty.';
  }
  if (count($errors)===$c) {
    //if (!$da->is_username_available($un)) {
    //  $errors[] = "<b>$un</b> is already taken.";
    //}
  }
}
?>
