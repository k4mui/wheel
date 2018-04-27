<?php
try {
  $root = dirname(__FILE__);
  require "$root/includes/init.php";
  require "$root/includes/formatting.php";

  switch($_SERVER['REQUEST_METHOD']) {
    case 'GET': break;
    default: throw new invalid_request_method_error(__FILE__, __LINE__);
  }

  $category_id = isset($_GET['id']) ? (int)$_GET['id'] : null;
  if ($category_id) {
    $category = $da->get_category($category_id);
  } else {
    throw new page_not_found_error(__FILE__, __LINE__);
  }
  $tags = $da->get_tags(24);
  $categories = $da->get_categories(12);
  $posts = $da->get_discussions_by_category_id($category_id);
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
  <script src='/js/wheel.js' type='text/javascript'></script>
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
        <a class='feed-link <?php echo ($active_link==2?'selected':''); ?>' href='?rc'>Saved Discussions</a>
        <a class='feed-link <?php echo ($active_link==3?'selected':''); ?>' href='?tr'>Top Trending</a>
      </div>
      <div class='col-middle'>
        <div class='block-title'>Discussions on '<?php echo $category['category_name']; ?>' <span id='subscription-info'></span></div>
        <?php
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
    <a href='About'>About</a>
    <a href='About'>Privacy</a>
    <br>
    &copy; 2018 wheel.
    Timezone: <?php echo $_SESSION['timezone_name']; ?>,
    Country: <?php echo $_SESSION['country_name']; ?>
    (<a class='text-bold' href=''>Change</a>)
  </div> <!-- .site-footer -->
<script type='text/javascript'>
function subscription_info_handler() {
  if (this.readyState==4) {
    if (this.status==200) {
      var rjs = JSON.parse(this.responseText);
      if (rjs.error[0]) {
        //
      } else {
        if (rjs.data[0]) {
          inner_html('subscription-info', "(<a id='unsubscribe' href='#'>Unsubscribe</a>)");
        } else {
          inner_html('subscription-info', "(<a id='subscribe' href='#'>Subscribe</a>)");
        }
      }
    }
  }
}
function subscribe_handler() {
  if (this.readyState==4) {
    if (this.status==200) {
      var rjs = JSON.parse(this.responseText);
      if (rjs.error[0]) {
        //
      } else {
        if (rjs.data[0]) {
          inner_html('subscription-info', "(<a id='unsubscribe' href='#'>Unsubscribe</a>)");
        } else {
          //inner_html('is-saved', "(<a id='add-saved' href='#'>Add to saved</a>)");
        }
      }
    }
  }
}
function unsubscribe_handler() {
  if (this.readyState==4) {
    if (this.status==200) {
      var rjs = JSON.parse(this.responseText);
      if (rjs.error[0]) {
      } else {
        if (rjs.data[0]) {
          inner_html('subscription-info', "(<a id='subscribe' href='#'>Subscribe</a>)");
        } else {
          //inner_html('is-saved', "(<a id='remove-saved' href='#'>Remove from saved</a>)");
        }
      }
    }
  }
}

(function() {
  <?php
  if (isset($_SESSION['user_id'])) {
    echo "ajax('GET','/api/v1/is-subscribed.php?category_id=$category_id',subscription_info_handler);";
    echo "document.body.addEventListener('click', function(e) {
      if (e.srcElement.id=='subscribe') {
        ajax('GET','/api/v1/subscribe.php?category_id=$category_id',subscribe_handler);
        return true;
      }
      else if (e.srcElement.id=='unsubscribe') {
        ajax('GET','/api/v1/unsubscribe.php?category_id=$category_id',unsubscribe_handler);
        return true;
      }
    });";
  }
  ?>
})();
</script>
</body>
</html>
