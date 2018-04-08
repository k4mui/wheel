<?php
//$root = dirname(__FILE__);
//require "$root/includes/init.php";
//require "$root/includes/classes/database.php";
//require "$root/includes/formatting.php";

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
  $error = "Invalid request!";
  include("error.php");
  die();
}
//$rows = array();
/*
$da = data_access::get_instance();
if ($da === null) {
  include("error.php");
  die();
}

$stats = $da->get_site_stats();
$rows = $da->get_boards();

foreach($rows as $k => $v) {
  $rows[$k]['stats'] = $da->get_board_stats($rows[$k]['id']);
  $rows[$k]['recent'] = $da->get_recent_discussion($rows[$k]['id']);
  if (!$rows[$k]['stats']) {
    include('error.php');
    unset($da);
    die();
  }
}

$discussions = $da->get_recent_discussions();
if ($rows == null || $stats == null) {
  include("error.php");
  unset($da);
  die();
}

unset($da);*/
?>

<!DOCTYPE html>
<html lang="en-US">
<head>
	<meta charset="utf-8">
  <title>wheel - Home</title>
  <link rel="icon" type="image/png" sizes="32x32" href="/images/favicons/32x32.png">
  <link rel="icon" type="image/png" sizes="96x96" href="/images/favicons/96x96.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/images/favicons/16x16.png">
  <link rel="shortcut icon" type="image/x-icon" href="/images/favicons/favicon.ico">
	<link href="/fonts/font-awesome/css/fontawesome-all.css" rel="stylesheet" type="text/css">
	<link href="/css/wheel_v2.css?v=<?php echo time();?>" rel="stylesheet" type="text/css">
</head>
<body>
  <div class='site-header'>
    <div class='inner'>
      <div class='site-logo'>
        <a href='/'></a>
      </div>
    </div>
  </div> <!-- .site-header -->
  <div class='site-content'>
    <div class='col-left'>
    Hello
    </div>
  </div> <!-- .site-content -->
</body>
</html>
