<?php
$root = dirname(__FILE__);
require "$root/includes/init.php";
require "$root/includes/classes/database.php";
require "$root/includes/formatting.php";
require "$root/includes/validation.php";


$errors = null;

if ($user->is_registered()) {
  $error = "You are already logged in.";
  include("error.php");
  die();
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {
  $errors = array();
  check_login_inputs($_POST, $errors);
  if (!$errors) {
    $da = data_access::get_instance();
    if ($da === null) {
      include("error.php");
      die();
    }
    $u = $da->get_user($_POST['email_address']);
    if ($u) {
      if ($u[0]['password_hash'] === md5($_POST['password'])) {
        if ($u[0]['account_status']===1) {
          $success = "Login successful.";
          $user->update($u[0]);
          include("success.php");
          unset($u);
          die();
        } else {
          $errors[] = "Your account is deactivated.";
        }
      } else {
        $errors[] = "Wrong password.";
      }
    } else {
      $errors[] = "Account doesn't exist.";
    }
    unset($da);
  }
} else if ($_SERVER["REQUEST_METHOD"] !== "GET") {
  $error = "Invalid request!";
  include("error.php");
  die();
}
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
	<meta charset="utf-8">
  <title>wheel - Login</title>
  <link rel="icon" type="image/png" sizes="32x32" href="/images/favicons/32x32.png">
  <link rel="icon" type="image/png" sizes="96x96" href="/images/favicons/96x96.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/images/favicons/16x16.png">
  <link rel="shortcut icon" type="image/x-icon" href="/images/favicons/favicon.ico">
	<link href="/fonts/font-awesome/css/fontawesome-all.css" rel="stylesheet" type="text/css">
	<link href="/css/wheel.css?v=<?php echo time();?>" rel="stylesheet" type="text/css">
  <script type="text/javascript" src="/js/wheel.js"></script>
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
            echo '<li><i class="fas fa-user-plus"></i> <a href="/register.php">Register</a></li>
                    <li><i class="fas fa-sign-in-alt"></i> <a href="/login.php">Login</a></li>';
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
          <li><i class="fas fa-sign-in-alt"></i> <a href="/login.php">Login</a></li>
        </ul>
			</div> <!-- #page-title -->
		</div> <!-- #head -->
		<div id="body-wrapper">
	    <div id="form-area">
        <div>
          <?php
          if (is_array($errors) && $errors) {
            echo "<div id='errors'>";
            foreach ($errors as $err) {
              echo "<div>$err</div>";
            }
            echo "</div>";
          }
          ?>
        </div>
        <div oninput="check_login_form()">
          <form class="account-form" name="login_form" action="" method="POST">
            Email Address: <span id="side_error_ea"></span><br/>
            <input type="text" name="email_address" maxlength="254" value=""><br/>
            Password: <span id="side_error_pw"></span><br/>
            <input type="password" name="password"><br/>
            · Don't have an account? <a href="/register.php">Create one here</a>.
            <br/>
            · Forgot password? <a href="/reset.php">Click here</a> to reset.
            <br/>
            <input type="submit" name="submit" value="Login">
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
  <script>
    (function() {
      disable_button('login_form');
    })();
    </script>
</body>
</html>
