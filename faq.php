<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require "$root/lib/init.php";
//require "$root/lib/db.php";
//require "$root/lib/validation.php";


if ($_SERVER["REQUEST_METHOD"] !== "GET") {
  $error = "Invalid request!";
  include("error.php");
  die();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
                      "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta charset="utf-8">
	<title>wheel - FAQ</title>
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
          <li><i class="fas fa-info-circle"></i> <a href="/faq.php">FAQ</a></li>
        </ul>
			</div> <!-- #page-title -->
		</div> <!-- #head -->
		<div id="body-wrapper">
	    <div id="form-area">
            <div>
                <div>
                    <ul class="faq">
                        <li> <a href="#posting"><b>Posting</b></a>
                            <ul class="faq">
                                <li><a href="#posting1">What is the character limit for discussion title?</a></li>
                                <li><a href="#posting2">What is the character limit for discussion content?</a></li>
                                <li><a href="#posting3">What is the character limit for reply?</a></li>
                                <li><a href="#posting4">What is the maximum allowed size for an attachment?</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="faq-answers">
                    <div id="posting">
                        <p id="posting1"><h4>What is the character limit for discussion title?</h4>128 characters.</p>
                    </div>
                    <div id="posting">
                        <p id="posting2"><h4>What is the character limit for discussion content?</h4>4096 characters.</p>
                    </div>
                    <div id="posting">
                        <p id="posting3"><h4>What is the character limit for reply?</h4>2048 characters.</p>
                    </div>
                    <div id="posting">
                        <p id="posting4"><h4>What is the maximum allowed size for an attachment?</h4>12 mega-bytes (MB).</p>
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
