<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require "$root/lib/init.php";
require "$root/lib/db.php";
require "$root/lib/post_processing.php";


if ($_SERVER['REQUEST_METHOD'] !== "GET") {
  $error = "Invalid request.";
	include("error.php");
	die();
}

$board = NULL;
$board_id = isset($_GET["id"]) ? (int)$_GET["id"] : NULL;
$error = "The board you are trying to access is invalid.";

if ($board_id === 0) {
	include("error.php");
	die();
}

$da = data_access::get_instance();
if ($da === null) {
  include("error.php");
  die();
}

$board = $da->get_board_object($board_id);
if ($board === null) {
  include("error.php");
  unset($da);
  die();
}

$rows = $da->get_discussions($board->get_id());
if ($rows === null) {
  unset($error);
  include("error.php");
  unset($da);
  die();
}
unset($da);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
                      "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta charset="utf-8">
  <title>wheel - <?php echo $board->get_title(); ?></title>
  <link rel="icon" type="image/png" sizes="32x32" href="/images/favicon/32x32.png" />
  <link rel="icon" type="image/png" sizes="96x96" href="/images/favicon/96x96.png" />
  <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon/16x16.png" />
  <link rel="shortcut icon" type="image/x-icon" href="/images/favicon/fi.ico" />
	<link href="/fonts/font-awesome/css/fontawesome-all.css" rel="stylesheet" type="text/css" />
	<link href="/styles/wheel.css?v=<?php echo time();?>" rel="stylesheet" type="text/css" />
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
              echo '<li><i class="fas fa-envelope"></i> <a href="inbox.php">Admin Panel</a></li>';
            }
            if ($user->is_mod()) { // mod
              echo '<li><i class="fas fa-envelope"></i> <a href="inbox.php">Admin Panel</a></li>';
            }
            if ($user->is_registered()) { // common for registered
              echo "<li><i class=\"fas fa-user\"></i> <a href=\"/account.php\">Account</a></li>"
                .  "<li><i class=\"fas fa-sign-out-alt\"></i> <a href=\"/logout.php\">Logout</a></li>";
            } else { // anon
              echo "<li><i class=\"fas fa-user-plus\"></i> <a href=\"/register.php\">Register</a></li>"
                .  "<li><i class=\"fas fa-sign-in-alt\"></i> <a href=\"/login.php\">Login</a></li>";
            }
            ?>
					</ul>
				</div> <!-- .row -->
			</div> <!-- #user-panel -->
			<div id="site-nav">
				<div class="row">
          <img id="site-logo" src="/images/shi.png" />
          <span id="site-title">wheel</span>
					<div id="site-search" class="float-right">
            <form class="input-group" action="/search.php" method="GET">
              <input type="text" name="q" placeholder="Search discussions..." />
              <input type="hidden" name="id" value="-1" />
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
          <li><i class="fas fa-<?php echo $board->get_icon(); ?>"></i> <a href="/viewboard.php?id=<?php echo $board->get_id(); ?>">Board: <?php echo $board->get_title(); ?></a></li>
        </ul>
			</div> <!-- #page-title -->
		</div> <!-- #head -->
		<div id="body-wrapper">
      <div class="row" id="boards-button-section">
        <ul class="list float-left">
          <?php
          if (!$board->is_locked()) {
            echo "<li>"
							.    "<a class=\"btn bg-success fg-white\" href=\"/newdiscussion.php?id=" . $board->get_id() . "\"><i class=\"fas fa-file\"></i> New Discussion</a>"
							.  "</li>";
						if ($user->is_admin()) {
							echo "<li>"
								.    "<a class=\"btn bg-pomegranate fg-white\" href=\"/lock.php?id=" . $board->get_id() . "\"><i class=\"fas fa-lock\"></i> Lock</a>"
								.  "</li>";
						}
					}
					if ($board->is_locked()) {
						echo "<li><a class=\"btn bg-lock fg-white\" href=\"faq.php#locked-board\"><i class=\"fas fa-lock\"></i> Locked</a></li>";
					}
					echo "<li>"
						.    "<a class=\"btn bg-archive fg-white\" href=\"/viewarchive.php?id=" . $board->get_id() . "\"><i class=\"fas fa-file-archive\"></i> View Archive</a>"
						.  "</li>";

          ?>
        </ul>
        <div id="board-search" class="float-right">
          <form class="input-group" action="/search.php" method="GET">
            <input type="text" name="q" placeholder="Search this board..." />
            <input type="hidden" name="id" value="<?php echo $board->get_id(); ?>" />
            <button type="submit">
              <i class="fas fa-search"></i>
            </button>
          </form>
        </div>
      </div>
			<div class="row">
					<div id="body-left" class="float-left">
            <div id="boards-section">
              <div class="card-header">Discussions</div>
              <?php
              if ($rows) {
                foreach ($rows as $id => $row) {
                  echo "<div class=\"boards-item\">"
                    .    "<span class=\"boards-icon\">"
                    .       "<img src=\"/images/usercontents/" . $row["filename"] . "\" class=\"board-discussions\" />"
                    .    "</span>"
                    .    "<div class=\"discussions-title\">"
                    .      "<h3><a href=\"viewdiscussion.php?id=" . $row["id"] . "\">" . $row["title"] . "</a></h3>"
                    .      "<div>" . $row["full_text"]
                    .      "</div>"
                    .    "</div>"
                    .    "<div class=\"discussions-stats\">"
                    .      "<div>"
                    .        "<div class=\"text-right\">"
                    .          "<span class=\"fg-bright\">Replies:</span> " . $row["reply_count"]
                    .        "</div>"
                    .        "<div class=\"text-right\"><span class=\"fg-bright\">Images:</span> " . $row["image_count"] . "</div>"
                    .      "</div>"
                    .    "</div>"
                    .    "<div class=\"discussions-recent\">"
                    .      "<div>"
                    .        "<div class=\"text-right\">"
                    .          "<span class=\"fg-bright\">Created:</span> " . mysql_timestamp_to_date2($row["creation_timestamp"])
                    .        "</div>"
                    .        "<div class=\"text-right\"><span class=\"fg-bright\">Last Reply:</span> " . ($row["last_reply_timestamp"] ? mysql_timestamp_to_date2($row["last_reply_timestamp"]) : "no replies yet") . "</div>"
                    .      "</div>"
                    .    "</div>"
                    .  "</div>";
                }
              } else {
                echo "<div class=\"boards-item\">No posts sorry :(</div>";
              }
              ?>
            </div>
            </div>
            <div id="body-right">
            <div id="recent-posts-section">
              <div class="card-header">Board Rules</div>
              <div class="card-body" id="recent-posts">
                <?php echo $board->get_rules(); ?>
              </div>
            </div>

            <div id="board-statistics-section">
              <div class="card-header">Statistics of <?php echo $board->get_title(); ?></div>
              <div id="stats">
                <div class="stats-item">
                  <span class="stats-left">Discussions:</span>
                  <span class="stats-right"><?php echo $board->get_discussion_count(); ?></span>
                </div>
                <div class="stats-item">
                  <span class="stats-left">Replies:</span>
                  <span class="stats-right"><?php echo $board->get_reply_count(); ?></span>
                </div>
                <div class="stats-item">
                  <span class="stats-left">Images:</span>
                  <span class="stats-right"><?php echo $board->get_image_count(); ?> (<?php echo human_readable_filesize($board->get_image_size()); ?>)</span>
                </div>
              </div>
						</div>
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
