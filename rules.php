<?php
$root = dirname(__FILE__);
require "$root/includes/init.php";


if ($_SERVER["REQUEST_METHOD"] !== "GET") {
  $error = "Invalid request!";
  include("error.php");
  die();
}
?>

<!DOCTYPE html>
<html lang="en-US">
<head>
	<meta charset="utf-8">
  <title>wheel - Rules</title>
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
          <li><i class="fas fa-clipboard"></i> <a href="/rules.php">Rules</a></li>
        </ul>
			</div> <!-- #page-title -->
		</div> <!-- #head -->
		<div id="body-wrapper">
	    <div id="form-area">
        <div>
            <h4>By using this website, you agree that you'll follow these rules. These are global rules and apply to all boards:</h4>
            <ol>
                <li>You will not upload, post, discuss, request, or link to anything that violates local law.</li>
                <li>You will not post or request personal information or calls to invasion.</li>
                <li>The quality of posts is extremely important to this community. Contributors are encouraged to provide high-quality images and informative comments.</li>
                <li>Complaining about wheel (its policies, moderation, etc) on the imageboards may result in post deletion and a ban.</li>
                <li>Refrain from spamming or flooding of any kind.</li>
                <li>Advertising is not welcome. This includes any type of referral linking, "offers", soliciting, begging, stream threads, etc unless otherwise stated on any board.</li>
                <li>Do not use avatars or attach signatures to your posts.</li>
            </ol>
        </div>
      </div>
		</div> <!-- #body-wrapper -->
		<div id="foot">
			&copy; 2018 wheel
		</div> <!-- #footer -->
	</div> <!-- #wrap-all -->
</body>
</html>
