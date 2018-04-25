<?php
try {
  $root = dirname(__FILE__);
  require("$root/includes/init.php");
  require("$root/includes/formatting.php");

  $discussion_id = null;
  $errors = array();

  switch($_SERVER['REQUEST_METHOD']) {
    case 'POST': require('includes/ph/view-discussion.php'); break;
    case 'GET': break;
    default: throw new invalid_request_method_error(__FILE__, __LINE__);
  }

  $discussion_id = isset($_GET['id']) ? (int)$_GET['id'] : null;
  if ($discussion_id) { // discussion id is valid integer
    $discussion = $da->get_discussion($discussion_id);
    $tags = $da->get_tags_by_discussion_id($discussion_id);
    $reply_count = 0;
  } else { // discussion id is invalid or not provided
    throw new page_not_found_error(__FILE__, __LINE__);
  }

  // update post views
  $da->update_post_view(
    $discussion_id,
    isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1,
    $_SESSION['country_id'],
    $_SESSION['timezone_id'],
    $_SESSION['client_id']
  );
} catch(Exception $e) {
  if (method_exists($e, 'process_error')) { // check if custom error
    $e->process_error();
  }
  error_log($e);
  die('Unexpected error occurred. Please try again in a few minutes.');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>wheel - Home</title>
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
      <div class='col-left' id='rd'>
        <div class='block-title'>Related Discussions</div>
      </div>
      <div class='col-middle'>
        <div class='card card-discussion'>
            <div class='card-header'>
              <h2><?php echo $discussion['post_title'] ?></h2>
              <span class='text-mute'>
                Submitted
                <?php
                echo time_elapsed_string($discussion['submitted_ts']);
                ?>
                · by
                <?php
                echo ($discussion['author_id'] ? 'Someone' : 'Guest');
                ?>
              </span>
            </div>
            <div class='card-body'>
                <?php 
                if ($discussion['attachment_name']) {
                    echo "<img class='image' src='/images/usercontents/{$discussion['attachment_name']}'>";
                }
                echo "<pre>{$discussion['post_text']}</pre>";
                ?>
            </div>
        </div>
        <div class='card'>
            <div class='card-header'>Reply</div>
            <div class='card-body'>
              <form class='form' name='reply_form' method='post' enctype='multipart/form-data' action=''>
                <textarea name='dc' rows='4' placeholder='Write something..'></textarea>
                <div class='form-label'>Attachment <span class='sub-info'>(optional)</span>:</div>
                <input type='file' name='da'>
                <input type='hidden' name='dca' value='<?php echo $discussion['category_id']; ?>'>
                <input type='hidden' name='pp' value='<?php echo $discussion['post_id']; ?>'>
                <input class='form-submit-button' type='submit' name='submit' value='Add Reply'>
              </form>
            </div>
        </div>
        <div id='reply_count' class='block-title'>
        </div>
        <div id='replies_section'></div>
      </div>
      <div class='col-right'>
        <div class='card'>
          <div class='card-header'>Category</div>
          <?php
          //foreach($categories as $cat) {
          //  echo "<a class='category' href='/view-category.php?c={$cat['category_id']}'>{$cat['category_name']}</a>";
          //}
          ?>
          <a class='category' href='/view-category.php?id=<?php echo $discussion['category_id']; ?>'>
            <?php
            echo $discussion['category_name'];
            ?>
          </a>
        </div> <!-- .card -->
        <div class='card'>
          <div class='card-header'>Tags</div>
          <div class='card-body tags'>
            <?php
            foreach($tags as $tag) {
                echo "<a class='tag' href='/view-tag?t={$tag['tag']}'>{$tag['tag']}</a>";
            }
            ?>
          </div> <!-- .card-body -->
        </div> <!-- .card -->
      </div>
    </div> <!-- .site-main-grid -->
  </div> <!-- .site-content -->
  <div class='site-footer'>
    <a href='About'>About</a>
    <a href='About'>Privacy</a>
    <br>
    &copy; 2018 wheel
    Timezone: <?php echo $_SESSION['timezone_name']; ?>,
    Country: <?php echo $_SESSION['country_name']; ?>
    (<a class='text-bold' href=''>Change</a>)
  </div> <!-- .site-footer -->
<script type='text/javascript'>
function readyStateHandler() {
  if (this.readyState == 3) {
    //inner_html('replies_section', 'Loading replies...');
  } else if (this.readyState == 4) {
    if (this.status == 200) {
      var jsn = JSON.parse(this.responseText);
      var jsnl = jsn.data.length;
      if (jsnl > 0) {
        inner_html('reply_count', jsnl + ' replies.');
        for(var i=0;i<jsnl;i++) {
          var dta = "<div class='card card-reply'>" +
                    "  <div class='card-header text-mute'> Reply by " +
                    jsn.data[i].author + " · " + jsn.data[i].time +
                    "  </div>" +
                    "  <div class='card-body'><pre>" +
                    jsn.data[i].post_text +
                    "  </pre></div>" +
                    "</div>";
          append_html('replies_section', dta);
        }
      } else {
        inner_html('reply_count', 'No replies yet.');
      }
    } else {

    }
  }
}

(function() {
  disable_submit(document.reply_form);
  document.reply_form.dc.addEventListener('input', check_reply);
  ajax('GET'
  ,    '/api/v1/replies.php?discussion_id=<?php echo $discussion_id; ?>'
  ,    readyStateHandler);
})();
</script>
</body>
</html>
