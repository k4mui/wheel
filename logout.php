<?php
$root = dirname(__FILE__);
require "$root/includes/init.php";


if ($_SERVER['REQUEST_METHOD'] !== "GET") {
  $error = "Invalid request!";
  include("error.php");
  die();
}
if (!$user->is_registered()) {
  $error = "You need to login first.";
  include("error.php");
  die();
} else {
  $user->logout();
}
?>
<!DOCTYPE html>
<html lang='en-US'>
<head>
<meta charset="utf-8">
  <title>wheel - Logout</title>
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
              echo "<li><i class='fas fa-user-secret'></i> <a href='inbox.php'>Admin Panel</a></li>";
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
          <img id="site-logo" src="/images/logos/shishui.png">
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
	    <div id="form-area" class="success">
        <div> Logged out successfully.</div>
      </div>
		</div> <!-- #body-wrapper -->
		<div id="foot">
			&copy; 2018 wheel
		</div> <!-- #footer -->
	</div> <!-- #wrap-all -->
</body>
</html>
