<?php
$root = dirname(__FILE__);
require "$root/includes/init.php";
require "$root/includes/classes/database.php";
require "$root/includes/validation.php";

$accepted_methods = array("POST", "GET");
if (!in_array($_SERVER["REQUEST_METHOD"], $accepted_methods)) {
  $error = "Invalid request.";
  include("404.php");
  die();
}

$discussion = null;
$full_text = null;
$image = null;
$errors = array();


$discussion_id = isset($_GET["id"]) ? (int)$_GET["id"] : null;
if ($discussion_id === 0) {
  die("Invalid disucssion id.");
}
$da = data_access::get_instance();
$discussion = $da->get_discussion($discussion_id);
if ($discussion === null) {
  $error = "The discussion you are trying to access is not valid";
  include("error.php");
  unset($da);
  die();
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {
  if (isset($_POST["full_text"])) {
    $full_text = $_POST["full_text"];
  }
  if (isset($_FILES['attachment'])) {
    $image = $_FILES['attachment'];
  }
  if ($image['error'] !== 4) {
    check_discussion_attachment($image, $errors);
  }
  if ($full_text) {
    check_discussion_text($full_text, $errors);
  } else {
    $errors[] = "Text cannot be empty";
  }
	if (count($errors) === 0) {
    //success
    $image_id = null;
    if(!$image['error']) {
      $image_id = $da->insert_image($image);
      if ($image_id) {
        if ($da->insert_reply($full_text, $image_id, $user->get_id(), $discussion['id'])) {
          header("Location: vd.php?id={$discussion['id']}");
          unset($da);
          die();
        } else {
          $errors[] = "Cannot create reply. Please try again later.";
        }
      } else {
        $errors[] = "Image cannot be uploaded. Try again later.";
      }
    } else {
      if ($da->insert_reply($full_text, $image_id, $user->get_id(), $discussion['id'])) {
        header("Location: vd.php?id={$discussion['id']}");
        unset($da);
        die();
      } else {
        $errors[] = "Cannot create reply. Please try again later.";
      }
    }
  }
}
?>
<!DOCTYPE html>
<html lang='en-US'>
<head>
	<meta charset="utf-8">
  <title>wheel - New Reply</title>
	<link rel="icon" type="image/png" sizes="32x32" href="/images/favicons/32x32.png" />
  <link rel="icon" type="image/png" sizes="96x96" href="/images/favicons/96x96.png" />
  <link rel="icon" type="image/png" sizes="16x16" href="/images/favicons/16x16.png" />
  <link rel="shortcut icon" type="image/x-icon" href="/images/favicons/favicon.ico" />
	<link href="/fonts/font-awesome/css/fontawesome-all.css" rel="stylesheet" type="text/css" />
	<link href="/css/wheel.css?v=<?php echo time();?>" rel="stylesheet" type="text/css" />
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
          <li><i class="fas fa-<?php echo $discussion['fa_icon']; ?>"></i> <a href="/vb.php?id=<?php echo $discussion['board_id']; ?>">Board: <?php echo $discussion['board_title']; ?></a></li>
          <li>/</li>
          <li><i class="fas fa-file"></i> <a href="/vd.php?id=<?php echo $discussion['id'] ?>"><?php echo $discussion['title']; ?></a></li>
          <li>/</li>
          <li><i class="fas fa-file"></i> <a href="/nr.php?id=<?php echo $discussion['id']; ?>">New Reply</a></li>
        </ul>
			</div> <!-- #page-title -->
		</div> <!-- #head -->
		<div id="body-wrapper">
      <div id="form-area">
      <div>
        <?php
        if ($errors) {
          echo "<div id='errors'>";
          foreach ($errors as $err) {
            echo "<div>$err</div>";
          }
          echo "</div>";
        }
        ?>
        </div>
        <div id="new-discussion">
          <form class="account-form" action="" method="post" enctype="multipart/form-data">
            Reply:<br>
            <textarea name="full_text" rows="16"><?php echo $full_text ? $full_text : ''; ?></textarea><br/>
            Attachment:<br/>
            <input type="file" name="attachment" accept="image/*"><br/>
            <br/>
            <input type="submit" value="Post">
          </form>
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
