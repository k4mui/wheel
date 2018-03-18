<?php
class user
{
  private $id;
  private $role;

  function __construct()
  {
    $this->id = null; // not logged in
    $this->role = 0;
  }
  public function logout() {
    $this->id = null;
    $this->role = 0;
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
  public function is_mod() {
    return $this->role == 2;
  }
  public function is_registered() {
    return $this->id !== null;
  }
  public function update($user) {
    $this->set_id($user['id']);
    $this->set_role($user['role']);
  }
}

?>
