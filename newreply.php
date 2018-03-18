<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require "$root/lib/init.php";
require "$root/lib/db.php";
require "$root/lib/validation.php";

$accepted_methods = array("POST", "GET");
if (!in_array($_SERVER["REQUEST_METHOD"], $accepted_methods)) {
  $error = "Invalid request.";
  include("404.php");
  die();
}

$discussion = NULL;
$full_text = NULL;
$image = NULL;
$image_id = NULL;
$errors = array();
$success = False;


$discussion_id = isset($_GET["id"]) ? (int)$_GET["id"] : NULL;
if ($discussion_id === 0) {
  die("Invalid disucssion id.");
}
$da = new DataAccess;
$discussion = $da->get_discussion_x($discussion_id);
unset($da);
if ($discussion === NULL) {
  $error = "The discussion you are trying to access is not valid";
  include("404.php");
  die();
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {
  if (isset($_POST["full_text"])) {
    $full_text = $_POST["full_text"];
  }
  if ($_FILES["attachment"]["tmp_name"]) {
    $image = $_FILES["attachment"];
  }
  if ($full_text) {
    check_discussion_text($full_text, $errors);
  } else {
    $errors[] = "Text cannot be empty";
  }
  if ($image) {
    check_discussion_attachment($image, $errors);
  }
	if (count($errors) === 0) {
    //success
    $da = new DataAccess;
    if($image) {
      $image_id = $da->insert_image($image, $user->get_id());
      if ($image_id) {
        if ($da->insert_reply($full_text, $image_id, $user->get_id(), $discussion->get_id())) {
          $success = True;
          header("Location: viewboard.php?id=" . $discussion->get_board_id());
          die();
        } else {
          $errors[] = "Cannot create discussion. Please try again later.";
        }
      } else {
        $errors[] = "Image cannot be uploaded. Try again later.";
      }
    } else { // no attachment
      if ($da->insert_reply_l($full_text, $user->get_id(), $discussion->get_id())) {
        $success = True;
        header("Location: viewdiscussion.php?id=" . $discussion->get_id());
        die();
      } else {
        $errors[] = "Cannot create discussion. Please try again later.";
      }
    }
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
                      "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta charset="utf-8">
  <title>wheel - New Reply</title>
	<link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png" />
  <link rel="icon" type="image/png" sizes="96x96" href="/favicon/favicon-96x96.png" />
  <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png" />
  <link rel="shortcut icon" type="image/x-icon" href="/favicon/favicon.ico" />
	<link href="/fonts/font-awesome/css/fontawesome-all.css" rel="stylesheet" type="text/css" />
	<link href="/styles/wheel.css?v=<?php echo time();?>" rel="stylesheet" type="text/css" />
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
          <li><i class="fas fa-<?php echo $discussion->get_board_icon(); ?>"></i> <a href="/viewboard.php?id=<?php echo $discussion->get_board_id(); ?>">Board: <?php echo $discussion->get_board_title(); ?></a></li>
          <li>/</li>
          <li><i class="fas fa-file"></i> <a href="/viewdiscussion.php?id=<?php echo $discussion->get_id(); ?>"><?php echo $discussion->get_title(); ?></a></li>
          <li>/</li>
          <li><i class="fas fa-file"></i> <a href="/newreply.php?id=<?php echo $discussion->get_id(); ?>">New Reply</a></li>
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
            Reply:<br/>
            <textarea name="full_text" rows="16"><?php echo $full_text ? $full_text : ''; ?></textarea><br/>
            Attachment:<br/>
            <input type="file" name="attachment" accept="image/*" /><br/>
            <br/>
            <input type="submit" value="Post"/>
          </form>
        </div>
      </div>
		</div> <!-- #body-wrapper -->
		<div id="foot">
			&copy; 2018 wheel
		</div> <!-- #footer -->
	</div> <!-- #wrap-all -->
</body>
</html>
