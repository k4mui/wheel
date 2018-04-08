<?php
$root = dirname(__FILE__);
require "$root/includes/init.php";
//require "$root/includes/classes/database.php";
//require "$root/includes/formatting.php";

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
  $error = "Invalid request!";
  include("error.php");
  die();
}

$da = data_access::get_instance();
$tags = $da->get_tags(24);
$categories = $da->get_categories(12);
$posts = $da->get_posts();

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
            <?php
            if (!isset($_SESSION['uid'])) {
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
        <div class='block-title'>Feed</div>
        <a class='feed-link selected' href=''>Top Discussions</a>
        <a class='feed-link' href=''>Latest Discussions</a>
      </div>
      <div class='col-middle'>
        <div class='block-title'>Discussions for you</div>
        <?php
          foreach($posts as $post) {
            echo "<div class='card-post-list'>
                    <div class='post-list-title'>
                      <a href='view-discussion.php?id={$post['post_id']}'>
                        <span>{$post['post_title']}</span>
                      </a>
                    </div>
                    <div class='text-mute'>Submitted 21 hours ago · <span class='text-bold'>No replies yet</span></div>
                    <div class='post-list-footer'>
                      <span class='badge badge-cat'>Category: <a class='text-bold' href=''>Books</a></span>
                      <a class='badge badge-tag' href=''>book</a>
                    </div>
                  </div>";
          }
          ?>
      </div>
      <div class='col-right'>
        <div class='card'>
          <div class='card-header'>Categories</div>
          <?php
          foreach($categories as $cat) {
            echo "<a class='category' href='/view-category.php?c={$cat['category_id']}'>{$cat['category_name']}</a>";
          }
          ?>
          <a class='category' href='/view-categories.php'><b>View all categories</b></a>
        </div> <!-- .card -->
        <div class='card'>
          <div class='card-header'>Tags</div>
          <div class='card-body'>
              <?php
              foreach($tags as $tag) {
                echo "<a class='badge badge-tag' href='/view-tag.php?t={$tag['tag']}'>{$tag['tag']}</a>";
              }
              ?>
              <a class='colored' href='/view-tags.php'><b>View all tags</b></a>
          </div>
        </div> <!-- .card -->
      </div>
    </div> <!-- .site-main-grid -->
  </div> <!-- .site-content -->
  <div class='site-footer'>
    <a href='About'>About</a>
    <a href='About'>Privacy</a>
    <br>
    &copy; 2018 wheel. Timezone: <?php echo $ud_timezone; ?>.
  </div> <!-- .site-footer -->
</body>
</html>
