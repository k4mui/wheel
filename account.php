<?php
try {
  $root = dirname(__FILE__);
  require "$root/includes/init.php";
  require "$root/includes/formatting.php";

  switch($_SERVER['REQUEST_METHOD']) {
    case 'GET': break;
    default: throw new invalid_request_method_error(__FILE__, __LINE__);
  }

  if (!isset($_SESSION['user_id'])) {
    throw new forbidden_access_error(__FILE__, __LINE__);
  }

  if (isset($_GET['rbm'])) {
    $posts = $da->get_replies_by_user_id($_SESSION['user_id']);
    $list_header = 'Replies by Me';
    $active_link = 2;
  } elseif (isset($_GET['cs'])) {
    $list_header = 'Change Settings';
    $active_link = 3;
  } else {
    $posts = $da->get_discussions_by_user_id($_SESSION['user_id']);
    $list_header = 'Discussions Created by Me';
    $active_link = 1;
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
  <title>wheel - Home</title>
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
            <li><a href='/account.php'>Account</a></li>
            <li><a href='/logout.php'>Logout</a></li>
          </ul>
        </div>
      </div> <!-- .grid -->
    </div> <!-- .inner -->
  </div> <!-- .site-header -->
  <div class='site-content'>
    <div class='site-main-grid'>
      <div class='col-left'>
        <div class='block-title'>Account Menu</div>
        <a class='feed-link <?php echo ($active_link==1?'selected':''); ?>' href='/'>Discussions Created by Me</a>
        <a class='feed-link <?php echo ($active_link==2?'selected':''); ?>' href='/?rbm'>Replies by Me</a>
        <a class='feed-link <?php echo ($active_link==3?'selected':''); ?>' href='/?cs'>Change Settings</a>
      </div>
      <div class='col-middle'>
        <div class='block-title'><?php echo $list_header; ?></div>
        <?php
          if ($active_link==1) {
            if ($posts) {
              foreach($posts as $post) {
                echo "<div class='card-post-list'>
                        <div class='post-list-title'>
                          <a href='view-discussion.php?id={$post['post_id']}'>
                            <span>{$post['post_title']}</span>
                          </a>
                        </div>
                        <div class='text-mute'>Submitted " . time_elapsed_string($post['submitted_ts']) . 
                        " · by " . ($post['author_id']?'Someone' : 'Guest') .
                        " · <span class='text-bold' id='rc{$post['post_id']}'>-</span></div>
                        <div class='post-list-footer' id='post{$post['post_id']}'>
                          <span class='badge badge-cat'>Category: <a class='text-bold' href='view-category.php?id={$post['category_id']}'>{$post['category_name']}</a></span>
                        </div>
                      </div>";
              }
            } else {
              echo 'No discussions found.';
            }
          } else if ($active_link==3) {
            echo "";
          }
          ?>
      </div>
      <div class='col-right'>
        <div class='card'>
          <div class='card-header'>Categories</div>
          <?php
          foreach($categories as $cat) {
            echo "<a class='category' href='/view-category.php?id={$cat['category_id']}'>{$cat['category_name']}</a>";
          }
          ?>
          <a class='category' href='/view-categories.php'><b>View all categories</b></a>
        </div> <!-- .card -->
        <div class='card'>
          <div class='card-header'>Tags</div>
          <div class='card-body tags'>
              <?php
              foreach($tags as $tag) {
                echo "<a class='tag' href='/view-tag.php?t={$tag['tag']}'>{$tag['tag']}</a>";
              }
              ?>
          </div>
          <a class='category' href='/view-tags.php'><b>View all tags</b></a>
        </div> <!-- .card -->
      </div>
    </div> <!-- .site-main-grid -->
  </div> <!-- .site-content -->
  <div class='site-footer'>
    <a href='./about.php'>About</a>
    <a href='/privacy.php'>Privacy</a>
    <br>
    &copy; 2018 wheel.
    Timezone: <?php echo $_SESSION['timezone_name']; ?>,
    Country: <?php echo $_SESSION['country_name']; ?>
    (<a class='text-bold' href='/preferences.php'>Change</a>)
  </div> <!-- .site-footer -->
<script type='text/javascript'>
(function() {

})();
</script>
</body>
</html>
