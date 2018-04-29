<?php
try {
  $root = dirname(__FILE__);
  require_once("$root/includes/init.php");

  switch($_SERVER['REQUEST_METHOD']) {
    case 'POST': require("$root/includes/ph/register.php"); break;
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
  <title>wheel - Register</title>
  <link rel="icon" type="image/png" sizes="32x32" href="/images/favicons/32x32.png">
  <link rel="icon" type="image/png" sizes="96x96" href="/images/favicons/96x96.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/images/favicons/16x16.png">
  <link rel="shortcut icon" type="image/x-icon" href="/images/favicons/favicon.ico">
	<link href="/fonts/font-awesome/css/fontawesome-all.css" rel="stylesheet" type="text/css">
	<link href="/css/wheel_v2.css?v=<?php echo time();?>" rel="stylesheet" type="text/css">
  <script src='/js/wheel.js?v=<?php echo time();?>' type='text/javascript'></script>
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
        <div id='errors-section'>
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
        </div>
        <div class='card'>
          <div class='card-header'>
            User Registration
          </div>
          <div class='card-body'>
            <form class='form' action='' method='post' name='form'>
              <div class='grid'>
                <div class='grid-49'>
                  <div class='form-label'>Username:</div>
                  <input type='text' name='un' placeholder='Maximum length is 32 characters'>
                </div>
                <div class='grid-49'>
                  <div class='form-label'>Email Address:</div>
                  <input type='text' name='ue' placeholder='Valid email address'>
                </div>
              </div> <!-- .grid -->
              <div class='grid'>
                <div class='grid-49'>
                  <div class='form-label'>Password:</div>
                  <input type='password' name='us' placeholder='Minimum length is 8 characters'>
                </div>
                <div class='grid-49'>
                  <div class='form-label'>Confirm Password:</div>
                  <input type='password' name='cus' placeholder='Must match with Password'>
                </div>
              </div> <!-- .grid -->
              <div class='form-label'>Date of Birth:</div>
              <div class='grid'>
                <div class='grid-24'>
                  <select name='dob_d'>
                    <option value='0'>-- date --</option>
                    <option value='1'>1</option>
                    <option value='2'>2</option>
                    <option value='3'>3</option>
                    <option value='4'>4</option>
                    <option value='5'>5</option>
                    <option value='6'>6</option>
                    <option value='7'>7</option>
                    <option value='8'>8</option>
                    <option value='9'>9</option>
                    <option value='10'>10</option>
                    <option value='11'>11</option>
                    <option value='12'>12</option>
                    <option value='13'>13</option>
                    <option value='14'>14</option>
                    <option value='15'>15</option>
                    <option value='16'>16</option>
                    <option value='17'>17</option>
                    <option value='18'>18</option>
                    <option value='19'>19</option>
                    <option value='20'>20</option>
                    <option value='21'>21</option>
                    <option value='22'>22</option>
                    <option value='23'>23</option>
                    <option value='24'>24</option>
                    <option value='25'>25</option>
                    <option value='26'>26</option>
                    <option value='27'>27</option>
                    <option value='28'>28</option>
                    <option value='29'>29</option>
                    <option value='30'>30</option>
                    <option value='31'>31</option>
                  </select>
                </div>
                <div class='grid-48'>
                  <select name='dob_m'>
                    <option value='0'>-- month --</option>
                    <option value='1'>January</option>
                    <option value='2'>February</option>
                    <option value='3'>March</option>
                    <option value='4'>April</option>
                    <option value='5'>May</option>
                    <option value='6'>June</option>
                    <option value='7'>July</option>
                    <option value='8'>August</option>
                    <option value='9'>September</option>
                    <option value='10'>October</option>
                    <option value='11'>November</option>
                    <option value='12'>December</option>
                  </select>
                </div>
                <div class='grid-24'>
                  <select name='dob_y'>
                    <option value='0'>-- year --</option>
                    <?php
                    for ($i=1970; $i < 2018; $i++) { 
                      echo "<option value='$i'>$i</option>";
                    }
                    ?>
                  </select>
                </div>
              </div>
              <input class='form-submit-button' type='submit' value='Create Account' id='submit-button'>
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
<script type='text/javascript'>
(function() {
  ID('submit-button').disabled = true;
  document._errors = {
    u: true,
    e: true,
    p: true,
    cp: true,
    //dob: true
  };
  document.form.un.addEventListener('input', check_username);
  document.form.ue.addEventListener('input', check_email);
  document.form.us.addEventListener('input', check_password);
  document.form.cus.addEventListener('input', check_confirm_password);
  //document.form.dob_d.addEventListener('change', check_dob);
  //document.form.dob_m.addEventListener('change', check_dob);
  //document.form.dob_y.addEventListener('change', check_dob);
})();
</script>
</body>
</html>
