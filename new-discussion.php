<?php
try {
  $root = dirname(__FILE__);
  require "$root/includes/init.php";
  require "$root/includes/formatting.php";

  $categories = $da->get_categories();
  $categories_l = count($categories);
  switch($_SERVER['REQUEST_METHOD']) {
    case 'GET': break;
    case 'POST': require_once('includes/ph/new-discussion.php'); break;
    default: throw new invalid_request_method_error(__FILE__, __LINE__);
  }
  $category = (isset($_GET['c']) && $_GET['c']) ? strtolower($_GET['c']) : null;
  
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
  <title>wheel - New Discussion</title>
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
              New Discussion
          </div>
          <div class='card-body'>
            <form class='form' method='post' action='' enctype='multipart/form-data'>
              <div class='form-label'>Category:</div>
              <select name='dca'>
                <option value='0'>-- choose an appropriate category --</option>
                <?php
                foreach($categories as $cat) {
                  echo "<option value='{$cat['category_id']}' "
                    .   ($category && $category===strtolower($cat['category_name'])?'selected':'')
                    .  ">{$cat['category_name']}</option>";
                }
                ?>
              </select>
              <div class='form-label'>Title:</div>
              <input type='text' name='dt' placeholder='Give an appropriate title'>
              <div class='form-label'>Content:</div>
              <textarea name='dc' rows='12' placeholder='Give a short or broad description'></textarea>
              <div class='form-label'>Attachment <span class='sub-info'>(optional)</span>:</div>
              <input type='file' name='da'>
              <div class='form-label'>Tags:</div>
              <input type='text' name='dta' placeholder='comma separated tags, at least one. e.g: programming,python'>
              <input class='form-submit-button' type='submit' value='Add Discussion'>
            </form>
          </div>
        </div>
      </div>
    </div> <!-- .site-main-grid -->
  </div> <!-- .site-content -->
  <div class='site-footer'>
    <a href='/about.php'>About</a>
    <a href='/privacy.php'>Privacy</a>
    <br>
    &copy; 2018 wheel. 
    Timezone: <?php echo $_SESSION['timezone_name']; ?>,
    Country: <?php echo $_SESSION['country_name']; ?>
    (<a class='text-bold' href='/preferences.php'>Change</a>)
  </div> <!-- .site-footer -->
</body>
</html>
