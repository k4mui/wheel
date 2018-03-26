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
?>
