<?php
try {
  $root = dirname(__FILE__);
  require "$root/includes/init.php";
  require "$root/includes/formatting.php";

  switch($_SERVER['REQUEST_METHOD']) {
    case 'GET': break;
    default: throw new invalid_request_method_error(__FILE__, __LINE__);
  }


  $tags = $da->get_tags(24);
  $categories = $da->get_categories(12);
  if (isset($_SESSION['user_id'])) {
    $subscribed_categories = $da->get_subscribed_categories();
  }
  if (isset($_GET['tr'])) {
    //$posts = $da->get_trending_discussions();
    $list_header = 'Top Trending Discussions';
    $active_link = 3;
  } elseif (isset($_GET['sd'])) {
    if (isset($_SESSION['user_id'])) {
      $posts = $da->get_saved_discussions();
      $list_header = 'Saved Discussions';
      $active_link = 2;
    }
  } elseif (isset($_GET['dfu'])) {
    if (isset($_SESSION['user_id'])) {
      $posts = $da->get_relevant_discussions();
      $list_header = 'Discussions for You';
      $active_link = 4;
    }
  } else {
    $posts = $da->get_discussions();
    $list_header = 'Latest discussions';
    $active_link = 1;
  }
} catch(Exception $e) {
  if (method_exists($e, 'process_error')) { // check if custom error
    $e->process_error();
  }
  error_log($e);
  die('Unexpected error occurred. Please try again in a few minutes.');
}
//$timezones = $da->get_timezones();
//$countries = $da->get_countries();

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
        <div class='block-title'>Feed</div>
        <a class='feed-link <?php echo ($active_link==1?'selected':''); ?>' href='/'>Latest Discussions</a>
        <?php
        if (isset($_SESSION['user_id'])) {
          echo "<a class='feed-link " . ($active_link==4?'selected':'') . "' href='/?dfu'>Discussions for You</a>
                <a class='feed-link " . ($active_link==2?'selected':'') . "' href='/?sd'>Saved Discussions</a>";
        }
        ?>
        <a class='feed-link <?php echo ($active_link==3?'selected':''); ?>' href='/?tr'>Top Trending</a>
        <br>
        <div class='block-title'>Subscribed Categories</div>
        <?php
        if (isset($_SESSION['user_id'])) {
          if ($subscribed_categories) {
            foreach($subscribed_categories as $subscribed_category) {
              echo "<a class='feed-link' href='/view-category.php?id=${subscribed_category['category_id']}'>${subscribed_category['category_name']}</a>";
            }
          } else {
            echo "<div class='text-mute'>You haven't subscribed to any categories yet.</div>";
          }
        } else {
          echo "<div class='text-mute'><a href='/login.php'>Login</a> or <a href='/register.php'>Register</a> to subscribe to your favourite categories.</div>";
        }
        ?>
      </div>
      <div class='col-middle'>
        <div class='block-title'><?php echo $list_header; ?></div>
        <?php
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
                echo "<a class='tag' href='/view-tag.php?id={$tag['id']}'>{$tag['name']}</a>";
              }
              ?>
          </div>
          <a class='category' href='/view-tags.php'><b>View all tags</b></a>
        </div> <!-- .card -->
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
    (<a class='text-bold' href='/preferences.php'>Change</a>)
  </div> <!-- .site-footer -->
<script type='text/javascript'>
(function() {
  var post_ids = [
    <?php
    foreach($posts as $post) {
      echo $post['post_id'].',';
    }
    ?>
  ];
  function loadTags(post_id) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        var jsn = JSON.parse(this.responseText);
        //console.log(jsn);
        for(var key in jsn['data']) {
          document.getElementById('post'+post_id).innerHTML += `<a class='tag' href='view-tag.php?t=${jsn['data'][key]['tag']}'>${jsn['data'][key]['tag']}</a>`;
        }
      }
    };
    xhttp.open("GET", "/api/v1/get-tags.php?id="+post_id, true);
    xhttp.send();
  }
  function loadReplyCount(post_id) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        var jsn = JSON.parse(this.responseText);
        //console.log(jsn);
        var c = jsn['data'][0]['reply_count'];
        if (c>0) {
          document.getElementById('rc'+post_id).innerHTML = `${c} replies`;
        } else {
          document.getElementById('rc'+post_id).innerHTML = 'No replies yet';
        }
      }
    };
    xhttp.open("GET", "/api/v1/get-reply-count.php?id="+post_id, true);
    xhttp.send();
  }
  //for (let index = 0; index < post_ids.length; index++) {
  //  loadTags(post_ids[index]);
  //  loadReplyCount(post_ids[index]);
  //}

})();
</script>
</body>
</html>
