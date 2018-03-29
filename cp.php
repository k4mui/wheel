<?php
$root = dirname(__FILE__);
require "$root/includes/init.php";
require "$root/includes/classes/database.php";
require "$root/includes/formatting.php";

print_r($_POST);

$controls = array('users', 'boards');
$actions = array(
  'boards' => array('Title', 'Is Locked', 'Icon'),
  'users' => array('Email', 'Role','Account Status', 'Joined On', 'Moderation')
);
$error = null;
if (isset($_GET['c'])) {
  $control = $_GET['c'];
  if (!in_array($control, $controls)) {
    $error = 'Invalid control selection.';
  }
} else {
  $control = 'users';
}

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
  $error = 'Invalid request!';
} else if (!$user->is_admin()) {
  $error = 'You don\'t have enough privilige to view this page.';
}

if ($error) {
  include('error.php');
  die();
}

$da = data_access::get_instance();
if ($da === null) {
  include("error.php");
  die();
}
if ($control==='users') {
  $rows = $da->get_users_cp();
} else if ($control==='boards') {
  $rows = $da->get_boards_cp();
}

unset($da);
?>

<!DOCTYPE html>
<html lang="en-US">
<head>
	<meta charset="utf-8">
  <title>wheel - Home</title>
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
            echo "<li><i class='fas fa-user-secret'></i> <a href='inbox.php'>Admin Panel</a></li>
                  <li><i class='fas fa-user'></i> <a href='/account.php'>Account</a></li>
                  <li><i class='fas fa-sign-out-alt'></i> <a href='/logout.php'>Logout</a></li>";
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
			<div class="row">
        <div id="body-left" class="float-left">
          <div id="boards-section">
            <div class="card-header"><?php echo ucfirst($control); ?></div>
            <div class='regular-body'>
              <form action='' method='post'>
                <table class='table'>
                  <tbody>
                    <tr>
                      <th>Selection</th>
                      <th>Id</th>
                      <?php
                        foreach($actions[$control] as $k=>$v) {
                          echo "<th>{$v}</th>";
                        }
                      ?>
                    </tr>
                    <?php
                    foreach($rows as $k=>$v) {
                      echo "<tr>
                              <td><input type='radio' name='id' value='{$v['id']}'></td>
                              <td>{$v['id']}</td>";
                      foreach ($v as $x=>$y) {
                        if ($x!=='id') {
                          echo "<td>{$y}</td>";
                        }
                      }
                        echo "</tr>";
                    }
                    ?>
                  </tbody>
                </table>
                <input class='button bg-pomegranate' type='submit' name='submit' value='Remove'>
                <input class='button bg-magenta-purple' type='submit' name='submit' value='Edit'>
              </form>
            </div>
          </div> <!-- #boards-section -->
        </div>
        <div id="body-right">
          <div id="recent-posts-section">
            <div class="card-header">Controls</div>
            <div id="recent-posts">
              <a href="/cp.php?c=users">Users</a>
              <a href="/cp.php?c=boards">Boards</a>
            </div>
          </div> <!-- #recent-posts-section -->
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
