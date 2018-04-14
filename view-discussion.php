<?php
$root = dirname(__FILE__);
require "$root/includes/init.php";
//require "$root/includes/classes/database.php";
require "$root/includes/formatting.php";
require_once("$root/includes/client.php");
$errors = array();

if ($_SERVER['REQUEST_METHOD']==='POST') {
    require_once('includes/ph/view-discussion.php');
} elseif ($_SERVER["REQUEST_METHOD"] !== "GET") {
  $error = "Invalid request!";
  include("error.php");
  die();
}
$did = isset($_GET['id']) ? (int)$_GET['id'] : null;
if ($did) {
    $da = data_access::get_instance();
    $discussion = $da->get_discussion($did);
    if (!$discussion) {
        die('Worng id');
    }
    $tags = $da->get_tags_by_post_id($did);
    $replies = $da->get_replies($did);
    $reply_count = count($replies);
} else {
    die('1');
}
$da->update_post_view(
  $did,
  isset($_SESSION['uid']) ? $_SESSION['uid'] : null,
  $_SESSION['cc'],
  $_SESSION['tz'],
  isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null,
  ip2long(get_client_ip())
);
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
      <div class='col-left'>
        <div class='block-title'>Related Discussions</div>
      </div>
      <div class='col-middle'>
        <div class='card'>
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
        <div class='block-title'>
          <?php
          if ($reply_count) {
            echo "$reply_count replies";
          } else {
            echo "No replies yet";
          }
          ?>
        </div>
        <?php
        foreach($replies as $reply) {
          echo "<div class='card' id='{$reply['post_id']}'>
                  <div class='card-header text-mute'>Reply by 
                  " . ($reply['author_id'] ? $reply['author_id'] : 'Guest') .
                  " · " . time_elapsed_string($reply['submitted_ts']) . "
                  [<a href='#{$reply['post_id']}' class='text-bold'>Permalink</a>]
                  </div>
                  <div class='card-body'>
                    <pre>{$reply['post_text']}</pre>
                  </div>
                </div>";
        }
        ?>
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
    &copy; 2018 wheel. Timezone: <?php echo $ud_timezone; ?>.
  </div> <!-- .site-footer -->
<script type='text/javascript'>
(function() {
  /*var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      var jsn = JSON.parse(this.responseText);
      console.log(jsn);
      console.log(this.responseText);
      //if (jsn.reply_count==0) {
          ID('replies-section').innerHTML = 'No replies';
      //} else {
        //ID('replies-section').innerHTML = jsn.reply_count + ' replies';
      //}
    }
  };
  xhttp.open("GET", "/api/v1/replies.php?id=<?php echo $did; ?>", true);
  xhttp.send();*/
  disable_submit(document.reply_form);
  document.reply_form.dc.addEventListener('input', check_reply);
})();
</script>
</body>
</html>
