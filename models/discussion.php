<?php

/**
 *
 */
class discussion
{
  private $id;
  private $author_id;
  private $title;
  private $archived;
  private $full_text;
  private $creation_timestamp;
  private $board_id;
  private $board_title;
  private $board_icon;
  private $image_filename;


  function __construct()
  {
  }

  public function set_author_id($author_id) {
    $this->author_id = $author_id;
  }
  public function get_author_id() {
    return $this->author_id;
  }
  public function set_id($id) {
    $this->id = $id;
  }
  public function set_title($title) {
    $this->title = $title;
  }
  public function set_full_text($full_text) {
    $this->full_text = $full_text;
  }
  public function set_creation_timestamp($creation_timestamp) {
    $this->creation_timestamp = $creation_timestamp;
  }
  public function set_board_id($board_id) {
    $this->board_id = $board_id;
  }
  public function get_id() {
    return $this->id;
  }
  public function get_title() {
    return $this->title;
  }
  public function get_full_text() {
    return $this->full_text;
  }
  public function get_creation_timestamp() {
    return $this->creation_timestamp;
  }
  public function get_board_id() {
    return $this->board_id;
  }
  public function set_board_title($board_title) {
    $this->board_title = $board_title;
  }
  public function get_board_title() {
    return $this->board_title;
  }
  public function set_board_icon($board_icon) {
    $this->board_icon = $board_icon;
  }
  public function get_board_icon() {
    return $this->board_icon;
  }
  public function set_image_filename($image_filename) {
    $this->image_filename = $image_filename;
  }
  public function get_image_filename() {
    return $this->image_filename;
  }
  public function set_archived($archived) {
    $this->archived = $archived;
  }
  public function is_archived() {
    return $this->archived;
  }
  public static function with_row(& $row) {
    $ins = new self();
    $ins->set_id($row["id"]);
    $ins->set_title($row["title"]);
    $ins->set_full_text($row["full_text"]);
    $ins->set_creation_timestamp($row["creation_timestamp"]);
    $ins->set_board_id($row["board_id"]);
    return $ins;
  }
  public static function with_row_x(& $row) {
    $ins = new self();
    $ins->set_id($row["id"]);
    $ins->set_title($row["title"]);
    $ins->set_full_text($row["full_text"]);
    $ins->set_creation_timestamp($row["creation_timestamp"]);
    $ins->set_board_id($row["board_id"]);
    $ins->set_board_title($row["board_title"]);
    $ins->set_board_icon($row["fa_icon"]);
    $ins->set_image_filename($row["filename"]);
    $ins->set_archived((int)$row["archived"]);
    $ins->set_author_id((int)$row["author_id"]);
    return $ins;
  }
}

?>
