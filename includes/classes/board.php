<?php
class board {
  private $fa_icon;
  private $id;
  private $locked;
  private $title;
  private $image_size;
  private $reply_count;
  private $discussion_count;
  private $image_count;
  private $rules;

  function __construct() {
    $this->locked = 0;
    $this->id = 0;
  }
  public static function with_row_sm(& $row) {
    if ($row) {
      $instance = new self();
      $instance->set_id((int)$row["id"]);
      $instance->set_icon($row["fa_icon"]);
      $instance->set_title($row["title"]);
      $instance->set_locked((int)$row["locked"]);
      return $instance;
    } else {
      return null;
    }
  }
  public static function with_row(& $row) {
    if ($row) {
      $instance = new self();
      $instance->set_id((int)$row["id"]);
      $instance->set_icon($row["fa_icon"]);
      $instance->set_title($row["title"]);
      $instance->set_locked((int)$row["locked"]);
      $instance->set_image_count($row["discussion_count"]+$row["image_count_r"]);
      $instance->set_image_size($row["image_size_d"]+$row["image_size_r"]);
      $instance->set_reply_count($row["reply_count"]);
      $instance->set_discussion_count($row["discussion_count"]);
      $instance->set_rules($row["full_text"]);
      return $instance;
    } else {
      return null;
    }
  }
  public function get_rules() {
    return $this->rules;
  }
  public function set_rules($r) {
    $this->rules = $r;
  }
  public function get_image_count() {
    return $this->image_count;
  }
  public function set_image_count($ic) {
    $this->image_count = number_format($ic);
  }
  public function get_image_size() {
    return $this->image_size;
  }
  public function set_image_size($is) {
    $this->image_size = $is;
  }
  public function get_reply_count() {
    return $this->reply_count;
  }
  public function set_reply_count($rc) {
    $this->reply_count = number_format($rc);
  }
  public function get_discussion_count() {
    return $this->discussion_count;
  }
  public function set_discussion_count($dc) {
    $this->discussion_count = (int)$dc;
  }
  public function get_icon() {
    return $this->fa_icon;
  }
  public function set_icon($icon) {
    $this->fa_icon = $icon;
  }
  public function get_id() {
    return $this->id;
  }
  public function set_id($id) {
    $this->id = $id;
  }
  public function is_locked() {
    return $this->locked;
  }
  public function set_locked($locked) {
    $this->locked = $locked;
  }
  public function get_title() {
    return $this->title;
  }
  public function set_title($title) {
    $this->title = $title;
  }
}
?>
