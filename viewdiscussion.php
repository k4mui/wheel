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

$discussion = null;
$discussion_id = isset($_GET["id"]) ? (int)$_GET["id"] : NULL;
$error = 'The discussion you are trying to view is not valid.';

if ($discussion_id === 0) {
  include('error.php');
  die();
}

$da = data_access::get_instance();
if ($da === null) {
  $error = 'Server is having some trouble. Try again later.';
  include("error.php");
  die();
}

$discussion = $da->get_discussion_object($discussion_id);
if ($discussion === null) {
  include("error.php");
  unset($da);
  die();
}

$rows = $da->get_replies($discussion_id);
if ($rows === null) {
  $error = 'Cannot fetch data';
  include('error.php');
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
  <title>wheel - <?php echo $discussion->get_title(); ?></title>
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
          <li><i class="fas fa-<?php echo $discussion->get_board_icon(); ?>"></i> <a href="/viewboard.php?id=<?php echo $discussion->get_board_id(); ?>">Board: <?php echo $discussion->get_board_title(); ?></a></li>
          <li>/</li>
          <li><i class="fas fa-<?php echo $discussion->get_board_icon(); ?>"></i> <a href="/viewdiscussion.php?id=<?php echo $discussion->get_id(); ?>"><?php echo $discussion->get_title(); ?></a></li>
        </ul>
			</div> <!-- #page-title -->
		</div> <!-- #head -->
		<div id="body-wrapper">
      <div class="row" id="boards-button-section">
        <ul class="list float-left">
          <?php
          if (!$discussion->is_archived()) {
            echo "<li>"
							.    "<a class=\"btn bg-success fg-white\" href=\"/newreply.php?id=" . $discussion->get_id() . "\"><i class=\"fas fa-file\"></i> New Reply</a>"
							.  "</li>";
						if ($user->is_admin()) {
							echo "<li>"
								.    "<a class=\"btn bg-pomegranate fg-white\" href=\"/archive.php?id=" . $discussion->get_id() . "\"><i class=\"fas fa-lock\"></i> Archive</a>"
								.  "</li>";
						}
					} else {
						echo "<li><a class=\"btn bg-lock fg-white\" href=\"faq.php#locked-board\"><i class=\"fas fa-lock\"></i> Archived</a></li>";
          }
          if ($discussion->get_author_id() === $user->get_id() && $user->is_registered()) {
            echo "<li><a class=\"btn bg-lock fg-white\" href=\"remove.php?id=" . $discussion->get_id() . "\"><i class=\"fas fa-lock\"></i> Remove</a></li>";
          }

          ?>
        </ul>
        <div id="board-search" class="float-right">
          <form class="input-group" action="/search.php" method="GET">
            <input type="text" name="q" placeholder="Search this discussion..." />
            <input type="hidden" name="did" value="<?php echo $discussion->get_id(); ?>" />
            <button type="submit">
              <i class="fas fa-search"></i>
            </button>
          </form>
        </div>
      </div>
			<div class="row">
        <div id="replies-section">
          <div class="discussion-head">OP <?php echo $discussion->get_creation_timestamp(); ?></div>
          <div class="discussion-item">
            <a href="<?php echo "/images/usercontents/" . $discussion->get_image_filename(); ?>"><img class="post-image" src="<?php echo "/images/usercontents/" . $discussion->get_image_filename(); ?>"/></a>
            <?php echo $discussion->get_full_text(); ?>
          </div>
        </div> <!-- #replies-section -->
        <?php
          if($rows) {
            foreach($rows as $id => $row) {
              echo "<div class=\"reply\">"
                .  "<div class=\"reply-head\">#" . $row["id"] . " - " . $row["creation_timestamp"] . "</div>"
                .  "<div class=\"reply-body\">";
              if($row["filename"]) {
                echo "<a href=\"/images/usercontents/" . $row["filename"] . "\"><img class=\"post-image\" src=\"/images/usercontents/" . $row["filename"] . "\"/></a>";
              }
              echo $row["full_text"] .  "</div></div>";
            }
          } else {
            echo "<div class='reply'><div class='reply-head'>No replies yet!</div></div>";
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
