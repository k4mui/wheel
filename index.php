<?php
$root = dirname(__FILE__);
require "$root/includes/init.php";
require "$root/includes/classes/database.php";
require "$root/includes/formatting.php";


if ($_SERVER["REQUEST_METHOD"] !== "GET") {
  $error = "Invalid request!";
  include("error.php");
  die();
}

$da = data_access::get_instance();
if ($da === null) {
  include("error.php");
  die();
}

$stats = $da->get_site_stats();
$rows = $da->get_boards_data();
$discussions = $da->get_recent_discussions();
if ($rows == null || $stats == null) {
  include("error.php");
  unset($da);
  die();
}

unset($da);
?>

<!DOCTYPE html>
<html lang="en-US">
<head>
	<meta charset="utf-8">
  <title>wheel - Home</title>
  <link rel="icon" type="image/png" sizes="32x32" href="/i/f/32.png">
  <link rel="icon" type="image/png" sizes="96x96" href="/i/f/96.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/i/f/16.png">
  <link rel="shortcut icon" type="image/x-icon" href="/i/f/fi.ico">
	<link href="/f/fa/css/fontawesome-all.css" rel="stylesheet" type="text/css">
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
              echo '<li><i class="fas fa-user-secret"></i> <a href="inbox.php">Admin Panel</a></li>';
            }
            if ($user->is_registered()) { // common for registered
              echo '<li><i class="fas fa-user"></i> <a href="/account.php">Account</a></li>
                    <li><i class="fas fa-sign-out-alt"></i> <a href="/logout.php">Logout</a></li>';
            } else { // anon
              echo '<li><i class="fas fa-user-plus"></i> <a href="/register.php">Register</a></li>
                    <li><i class="fas fa-sign-in-alt"></i> <a href="/login.php">Login</a></li>';
            }
            ?>
					</ul>
				</div> <!-- .row -->
			</div> <!-- #user-panel -->
			<div id="site-nav">
				<div class="row">
          <img id="site-logo" src="/i/shishui.png">
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
        </ul>
			</div> <!-- #page-title -->
		</div> <!-- #head -->
		<div id="body-wrapper">
			<div class="row">
        <div id="body-left" class="float-left">
          <div id="boards-section">
            <div class="card-header">Boards</div>
            <?php
            foreach ($rows as $id => $row) {
              echo '<div class="boards-item">
                      <span class="boards-icon">
                        <i class="fas fa-' . $row['fa_icon'] . '"></i>
                      </span>
                      <div class="boards-title">
                        <h3><a href="vb.php?id=' . $row['id'] . '">' . $row['title'] . '</a></h3>
                        <div class="boards-stats">
                          <span class="fg-bright">Discussions:</span> <span class="fg-black">' . $row['discussion_count'] . '</span> ·
                          <span class="fg-bright">Replies:</span> <span class="fg-black">' . $row['reply_count'] . '</span> ·
                          <span class="fg-bright">Images:</span> <span class="fg-black">' . ($row['image_count_r']+$row['discussion_count']) . '</span>
                        </div> <!-- .boards-stats -->
                      </div> <!-- .boards-title -->
                      <div class="boards-recent">
                        <img class="recent-img" src="/i/uc/' . ($row['image_filename'] ?  $row['image_filename'] : '200x200.png') . '" alt="x"/>
                        <div class="boards-recent-info">
                          <div class="boards-recent-title">
                            <span class="fg-bright">Recent:</span> ' . ($row['last_discussion_title'] ? '<a href="/vd.php?id="' . $row['last_discussion_id'] . '">' . $row['last_discussion_title'] . '</a>' : 'No discussion yet') . '
                          </div>
                          <div>
                            <span class="fg-bright">Posted:</span> ' . ($row['last_discussion_timestamp'] ? mysql_timestamp_to_date($row['last_discussion_timestamp']) : 'No discussion yet') . '
                          </div>
                        </div>
                      </div>
                    </div> <!-- .boards-item -->';
            }
            ?>
          </div> <!-- #boards-section -->
        </div>
        <div id="body-right">
          <div id="recent-posts-section">
            <div class="card-header">Recent Discussions</div>
            <div id="recent-posts">
            <?php
            if ($discussions) {
              foreach($discussions as $id => $discussion) {
                echo '<div class="recent-item">
                        <img class="top" src="/i/uc/' . $discussion['filename'] . '">
                        <div class="recent-info">
                          <div class="boards-recent-title">
                            <a href="vd.php?id=' . $discussion['id'] . '">' . $discussion['title'] . '</a>
                          </div>
                          <div class="boards-stats">' . mysql_timestamp_to_date($discussion['creation_timestamp']) .'
                          </div> <!-- .boards-stats -->
                          <div class="boards-stats">
                            <a href="/vb.php?id=' . $discussion['board_id'] . '">' . $discussion['board_title'] . '</a>
                          </div>
                        </div>
                      </div> <!-- .recent-item -->';
              }
            } else {
              echo '<div class="recent-item">No discussions yet</div>';
            }
            ?>
            </div>
          </div> <!-- #recent-posts-section -->
          <div id="statistics-section">
            <div class="card-header">Site Statistics</div>
            <div id="stats">
              <div class="stats-item">
                <span class="stats-left">Discussions:</span>
                <span class="stats-right"><?php echo $stats['discussion_count']; ?></span>
              </div>
              <div class="stats-item">
                <span class="stats-left">Replies:</span>
                <span class="stats-right"><?php echo $stats['reply_count']; ?></span>
              </div>
              <div class="stats-item">
                <span class="stats-left">Images:</span>
                <span class="stats-right"><?php echo $stats['image_count']; ?> (<?php echo human_readable_filesize((int)$stats['image_size']); ?>)</span>
              </div>
            </div>
          </div> <!-- #statistics-section -->
        </div>
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
