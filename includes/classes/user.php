<?php
class user {
  private $id;
  private $role;
  private $board_id;


  function __construct() {
    $this->id = null; // not logged in
    $this->role = 0;
  }

  public function logout() {
    $this->id = null;
    $this->role = 0;
  }

  public function get_board_id() {
    return $this->board_id;
  }

  public function set_board_id($bid) {
    $this->board_id = $bid;
  }
  
  public function get_id() {
    return $this->id;
  }

  public function set_id($id) {
    $this->id = $id;
  }

  public function get_role() {
    return $this->role;
  }

  public function set_role($role) {
    if ($role == 'normal') {
      $this->role = 1;
    } else if ($role == 'moderator') {
      $this->role = 2;
    } else {
      $this->role = 3;
    }
  }

  public function is_admin() {
    return $this->role == 3;
  }

  public function is_mod_of($board_id) {
    return $this->is_registered() && $this->board_id === $board_id;
  }

  public function is_same($author_id) {
    //return true;
    return $this->is_registered() && $this->id === $author_id;
  }

  public function is_registered() {
    return $this->id !== null;
  }

  public function update($user) {
    $this->set_id($user['id']);
    $this->set_role($user['role']);
    $this->set_board_id($user['board_id']);
  }
}
?>
