<?php

function mysql_timestamp_to_date($mysql_timestamp) {
    $timestamp = strtotime($mysql_timestamp);
    return date('F j, Y g:ia', $timestamp);
}
function mysql_timestamp_to_date2($mysql_timestamp) {
  $timestamp = strtotime($mysql_timestamp);
  return date('M j, Y g:ia', $timestamp);
}

function human_readable_filesize($bytes) {
  if ($bytes == 0)
      return "0.00 B";

  $s = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
  $e = floor(log($bytes, 1024));

  return round($bytes/pow(1024, $e), 2).$s[$e];
}

function time_elapsed_string($datetime, $full = false) {
  $now = new DateTime;
  $now->setTimezone(new DateTimeZone($_SESSION['utc_offset']));
  $ago = new DateTime($datetime);
  $ago->setTimezone(new DateTimeZone($_SESSION['utc_offset']));
  $diff = $now->diff($ago);

  $diff->w = floor($diff->d / 7);
  $diff->d -= $diff->w * 7;

  $string = array(
      'y' => 'year',
      'm' => 'month',
      'w' => 'week',
      'd' => 'day',
      'h' => 'hour',
      'i' => 'minute',
      's' => 'second',
  );
  foreach ($string as $k => &$v) {
      if ($diff->$k) {
          $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
      } else {
          unset($string[$k]);
      }
  }

  if (!$full) $string = array_slice($string, 0, 1);
  return $string ? implode(', ', $string) . ' ago' : 'just now';
}
?>
