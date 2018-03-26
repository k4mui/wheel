<?php
$root = dirname(__FILE__);
require "$root/includes/init.php";
require "$root/includes/classes/database.php";
require "$root/includes/formatting.php";


if ($_SERVER['REQUEST_METHOD'] !== "GET") {
  $error = "The content you are trying to access is unavailable.";
	include("404.php");
	die();
}

$board = NULL;
$board_id = isset($_GET["id"]) ? (int)$_GET["id"] : NULL;
if ($board_id === 0) {
  die("Wrong board id");
}

$da = new DataAccess;
//echo "<pre>";
$board = $da->get_board_x($board_id);
$rows = $da->get_discussions_a($board->get_id());
//echo "</pre>";
//$board = $da->get_board_mysql($board_id);
//$board = $da->get_board($board_id);
unset($da);

if ($board === NULL) {
  $error = "The board you are trying to access is not a valid board :(";
  include("404.php");
  die();
}

?>
<!DOCTYPE html>
<html lang="en-US">
<head>
	<meta charset="utf-8">
  <title>wheel - <?php echo $board->get_title(); ?></title>
  <link rel="icon" type="image/png" sizes="32x32" href="/images/favicon/32x32.png">
  <link rel="icon" type="image/png" sizes="96x96" href="/images/favicon/96x96.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon/16x16.png">
  <link rel="shortcut icon" type="image/x-icon" href="/images/favicon/fi.ico">
	<link href="/fonts/font-awesome/css/fontawesome-all.css" rel="stylesheet" type="text/css">
	<link href="/styles/wheel.css?v=<?php echo time();?>" rel="stylesheet" type="text/css">
</head>
<body>
	<div id="wrap-all">
  <div id="head">
			<div id="user-panel">
				<div class="row">
          <ul class="list float-left">
            <li id="home-link"><i class="fas fa-info-circle"></i> <a href="/">FAQ</a></li>
            <li id="home-link"><i class="fas fa-question-circle"></i> <a href="/">Help</a></li>
            <li id="home-link"><i class="fas fa-clipboard"></i> <a href="/">Rules</a></li>
          </ul>
					<ul class="list float-right">
						<?php
            if ($user->is_admin()) { //admin
              echo '<li><i class="fas fa-envelope"></i> <a href="inbox.php">Admin Panel</a></li>';
            }
            if ($user->is_mod()) { // mod
              echo '<li><i class="fas fa-envelope"></i> <a href="inbox.php">Admin Panel</a></li>';
            }
            if ($user->is_anon()) { // anon
              echo "<li><i class=\"fas fa-user-plus\"></i> <a href=\"/register.php\">Register</a></li>"
                .  "<li><i class=\"fas fa-sign-in-alt\"></i> <a href=\"/login.php\">Login</a></li>";
            }
            if ($user->is_registered()) { // common for registered
              echo "<li><i class=\"fas fa-user\"></i> <a href=\"/account.php\">Account</a></li>"
                .  "<li><i class=\"fas fa-sign-out-alt\"></i> <a href=\"/logout.php\">Logout</a></li>";
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
          <li>/</li>
          <li><i class="fas fa-<?php echo $board->get_icon(); ?>"></i> <a href="/viewarchive.php?id=<?php echo $board->get_id(); ?>">Archive</a></li>
        </ul>
			</div> <!-- #page-title -->
      <div id="archive-indicator">
            You cannot reply to these discussions, they have been archived!
      </div>
		</div> <!-- #head -->
		<div id="body-wrapper">
      <div class="row" id="boards-button-section">
        <ul class="list float-left">
          <?php
          if (!$board->is_locked()) {
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
						.    "<a class=\"btn bg-archive fg-white\" href=\"/viewboard.php?id=" . $board->get_id() . "\"><i class=\"fas fa-file-archive\"></i> View Discussions</a>"
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
                    .          "<span class=\"fg-bright\">Created:</span> " . fancy_time($row["creation_timestamp"])
                    .        "</div>"
                    .        "<div class=\"text-right\"><span class=\"fg-bright\">Last Reply:</span> " . ($row["last_reply_timestamp"] ? $row["last_reply_timestamp"] : "No replies yet :(") . "</div>"
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
                  <span class="stats-right"><?php echo $board->get_image_count(); ?> (<?php echo $board->get_image_size(); ?>)</span>
                </div>
              </div>
						</div>
					</div>
			</div>
		</div> <!-- #body-wrapper -->
		<div id="foot">
			&copy; 2018 wheel
		</div> <!-- #footer -->
	</div> <!-- #wrap-all -->
</body>
</html>
