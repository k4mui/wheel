<?php
try {
  $root = dirname(__FILE__);
  require_once("$root/includes/init.php");

  switch($_SERVER['REQUEST_METHOD']) {
    case 'POST': require("$root/includes/ph/login.php"); break;
    case 'GET': break;
    default: throw new invalid_request_method_error(__FILE__, __LINE__);
  }
} catch(Exception $e) {
  if (method_exists($e, 'process_error')) { // check if custom error
    $e->process_error();
  }
  error_log($e);
  die('Unexpected error occurred. Please try again in a few minutes.');
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
	<link href="/css/wheel_v2.css?v=<?php echo time();?>" rel="stylesheet" type="text/css">
</head>
<body>
<div class='site-header'>
    <div class='inner'>
      <div class='grid'>
        <div class='site-logo'>
          <a href='/'></a>
        </div> <!-- .site-logo -->
        <div class='site-search'>
          <form action='/search.php' method='get'>
            <input type='text' name='q' placeholder='Search'>
            <button type='submit'>
              <i class="fas fa-search"></i>
            </button>
          </form>
        </div>
        <div class='nav-menu'>
          <ul class='list list-inline'>
            <li><a href='new-discussion.php'>New Discussion</a></li>
            <?php
            if (!isset($_SESSION['user_id'])) {
              echo "<li><a href='/register.php'>Register</a></li>
                    <li><a href='/login.php'>Login</a></li>";
            } else {
              echo "<li><a href='/account.php'>Account</a></li>
                    <li><a href='/logout.php'>Logout</a></li>";
            }
            ?>
          </ul>
        </div>
      </div> <!-- .grid -->
    </div> <!-- .inner -->
  </div> <!-- .site-header -->
  <div class='site-content'>
    <div class='site-main-grid'>
      <div class='col-left'>
        <div class='blank'></div>
      </div>
      <div class='col-middle'>
        <?php
        if (isset($errors) && $errors) {
          echo "<div class='card card-error'>
                  <div class='card-body'>";
          foreach($errors as $e) {
            echo "<div class='list'>$e</div>";
          }
          echo '  </div>
                </div>';
        }
        ?>
        <div class='card'>
          <div class='card-header'>
              User Login
          </div>
          <div class='card-body'>
            <form class='form' action='' method='post'>
              <div class='form-label'>Username or Email Address:</div>
              <input type='text' name='ue' placeholder='Username or email address'>
              <div class='form-label'>Password:</div>
              <input type='password' name='us' placeholder='Password'>
              <div class='margin-top-md'>
                <input type='checkbox' name='rm'> Remember me
              </div>
              <input class='form-submit-button' type='submit' value='Login'>
            </form>
          </div>
        </div>
      </div>
    </div> <!-- .site-main-grid -->
  </div> <!-- .site-content -->
  <div class='site-footer'>
    <a href='About'>About</a>
    <a href='About'>Privacy</a>
    <br>
    &copy; 2018 wheel.
    Timezone: <?php echo $_SESSION['timezone_name']; ?>,
    Country: <?php echo $_SESSION['country_name']; ?>
    (<a class='text-bold' href=''>Change</a>)
  </div> <!-- .site-footer -->
</body>
</html>
