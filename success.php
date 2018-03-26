<!DOCTYPE html>
<html lang="en-US">
<head>
	<meta charset="utf-8">
  <title>wheel - Success</title>
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
						<form class="input-group" action="index.html" method="get">
							<input type="text" name="_query" placeholder="Search discussions..." />
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
      <div id="form-area">
        <div id="new-discussion" class="success">
          <?php echo $success; ?>
        </div>
      </div>
		</div> <!-- #body-wrapper -->
		<div id="foot">
			&copy; 2018 wheel
		</div> <!-- #footer -->
	</div> <!-- #wrap-all -->
</body>
</html>
