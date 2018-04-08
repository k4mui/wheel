<?php
$root = dirname(__FILE__);
require "$root/includes/init.php";
require "$root/includes/classes/database.php";

if ($_SERVER['REQUEST_METHOD'] !== "GET") {
	$error = "Invalid request!";
	include("error.php");
	die();
}

$board = null;
$rows = null;
$rows2 = null;
$board_id = isset($_GET["id"]) ? (int)$_GET["id"] : null;
$query = isset($_GET["q"]) ? $_GET["q"] : null;

if ($board_id === 0) {
	die("Wrong board id");
} else if ($board_id === -1) {
  $da = data_access::get_instance();
  if ($da===null) {
	  die('DB Error');
  } else {
	  $rows = $da->search_discussions($query, $board_id);
	  $rows2 = $da->search_replies($query, $board_id);
  }
  unset($da);
} else {
	$da = data_access::get_instance();
	if ($da===null) {
		die('DB error');
	} else {
		$board = $da->get_board($board_id);
		if ($board === null) {
			die('Invalid board');
		} else {
			$rows = $da->search_discussions($query, $board_id);
			$rows2 = $da->search_replies($query, $board_id);
		}
	}
}

?>
<!DOCTYPE html>
<html lang='en-US'>
<head>
	<meta charset="utf-8">
  <title>wheel - Search Results</title>
	<link rel="icon" type="image/png" sizes="32x32" href="/images/favicons/32x32.png">
  <link rel="icon" type="image/png" sizes="96x96" href="/images/favicons/96x96.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/images/favicons/16x16.png">
  <link rel="shortcut icon" type="image/x-icon" href="/images/favicons/favicon.ico">
	<link href="/fonts/font-awesome/css/fontawesome-all.css" rel="stylesheet" type="text/css">
	<link href="/css/wheel.css?v=<?php echo time();?>" rel="stylesheet" type="text/css">
</head>
<body>
	<div id="wrap-all">
  <div id="head">
			<div id="user-panel">
				<div class="row">
          <ul class="list float-left">
            <li id="home-link"><i class="fas fa-info-circle"></i> <a href="/faq.php">FAQ</a></li>
            <li id="home-link"><i class="fas fa-question-circle"></i> <a href="/faq.php#about">About</a></li>
            <li id="home-link"><i class="fas fa-clipboard"></i> <a href="/rules.php">Rules</a></li>
          </ul>
					<ul class="list float-right">
						<?php
            if ($user->is_admin()) { //admin
              echo "<li><i class='fas fa-user-secret'></i> <a href='/cp.php'>Control Panel</a></li>";
            }
            if ($user->is_registered()) { // common for registered
              echo "<li><i class='fas fa-user'></i> <a href='/account.php'>Account</a></li>
                    <li><i class='fas fa-sign-out-alt'></i> <a href='/logout.php'>Logout</a></li>";
            } else { // anon
              echo "<li><i class='fas fa-user-plus'></i> <a href='/register.php'>Register</a></li>
                    <li><i class='fas fa-sign-in-alt'></i> <a href='/login.php'>Login</a></li>";
            }
            ?>
					</ul>
				</div> <!-- .row -->
			</div> <!-- #user-panel -->
			<div id="site-nav">
				<div class="row">
          <img id="site-logo" src="/images/logos/shishui.png" />
          <span id="site-title">wheel</span>
					<div id="site-search" class="float-right">
            <form class="input-group" action="/search.php" method="GET">
              <input type="text" name="q" placeholder="Search discussions...">
              <input type="hidden" name="id" value="-1">
							<button type="submit">
								<i class="fas fa-search"></i>
							</button>
						</form>
					</div>
				</div> <!-- .row -->
			</div> <!-- #site-nav -->
			<div id="page-title">
        <ul class="list">
          <li><i class="fas fa-home"></i> <a href="/">Boards Index</a></li>
          <li>/</li>
          <?php
          if ($board) {
            echo "<li><i class=\"fas fa-" . $board['fa_icon'] . "\"></i> <a href=\"/vb.php?id=" . $board['id'] . "\">Board: " . $board['title'] . "</a></li>"
              .  "<li>/</li>";
          }
          ?>
          <li><i class="fas fa-search"></i> <a href="/search.php?id=<?php echo $board ? $board['id'] : -1; ?>">Search: <?php echo $query; ?></a></li>
        </ul>
			</div> <!-- #page-title -->
		</div> <!-- #head -->
		<div id="body-wrapper">
      <div id="boards-section">
        <div class="card-header">
          Search results for <?php echo $query; ?>
        </div>
        <div class="boards-item">
          Total results: <?php echo count($rows); ?>
        </div>
		<?php
			if ($rows) {
				foreach ($rows as $row) {
					echo "<div class='boards-item'>
					{$row['full_text']}</div>";
				}
			}
			
			if ($rows2) {
				foreach ($rows2 as $row) {
					echo "<div class='boards-item'>
					{$row['full_text']}</div>";
				}
			}
		?>
      </div>
		</div> <!-- #body-wrapper -->
		<div id="foot">
      <ul class="list">
        <li><a href="/privacy.php">Privacy</a></li>
        <li><a href="/terms.php">Terms</a></li>
        <li><a href="/contact.php">Contact</a></li>
      </ul>
			<div>&copy; 2018 wheel. All rights reserved. All times are in UTC.</div>
		</div> <!-- #foot -->
	</div> <!-- #wrap-all -->
</body>
</html>
