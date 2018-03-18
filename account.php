<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require "$root/lib/init.php";
require "$root/lib/db.php";

if ($_SERVER['REQUEST_METHOD'] !== "GET") {
	die();
}
if (!$user->is_registered()) {
  $error = "You need to login first";
  include("404.php");
  die();
}
$da = new DataAccess;
$acc = $da->get_account_info($user->get_id());
//print_r($acc);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
                      "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta charset="utf-8">
  <title>wheel - My Account</title>
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
          <li><i class="fas fa-search"></i> <a href="/user.php?id=<?php echo -1; ?>">My Account</a></li>
        </ul>
			</div> <!-- #page-title -->
		</div> <!-- #head -->
		<div id="body-wrapper">
      <div id="boards-section">
        <div class="card-header">
          My Account
        </div>
        <div class="boards-item">
          <h3>Statistics</h3>
          <hr/>
          <div class="row acc-item">
            <div class="acc-iteml">Discussions Created:</div> <div class="acc-itemr"><?php echo $acc["discussion_count"]; ?></div>
          </div>
          <div class="row acc-item">
            <div class="acc-iteml">Replies Given:</div> <div class="acc-itemr"><?php echo $acc["reply_count"]; ?></div>
          </div>
          <div class="row acc-item">
            <div class="acc-iteml">Images Uploaded:</div> <div class="acc-itemr"><?php echo (int)$acc["image_count_r"] + (int)$acc["image_count_d"]; ?></div>
          </div>

          <br/>
          <h3>Informations</h3>
          <hr/>
          <div class="row acc-item">
            <div class="acc-iteml">Joined On:</div> <div class="acc-itemr"><?php echo $acc["joined_on"]; ?></div>
          </div>
          <div class="row acc-item">
            <div class="acc-iteml">Email Address:</div> <div class="acc-itemr"><?php echo $user->get_email_address(); ?></div>
          </div>
          <div class="row acc-item">
            <div class="acc-iteml">Password:</div> <div class="acc-itemr">&lt;secret&gt;</div>
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
