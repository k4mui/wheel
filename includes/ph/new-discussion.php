<?php
require_once(__DIR__.'/../validation.php');

$errors = array();

$dt = isset($_POST['dt']) ? $_POST['dt'] : null;
$dc = isset($_POST['dc']) ? $_POST['dc'] : null;
$dta = isset($_POST['dta']) ? $_POST['dta'] : null; // tags
$dca = isset($_POST['dca']) ? (int)$_POST['dca'] : null; // category id
$dat = isset($_FILES['da']) ? $_FILES['da'] : null; // image

$tags = explode(',', $dta);

if (!$dca || $dca > $categories_l) {
    $errors[] = 'Invalid category.';
}

check_discussion_attachment($dat, $errors);
check_discussion_tags($tags, $errors);
check_discussion_title($dt, $errors);
check_discussion_content($dc, $errors);
if (isset($_SESSION['user_id'])) {
    $uid = $_SESSION['user_id'];
} else {
    $uid = null;
}

if (!$errors) {
    $image_id = null;
    $tag_ids = $da->insert_tags($tags);
    if (isset($dat['tmp_name']) && $dat['tmp_name']) {
        $image_id = $da->insert_image($dat);
        print_r($tag_ids);
        if ($image_id) {
            $post_id = $da->insert_post($dt, $dc, null, $uid, $image_id, $dca);
            if ($post_id) {
                if ($da->insert_post_tags($post_id, $tag_ids)) {
                    header('Location: /index.php');
                } else {
                    $errors[] = 'Cannot insert tags.';
                }
            } else {
                $errors[] = 'Cannot insert post.';
            }
        } else {
            $errors[] = 'Cannot upload image.';
        }
    } else {
        $post_id = $da->insert_post($dt, $dc, null, null, null, $dca);
        if ($post_id) {
            if ($da->insert_post_tags($post_id, $tag_ids)) {
                header('Location: /index.php');
                die();
            } else {
                $errors[] = 'Cannot insert tags.';
            }
        } else {
            $errors[] = 'Cannot insert post.';
        }
    }
}
?>