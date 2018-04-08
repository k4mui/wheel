<?php
require_once(__DIR__.'/../validation.php');

$errors = array();

$dc = isset($_POST['dc']) ? $_POST['dc'] : null;
$dca = isset($_POST['dca']) ? (int)$_POST['dca'] : null; // category id
$dat = isset($_FILES['da']) ? $_FILES['da'] : null; // image
$pp = isset($_POST['pp']) ? $_POST['pp'] : null; // parent post id

check_discussion_attachment($dat, $errors);
check_discussion_content($dc, $errors);

if (!$errors) {
    $da = data_access::get_instance();
    $image_id = null;
    if (isset($dat['tmp_name']) && $dat['tmp_name']) {
        $image_id = $da->insert_image($dat);

        if ($image_id) {
            $post_id = $da->insert_post(null, $dc, $pp, null, $image_id, $dca);
            if ($post_id) {
                header('Location: http://192.140.254.221/');
                die();
            } else {
                $errors[] = 'Cannot insert post.';
            }
        } else {
            $errors[] = 'Cannot upload image.';
        }
    } else {
        $post_id = $da->insert_post(null, $dc, $pp, null, null, $dca);
        if ($post_id) {
            header('Location: http://192.140.254.221/');
            die();
        } else {
            $errors[] = 'Cannot insert post.';
        }
    }
    unset($da);
}
?>